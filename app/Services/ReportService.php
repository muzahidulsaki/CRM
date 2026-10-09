<?php

namespace App\Services;

use App\Models\Lead;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Project;
use App\Models\Task;
use App\Models\Employee;
use App\Models\VideoBilling;
use App\Models\Worklog;
use App\Models\EmployeePayout;
use App\Models\SupportTicket;
use App\Models\Invoice;
use App\Models\ClientPayment;
use App\Models\ServiceRenewal;
use App\Models\Expense;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportService
{
    /**
     * SRS Section 49: Admin's Complete View
     * Returns a centralized executive snapshot covering Sales, Projects, Support, Finance, and Production.
     */
    public function getAdminExecutiveSummary(): array
    {
        $today = Carbon::today();
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        // 1. SALES
        $newLeadsToday = Lead::whereDate('created_at', $today)->count();
        $leadsInFollowUp = Lead::where(function ($q) use ($today) {
            $q->whereIn('lead_status', ['follow_up', 'contacted', 'meeting'])
              ->orWhereDate('next_followup_date', '>=', $today)
              ->orWhereHas('followUps', function ($fq) use ($today) {
                  $fq->whereDate('scheduled_at', '>=', $today)->where('status', 'pending');
              });
        })->count();

        $totalSalesCountMonth = Order::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();
        $totalSoldAmountMonth = (float) Order::whereBetween('created_at', [$startOfMonth, $endOfMonth])->sum('total_amount');
        $totalCollectedMonth = (float) ClientPayment::whereBetween('payment_date', [$startOfMonth->toDateString(), $endOfMonth->toDateString()])->sum('amount');

        // 2. PROJECTS & TASKS
        $runningProjects = Project::whereIn('status', ['in_progress', 'qa_review'])->count();
        $delayedProjects = Project::whereIn('status', ['pending', 'in_progress', 'qa_review'])
            ->whereNotNull('deadline')
            ->where('deadline', '<', $today)
            ->with(['customer'])
            ->get();
        $tasksDueToday = Task::whereDate('deadline', $today)->where('status', '!=', 'completed')->count();
        $tasksDueTomorrow = Task::whereDate('deadline', $today->copy()->addDay())->where('status', '!=', 'completed')->count();
        $tasksInProgress = Task::where('status', 'in_progress')->count();

        // 3. SUPPORT
        $pendingTickets = SupportTicket::whereIn('status', ['New', 'Assigned', 'Working'])->count();
        $reworkTickets = SupportTicket::where('status', 'Reworking')->count();
        $resolvedTicketsMonth = SupportTicket::whereIn('status', ['Resolved', 'Client Confirmed', 'Closed'])
            ->whereBetween('updated_at', [$startOfMonth, $endOfMonth])
            ->count();
        $urgentPendingTickets = SupportTicket::whereIn('priority', ['High', 'Critical'])
            ->whereNotIn('status', ['Resolved', 'Client Confirmed', 'Closed'])
            ->with(['customer', 'assignedDeveloper'])
            ->take(5)
            ->get();

        // 4. FINANCE
        $incomeMonth = (float) ClientPayment::whereBetween('payment_date', [$startOfMonth->toDateString(), $endOfMonth->toDateString()])->sum('amount');
        $expensesMonth = (float) Expense::whereBetween('expense_date', [$startOfMonth->toDateString(), $endOfMonth->toDateString()])->sum('amount');
        $payrollMonth = (float) EmployeePayout::where('status', 'paid')
            ->whereBetween('payment_date', [$startOfMonth->toDateString(), $endOfMonth->toDateString()])
            ->sum('net_amount');
        $totalOutflowMonth = $expensesMonth + $payrollMonth;
        $netProfitMonth = $incomeMonth - $totalOutflowMonth;

        $totalReceivableDue = (float) Invoice::whereIn('status', ['Draft', 'Sent', 'Partially Paid'])->sum('due_amount');
        $overdueRenewals = ServiceRenewal::where('next_due_date', '<', $today)->count();
        $upcomingRenewalsAmount = (float) ServiceRenewal::whereBetween('next_due_date', [$today, $today->copy()->addDays(30)])->sum('renewal_fee');
        $pendingEmployeePayables = (float) EmployeePayout::where('status', 'draft')->sum('net_amount');

        // 5. PRODUCTION
        $completedVideos = VideoBilling::where('status', 'approved')->count();
        $totalSecondsEdited = (int) VideoBilling::where('status', 'approved')->sum('total_seconds');
        $deliveredWebsites = Order::where('service_category', 'Web & Software Development')
            ->where('status', 'delivered')
            ->count();
        $completedTasksMonth = Task::where('status', 'completed')
            ->whereBetween('updated_at', [$startOfMonth, $endOfMonth])
            ->count();
        $pendingWorkCount = Task::whereIn('status', ['pending', 'in_progress', 'qa_review'])->count();

        return [
            'sales' => [
                'new_leads_today' => $newLeadsToday,
                'leads_in_follow_up' => $leadsInFollowUp,
                'total_sales_month' => $totalSalesCountMonth,
                'total_sold_amount_month' => $totalSoldAmountMonth,
                'total_collected_month' => $totalCollectedMonth,
            ],
            'projects' => [
                'running_projects' => $runningProjects,
                'delayed_projects_count' => $delayedProjects->count(),
                'delayed_projects' => $delayedProjects,
                'tasks_due_today' => $tasksDueToday,
                'tasks_due_tomorrow' => $tasksDueTomorrow,
                'tasks_in_progress' => $tasksInProgress,
            ],
            'support' => [
                'pending_tickets' => $pendingTickets,
                'rework_tickets' => $reworkTickets,
                'resolved_tickets_month' => $resolvedTicketsMonth,
                'urgent_tickets' => $urgentPendingTickets,
            ],
            'finance' => [
                'income_month' => $incomeMonth,
                'expense_month' => $expensesMonth,
                'payroll_month' => $payrollMonth,
                'total_outflow_month' => $totalOutflowMonth,
                'net_profit_month' => $netProfitMonth,
                'total_receivable_due' => $totalReceivableDue,
                'overdue_renewals_count' => $overdueRenewals,
                'upcoming_renewals_amount' => $upcomingRenewalsAmount,
                'pending_employee_payables' => $pendingEmployeePayables,
            ],
            'production' => [
                'completed_videos' => $completedVideos,
                'total_seconds_edited' => $totalSecondsEdited,
                'delivered_websites' => $deliveredWebsites,
                'completed_tasks_month' => $completedTasksMonth,
                'pending_work_count' => $pendingWorkCount,
            ]
        ];
    }

    /**
     * Detailed Sales & Conversion Report
     */
    public function getSalesReport(Carbon $startDate, Carbon $endDate): array
    {
        $leadsQuery = Lead::whereBetween('created_at', [$startDate->copy()->startOfDay(), $endDate->copy()->endOfDay()]);
        $totalLeads = (clone $leadsQuery)->count();

        // Status Breakdown
        $statusCounts = (clone $leadsQuery)
            ->select('lead_status', DB::raw('count(*) as count'))
            ->groupBy('lead_status')
            ->pluck('count', 'lead_status')
            ->toArray();

        // Source Breakdown
        $sourceCounts = (clone $leadsQuery)
            ->select('lead_source', DB::raw('count(*) as count'))
            ->groupBy('lead_source')
            ->pluck('count', 'lead_source')
            ->toArray();

        // Category Breakdown
        $categoryCounts = (clone $leadsQuery)
            ->select('interested_service', DB::raw('count(*) as count'))
            ->groupBy('interested_service')
            ->pluck('count', 'interested_service')
            ->toArray();

        // Conversion
        $wonLeads = $statusCounts['won'] ?? 0;
        $conversionRate = $totalLeads > 0 ? round(($wonLeads / $totalLeads) * 100, 2) : 0;

        // Orders within range
        $orders = Order::whereBetween('created_at', [$startDate->copy()->startOfDay(), $endDate->copy()->endOfDay()])
            ->with(['customer', 'quotation'])
            ->latest()
            ->get();
        $totalSalesValue = (float) $orders->sum('total_amount');
        $totalCollected = (float) $orders->sum('paid_amount');
        $totalDue = (float) $orders->sum('due_amount');

        return [
            'total_leads' => $totalLeads,
            'status_counts' => $statusCounts,
            'source_counts' => $sourceCounts,
            'category_counts' => $categoryCounts,
            'won_leads' => $wonLeads,
            'conversion_rate' => $conversionRate,
            'orders' => $orders,
            'total_sales_value' => $totalSalesValue,
            'total_collected' => $totalCollected,
            'total_due' => $totalDue,
        ];
    }

    /**
     * Detailed Production, Video Editing & Team Productivity Report
     */
    public function getProductionReport(Carbon $startDate, Carbon $endDate): array
    {
        // Video Billings
        $videoBillings = VideoBilling::whereBetween('created_at', [$startDate->copy()->startOfDay(), $endDate->copy()->endOfDay()])
            ->with(['employee.user', 'project'])
            ->latest()
            ->get();

        $totalVideos = $videoBillings->count();
        $totalSeconds = (int) $videoBillings->sum('total_seconds');
        $totalVideoCost = (float) $videoBillings->sum('total_payable');

        // Video editor performance ranking
        $editorStats = [];
        foreach ($videoBillings as $vb) {
            $editorName = $vb->employee->user->name ?? 'Editor #' . $vb->employee_id;
            if (!isset($editorStats[$editorName])) {
                $editorStats[$editorName] = [
                    'count' => 0,
                    'seconds' => 0,
                    'amount' => 0,
                ];
            }
            $editorStats[$editorName]['count']++;
            $editorStats[$editorName]['seconds'] += $vb->total_seconds;
            $editorStats[$editorName]['amount'] += (float)$vb->total_payable;
        }

        // Developer Worklogs
        $worklogs = Worklog::whereBetween('log_date', [$startDate->toDateString(), $endDate->toDateString()])
            ->with(['employee.user', 'task.project'])
            ->latest()
            ->get();
        $totalWorklogHours = (float) $worklogs->sum('hours_worked');

        // Tasks Status
        $tasksCompleted = Task::where('status', 'completed')
            ->whereBetween('updated_at', [$startDate->copy()->startOfDay(), $endDate->copy()->endOfDay()])
            ->count();
        $tasksCreated = Task::whereBetween('created_at', [$startDate->copy()->startOfDay(), $endDate->copy()->endOfDay()])
            ->count();

        // Projects Delivered
        $deliveredProjects = Project::where('status', 'delivered')
            ->whereBetween('updated_at', [$startDate->copy()->startOfDay(), $endDate->copy()->endOfDay()])
            ->with('customer')
            ->get();

        return [
            'total_videos' => $totalVideos,
            'total_seconds' => $totalSeconds,
            'total_video_cost' => $totalVideoCost,
            'editor_stats' => $editorStats,
            'video_billings' => $videoBillings,
            'worklogs' => $worklogs,
            'total_worklog_hours' => $totalWorklogHours,
            'tasks_completed' => $tasksCompleted,
            'tasks_created' => $tasksCreated,
            'delivered_projects' => $deliveredProjects,
        ];
    }

    /**
     * Detailed Finance, P&L & Aging Receivables Report
     */
    public function getFinancialReport(Carbon $startDate, Carbon $endDate): array
    {
        // Inflows (Client Payments)
        $clientPayments = ClientPayment::whereBetween('payment_date', [$startDate->toDateString(), $endDate->toDateString()])
            ->with(['customer', 'invoice'])
            ->latest()
            ->get();
        $totalInflow = (float) $clientPayments->sum('amount');

        // Outflows (Expenses & Payouts)
        $expenses = Expense::whereBetween('expense_date', [$startDate->toDateString(), $endDate->toDateString()])
            ->latest()
            ->get();
        $totalExpenses = (float) $expenses->sum('amount');

        $payouts = EmployeePayout::where('status', 'paid')
            ->whereBetween('payment_date', [$startDate->toDateString(), $endDate->toDateString()])
            ->with('employee.user')
            ->latest()
            ->get();
        $totalPayouts = (float) $payouts->sum('net_amount');

        $totalOutflow = $totalExpenses + $totalPayouts;
        $netProfit = $totalInflow - $totalOutflow;

        // Expense by category
        $expenseCategories = Expense::whereBetween('expense_date', [$startDate->toDateString(), $endDate->toDateString()])
            ->select('category', DB::raw('sum(amount) as total'))
            ->groupBy('category')
            ->pluck('total', 'category')
            ->toArray();

        // Aging Accounts Receivable (All pending unpaid invoices)
        $today = Carbon::today();
        $unpaidInvoices = Invoice::whereIn('status', ['Draft', 'Sent', 'Partially Paid'])
            ->where('due_amount', '>', 0)
            ->with('customer')
            ->get();

        $aging = [
            'current' => 0.0,    // 0-30 days
            'days_31_60' => 0.0, // 31-60 days
            'days_61_90' => 0.0, // 61-90 days
            'over_90' => 0.0,    // 90+ days
            'total_due' => 0.0,
            'invoices' => []
        ];

        foreach ($unpaidInvoices as $inv) {
            $issueDate = Carbon::parse($inv->issue_date);
            $daysDiff = $today->diffInDays($issueDate);
            $due = (float) $inv->due_amount;

            $aging['total_due'] += $due;
            if ($daysDiff <= 30) {
                $aging['current'] += $due;
                $bracket = '0-30 Days';
            } elseif ($daysDiff <= 60) {
                $aging['days_31_60'] += $due;
                $bracket = '31-60 Days';
            } elseif ($daysDiff <= 90) {
                $aging['days_61_90'] += $due;
                $bracket = '61-90 Days';
            } else {
                $aging['over_90'] += $due;
                $bracket = '90+ Days';
            }

            $aging['invoices'][] = [
                'invoice' => $inv,
                'days' => $daysDiff,
                'bracket' => $bracket
            ];
        }

        return [
            'total_inflow' => $totalInflow,
            'client_payments' => $clientPayments,
            'total_expenses' => $totalExpenses,
            'expenses' => $expenses,
            'expense_categories' => $expenseCategories,
            'total_payouts' => $totalPayouts,
            'payouts' => $payouts,
            'total_outflow' => $totalOutflow,
            'net_profit' => $netProfit,
            'aging' => $aging,
        ];
    }

    /**
     * Detailed Support & SLA Ticket Report
     */
    public function getSupportReport(Carbon $startDate, Carbon $endDate): array
    {
        $tickets = SupportTicket::whereBetween('created_at', [$startDate->copy()->startOfDay(), $endDate->copy()->endOfDay()])
            ->with(['customer', 'assignedDeveloper', 'assignedManager'])
            ->latest()
            ->get();

        $totalTickets = $tickets->count();
        $statusCounts = $tickets->groupBy('status')->map->count()->toArray();
        $priorityCounts = $tickets->groupBy('priority')->map->count()->toArray();
        $categoryCounts = $tickets->groupBy('issue_type')->map->count()->toArray();

        // Resolution metrics
        $resolvedTickets = $tickets->filter(fn ($t) => in_array($t->status, ['Resolved', 'Client Confirmed', 'Closed']));
        $resolutionRate = $totalTickets > 0 ? round(($resolvedTickets->count() / $totalTickets) * 100, 2) : 0;

        // Developer leaderboard
        $devStats = [];
        foreach ($tickets as $t) {
            if ($t->assignedDeveloper) {
                $devName = $t->assignedDeveloper->name;
                if (!isset($devStats[$devName])) {
                    $devStats[$devName] = ['total' => 0, 'resolved' => 0];
                }
                $devStats[$devName]['total']++;
                if (in_array($t->status, ['Resolved', 'Client Confirmed', 'Closed'])) {
                    $devStats[$devName]['resolved']++;
                }
            }
        }

        return [
            'total_tickets' => $totalTickets,
            'status_counts' => $statusCounts,
            'priority_counts' => $priorityCounts,
            'category_counts' => $categoryCounts,
            'resolved_count' => $resolvedTickets->count(),
            'resolution_rate' => $resolutionRate,
            'dev_stats' => $devStats,
            'tickets' => $tickets,
        ];
    }

    /**
     * CSV Exporter for any report type
     */
    public function exportCsv(string $type, Carbon $startDate, Carbon $endDate): string
    {
        $output = fopen('php://temp', 'r+');
        // UTF-8 BOM for Microsoft Excel compatibility
        fputs($output, "\xEF\xBB\xBF");

        switch ($type) {
            case 'sales':
                fputcsv($output, ['Lead Code', 'Name', 'Phone', 'Source', 'Interested Service', 'Status', 'Budget', 'Created Date']);
                $leads = Lead::whereBetween('created_at', [$startDate->copy()->startOfDay(), $endDate->copy()->endOfDay()])->get();
                foreach ($leads as $l) {
                    fputcsv($output, [
                        $l->lead_code,
                        $l->name,
                        $l->phone,
                        $l->lead_source,
                        $l->interested_service,
                        ucfirst($l->lead_status),
                        $l->budget ?? 0,
                        $l->created_at->format('Y-m-d H:i')
                    ]);
                }
                break;

            case 'video_production':
                fputcsv($output, ['Billing Code', 'Editor Name', 'Video Title', 'Duration (Min:Sec)', 'Total Seconds', 'Rate/Sec (BDT)', 'Total Payable (BDT)', 'Status', 'Date']);
                $billings = VideoBilling::whereBetween('created_at', [$startDate->copy()->startOfDay(), $endDate->copy()->endOfDay()])
                    ->with(['employee.user'])
                    ->get();
                foreach ($billings as $vb) {
                    fputcsv($output, [
                        $vb->billing_code,
                        $vb->employee->user->name ?? 'N/A',
                        $vb->video_title ?? 'N/A',
                        sprintf('%02d:%02d', $vb->duration_minutes, $vb->duration_seconds),
                        $vb->total_seconds,
                        $vb->rate_per_second,
                        $vb->total_payable,
                        ucfirst($vb->status),
                        $vb->created_at->format('Y-m-d')
                    ]);
                }
                break;

            case 'finance_inflows':
                fputcsv($output, ['Payment No', 'Customer Name', 'Payment Date', 'Method', 'Transaction Ref', 'Amount (BDT)', 'Invoice No', 'Received By']);
                $payments = ClientPayment::whereBetween('payment_date', [$startDate->toDateString(), $endDate->toDateString()])
                    ->with(['customer', 'invoice', 'receivedBy'])
                    ->get();
                foreach ($payments as $cp) {
                    fputcsv($output, [
                        $cp->payment_no,
                        $cp->customer->name ?? 'N/A',
                        $cp->payment_date,
                        ucfirst($cp->payment_method),
                        $cp->transaction_reference ?? '-',
                        $cp->amount,
                        $cp->invoice->invoice_no ?? '-',
                        $cp->receivedBy->name ?? 'System'
                    ]);
                }
                break;

            case 'finance_expenses':
                fputcsv($output, ['Expense No', 'Category', 'Title', 'Date', 'Amount (BDT)', 'Payment Method', 'Paid To', 'Status']);
                $expenses = Expense::whereBetween('expense_date', [$startDate->toDateString(), $endDate->toDateString()])->get();
                foreach ($expenses as $exp) {
                    fputcsv($output, [
                        $exp->expense_no,
                        $exp->category,
                        $exp->title,
                        $exp->expense_date,
                        $exp->amount,
                        ucfirst($exp->payment_method),
                        $exp->paid_to ?? '-',
                        ucfirst($exp->status)
                    ]);
                }
                break;

            case 'support_tickets':
                fputcsv($output, ['Ticket No', 'Subject', 'Customer', 'Issue Type', 'Priority', 'Status', 'Assigned Developer', 'Created At', 'Resolved At']);
                $tickets = SupportTicket::whereBetween('created_at', [$startDate->copy()->startOfDay(), $endDate->copy()->endOfDay()])
                    ->with(['customer', 'assignedDeveloper'])
                    ->get();
                foreach ($tickets as $t) {
                    fputcsv($output, [
                        $t->ticket_no,
                        $t->subject,
                        $t->contact_name ?? $t->customer->name ?? 'Guest',
                        $t->issue_type,
                        $t->priority,
                        $t->status,
                        $t->assignedDeveloper->name ?? 'Unassigned',
                        $t->created_at->format('Y-m-d H:i'),
                        $t->resolved_at ? Carbon::parse($t->resolved_at)->format('Y-m-d H:i') : '-'
                    ]);
                }
                break;
        }

        rewind($output);
        $csvContent = stream_get_contents($output);
        fclose($output);

        return $csvContent;
    }
}
