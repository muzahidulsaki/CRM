<?php

namespace App\Http\Controllers;

use App\Models\ClientPayment;
use App\Models\Customer;
use App\Models\Project;
use App\Models\ServiceRenewal;
use App\Services\FinanceService;
use App\Services\PhoneHelper;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ServiceRenewalController extends Controller
{
    protected FinanceService $financeService;

    public function __construct(FinanceService $financeService)
    {
        $this->financeService = $financeService;
    }

    /**
     * Display listing of service renewals with alert filters.
     */
    public function index(Request $request): View
    {
        $query = ServiceRenewal::with(['customer', 'project']);

        // Search
        if ($search = $request->input('search')) {
            $normalizedSearch = PhoneHelper::normalize($search);
            $query->where(function ($q) use ($search, $normalizedSearch) {
                $q->where('renewal_no', 'like', "%{$search}%")
                  ->orWhere('service_name', 'like', "%{$search}%")
                  ->orWhereHas('customer', function ($cq) use ($search, $normalizedSearch) {
                      $cq->where('name', 'like', "%{$search}%")
                         ->orWhere('phone', 'like', "%{$search}%");
                      if ($normalizedSearch) {
                          $cq->orWhere('normalized_phone', 'like', "%{$normalizedSearch}%");
                      }
                  });
            });
        }

        // Category filter
        if ($category = $request->input('category')) {
            $query->where('category', $category);
        }

        // Alert filter
        $today = now()->toDateString();
        $alert = $request->input('alert');

        if ($alert === 'overdue') {
            $query->where('next_due_date', '<', $today)->whereNotIn('status', ['Suspended', 'Cancelled']);
        } elseif ($alert === 'due_today') {
            $query->where('next_due_date', $today)->whereNotIn('status', ['Suspended', 'Cancelled']);
        } elseif ($alert === 'upcoming') {
            $query->whereBetween('next_due_date', [$today, now()->addDays(15)->toDateString()])
                  ->whereNotIn('status', ['Suspended', 'Cancelled']);
        }

        $renewals = $query->orderBy('next_due_date', 'asc')->paginate(15)->withQueryString();

        // Alert metrics
        $metrics = [
            'total_active' => ServiceRenewal::where('status', 'Active')->count(),
            'due_today' => ServiceRenewal::where('next_due_date', $today)->whereNotIn('status', ['Suspended', 'Cancelled'])->count(),
            'overdue' => ServiceRenewal::where('next_due_date', '<', $today)->whereNotIn('status', ['Suspended', 'Cancelled'])->count(),
            'upcoming' => ServiceRenewal::whereBetween('next_due_date', [$today, now()->addDays(15)->toDateString()])->whereNotIn('status', ['Suspended', 'Cancelled'])->count(),
            'total_annual_value' => ServiceRenewal::sum('renewal_fee'),
        ];

        return view('renewals.index', [
            'renewals' => $renewals,
            'metrics' => $metrics,
            'categories' => ServiceRenewal::CATEGORIES,
            'cycles' => ServiceRenewal::CYCLES,
            'currentAlert' => $alert,
        ]);
    }

    /**
     * Show form for creating a new renewal tracker.
     */
    public function create(): View
    {
        $customers = Customer::orderBy('name')->get();
        $projects = Project::orderBy('name')->get();

        return view('renewals.create', [
            'customers' => $customers,
            'projects' => $projects,
            'categories' => ServiceRenewal::CATEGORIES,
            'cycles' => ServiceRenewal::CYCLES,
        ]);
    }

    /**
     * Store a newly created renewal in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'project_id' => 'nullable|exists:projects,id',
            'service_name' => 'required|string|max:255',
            'category' => 'required|in:' . implode(',', ServiceRenewal::CATEGORIES),
            'billing_cycle' => 'required|in:' . implode(',', ServiceRenewal::CYCLES),
            'renewal_fee' => 'required|numeric|min:0',
            'last_payment_date' => 'nullable|date',
            'next_due_date' => 'required|date',
            'reminder_days' => 'required|integer|min:1|max:60',
            'notes' => 'nullable|string',
        ]);

        $renewal = $this->financeService->createServiceRenewal($validated);

        return redirect()->route('renewals.show', $renewal)
            ->with('success', "Renewal tracker #{$renewal->renewal_no} registered successfully.");
    }

    /**
     * Display the specified renewal tracker details.
     */
    public function show(ServiceRenewal $renewal): View
    {
        $renewal->load(['customer', 'project']);

        return view('renewals.show', [
            'renewal' => $renewal,
            'methods' => ClientPayment::METHODS,
        ]);
    }

    /**
     * Process renewal payment and advance next_due_date.
     */
    public function renew(Request $request, ServiceRenewal $renewal): RedirectResponse
    {
        $validated = $request->validate([
            'paid_amount' => 'required|numeric|min:1',
            'payment_method' => 'required|in:' . implode(',', ClientPayment::METHODS),
            'transaction_reference' => 'nullable|string|max:100',
        ]);

        $this->financeService->processRenewalPayment(
            $renewal,
            floatval($validated['paid_amount']),
            $validated['payment_method'],
            $validated['transaction_reference'] ?? null
        );

        return redirect()->route('renewals.show', $renewal)
            ->with('success', "Service {$renewal->service_name} successfully renewed! Next due date updated.");
    }

    /**
     * Show form for editing the renewal.
     */
    public function edit(ServiceRenewal $renewal): View
    {
        $customers = Customer::orderBy('name')->get();
        $projects = Project::orderBy('name')->get();

        return view('renewals.edit', [
            'renewal' => $renewal,
            'customers' => $customers,
            'projects' => $projects,
            'categories' => ServiceRenewal::CATEGORIES,
            'cycles' => ServiceRenewal::CYCLES,
            'statuses' => ServiceRenewal::STATUSES,
        ]);
    }

    /**
     * Update the specified renewal.
     */
    public function update(Request $request, ServiceRenewal $renewal): RedirectResponse
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'project_id' => 'nullable|exists:projects,id',
            'service_name' => 'required|string|max:255',
            'category' => 'required|in:' . implode(',', ServiceRenewal::CATEGORIES),
            'billing_cycle' => 'required|in:' . implode(',', ServiceRenewal::CYCLES),
            'renewal_fee' => 'required|numeric|min:0',
            'next_due_date' => 'required|date',
            'status' => 'required|in:' . implode(',', ServiceRenewal::STATUSES),
            'notes' => 'nullable|string',
        ]);

        $renewal->update($validated);

        return redirect()->route('renewals.show', $renewal)
            ->with('success', "Renewal #{$renewal->renewal_no} updated successfully.");
    }

    /**
     * Remove the specified renewal.
     */
    public function destroy(ServiceRenewal $renewal): RedirectResponse
    {
        $no = $renewal->renewal_no;
        $renewal->delete();

        return redirect()->route('renewals.index')
            ->with('success', "Renewal #{$no} deleted successfully.");
    }
}
