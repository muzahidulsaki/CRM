<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\Customer;
use App\Models\SupportTicket;
use App\Models\TicketReply;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TicketService
{
    /**
     * Generate sequential ticket number (e.g., SUP-2026-00001)
     */
    public function generateTicketNo(): string
    {
        $year = date('Y');
        $prefix = "SUP-{$year}-";

        $latestTicket = SupportTicket::where('ticket_no', 'like', "{$prefix}%")
            ->orderBy('ticket_no', 'desc')
            ->first();

        if ($latestTicket) {
            $lastNumber = (int) substr($latestTicket->ticket_no, strlen($prefix));
            $nextNumber = str_pad($lastNumber + 1, 5, '0', STR_PAD_LEFT);
        } else {
            $nextNumber = '00001';
        }

        return $prefix . $nextNumber;
    }

    /**
     * Create a new ticket (from public portal or internal admin)
     */
    public function createTicket(array $data, ?UploadedFile $attachment = null): SupportTicket
    {
        return DB::transaction(function () use ($data, $attachment) {
            $ticketNo = $this->generateTicketNo();
            
            // Normalize phone
            $phone = $data['phone'] ?? null;
            $normalizedPhone = $phone ? PhoneHelper::normalize($phone) : null;
            $email = $data['email'] ?? null;

            // Auto-detect existing customer if customer_id not explicitly provided
            $customerId = $data['customer_id'] ?? null;
            if (!$customerId && ($normalizedPhone || $phone || $email)) {
                $existingCustomer = Customer::where(function ($q) use ($normalizedPhone, $phone, $email) {
                    if ($normalizedPhone) {
                        $q->where('normalized_phone', $normalizedPhone);
                    }
                    if ($phone) {
                        $q->orWhere('phone', $phone);
                    }
                    if ($email) {
                        $q->orWhere('email', $email);
                    }
                })->first();

                if ($existingCustomer) {
                    $customerId = $existingCustomer->id;
                    if (empty($data['company_name']) && $existingCustomer->company_name) {
                        $data['company_name'] = $existingCustomer->company_name;
                    }
                }
            }

            // Handle attachment
            $attachmentPath = null;
            if ($attachment) {
                $attachmentPath = $attachment->store('tickets', 'public');
            }

            $ticket = SupportTicket::create([
                'ticket_no' => $ticketNo,
                'customer_id' => $customerId,
                'contact_name' => $data['contact_name'],
                'company_name' => $data['company_name'] ?? null,
                'email' => $email,
                'phone' => $phone,
                'whatsapp' => $data['whatsapp'] ?? null,
                'service_url' => $data['service_url'] ?? null,
                'order_id' => $data['order_id'] ?? null,
                'project_id' => $data['project_id'] ?? null,
                'issue_type' => $data['issue_type'] ?? 'Technical Support',
                'priority' => $data['priority'] ?? 'Medium',
                'status' => 'New',
                'subject' => $data['subject'],
                'description' => $data['description'],
                'attachment_path' => $attachmentPath,
                'assigned_manager_id' => $data['assigned_manager_id'] ?? null,
                'assigned_developer_id' => $data['assigned_developer_id'] ?? null,
                'estimated_hours' => $data['estimated_hours'] ?? null,
                'internal_notes' => $data['internal_notes'] ?? null,
            ]);

            // Add initial system / creation reply log
            TicketReply::create([
                'support_ticket_id' => $ticket->id,
                'user_id' => auth()->id() ?? null,
                'sender_type' => auth()->check() ? 'Agent' : 'Client',
                'sender_name' => $data['contact_name'],
                'message' => "Ticket initiated: " . $ticket->subject . "\n\n" . $ticket->description,
                'attachment_path' => $attachmentPath,
                'is_internal' => false,
            ]);

            ActivityLog::log(
                action: 'created',
                module: 'support',
                description: "Created Support Ticket #{$ticket->ticket_no} for {$ticket->contact_name}",
                record: $ticket
            );

            return $ticket;
        });
    }

    /**
     * Assign manager and developer
     */
    public function assignStaff(SupportTicket $ticket, ?int $managerId, ?int $developerId): SupportTicket
    {
        $oldStatus = $ticket->status;
        $updates = [
            'assigned_manager_id' => $managerId,
            'assigned_developer_id' => $developerId,
        ];

        if ($ticket->status === 'New' && ($managerId || $developerId)) {
            $updates['status'] = 'Assigned';
        }

        $ticket->update($updates);

        $devName = $ticket->assignedDeveloper?->name ?? 'None';
        $mgrName = $ticket->assignedManager?->name ?? 'None';

        TicketReply::create([
            'support_ticket_id' => $ticket->id,
            'user_id' => auth()->id(),
            'sender_type' => 'System',
            'sender_name' => 'System',
            'message' => "Staff assignment updated: Manager -> {$mgrName}, Developer -> {$devName}.",
            'is_internal' => true,
        ]);

        ActivityLog::log(
            action: 'assigned',
            module: 'support',
            description: "Assigned staff to Ticket #{$ticket->ticket_no}: Dev ({$devName}), Mgr ({$mgrName})",
            record: $ticket
        );

        return $ticket->fresh();
    }

    /**
     * Change ticket status
     */
    public function updateStatus(SupportTicket $ticket, string $status, ?string $resolutionNotes = null, ?float $actualHours = null): SupportTicket
    {
        $oldStatus = $ticket->status;
        $updates = ['status' => $status];

        if ($status === 'Resolved') {
            $updates['resolved_at'] = now();
            if ($resolutionNotes) {
                $updates['resolution_notes'] = $resolutionNotes;
            }
        }

        if ($status === 'Closed') {
            $updates['closed_at'] = now();
        }

        if ($actualHours !== null) {
            $updates['actual_hours'] = $actualHours;
        }

        $ticket->update($updates);

        TicketReply::create([
            'support_ticket_id' => $ticket->id,
            'user_id' => auth()->id(),
            'sender_type' => 'System',
            'sender_name' => 'System',
            'message' => "Status changed from '{$oldStatus}' to '{$status}'." . ($resolutionNotes ? "\nResolution: " . $resolutionNotes : ''),
            'is_internal' => false,
        ]);

        ActivityLog::log(
            action: 'status_updated',
            module: 'support',
            description: "Changed status of Ticket #{$ticket->ticket_no} to {$status}",
            record: $ticket
        );

        return $ticket->fresh();
    }

    /**
     * Add a reply to the ticket
     */
    public function addReply(
        SupportTicket $ticket,
        string $message,
        string $senderType,
        string $senderName,
        ?int $userId = null,
        ?UploadedFile $attachment = null,
        bool $isInternal = false
    ): TicketReply {
        $attachmentPath = null;
        if ($attachment) {
            $attachmentPath = $attachment->store('tickets/replies', 'public');
        }

        $reply = TicketReply::create([
            'support_ticket_id' => $ticket->id,
            'user_id' => $userId,
            'sender_type' => $senderType,
            'sender_name' => $senderName,
            'message' => $message,
            'attachment_path' => $attachmentPath,
            'is_internal' => $isInternal,
        ]);

        // Auto transition status if client replies to a resolved ticket -> Reopened
        if ($senderType === 'Client' && in_array($ticket->status, ['Resolved', 'Closed'])) {
            $ticket->update(['status' => 'Reopened']);
        } elseif ($senderType === 'Developer' && in_array($ticket->status, ['New', 'Assigned'])) {
            $ticket->update(['status' => 'Working']);
        }

        return $reply;
    }

    /**
     * Get high-level ticket metrics for dashboard
     */
    public function getMetrics(): array
    {
        return [
            'total_tickets' => SupportTicket::count(),
            'open_tickets' => SupportTicket::whereIn('status', ['New', 'Assigned', 'Working', 'Reworking', 'Testing'])->count(),
            'critical_tickets' => SupportTicket::where('priority', 'Critical')->whereNotIn('status', ['Resolved', 'Closed'])->count(),
            'resolved_tickets' => SupportTicket::whereIn('status', ['Resolved', 'Client Confirmed', 'Closed'])->count(),
            'pending_assignment' => SupportTicket::where('status', 'New')->count(),
        ];
    }
}
