<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Lead;
use App\Models\Setting;
use App\Models\User;
use App\Services\LeadService;
use App\Services\PhoneHelper;
use Illuminate\Support\Facades\DB;
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

        if ($source = $request->input('source', $request->input('lead_source'))) {
            $query->where('lead_source', $source);
        }

        if ($businessCategory = $request->input('business_type', $request->input('business_category'))) {
            $query->where('business_type', $businessCategory);
        }

        if ($service = $request->input('service')) {
            $query->where('interested_service', $service);
        }

        if ($assignedTo = $request->input('assigned_to')) {
            $query->where('assigned_to', $assignedTo);
        }

        // Follow-up Filters (Today, Prev./Overdue, Upcoming)
        $today = now()->format('Y-m-d');
        $isOverdueFollowup = ($request->input('today_followup') === 'overdue' || $request->input('follow_up') === 'overdue');
        $isTodayFollowup = ($request->input('today_followup') === '1' || $request->input('today_followup') === 'today' || $request->input('follow_up') === 'today');
        $isUpcomingFollowup = ($request->input('follow_up') === 'upcoming');

        if ($isOverdueFollowup) {
            $query->whereNotNull(DB::raw('COALESCE(followup_next_date, next_followup_date)'))
                  ->whereDate(DB::raw('COALESCE(followup_next_date, next_followup_date)'), '<', $today)
                  ->whereNotIn('lead_status', ['Won', 'Lost']);
        } elseif ($isTodayFollowup) {
            $query->whereNotNull(DB::raw('COALESCE(followup_next_date, next_followup_date)'))
                  ->whereDate(DB::raw('COALESCE(followup_next_date, next_followup_date)'), $today)
                  ->whereNotIn('lead_status', ['Won', 'Lost']);
        } elseif ($isUpcomingFollowup) {
            $query->whereNotNull(DB::raw('COALESCE(followup_next_date, next_followup_date)'))
                  ->whereDate(DB::raw('COALESCE(followup_next_date, next_followup_date)'), '>', $today);
        }

        // Today Meeting Date Filter
        if ($request->boolean('today_meeting') || $request->filled('meeting_date')) {
            $meetingDate = $request->input('meeting_date') ?: now()->format('Y-m-d');
            $query->whereDate('next_meeting_datetime', $meetingDate);
        }

        // Why Not Order Filter
        if ($whyNotOrder = $request->input('why_not_order')) {
            $query->where('why_not_order', $whyNotOrder);
        }

        // Existing Order Filter (has orders, or by category, matched via customer_id or phone)
        if ($existingOrder = $request->input('existing_order')) {
            if ($existingOrder === 'has_orders') {
                $query->where(function ($q) {
                    $q->whereHas('customer.orders')
                      ->orWhereIn('phone', function ($sub) {
                          $sub->select('phone')->from('customers')->whereIn('id', function ($sub2) {
                              $sub2->select('customer_id')->from('orders')->whereNull('deleted_at');
                          });
                      })
                      ->orWhereIn('normalized_phone', function ($sub) {
                          $sub->select('normalized_phone')->from('customers')->whereIn('id', function ($sub2) {
                              $sub2->select('customer_id')->from('orders')->whereNull('deleted_at');
                          });
                      });
                });
            } elseif ($existingOrder === 'no_orders') {
                $query->whereDoesntHave('customer.orders')
                      ->whereNotIn('phone', function ($sub) {
                          $sub->select('phone')->from('customers')->whereIn('id', function ($sub2) {
                              $sub2->select('customer_id')->from('orders')->whereNull('deleted_at');
                          });
                      });
            } else {
                $query->where(function ($q) use ($existingOrder) {
                    $q->whereHas('customer.orders', function ($oq) use ($existingOrder) {
                        $oq->where('service_category', 'like', "%{$existingOrder}%");
                    })
                    ->orWhereIn('phone', function ($sub) use ($existingOrder) {
                        $sub->select('phone')->from('customers')->whereIn('id', function ($sub2) use ($existingOrder) {
                            $sub2->select('customer_id')->from('orders')->where('service_category', 'like', "%{$existingOrder}%")->whereNull('deleted_at');
                        });
                    });
                });
            }
        }

        $perPageInput = $request->input('per_page', 15);
        if (strtolower((string)$perPageInput) === 'all') {
            $totalCount = (clone $query)->count();
            $perPageCount = max(1, $totalCount);
        } elseif (is_numeric($perPageInput) && intval($perPageInput) > 0) {
            $perPageCount = min(1000, intval($perPageInput));
        } else {
            $perPageCount = 15;
        }

        $leads = $query->orderBy('id', 'desc')->paginate($perPageCount)->withQueryString();

        // Metrics for summary badges
        $today = now()->format('Y-m-d');
        $metrics = [
            'total' => Lead::count(),
            'new' => Lead::where('lead_status', 'New')->count(),
            'interested' => Lead::where('lead_status', 'Interested')->count(),
            'proposal' => Lead::where('lead_status', 'Proposal Sent')->count(),
            'won' => Lead::where('lead_status', 'Won')->count(),
            'lost' => Lead::where('lead_status', 'Lost')->count(),
            'today_followups' => Lead::whereNotNull(DB::raw('COALESCE(followup_next_date, next_followup_date)'))
                ->whereDate(DB::raw('COALESCE(followup_next_date, next_followup_date)'), $today)
                ->whereNotIn('lead_status', ['Won', 'Lost'])
                ->count(),
            'overdue_followups' => Lead::whereNotNull(DB::raw('COALESCE(followup_next_date, next_followup_date)'))
                ->whereDate(DB::raw('COALESCE(followup_next_date, next_followup_date)'), '<', $today)
                ->whereNotIn('lead_status', ['Won', 'Lost'])
                ->count(),
        ];

        $dropdowns = $this->getFormDropdowns();

        $dbCategories = Lead::whereNotNull('business_type')
            ->where('business_type', '!=', '')
            ->distinct()
            ->pluck('business_type')
            ->toArray();
        $businessCategories = array_values(array_unique(array_merge($dropdowns['businessCategories'], $dbCategories)));

        return view('leads.index', array_merge([
            'leads' => $leads,
            'metrics' => $metrics,
            'leadSources' => $dropdowns['leadSources'],
            'businessCategories' => $businessCategories,
            'serviceCategories' => Setting::get('service_categories', []),
            'users' => $dropdowns['users'],
            'whyNotOrderOptions' => $dropdowns['whyNotOrderOptions'],
        ], $dropdowns));
    }

    /**
     * Show the form for creating a new lead.
     */
    public function create(): View
    {
        $nextLeadCode = LeadService::generateLeadCode();
        $dropdowns = $this->getFormDropdowns();

        return view('leads.create', array_merge(['nextLeadCode' => $nextLeadCode], $dropdowns));
    }

    /**
     * AJAX endpoint to check duplicates while typing in form
     */
    public function checkDuplicate(Request $request): JsonResponse
    {
        $phone = $request->input('phone');
        $whatsapp = $request->input('whatsapp');
        $imo = $request->input('imo_number', $request->input('imo'));
        $email = $request->input('email');
        $excludeId = $request->input('exclude_id');

        $result = LeadService::checkDuplicate(
            $phone,
            $whatsapp,
            $email,
            $excludeId ? intval($excludeId) : null,
            $imo
        );

        return response()->json($result);
    }

    /**
     * Store a newly created lead in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        // Support either field name
        if (!$request->has('lead_temperature') && $request->has('client_temperature')) {
            $request->merge(['lead_temperature' => $request->input('client_temperature')]);
        }

        $request->validate([
            'name' => ['nullable', 'string', 'max:255'],
            'company_name' => ['nullable', 'string', 'max:255'],
            'business_type' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'lead_source' => ['required', 'string', 'max:255'],
            'lead_temperature' => ['required', 'in:Hot,Warm,Cold'],
            'lead_status' => ['required', 'in:New,Contacted,Interested,Meeting/Demo,Proposal Sent,Negotiation,Won,Lost'],
            'budget' => ['nullable', 'numeric', 'min:0'],
            'discussed_amount' => ['nullable', 'numeric', 'min:0'],
            'assigned_to' => ['nullable', 'exists:users,id'],
            'next_followup_date' => ['nullable', 'date'],
        ]);

        // Strict Duplicate Check (Mobile Number, WhatsApp Number, Imo Number & Email Address)
        $duplicateCheck = LeadService::checkDuplicate(
            $request->input('phone'),
            $request->input('whatsapp'),
            $request->input('email'),
            null,
            $request->input('imo_number')
        );

        if ($duplicateCheck['is_duplicate']) {
            return back()
                ->withInput()
                ->withErrors($duplicateCheck['field_errors'] ?? [])
                ->with('duplicate_warning', $duplicateCheck);
        }

        $leadCode = LeadService::generateLeadCode();

        $data = $request->except(['_token', 'allow_duplicate']);
        $data['lead_code'] = $leadCode;
        $data['name'] = $request->filled('name')
            ? $request->input('name')
            : ($request->filled('company_name') ? $request->input('company_name') : '-');
        $data['lead_source'] = $request->input('lead_source');
        $data['lead_status'] = $request->input('lead_status', 'New');
        $data['lead_temperature'] = $request->input('lead_temperature', 'Warm');
        $data['created_by'] = auth()->id();

        // Two-way sync for followup date on create
        if (!empty($data['followup_next_date']) && empty($data['next_followup_date'])) {
            $data['next_followup_date'] = $data['followup_next_date'];
        } elseif (!empty($data['next_followup_date']) && empty($data['followup_next_date'])) {
            $data['followup_next_date'] = \Carbon\Carbon::parse($data['next_followup_date'])->format('Y-m-d');
        }

        if (!empty($data['interested_products']) && empty($data['interested_service'])) {
            $data['interested_service'] = $data['interested_products'];
        }

        $lead = Lead::create($data);

        // Activity log
        ActivityLog::log(
            action: 'lead_created',
            description: "New lead created: {$lead->lead_code} ({$lead->name})",
            subject: $lead,
            userId: auth()->id()
        );

        return redirect()->route('leads.index')
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
    public function edit(Request $request, Lead $lead): View
    {
        $dropdowns = $this->getFormDropdowns();
        $returnTo = $request->input('return_to');
        if (empty($returnTo)) {
            $prev = url()->previous();
            if ($prev && !str_contains($prev, route('leads.edit', $lead))) {
                $returnTo = $prev;
            }
        }

        return view('leads.edit', array_merge([
            'lead' => $lead,
            'returnTo' => $returnTo ?: route('leads.index'),
        ], $dropdowns));
    }

    /**
     * Helper for standardized dropdown options matching CRM Format.xlsx
     */
    private function getFormDropdowns(): array
    {
        return [
            'whatsappStatuses' => ['Active', 'Delivered', 'Seen', 'Replied', 'Unreachable', 'Blocked'],
            'emailStatuses' => ['Valid', 'Sent', 'Bounced', 'Opened', 'Replied', 'Invalid'],
            'messengerStatuses' => ['Active', 'Contacted', 'Seen', 'Unresponsive'],
            'meetingPlatforms' => ['Google Meet', 'Zoom', 'WhatsApp Call', 'Imo Call', 'Messenger', 'Phone Call', 'In-Person (Office)', 'Other'],
            'srsStatuses' => ['Pending', 'In Discussion', 'Drafting', 'Under Review', 'Approved', 'Rejected'],
            'whyNotOrderOptions' => [
                'On Hold',
                'Trust Issue',
                'Payment Issue',
                'Quality Issue',
                'Timing Issue',
                'Delay',
                'No Need',
            ],
            'potentialOrderOptions' => ['High', 'Medium', 'Low', 'Future Prospect'],
            'retentionsMarketingOptions' => ['Active', 'Queued', 'Follow-up Required', 'Not Applicable'],
            'repeatMarketingOptions' => ['Active', 'Queued', 'Follow-up Required', 'Not Applicable'],
            'businessCategories' => [
                'Ecommerce-Fashion',
                'Ecommerce-Electronics',
                'Ecommerce-Cosmetics',
                'Ecommerce-Grocery',
                'Ecommerce-Others',
                'Super Shop',
                'Showroom Business',
                'Travel Agency',
                'Restaurant Business',
                'Cleaning Service',
                'Constructions Business',
                'Hotel Business',
                'Digital Business',
                'Comapany/ Factory',
                'School & College',
                'Real Estate',
                'Others Business',
            ],
            'countries' => [
                'Saudi Arab',
                'UAE',
                'Qatar',
                'Oman',
                'Malaysia',
                'Kuwait',
                'Bahrain',
                'Bangladesh',
                'Netrakona',
                'USA',
                'UK',
                'Canada',
                'Australia',
                'Others'
            ],
            'leadSources' => Setting::get('lead_sources', []),
            'serviceCategories' => Setting::get('service_categories', []),
            'users' => User::where('status', 'active')->orderBy('name')->get(),
        ];
    }

    /**
     * Update the specified lead in storage.
     */
    public function update(Request $request, Lead $lead): RedirectResponse
    {
        $request->validate([
            'name' => ['nullable', 'string', 'max:255'],
            'company_name' => ['nullable', 'string', 'max:255'],
            'business_type' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'lead_source' => ['required', 'string', 'max:255'],
            'lead_temperature' => ['required', 'in:Hot,Warm,Cold'],
            'lead_status' => ['required', 'in:New,Contacted,Interested,Meeting/Demo,Proposal Sent,Negotiation,Won,Lost'],
            'budget' => ['nullable', 'numeric', 'min:0'],
            'discussed_amount' => ['nullable', 'numeric', 'min:0'],
            'assigned_to' => ['nullable', 'exists:users,id'],
            'next_followup_date' => ['nullable', 'date'],
            'followup_next_date' => ['nullable', 'date'],
        ]);

        $oldStatus = $lead->lead_status;
        $oldAssigned = $lead->assigned_to;

        $updateData = $request->all();
        if (empty($updateData['name'])) {
            $updateData['name'] = $request->filled('company_name') ? $request->input('company_name') : '-';
        }
        if (!empty($updateData['interested_products']) && empty($updateData['interested_service'])) {
            $updateData['interested_service'] = $updateData['interested_products'];
        }

        // Two-way synchronization for follow-up date on edit
        if ($request->has('followup_next_date')) {
            $nextDate = $request->input('followup_next_date');
            $updateData['followup_next_date'] = !empty($nextDate) ? $nextDate : null;
            $updateData['next_followup_date'] = !empty($nextDate) ? $nextDate : null;
        } elseif ($request->has('next_followup_date')) {
            $nextDate = $request->input('next_followup_date');
            $updateData['next_followup_date'] = !empty($nextDate) ? $nextDate : null;
            $updateData['followup_next_date'] = !empty($nextDate) ? \Carbon\Carbon::parse($nextDate)->format('Y-m-d') : null;
        }

        $lead->update($updateData);

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

        $returnTo = $request->input('return_to');
        if (!empty($returnTo) && filter_var($returnTo, FILTER_VALIDATE_URL)) {
            return redirect($returnTo)
                ->with('success', "Lead {$lead->lead_code} updated successfully.");
        }

        return redirect()->route('leads.index')
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

        return back()
            ->with('success', "Lead {$leadCode} deleted successfully.");
    }
}
