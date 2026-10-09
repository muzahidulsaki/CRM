<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\ClientPayment;
use App\Models\EmployeePayout;
use App\Models\Expense;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Order;
use App\Models\ServiceRenewal;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class FinanceService
{
    /**
     * Generate sequential code with year prefix
     */
    public function generateCode(string $prefix, string $modelClass, string $column = 'code'): string
    {
        $year = date('Y');
        $fullPrefix = "{$prefix}-{$year}-";

        $latest = $modelClass::where($column, 'like', "{$fullPrefix}%")
            ->orderBy($column, 'desc')
            ->first();

        if ($latest) {
            $lastNumber = (int) substr($latest->$column, strlen($fullPrefix));
            $nextNumber = str_pad($lastNumber + 1, 5, '0', STR_PAD_LEFT);
        } else {
            $nextNumber = '00001';
        }

        return $fullPrefix . $nextNumber;
    }

    public function generateInvoiceNo(): string
    {
        return $this->generateCode('INV', Invoice::class, 'invoice_no');
    }

    public function generatePaymentNo(): string
    {
        return $this->generateCode('RCP', ClientPayment::class, 'payment_no');
    }

    public function generateRenewalNo(): string
    {
        return $this->generateCode('REN', ServiceRenewal::class, 'renewal_no');
    }

    public function generateExpenseNo(): string
    {
        return $this->generateCode('EXP', Expense::class, 'expense_no');
    }

    /**
     * Create Invoice with itemized lines and auto-totals
     */
    public function createInvoice(array $data, array $items): Invoice
    {
        return DB::transaction(function () use ($data, $items) {
            $invoiceNo = $this->generateInvoiceNo();

            // Calculate Subtotal from items
            $subtotal = 0;
            $formattedItems = [];

            foreach ($items as $item) {
                $qty = max(0.01, floatval($item['quantity'] ?? 1));
                $price = max(0, floatval($item['unit_price'] ?? 0));
                $total = $qty * $price;
                $subtotal += $total;

                $formattedItems[] = [
                    'service_category' => $item['service_category'] ?? null,
                    'item_description' => $item['item_description'],
                    'quantity' => $qty,
                    'unit_price' => $price,
                    'total_price' => $total,
                ];
            }

            // Discount calculation
            $discountType = $data['discount_type'] ?? 'fixed';
            $discountValue = max(0, floatval($data['discount_value'] ?? 0));
            $discountAmount = ($discountType === 'percentage')
                ? ($subtotal * ($discountValue / 100))
                : $discountValue;
            $discountAmount = min($subtotal, $discountAmount);

            $taxable = $subtotal - $discountAmount;

            // Tax calculation
            $taxPercentage = max(0, floatval($data['tax_percentage'] ?? 0));
            $taxAmount = $taxable * ($taxPercentage / 100);

            // Total amount
            $totalAmount = $taxable + $taxAmount;

            $invoice = Invoice::create([
                'invoice_no' => $invoiceNo,
                'customer_id' => $data['customer_id'],
                'order_id' => $data['order_id'] ?? null,
                'project_id' => $data['project_id'] ?? null,
                'issue_date' => $data['issue_date'] ?? now()->toDateString(),
                'due_date' => $data['due_date'] ?? now()->addDays(15)->toDateString(),
                'subtotal' => $subtotal,
                'discount_type' => $discountType,
                'discount_value' => $discountValue,
                'discount_amount' => $discountAmount,
                'tax_percentage' => $taxPercentage,
                'tax_amount' => $taxAmount,
                'total_amount' => $totalAmount,
                'paid_amount' => 0,
                'due_amount' => $totalAmount,
                'status' => $data['status'] ?? 'Sent',
                'notes' => $data['notes'] ?? null,
                'terms' => $data['terms'] ?? 'Payment due within 15 days of issue date. Late payments subject to 2% penalty.',
                'created_by' => auth()->id(),
            ]);

            foreach ($formattedItems as $fItem) {
                $invoice->items()->create($fItem);
            }

            ActivityLog::log(
                action: 'created',
                module: 'finance',
                description: "Generated Invoice #{$invoice->invoice_no} for Total ৳{$invoice->total_amount}",
                record: $invoice
            );

            return $invoice;
        });
    }

    /**
     * Record client collection & auto-settle invoice and order
     */
    public function recordClientPayment(array $data, ?UploadedFile $attachment = null): ClientPayment
    {
        return DB::transaction(function () use ($data, $attachment) {
            $paymentNo = $this->generatePaymentNo();
            $amount = floatval($data['amount']);

            $attachmentPath = null;
            if ($attachment) {
                $attachmentPath = $attachment->store('receipts', 'public');
            }

            $payment = ClientPayment::create([
                'payment_no' => $paymentNo,
                'invoice_id' => $data['invoice_id'] ?? null,
                'order_id' => $data['order_id'] ?? null,
                'customer_id' => $data['customer_id'],
                'amount' => $amount,
                'payment_date' => $data['payment_date'] ?? now()->toDateString(),
                'payment_method' => $data['payment_method'] ?? 'Bank Transfer',
                'transaction_reference' => $data['transaction_reference'] ?? null,
                'received_by_id' => auth()->id() ?? ($data['received_by_id'] ?? null),
                'notes' => $data['notes'] ?? null,
                'attachment_path' => $attachmentPath,
            ]);

            // Settle Invoice if linked
            if (!empty($data['invoice_id'])) {
                $invoice = Invoice::find($data['invoice_id']);
                if ($invoice) {
                    $newPaid = $invoice->paid_amount + $amount;
                    $newDue = max(0, $invoice->total_amount - $newPaid);
                    $newStatus = ($newDue <= 0) ? 'Paid' : 'Partially Paid';

                    $invoice->update([
                        'paid_amount' => $newPaid,
                        'due_amount' => $newDue,
                        'status' => $newStatus,
                    ]);
                }
            }

            // Settle Order if linked
            if (!empty($data['order_id'])) {
                $order = Order::find($data['order_id']);
                if ($order) {
                    $newPaid = $order->paid_amount + $amount;
                    $newDue = max(0, $order->total_amount - $newPaid);
                    $order->update([
                        'paid_amount' => $newPaid,
                        'due_amount' => $newDue,
                    ]);
                }
            }

            ActivityLog::log(
                action: 'payment_received',
                module: 'finance',
                description: "Received payment ৳{$payment->amount} (Receipt: {$payment->payment_no})",
                record: $payment
            );

            return $payment;
        });
    }

    /**
     * Create Service Renewal tracker
     */
    public function createServiceRenewal(array $data): ServiceRenewal
    {
        $renewalNo = $this->generateRenewalNo();

        $renewal = ServiceRenewal::create([
            'renewal_no' => $renewalNo,
            'customer_id' => $data['customer_id'],
            'project_id' => $data['project_id'] ?? null,
            'service_name' => $data['service_name'],
            'category' => $data['category'] ?? 'Domain',
            'billing_cycle' => $data['billing_cycle'] ?? 'Yearly',
            'renewal_fee' => floatval($data['renewal_fee']),
            'last_payment_date' => $data['last_payment_date'] ?? null,
            'next_due_date' => $data['next_due_date'],
            'reminder_days' => intval($data['reminder_days'] ?? 7),
            'status' => 'Active',
            'auto_renew' => isset($data['auto_renew']) ? (bool) $data['auto_renew'] : true,
            'notes' => $data['notes'] ?? null,
        ]);

        ActivityLog::log(
            action: 'created',
            module: 'finance',
            description: "Registered Service Renewal #{$renewal->renewal_no} ({$renewal->service_name})",
            record: $renewal
        );

        return $renewal;
    }

    /**
     * Process renewal payment and advance next_due_date according to cycle
     */
    public function processRenewalPayment(
        ServiceRenewal $renewal,
        float $paidAmount,
        string $paymentMethod = 'Bank Transfer',
        ?string $reference = null
    ): ServiceRenewal {
        return DB::transaction(function () use ($renewal, $paidAmount, $paymentMethod, $reference) {
            $currentDue = Carbon::parse($renewal->next_due_date);

            // Compute next due date by billing cycle
            $nextDueDate = match($renewal->billing_cycle) {
                'Monthly' => $currentDue->copy()->addMonth(),
                'Quarterly' => $currentDue->copy()->addMonths(3),
                'Half-Yearly' => $currentDue->copy()->addMonths(6),
                'Yearly' => $currentDue->copy()->addYear(),
                'Biennial' => $currentDue->copy()->addYears(2),
                default => $currentDue->copy()->addYear(),
            };

            $renewal->update([
                'last_payment_date' => now()->toDateString(),
                'next_due_date' => $nextDueDate->toDateString(),
                'status' => 'Active',
            ]);

            // Create client collection money receipt
            $this->recordClientPayment([
                'customer_id' => $renewal->customer_id,
                'amount' => $paidAmount,
                'payment_date' => now()->toDateString(),
                'payment_method' => $paymentMethod,
                'transaction_reference' => $reference,
                'notes' => "Service Renewal: {$renewal->service_name} (Cycle: {$renewal->billing_cycle})",
            ]);

            ActivityLog::log(
                action: 'renewal_processed',
                module: 'finance',
                description: "Renewed {$renewal->service_name}. Next due: {$nextDueDate->format('d M, Y')}",
                record: $renewal
            );

            return $renewal->fresh();
        });
    }

    /**
     * Create business expense
     */
    public function createExpense(array $data, ?UploadedFile $attachment = null): Expense
    {
        $expenseNo = $this->generateExpenseNo();

        $attachmentPath = null;
        if ($attachment) {
            $attachmentPath = $attachment->store('expenses', 'public');
        }

        $expense = Expense::create([
            'expense_no' => $expenseNo,
            'category' => $data['category'] ?? 'Other',
            'title' => $data['title'],
            'amount' => floatval($data['amount']),
            'expense_date' => $data['expense_date'] ?? now()->toDateString(),
            'payment_method' => $data['payment_method'] ?? 'Cash',
            'paid_to' => $data['paid_to'] ?? null,
            'paid_by_id' => auth()->id() ?? ($data['paid_by_id'] ?? null),
            'is_recurring' => isset($data['is_recurring']) ? (bool)$data['is_recurring'] : false,
            'recurring_frequency' => $data['recurring_frequency'] ?? null,
            'due_date' => $data['due_date'] ?? null,
            'status' => $data['status'] ?? 'Paid',
            'attachment_path' => $attachmentPath,
            'notes' => $data['notes'] ?? null,
        ]);

        ActivityLog::log(
            action: 'expense_logged',
            module: 'finance',
            description: "Recorded Expense #{$expense->expense_no}: {$expense->title} (৳{$expense->amount})",
            record: $expense
        );

        return $expense;
    }

    /**
     * Financial Ledger & Cashflow Analytics (SRS Section 25 & 26)
     */
    public function getFinancialSummary(?string $startDate = null, ?string $endDate = null): array
    {
        $clientPaymentsQuery = ClientPayment::query();
        $expensesQuery = Expense::query()->where('status', 'Paid');
        $employeePayoutsQuery = EmployeePayout::query();

        if ($startDate && $endDate) {
            $clientPaymentsQuery->whereBetween('payment_date', [$startDate, $endDate]);
            $expensesQuery->whereBetween('expense_date', [$startDate, $endDate]);
            $employeePayoutsQuery->whereBetween('payment_date', [$startDate, $endDate]);
        }

        $totalClientIncome = (float) $clientPaymentsQuery->sum('amount');
        $totalOfficeExpenses = (float) $expensesQuery->sum('amount');
        $totalEmployeePayouts = (float) $employeePayoutsQuery->sum('paid_amount');

        $totalInflow = $totalClientIncome;
        $totalOutflow = $totalOfficeExpenses + $totalEmployeePayouts;
        $netProfit = $totalInflow - $totalOutflow;

        $totalReceivableDue = (float) Invoice::sum('due_amount');
        $overdueInvoicesCount = Invoice::where('status', 'Overdue')->count();

        // Renewals alerts count
        $today = now()->toDateString();
        $upcomingLimit = now()->addDays(7)->toDateString();
        $dueTodayRenewals = ServiceRenewal::where('next_due_date', $today)->whereNotIn('status', ['Suspended', 'Cancelled'])->count();
        $overdueRenewals = ServiceRenewal::where('next_due_date', '<', $today)->whereNotIn('status', ['Suspended', 'Cancelled'])->count();
        $upcomingRenewals = ServiceRenewal::whereBetween('next_due_date', [$today, $upcomingLimit])->whereNotIn('status', ['Suspended', 'Cancelled'])->count();

        return [
            'total_inflow' => $totalInflow,
            'total_client_income' => $totalClientIncome,
            'total_outflow' => $totalOutflow,
            'total_office_expenses' => $totalOfficeExpenses,
            'total_employee_payouts' => $totalEmployeePayouts,
            'net_profit' => $netProfit,
            'total_receivable_due' => $totalReceivableDue,
            'overdue_invoices_count' => $overdueInvoicesCount,
            'renewals_alert' => [
                'due_today' => $dueTodayRenewals,
                'overdue' => $overdueRenewals,
                'upcoming' => $upcomingRenewals,
                'total_alerts' => $dueTodayRenewals + $overdueRenewals + $upcomingRenewals,
            ],
        ];
    }
}
