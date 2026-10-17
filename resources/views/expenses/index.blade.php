<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-slate-800 leading-tight">
                    Expenses & Office Costs
                </h2>
                <p class="text-xs text-slate-500 mt-1">Track rent, internet, VPS servers, advertising, and operational overheads.</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('expenses.create') }}" class="inline-flex items-center px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold rounded-lg shadow-sm transition">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Log New Expense
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
                        <span class="text-xs font-semibold">Total Expenses</span>
                        <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                    <div class="text-2xl font-black text-slate-900">{{ __('BDT') }} {{ number_format($metrics['total_expenses'], 2) }}</div>
                    <p class="text-[11px] text-slate-400 mt-1">All-time recorded outflow</p>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs">
                    <div class="flex items-center justify-between text-slate-500 mb-1">
                        <span class="text-xs font-semibold">Today's Expenses</span>
                        <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <div class="text-2xl font-black text-amber-600">{{ __('BDT') }} {{ number_format($metrics['today_expenses'], 2) }}</div>
                    <p class="text-[11px] text-slate-400 mt-1">{{ now()->format('d M, Y') }}</p>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs">
                    <div class="flex items-center justify-between text-slate-500 mb-1">
                        <span class="text-xs font-semibold">This Month</span>
                        <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    </div>
                    <div class="text-2xl font-black text-purple-600">{{ __('BDT') }} {{ number_format($metrics['month_expenses'], 2) }}</div>
                    <p class="text-[11px] text-slate-400 mt-1">{{ now()->format('F Y') }}</p>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs">
                    <div class="flex items-center justify-between text-slate-500 mb-1">
                        <span class="text-xs font-semibold">Recurring Trackers</span>
                        <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    </div>
                    <div class="text-2xl font-black text-blue-600">{{ $metrics['recurring_count'] }}</div>
                    <p class="text-[11px] text-slate-400 mt-1">Rent, servers & bills</p>
                </div>
            </div>

            <!-- Filter Toolbar -->
            <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-xs">
                <form action="{{ route('expenses.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div>
                        <input type="text" name="search" value="{{ request('search') }}"
                            class="w-full text-xs px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-rose-500 outline-none"
                            placeholder="Search Title, Expense #, Vendor...">
                    </div>

                    <div>
                        <select name="category" class="w-full text-xs px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-rose-500 outline-none">
                            <option value="">-- All Categories --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat }}" {{ request('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex items-center space-x-2">
                        <button type="submit" class="flex-1 py-2 bg-slate-800 hover:bg-slate-900 text-white text-xs font-bold rounded-lg transition">
                            Filter
                        </button>
                        @if(request()->anyFilled(['search', 'category']))
                            <a href="{{ route('expenses.index') }}" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-bold rounded-lg transition">
                                Reset
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Expenses Table -->
            <div class="bg-white rounded-xl border border-slate-200 shadow-xs overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 text-left text-xs">
                        <thead class="bg-slate-50 text-slate-500 font-bold uppercase tracking-wider">
                            <tr>
                                <th class="px-5 py-3">Expense #</th>
                                <th class="px-5 py-3">Category</th>
                                <th class="px-5 py-3">Title / Description</th>
                                <th class="px-5 py-3">Date</th>
                                <th class="px-5 py-3">Paid To / Vendor</th>
                                <th class="px-5 py-3">Method</th>
                                <th class="px-5 py-3 text-right">Amount ({{ __('BDT') }})</th>
                                <th class="px-5 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                            @forelse($expenses as $exp)
                                <tr class="hover:bg-slate-50/80 transition">
                                    <td class="px-5 py-3.5 whitespace-nowrap font-mono font-bold text-slate-800">
                                        {{ $exp->expense_no }}
                                    </td>
                                    <td class="px-5 py-3.5 whitespace-nowrap">
                                        <span class="px-2 py-0.5 rounded font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                                            {{ $exp->category }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-3.5">
                                        <div class="font-bold text-slate-900">{{ $exp->title }}</div>
                                        @if($exp->is_recurring)
                                            <span class="text-[10px] text-blue-600 font-semibold">&bull; Recurring ({{ $exp->recurring_frequency }})</span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-3.5 whitespace-nowrap text-slate-500">
                                        {{ $exp->expense_date->format('d M, Y') }}
                                    </td>
                                    <td class="px-5 py-3.5 whitespace-nowrap text-slate-600">
                                        {{ $exp->paid_to ?? 'Internal / General' }}
                                    </td>
                                    <td class="px-5 py-3.5 whitespace-nowrap">
                                        {{ $exp->payment_method }}
                                    </td>
                                    <td class="px-5 py-3.5 whitespace-nowrap text-right font-black text-rose-600 text-sm">
                                        {{ __('BDT') }} {{ number_format($exp->amount, 2) }}
                                    </td>
                                    <td class="px-5 py-3.5 whitespace-nowrap text-right space-x-1.5">
                                        <a href="{{ route('expenses.show', $exp) }}" class="inline-flex items-center px-2 py-1 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold text-xs rounded transition">
                                            View
                                        </a>
                                        <a href="{{ route('expenses.edit', $exp) }}" class="inline-flex items-center px-2 py-1 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold text-xs rounded transition">
                                            Edit
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-5 py-8 text-center text-slate-400">
                                        No expenses recorded yet. Click "Log New Expense" to add an office cost.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($expenses->hasPages())
                    <div class="p-4 border-t border-slate-200">
                        {{ $expenses->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
