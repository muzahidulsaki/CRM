<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Project;
use App\Models\SupportTicket;
use App\Models\TicketReply;
use App\Services\TicketService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class PortalController extends Controller
{
    /**
     * Get the logged-in user's linked customer record.
     */
    protected function getCustomer(): ?Customer
    {
        $user = Auth::user();

        // 1. Direct relationship check
        if ($user->customer) {
            return $user->customer;
        }

        // 2. Direct customer where user_id matches
        $customer = Customer::where('user_id', $user->id)->first();
        if ($customer) {
            return $customer;
        }

        // 3. Fallback: match by email
        if ($user->email) {
            $customer = Customer::where('email', $user->email)->first();
            if ($customer) {
                $customer->update(['user_id' => $user->id]);
                return $customer;
            }
        }

        return null;
    }

    /**
     * Portal Dashboard
     */
    public function dashboard(): View
    {
        $customer = $this->getCustomer();
        
        if (!$customer) {
            return view('portal.unlinked');
        }

        $projects = Project::where('customer_id', $customer->id)
            ->latest()
            ->take(5)
            ->get();

        $invoices = Invoice::where('customer_id', $customer->id)
            ->latest('issue_date')
            ->take(5)
            ->get();

        $tickets = SupportTicket::where('customer_id', $customer->id)
            ->latest()
            ->take(5)
            ->get();

        $stats = [
            'active_projects' => Project::where('customer_id', $customer->id)->whereIn('status', ['Planning', 'In Progress', 'Testing', 'QA/Review'])->count(),
            'total_projects' => Project::where('customer_id', $customer->id)->count(),
            'unpaid_invoices_count' => Invoice::where('customer_id', $customer->id)->whereIn('status', ['Sent', 'Partially Paid', 'Overdue'])->count(),
            'total_due' => (float) Invoice::where('customer_id', $customer->id)->sum('due_amount'),
            'open_tickets' => SupportTicket::where('customer_id', $customer->id)->whereNotIn('status', ['Resolved', 'Closed'])->count(),
        ];

        return view('portal.dashboard', compact('customer', 'projects', 'invoices', 'tickets', 'stats'));
    }

    /**
     * My Projects
     */
    public function projects(): View
    {
        $customer = $this->getCustomer();
        if (!$customer) return view('portal.unlinked');

        $projects = Project::where('customer_id', $customer->id)
            ->with(['projectManager'])
            ->withCount('tasks')
            ->latest()
            ->paginate(10);

        return view('portal.projects.index', compact('customer', 'projects'));
    }

    /**
     * Show Project Details
     */
    public function showProject(Project $project): View
    {
        $customer = $this->getCustomer();
        if (!$customer || $project->customer_id !== $customer->id) {
            abort(403, 'Unauthorized access: this project does not belong to your account.');
        }

        $project->load(['tasks', 'order', 'projectManager']);

        return view('portal.projects.show', compact('customer', 'project'));
    }

    /**
     * My Invoices
     */
    public function invoices(): View
    {
        $customer = $this->getCustomer();
        if (!$customer) return view('portal.unlinked');

        $invoices = Invoice::where('customer_id', $customer->id)
            ->latest('issue_date')
            ->paginate(10);

        $summary = [
            'total_billed' => (float) Invoice::where('customer_id', $customer->id)->sum('total_amount'),
            'total_paid' => (float) Invoice::where('customer_id', $customer->id)->sum('paid_amount'),
            'total_due' => (float) Invoice::where('customer_id', $customer->id)->sum('due_amount'),
        ];

        return view('portal.invoices.index', compact('customer', 'invoices', 'summary'));
    }

    /**
     * View Invoice
     */
    public function showInvoice(Invoice $invoice): View
    {
        $customer = $this->getCustomer();
        if (!$customer || $invoice->customer_id !== $customer->id) {
            abort(403, 'Unauthorized access: this invoice does not belong to your account.');
        }

        $invoice->load(['items', 'customer']);

        return view('portal.invoices.show', compact('customer', 'invoice'));
    }

    /**
     * My Support Tickets
     */
    public function tickets(): View
    {
        $customer = $this->getCustomer();
        if (!$customer) return view('portal.unlinked');

        $tickets = SupportTicket::where('customer_id', $customer->id)
            ->latest()
            ->paginate(10);

        return view('portal.tickets.index', compact('customer', 'tickets'));
    }

    /**
     * Create Ticket Form
     */
    public function createTicket(): View
    {
        $customer = $this->getCustomer();
        if (!$customer) return view('portal.unlinked');

        $projects = Project::where('customer_id', $customer->id)->get();

        return view('portal.tickets.create', compact('customer', 'projects'));
    }

    /**
     * Store Ticket
     */
    public function storeTicket(Request $request, TicketService $ticketService): RedirectResponse
    {
        $customer = $this->getCustomer();
        if (!$customer) return redirect()->route('portal.dashboard');

        $validated = $request->validate([
            'subject' => ['required', 'string', 'max:255'],
            'issue_type' => ['required', 'string'],
            'priority' => ['required', 'in:Low,Medium,High,Critical'],
            'project_id' => ['nullable', 'exists:projects,id'],
            'service_url' => ['nullable', 'url', 'max:255'],
            'description' => ['required', 'string'],
            'attachment' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf,zip,txt', 'max:10240'],
        ]);

        $ticketData = [
            'customer_id' => $customer->id,
            'contact_name' => $customer->name,
            'company_name' => $customer->company_name,
            'email' => $customer->email ?: Auth::user()->email,
            'phone' => $customer->phone,
            'whatsapp' => $customer->whatsapp,
            'subject' => $validated['subject'],
            'issue_type' => $validated['issue_type'],
            'priority' => $validated['priority'],
            'project_id' => $validated['project_id'] ?? null,
            'service_url' => $validated['service_url'] ?? null,
            'description' => $validated['description'],
            'status' => 'New',
        ];

        $attachment = $request->file('attachment');
        $ticket = $ticketService->createTicket($ticketData, $attachment);

        return redirect()->route('portal.tickets.show', $ticket)
            ->with('success', "Support ticket {$ticket->ticket_no} submitted successfully! Our technical team is reviewing it.");
    }

    /**
     * Show Ticket Thread & Chat
     */
    public function showTicket(SupportTicket $ticket): View
    {
        $customer = $this->getCustomer();
        if (!$customer || $ticket->customer_id !== $customer->id) {
            abort(403, 'Unauthorized access: this ticket does not belong to your account.');
        }

        $ticket->load(['replies.user', 'project']);

        return view('portal.tickets.show', compact('customer', 'ticket'));
    }

    /**
     * Reply to Ticket
     */
    public function replyTicket(Request $request, SupportTicket $ticket): RedirectResponse
    {
        $customer = $this->getCustomer();
        if (!$customer || $ticket->customer_id !== $customer->id) {
            abort(403, 'Unauthorized access: this ticket does not belong to your account.');
        }

        $request->validate([
            'message' => ['required', 'string'],
            'attachment' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf,zip,txt', 'max:10240'],
        ]);

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('ticket_attachments', 'public');
        }

        TicketReply::create([
            'support_ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'sender_type' => 'Client',
            'sender_name' => $customer->name,
            'message' => $request->message,
            'attachment_path' => $attachmentPath,
            'is_internal' => false,
        ]);

        // If ticket was resolved or closed, client reply reopens it
        if (in_array($ticket->status, ['Resolved', 'Closed'])) {
            $ticket->update(['status' => 'Reopened']);
        } else {
            $ticket->touch();
        }

        return back()->with('success', 'Your reply has been posted successfully.');
    }

    /**
     * Profile Page
     */
    public function profile(): View
    {
        $customer = $this->getCustomer();
        $user = Auth::user();

        return view('portal.profile', compact('customer', 'user'));
    }

    /**
     * Update Profile / Password
     */
    public function updateProfile(Request $request): RedirectResponse
    {
        $user = Auth::user();
        $customer = $this->getCustomer();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'current_password' => ['nullable', 'required_with:new_password', 'current_password'],
            'new_password' => ['nullable', 'string', 'min:6', 'confirmed'],
        ]);

        $user->name = $request->name;
        if ($request->filled('new_password')) {
            $user->password = Hash::make($request->new_password);
        }
        $user->save();

        if ($customer) {
            $customer->update([
                'name' => $request->name,
                'phone' => $request->phone ?: $customer->phone,
            ]);
        }

        return back()->with('success', 'Your profile and security credentials have been updated.');
    }
}
