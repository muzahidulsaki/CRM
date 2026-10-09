<?php

namespace App\Http\Controllers;

use App\Models\ClientPayment;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Order;
use App\Services\FinanceService;
use App\Services\PhoneHelper;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClientPaymentController extends Controller
{
    protected FinanceService $financeService;

    public function __construct(FinanceService $financeService)
    {
        $this->financeService = $financeService;
    }

    /**
     * Display listing of client payments & money receipts.
     */
    public function index(Request $request): View
    {
        $query = ClientPayment::with(['customer', 'invoice', 'order', 'receivedBy']);

        if ($search = $request->input('search')) {
            $normalizedSearch = PhoneHelper::normalize($search);
            $query->where(function ($q) use ($search, $normalizedSearch) {
                $q->where('payment_no', 'like', "%{$search}%")
                  ->orWhere('transaction_reference', 'like', "%{$search}%")
                  ->orWhereHas('customer', function ($cq) use ($search, $normalizedSearch) {
                      $cq->where('name', 'like', "%{$search}%")
                         ->orWhere('phone', 'like', "%{$search}%");
                      if ($normalizedSearch) {
                          $cq->orWhere('normalized_phone', 'like', "%{$normalizedSearch}%");
                      }
                  });
            });
        }

        if ($method = $request->input('payment_method')) {
            $query->where('payment_method', $method);
        }

        $payments = $query->orderBy('id', 'desc')->paginate(15)->withQueryString();

        $metrics = [
            'total_collected' => ClientPayment::sum('amount'),
            'payments_count' => ClientPayment::count(),
            'today_collected' => ClientPayment::where('payment_date', now()->toDateString())->sum('amount'),
            'month_collected' => ClientPayment::whereBetween('payment_date', [now()->startOfMonth()->toDateString(), now()->endOfMonth()->toDateString()])->sum('amount'),
        ];

        return view('client-payments.index', [
            'payments' => $payments,
            'metrics' => $metrics,
            'methods' => ClientPayment::METHODS,
        ]);
    }

    /**
     * Show form to record a new client payment.
     */
    public function create(Request $request): View
    {
        $customers = Customer::orderBy('name')->get();
        $invoices = Invoice::where('due_amount', '>', 0)->with('customer')->orderBy('id', 'desc')->get();
        $orders = Order::where('due_amount', '>', 0)->with('customer')->orderBy('id', 'desc')->get();

        $prefilledInvoiceId = $request->input('invoice_id');
        $prefilledOrderId = $request->input('order_id');
        $prefilledCustomerId = $request->input('customer_id');

        $selectedInvoice = $prefilledInvoiceId ? Invoice::find($prefilledInvoiceId) : null;
        if ($selectedInvoice && !$prefilledCustomerId) {
            $prefilledCustomerId = $selectedInvoice->customer_id;
        }

        $selectedOrder = $prefilledOrderId ? Order::find($prefilledOrderId) : null;
        if ($selectedOrder && !$prefilledCustomerId) {
            $prefilledCustomerId = $selectedOrder->customer_id;
        }

        return view('client-payments.create', [
            'customers' => $customers,
            'invoices' => $invoices,
            'orders' => $orders,
            'methods' => ClientPayment::METHODS,
            'prefilledInvoice' => $selectedInvoice,
            'prefilledOrder' => $selectedOrder,
            'prefilledCustomerId' => $prefilledCustomerId,
        ]);
    }

    /**
     * Store newly recorded client payment.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'invoice_id' => 'nullable|exists:invoices,id',
            'order_id' => 'nullable|exists:orders,id',
            'amount' => 'required|numeric|min:1',
            'payment_date' => 'required|date',
            'payment_method' => 'required|in:' . implode(',', ClientPayment::METHODS),
            'transaction_reference' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
            'attachment' => 'nullable|file|max:10240|mimes:jpg,jpeg,png,pdf',
        ]);

        $payment = $this->financeService->recordClientPayment(
            $validated,
            $request->file('attachment')
        );

        return redirect()->route('client-payments.show', $payment)
            ->with('success', "Payment #{$payment->payment_no} recorded successfully. Money receipt generated.");
    }

    /**
     * Display printable official client money receipt.
     */
    public function show(ClientPayment $clientPayment): View
    {
        $clientPayment->load(['customer', 'invoice', 'order', 'receivedBy']);

        return view('client-payments.show', [
            'payment' => $clientPayment,
        ]);
    }

    /**
     * Remove the specified payment.
     */
    public function destroy(ClientPayment $clientPayment): RedirectResponse
    {
        $no = $clientPayment->payment_no;
        $clientPayment->delete();

        return redirect()->route('client-payments.index')
            ->with('success', "Payment #{$no} deleted successfully.");
    }
}
