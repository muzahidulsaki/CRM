<?php

namespace App\Http\Controllers;

use App\Models\SupportTicket;
use App\Services\PhoneHelper;
use App\Services\TicketService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PublicSupportController extends Controller
{
    protected TicketService $ticketService;

    public function __construct(TicketService $ticketService)
    {
        $this->ticketService = $ticketService;
    }

    /**
     * Show public support submission form.
     */
    public function create(): View
    {
        return view('public-support.create', [
            'issueTypes' => SupportTicket::ISSUE_TYPES,
            'priorities' => SupportTicket::PRIORITIES,
        ]);
    }

    /**
     * Handle public ticket submission.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'contact_name' => 'required|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'phone' => 'required|string|max:30',
            'whatsapp' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:255',
            'service_url' => 'nullable|url|max:255',
            'issue_type' => 'required|string|in:' . implode(',', SupportTicket::ISSUE_TYPES),
            'priority' => 'required|string|in:' . implode(',', SupportTicket::PRIORITIES),
            'subject' => 'required|string|max:255',
            'description' => 'required|string|min:10',
            'attachment' => 'nullable|file|max:10240|mimes:jpg,jpeg,png,gif,pdf,zip,doc,docx,txt',
        ]);

        $ticket = $this->ticketService->createTicket(
            $validated,
            $request->file('attachment')
        );

        return redirect()->route('public-support.track', $ticket->ticket_no)
            ->with('success', "Your support request has been submitted successfully! Ticket ID: {$ticket->ticket_no}. Please save this ID to track your progress.");
    }

    /**
     * Track a ticket by ticket number.
     */
    public function track(Request $request, ?string $ticket_no = null): View
    {
        $ticketCode = $ticket_no ?? $request->input('ticket_no');
        $ticket = null;
        $error = null;

        if ($ticketCode) {
            $ticketCode = strtoupper(trim($ticketCode));
            $ticket = SupportTicket::with(['publicReplies.user'])
                ->where('ticket_no', $ticketCode)
                ->first();

            if (!$ticket) {
                $error = "No support ticket found matching '{$ticketCode}'. Please verify your Ticket ID.";
            }
        }

        return view('public-support.track', [
            'ticket' => $ticket,
            'searchedTicketNo' => $ticketCode,
            'error' => $error,
        ]);
    }

    /**
     * Public client reply to an existing ticket.
     */
    public function reply(Request $request, string $ticket_no): RedirectResponse
    {
        $ticket = SupportTicket::where('ticket_no', strtoupper(trim($ticket_no)))->firstOrFail();

        $validated = $request->validate([
            'sender_name' => 'required|string|max:255',
            'message' => 'required|string|min:3',
            'attachment' => 'nullable|file|max:10240|mimes:jpg,jpeg,png,gif,pdf,zip,doc,docx,txt',
        ]);

        $this->ticketService->addReply(
            ticket: $ticket,
            message: $validated['message'],
            senderType: 'Client',
            senderName: $validated['sender_name'],
            attachment: $request->file('attachment'),
            isInternal: false
        );

        return redirect()->route('public-support.track', $ticket->ticket_no)
            ->with('success', 'Your reply has been posted successfully. Our technical team has been notified.');
    }
}
