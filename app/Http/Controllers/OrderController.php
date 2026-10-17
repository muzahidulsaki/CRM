<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Quotation;
use App\Models\Setting;
use App\Models\User;
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

        $orders = $query->orderBy('id', 'desc')->paginate(15)->withQueryString();

        $metrics = [
            'total' => Order::count(),
            'active' => Order::whereIn('status', ['Confirmed', 'Project Started', 'Working', 'Under Review'])->count(),
            'completed' => Order::whereIn('status', ['Completed', 'Delivered'])->count(),
            'total_sales' => Order::where('status', '!=', 'Cancelled')->sum('total_amount'),
            'total_due' => Order::where('status', '!=', 'Cancelled')->sum('due_amount'),
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
        $preselectedCustomer = $request->filled('customer_id') ? Customer::find($request->input('customer_id')) : ($preselectedQuotation?->customer);

        $customers = Customer::where('status', 'active')->orderBy('name')->get();
        $users = User::where('status', 'active')->orderBy('name')->get();
        $serviceCategories = Setting::get('service_categories', []);

        return view('orders.create', compact('preselectedQuotation', 'preselectedCustomer', 'customers', 'users', 'serviceCategories'));
    }

    /**
     * Store a newly created order.
     */
    public function store(Request $request): RedirectResponse
    {
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
        $due = max(0, $total - $paid);

        $order = Order::create([
            'order_code' => $orderCode,
            'customer_id' => $request->input('customer_id'),
            'quotation_id' => $request->input('quotation_id'),
            'sales_person_id' => $request->input('sales_person_id', auth()->id()),
            'project_manager_id' => $request->input('project_manager_id'),
            'service_category' => $request->input('service_category'),
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
