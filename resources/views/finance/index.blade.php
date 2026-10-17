<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-slate-800 leading-tight">
                    Financial Statements & Profit/Loss
                </h2>
                <p class="text-xs text-slate-500 mt-1">Real-time cashflow, income, employee payouts, office overheads, and net profit ledger.</p>
            </div>
            <div class="flex items-center space-x-2">
                <a href="{{ route('invoices.create') }}" class="px-3.5 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-lg transition shadow-xs">
                    + New Invoice
                </a>
                <a href="{{ route('client-payments.create') }}" class="px-3.5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-lg transition shadow-xs">
                    + Record Payment
                </a>
                <a href="{{ route('expenses.create') }}" class="px-3.5 py-2 bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold rounded-lg transition shadow-xs">
                    + Log Expense
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Date Range Filter Bar -->
            <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-center space-x-2 text-xs font-bold text-slate-700">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <span>Financial Statement Period:</span>
                    <span class="text-slate-500 font-medium">{{ \Carbon\Carbon::parse($startDate)->format('d M, Y') }} &rarr; {{ \Carbon\Carbon::parse($endDate)->format('d M, Y') }}</span>
                </div>

                <form action="{{ route('finance.index') }}" method="GET" class="flex items-center space-x-2">
                    <input type="date" name="start_date" value="{{ $startDate }}" class="text-xs px-2.5 py-1.5 border border-slate-300 rounded-lg outline-none">
                    <span class="text-xs text-slate-400">to</span>
                    <input type="date" name="end_date" value="{{ $endDate }}" class="text-xs px-2.5 py-1.5 border border-slate-300 rounded-lg outline-none">
                    <button type="submit" class="px-3 py-1.5 bg-slate-800 hover:bg-slate-900 text-white text-xs font-bold rounded-lg transition">
                        Apply
                    </button>
                    <a href="{{ route('finance.index') }}" class="px-2.5 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-bold rounded-lg transition">
                        This Month
                    </a>
                </form>
            </div>

            <!-- Executive Cashflow Formula Banner -->
            <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 text-white rounded-3xl p-6 sm:p-8 shadow-xl">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div>
                        <span class="inline-block px-3 py-1 bg-white/10 text-white text-xs font-bold rounded-full uppercase tracking-wider mb-2">
                            Financial Equation
                        </span>
                        <h3 class="text-xl sm:text-2xl font-black tracking-tight">
                            Net Income / Profit = Total Inflow &minus; Total Outflow
                        </h3>
                        <p class="text-xs text-slate-300 mt-1 max-w-xl">
                            All values calculated dynamically from database client receipts, employee payouts, and recorded operational expenses.
                        </p>
                    </div>

                    <div class="bg-white/10 backdrop-blur-md rounded-2xl p-5 border border-white/20 text-center min-w-[200px]">
                        <span class="text-xs uppercase font-bold text-slate-300 tracking-wider">Net Profit</span>
                        <div class="text-3xl font-black mt-1 {{ $summary['net_profit'] >= 0 ? 'text-emerald-400' : 'text-rose-400' }}">
                            {{ __('BDT') }} {{ number_format($summary['net_profit'], 2) }}
                        </div>
                        <span class="text-[11px] text-slate-300 mt-0.5 block">
                            {{ $summary['net_profit'] >= 0 ? 'Surplus / Net Profit' : 'Deficit / Net Loss' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Inflow vs Outflow Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Total Inflow -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs">
                    <div class="flex items-center justify-between text-emerald-600 mb-2">
                        <span class="text-xs font-bold uppercase tracking-wider">Total Inflow (Collections)</span>
                        <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"/></svg>
                        </div>
                    </div>
                    <div class="text-2xl font-black text-slate-900">{{ __('BDT') }} {{ number_format($summary['total_inflow'], 2) }}</div>
                    <div class="mt-4 pt-3 border-t border-slate-100 text-xs text-slate-500 space-y-1">
                        <div class="flex justify-between">
                            <span>Client Payments & Renewals:</span>
                            <span class="font-bold text-slate-800">{{ __('BDT') }} {{ number_format($summary['total_client_income'], 2) }}</span>
                        </div>
                        <div class="flex justify-between text-amber-600">
                            <span>Pending Client Due:</span>
                            <span class="font-semibold">{{ __('BDT') }} {{ number_format($summary['total_receivable_due'], 2) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Total Outflow -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs">
                    <div class="flex items-center justify-between text-rose-600 mb-2">
                        <span class="text-xs font-bold uppercase tracking-wider">Total Outflow (Expenses + Payouts)</span>
                        <div class="w-8 h-8 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"/></svg>
                        </div>
                    </div>
                    <div class="text-2xl font-black text-slate-900">{{ __('BDT') }} {{ number_format($summary['total_outflow'], 2) }}</div>
                    <div class="mt-4 pt-3 border-t border-slate-100 text-xs text-slate-500 space-y-1">
                        <div class="flex justify-between">
                            <span>Employee Payouts:</span>
                            <span class="font-bold text-slate-800">{{ __('BDT') }} {{ number_format($summary['total_employee_payouts'], 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Office & Server Expenses:</span>
                            <span class="font-bold text-slate-800">{{ __('BDT') }} {{ number_format($summary['total_office_expenses'], 2) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Renewal Alerts Quick Box -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs">
                    <div class="flex items-center justify-between text-amber-600 mb-2">
                        <span class="text-xs font-bold uppercase tracking-wider">Renewal Alerts</span>
                        <a href="{{ route('renewals.index') }}" class="text-xs text-blue-600 hover:underline font-bold">View All &rarr;</a>
                    </div>
                    <div class="text-2xl font-black text-slate-900">{{ $summary['renewals_alert']['total_alerts'] }} <span class="text-xs font-semibold text-slate-400">Alerts</span></div>
                    <div class="mt-4 pt-3 border-t border-slate-100 text-xs space-y-1">
                        <div class="flex justify-between text-rose-600 font-semibold">
                            <span>Overdue Subscriptions:</span>
                            <span>{{ $summary['renewals_alert']['overdue'] }}</span>
                        </div>
                        <div class="flex justify-between text-red-600 font-semibold">
                            <span>Due Today:</span>
                            <span>{{ $summary['renewals_alert']['due_today'] }}</span>
                        </div>
                        <div class="flex justify-between text-amber-600">
                            <span>Due within 7 Days:</span>
                            <span>{{ $summary['renewals_alert']['upcoming'] }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Two Columns: Recent Collections & Expenses Breakdown -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                <!-- Recent Client Collections -->
                <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-6 space-y-4">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                        <h4 class="font-bold text-sm text-slate-900 flex items-center">
                            <svg class="w-4 h-4 mr-2 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Recent Client Collections (Inflow)
                        </h4>
                        <a href="{{ route('client-payments.index') }}" class="text-xs font-bold text-blue-600 hover:underline">
                            All Receipts &rarr;
                        </a>
                    </div>

                    <div class="divide-y divide-slate-100 text-xs">
                        @forelse($recentPayments as $p)
                            <div class="py-2.5 flex items-center justify-between">
                                <div>
                                    <span class="font-bold text-slate-900">{{ $p->customer->name }}</span>
                                    <div class="text-[11px] text-slate-400">{{ $p->payment_no }} &bull; {{ $p->payment_method }} &bull; {{ $p->payment_date->format('d M, Y') }}</div>
                                </div>
                                <div class="text-right">
                                    <span class="font-black text-emerald-600 text-sm">{{ __('BDT') }} {{ number_format($p->amount, 2) }}</span>
                                </div>
                            </div>
                        @empty
                            <p class="text-xs text-slate-400 italic py-4 text-center">No client payments recorded yet.</p>
                        @endforelse
                    </div>
                </div>

                <!-- Expenses Breakdown by Category -->
                <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-6 space-y-4">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                        <h4 class="font-bold text-sm text-slate-900 flex items-center">
                            <svg class="w-4 h-4 mr-2 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/></svg>
                            Expenses by Category (Outflow)
                        </h4>
                        <a href="{{ route('expenses.index') }}" class="text-xs font-bold text-blue-600 hover:underline">
                            All Expenses &rarr;
                        </a>
                    </div>

                    <div class="space-y-3 text-xs">
                        @forelse($expenseCategories as $ec)
                            <div>
                                <div class="flex justify-between items-center mb-1">
                                    <span class="font-semibold text-slate-700">{{ $ec->category }}</span>
                                    <span class="font-bold text-slate-900">{{ __('BDT') }} {{ number_format($ec->total, 2) }}</span>
                                </div>
                                @php
                                    $pct = $summary['total_office_expenses'] > 0 ? round(($ec->total / $summary['total_office_expenses']) * 100, 1) : 0;
                                @endphp
                                <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden">
                                    <div class="bg-rose-500 h-1.5 rounded-full" style="width: {{ $pct }}%"></div>
                                </div>
                            </div>
                        @empty
                            <p class="text-xs text-slate-400 italic py-4 text-center">No expenses in selected period.</p>
                        @endforelse
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>
