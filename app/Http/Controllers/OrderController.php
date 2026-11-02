<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Customer;
use App\Models\Lead;
use App\Models\Order;
use App\Models\Quotation;
use App\Models\Setting;
use App\Models\User;
use App\Services\LeadService;
use App\Services\OrderService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    /**
     * Display a listing of orders with metrics and filters.
     */
    public function index(Request $request): View
    {
        $query = Order::with(['customer', 'salesPerson', 'projectManager', 'project']);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('order_code', 'like', "%{$search}%")
                  ->orWhere('title', 'like', "%{$search}%")
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

        if ($category = $request->input('category')) {
            $query->where('service_category', $category);
        }

        if ($orderType = $request->input('type')) {
            if ($orderType !== 'all') {
                $query->where('order_type', $orderType);
            }
        }

        $orders = $query->orderBy('id', 'desc')->paginate(15)->withQueryString();

        $metrics = [
            'total' => Order::count(),
            'active' => Order::whereIn('status', ['Confirmed', 'Project Started', 'Working', 'Under Review'])->count(),
            'completed' => Order::whereIn('status', ['Completed', 'Delivered'])->count(),
            'total_sales' => Order::where('status', '!=', 'Cancelled')->sum('total_amount'),
            'total_due' => Order::where('status', '!=', 'Cancelled')->sum('due_amount'),
            'creative_count' => Order::where('order_type', 'creative')->count(),
            'ads_count' => Order::where('order_type', 'ads')->count(),
            'soft_count' => Order::where('order_type', 'web_software')->count(),
        ];

        $serviceCategories = Setting::get('service_categories', []);

        return view('orders.index', compact('orders', 'metrics', 'serviceCategories'));
    }

    /**
     * Show form for creating a new order.
     */
    public function create(Request $request): View
    {
        $preselectedQuotation = $request->filled('quotation_id') ? Quotation::with(['lead', 'customer', 'items'])->find($request->input('quotation_id')) : null;
        $preselectedCustomer = null;

        if ($request->filled('customer_id')) {
            $preselectedCustomer = Customer::find($request->input('customer_id'));
        } elseif ($preselectedQuotation?->customer) {
            $preselectedCustomer = $preselectedQuotation->customer;
        } elseif ($request->filled('lead_id')) {
            $lead = Lead::find($request->input('lead_id'));
            if ($lead) {
                if ($lead->customer_id) {
                    $preselectedCustomer = Customer::find($lead->customer_id);
                } else {
                    $preselectedCustomer = [
                        'id' => 'lead_' . $lead->id,
                        'type' => 'lead',
                        'customer_code' => $lead->lead_code,
                        'name' => $lead->name,
                        'company_name' => $lead->company_name,
                        'phone' => $lead->phone,
                        'whatsapp' => $lead->whatsapp,
                        'email' => $lead->email,
                        'badge' => 'Lead (' . $lead->lead_code . ')',
                    ];
                }
            }
        }

        // Fetch active customers
        $customersList = Customer::where('status', 'active')
            ->select('id', 'customer_code', 'name', 'company_name', 'phone', 'whatsapp', 'email')
            ->orderBy('name')
            ->get()
            ->map(function ($c) {
                return [
                    'id' => $c->id,
                    'type' => 'customer',
                    'customer_code' => $c->customer_code,
                    'name' => $c->name,
                    'company_name' => $c->company_name,
                    'phone' => $c->phone,
                    'whatsapp' => $c->whatsapp,
                    'email' => $c->email,
                    'badge' => 'Client',
                ];
            });

        // Also fetch unconverted Leads so they can be selected directly by WhatsApp/phone
        $leadsList = Lead::whereNull('customer_id')
            ->select('id', 'lead_code', 'name', 'company_name', 'phone', 'whatsapp', 'email')
            ->latest('id')
            ->limit(100)
            ->get()
            ->map(function ($l) {
                return [
                    'id' => 'lead_' . $l->id,
                    'type' => 'lead',
                    'customer_code' => $l->lead_code,
                    'name' => $l->name,
                    'company_name' => $l->company_name,
                    'phone' => $l->phone,
                    'whatsapp' => $l->whatsapp,
                    'email' => $l->email,
                    'badge' => 'Lead (' . $l->lead_code . ')',
                ];
            });

        $customers = $customersList->concat($leadsList)->values();

        $users = User::where('status', 'active')->orderBy('name')->get();
        $serviceCategories = Setting::get('service_categories', [
            'Web Development',
            'Software / ERP',
            'Video Editing',
            'Graphics Design',
            'Social Media Marketing (SMM)',
            'Digital Marketing / Ads',
            'Mobile App Development',
            'Other'
        ]);

        $orderType = $request->input('type', 'creative');

        return view('orders.create', compact('preselectedQuotation', 'preselectedCustomer', 'customers', 'users', 'serviceCategories', 'orderType'));
    }

    /**
     * Store a newly created order.
     */
    public function store(Request $request): RedirectResponse
    {
        // Auto-convert lead to customer if a lead was selected in the Client selection dropdown
        $customerId = $request->input('customer_id');
        if (is_string($customerId) && str_starts_with($customerId, 'lead_')) {
            $leadId = intval(str_replace('lead_', '', $customerId));
            $lead = Lead::findOrFail($leadId);
            $customer = LeadService::convertToCustomer($lead, auth()->id());
            $request->merge(['customer_id' => $customer->id]);
        } elseif (!$request->filled('customer_id') && $request->filled('lead_id')) {
            $lead = Lead::findOrFail($request->input('lead_id'));
            $customer = LeadService::convertToCustomer($lead, auth()->id());
            $request->merge(['customer_id' => $customer->id]);
        }

        $request->validate([
            'customer_id' => ['required', 'exists:customers,id'],
            'title' => ['required', 'string', 'max:255'],
            'service_category' => ['required', 'string'],
            'total_amount' => ['required', 'numeric', 'min:0'],
            'paid_amount' => ['nullable', 'numeric', 'min:0'],
            'order_date' => ['required', 'date'],
            'expected_delivery_date' => ['nullable', 'date', 'after_or_equal:order_date'],
            'sales_person_id' => ['nullable', 'exists:users,id'],
            'project_manager_id' => ['nullable', 'exists:users,id'],
            'status' => ['required', 'in:Pending,Confirmed,Project Started,Working,Under Review,Completed,Delivered,Cancelled'],
        ]);

        $orderCode = OrderService::generateOrderCode();
        $total = floatval($request->input('total_amount'));
        $paid = floatval($request->input('paid_amount', 0));

        $orderType = $request->input('order_type', 'creative');
        $meta = $request->input('meta', []);

        // Compute paid amount from installments if provided
        if ($orderType === 'creative') {
            $sumInstallments = floatval($meta['pay_1st'] ?? 0) + floatval($meta['pay_2nd'] ?? 0) + floatval($meta['pay_3rd'] ?? 0);
            if ($sumInstallments > 0) {
                $paid = $sumInstallments;
            }
        } elseif ($orderType === 'web_software') {
            $sumInstallments = 0;
            for ($i = 1; $i <= 7; $i++) {
                $suffix = match($i) { 1 => '1st', 2 => '2nd', 3 => '3rd', default => "{$i}th" };
                $sumInstallments += floatval($meta["pay_{$suffix}"] ?? 0);
            }
            if ($sumInstallments > 0) {
                $paid = $sumInstallments;
            }
        }

        $due = max(0, $total - $paid);

        $order = Order::create([
            'order_code' => $orderCode,
            'customer_id' => $request->input('customer_id'),
            'quotation_id' => $request->input('quotation_id'),
            'sales_person_id' => $request->input('sales_person_id', auth()->id()),
            'project_manager_id' => $request->input('project_manager_id'),
            'service_category' => $request->input('service_category'),
            'order_type' => $orderType,
            'order_source' => $request->input('order_source', 'Repeated'),
            'activity_status' => $request->input('activity_status', 'Active'),
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'total_amount' => $total,
            'paid_amount' => $paid,
            'due_amount' => $due,
            'payment_terms' => $request->input('payment_terms'),
            'order_date' => $request->input('order_date'),
            'expected_delivery_date' => $request->input('expected_delivery_date'),
            'status' => $request->input('status', 'Confirmed'),
            'notes' => $request->input('notes'),
            'meta' => $meta,
        ]);

        // If from quotation, update quotation status
        if ($quotationId = $request->input('quotation_id')) {
            $quotation = Quotation::find($quotationId);
            if ($quotation) {
                $quotation->update([
                    'status' => 'Accepted',
                    'converted_to_order_at' => now(),
                ]);
            }
        }

        ActivityLog::log(
            action: 'order_created',
            description: "New order created: {$order->order_code} ({$order->title}), Total: BDT " . number_format($order->total_amount, 2),
            subject: $order,
            userId: auth()->id()
        );

        // Auto create Project if requested
        if ($request->boolean('create_project')) {
            OrderService::createProjectFromOrder($order, $request->input('project_manager_id'));
        }

        return redirect()->route('orders.show', $order)
            ->with('success', "Order {$order->order_code} created successfully.");
    }

    /**
     * Display order details & linked project.
     */
    public function show(Order $order): View
    {
        $order->load(['customer', 'quotation', 'lead', 'salesPerson', 'projectManager', 'project.tasks']);

        return view('orders.show', compact('order'));
    }

    /**
     * Show form for editing order.
     */
    public function edit(Order $order): View
    {
        $customers = Customer::where('status', 'active')->orderBy('name')->get();
        $users = User::where('status', 'active')->orderBy('name')->get();
        $serviceCategories = Setting::get('service_categories', []);

        return view('orders.edit', compact('order', 'customers', 'users', 'serviceCategories'));
    }

    /**
     * Update order details.
     */
    public function update(Request $request, Order $order): RedirectResponse
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'service_category' => ['required', 'string'],
            'total_amount' => ['required', 'numeric', 'min:0'],
            'paid_amount' => ['nullable', 'numeric', 'min:0'],
            'order_date' => ['required', 'date'],
            'expected_delivery_date' => ['nullable', 'date'],
            'status' => ['required', 'in:Pending,Confirmed,Project Started,Working,Under Review,Completed,Delivered,Cancelled'],
        ]);

        $total = floatval($request->input('total_amount'));
        $paid = floatval($request->input('paid_amount', 0));
        $due = max(0, $total - $paid);

        $order->update(array_merge($request->all(), [
            'due_amount' => $due,
        ]));

        ActivityLog::log(
            action: 'order_updated',
            description: "Order {$order->order_code} updated.",
            subject: $order,
            userId: auth()->id()
        );

        return redirect()->route('orders.show', $order)
            ->with('success', "Order {$order->order_code} updated successfully.");
    }

    /**
     * Initialize Project from Order if not already created.
     */
    public function initializeProject(Request $request, Order $order): RedirectResponse
    {
        if ($order->project) {
            return redirect()->route('projects.show', $order->project);
        }

        $project = OrderService::createProjectFromOrder($order, $request->input('project_manager_id'));

        return redirect()->route('projects.show', $project)
            ->with('success', "Project {$project->project_code} initialized successfully!");
    }

    /**
     * Quick status update.
     */
    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        $request->validate([
            'status' => ['required', 'in:Pending,Confirmed,Project Started,Working,Under Review,Completed,Delivered,Cancelled'],
        ]);

        $old = $order->status;
        $order->status = $request->input('status');
        $order->save();

        ActivityLog::log(
            action: 'order_status_updated',
            description: "Order {$order->order_code} status: '{$old}' -> '{$order->status}'",
            subject: $order,
            userId: auth()->id()
        );

        return back()->with('success', 'Order status updated successfully.');
    }

    /**
     * Remove order.
     */
    public function destroy(Order $order): RedirectResponse
    {
        $code = $order->order_code;
        $order->delete();

        ActivityLog::log(
            action: 'order_deleted',
            description: "Order {$code} deleted.",
            subject: null,
            userId: auth()->id()
        );

        return redirect()->route('orders.index')
            ->with('success', "Order {$code} deleted successfully.");
    }
}
