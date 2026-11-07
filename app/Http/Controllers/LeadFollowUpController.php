<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Lead;
use App\Models\LeadFollowUp;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LeadFollowUpController extends Controller
{
    /**
     * Store a new follow-up / activity log for a lead.
     */
    public function store(Request $request, Lead $lead): RedirectResponse
    {
        $request->validate([
            'type' => ['required', 'in:phone_call,whatsapp,email,meeting,internal_note'],
            'notes' => ['required', 'string'],
            'scheduled_at' => ['nullable', 'date'],
            'status' => ['required', 'in:scheduled,completed,missed,cancelled'],
            'subject' => ['nullable', 'string', 'max:255'],
            'next_action' => ['nullable', 'string', 'max:255'],
            'next_followup_date' => ['nullable', 'date'],
        ]);

        $conductedAt = in_array($request->input('status'), ['completed']) ? now() : null;

        $followUp = LeadFollowUp::create([
            'lead_id' => $lead->id,
            'customer_id' => $lead->customer_id,
            'user_id' => auth()->id(),
            'type' => $request->input('type'),
            'subject' => $request->input('subject'),
            'notes' => $request->input('notes'),
            'scheduled_at' => $request->input('scheduled_at') ?? now(),
            'conducted_at' => $conductedAt,
            'status' => $request->input('status'),
            'next_action' => $request->input('next_action'),
            'next_followup_date' => $request->input('next_followup_date'),
        ]);

        // Update lead's last contact date and next follow-up date
        $leadUpdates = [];
        if ($conductedAt) {
            $leadUpdates['last_contact_date'] = $conductedAt;
            $leadUpdates['followup_last_date'] = $conductedAt->format('Y-m-d');
        }
        if ($request->filled('next_followup_date')) {
            $nextDate = $request->input('next_followup_date');
            $leadUpdates['next_followup_date'] = $nextDate;
            $leadUpdates['followup_next_date'] = \Carbon\Carbon::parse($nextDate)->format('Y-m-d');
        } elseif ($request->input('status') === 'completed') {
            // When marked as completed and no new date is scheduled, clear past overdue dates
            $effectiveDate = $lead->followup_next_date ?? $lead->next_followup_date;
            if ($effectiveDate && \Carbon\Carbon::parse($effectiveDate)->isPast()) {
                $leadUpdates['next_followup_date'] = null;
                $leadUpdates['followup_next_date'] = null;
            }
        }
        if (!empty($leadUpdates)) {
            $lead->update($leadUpdates);
        }

        ActivityLog::log(
            action: 'lead_followup_added',
            description: "New follow-up added to lead {$lead->lead_code} ({$followUp->type_label}).",
            subject: $lead,
            userId: auth()->id()
        );

        return redirect()->route('leads.show', $lead)
            ->with('success', 'Follow-up saved successfully.');
    }

    /**
     * Mark a scheduled follow-up as completed.
     */
    public function complete(Request $request, LeadFollowUp $followUp): RedirectResponse
    {
        $followUp->update([
            'status' => 'completed',
            'conducted_at' => now(),
            'notes' => $followUp->notes . ($request->filled('outcome') ? "\n[Outcome: " . $request->input('outcome') . ']' : ''),
        ]);

        if ($followUp->lead) {
            $leadUpdates = [
                'last_contact_date' => now(),
                'followup_last_date' => now()->format('Y-m-d'),
            ];

            // If the completed follow-up was the scheduled one, check if another scheduled follow-up exists
            $nextScheduled = LeadFollowUp::where('lead_id', $followUp->lead_id)
                ->where('id', '!=', $followUp->id)
                ->where('status', 'scheduled')
                ->where('scheduled_at', '>=', now())
                ->orderBy('scheduled_at', 'asc')
                ->first();

            if ($nextScheduled) {
                $leadUpdates['next_followup_date'] = $nextScheduled->scheduled_at;
                $leadUpdates['followup_next_date'] = \Carbon\Carbon::parse($nextScheduled->scheduled_at)->format('Y-m-d');
            } else {
                $effectiveDate = $followUp->lead->followup_next_date ?? $followUp->lead->next_followup_date;
                if ($effectiveDate && \Carbon\Carbon::parse($effectiveDate)->isPast()) {
                    $leadUpdates['next_followup_date'] = null;
                    $leadUpdates['followup_next_date'] = null;
                }
            }

            $followUp->lead->update($leadUpdates);
        }

        ActivityLog::log(
            action: 'lead_followup_completed',
            description: "Follow-up completed ({$followUp->type_label}).",
            subject: $followUp->lead,
            userId: auth()->id()
        );

        return back()->with('success', 'Follow-up marked as completed.');
    }

    /**
     * Remove a follow-up.
     */
    public function destroy(LeadFollowUp $followUp): RedirectResponse
    {
        $lead = $followUp->lead;
        $followUp->delete();

        return redirect()->route('leads.show', $lead)
            ->with('success', 'Follow-up record deleted.');
    }
}
