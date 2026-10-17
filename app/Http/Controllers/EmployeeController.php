<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Employee;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class EmployeeController extends Controller
{
    /**
     * Display a listing of employees.
     */
    public function index(Request $request): View
    {
        $query = Employee::with(['user.role', 'videoBillings', 'payouts']);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('employee_code', 'like', "%{$search}%")
                  ->orWhere('department', 'like', "%{$search}%")
                  ->orWhere('designation', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%")
                         ->orWhere('phone', 'like', "%{$search}%");
                  });
            });
        }

        if ($type = $request->input('employment_type')) {
            $query->where('employment_type', $type);
        }

        if ($payType = $request->input('payment_type')) {
            $query->where('payment_type', $payType);
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $employees = $query->orderBy('id', 'desc')->paginate(15)->withQueryString();

        $metrics = [
            'total' => Employee::count(),
            'permanent' => Employee::where('employment_type', 'permanent')->count(),
            'contractual' => Employee::where('employment_type', 'contractual')->count(),
            'freelance' => Employee::where('employment_type', 'freelance')->count(),
            'active' => Employee::where('status', 'active')->count(),
        ];

        return view('employees.index', compact('employees', 'metrics'));
    }

    /**
     * Show form for creating a new employee.
     */
    public function create(): View
    {
        // Users who don't have an employee profile yet
        $availableUsers = User::whereDoesntHave('employee')->where('status', 'active')->get();
        $roles = Role::all();

        return view('employees.create', compact('availableUsers', 'roles'));
    }

    /**
     * Store newly created employee.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'user_id' => ['required', 'exists:users,id', 'unique:employees,user_id'],
            'department' => ['required', 'string', 'max:100'],
            'designation' => ['required', 'string', 'max:100'],
            'employment_type' => ['required', 'in:permanent,contractual,freelance'],
            'payment_type' => ['required', 'in:monthly_salary,per_second_video,per_task,hourly'],
            'salary_or_rate' => ['required', 'numeric', 'min:0'],
            'payment_schedule' => ['required', 'string'],
            'joining_date' => ['nullable', 'date'],
        ]);

        $year = date('Y');
        $prefix = "EMP-{$year}-";
        $last = Employee::where('employee_code', 'like', "{$prefix}%")->orderBy('id', 'desc')->first();
        $sequence = 1;
        if ($last && preg_match('/EMP-\d{4}-(\d+)/', $last->employee_code, $matches)) {
            $sequence = intval($matches[1]) + 1;
        }
        $empCode = sprintf('%s%05d', $prefix, $sequence);

        $employee = Employee::create([
            'user_id' => $request->input('user_id'),
            'employee_code' => $empCode,
            'department' => $request->input('department'),
            'designation' => $request->input('designation'),
            'employment_type' => $request->input('employment_type'),
            'joining_date' => $request->input('joining_date', now()->format('Y-m-d')),
            'payment_type' => $request->input('payment_type'),
            'salary_or_rate' => $request->input('salary_or_rate'),
            'payment_schedule' => $request->input('payment_schedule', 'monthly'),
            'emergency_contact' => $request->input('emergency_contact'),
            'address' => $request->input('address'),
            'status' => $request->input('status', 'active'),
            'notes' => $request->input('notes'),
        ]);

        ActivityLog::log(
            action: 'employee_created',
            description: "New employee profile created: {$employee->employee_code} ({$employee->user->name})",
            subject: $employee,
            userId: auth()->id()
        );

        return redirect()->route('employees.show', $employee)
            ->with('success', "Employee profile {$employee->employee_code} created successfully.");
    }

    /**
     * Display Employee 360° Profile & Financial Ledger.
     */
    public function show(Employee $employee): View
    {
        $employee->load([
            'user.role',
            'videoBillings' => fn ($q) => $q->orderBy('id', 'desc')->take(20),
            'worklogs' => fn ($q) => $q->orderBy('id', 'desc')->take(20),
            'payouts' => fn ($q) => $q->orderBy('id', 'desc')->take(20),
        ]);

        $financials = [
            'total_earned' => $employee->total_video_billings_earned,
            'total_paid' => $employee->total_payouts,
            'current_due' => $employee->current_due,
            'total_videos' => $employee->videoBillings->count(),
            'total_seconds' => $employee->videoBillings->sum('total_seconds'),
        ];

        return view('employees.show', compact('employee', 'financials'));
    }

    /**
     * Show form for editing employee profile.
     */
    public function edit(Employee $employee): View
    {
        return view('employees.edit', compact('employee'));
    }

    /**
     * Update employee profile.
     */
    public function update(Request $request, Employee $employee): RedirectResponse
    {
        $request->validate([
            'department' => ['required', 'string', 'max:100'],
            'designation' => ['required', 'string', 'max:100'],
            'employment_type' => ['required', 'in:permanent,contractual,freelance'],
            'payment_type' => ['required', 'in:monthly_salary,per_second_video,per_task,hourly'],
            'salary_or_rate' => ['required', 'numeric', 'min:0'],
            'payment_schedule' => ['required', 'string'],
            'status' => ['required', 'in:active,inactive,on_leave'],
        ]);

        $employee->update($request->all());

        ActivityLog::log(
            action: 'employee_updated',
            description: "Employee profile {$employee->employee_code} updated.",
            subject: $employee,
            userId: auth()->id()
        );

        return redirect()->route('employees.show', $employee)
            ->with('success', 'Employee profile updated successfully.');
    }
}
