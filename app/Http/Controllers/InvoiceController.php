<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\Project;
use App\Services\FinanceService;
use App\Services\PhoneHelper;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InvoiceController extends Controller
{
    protected FinanceService $financeService;

    public function __construct(FinanceService $financeService)
    {
        $this->financeService = $financeService;
    }

    /**
     * Display a listing of invoices with filters.
     */
    public function index(Request $request): View
    {
        $query = Invoice::with(['customer', 'order', 'project']);

        if ($search = $request->input('search')) {
            $normalizedSearch = PhoneHelper::normalize($search);
            $query->where(function ($q) use ($search, $normalizedSearch) {
                $q->where('invoice_no', 'like', "%{$search}%")
                  ->orWhereHas('customer', function ($cq) use ($search, $normalizedSearch) {
                      $cq->where('name', 'like', "%{$search}%")
                         ->orWhere('company_name', 'like', "%{$search}%")
                         ->orWhere('phone', 'like', "%{$search}%");
                      if ($normalizedSearch) {
                          $cq->orWhere('normalized_phone', 'like', "%{$normalizedSearch}%");
                      }
                  });
            });
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($customerId = $request->input('customer_id')) {
            $query->where('customer_id', $customerId);
        }

        $invoices = $query->orderBy('id', 'desc')->paginate(15)->withQueryString();

        $metrics = [
            'total_invoiced' => Invoice::sum('total_amount'),
            'total_paid' => Invoice::sum('paid_amount'),
            'total_due' => Invoice::sum('due_amount'),
            'paid_invoices_count' => Invoice::where('status', 'Paid')->count(),
            'due_invoices_count' => Invoice::whereIn('status', ['Sent', 'Partially Paid', 'Overdue'])->count(),
        ];

        $customers = Customer::orderBy('name')->get();

        return view('invoices.index', [
            'invoices' => $invoices,
            'metrics' => $metrics,
            'customers' => $customers,
            'statuses' => Invoice::STATUSES,
        ]);
    }

    /**
     * Show the form for creating a new invoice.
     */
    public function create(Request $request): View
    {
        $customers = Customer::orderBy('name')->get();
        $projects = Project::orderBy('name')->get();
        $orders = Order::orderBy('id', 'desc')->take(50)->get();

        // Optional prefilled customer or order
        $prefilledCustomerId = $request->input('customer_id');
        $prefilledOrderId = $request->input('order_id');
        $prefilledOrder = $prefilledOrderId ? Order::find($prefilledOrderId) : null;

        return view('invoices.create', [
            'customers' => $customers,
            'projects' => $projects,
            'orders' => $orders,
            'prefilledCustomerId' => $prefilledCustomerId,
            'prefilledOrder' => $prefilledOrder,
        ]);
    }

    /**
     * Store a newly created invoice in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'order_id' => 'nullable|exists:orders,id',
            'project_id' => 'nullable|exists:projects,id',
            'issue_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:issue_date',
            'discount_type' => 'required|in:fixed,percentage',
            'discount_value' => 'nullable|numeric|min:0',
            'tax_percentage' => 'nullable|numeric|min:0|max:100',
            'notes' => 'nullable|string',
            'terms' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.item_description' => 'required|string|max:255',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.service_category' => 'nullable|string',
        ]);

        $invoice = $this->financeService->createInvoice(
            $validated,
            $validated['items']
        );

        return redirect()->route('invoices.show', $invoice)
            ->with('success', "Invoice #{$invoice->invoice_no} created successfully.");
    }

    /**
     * Display the specified invoice (print-ready official invoice).
     */
    public function show(Invoice $invoice): View
    {
        $invoice->load(['customer', 'order', 'project', 'items', 'payments.receivedBy', 'creator']);

        return view('invoices.show', [
            'invoice' => $invoice,
        ]);
    }

    /**
     * Show the form for editing the invoice.
     */
    public function edit(Invoice $invoice): View
    {
        $invoice->load('items');
        $customers = Customer::orderBy('name')->get();
        $projects = Project::orderBy('name')->get();
        $orders = Order::orderBy('id', 'desc')->take(50)->get();

        return view('invoices.edit', [
            'invoice' => $invoice,
            'customers' => $customers,
            'projects' => $projects,
            'orders' => $orders,
            'statuses' => Invoice::STATUSES,
        ]);
    }

    /**
     * Update the specified invoice in storage.
     */
    public function update(Request $request, Invoice $invoice): RedirectResponse
    {
        $validated = $request->validate([
            'issue_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:issue_date',
            'status' => 'required|in:' . implode(',', Invoice::STATUSES),
            'notes' => 'nullable|string',
            'terms' => 'nullable|string',
        ]);

        $invoice->update($validated);

        return redirect()->route('invoices.show', $invoice)
            ->with('success', "Invoice #{$invoice->invoice_no} updated successfully.");
    }

    /**
     * Remove the specified invoice from storage.
     */
    public function destroy(Invoice $invoice): RedirectResponse
    {
        $no = $invoice->invoice_no;
        $invoice->delete();

        return redirect()->route('invoices.index')
            ->with('success', "Invoice #{$no} deleted successfully.");
    }
}
