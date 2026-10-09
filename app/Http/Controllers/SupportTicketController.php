<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Project;
use App\Models\SupportTicket;
use App\Models\User;
use App\Services\PhoneHelper;
use App\Services\TicketService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SupportTicketController extends Controller
{
    protected TicketService $ticketService;

    public function __construct(TicketService $ticketService)
    {
        $this->ticketService = $ticketService;
    }

    /**
     * Display listing of support tickets with filters.
     */
    public function index(Request $request): View
    {
        $query = SupportTicket::with(['customer', 'project', 'assignedDeveloper', 'assignedManager']);

        // Search
        if ($search = $request->input('search')) {
            $normalizedSearch = PhoneHelper::normalize($search);
            $query->where(function ($q) use ($search, $normalizedSearch) {
                $q->where('ticket_no', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%")
                  ->orWhere('contact_name', 'like', "%{$search}%")
                  ->orWhere('company_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");

                if ($normalizedSearch) {
                    $q->orWhere('phone', 'like', "%{$normalizedSearch}%");
                }
            });
        }

        // Filters
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($priority = $request->input('priority')) {
            $query->where('priority', $priority);
        }

        if ($issueType = $request->input('issue_type')) {
            $query->where('issue_type', $issueType);
        }

        if ($devId = $request->input('assigned_developer_id')) {
            $query->where('assigned_developer_id', $devId);
        }

        $tickets = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();
        $metrics = $this->ticketService->getMetrics();

        // Get developers for filter dropdown
        $developers = User::whereHas('role', function ($q) {
            $q->whereIn('name', ['Developer', 'Project Manager', 'Admin', 'Super Admin']);
        })->orWhereNull('role_id')->get();

        return view('support-tickets.index', [
            'tickets' => $tickets,
            'metrics' => $metrics,
            'developers' => $developers,
            'statuses' => SupportTicket::STATUSES,
            'priorities' => SupportTicket::PRIORITIES,
            'issueTypes' => SupportTicket::ISSUE_TYPES,
        ]);
    }

    /**
     * Show the form for creating a new support ticket manually.
     */
    public function create(): View
    {
        $customers = Customer::orderBy('name')->get();
        $projects = Project::orderBy('name')->get();
        $orders = Order::orderBy('id', 'desc')->take(50)->get();
        $users = User::orderBy('name')->get();

        return view('support-tickets.create', [
            'customers' => $customers,
            'projects' => $projects,
            'orders' => $orders,
            'users' => $users,
            'issueTypes' => SupportTicket::ISSUE_TYPES,
            'priorities' => SupportTicket::PRIORITIES,
        ]);
    }

    /**
     * Store a newly created support ticket in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            'contact_name' => 'required|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:30',
            'whatsapp' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:255',
            'service_url' => 'nullable|url|max:255',
            'project_id' => 'nullable|exists:projects,id',
            'order_id' => 'nullable|exists:orders,id',
            'issue_type' => 'required|string|in:' . implode(',', SupportTicket::ISSUE_TYPES),
            'priority' => 'required|string|in:' . implode(',', SupportTicket::PRIORITIES),
            'subject' => 'required|string|max:255',
            'description' => 'required|string|min:5',
            'assigned_manager_id' => 'nullable|exists:users,id',
            'assigned_developer_id' => 'nullable|exists:users,id',
            'estimated_hours' => 'nullable|numeric|min:0',
            'internal_notes' => 'nullable|string',
            'attachment' => 'nullable|file|max:10240|mimes:jpg,jpeg,png,gif,pdf,zip,doc,docx,txt',
        ]);

        $ticket = $this->ticketService->createTicket(
            $validated,
            $request->file('attachment')
        );

        return redirect()->route('support-tickets.show', $ticket)
            ->with('success', "Support ticket {$ticket->ticket_no} created successfully.");
    }

    /**
     * Display the specified support ticket with conversation thread.
     */
    public function show(SupportTicket $supportTicket): View
    {
        $supportTicket->load([
            'customer',
            'project',
            'order',
            'assignedManager',
            'assignedDeveloper',
            'replies.user',
        ]);

        $users = User::orderBy('name')->get();

        return view('support-tickets.show', [
            'ticket' => $supportTicket,
            'users' => $users,
            'statuses' => SupportTicket::STATUSES,
            'priorities' => SupportTicket::PRIORITIES,
        ]);
    }

    /**
     * Show the form for editing the ticket.
     */
    public function edit(SupportTicket $supportTicket): View
    {
        $customers = Customer::orderBy('name')->get();
        $projects = Project::orderBy('name')->get();
        $orders = Order::orderBy('id', 'desc')->take(50)->get();
        $users = User::orderBy('name')->get();

        return view('support-tickets.edit', [
            'ticket' => $supportTicket,
            'customers' => $customers,
            'projects' => $projects,
            'orders' => $orders,
            'users' => $users,
            'issueTypes' => SupportTicket::ISSUE_TYPES,
            'priorities' => SupportTicket::PRIORITIES,
            'statuses' => SupportTicket::STATUSES,
        ]);
    }

    /**
     * Update the specified support ticket in storage.
     */
    public function update(Request $request, SupportTicket $supportTicket): RedirectResponse
    {
        $validated = $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            'contact_name' => 'required|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:30',
            'whatsapp' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:255',
            'service_url' => 'nullable|url|max:255',
            'project_id' => 'nullable|exists:projects,id',
            'order_id' => 'nullable|exists:orders,id',
            'issue_type' => 'required|string|in:' . implode(',', SupportTicket::ISSUE_TYPES),
            'priority' => 'required|string|in:' . implode(',', SupportTicket::PRIORITIES),
            'status' => 'required|string|in:' . implode(',', SupportTicket::STATUSES),
            'subject' => 'required|string|max:255',
            'description' => 'required|string|min:5',
            'assigned_manager_id' => 'nullable|exists:users,id',
            'assigned_developer_id' => 'nullable|exists:users,id',
            'estimated_hours' => 'nullable|numeric|min:0',
            'actual_hours' => 'nullable|numeric|min:0',
            'internal_notes' => 'nullable|string',
            'resolution_notes' => 'nullable|string',
        ]);

        $supportTicket->update($validated);

        return redirect()->route('support-tickets.show', $supportTicket)
            ->with('success', "Ticket {$supportTicket->ticket_no} updated successfully.");
    }

    /**
     * Remove the specified support ticket from storage.
     */
    public function destroy(SupportTicket $supportTicket): RedirectResponse
    {
        $ticketNo = $supportTicket->ticket_no;
        $supportTicket->delete();

        return redirect()->route('support-tickets.index')
            ->with('success', "Ticket {$ticketNo} deleted successfully.");
    }

    /**
     * Quick staff assignment.
     */
    public function assign(Request $request, SupportTicket $supportTicket): RedirectResponse
    {
        $validated = $request->validate([
            'assigned_manager_id' => 'nullable|exists:users,id',
            'assigned_developer_id' => 'nullable|exists:users,id',
        ]);

        $this->ticketService->assignStaff(
            $supportTicket,
            $validated['assigned_manager_id'] ?? null,
            $validated['assigned_developer_id'] ?? null
        );

        return back()->with('success', "Staff assignment updated for Ticket #{$supportTicket->ticket_no}.");
    }

    /**
     * Quick status transition and resolution.
     */
    public function updateStatus(Request $request, SupportTicket $supportTicket): RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|string|in:' . implode(',', SupportTicket::STATUSES),
            'resolution_notes' => 'nullable|string',
            'actual_hours' => 'nullable|numeric|min:0',
        ]);

        $this->ticketService->updateStatus(
            $supportTicket,
            $validated['status'],
            $validated['resolution_notes'] ?? null,
            $validated['actual_hours'] ? (float) $validated['actual_hours'] : null
        );

        return back()->with('success', "Ticket status updated to '{$validated['status']}'.");
    }

    /**
     * Add staff reply or internal note.
     */
    public function reply(Request $request, SupportTicket $supportTicket): RedirectResponse
    {
        $validated = $request->validate([
            'message' => 'required|string|min:2',
            'is_internal' => 'nullable|boolean',
            'attachment' => 'nullable|file|max:10240|mimes:jpg,jpeg,png,gif,pdf,zip,doc,docx,txt',
        ]);

        $senderType = auth()->user()->hasRole('Developer') ? 'Developer' : 'Agent';
        $isInternal = (bool) ($request->input('is_internal', false));

        $this->ticketService->addReply(
            ticket: $supportTicket,
            message: $validated['message'],
            senderType: $senderType,
            senderName: auth()->user()->name,
            userId: auth()->id(),
            attachment: $request->file('attachment'),
            isInternal: $isInternal
        );

        $typeLabel = $isInternal ? 'Internal note' : 'Reply';
        return back()->with('success', "{$typeLabel} posted successfully.");
    }
}
