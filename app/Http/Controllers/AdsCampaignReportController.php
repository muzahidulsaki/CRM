<?php

namespace App\Http\Controllers;

use App\Models\AdsCampaignReport;
use App\Models\Lead;
use App\Models\Customer;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdsCampaignReportController extends Controller
{
    public function index(Request $request)
    {
        $query = AdsCampaignReport::query()->with(['lead', 'customer', 'order', 'creator']);

        // Search
        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('business_name', 'like', "%{$search}%")
                  ->orWhere('whatsapp', 'like', "%{$search}%")
                  ->orWhere('lead_code', 'like', "%{$search}%")
                  ->orWhere('ads_post_link', 'like', "%{$search}%")
                  ->orWhere('business_category', 'like', "%{$search}%");
            });
        }

        // Filter: Month
        if ($request->filled('month')) {
            $query->where('order_month', $request->input('month'));
        }

        // Filter: Category
        if ($request->filled('category')) {
            $query->where('business_category', $request->input('category'));
        }

        // Filter: Activity
        if ($request->filled('activity')) {
            $query->where('activity', $request->input('activity'));
        }

        // Filter: Today Check
        if ($request->filled('today_check')) {
            $check = $request->input('today_check');
            if ($check === 'Checked') {
                $query->where('today_check', 'Checked');
            } elseif ($check === 'Unchecked') {
                $query->where(function ($q) {
                    $q->whereNull('today_check')->orWhere('today_check', '!=', 'Checked');
                });
            } else {
                $query->where('today_check', $check);
            }
        }

        // Metrics calculations
        $metrics = [
            'total_campaigns' => AdsCampaignReport::count(),
            'active_campaigns' => AdsCampaignReport::where('activity', 'Active')->count(),
            'total_bdt' => AdsCampaignReport::sum('total_amount'),
            'total_dollar' => AdsCampaignReport::sum('total_dollar'),
            'running_dollar' => AdsCampaignReport::sum('running_dollar'),
            'dollar_due' => AdsCampaignReport::sum('dollar_due'),
            'checked_today' => AdsCampaignReport::where('today_check', 'Checked')->whereDate('today_checked_at', Carbon::today())->count(),
        ];

        // Unique filter options
        $months = AdsCampaignReport::whereNotNull('order_month')->distinct()->pluck('order_month');
        $categories = AdsCampaignReport::whereNotNull('business_category')->distinct()->pluck('business_category');

        $reports = $query->latest('id')->paginate(20)->withQueryString();

        return view('ads-reports.index', compact('reports', 'metrics', 'months', 'categories'));
    }

    public function create(Request $request)
    {
        $leads = Lead::latest('id')->limit(50)->get(['id', 'lead_code', 'name', 'phone', 'whatsapp', 'company_name', 'business_category']);
        $customers = Customer::latest('id')->limit(50)->get(['id', 'name', 'phone', 'company_name']);

        $prefilledLead = null;
        if ($request->filled('lead_id')) {
            $prefilledLead = Lead::find($request->input('lead_id'));
        }

        return view('ads-reports.create', compact('leads', 'customers', 'prefilledLead'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'lead_code' => 'nullable|string|max:50',
            'lead_id' => 'nullable|exists:leads,id',
            'customer_id' => 'nullable|exists:customers,id',
            'order_id' => 'nullable|exists:orders,id',
            'order_source' => 'nullable|string|max:100',
            'activity' => 'required|string|in:Active,Paused,Completed',
            'total_amount' => 'nullable|numeric|min:0',
            'total_dollar' => 'nullable|numeric|min:0',
            'ads_post_link' => 'nullable|string|max:500',
            'business_category' => 'nullable|string|max:100',
            'business_name' => 'required|string|max:255',
            'whatsapp' => 'nullable|string|max:50',
            'order_month' => 'nullable|string|max:50',
            'order_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'order_details' => 'nullable|string',
            'starting_date' => 'nullable|date',
            'total_days' => 'nullable|integer|min:0',
            'daily_cost' => 'nullable|numeric|min:0',
            'previous_dollar' => 'nullable|numeric|min:0',
            'running_dollar' => 'nullable|numeric|min:0',
            'total_dollar_cost' => 'nullable|numeric|min:0',
            'dollar_due' => 'nullable|numeric|min:0',
            'today_check' => 'nullable|string|max:50',
            'notes' => 'nullable|string',
        ]);

        $validated['created_by'] = Auth::id();
        if (!empty($validated['today_check']) && $validated['today_check'] === 'Checked') {
            $validated['today_checked_at'] = Carbon::today();
        }

        if (empty($validated['order_month']) && !empty($validated['order_date'])) {
            $validated['order_month'] = Carbon::parse($validated['order_date'])->format('F Y');
        }

        AdsCampaignReport::create($validated);

        return redirect()->route('ads-reports.index')->with('success', 'Ads campaign report entry successfully recorded!');
    }

    public function edit(AdsCampaignReport $adsReport)
    {
        $leads = Lead::latest('id')->limit(50)->get(['id', 'lead_code', 'name', 'phone', 'whatsapp', 'company_name', 'business_category']);
        $customers = Customer::latest('id')->limit(50)->get(['id', 'name', 'phone', 'company_name']);

        return view('ads-reports.edit', compact('adsReport', 'leads', 'customers'));
    }

    public function update(Request $request, AdsCampaignReport $adsReport)
    {
        $validated = $request->validate([
            'lead_code' => 'nullable|string|max:50',
            'lead_id' => 'nullable|exists:leads,id',
            'customer_id' => 'nullable|exists:customers,id',
            'order_id' => 'nullable|exists:orders,id',
            'order_source' => 'nullable|string|max:100',
            'activity' => 'required|string|in:Active,Paused,Completed',
            'total_amount' => 'nullable|numeric|min:0',
            'total_dollar' => 'nullable|numeric|min:0',
            'ads_post_link' => 'nullable|string|max:500',
            'business_category' => 'nullable|string|max:100',
            'business_name' => 'required|string|max:255',
            'whatsapp' => 'nullable|string|max:50',
            'order_month' => 'nullable|string|max:50',
            'order_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'order_details' => 'nullable|string',
            'starting_date' => 'nullable|date',
            'total_days' => 'nullable|integer|min:0',
            'daily_cost' => 'nullable|numeric|min:0',
            'previous_dollar' => 'nullable|numeric|min:0',
            'running_dollar' => 'nullable|numeric|min:0',
            'total_dollar_cost' => 'nullable|numeric|min:0',
            'dollar_due' => 'nullable|numeric|min:0',
            'today_check' => 'nullable|string|max:50',
            'notes' => 'nullable|string',
        ]);

        if (!empty($validated['today_check']) && $validated['today_check'] === 'Checked' && $adsReport->today_check !== 'Checked') {
            $validated['today_checked_at'] = Carbon::today();
        }

        $adsReport->update($validated);

        return redirect()->route('ads-reports.index')->with('success', 'Ads campaign report updated successfully!');
    }

    public function toggleCheck(Request $request, AdsCampaignReport $adsReport)
    {
        $current = $adsReport->today_check;
        $newCheck = ($current === 'Checked') ? 'Pending' : 'Checked';

        $adsReport->update([
            'today_check' => $newCheck,
            'today_checked_at' => ($newCheck === 'Checked') ? Carbon::today() : null,
        ]);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'today_check' => $newCheck]);
        }

        return back()->with('success', "Campaign #{$adsReport->id} marked as {$newCheck}!");
    }

    public function destroy(AdsCampaignReport $adsReport)
    {
        $adsReport->delete();
        return redirect()->route('ads-reports.index')->with('success', 'Ads campaign report entry removed.');
    }
}
