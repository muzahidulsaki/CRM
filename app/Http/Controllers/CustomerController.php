<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Customer;
use App\Services\LeadService;
use App\Services\PhoneHelper;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomerController extends Controller
{
    /**
     * Display listing of customers.
     */
    public function index(Request $request): View
    {
        $query = Customer::withCount(['leads', 'quotations']);

        if ($search = $request->input('search')) {
            $normalized = PhoneHelper::normalize($search);
            $query->where(function ($q) use ($search, $normalized) {
                $q->where('customer_code', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%")
                  ->orWhere('company_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");

                if ($normalized) {
                    $q->orWhere('normalized_phone', 'like', "%{$normalized}%")
                      ->orWhere('normalized_whatsapp', 'like', "%{$normalized}%");
                }
            });
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $customers = $query->orderBy('id', 'desc')->paginate(15)->withQueryString();

        $metrics = [
            'total' => Customer::count(),
            'active' => Customer::where('status', 'active')->count(),
            'inactive' => Customer::where('status', 'inactive')->count(),
        ];

        return view('customers.index', compact('customers', 'metrics'));
    }

    /**
     * Show the form for creating a new customer manually.
     */
    public function create(): View
    {
        return view('customers.create');
    }

    /**
     * Store a newly created customer.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'company_name' => ['nullable', 'string', 'max:255'],
        ]);

        $code = LeadService::generateCustomerCode();

        $customer = Customer::create([
            'customer_code' => $code,
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
            'status' => $request->input('status', 'active'),
            'notes' => $request->input('notes'),
            'created_by' => auth()->id(),
        ]);

        ActivityLog::log(
            action: 'customer_created',
            description: "নতুন কাস্টমার তৈরি করা হয়েছে: {$customer->customer_code} ({$customer->name})",
            subject: $customer,
            userId: auth()->id()
        );

        return redirect()->route('customers.show', $customer)
            ->with('success', "কাস্টমার {$customer->customer_code} সফলভাবে তৈরি হয়েছে।");
    }

    /**
     * Display Customer 360° Profile.
     */
    public function show(Customer $customer): View
    {
        $customer->load([
            'creator',
            'leads.assignedUser',
            'quotations.items',
            'followUps.user',
        ]);

        return view('customers.show', compact('customer'));
    }

    /**
     * Show the form for editing the customer.
     */
    public function edit(Customer $customer): View
    {
        return view('customers.edit', compact('customer'));
    }

    /**
     * Update customer information.
     */
    public function update(Request $request, Customer $customer): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'company_name' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        $customer->update($request->all());

        ActivityLog::log(
            action: 'customer_updated',
            description: "কাস্টমার {$customer->customer_code} তথ্য আপডেট করা হয়েছে।",
            subject: $customer,
            userId: auth()->id()
        );

        return redirect()->route('customers.show', $customer)
            ->with('success', "কাস্টমার {$customer->customer_code} সফলভাবে আপডেট হয়েছে।");
    }

    /**
     * Delete customer.
     */
    public function destroy(Customer $customer): RedirectResponse
    {
        $code = $customer->customer_code;
        $customer->delete();

        ActivityLog::log(
            action: 'customer_deleted',
            description: "কাস্টমার {$code} মুছে ফেলা হয়েছে।",
            subject: null,
            userId: auth()->id()
        );

        return redirect()->route('customers.index')
            ->with('success', "কাস্টমার {$code} সফলভাবে মুছে ফেলা হয়েছে।");
    }
}
