<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Customer;
use App\Models\Role;
use App\Models\User;
use App\Services\LeadService;
use App\Services\PhoneHelper;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
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
            description: "New customer created: {$customer->customer_code} ({$customer->name})",
            subject: $customer,
            userId: auth()->id()
        );

        return redirect()->route('customers.show', $customer)
            ->with('success', "Customer {$customer->customer_code} created successfully.");
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
            description: "Customer {$customer->customer_code} updated.",
            subject: $customer,
            userId: auth()->id()
        );

        return redirect()->route('customers.show', $customer)
            ->with('success', "Customer {$customer->customer_code} updated successfully.");
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
            description: "Customer {$code} deleted.",
            subject: null,
            userId: auth()->id()
        );

        return redirect()->route('customers.index')
            ->with('success', "Customer {$code} deleted successfully.");
    }

    /**
     * Create client portal login user for this customer.
     */
    public function createPortalAccount(Request $request, Customer $customer): RedirectResponse
    {
        if ($customer->user_id) {
            return back()->with('error', 'This customer already has a portal login account.');
        }

        $request->validate([
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['nullable', 'string', 'min:6'],
        ]);

        $plainPassword = $request->filled('password') ? $request->password : Str::random(10);
        $clientRole = Role::firstOrCreate(['slug' => 'client'], ['name' => 'Client', 'description' => 'Client Portal Access']);

        $user = User::create([
            'name' => $customer->name,
            'email' => $request->email,
            'phone' => $customer->phone,
            'role_id' => $clientRole->id,
            'status' => 'active',
            'password' => Hash::make($plainPassword),
            'email_verified_at' => now(),
        ]);

        $customer->update([
            'user_id' => $user->id,
            'email' => $request->email,
        ]);

        ActivityLog::log(
            action: 'client_portal_account_created',
            description: "Portal account created for customer {$customer->customer_code} ({$user->email}).",
            subject: $customer,
            userId: auth()->id()
        );

        return back()->with('portal_credentials', [
            'email' => $user->email,
            'password' => $plainPassword,
            'message' => 'Client portal account successfully created! Please share these login details with the client.',
        ]);
    }

    /**
     * Reset portal password for client.
     */
    public function resetPortalPassword(Request $request, Customer $customer): RedirectResponse
    {
        if (!$customer->user) {
            return back()->with('error', 'No portal account linked to this customer.');
        }

        $request->validate([
            'password' => ['nullable', 'string', 'min:6'],
        ]);

        $plainPassword = $request->filled('password') ? $request->password : Str::random(10);
        $customer->user->update([
            'password' => Hash::make($plainPassword),
        ]);

        ActivityLog::log(
            action: 'client_portal_password_reset',
            description: "Portal password reset for customer {$customer->customer_code}.",
            subject: $customer,
            userId: auth()->id()
        );

        return back()->with('portal_credentials', [
            'email' => $customer->user->email,
            'password' => $plainPassword,
            'message' => 'Portal password has been reset! Please share the new password with the client.',
        ]);
    }

    /**
     * Toggle active/inactive portal status.
     */
    public function togglePortalAccess(Customer $customer): RedirectResponse
    {
        if (!$customer->user) {
            return back()->with('error', 'No portal account linked to this customer.');
        }

        $newStatus = $customer->user->status === 'active' ? 'inactive' : 'active';
        $customer->user->update(['status' => $newStatus]);

        ActivityLog::log(
            action: 'client_portal_status_toggled',
            description: "Portal access status changed to '{$newStatus}' for {$customer->customer_code}.",
            subject: $customer,
            userId: auth()->id()
        );

        return back()->with('success', "Portal access has been " . ($newStatus === 'active' ? 'activated' : 'deactivated') . ".");
    }
}
