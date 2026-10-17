<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\Customer;
use App\Models\Quotation;
use App\Models\Project;
use App\Models\Order;
use App\Models\Task;
use App\Models\Employee;
use App\Models\VideoBilling;
use App\Models\EmployeePayout;
use App\Models\SupportTicket;
use App\Models\ClientPayment;
use App\Models\Expense;
use App\Services\ReportService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected ReportService $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    /**
     * Display the Executive Admin Dashboard (SRS Section 49)
     */
    public function index()
    {
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();
        $startOfLastMonth = Carbon::now()->subMonth()->startOfMonth();
        $endOfLastMonth = Carbon::now()->subMonth()->endOfMonth();

        $summary = $this->reportService->getAdminExecutiveSummary();

        // 1. Module 01: CRM & Sales Card
        $totalLeads = Lead::count();
        $totalClients = Customer::count();
        $totalQuotations = Quotation::count();

        // 2. Module 02: Orders & Projects Card
        $totalProjects = Project::count();
        $totalOrders = Order::count();
        $totalTasks = Task::count();

        // 3. Module 03: Team & Billing Card
        $totalTeamPaid = (float) (VideoBilling::sum('paid_amount') + EmployeePayout::where('status', 'paid')->sum('net_amount'));
        $totalStaff = Employee::count();
        $totalBillingDue = (float) VideoBilling::sum('due_amount');

        // 4. Module 04: Support & Tickets Card
        $totalTickets = SupportTicket::count();
        $openTickets = SupportTicket::whereIn('status', ['New', 'Assigned', 'Working', 'Open', 'In Progress'])->count();
        $resolvedTickets = SupportTicket::whereIn('status', ['Resolved', 'Closed', 'Client Confirmed'])->count();

        // Daily Pulse Dynamic Comparisons (vs yesterday / last month)
        $leadsYesterday = Lead::whereDate('created_at', $yesterday)->count();
        $leadsTrend = $leadsYesterday > 0 
            ? round((($summary['sales']['new_leads_today'] - $leadsYesterday) / $leadsYesterday) * 100) 
            : ($summary['sales']['new_leads_today'] > 0 ? 100 : 0);

        $projectsYesterday = Project::whereDate('created_at', $yesterday)->count();
        $projectsTrend = $projectsYesterday > 0 
            ? round((($summary['projects']['running_projects'] - $projectsYesterday) / $projectsYesterday) * 100) 
            : ($summary['projects']['running_projects'] > 0 ? 100 : 0);

        $videoYesterday = (int) VideoBilling::where('status', 'approved')->whereDate('updated_at', $yesterday)->sum('total_seconds');
        $videoToday = (int) VideoBilling::where('status', 'approved')->whereDate('updated_at', $today)->sum('total_seconds');
        $videoTrend = $videoYesterday > 0 
            ? round((($videoToday - $videoYesterday) / $videoYesterday) * 100) 
            : ($videoToday > 0 ? 100 : 0);

        $incomeLastMonth = (float) ClientPayment::whereBetween('payment_date', [$startOfLastMonth->toDateString(), $endOfLastMonth->toDateString()])->sum('amount');
        $expenseLastMonth = (float) Expense::whereBetween('expense_date', [$startOfLastMonth->toDateString(), $endOfLastMonth->toDateString()])->sum('amount');
        $payrollLastMonth = (float) EmployeePayout::where('status', 'paid')->whereBetween('payment_date', [$startOfLastMonth->toDateString(), $endOfLastMonth->toDateString()])->sum('net_amount');
        $profitLastMonth = $incomeLastMonth - ($expenseLastMonth + $payrollLastMonth);
        $profitCurrent = $summary['finance']['net_profit_month'];
        $profitTrend = $profitLastMonth != 0 
            ? round((($profitCurrent - $profitLastMonth) / abs($profitLastMonth)) * 100) 
            : ($profitCurrent > 0 ? 100 : 0);

        $ticketsYesterday = SupportTicket::whereDate('created_at', $yesterday)->count();
        $ticketsTrend = $ticketsYesterday > 0 
            ? round((($summary['support']['pending_tickets'] - $ticketsYesterday) / $ticketsYesterday) * 100) 
            : 0;

        // Dynamic 7-day Historical Trends for Sparkline Wave Generation
        $days7 = collect(range(6, 0))->map(fn($i) => Carbon::today()->subDays($i));
        $leadsHistory7 = $days7->map(fn($day) => Lead::whereDate('created_at', $day)->count())->values()->toArray();
        $projectsHistory7 = $days7->map(fn($day) => Project::whereDate('created_at', $day)->count() + Order::whereDate('created_at', $day)->count())->values()->toArray();
        $billingHistory7 = $days7->map(fn($day) => (float) (ClientPayment::whereDate('payment_date', $day)->sum('amount') + VideoBilling::whereDate('updated_at', $day)->where('status', 'approved')->sum('paid_amount')))->values()->toArray();
        $ticketsHistory7 = $days7->map(fn($day) => SupportTicket::whereDate('created_at', $day)->count())->values()->toArray();

        // 5-day historical trend for the Executive Complete View micro bar charts
        $days5 = collect(range(4, 0))->map(fn($i) => Carbon::today()->subDays($i));
        $leadsHistory5 = $days5->map(fn($day) => Lead::whereDate('created_at', $day)->count())->values()->toArray();
        $projectsHistory5 = $days5->map(fn($day) => Project::whereDate('created_at', $day)->count())->values()->toArray();
        $videoHistory5 = $days5->map(fn($day) => (int) VideoBilling::where('status', 'approved')->whereDate('updated_at', $day)->sum('total_seconds'))->values()->toArray();
        $profitHistory5 = $days5->map(function($day) {
            $inc = (float) ClientPayment::whereDate('payment_date', $day)->sum('amount');
            $exp = (float) Expense::whereDate('expense_date', $day)->sum('amount');
            return max(0, $inc - $exp);
        })->values()->toArray();
        $ticketsHistory5 = $days5->map(fn($day) => SupportTicket::whereDate('created_at', $day)->count())->values()->toArray();

        $sparklines = [
            'leads' => $this->generateSparklinePath($leadsHistory7, 100, 42, 5, 'crm'),
            'projects' => $this->generateSparklinePath($projectsHistory7, 100, 42, 5, 'ops'),
            'billing' => $this->generateSparklinePath($billingHistory7, 100, 42, 5, 'fin'),
            'tickets' => $this->generateSparklinePath($ticketsHistory7, 100, 42, 5, 'sup'),
        ];

        $microBars = [
            'leads' => $this->generateBarHeights($leadsHistory5),
            'projects' => $this->generateBarHeights($projectsHistory5),
            'video' => $this->generateBarHeights($videoHistory5),
            'profit' => $this->generateBarHeights($profitHistory5),
            'tickets' => $this->generateBarHeights($ticketsHistory5),
        ];

        $cardMetrics = [
            'leads' => $totalLeads,
            'clients' => $totalClients,
            'quotations' => $totalQuotations,
            'projects' => $totalProjects,
            'orders' => $totalOrders,
            'tasks' => $totalTasks,
            'team_paid' => $totalTeamPaid,
            'staff' => $totalStaff,
            'billing_due' => $totalBillingDue,
            'tickets' => $totalTickets,
            'open_tickets' => $openTickets,
            'resolved_tickets' => $resolvedTickets,
            'trends' => [
                'leads' => $leadsTrend,
                'projects' => $projectsTrend,
                'video' => $videoTrend,
                'profit' => $profitTrend,
                'tickets' => $ticketsTrend,
            ],
            'sparklines' => $sparklines,
            'micro_bars' => $microBars,
        ];

        return view('dashboard', compact('summary', 'cardMetrics'));
    }

    /**
     * Generate mathematically bounded, smooth cubic Bezier curve for Sparkline SVG
     */
    protected function generateSparklinePath(array $values, int $width = 100, int $height = 42, int $padding = 5, string $type = 'crm'): array
    {
        $count = count($values);
        if ($count < 2) {
            $values = [0, 0, 0, 0, 0, 0, 0];
            $count = 7;
        }

        $min = min($values);
        $max = max($values);
        $allZero = ($max === 0 && $min === 0);

        $usableWidth = $width - ($padding * 2);
        $usableHeight = $height - ($padding * 2);
        $dx = $usableWidth / ($count - 1);

        $points = [];
        for ($i = 0; $i < $count; $i++) {
            $x = round($padding + ($i * $dx), 1);
            if ($allZero) {
                // Match the screenshot's calm, elegant aesthetic curve for each card
                if ($type === 'crm') {
                    $ratio = 0.45 + 0.28 * sin(($i / ($count - 1)) * M_PI * 1.4);
                } elseif ($type === 'ops') {
                    $ratio = 0.40 + 0.32 * sin(($i / ($count - 1)) * M_PI * 1.3 - 0.2);
                } elseif ($type === 'fin') {
                    $ratio = 0.50 + 0.30 * sin(($i / ($count - 1)) * M_PI * 1.5 + 0.1);
                } else {
                    $ratio = 0.42 + 0.35 * sin(($i / ($count - 1)) * M_PI * 1.4 - 0.1);
                }
            } else {
                $ratio = ($max > $min) ? ($values[$i] - $min) / ($max - $min) : 0.5;
            }

            // Invert Y and keep safely inside padding bounds
            $y = round(($height - $padding) - ($ratio * $usableHeight * 0.72) - ($usableHeight * 0.14), 1);
            $points[] = [$x, $y];
        }

        // Build smooth Cubic Bezier path
        $linePath = "M " . $points[0][0] . " " . $points[0][1];
        for ($i = 0; $i < $count - 1; $i++) {
            $p0 = $points[max(0, $i - 1)];
            $p1 = $points[$i];
            $p2 = $points[$i + 1];
            $p3 = $points[min($count - 1, $i + 2)];

            $cp1x = round($p1[0] + ($p2[0] - $p0[0]) / 6, 1);
            $cp1y = round($p1[1] + ($p2[1] - $p0[1]) / 6, 1);
            $cp2x = round($p2[0] - ($p3[0] - $p1[0]) / 6, 1);
            $cp2y = round($p2[1] - ($p3[1] - $p1[1]) / 6, 1);

            $linePath .= " C $cp1x $cp1y, $cp2x $cp2y, " . $p2[0] . " " . $p2[1];
        }

        $lastX = $points[$count - 1][0];
        $firstX = $points[0][0];
        $areaPath = $linePath . " L $lastX $height L $firstX $height Z";

        return [
            'line' => $linePath,
            'area' => $areaPath,
            'all_zero' => $allZero
        ];
    }

    /**
     * Generate normalized bar percentage heights for micro bar charts (5 bars)
     */
    protected function generateBarHeights(array $values): array
    {
        $max = max($values);
        $min = min($values);

        if ($max === 0 && $min === 0) {
            // Aesthetic default heights matching screenshot micro-bars
            return [30, 50, 70, 45, 85];
        }

        $heights = [];
        foreach ($values as $v) {
            if ($max > 0) {
                $pct = round(($v / $max) * 75 + 25);
            } else {
                $pct = 25;
            }
            $heights[] = min(100, max(20, $pct));
        }

        return $heights;
    }
}
