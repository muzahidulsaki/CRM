<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\Employee;
use App\Models\EmployeePayout;
use App\Models\Setting;
use App\Models\VideoBilling;
use Illuminate\Support\Facades\DB;

class BillingService
{
    /**
     * Generate unique sequential Video Billing Code: VB-YYYY-XXXXX
     */
    public static function generateBillingCode(): string
    {
        $year = date('Y');
        $prefix = "VB-{$year}-";

        $last = VideoBilling::withTrashed()
            ->where('billing_code', 'like', "{$prefix}%")
            ->orderBy('id', 'desc')
            ->first();

        $sequence = 1;
        if ($last && preg_match('/VB-\d{4}-(\d+)/', $last->billing_code, $matches)) {
            $sequence = intval($matches[1]) + 1;
        }

        return sprintf('%s%05d', $prefix, $sequence);
    }

    /**
     * Generate unique sequential Payout Voucher No: PAY-YYYY-XXXXX
     */
    public static function generateVoucherNo(): string
    {
        $year = date('Y');
        $prefix = "PAY-{$year}-";

        $last = EmployeePayout::withTrashed()
            ->where('voucher_no', 'like', "{$prefix}%")
            ->orderBy('id', 'desc')
            ->first();

        $sequence = 1;
        if ($last && preg_match('/PAY-\d{4}-(\d+)/', $last->voucher_no, $matches)) {
            $sequence = intval($matches[1]) + 1;
        }

        return sprintf('%s%05d', $prefix, $sequence);
    }

    /**
     * Calculate video duration in total seconds and payable amount
     */
    public static function calculateVideoPayable(int $minutes, int $seconds, float $ratePerSecond, int $numberOfVideos = 1): array
    {
        $totalSeconds = ($minutes * 60) + $seconds;
        $numberOfVideos = max(1, $numberOfVideos);
        $totalPayable = round($totalSeconds * $ratePerSecond * $numberOfVideos, 2);

        return [
            'total_seconds' => $totalSeconds,
            'rate_per_second' => $ratePerSecond,
            'number_of_videos' => $numberOfVideos,
            'total_payable' => $totalPayable,
        ];
    }

    /**
     * Create a new Video Billing entry
     */
    public static function createVideoBilling(array $data, int $userId): VideoBilling
    {
        $mins = intval($data['duration_minutes'] ?? 0);
        $secs = intval($data['duration_seconds'] ?? 0);
        $employee = Employee::findOrFail($data['employee_id']);

        // Default rate from employee profile or global settings
        $rate = floatval($data['rate_per_second'] ?? ($employee->salary_or_rate ?: Setting::get('default_video_rate_per_second', 8.00)));
        $numVideos = intval($data['number_of_videos'] ?? 1);

        $calc = self::calculateVideoPayable($mins, $secs, $rate, $numVideos);
        $billingCode = self::generateBillingCode();

        $billing = VideoBilling::create([
            'billing_code' => $billingCode,
            'employee_id' => $employee->id,
            'project_id' => $data['project_id'] ?? null,
            'task_id' => $data['task_id'] ?? null,
            'customer_id' => $data['customer_id'] ?? null,
            'video_title' => $data['video_title'],
            'video_url' => $data['video_url'] ?? null,
            'duration_minutes' => $mins,
            'duration_seconds' => $secs,
            'total_seconds' => $calc['total_seconds'],
            'rate_per_second' => $rate,
            'number_of_videos' => $numVideos,
            'total_payable' => $calc['total_payable'],
            'paid_amount' => 0.00,
            'due_amount' => $calc['total_payable'],
            'work_start_date' => $data['work_start_date'] ?? null,
            'work_end_date' => $data['work_end_date'] ?? now()->format('Y-m-d'),
            'status' => $data['status'] ?? 'Submitted',
            'payment_status' => 'Unpaid',
            'notes' => $data['notes'] ?? null,
            'created_by' => $userId,
        ]);

        ActivityLog::log(
            action: 'video_billing_created',
            description: "Video billing created: {$billing->billing_code} ({$billing->video_title}, BDT {$billing->total_payable})",
            subject: $billing,
            userId: $userId
        );

        return $billing;
    }

    /**
     * Approve Video Billing
     */
    public static function approveVideoBilling(VideoBilling $billing, int $approverId): VideoBilling
    {
        $billing->update([
            'status' => 'Approved',
            'approved_by' => $approverId,
            'approved_at' => now(),
            'due_amount' => max(0, $billing->total_payable - $billing->paid_amount),
        ]);

        ActivityLog::log(
            action: 'video_billing_approved',
            description: "Video billing approved: {$billing->billing_code} (BDT {$billing->total_payable})",
            subject: $billing,
            userId: $approverId
        );

        return $billing;
    }

    /**
     * Create Employee Payout & settle linked billings if provided
     */
    public static function createPayout(array $data, int $userId): EmployeePayout
    {
        return DB::transaction(function () use ($data, $userId) {
            $voucherNo = self::generateVoucherNo();
            $baseAmount = floatval($data['base_amount'] ?? 0);
            $bonusAmount = floatval($data['bonus_amount'] ?? 0);
            $deductionAmount = floatval($data['deduction_amount'] ?? 0);
            $netAmount = max(0, $baseAmount + $bonusAmount - $deductionAmount);
            $paidAmount = floatval($data['paid_amount'] ?? $netAmount);

            $payout = EmployeePayout::create([
                'voucher_no' => $voucherNo,
                'employee_id' => $data['employee_id'],
                'payout_type' => $data['payout_type'] ?? 'video_billing',
                'salary_month' => $data['salary_month'] ?? null,
                'base_amount' => $baseAmount,
                'bonus_amount' => $bonusAmount,
                'deduction_amount' => $deductionAmount,
                'net_amount' => $netAmount,
                'paid_amount' => $paidAmount,
                'payment_method' => $data['payment_method'] ?? 'bKash',
                'account_details' => $data['account_details'] ?? null,
                'transaction_reference' => $data['transaction_reference'] ?? null,
                'payment_date' => $data['payment_date'] ?? now()->format('Y-m-d'),
                'status' => 'Paid',
                'linked_video_billing_ids' => $data['linked_video_billing_ids'] ?? null,
                'notes' => $data['notes'] ?? null,
                'created_by' => $userId,
            ]);

            // Settle linked video billings if applicable
            if (!empty($data['linked_video_billing_ids'])) {
                $billings = VideoBilling::whereIn('id', $data['linked_video_billing_ids'])->get();
                $remainingPayment = $paidAmount;

                foreach ($billings as $b) {
                    if ($remainingPayment <= 0) {
                        break;
                    }

                    $due = $b->due_amount;
                    if ($remainingPayment >= $due) {
                        $b->paid_amount += $due;
                        $b->due_amount = 0;
                        $b->payment_status = 'Paid';
                        $remainingPayment -= $due;
                    } else {
                        $b->paid_amount += $remainingPayment;
                        $b->due_amount -= $remainingPayment;
                        $b->payment_status = 'Partially Paid';
                        $remainingPayment = 0;
                    }
                    $b->save();
                }
            }

            ActivityLog::log(
                action: 'employee_payout_created',
                description: "Employee payout voucher created: {$payout->voucher_no} (BDT {$payout->paid_amount}, Method: {$payout->payment_method})",
                subject: $payout,
                userId: $userId
            );

            return $payout;
        });
    }

    /**
     * Summary Metrics for Dashboard & Reports
     */
    public static function getMetrics(): array
    {
        return [
            'total_billings' => VideoBilling::count(),
            'approved_billings' => VideoBilling::where('status', 'Approved')->count(),
            'total_video_seconds' => VideoBilling::where('status', 'Approved')->sum('total_seconds'),
            'total_payable_amount' => VideoBilling::where('status', 'Approved')->sum('total_payable'),
            'total_paid_to_editors' => VideoBilling::sum('paid_amount'),
            'total_editor_due' => VideoBilling::where('status', 'Approved')->sum('due_amount'),
            'total_payouts_all' => EmployeePayout::where('status', 'Paid')->sum('paid_amount'),
        ];
    }
}
