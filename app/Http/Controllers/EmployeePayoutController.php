<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Employee;
use App\Models\EmployeePayout;
use App\Models\VideoBilling;
use App\Services\BillingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmployeePayoutController extends Controller
{
    /**
     * Display listing of payouts / salary slips.
     */
    public function index(Request $request): View
    {
        $query = EmployeePayout::with(['employee.user', 'creator']);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('voucher_no', 'like', "%{$search}%")
                  ->orWhere('transaction_reference', 'like', "%{$search}%")
                  ->orWhereHas('employee.user', function ($eq) use ($search) {
                      $eq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($empId = $request->input('employee_id')) {
            $query->where('employee_id', $empId);
        }

        if ($type = $request->input('payout_type')) {
            $query->where('payout_type', $type);
        }

        if ($method = $request->input('payment_method')) {
            $query->where('payment_method', $method);
        }

        $payouts = $query->orderBy('id', 'desc')->paginate(15)->withQueryString();

        $metrics = [
            'total_paid' => EmployeePayout::where('status', 'Paid')->sum('paid_amount'),
            'video_billing_paid' => EmployeePayout::where('status', 'Paid')->where('payout_type', 'video_billing')->sum('paid_amount'),
            'salary_paid' => EmployeePayout::where('status', 'Paid')->where('payout_type', 'monthly_salary')->sum('paid_amount'),
            'total_bonus' => EmployeePayout::where('status', 'Paid')->sum('bonus_amount'),
        ];

        $employees = Employee::with('user')->where('status', 'active')->get();

        return view('payouts.index', compact('payouts', 'metrics', 'employees'));
    }

    /**
     * Show form for creating a payout voucher.
     */
    public function create(Request $request): View
    {
        $preselectedEmpId = $request->input('employee_id');
        $preselectedBillingId = $request->input('video_billing_id');

        $employees = Employee::with(['user', 'videoBillings'])->where('status', 'active')->get();
        $selectedEmployee = $preselectedEmpId ? Employee::with('user')->find($preselectedEmpId) : null;
        $selectedBilling = $preselectedBillingId ? VideoBilling::find($preselectedBillingId) : null;

        return view('payouts.create', compact('employees', 'selectedEmployee', 'selectedBilling'));
    }

    /**
     * Store a newly created payout.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'employee_id' => ['required', 'exists:employees,id'],
            'payout_type' => ['required', 'in:monthly_salary,video_billing,task_contract,hourly_work,advance,bonus'],
            'base_amount' => ['required', 'numeric', 'min:0'],
            'bonus_amount' => ['nullable', 'numeric', 'min:0'],
            'deduction_amount' => ['nullable', 'numeric', 'min:0'],
            'paid_amount' => ['required', 'numeric', 'min:0.01'],
            'payment_method' => ['required', 'in:Cash,bKash,Nagad,Rocket,Bank Transfer'],
            'payment_date' => ['required', 'date'],
        ]);

        $payout = BillingService::createPayout($request->all(), auth()->id());

        return redirect()->route('payouts.show', $payout)
            ->with('success', "Payment voucher {$payout->voucher_no} created and processed successfully.");
    }

    /**
     * Display printable payout voucher / money receipt.
     */
    public function show(EmployeePayout $payout): View
    {
        $payout->load(['employee.user', 'creator']);

        $linkedBillings = collect();
        if (!empty($payout->linked_video_billing_ids)) {
            $linkedBillings = VideoBilling::whereIn('id', $payout->linked_video_billing_ids)->get();
        }

        return view('payouts.show', compact('payout', 'linkedBillings'));
    }

    /**
     * Fetch employee's unpaid billings and current due via AJAX.
     */
    public function getEmployeePendingAjax(Employee $employee): JsonResponse
    {
        $unpaidBillings = $employee->videoBillings()
            ->where('status', 'Approved')
            ->where('due_amount', '>', 0)
            ->orderBy('id', 'desc')
            ->get();

        return response()->json([
            'status' => 'success',
            'employee' => [
                'name' => $employee->user->name,
                'designation' => $employee->designation,
                'payment_type' => $employee->payment_type,
                'salary_or_rate' => $employee->salary_or_rate,
                'current_due' => $employee->current_due,
            ],
            'billings' => $unpaidBillings,
        ]);
    }

    /**
     * Remove or cancel payout voucher.
     */
    public function destroy(EmployeePayout $payout): RedirectResponse
    {
        $voucher = $payout->voucher_no;
        $payout->delete();

        ActivityLog::log(
            action: 'employee_payout_deleted',
            description: "Voucher {$voucher} cancelled/deleted.",
            subject: null,
            userId: auth()->id()
        );

        return redirect()->route('payouts.index')
            ->with('success', "Voucher {$voucher} deleted successfully.");
    }
}
