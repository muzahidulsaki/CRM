<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Customer;
use App\Models\Lead;
use App\Models\Quotation;
use App\Models\QuotationItem;
use App\Models\Setting;
use App\Services\LeadService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class QuotationController extends Controller
{
    /**
     * Display a listing of quotations.
     */
    public function index(Request $request): View
    {
        $query = Quotation::with(['lead', 'customer', 'creator']);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('quotation_code', 'like', "%{$search}%")
                  ->orWhereHas('lead', function ($lq) use ($search) {
                      $lq->where('name', 'like', "%{$search}%")
                         ->orWhere('company_name', 'like', "%{$search}%")
                         ->orWhere('phone', 'like', "%{$search}%");
                  })
                  ->orWhereHas('customer', function ($cq) use ($search) {
                      $cq->where('name', 'like', "%{$search}%")
                         ->orWhere('company_name', 'like', "%{$search}%")
                         ->orWhere('phone', 'like', "%{$search}%");
                  });
            });
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $quotations = $query->orderBy('id', 'desc')->paginate(15)->withQueryString();

        $metrics = [
            'total' => Quotation::count(),
            'draft' => Quotation::where('status', 'Draft')->count(),
            'sent' => Quotation::where('status', 'Sent')->count(),
            'accepted' => Quotation::where('status', 'Accepted')->count(),
            'rejected' => Quotation::where('status', 'Rejected')->count(),
            'total_value' => Quotation::where('status', 'Accepted')->sum('total_amount'),
        ];

        return view('quotations.index', compact('quotations', 'metrics'));
    }

    /**
     * Show the form for creating a new quotation.
     */
    public function create(Request $request): View
    {
        $preselectedLead = $request->filled('lead_id') ? Lead::find($request->input('lead_id')) : null;
        $preselectedCustomer = $request->filled('customer_id') ? Customer::find($request->input('customer_id')) : null;

        $leads = Lead::whereNotIn('lead_status', ['Lost'])->orderBy('name')->get();
        $customers = Customer::where('status', 'active')->orderBy('name')->get();
        $serviceCategories = Setting::get('service_categories', []);

        return view('quotations.create', compact('preselectedLead', 'preselectedCustomer', 'leads', 'customers', 'serviceCategories'));
    }

    /**
     * Store a newly created quotation with line items.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'quotation_date' => ['required', 'date'],
            'valid_until' => ['nullable', 'date', 'after_or_equal:quotation_date'],
            'lead_id' => ['nullable', 'exists:leads,id'],
            'customer_id' => ['nullable', 'exists:customers,id'],
            'status' => ['required', 'in:Draft,Sent,Negotiation,Accepted,Rejected,Expired'],
            'discount_amount' => ['nullable', 'numeric', 'min:0'],
            'tax_amount' => ['nullable', 'numeric', 'min:0'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.description' => ['required', 'string'],
            'items.*.quantity' => ['required', 'numeric', 'min:0.01'],
            'items.*.unit_price' => ['required', 'numeric', 'min:0'],
        ]);

        $quotation = DB::transaction(function () use ($request) {
            $code = LeadService::generateQuotationCode();
            $leadId = $request->input('lead_id');
            $customerId = $request->input('customer_id');

            // If lead has a customer already, tie to that customer
            if ($leadId && !$customerId) {
                $lead = Lead::find($leadId);
                $customerId = $lead?->customer_id;
            }

            $discount = floatval($request->input('discount_amount', 0));
            $tax = floatval($request->input('tax_amount', 0));

            $quotation = Quotation::create([
                'quotation_code' => $code,
                'lead_id' => $leadId,
                'customer_id' => $customerId,
                'created_by' => auth()->id(),
                'quotation_date' => $request->input('quotation_date'),
                'valid_until' => $request->input('valid_until'),
                'status' => $request->input('status', 'Draft'),
                'discount_amount' => $discount,
                'tax_amount' => $tax,
                'payment_terms' => $request->input('payment_terms'),
                'delivery_terms' => $request->input('delivery_terms'),
                'notes' => $request->input('notes'),
            ]);

            $subtotal = 0;
            foreach ($request->input('items', []) as $itemData) {
                $qty = floatval($itemData['quantity']);
                $unitPrice = floatval($itemData['unit_price']);
                $itemTotal = round($qty * $unitPrice, 2);
                $subtotal += $itemTotal;

                QuotationItem::create([
                    'quotation_id' => $quotation->id,
                    'service_category' => $itemData['service_category'] ?? null,
                    'description' => $itemData['description'],
                    'quantity' => $qty,
                    'unit_price' => $unitPrice,
                    'total_price' => $itemTotal,
                ]);
            }

            $quotation->subtotal = $subtotal;
            $quotation->total_amount = max(0, $subtotal - $discount + $tax);
            $quotation->save();

            // If created for a Lead, update lead status to Proposal Sent if it was in New/Contacted/Interested
            if ($leadId) {
                $lead = Lead::find($leadId);
                if ($lead && in_array($lead->lead_status, ['New', 'Contacted', 'Interested', 'Meeting/Demo'])) {
                    $lead->update(['lead_status' => 'Proposal Sent']);
                }
            }

            ActivityLog::log(
                action: 'quotation_created',
                description: "New quotation created: {$quotation->quotation_code}. Total: BDT " . number_format($quotation->total_amount, 2),
                subject: $quotation,
                userId: auth()->id()
            );

            return $quotation;
        });

        return redirect()->route('quotations.show', $quotation)
            ->with('success', "Quotation {$quotation->quotation_code} created successfully.");
    }

    /**
     * Display printable view of the quotation.
     */
    public function show(Quotation $quotation): View
    {
        $quotation->load(['lead', 'customer', 'creator', 'items']);

        $companySettings = [
            'name' => Setting::get('company_name', 'Projukti Sheba'),
            'phone' => Setting::get('company_phone', '+8801700000000'),
            'email' => Setting::get('company_email', 'info@projuktisheba.com'),
            'currency_symbol' => Setting::get('currency_symbol', 'BDT'),
        ];

        return view('quotations.show', compact('quotation', 'companySettings'));
    }

    /**
     * Show the form for editing the specified quotation.
     */
    public function edit(Quotation $quotation): View
    {
        $quotation->load('items');
        $leads = Lead::whereNotIn('lead_status', ['Lost'])->orderBy('name')->get();
        $customers = Customer::where('status', 'active')->orderBy('name')->get();
        $serviceCategories = Setting::get('service_categories', []);

        return view('quotations.edit', compact('quotation', 'leads', 'customers', 'serviceCategories'));
    }

    /**
     * Update quotation header and items.
     */
    public function update(Request $request, Quotation $quotation): RedirectResponse
    {
        $request->validate([
            'quotation_date' => ['required', 'date'],
            'valid_until' => ['nullable', 'date', 'after_or_equal:quotation_date'],
            'status' => ['required', 'in:Draft,Sent,Negotiation,Accepted,Rejected,Expired'],
            'discount_amount' => ['nullable', 'numeric', 'min:0'],
            'tax_amount' => ['nullable', 'numeric', 'min:0'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.description' => ['required', 'string'],
            'items.*.quantity' => ['required', 'numeric', 'min:0.01'],
            'items.*.unit_price' => ['required', 'numeric', 'min:0'],
        ]);

        DB::transaction(function () use ($request, $quotation) {
            $discount = floatval($request->input('discount_amount', 0));
            $tax = floatval($request->input('tax_amount', 0));

            $quotation->update([
                'quotation_date' => $request->input('quotation_date'),
                'valid_until' => $request->input('valid_until'),
                'status' => $request->input('status'),
                'discount_amount' => $discount,
                'tax_amount' => $tax,
                'payment_terms' => $request->input('payment_terms'),
                'delivery_terms' => $request->input('delivery_terms'),
                'notes' => $request->input('notes'),
            ]);

            // Re-sync items
            $quotation->items()->delete();
            $subtotal = 0;
            foreach ($request->input('items', []) as $itemData) {
                $qty = floatval($itemData['quantity']);
                $unitPrice = floatval($itemData['unit_price']);
                $itemTotal = round($qty * $unitPrice, 2);
                $subtotal += $itemTotal;

                QuotationItem::create([
                    'quotation_id' => $quotation->id,
                    'service_category' => $itemData['service_category'] ?? null,
                    'description' => $itemData['description'],
                    'quantity' => $qty,
                    'unit_price' => $unitPrice,
                    'total_price' => $itemTotal,
                ]);
            }

            $quotation->subtotal = $subtotal;
            $quotation->total_amount = max(0, $subtotal - $discount + $tax);
            $quotation->save();

            // Auto-convert lead to customer if accepted
            if ($quotation->status === 'Accepted' && $quotation->lead && !$quotation->customer_id) {
                LeadService::convertToCustomer($quotation->lead, auth()->id());
            }

            ActivityLog::log(
                action: 'quotation_updated',
                description: "Quotation {$quotation->quotation_code} updated.",
                subject: $quotation,
                userId: auth()->id()
            );
        });

        return redirect()->route('quotations.show', $quotation)
            ->with('success', "Quotation {$quotation->quotation_code} updated successfully.");
    }

    /**
     * Update quotation status.
     */
    public function updateStatus(Request $request, Quotation $quotation): RedirectResponse
    {
        $request->validate([
            'status' => ['required', 'in:Draft,Sent,Negotiation,Accepted,Rejected,Expired'],
        ]);

        $oldStatus = $quotation->status;
        $quotation->status = $request->input('status');
        $quotation->save();

        if ($quotation->status === 'Accepted' && $quotation->lead && !$quotation->customer_id) {
            LeadService::convertToCustomer($quotation->lead, auth()->id());
        }

        ActivityLog::log(
            action: 'quotation_status_changed',
            description: "Quotation {$quotation->quotation_code} status: '{$oldStatus}' -> '{$quotation->status}'",
            subject: $quotation,
            userId: auth()->id()
        );

        return back()->with('success', 'Quotation status updated successfully.');
    }

    /**
     * Delete quotation.
     */
    public function destroy(Quotation $quotation): RedirectResponse
    {
        $code = $quotation->quotation_code;
        $quotation->delete();

        ActivityLog::log(
            action: 'quotation_deleted',
            description: "Quotation {$code} deleted.",
            subject: null,
            userId: auth()->id()
        );

        return redirect()->route('quotations.index')
            ->with('success', "Quotation {$code} deleted successfully.");
    }
}
