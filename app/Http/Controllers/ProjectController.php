<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Project;
use App\Models\Setting;
use App\Models\User;
use App\Services\OrderService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProjectController extends Controller
{
    /**
     * Display a listing of projects.
     */
    public function index(Request $request): View
    {
        $query = Project::with(['customer', 'order', 'projectManager', 'tasks']);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('project_code', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%")
                  ->orWhereHas('customer', function ($cq) use ($search) {
                      $cq->where('name', 'like', "%{$search}%")
                         ->orWhere('company_name', 'like', "%{$search}%");
                  });
            });
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($category = $request->input('category')) {
            $query->where('category', $category);
        }

        if ($pm = $request->input('pm_id')) {
            $query->where('project_manager_id', $pm);
        }

        $projects = $query->orderBy('id', 'desc')->paginate(15)->withQueryString();

        $metrics = [
            'total' => Project::count(),
            'active' => Project::whereIn('status', ['Planning', 'In Development', 'In QA', 'Client Review'])->count(),
            'in_development' => Project::where('status', 'In Development')->count(),
            'in_qa' => Project::where('status', 'In QA')->count(),
            'completed' => Project::whereIn('status', ['Completed', 'Delivered'])->count(),
            'delayed' => Project::whereDate('deadline', '<', now())->whereNotIn('status', ['Completed', 'Delivered'])->count(),
        ];

        $serviceCategories = Setting::get('service_categories', []);
        $projectManagers = User::where('status', 'active')->orderBy('name')->get();

        return view('projects.index', compact('projects', 'metrics', 'serviceCategories', 'projectManagers'));
    }

    /**
     * Show form for creating project manually.
     */
    public function create(Request $request): View
    {
        $preselectedOrder = $request->filled('order_id') ? Order::with('customer')->find($request->input('order_id')) : null;
        $orders = Order::whereDoesntHave('project')->orderBy('id', 'desc')->get();
        $customers = Customer::where('status', 'active')->orderBy('name')->get();
        $projectManagers = User::where('status', 'active')->orderBy('name')->get();
        $serviceCategories = Setting::get('service_categories', []);

        return view('projects.create', compact('preselectedOrder', 'orders', 'customers', 'projectManagers', 'serviceCategories'));
    }

    /**
     * Store a newly created project.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'order_id' => ['required', 'exists:orders,id'],
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string'],
            'project_manager_id' => ['nullable', 'exists:users,id'],
            'start_date' => ['nullable', 'date'],
            'deadline' => ['nullable', 'date'],
            'status' => ['required', 'in:Pending,Planning,In Development,In QA,Client Review,Completed,Delivered,Delayed,Cancelled'],
        ]);

        $order = Order::findOrFail($request->input('order_id'));
        $projectCode = OrderService::generateProjectCode();

        $project = Project::create([
            'project_code' => $projectCode,
            'order_id' => $order->id,
            'customer_id' => $order->customer_id,
            'project_manager_id' => $request->input('project_manager_id', auth()->id()),
            'name' => $request->input('name'),
            'category' => $request->input('category'),
            'description' => $request->input('description'),
            'start_date' => $request->input('start_date', now()->format('Y-m-d')),
            'deadline' => $request->input('deadline'),
            'status' => $request->input('status', 'Planning'),
            'progress_percent' => 0,
            'git_repository' => $request->input('git_repository'),
            'live_url' => $request->input('live_url'),
            'notes' => $request->input('notes'),
        ]);

        $order->update([
            'status' => 'Project Started',
            'project_manager_id' => $project->project_manager_id,
        ]);

        ActivityLog::log(
            action: 'project_created',
            description: "নতুন প্রজেক্ট তৈরি হয়েছে: {$project->project_code} ({$project->name})",
            subject: $project,
            userId: auth()->id()
        );

        return redirect()->route('projects.show', $project)
            ->with('success', "প্রজেক্ট {$project->project_code} সফলভাবে তৈরি হয়েছে।");
    }

    /**
     * Display project details, progress bar and tasks list.
     */
    public function show(Project $project): View
    {
        $project->load([
            'customer',
            'order',
            'projectManager',
            'tasks.assignedUser',
            'tasks.qaUser',
        ]);

        // Synchronize latest progress
        OrderService::syncProjectProgress($project);

        $taskCounts = [
            'total' => $project->tasks->count(),
            'pending' => $project->tasks->where('status', 'Pending')->count(),
            'working' => $project->tasks->where('status', 'Working')->count(),
            'reworking' => $project->tasks->where('status', 'Reworking')->count(),
            'completed' => $project->tasks->where('status', 'Completed')->count(),
            'checked' => $project->tasks->where('status', 'Checked')->count(),
            'delivered' => $project->tasks->where('status', 'Delivered')->count(),
        ];

        $teamMembers = User::where('status', 'active')->orderBy('name')->get();

        return view('projects.show', compact('project', 'taskCounts', 'teamMembers'));
    }

    /**
     * Show form for editing project.
     */
    public function edit(Project $project): View
    {
        $projectManagers = User::where('status', 'active')->orderBy('name')->get();
        $serviceCategories = Setting::get('service_categories', []);

        return view('projects.edit', compact('project', 'projectManagers', 'serviceCategories'));
    }

    /**
     * Update project information.
     */
    public function update(Request $request, Project $project): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string'],
            'project_manager_id' => ['nullable', 'exists:users,id'],
            'deadline' => ['nullable', 'date'],
            'status' => ['required', 'in:Pending,Planning,In Development,In QA,Client Review,Completed,Delivered,Delayed,Cancelled'],
        ]);

        $project->update($request->all());
        OrderService::syncProjectProgress($project);

        ActivityLog::log(
            action: 'project_updated',
            description: "প্রজেক্ট {$project->project_code} তথ্য আপডেট করা হয়েছে।",
            subject: $project,
            userId: auth()->id()
        );

        return redirect()->route('projects.show', $project)
            ->with('success', "প্রজেক্ট তথ্য সফলভাবে আপডেট হয়েছে।");
    }

    /**
     * Quick status update.
     */
    public function updateStatus(Request $request, Project $project): RedirectResponse
    {
        $request->validate([
            'status' => ['required', 'in:Pending,Planning,In Development,In QA,Client Review,Completed,Delivered,Delayed,Cancelled'],
        ]);

        $old = $project->status;
        $project->status = $request->input('status');
        $project->save();

        ActivityLog::log(
            action: 'project_status_updated',
            description: "প্রজেক্ট {$project->project_code} স্ট্যাটাস: '{$old}' -> '{$project->status}'",
            subject: $project,
            userId: auth()->id()
        );

        return back()->with('success', 'প্রজেক্ট স্ট্যাটাস সফলভাবে আপডেট করা হয়েছে।');
    }

    /**
     * Remove project.
     */
    public function destroy(Project $project): RedirectResponse
    {
        $code = $project->project_code;
        $project->delete();

        ActivityLog::log(
            action: 'project_deleted',
            description: "প্রজেক্ট {$code} মুছে ফেলা হয়েছে।",
            subject: null,
            userId: auth()->id()
        );

        return redirect()->route('projects.index')
            ->with('success', "প্রজেক্ট {$code} মুছে ফেলা হয়েছে।");
    }
}
