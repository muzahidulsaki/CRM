<?php

namespace App\Http\Controllers;

use App\Models\ClientPayment;
use App\Models\EmployeePayout;
use App\Models\Expense;
use App\Models\Invoice;
use App\Models\ServiceRenewal;
use App\Services\FinanceService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FinanceController extends Controller
{
    protected FinanceService $financeService;

    public function __construct(FinanceService $financeService)
    {
        $this->financeService = $financeService;
    }

    /**
     * Display executive financial dashboard & P&L report (SRS Section 25 & 26).
     */
    public function index(Request $request): View
    {
        $startDate = $request->input('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', now()->endOfMonth()->toDateString());

        $summary = $this->financeService->getFinancialSummary($startDate, $endDate);

        // Recent Client Payments
        $recentPayments = ClientPayment::with('customer')
            ->orderBy('payment_date', 'desc')
            ->take(8)
            ->get();

        // Recent Expenses
        $recentExpenses = Expense::orderBy('expense_date', 'desc')
            ->take(8)
            ->get();

        // Expenses breakdown by category
        $expenseCategories = Expense::selectRaw('category, sum(amount) as total')
            ->whereBetween('expense_date', [$startDate, $endDate])
            ->groupBy('category')
            ->orderBy('total', 'desc')
            ->get();

        // Overdue & Due Soon Renewals
        $urgentRenewals = ServiceRenewal::with('customer')
            ->where('next_due_date', '<=', now()->addDays(7)->toDateString())
            ->whereNotIn('status', ['Suspended', 'Cancelled'])
            ->orderBy('next_due_date', 'asc')
            ->take(5)
            ->get();

        return view('finance.index', [
            'summary' => $summary,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'recentPayments' => $recentPayments,
            'recentExpenses' => $recentExpenses,
            'expenseCategories' => $expenseCategories,
            'urgentRenewals' => $urgentRenewals,
        ]);
    }
}
