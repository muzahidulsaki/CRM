<?php

namespace App\Services;

use App\Models\Lead;
use App\Models\Customer;
use App\Models\LeadFollowUp;
use App\Models\ServiceRenewal;
use App\Models\Invoice;
use App\Models\Expense;
use App\Models\ApiToken;
use App\Models\WebhookLog;
use App\Models\CrmNotification;
use App\Services\PhoneHelper;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AutomationService
{
    /**
     * Ingest an external lead from website contact form or landing page (SRS Section 39)
     */
    public function processIncomingLead(array $data, ?string $tokenString = null): array
    {
        // 1. Verify API Token if provided
        if ($tokenString) {
            $apiToken = ApiToken::where('token', $tokenString)->where('is_active', true)->first();
            if (!$apiToken) {
                return ['success' => false, 'error' => 'Invalid or inactive API Token', 'code' => 401];
            }
            $apiToken->update(['last_used_at' => now()]);
        }

        $phone = $data['phone'] ?? '';
        $normalizedPhone = PhoneHelper::normalize($phone);
        $email = !empty($data['email']) ? strtolower(trim($data['email'])) : null;
        $name = trim($data['name'] ?? 'Website Visitor');
        $service = $data['interested_service'] ?? $data['service'] ?? 'General Inquiry';
        $source = $data['source'] ?? 'website';
        $notes = $data['message'] ?? $data['notes'] ?? null;
        $budget = isset($data['budget']) ? (float)$data['budget'] : null;

        // 2. Duplicate Check (SRS Section 7 & 39)
        $existingLead = null;
        if (!empty($normalizedPhone)) {
            $existingLead = Lead::where('normalized_phone', $normalizedPhone)
                ->where('created_at', '>=', now()->subDays(30))
                ->first();
        }
        if (!$existingLead && !empty($email)) {
            $existingLead = Lead::where('email', $email)
                ->where('created_at', '>=', now()->subDays(30))
                ->first();
        }

        if ($existingLead) {
            // Append interaction rather than creating a duplicate row
            $systemUserId = \Illuminate\Support\Facades\Auth::id() ?? \App\Models\User::where('email', 'admin@projuktisheba.com')->value('id') ?? 1;
            LeadFollowUp::create([
                'lead_id' => $existingLead->id,
                'customer_id' => $existingLead->customer_id,
                'user_id' => $systemUserId,
                'type' => 'internal_note',
                'scheduled_at' => now(),
                'conducted_at' => now(),
                'status' => 'completed',
                'subject' => 'Website Form Re-submission: ' . $service,
                'notes' => $notes ?: 'Customer submitted inquiry again from website.',
            ]);

            CrmNotification::create([
                'type' => 'new_lead',
                'title' => 'Lead Inquiry Re-submission: ' . $existingLead->name,
                'message' => "{$existingLead->name} ({$phone}) re-submitted inquiry for: {$service}",
                'link_url' => route('leads.show', $existingLead->id),
                'priority' => 'high',
            ]);

            return [
                'success' => true,
                'is_duplicate' => true,
                'lead_code' => $existingLead->lead_code,
                'message' => 'Existing lead updated with new interaction.',
                'lead_id' => $existingLead->id,
            ];
        }

        // 3. Create New Lead
        $year = date('Y');
        $count = Lead::whereYear('created_at', $year)->count() + 1;
        $leadCode = 'LEAD-' . $year . '-' . str_pad($count, 5, '0', STR_PAD_LEFT);

        // Check if existing Customer has this phone
        $existingCustomer = !empty($normalizedPhone) ? Customer::where('normalized_phone', $normalizedPhone)->first() : null;

        $newLead = Lead::create([
            'lead_code' => $leadCode,
            'customer_id' => $existingCustomer?->id,
            'name' => $name,
            'phone' => $phone,
            'normalized_phone' => $normalizedPhone,
            'email' => $email,
            'lead_source' => $source,
            'interested_service' => $service,
            'budget' => $budget,
            'lead_status' => 'new',
            'notes' => $notes,
        ]);

        // Dispatch Notification
        CrmNotification::create([
            'type' => 'new_lead',
            'title' => 'New Website Lead: ' . $name,
            'message' => "New lead received from website! Service: {$service}, Phone: {$phone}",
            'link_url' => route('leads.show', $newLead->id),
            'priority' => 'urgent',
        ]);

        return [
            'success' => true,
            'is_duplicate' => false,
            'lead_code' => $leadCode,
            'lead_id' => $newLead->id,
            'message' => 'New lead captured successfully.',
        ];
    }

    /**
     * Process WhatsApp Business Webhook message (SRS Section 40)
     */
    public function processIncomingWhatsApp(array $payload): array
    {
        // Extract WhatsApp payload details (supports standard Meta Cloud API or simplified test payload)
        $externalId = null;
        $senderPhone = null;
        $senderName = null;
        $messageBody = null;

        if (isset($payload['entry'][0]['changes'][0]['value']['messages'][0])) {
            $msg = $payload['entry'][0]['changes'][0]['value']['messages'][0];
            $contact = $payload['entry'][0]['changes'][0]['value']['contacts'][0] ?? [];
            $externalId = $msg['id'] ?? null;
            $senderPhone = $msg['from'] ?? null;
            $senderName = $contact['profile']['name'] ?? 'WhatsApp User';
            $messageBody = $msg['text']['body'] ?? ($msg['type'] ?? 'Media/Attachment');
        } else {
            // Simplified format
            $externalId = $payload['message_id'] ?? $payload['id'] ?? ('wa_' . Str::random(16));
            $senderPhone = $payload['from'] ?? $payload['phone'] ?? null;
            $senderName = $payload['name'] ?? 'WhatsApp User';
            $messageBody = $payload['message'] ?? $payload['body'] ?? '';
        }

        if (!$senderPhone) {
            return ['success' => false, 'error' => 'Missing sender phone number', 'code' => 422];
        }

        $normalizedPhone = PhoneHelper::normalize($senderPhone);

        // 1. Deduplication check on external_id (SRS Section 40)
        if ($externalId) {
            $alreadyProcessed = WebhookLog::where('external_id', $externalId)->first();
            if ($alreadyProcessed) {
                return [
                    'success' => true,
                    'status' => 'duplicate',
                    'message' => 'WhatsApp message was already processed.',
                ];
            }
        }

        // 2. Identify if Customer or Lead exists
        $customer = Customer::where('normalized_phone', $normalizedPhone)->first();
        $lead = null;

        if (!$customer) {
            $lead = Lead::where('normalized_phone', $normalizedPhone)->first();
            if (!$lead) {
                // Auto-create new Lead from WhatsApp
                $year = date('Y');
                $count = Lead::whereYear('created_at', $year)->count() + 1;
                $leadCode = 'LEAD-' . $year . '-' . str_pad($count, 5, '0', STR_PAD_LEFT);

                $lead = Lead::create([
                    'lead_code' => $leadCode,
                    'name' => $senderName,
                    'phone' => $senderPhone,
                    'normalized_phone' => $normalizedPhone,
                    'lead_source' => 'whatsapp',
                    'interested_service' => 'WhatsApp Inquiry',
                    'lead_status' => 'new',
                    'notes' => 'Auto-created from incoming WhatsApp message: ' . $messageBody,
                ]);
            }
        }

        // 3. Record communication in LeadFollowUp
        if ($lead) {
            $systemUserId = \Illuminate\Support\Facades\Auth::id() ?? \App\Models\User::where('email', 'admin@projuktisheba.com')->value('id') ?? 1;
            LeadFollowUp::create([
                'lead_id' => $lead->id,
                'customer_id' => $lead->customer_id,
                'user_id' => $systemUserId,
                'type' => 'whatsapp',
                'scheduled_at' => now(),
                'conducted_at' => now(),
                'status' => 'completed',
                'subject' => 'WhatsApp Message: ' . Str::limit($messageBody, 40),
                'notes' => $messageBody,
            ]);
        }

        // 4. Save Webhook Log
        WebhookLog::create([
            'provider' => 'whatsapp',
            'event_type' => 'incoming_message',
            'external_id' => $externalId,
            'sender_phone' => $senderPhone,
            'normalized_phone' => $normalizedPhone,
            'sender_name' => $senderName,
            'message_body' => $messageBody,
            'payload' => $payload,
            'status' => 'processed',
            'response_message' => 'Message linked successfully',
        ]);

        // 5. Dispatch in-app notification
        $targetName = $customer ? $customer->name : $lead->name;
        $targetUrl = $customer ? route('customers.show', $customer->id) : route('leads.show', $lead->id);

        CrmNotification::create([
            'type' => 'whatsapp_msg',
            'title' => "WhatsApp Message: {$targetName}",
            'message' => "Message: \"{$messageBody}\" ({$senderPhone})",
            'link_url' => $targetUrl,
            'priority' => 'high',
        ]);

        return [
            'success' => true,
            'status' => 'processed',
            'customer_id' => $customer?->id,
            'lead_id' => $lead?->id,
            'message' => 'WhatsApp message ingested and connected to CRM record.',
        ];
    }

    /**
     * Run automated reminder checks and generate notification alerts (SRS Section 20, 22, 25, 32)
     */
    public function runAutomatedReminders(): array
    {
        $today = Carbon::today();
        $in7Days = $today->copy()->addDays(7);
        $stats = [
            'renewals_alerted' => 0,
            'invoices_alerted' => 0,
            'followups_alerted' => 0,
            'expenses_alerted' => 0,
            'total_notifications' => 0,
        ];

        // 1. Service Renewals Reminders (Upcoming, Due Today, Overdue)
        $upcomingRenewals = ServiceRenewal::whereBetween('next_due_date', [$today, $in7Days])
            ->with('customer')
            ->get();
        foreach ($upcomingRenewals as $ren) {
            $isToday = $ren->next_due_date->isToday();
            $title = $isToday ? "Urgent: Service Renewal Due Today ({$ren->service_name})" : "Upcoming Service Renewal ({$ren->service_name})";
            $priority = $isToday ? 'urgent' : 'high';

            // Avoid repeating identical notification today
            $alreadyAlerted = CrmNotification::where('type', 'renewal_alert')
                ->where('title', 'like', "%{$ren->renewal_no}%")
                ->whereDate('created_at', $today)
                ->exists();

            if (!$alreadyAlerted) {
                CrmNotification::create([
                    'type' => 'renewal_alert',
                    'title' => "{$title} - {$ren->renewal_no}",
                    'message' => "Client: {$ren->customer->name}, Fee: BDT {$ren->renewal_fee}, Due Date: {$ren->next_due_date->format('d M, Y')}",
                    'link_url' => route('renewals.show', $ren->id),
                    'priority' => $priority,
                ]);
                $stats['renewals_alerted']++;
                $stats['total_notifications']++;
            }
        }

        // 2. Overdue Invoices Reminders
        $overdueInvoices = Invoice::where('due_amount', '>', 0)
            ->whereNotNull('due_date')
            ->where('due_date', '<', $today)
            ->with('customer')
            ->get();
        foreach ($overdueInvoices as $inv) {
            $alreadyAlerted = CrmNotification::where('type', 'invoice_overdue')
                ->where('title', 'like', "%{$inv->invoice_no}%")
                ->whereDate('created_at', $today)
                ->exists();

            if (!$alreadyAlerted) {
                CrmNotification::create([
                    'type' => 'invoice_overdue',
                    'title' => "Overdue Invoice Reminder: {$inv->invoice_no}",
                    'message' => "Client: {$inv->customer->name}, Due: BDT {$inv->due_amount}, Due Date was: {$inv->due_date->format('d M, Y')}",
                    'link_url' => route('invoices.show', $inv->id),
                    'priority' => 'urgent',
                ]);
                $stats['invoices_alerted']++;
                $stats['total_notifications']++;
            }
        }

        // 3. Lead Follow-ups Scheduled for Today
        $todayFollowUps = LeadFollowUp::whereDate('scheduled_at', $today)
            ->where('status', 'pending')
            ->with(['lead', 'customer'])
            ->get();
        foreach ($todayFollowUps as $fu) {
            $target = $fu->customer?->name ?? $fu->lead?->name ?? 'Lead';
            $alreadyAlerted = CrmNotification::where('type', 'follow_up')
                ->where('title', 'like', "%Follow-up #{$fu->id}%")
                ->whereDate('created_at', $today)
                ->exists();

            if (!$alreadyAlerted) {
                CrmNotification::create([
                    'type' => 'follow_up',
                    'title' => "Today's Follow-up: {$target} (Follow-up #{$fu->id})",
                    'message' => "Subject: {$fu->subject}, Time: {$fu->scheduled_at->format('h:i A')}",
                    'link_url' => $fu->lead ? route('leads.show', $fu->lead->id) : route('customers.show', $fu->customer_id),
                    'priority' => 'high',
                ]);
                $stats['followups_alerted']++;
                $stats['total_notifications']++;
            }
        }

        // 4. Recurring Expenses Reminders
        $recurringExpenses = Expense::where('is_recurring', true)
            ->whereNotNull('due_date')
            ->whereBetween('due_date', [$today, $in7Days])
            ->get();
        foreach ($recurringExpenses as $exp) {
            $alreadyAlerted = CrmNotification::where('type', 'expense_alert')
                ->where('title', 'like', "%{$exp->expense_no}%")
                ->whereDate('created_at', $today)
                ->exists();

            if (!$alreadyAlerted) {
                CrmNotification::create([
                    'type' => 'expense_alert',
                    'title' => "Upcoming Expense Reminder: {$exp->title} ({$exp->expense_no})",
                    'message' => "Category: {$exp->category}, Amount: BDT {$exp->amount}, Due Date: {$exp->due_date->format('d M, Y')}",
                    'link_url' => route('expenses.show', $exp->id),
                    'priority' => 'medium',
                ]);
                $stats['expenses_alerted']++;
                $stats['total_notifications']++;
            }
        }

        return $stats;
    }
}
