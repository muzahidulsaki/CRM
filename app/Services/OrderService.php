<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\Order;
use App\Models\Project;
use App\Models\Quotation;
use App\Models\Task;
use Illuminate\Support\Facades\DB;

class OrderService
{
    /**
     * Generate unique sequential Order Code: ORD-YYYY-XXXXX
     */
    public static function generateOrderCode(): string
    {
        $year = date('Y');
        $prefix = "ORD-{$year}-";

        $last = Order::withTrashed()
            ->where('order_code', 'like', "{$prefix}%")
            ->orderBy('id', 'desc')
            ->first();

        $sequence = 1;
        if ($last && preg_match('/ORD-\d{4}-(\d+)/', $last->order_code, $matches)) {
            $sequence = intval($matches[1]) + 1;
        }

        return sprintf('%s%05d', $prefix, $sequence);
    }

    /**
     * Generate unique sequential Project Code: PRJ-YYYY-XXXXX
     */
    public static function generateProjectCode(): string
    {
        $year = date('Y');
        $prefix = "PRJ-{$year}-";

        $last = Project::withTrashed()
            ->where('project_code', 'like', "{$prefix}%")
            ->orderBy('id', 'desc')
            ->first();

        $sequence = 1;
        if ($last && preg_match('/PRJ-\d{4}-(\d+)/', $last->project_code, $matches)) {
            $sequence = intval($matches[1]) + 1;
        }

        return sprintf('%s%05d', $prefix, $sequence);
    }

    /**
     * Generate unique sequential Task Code: TSK-YYYY-XXXXX
     */
    public static function generateTaskCode(): string
    {
        $year = date('Y');
        $prefix = "TSK-{$year}-";

        $last = Task::withTrashed()
            ->where('task_code', 'like', "{$prefix}%")
            ->orderBy('id', 'desc')
            ->first();

        $sequence = 1;
        if ($last && preg_match('/TSK-\d{4}-(\d+)/', $last->task_code, $matches)) {
            $sequence = intval($matches[1]) + 1;
        }

        return sprintf('%s%05d', $prefix, $sequence);
    }

    /**
     * Create an Order from an accepted Quotation
     */
    public static function createOrderFromQuotation(Quotation $quotation, array $additionalData = []): Order
    {
        return DB::transaction(function () use ($quotation, $additionalData) {
            // Ensure customer exists
            $customerId = $quotation->customer_id;
            if (!$customerId && $quotation->lead) {
                $customer = LeadService::convertToCustomer($quotation->lead);
                $customerId = $customer->id;
            }

            $orderCode = self::generateOrderCode();
            $firstItem = $quotation->items->first();
            $category = $firstItem?->service_category ?? 'Software Development';
            $title = $firstItem?->description ?? "Order from {$quotation->quotation_code}";

            $totalAmount = floatval($additionalData['total_amount'] ?? ($quotation->total_amount ?: 0));
            $paidAmount = floatval($additionalData['paid_amount'] ?? 0);
            $dueAmount = max(0, $totalAmount - $paidAmount);

            $order = Order::create([
                'order_code' => $orderCode,
                'customer_id' => $customerId,
                'quotation_id' => $quotation->id,
                'lead_id' => $quotation->lead_id,
                'sales_person_id' => $quotation->created_by,
                'project_manager_id' => $additionalData['project_manager_id'] ?? null,
                'service_category' => $additionalData['service_category'] ?? $category,
                'title' => $additionalData['title'] ?? $title,
                'description' => $additionalData['description'] ?? $quotation->notes,
                'total_amount' => $totalAmount,
                'paid_amount' => $paidAmount,
                'due_amount' => $dueAmount,
                'payment_terms' => $quotation->payment_terms,
                'order_date' => $additionalData['order_date'] ?? now()->format('Y-m-d'),
                'expected_delivery_date' => $additionalData['expected_delivery_date'] ?? $quotation->valid_until,
                'status' => 'Confirmed',
                'notes' => $additionalData['notes'] ?? null,
            ]);

            // Update quotation converted date
            $quotation->update([
                'status' => 'Accepted',
                'converted_to_order_at' => now(),
            ]);

            ActivityLog::log(
                action: 'order_created_from_quotation',
                description: "কোটেশন {$quotation->quotation_code} থেকে অর্ডার {$order->order_code} তৈরি করা হয়েছে।",
                subject: $order,
                userId: auth()->id()
            );

            // Auto create Project if requested
            if (!empty($additionalData['create_project'])) {
                self::createProjectFromOrder($order, $additionalData['project_manager_id'] ?? null);
            }

            return $order;
        });
    }

    /**
     * Create Project directly from Order
     */
    public static function createProjectFromOrder(Order $order, ?int $pmId = null, array $projectData = []): Project
    {
        return DB::transaction(function () use ($order, $pmId, $projectData) {
            $projectCode = self::generateProjectCode();

            $project = Project::create([
                'project_code' => $projectCode,
                'order_id' => $order->id,
                'customer_id' => $order->customer_id,
                'project_manager_id' => $pmId ?? $order->project_manager_id ?? auth()->id(),
                'name' => $projectData['name'] ?? $order->title,
                'category' => $projectData['category'] ?? $order->service_category,
                'description' => $projectData['description'] ?? $order->description,
                'start_date' => $projectData['start_date'] ?? $order->start_date ?? now()->format('Y-m-d'),
                'deadline' => $projectData['deadline'] ?? $order->expected_delivery_date,
                'status' => 'Planning',
                'progress_percent' => 0,
                'notes' => $projectData['notes'] ?? null,
            ]);

            // Update order status
            $order->update([
                'status' => 'Project Started',
                'project_manager_id' => $project->project_manager_id,
            ]);

            ActivityLog::log(
                action: 'project_created_from_order',
                description: "অর্ডার {$order->order_code} থেকে প্রজেক্ট {$project->project_code} ({$project->name}) তৈরি হয়েছে।",
                subject: $project,
                userId: auth()->id()
            );

            return $project;
        });
    }

    /**
     * Synchronize Project Progress % from tasks
     */
    public static function syncProjectProgress(Project $project): int
    {
        $totalTasks = $project->tasks()->count();
        if ($totalTasks === 0) {
            return $project->progress_percent;
        }

        // Weight: Delivered/Checked = 100%, Completed = 80%, Working/Reworking = 40%, Pending = 0%
        $completedTasks = $project->tasks()->whereIn('status', ['Checked', 'Delivered'])->count();
        $inReviewTasks = $project->tasks()->where('status', 'Completed')->count();
        $workingTasks = $project->tasks()->whereIn('status', ['Working', 'Reworking'])->count();

        $calculatedPercent = round((($completedTasks * 1.0) + ($inReviewTasks * 0.8) + ($workingTasks * 0.4)) / $totalTasks * 100);
        $percent = min(100, max(0, intval($calculatedPercent)));

        $project->update(['progress_percent' => $percent]);

        // Auto update project status
        if ($percent === 100 && $completedTasks === $totalTasks) {
            $project->update(['status' => 'Completed']);
        } elseif ($percent > 0 && in_array($project->status, ['Pending', 'Planning'])) {
            $project->update(['status' => 'In Development']);
        }

        return $percent;
    }
}
