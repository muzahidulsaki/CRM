<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight flex items-center gap-2">
                    <svg class="w-7 h-7 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    {{ __('Customer 360° Directory') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">{{ __('Complete profile & history of regular customers and companies') }}</p>
            </div>
            <a href="{{ route('customers.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 shadow-sm transition">
                <svg class="w-4 h-4 me-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                {{ __('Add New Customer') }}
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Alerts -->
            @if(session('success'))
                <div class="p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 rounded-r-lg shadow-sm flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        <span class="font-medium text-sm">{{ __(session('success')) }}</span>
                    </div>
                </div>
            @endif

            <!-- Customer Metrics -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm flex items-center justify-between">
                    <div>
                        <div class="text-xs font-semibold text-gray-500 uppercase">{{ __('Total Customers') }}</div>
                        <div class="text-2xl font-bold text-gray-900 mt-1">{{ $metrics['total'] }}</div>
                    </div>
                    <div class="w-10 h-10 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold">
                        👥
                    </div>
                </div>

                <div class="bg-white p-4 rounded-xl border border-emerald-200 bg-emerald-50/30 shadow-sm flex items-center justify-between">
                    <div>
                        <div class="text-xs font-semibold text-emerald-600 uppercase">{{ __('Active Customers') }}</div>
                        <div class="text-2xl font-bold text-emerald-700 mt-1">{{ $metrics['active'] }}</div>
                    </div>
                    <div class="w-10 h-10 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold">
                        ✓
                    </div>
                </div>

                <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm flex items-center justify-between">
                    <div>
                        <div class="text-xs font-semibold text-gray-400 uppercase">{{ __('Inactive Customers') }}</div>
                        <div class="text-2xl font-bold text-gray-600 mt-1">{{ $metrics['inactive'] }}</div>
                    </div>
                    <div class="w-10 h-10 rounded-lg bg-gray-50 text-gray-500 flex items-center justify-center font-bold">
                        ✕
                    </div>
                </div>
            </div>

            <!-- Search Bar -->
            <div class="bg-white p-4 rounded-2xl border border-gray-200 shadow-sm">
                <form method="GET" action="{{ route('customers.index') }}" class="flex flex-col sm:flex-row gap-3">
                    <div class="relative flex-1">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('Search by name, company, phone or code...') }}" class="w-full text-sm border-gray-300 rounded-lg pl-9 focus:ring-indigo-500 focus:border-indigo-500">
                        <svg class="w-4 h-4 text-gray-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>

                    <div class="flex items-center gap-2">
                        <select name="status" class="text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">{{ __('All Statuses') }}</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>{{ __('Active') }}</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>{{ __('Inactive') }}</option>
                        </select>

                        <button type="submit" class="px-5 py-2.5 bg-gray-900 text-white rounded-lg text-sm font-semibold hover:bg-gray-800 transition">
                            {{ __('Search') }}
                        </button>
                    </div>
                </form>
            </div>

            <!-- Customer Table -->
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-left text-sm">
                        <thead class="bg-gray-50 text-gray-500 font-semibold uppercase text-xs">
                            <tr>
                                <th class="py-3.5 px-4">{{ __('Customer Code & Name') }}</th>
                                <th class="py-3.5 px-4">{{ __('Company / Business') }}</th>
                                <th class="py-3.5 px-4">{{ __('Contact (Phone / WA)') }}</th>
                                <th class="py-3.5 px-4 text-center">{{ __('Total Leads') }}</th>
                                <th class="py-3.5 px-4 text-center">{{ __('Total Quotations') }}</th>
                                <th class="py-3.5 px-4">{{ __('Status') }}</th>
                                <th class="py-3.5 px-4 text-right">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($customers as $customer)
                                <tr class="hover:bg-gray-50/60 transition">
                                    <td class="py-3.5 px-4">
                                        <a href="{{ route('customers.show', $customer) }}" class="font-bold text-indigo-600 hover:text-indigo-800">
                                            {{ $customer->customer_code }}
                                        </a>
                                        <div class="font-medium text-gray-900">{{ $customer->name }}</div>
                                    </td>
                                    <td class="py-3.5 px-4 text-xs text-gray-700">
                                        <div class="font-semibold text-gray-900">{{ $customer->company_name ?? __('Individual') }}</div>
                                        <div class="text-gray-400">{{ $customer->business_type ?? '-' }}</div>
                                    </td>
                                    <td class="py-3.5 px-4 text-xs">
                                        <div class="font-medium text-gray-900">{{ $customer->phone }}</div>
                                        @if($customer->whatsapp)
                                             <a href="https://wa.me/{{ $customer->normalized_whatsapp ? '88' . $customer->normalized_whatsapp : '88' . $customer->normalized_phone }}" target="_blank" class="text-emerald-600 hover:underline">
                                                WA: {{ $customer->whatsapp }}
                                            </a>
                                        @endif
                                    </td>
                                    <td class="py-3.5 px-4 text-center font-bold text-gray-700">
                                        {{ $customer->leads_count }}
                                    </td>
                                    <td class="py-3.5 px-4 text-center font-bold text-indigo-600">
                                        {{ $customer->quotations_count }}
                                    </td>
                                    <td class="py-3.5 px-4">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold {{ $customer->status === 'active' ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-100 text-gray-700' }}">
                                            {{ __($customer->status === 'active' ? 'Active' : 'Inactive') }}
                                        </span>
                                    </td>
                                    <td class="py-3.5 px-4 text-right">
                                        <a href="{{ route('customers.show', $customer) }}" class="inline-flex items-center gap-1 text-xs font-semibold text-indigo-700 bg-indigo-50 hover:bg-indigo-100 px-3 py-1.5 rounded-lg transition">
                                            {{ __('360° Profile') }} &rarr;
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-10 text-gray-400 text-xs">
                                        {{ __('No customers found.') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($customers->hasPages())
                    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                        {{ $customers->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
