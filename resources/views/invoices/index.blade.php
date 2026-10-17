<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-slate-800 leading-tight">
                    {{ __('Invoices & Client Billing') }}
                </h2>
                <p class="text-xs text-slate-500 mt-1">{{ __('Generate official client bills, track receivables, discounts, and payment status.') }}</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('client-payments.create') }}" class="inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-lg shadow-sm transition">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    {{ __('Record Payment') }}
                </a>
                <a href="{{ route('invoices.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-lg shadow-sm transition">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    {{ __('Create New Invoice') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="p-4 bg-emerald-50 border border-emerald-300 rounded-xl text-emerald-800 text-sm flex items-center">
                    <svg class="w-5 h-5 mr-3 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <!-- KPI Metric Cards -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs">
                    <div class="flex items-center justify-between text-slate-500 mb-1">
                        <span class="text-xs font-semibold">Total Invoiced</span>
                        <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <div class="text-2xl font-black text-slate-900">{{ __('BDT') }} {{ number_format($metrics['total_invoiced'], 2) }}</div>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs">
                    <div class="flex items-center justify-between text-slate-500 mb-1">
                        <span class="text-xs font-semibold">Total Paid</span>
                        <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div class="text-2xl font-black text-emerald-600">{{ __('BDT') }} {{ number_format($metrics['total_paid'], 2) }}</div>
                    <p class="text-[11px] text-slate-400 mt-1">{{ $metrics['paid_invoices_count'] }} Invoices fully cleared</p>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs">
                    <div class="flex items-center justify-between text-slate-500 mb-1">
                        <span class="text-xs font-semibold">Receivable Due</span>
                        <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div class="text-2xl font-black text-amber-600">{{ __('BDT') }} {{ number_format($metrics['total_due'], 2) }}</div>
                    <p class="text-[11px] text-slate-400 mt-1">{{ $metrics['due_invoices_count'] }} Invoices pending</p>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs">
                    <div class="flex items-center justify-between text-slate-500 mb-1">
                        <span class="text-xs font-semibold">Payment Status</span>
                        <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                    </div>
                    <div class="text-2xl font-black text-indigo-600">
                        @php
                            $collectionRate = $metrics['total_invoiced'] > 0 ? round(($metrics['total_paid'] / $metrics['total_invoiced']) * 100, 1) : 100;
                        @endphp
                        {{ $collectionRate }}%
                    </div>
                    <p class="text-[11px] text-slate-400 mt-1">Collection Efficiency</p>
                </div>
            </div>

            <!-- Filter Toolbar -->
            <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-xs">
                <form action="{{ route('invoices.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3">
                    <div>
                        <input type="text" name="search" value="{{ request('search') }}"
                            class="w-full text-xs px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none"
                            placeholder="Search Invoice #, Client Name...">
                    </div>

                    <div>
                        <select name="status" class="w-full text-xs px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                            <option value="">-- All Statuses --</option>
                            @foreach($statuses as $st)
                                <option value="{{ $st }}" {{ request('status') === $st ? 'selected' : '' }}>{{ $st }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <select name="customer_id" class="w-full text-xs px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                            <option value="">-- All Clients --</option>
                            @foreach($customers as $c)
                                <option value="{{ $c->id }}" {{ request('customer_id') == $c->id ? 'selected' : '' }}>
                                    {{ $c->name }} ({{ $c->company_name ?? 'Individual' }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex items-center space-x-2">
                        <button type="submit" class="flex-1 py-2 bg-slate-800 hover:bg-slate-900 text-white text-xs font-bold rounded-lg transition">
                            Filter
                        </button>
                        @if(request()->anyFilled(['search', 'status', 'customer_id']))
                            <a href="{{ route('invoices.index') }}" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-bold rounded-lg transition">
                                Reset
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Invoices Table -->
            <div class="bg-white rounded-xl border border-slate-200 shadow-xs overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 text-left text-xs">
                        <thead class="bg-slate-50 text-slate-500 font-bold uppercase tracking-wider">
                            <tr>
                                <th class="px-5 py-3">Invoice No</th>
                                <th class="px-5 py-3">Client & Project</th>
                                <th class="px-5 py-3">Issue Date</th>
                                <th class="px-5 py-3">Due Date</th>
                                <th class="px-5 py-3 text-right">Total Amount</th>
                                <th class="px-5 py-3 text-right">Paid</th>
                                <th class="px-5 py-3 text-right">Due Balance</th>
                                <th class="px-5 py-3 text-center">Status</th>
                                <th class="px-5 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                            @forelse($invoices as $inv)
                                <tr class="hover:bg-slate-50/80 transition">
                                    <td class="px-5 py-3.5 whitespace-nowrap">
                                        <a href="{{ route('invoices.show', $inv) }}" class="font-mono font-bold text-blue-600 hover:underline">
                                            {{ $inv->invoice_no }}
                                        </a>
                                    </td>
                                    <td class="px-5 py-3.5">
                                        <div class="font-bold text-slate-900">{{ $inv->customer->name }}</div>
                                        <div class="text-[11px] text-slate-400">
                                            {{ $inv->customer->company_name ?? 'Individual' }}
                                            @if($inv->project)
                                                &bull; <span class="text-blue-500">{{ $inv->project->name }}</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-5 py-3.5 whitespace-nowrap text-slate-500">
                                        {{ $inv->issue_date->format('d M, Y') }}
                                    </td>
                                    <td class="px-5 py-3.5 whitespace-nowrap text-slate-500">
                                        {{ $inv->due_date->format('d M, Y') }}
                                    </td>
                                    <td class="px-5 py-3.5 whitespace-nowrap text-right font-bold text-slate-900">
                                        {{ __('BDT') }} {{ number_format($inv->total_amount, 2) }}
                                    </td>
                                    <td class="px-5 py-3.5 whitespace-nowrap text-right text-emerald-600 font-semibold">
                                        {{ __('BDT') }} {{ number_format($inv->paid_amount, 2) }}
                                    </td>
                                    <td class="px-5 py-3.5 whitespace-nowrap text-right font-bold {{ $inv->due_amount > 0 ? 'text-amber-600' : 'text-slate-400' }}">
                                        {{ __('BDT') }} {{ number_format($inv->due_amount, 2) }}
                                    </td>
                                    <td class="px-5 py-3.5 whitespace-nowrap text-center">
                                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold border {{ $inv->status_badge_color }}">
                                            {{ $inv->status }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-3.5 whitespace-nowrap text-right space-x-1.5">
                                        <a href="{{ route('invoices.show', $inv) }}" class="inline-flex items-center px-2.5 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded transition">
                                            View / Print
                                        </a>
                                        @if($inv->due_amount > 0)
                                            <a href="{{ route('client-payments.create', ['invoice_id' => $inv->id]) }}" class="inline-flex items-center px-2.5 py-1 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 font-bold text-xs rounded border border-emerald-200 transition">
                                                Pay
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="px-5 py-8 text-center text-slate-400">
                                        No invoices recorded yet. Click "Create New Invoice" to generate one.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($invoices->hasPages())
                    <div class="p-4 border-t border-slate-200">
                        {{ $invoices->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
