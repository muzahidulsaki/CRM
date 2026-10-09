<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\User;
use App\Services\FinanceService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ExpenseController extends Controller
{
    protected FinanceService $financeService;

    public function __construct(FinanceService $financeService)
    {
        $this->financeService = $financeService;
    }

    /**
     * Display a listing of expenses.
     */
    public function index(Request $request): View
    {
        $query = Expense::with('paidBy');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('expense_no', 'like', "%{$search}%")
                  ->orWhere('title', 'like', "%{$search}%")
                  ->orWhere('paid_to', 'like', "%{$search}%");
            });
        }

        if ($category = $request->input('category')) {
            $query->where('category', $category);
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $expenses = $query->orderBy('expense_date', 'desc')->paginate(15)->withQueryString();

        $metrics = [
            'total_expenses' => Expense::sum('amount'),
            'today_expenses' => Expense::where('expense_date', now()->toDateString())->sum('amount'),
            'month_expenses' => Expense::whereBetween('expense_date', [now()->startOfMonth()->toDateString(), now()->endOfMonth()->toDateString()])->sum('amount'),
            'recurring_count' => Expense::where('is_recurring', true)->count(),
        ];

        return view('expenses.index', [
            'expenses' => $expenses,
            'metrics' => $metrics,
            'categories' => Expense::CATEGORIES,
            'methods' => Expense::METHODS,
        ]);
    }

    /**
     * Show the form for creating a new expense.
     */
    public function create(): View
    {
        $users = User::orderBy('name')->get();

        return view('expenses.create', [
            'users' => $users,
            'categories' => Expense::CATEGORIES,
            'methods' => Expense::METHODS,
        ]);
    }

    /**
     * Store a newly created expense in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'category' => 'required|in:' . implode(',', Expense::CATEGORIES),
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric|min:1',
            'expense_date' => 'required|date',
            'payment_method' => 'required|in:' . implode(',', Expense::METHODS),
            'paid_to' => 'nullable|string|max:255',
            'is_recurring' => 'nullable|boolean',
            'recurring_frequency' => 'nullable|in:Monthly,Quarterly,Yearly',
            'due_date' => 'nullable|date',
            'status' => 'required|in:Paid,Pending,Overdue',
            'notes' => 'nullable|string',
            'attachment' => 'nullable|file|max:10240|mimes:jpg,jpeg,png,pdf',
        ]);

        $expense = $this->financeService->createExpense(
            $validated,
            $request->file('attachment')
        );

        return redirect()->route('expenses.index')
            ->with('success', "Expense #{$expense->expense_no} logged successfully.");
    }

    /**
     * Show the specified expense details.
     */
    public function show(Expense $expense): View
    {
        $expense->load('paidBy');

        return view('expenses.show', [
            'expense' => $expense,
        ]);
    }

    /**
     * Show the form for editing the expense.
     */
    public function edit(Expense $expense): View
    {
        $users = User::orderBy('name')->get();

        return view('expenses.edit', [
            'expense' => $expense,
            'users' => $users,
            'categories' => Expense::CATEGORIES,
            'methods' => Expense::METHODS,
        ]);
    }

    /**
     * Update the specified expense in storage.
     */
    public function update(Request $request, Expense $expense): RedirectResponse
    {
        $validated = $request->validate([
            'category' => 'required|in:' . implode(',', Expense::CATEGORIES),
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric|min:1',
            'expense_date' => 'required|date',
            'payment_method' => 'required|in:' . implode(',', Expense::METHODS),
            'paid_to' => 'nullable|string|max:255',
            'status' => 'required|in:Paid,Pending,Overdue',
            'notes' => 'nullable|string',
        ]);

        $expense->update($validated);

        return redirect()->route('expenses.index')
            ->with('success', "Expense #{$expense->expense_no} updated successfully.");
    }

    /**
     * Remove the specified expense from storage.
     */
    public function destroy(Expense $expense): RedirectResponse
    {
        $no = $expense->expense_no;
        $expense->delete();

        return redirect()->route('expenses.index')
            ->with('success', "Expense #{$no} deleted successfully.");
    }
}
