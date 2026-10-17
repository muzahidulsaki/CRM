<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-slate-800 leading-tight">
                    Client Collections & Money Receipts
                </h2>
                <p class="text-xs text-slate-500 mt-1">Track all cash, bank, and mobile financial inflows received from clients.</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('client-payments.create') }}" class="inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-lg shadow-sm transition">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Record New Payment
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
                        <span class="text-xs font-semibold">Total Collections</span>
                        <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div class="text-2xl font-black text-slate-900">{{ __('BDT') }} {{ number_format($metrics['total_collected'], 2) }}</div>
                    <p class="text-[11px] text-slate-400 mt-1">{{ $metrics['payments_count'] }} Transactions recorded</p>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs">
                    <div class="flex items-center justify-between text-slate-500 mb-1">
                        <span class="text-xs font-semibold">Today's Collection</span>
                        <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <div class="text-2xl font-black text-blue-600">{{ __('BDT') }} {{ number_format($metrics['today_collected'], 2) }}</div>
                    <p class="text-[11px] text-slate-400 mt-1">{{ now()->format('d M, Y') }}</p>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs">
                    <div class="flex items-center justify-between text-slate-500 mb-1">
                        <span class="text-xs font-semibold">This Month's Inflow</span>
                        <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                    </div>
                    <div class="text-2xl font-black text-indigo-600">{{ __('BDT') }} {{ number_format($metrics['month_collected'], 2) }}</div>
                    <p class="text-[11px] text-slate-400 mt-1">{{ now()->format('F Y') }}</p>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs">
                    <div class="flex items-center justify-between text-slate-500 mb-1">
                        <span class="text-xs font-semibold">Money Receipts</span>
                        <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <div class="text-2xl font-black text-slate-900">{{ $metrics['payments_count'] }}</div>
                    <p class="text-[11px] text-slate-400 mt-1">Print-ready vouchers</p>
                </div>
            </div>

            <!-- Filter Toolbar -->
            <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-xs">
                <form action="{{ route('client-payments.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div>
                        <input type="text" name="search" value="{{ request('search') }}"
                            class="w-full text-xs px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none"
                            placeholder="Search Receipt #, Client, Transaction ID...">
                    </div>

                    <div>
                        <select name="payment_method" class="w-full text-xs px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                            <option value="">-- All Payment Methods --</option>
                            @foreach($methods as $m)
                                <option value="{{ $m }}" {{ request('payment_method') === $m ? 'selected' : '' }}>{{ $m }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex items-center space-x-2">
                        <button type="submit" class="flex-1 py-2 bg-slate-800 hover:bg-slate-900 text-white text-xs font-bold rounded-lg transition">
                            Filter
                        </button>
                        @if(request()->anyFilled(['search', 'payment_method']))
                            <a href="{{ route('client-payments.index') }}" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-bold rounded-lg transition">
                                Reset
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Payments Table -->
            <div class="bg-white rounded-xl border border-slate-200 shadow-xs overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 text-left text-xs">
                        <thead class="bg-slate-50 text-slate-500 font-bold uppercase tracking-wider">
                            <tr>
                                <th class="px-5 py-3">Receipt No</th>
                                <th class="px-5 py-3">Client</th>
                                <th class="px-5 py-3">Date</th>
                                <th class="px-5 py-3">Method</th>
                                <th class="px-5 py-3">Reference / Order</th>
                                <th class="px-5 py-3 text-right">Amount Collected</th>
                                <th class="px-5 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                            @forelse($payments as $p)
                                <tr class="hover:bg-slate-50/80 transition">
                                    <td class="px-5 py-3.5 whitespace-nowrap">
                                        <a href="{{ route('client-payments.show', $p) }}" class="font-mono font-bold text-blue-600 hover:underline">
                                            {{ $p->payment_no }}
                                        </a>
                                    </td>
                                    <td class="px-5 py-3.5">
                                        <div class="font-bold text-slate-900">{{ $p->customer->name }}</div>
                                        <div class="text-[11px] text-slate-400">
                                            {{ $p->customer->company_name ?? $p->customer->phone }}
                                        </div>
                                    </td>
                                    <td class="px-5 py-3.5 whitespace-nowrap text-slate-500">
                                        {{ $p->payment_date->format('d M, Y') }}
                                    </td>
                                    <td class="px-5 py-3.5 whitespace-nowrap">
                                        <span class="px-2 py-0.5 rounded font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                                            {{ $p->payment_method }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-3.5">
                                        @if($p->invoice)
                                            <span class="text-blue-600 font-mono">{{ $p->invoice->invoice_no }}</span>
                                        @elseif($p->order)
                                            <span class="text-indigo-600 font-mono">{{ $p->order->order_code }}</span>
                                        @endif
                                        @if($p->transaction_reference)
                                            <div class="text-[11px] font-mono text-slate-400">Ref: {{ $p->transaction_reference }}</div>
                                        @endif
                                    </td>
                                    <td class="px-5 py-3.5 whitespace-nowrap text-right font-black text-emerald-600 text-sm">
                                        {{ __('BDT') }} {{ number_format($p->amount, 2) }}
                                    </td>
                                    <td class="px-5 py-3.5 whitespace-nowrap text-right">
                                        <a href="{{ route('client-payments.show', $p) }}" class="inline-flex items-center px-2.5 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded transition">
                                            Money Receipt
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-5 py-8 text-center text-slate-400">
                                        No client payments recorded yet. Click "Record New Payment" to log an inflow.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($payments->hasPages())
                    <div class="p-4 border-t border-slate-200">
                        {{ $payments->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
