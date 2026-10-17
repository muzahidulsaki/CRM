<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Employee;
use App\Models\Project;
use App\Models\Task;
use App\Models\Worklog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WorklogController extends Controller
{
    /**
     * Display a listing of worklogs.
     */
    public function index(Request $request): View
    {
        $query = Worklog::with(['employee.user', 'task', 'project']);

        if ($empId = $request->input('employee_id')) {
            $query->where('employee_id', $empId);
        }

        if ($projId = $request->input('project_id')) {
            $query->where('project_id', $projId);
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $worklogs = $query->orderBy('log_date', 'desc')->paginate(15)->withQueryString();

        $metrics = [
            'total_hours' => Worklog::sum('hours_worked'),
            'total_billable' => Worklog::sum('billable_amount'),
            'approved_hours' => Worklog::where('status', 'Approved')->sum('hours_worked'),
        ];

        $employees = Employee::with('user')->where('status', 'active')->get();
        $projects = Project::whereNotIn('status', ['Delivered', 'Cancelled'])->orderBy('name')->get();
        $tasks = Task::whereNotIn('status', ['Delivered'])->orderBy('title')->get();

        return view('worklogs.index', compact('worklogs', 'metrics', 'employees', 'projects', 'tasks'));
    }

    /**
     * Store newly created worklog.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'employee_id' => ['required', 'exists:employees,id'],
            'log_date' => ['required', 'date'],
            'hours_worked' => ['required', 'numeric', 'min:0.25'],
            'work_summary' => ['required', 'string'],
            'project_id' => ['nullable', 'exists:projects,id'],
            'task_id' => ['nullable', 'exists:tasks,id'],
        ]);

        $employee = Employee::findOrFail($request->input('employee_id'));
        $hours = floatval($request->input('hours_worked'));
        $rate = floatval($request->input('hourly_rate', $employee->payment_type === 'hourly' ? $employee->salary_or_rate : 0));
        $billable = round($hours * $rate, 2);

        $worklog = Worklog::create([
            'employee_id' => $employee->id,
            'task_id' => $request->input('task_id'),
            'project_id' => $request->input('project_id'),
            'log_date' => $request->input('log_date'),
            'hours_worked' => $hours,
            'hourly_rate' => $rate,
            'billable_amount' => $billable,
            'work_summary' => $request->input('work_summary'),
            'status' => 'Logged',
            'payment_status' => 'Unpaid',
        ]);

        ActivityLog::log(
            action: 'worklog_created',
            description: "Worklog entry: {$employee->user->name} ({$hours} hrs)",
            subject: $worklog,
            userId: auth()->id()
        );

        return back()->with('success', 'Worklog entry added successfully.');
    }

    /**
     * Approve worklog.
     */
    public function approve(Worklog $worklog): RedirectResponse
    {
        $worklog->update([
            'status' => 'Approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        return back()->with('success', 'Worklog approved successfully.');
    }

    /**
     * Delete worklog.
     */
    public function destroy(Worklog $worklog): RedirectResponse
    {
        $worklog->delete();

        return back()->with('success', 'Worklog deleted successfully.');
    }
}
