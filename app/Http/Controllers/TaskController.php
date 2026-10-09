<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Services\OrderService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TaskController extends Controller
{
    /**
     * Display a listing of tasks with filters.
     */
    public function index(Request $request): View
    {
        $query = Task::with(['project.customer', 'assignedUser', 'assignedBy', 'qaUser']);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('task_code', 'like', "%{$search}%")
                  ->orWhere('title', 'like', "%{$search}%")
                  ->orWhereHas('project', function ($pq) use ($search) {
                      $pq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($priority = $request->input('priority')) {
            $query->where('priority', $priority);
        }

        if ($type = $request->input('task_type')) {
            $query->where('task_type', $type);
        }

        if ($assignedTo = $request->input('assigned_to')) {
            $query->where('assigned_to', $assignedTo);
        }

        if ($projectId = $request->input('project_id')) {
            $query->where('project_id', $projectId);
        }

        $tasks = $query->orderBy('deadline', 'asc')->paginate(15)->withQueryString();

        $metrics = [
            'total' => Task::count(),
            'pending' => Task::where('status', 'Pending')->count(),
            'working' => Task::where('status', 'Working')->count(),
            'reworking' => Task::where('status', 'Reworking')->count(),
            'completed' => Task::where('status', 'Completed')->count(),
            'checked' => Task::where('status', 'Checked')->count(),
            'delivered' => Task::where('status', 'Delivered')->count(),
        ];

        $projects = Project::whereNotIn('status', ['Completed', 'Delivered', 'Cancelled'])->orderBy('name')->get();
        $teamMembers = User::where('status', 'active')->orderBy('name')->get();

        return view('tasks.index', compact('tasks', 'metrics', 'projects', 'teamMembers'));
    }

    /**
     * Visual Kanban Board for Tasks (Pending -> Working -> Reworking -> Completed -> Checked -> Delivered)
     */
    public function kanban(Request $request): View
    {
        $stages = [
            'Pending' => 'অপেক্ষমাণ (Pending)',
            'Working' => 'চলমান কাজ (Working)',
            'Reworking' => 'রি-ওয়ার্কিং (Reworking)',
            'Completed' => 'সাবমিটেড / রিভিউতে (In Review)',
            'Checked' => 'কিউএ পাস (QA Checked)',
            'Delivered' => 'ডেলিভার্ড (Delivered)',
        ];

        $projectId = $request->input('project_id');
        $assignedTo = $request->input('assigned_to');

        $tasksByStage = [];
        foreach (array_keys($stages) as $stage) {
            $stageQuery = Task::with(['project', 'assignedUser'])
                ->where('status', $stage);

            if ($projectId) {
                $stageQuery->where('project_id', $projectId);
            }
            if ($assignedTo) {
                $stageQuery->where('assigned_to', $assignedTo);
            }

            $tasksByStage[$stage] = $stageQuery->orderBy('deadline', 'asc')->take(30)->get();
        }

        $projects = Project::whereNotIn('status', ['Completed', 'Delivered', 'Cancelled'])->orderBy('name')->get();
        $teamMembers = User::where('status', 'active')->orderBy('name')->get();

        return view('tasks.kanban', compact('stages', 'tasksByStage', 'projects', 'teamMembers'));
    }

    /**
     * Store a newly created task.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'project_id' => ['required', 'exists:projects,id'],
            'title' => ['required', 'string', 'max:255'],
            'task_type' => ['required', 'string'],
            'priority' => ['required', 'in:Low,Medium,High,Urgent'],
            'assigned_to' => ['nullable', 'exists:users,id'],
            'deadline' => ['nullable', 'date'],
            'estimated_hours' => ['nullable', 'numeric', 'min:0'],
        ]);

        $taskCode = OrderService::generateTaskCode();

        $task = Task::create([
            'task_code' => $taskCode,
            'project_id' => $request->input('project_id'),
            'assigned_to' => $request->input('assigned_to'),
            'assigned_by' => auth()->id(),
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'task_type' => $request->input('task_type', 'Development'),
            'priority' => $request->input('priority', 'Medium'),
            'status' => 'Pending',
            'start_date' => $request->input('start_date', now()->format('Y-m-d')),
            'deadline' => $request->input('deadline'),
            'estimated_hours' => $request->input('estimated_hours'),
        ]);

        OrderService::syncProjectProgress($task->project);

        ActivityLog::log(
            action: 'task_created',
            description: "নতুন টাস্ক তৈরি হয়েছে: {$task->task_code} ({$task->title})",
            subject: $task,
            userId: auth()->id()
        );

        return back()->with('success', "টাস্ক {$task->task_code} সফলভাবে তৈরি হয়েছে।");
    }

    /**
     * Display task details and submission/QA workflow.
     */
    public function show(Task $task): View
    {
        $task->load(['project.customer', 'project.projectManager', 'assignedUser', 'assignedBy', 'qaUser']);
        $teamMembers = User::where('status', 'active')->orderBy('name')->get();

        return view('tasks.show', compact('task', 'teamMembers'));
    }

    /**
     * Update task details.
     */
    public function update(Request $request, Task $task): RedirectResponse
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'priority' => ['required', 'in:Low,Medium,High,Urgent'],
            'status' => ['required', 'in:Pending,Working,Reworking,Completed,Checked,Delivered'],
            'assigned_to' => ['nullable', 'exists:users,id'],
            'deadline' => ['nullable', 'date'],
        ]);

        $task->update($request->all());
        OrderService::syncProjectProgress($task->project);

        ActivityLog::log(
            action: 'task_updated',
            description: "টাস্ক {$task->task_code} তথ্য আপডেট করা হয়েছে।",
            subject: $task,
            userId: auth()->id()
        );

        return back()->with('success', "টাস্ক সফলভাবে আপডেট হয়েছে।");
    }

    /**
     * Worker submits their finished work for QA review.
     */
    public function submitWork(Request $request, Task $task): RedirectResponse
    {
        $request->validate([
            'submission_notes' => ['required', 'string'],
            'submission_url' => ['nullable', 'url'],
            'actual_hours' => ['nullable', 'numeric', 'min:0'],
        ]);

        $task->update([
            'submission_notes' => $request->input('submission_notes'),
            'submission_url' => $request->input('submission_url'),
            'actual_hours' => $request->input('actual_hours', $task->actual_hours),
            'submitted_at' => now(),
            'status' => 'Completed', // In Review status awaiting QA
            'qa_status' => 'Pending',
        ]);

        OrderService::syncProjectProgress($task->project);

        ActivityLog::log(
            action: 'task_work_submitted',
            description: "টাস্ক {$task->task_code} সম্পন্ন করে পর্যালোচনার জন্য জমা দেওয়া হয়েছে।",
            subject: $task,
            userId: auth()->id()
        );

        return back()->with('success', 'কাজ সফলভাবে পর্যালোচনার জন্য জমা দেওয়া হয়েছে!');
    }

    /**
     * QA Review & Testing action (Passed -> Checked, Failed -> Reworking).
     */
    public function qaReview(Request $request, Task $task): RedirectResponse
    {
        $request->validate([
            'qa_status' => ['required', 'in:Passed,Failed'],
            'qa_notes' => ['nullable', 'string'],
        ]);

        $passed = $request->input('qa_status') === 'Passed';

        $task->update([
            'qa_by' => auth()->id(),
            'qa_status' => $request->input('qa_status'),
            'qa_notes' => $request->input('qa_notes'),
            'checked_at' => now(),
            // If passed -> Checked, if failed -> Reworking
            'status' => $passed ? 'Checked' : 'Reworking',
        ]);

        OrderService::syncProjectProgress($task->project);

        ActivityLog::log(
            action: 'task_qa_reviewed',
            description: "টাস্ক {$task->task_code} QA রিভিউ: " . ($passed ? 'অনুমোদিত (Passed)' : 'ত্রুটি থাকায় রি-ওয়ার্কিং (Failed)'),
            subject: $task,
            userId: auth()->id()
        );

        return back()->with('success', $passed ? 'টাস্কটি QA পাস করেছে এবং অনুমোদিত হয়েছে!' : 'টাস্কটিতে সমস্যা পাওয়ায় রি-ওয়ার্কিং হিসেবে চিহ্নিত হয়েছে।');
    }

    /**
     * Quick status change.
     */
    public function updateStatus(Request $request, Task $task): RedirectResponse
    {
        $request->validate([
            'status' => ['required', 'in:Pending,Working,Reworking,Completed,Checked,Delivered'],
        ]);

        $task->update(['status' => $request->input('status')]);
        OrderService::syncProjectProgress($task->project);

        return back()->with('success', 'টাস্ক স্ট্যাটাস সফলভাবে পরিবর্তন হয়েছে।');
    }

    /**
     * Remove task.
     */
    public function destroy(Task $task): RedirectResponse
    {
        $project = $task->project;
        $code = $task->task_code;
        $task->delete();

        OrderService::syncProjectProgress($project);

        ActivityLog::log(
            action: 'task_deleted',
            description: "টাস্ক {$code} মুছে ফেলা হয়েছে।",
            subject: null,
            userId: auth()->id()
        );

        return back()->with('success', "টাস্ক {$code} মুছে ফেলা হয়েছে।");
    }
}
