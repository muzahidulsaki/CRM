<?php

namespace App\Http\Controllers;

use App\Services\ReportService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    protected ReportService $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    /**
     * Parse date range from request or default to current month
     */
    protected function parseDateRange(Request $request): array
    {
        $filter = $request->query('range', 'this_month');

        switch ($filter) {
            case 'today':
                $start = Carbon::today();
                $end = Carbon::today();
                break;
            case 'yesterday':
                $start = Carbon::yesterday();
                $end = Carbon::yesterday();
                break;
            case 'last_7_days':
                $start = Carbon::today()->subDays(6);
                $end = Carbon::today();
                break;
            case 'last_month':
                $start = Carbon::now()->subMonth()->startOfMonth();
                $end = Carbon::now()->subMonth()->endOfMonth();
                break;
            case 'this_year':
                $start = Carbon::now()->startOfYear();
                $end = Carbon::now()->endOfYear();
                break;
            case 'custom':
                $start = $request->has('start_date') ? Carbon::parse($request->start_date) : Carbon::now()->startOfMonth();
                $end = $request->has('end_date') ? Carbon::parse($request->end_date) : Carbon::now()->endOfMonth();
                break;
            case 'this_month':
            default:
                $start = Carbon::now()->startOfMonth();
                $end = Carbon::now()->endOfMonth();
                break;
        }

        return [$start, $end, $filter];
    }

    /**
     * Reports Hub Index
     */
    public function index(Request $request)
    {
        [$startDate, $endDate, $filter] = $this->parseDateRange($request);
        $summary = $this->reportService->getAdminExecutiveSummary();

        return view('reports.index', compact('summary', 'startDate', 'endDate', 'filter'));
    }

    /**
     * Sales & Lead Conversion Report
     */
    public function sales(Request $request)
    {
        [$startDate, $endDate, $filter] = $this->parseDateRange($request);
        $data = $this->reportService->getSalesReport($startDate, $endDate);

        return view('reports.sales', compact('data', 'startDate', 'endDate', 'filter'));
    }

    /**
     * Production & Creative Video Report
     */
    public function production(Request $request)
    {
        [$startDate, $endDate, $filter] = $this->parseDateRange($request);
        $data = $this->reportService->getProductionReport($startDate, $endDate);

        return view('reports.production', compact('data', 'startDate', 'endDate', 'filter'));
    }

    /**
     * Finance, P&L & Aging Receivables Report
     */
    public function finance(Request $request)
    {
        [$startDate, $endDate, $filter] = $this->parseDateRange($request);
        $data = $this->reportService->getFinancialReport($startDate, $endDate);

        return view('reports.finance', compact('data', 'startDate', 'endDate', 'filter'));
    }

    /**
     * Support & SLA Helpdesk Report
     */
    public function support(Request $request)
    {
        [$startDate, $endDate, $filter] = $this->parseDateRange($request);
        $data = $this->reportService->getSupportReport($startDate, $endDate);

        return view('reports.support', compact('data', 'startDate', 'endDate', 'filter'));
    }

    /**
     * Export Report to CSV / Excel
     */
    public function export(Request $request, string $type)
    {
        [$startDate, $endDate] = $this->parseDateRange($request);
        $csvData = $this->reportService->exportCsv($type, $startDate, $endDate);

        $filename = "report_{$type}_{$startDate->format('Ymd')}_{$endDate->format('Ymd')}.csv";

        return response($csvData, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }
}
