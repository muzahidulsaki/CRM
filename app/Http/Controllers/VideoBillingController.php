<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\Project;
use App\Models\Setting;
use App\Models\Task;
use App\Models\VideoBilling;
use App\Services\BillingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VideoBillingController extends Controller
{
    /**
     * Display a listing of video editor billings.
     */
    public function index(Request $request): View
    {
        $query = VideoBilling::with(['employee.user', 'project', 'task', 'customer', 'approvedByUser']);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('billing_code', 'like', "%{$search}%")
                  ->orWhere('video_title', 'like', "%{$search}%")
                  ->orWhereHas('employee.user', function ($eq) use ($search) {
                      $eq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($employeeId = $request->input('employee_id')) {
            $query->where('employee_id', $employeeId);
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($paymentStatus = $request->input('payment_status')) {
            $query->where('payment_status', $paymentStatus);
        }

        $billings = $query->orderBy('id', 'desc')->paginate(15)->withQueryString();

        $metrics = [
            'total_count' => VideoBilling::count(),
            'approved_count' => VideoBilling::where('status', 'Approved')->count(),
            'total_seconds' => VideoBilling::where('status', 'Approved')->sum('total_seconds'),
            'total_payable' => VideoBilling::where('status', 'Approved')->sum('total_payable'),
            'total_paid' => VideoBilling::sum('paid_amount'),
            'total_due' => VideoBilling::where('status', 'Approved')->sum('due_amount'),
        ];

        $employees = Employee::with('user')->where('status', 'active')->get();

        return view('video-billings.index', compact('billings', 'metrics', 'employees'));
    }

    /**
     * Show form for creating a new video billing.
     */
    public function create(Request $request): View
    {
        $employees = Employee::with('user')->where('status', 'active')->get();
        $projects = Project::whereNotIn('status', ['Delivered', 'Cancelled'])->orderBy('name')->get();
        $tasks = Task::where('task_type', 'Video Editing')->orderBy('id', 'desc')->take(30)->get();
        $customers = Customer::where('status', 'active')->orderBy('name')->get();
        $defaultRate = Setting::get('default_video_rate_per_second', 8.00);

        $preselectedTaskId = $request->input('task_id');
        $preselectedTask = $preselectedTaskId ? Task::with(['project', 'assignedUser'])->find($preselectedTaskId) : null;

        return view('video-billings.create', compact('employees', 'projects', 'tasks', 'customers', 'defaultRate', 'preselectedTask'));
    }

    /**
     * Store a newly created video billing.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'employee_id' => ['required', 'exists:employees,id'],
            'video_title' => ['required', 'string', 'max:255'],
            'video_url' => ['nullable', 'url'],
            'duration_minutes' => ['required', 'integer', 'min:0'],
            'duration_seconds' => ['required', 'integer', 'min:0', 'max:59'],
            'rate_per_second' => ['required', 'numeric', 'min:0.01'],
            'number_of_videos' => ['required', 'integer', 'min:1'],
            'project_id' => ['nullable', 'exists:projects,id'],
            'task_id' => ['nullable', 'exists:tasks,id'],
            'customer_id' => ['nullable', 'exists:customers,id'],
            'work_end_date' => ['nullable', 'date'],
        ]);

        $billing = BillingService::createVideoBilling($request->all(), auth()->id());

        return redirect()->route('video-billings.show', $billing)
            ->with('success', "Video billing {$billing->billing_code} created successfully.");
    }

    /**
     * Display the specified video billing.
     */
    public function show(VideoBilling $videoBilling): View
    {
        $videoBilling->load(['employee.user', 'project', 'task', 'customer', 'approvedByUser', 'creator']);

        return view('video-billings.show', compact('videoBilling'));
    }

    /**
     * Show form for editing video billing.
     */
    public function edit(VideoBilling $videoBilling): View
    {
        $employees = Employee::with('user')->where('status', 'active')->get();
        $projects = Project::whereNotIn('status', ['Delivered', 'Cancelled'])->orderBy('name')->get();
        $tasks = Task::where('task_type', 'Video Editing')->orderBy('id', 'desc')->take(30)->get();
        $customers = Customer::where('status', 'active')->orderBy('name')->get();

        return view('video-billings.edit', compact('videoBilling', 'employees', 'projects', 'tasks', 'customers'));
    }

    /**
     * Update video billing.
     */
    public function update(Request $request, VideoBilling $videoBilling): RedirectResponse
    {
        $request->validate([
            'video_title' => ['required', 'string', 'max:255'],
            'duration_minutes' => ['required', 'integer', 'min:0'],
            'duration_seconds' => ['required', 'integer', 'min:0', 'max:59'],
            'rate_per_second' => ['required', 'numeric', 'min:0.01'],
            'number_of_videos' => ['required', 'integer', 'min:1'],
        ]);

        $mins = intval($request->input('duration_minutes'));
        $secs = intval($request->input('duration_seconds'));
        $rate = floatval($request->input('rate_per_second'));
        $num = intval($request->input('number_of_videos', 1));

        $calc = BillingService::calculateVideoPayable($mins, $secs, $rate, $num);

        $videoBilling->update(array_merge($request->all(), [
            'total_seconds' => $calc['total_seconds'],
            'total_payable' => $calc['total_payable'],
            'due_amount' => max(0, $calc['total_payable'] - $videoBilling->paid_amount),
        ]));

        ActivityLog::log(
            action: 'video_billing_updated',
            description: "Video billing {$videoBilling->billing_code} updated.",
            subject: $videoBilling,
            userId: auth()->id()
        );

        return redirect()->route('video-billings.show', $videoBilling)
            ->with('success', 'Video billing updated successfully.');
    }

    /**
     * Approve video billing.
     */
    public function approve(VideoBilling $videoBilling): RedirectResponse
    {
        BillingService::approveVideoBilling($videoBilling, auth()->id());

        return back()->with('success', "Video billing {$videoBilling->billing_code} approved!");
    }

    /**
     * Live AJAX calculation endpoint.
     */
    public function calculateAjax(Request $request): JsonResponse
    {
        $mins = intval($request->input('duration_minutes', 0));
        $secs = intval($request->input('duration_seconds', 0));
        $rate = floatval($request->input('rate_per_second', 8.00));
        $num = intval($request->input('number_of_videos', 1));

        $result = BillingService::calculateVideoPayable($mins, $secs, $rate, $num);

        return response()->json([
            'status' => 'success',
            'data' => $result,
        ]);
    }

    /**
     * Remove video billing.
     */
    public function destroy(VideoBilling $videoBilling): RedirectResponse
    {
        $code = $videoBilling->billing_code;
        $videoBilling->delete();

        ActivityLog::log(
            action: 'video_billing_deleted',
            description: "Video billing {$code} deleted.",
            subject: null,
            userId: auth()->id()
        );

        return redirect()->route('video-billings.index')
            ->with('success', "Video billing {$code} deleted successfully.");
    }
}
