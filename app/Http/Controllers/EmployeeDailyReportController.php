<?php

namespace App\Http\Controllers;

use App\Models\EmployeeDailyReport;
use App\Models\Employee;
use App\Models\User;
use App\Models\Lead;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmployeeDailyReportController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $isAdminOrManager = $user->isAdmin() || $user->hasRole(['manager', 'project_manager']);

        $query = EmployeeDailyReport::query()->with(['user.employee', 'reviewer']);

        // Date filter (defaults to today for managers if not specified, or all)
        $selectedDate = $request->input('date', Carbon::today()->format('Y-m-d'));
        if ($request->filled('date')) {
            $query->whereDate('report_date', $selectedDate);
        }

        // Employee filter for managers
        if ($isAdminOrManager && $request->filled('user_id')) {
            $query->where('user_id', $request->input('user_id'));
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        // If not manager or admin, only see own reports
        if (!$isAdminOrManager) {
            $query->where('user_id', $user->id);
        }

        // Metrics for today
        $todaySubmissions = EmployeeDailyReport::whereDate('report_date', Carbon::today())->count();
        $pendingReviews = EmployeeDailyReport::where('status', 'Submitted')->count();
        $totalHoursToday = EmployeeDailyReport::whereDate('report_date', Carbon::today())->sum('hours_worked');

        // Check if current user submitted today
        $myTodayReport = EmployeeDailyReport::where('user_id', $user->id)
            ->whereDate('report_date', Carbon::today())
            ->first();

        $employees = User::whereHas('role', function ($q) {
            $q->whereNotIn('slug', ['client']);
        })->get(['id', 'name']);

        $reports = $query->latest('report_date')->latest('id')->paginate(15)->withQueryString();

        return view('daily-reports.index', compact(
            'reports',
            'isAdminOrManager',
            'selectedDate',
            'todaySubmissions',
            'pendingReviews',
            'totalHoursToday',
            'myTodayReport',
            'employees'
        ));
    }

    public function create()
    {
        $user = Auth::user();
        $today = Carbon::today()->format('Y-m-d');

        // Check if report already exists for today
        $existingReport = EmployeeDailyReport::where('user_id', $user->id)
            ->whereDate('report_date', Carbon::today())
            ->first();

        // Count leads touched today by current user
        $leadsCountToday = Lead::where(function ($q) use ($user) {
            $q->where('assigned_to', $user->id)
              ->orWhere('created_by', $user->id);
        })->whereDate('updated_at', Carbon::today())->count();

        return view('daily-reports.create', compact('existingReport', 'today', 'leadsCountToday'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'report_date' => 'required|date',
            'work_mode' => 'required|string|in:Office,Remote,Field,Hybrid',
            'hours_worked' => 'required|numeric|min:0.5|max:24',
            'tasks_completed' => 'required|string|min:5',
            'in_progress_tasks' => 'nullable|string',
            'challenges' => 'nullable|string',
            'tomorrow_plan' => 'nullable|string',
            'leads_contacted' => 'nullable|integer|min:0',
            'meetings_done' => 'nullable|integer|min:0',
            'orders_closed' => 'nullable|integer|min:0',
        ]);

        $employee = $user->employee;
        $validated['employee_id'] = $employee ? $employee->id : null;
        $validated['user_id'] = $user->id;
        $validated['status'] = 'Submitted';

        EmployeeDailyReport::updateOrCreate(
            [
                'user_id' => $user->id,
                'report_date' => $validated['report_date'],
            ],
            $validated
        );

        return redirect()->route('daily-reports.index')->with('success', 'Your Daily Report has been successfully submitted! 🚀');
    }

    public function show(EmployeeDailyReport $dailyReport)
    {
        $dailyReport->load(['user.employee', 'reviewer']);
        $isAdminOrManager = Auth::user()->isAdmin() || Auth::user()->hasRole(['manager', 'project_manager']);

        return view('daily-reports.show', compact('dailyReport', 'isAdminOrManager'));
    }

    public function feedback(Request $request, EmployeeDailyReport $dailyReport)
    {
        $user = Auth::user();
        if (!$user->isAdmin() && !$user->hasRole(['manager', 'project_manager'])) {
            abort(403, 'Unauthorized to review daily reports.');
        }

        $validated = $request->validate([
            'status' => 'required|in:Reviewed,Needs Improvement',
            'manager_feedback' => 'required|string|min:3',
        ]);

        $dailyReport->update([
            'status' => $validated['status'],
            'manager_feedback' => $validated['manager_feedback'],
            'reviewed_by' => $user->id,
            'reviewed_at' => Carbon::now(),
        ]);

        return redirect()->route('daily-reports.show', $dailyReport->id)
            ->with('success', 'Review & feedback remarks saved successfully!');
    }
}

