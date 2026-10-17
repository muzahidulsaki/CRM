<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Lead;
use App\Models\Setting;
use App\Models\User;
use App\Services\LeadService;
use App\Services\PhoneHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LeadController extends Controller
{
    /**
     * Display a listing of leads with filters & search.
     */
    public function index(Request $request): View
    {
        $query = Lead::with(['assignedUser', 'creator', 'customer']);

        // Search query
        if ($search = $request->input('search')) {
            $normalizedSearch = PhoneHelper::normalize($search);
            $query->where(function ($q) use ($search, $normalizedSearch) {
                $q->where('lead_code', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%")
                  ->orWhere('company_name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");

                if ($normalizedSearch) {
                    $q->orWhere('normalized_phone', 'like', "%{$normalizedSearch}%")
                      ->orWhere('normalized_whatsapp', 'like', "%{$normalizedSearch}%");
                }
            });
        }

        // Filters
        if ($status = $request->input('status')) {
            $query->where('lead_status', $status);
        }

        if ($temperature = $request->input('temperature')) {
            $query->where('lead_temperature', $temperature);
        }

        if ($source = $request->input('source')) {
            $query->where('lead_source', $source);
        }

        if ($service = $request->input('service')) {
            $query->where('interested_service', $service);
        }

        if ($assignedTo = $request->input('assigned_to')) {
            $query->where('assigned_to', $assignedTo);
        }

        // Date range filter for follow-up
        if ($followUpFilter = $request->input('follow_up')) {
            $today = now()->format('Y-m-d');
            if ($followUpFilter === 'today') {
                $query->whereDate('next_followup_date', $today);
            } elseif ($followUpFilter === 'overdue') {
                $query->whereDate('next_followup_date', '<', $today)
                      ->whereNotIn('lead_status', ['Won', 'Lost']);
            } elseif ($followUpFilter === 'upcoming') {
                $query->whereDate('next_followup_date', '>', $today);
            }
        }

        $leads = $query->orderBy('updated_at', 'desc')->paginate(15)->withQueryString();

        // Metrics for summary badges
        $today = now()->format('Y-m-d');
        $metrics = [
            'total' => Lead::count(),
            'new' => Lead::where('lead_status', 'New')->count(),
            'interested' => Lead::where('lead_status', 'Interested')->count(),
            'proposal' => Lead::where('lead_status', 'Proposal Sent')->count(),
            'won' => Lead::where('lead_status', 'Won')->count(),
            'lost' => Lead::where('lead_status', 'Lost')->count(),
            'today_followups' => Lead::whereDate('next_followup_date', $today)->count(),
            'overdue_followups' => Lead::whereDate('next_followup_date', '<', $today)->whereNotIn('lead_status', ['Won', 'Lost'])->count(),
        ];

        $leadSources = Setting::get('lead_sources', []);
        $serviceCategories = Setting::get('service_categories', []);
        $users = User::where('status', 'active')->orderBy('name')->get();

        return view('leads.index', compact('leads', 'metrics', 'leadSources', 'serviceCategories', 'users'));
    }

    /**
     * Show the form for creating a new lead.
     */
    public function create(): View
    {
        $leadSources = Setting::get('lead_sources', []);
        $serviceCategories = Setting::get('service_categories', []);
        $users = User::where('status', 'active')->orderBy('name')->get();

        return view('leads.create', compact('leadSources', 'serviceCategories', 'users'));
    }

    /**
     * AJAX endpoint to check duplicates while typing in form
     */
    public function checkDuplicate(Request $request): JsonResponse
    {
        $phone = $request->input('phone');
        $whatsapp = $request->input('whatsapp');
        $email = $request->input('email');
        $excludeId = $request->input('exclude_id');

        $result = LeadService::checkDuplicate($phone, $whatsapp, $email, $excludeId ? intval($excludeId) : null);

        return response()->json($result);
    }

    /**
     * Store a newly created lead in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'lead_source' => ['required', 'string'],
            'lead_temperature' => ['required', 'in:Hot,Warm,Cold'],
            'budget' => ['nullable', 'numeric', 'min:0'],
            'discussed_amount' => ['nullable', 'numeric', 'min:0'],
            'assigned_to' => ['nullable', 'exists:users,id'],
            'next_followup_date' => ['nullable', 'date'],
        ]);

        // Duplicate Check
        if (!$request->boolean('allow_duplicate')) {
            $duplicateCheck = LeadService::checkDuplicate(
                $request->input('phone'),
                $request->input('whatsapp'),
                $request->input('email')
            );

            if ($duplicateCheck['is_duplicate']) {
                return back()
                    ->withInput()
                    ->with('duplicate_warning', $duplicateCheck);
            }
        }

        $leadCode = LeadService::generateLeadCode();

        $lead = Lead::create([
            'lead_code' => $leadCode,
            'name' => $request->input('name'),
            'company_name' => $request->input('company_name'),
            'business_type' => $request->input('business_type'),
            'phone' => $request->input('phone'),
            'whatsapp' => $request->input('whatsapp'),
            'email' => $request->input('email'),
            'address' => $request->input('address'),
            'city' => $request->input('city'),
            'country' => $request->input('country', 'Bangladesh'),
            'website' => $request->input('website'),
            'facebook' => $request->input('facebook'),
            'instagram' => $request->input('instagram'),
            'other_socials' => $request->input('other_socials'),
            'lead_source' => $request->input('lead_source'),
            'interested_service' => $request->input('interested_service'),
            'budget' => $request->input('budget'),
            'discussed_amount' => $request->input('discussed_amount'),
            'lead_temperature' => $request->input('lead_temperature', 'Warm'),
            'lead_status' => 'New',
            'assigned_to' => $request->input('assigned_to'),
            'expected_work_date' => $request->input('expected_work_date'),
            'next_followup_date' => $request->input('next_followup_date'),
            'notes' => $request->input('notes'),
            'created_by' => auth()->id(),
        ]);

        // Activity log
        ActivityLog::log(
            action: 'lead_created',
            description: "New lead created: {$lead->lead_code} ({$lead->name})",
            subject: $lead,
            userId: auth()->id()
        );

        return redirect()->route('leads.show', $lead)
            ->with('success', "Lead {$lead->lead_code} created successfully.");
    }

    /**
     * Display the specified lead details & timeline.
     */
    public function show(Lead $lead): View
    {
        $lead->load(['assignedUser', 'creator', 'customer', 'followUps.user', 'quotations.items']);
        $leadSources = Setting::get('lead_sources', []);
        $serviceCategories = Setting::get('service_categories', []);
        $users = User::where('status', 'active')->orderBy('name')->get();

        return view('leads.show', compact('lead', 'leadSources', 'serviceCategories', 'users'));
    }

    /**
     * Show the form for editing the specified lead.
     */
    public function edit(Lead $lead): View
    {
        $leadSources = Setting::get('lead_sources', []);
        $serviceCategories = Setting::get('service_categories', []);
        $users = User::where('status', 'active')->orderBy('name')->get();

        return view('leads.edit', compact('lead', 'leadSources', 'serviceCategories', 'users'));
    }

    /**
     * Update the specified lead in storage.
     */
    public function update(Request $request, Lead $lead): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'lead_source' => ['required', 'string'],
            'lead_temperature' => ['required', 'in:Hot,Warm,Cold'],
            'lead_status' => ['required', 'in:New,Contacted,Interested,Meeting/Demo,Proposal Sent,Negotiation,Won,Lost'],
            'budget' => ['nullable', 'numeric', 'min:0'],
            'discussed_amount' => ['nullable', 'numeric', 'min:0'],
            'assigned_to' => ['nullable', 'exists:users,id'],
            'next_followup_date' => ['nullable', 'date'],
        ]);

        $oldStatus = $lead->lead_status;
        $oldAssigned = $lead->assigned_to;

        $lead->update($request->all());

        // Status change log
        if ($oldStatus !== $lead->lead_status) {
            ActivityLog::log(
                action: 'lead_status_changed',
                description: "Lead {$lead->lead_code} status changed from '{$oldStatus}' to '{$lead->lead_status}'.",
                subject: $lead,
                userId: auth()->id()
            );

            // If updated to Won, auto convert to customer if desired
            if ($lead->lead_status === 'Won' && !$lead->customer_id) {
                LeadService::convertToCustomer($lead, auth()->id());
            }
        }

        // Assignment change log
        if ($oldAssigned !== $lead->assigned_to) {
            $assignedName = $lead->assignedUser ? $lead->assignedUser->name : 'Unassigned';
            ActivityLog::log(
                action: 'lead_reassigned',
                description: "Lead {$lead->lead_code} assigned to: {$assignedName}",
                subject: $lead,
                userId: auth()->id()
            );
        }

        return redirect()->route('leads.show', $lead)
            ->with('success', "Lead {$lead->lead_code} updated successfully.");
    }

    /**
     * Quick status update (e.g. from pipeline or detail dropdown).
     */
    public function updateStatus(Request $request, Lead $lead): JsonResponse|RedirectResponse
    {
        $request->validate([
            'lead_status' => ['nullable', 'in:New,Contacted,Interested,Meeting/Demo,Proposal Sent,Negotiation,Won,Lost'],
            'lead_temperature' => ['nullable', 'in:Hot,Warm,Cold'],
            'lost_reason' => ['nullable', 'string'],
        ]);

        $oldStatus = $lead->lead_status;

        if ($request->filled('lead_status')) {
            $lead->lead_status = $request->input('lead_status');
        }

        if ($request->filled('lead_temperature')) {
            $lead->lead_temperature = $request->input('lead_temperature');
        }

        if ($request->filled('lost_reason')) {
            $lead->lost_reason = $request->input('lost_reason');
        }

        $lead->save();

        if ($oldStatus !== $lead->lead_status) {
            ActivityLog::log(
                action: 'lead_status_changed',
                description: "Status changed: '{$oldStatus}' -> '{$lead->lead_status}'",
                subject: $lead,
                userId: auth()->id()
            );

            if ($lead->lead_status === 'Won' && !$lead->customer_id) {
                LeadService::convertToCustomer($lead, auth()->id());
            }
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Status updated successfully',
                'lead' => $lead,
            ]);
        }

        return back()->with('success', 'Status updated successfully.');
    }

    /**
     * Convert Lead to Customer explicitly.
     */
    public function convert(Lead $lead): RedirectResponse
    {
        $customer = LeadService::convertToCustomer($lead, auth()->id());

        return redirect()->route('customers.show', $customer)
            ->with('success', "Lead successfully converted to customer {$customer->customer_code}!");
    }

    /**
     * Visual Sales Pipeline (Kanban Board)
     */
    public function pipeline(Request $request): View
    {
        $stages = [
            'New' => 'New Lead',
            'Contacted' => 'Contacted',
            'Interested' => 'Interested',
            'Meeting/Demo' => 'Meeting/Demo',
            'Proposal Sent' => 'Proposal Sent',
            'Negotiation' => 'Negotiation',
            'Won' => 'Won',
            'Lost' => 'Lost',
        ];

        $leadsByStage = [];
        foreach (array_keys($stages) as $stage) {
            $leadsByStage[$stage] = Lead::with('assignedUser')
                ->where('lead_status', $stage)
                ->orderBy('updated_at', 'desc')
                ->take(30)
                ->get();
        }

        return view('leads.pipeline', compact('stages', 'leadsByStage'));
    }

    /**
     * Remove the specified lead from storage.
     */
    public function destroy(Lead $lead): RedirectResponse
    {
        $leadCode = $lead->lead_code;
        $lead->delete();

        ActivityLog::log(
            action: 'lead_deleted',
            description: "Lead {$leadCode} deleted.",
            subject: null,
            userId: auth()->id()
        );

        return redirect()->route('leads.index')
            ->with('success', "Lead {$leadCode} deleted successfully.");
    }
}
