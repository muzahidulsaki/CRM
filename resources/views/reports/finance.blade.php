<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('reports.index') }}" class="text-xs font-semibold text-indigo-600 hover:underline">&larr; {{ __('Reports Hub') }}</a>
                    <span class="text-gray-300">/</span>
                    <span class="text-xs font-semibold text-gray-500">{{ __('Financial Intelligence') }}</span>
                </div>
                <h2 class="font-bold text-2xl text-gray-900 leading-tight mt-1 flex items-center gap-2">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ __('Financial Performance & Aging Receivables Report') }}
                </h2>
                <p class="text-sm text-gray-500 mt-0.5">
                    {{ __('Cash inflow, cash outflow, net profit & aging receivables analysis') }}
                </p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('reports.export', ['type' => 'finance_inflows', 'range' => $filter]) }}" class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-semibold bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl shadow-sm transition">
                    &darr; Inflows CSV
                </a>
                <a href="{{ route('reports.export', ['type' => 'finance_expenses', 'range' => $filter]) }}" class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-semibold bg-rose-600 hover:bg-rose-700 text-white rounded-xl shadow-sm transition">
                    &darr; Expenses CSV
                </a>
                <button onclick="window.print()" class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-semibold bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 rounded-xl shadow-sm transition">
                    {{ __('Print') }}
                </button>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Filter Range Bar -->
            <div class="bg-white p-4 rounded-2xl border border-gray-200 shadow-sm flex flex-wrap items-center gap-2">
                <span class="text-xs font-bold text-gray-500 uppercase tracking-wider me-2">{{ __('Filter:') }}</span>
                <a href="{{ route('reports.finance', ['range' => 'today']) }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold {{ $filter === 'today' ? 'bg-emerald-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">{{ __('Today') }}</a>
                <a href="{{ route('reports.finance', ['range' => 'yesterday']) }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold {{ $filter === 'yesterday' ? 'bg-emerald-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">{{ __('Yesterday') }}</a>
                <a href="{{ route('reports.finance', ['range' => 'last_7_days']) }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold {{ $filter === 'last_7_days' ? 'bg-emerald-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">{{ __('Last 7 Days') }}</a>
                <a href="{{ route('reports.finance', ['range' => 'this_month']) }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold {{ $filter === 'this_month' ? 'bg-emerald-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">{{ __('This Month') }}</a>
                <a href="{{ route('reports.finance', ['range' => 'last_month']) }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold {{ $filter === 'last_month' ? 'bg-emerald-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">{{ __('Last Month') }}</a>
                <a href="{{ route('reports.finance', ['range' => 'this_year']) }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold {{ $filter === 'this_year' ? 'bg-emerald-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">{{ __('This Year') }}</a>
            </div>

            <!-- Key Financial Metrics Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm">
                    <span class="text-xs font-bold text-emerald-600 uppercase tracking-wider">{{ __('Total Cash Inflow (Inflow)') }}</span>
                    <div class="mt-2 text-3xl font-black text-emerald-600">{{ __('BDT') }} {{ number_format($data['total_inflow'], 2) }}</div>
                    <p class="text-xs text-gray-500 mt-1">{{ __('Payment receipts & collections') }}</p>
                </div>
                <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm">
                    <span class="text-xs font-bold text-rose-600 uppercase tracking-wider">{{ __('Total Cash Outflow (Outflow)') }}</span>
                    <div class="mt-2 text-3xl font-black text-rose-600">{{ __('BDT') }} {{ number_format($data['total_outflow'], 2) }}</div>
                    <p class="text-xs text-gray-500 mt-1">{{ __('Office expenses + payroll') }}</p>
                </div>
                <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm">
                    <span class="text-xs font-bold text-indigo-600 uppercase tracking-wider">{{ __('Net Profit / Loss (Net Profit)') }}</span>
                    <div class="mt-2 text-3xl font-black {{ $data['net_profit'] >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                        {{ $data['net_profit'] >= 0 ? '+' : '' }}{{ __('BDT') }} {{ number_format($data['net_profit'], 2) }}
                    </div>
                    <p class="text-xs text-gray-500 mt-1">{{ __('Inflow - Outflow') }}</p>
                </div>
                <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm">
                    <span class="text-xs font-bold text-amber-600 uppercase tracking-wider">{{ __('Total Outstanding Due (Due)') }}</span>
                    <div class="mt-2 text-3xl font-black text-amber-600">{{ __('BDT') }} {{ number_format($data['aging']['total_due'], 2) }}</div>
                    <p class="text-xs text-gray-500 mt-1">{{ __('Outstanding balance from customers') }}</p>
                </div>
            </div>

            <!-- Aging Accounts Receivable Buckets (SRS Requirement) -->
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
                <div class="flex justify-between items-center mb-4">
                    <div>
                        <h3 class="font-bold text-base text-gray-900 flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-amber-600"></span>
                            {{ __('Aging Accounts Receivable') }}
                        </h3>
                        <p class="text-xs text-gray-500 mt-0.5">{{ __('Analysis of overdue payments grouped by duration') }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    <div class="p-4 rounded-xl border border-emerald-100 bg-emerald-50/50">
                        <span class="text-xs font-bold text-emerald-800 uppercase tracking-wider">0 - 30 {{ __('Days (Current)') }}</span>
                        <div class="text-2xl font-black text-emerald-700 mt-1">{{ __('BDT') }} {{ number_format($data['aging']['current'], 2) }}</div>
                        <p class="text-[11px] text-gray-500 mt-0.5">{{ __('Within normal terms') }}</p>
                    </div>
                    <div class="p-4 rounded-xl border border-amber-100 bg-amber-50/50">
                        <span class="text-xs font-bold text-amber-800 uppercase tracking-wider">31 - 60 {{ __('Days') }}</span>
                        <div class="text-2xl font-black text-amber-700 mt-1">{{ __('BDT') }} {{ number_format($data['aging']['days_31_60'], 2) }}</div>
                        <p class="text-[11px] text-gray-500 mt-0.5">{{ __('Follow-up required') }}</p>
                    </div>
                    <div class="p-4 rounded-xl border border-orange-100 bg-orange-50/50">
                        <span class="text-xs font-bold text-orange-800 uppercase tracking-wider">61 - 90 {{ __('Days') }}</span>
                        <div class="text-2xl font-black text-orange-700 mt-1">{{ __('BDT') }} {{ number_format($data['aging']['days_61_90'], 2) }}</div>
                        <p class="text-[11px] text-gray-500 mt-0.5">{{ __('Send urgent reminder') }}</p>
                    </div>
                    <div class="p-4 rounded-xl border border-rose-100 bg-rose-50/50">
                        <span class="text-xs font-bold text-rose-800 uppercase tracking-wider">90+ {{ __('Days (Overdue)') }}</span>
                        <div class="text-2xl font-black text-rose-700 mt-1">{{ __('BDT') }} {{ number_format($data['aging']['over_90'], 2) }}</div>
                        <p class="text-[11px] text-rose-600 font-semibold mt-0.5">{{ __('High-risk delinquency') }}</p>
                    </div>
                </div>

                <!-- Overdue Invoices List -->
                @if(count($data['aging']['invoices']) > 0)
                    <div class="mt-6 border-t pt-4 overflow-x-auto">
                        <h4 class="font-bold text-xs text-gray-700 uppercase tracking-wider mb-2">{{ __('Outstanding Invoices List:') }}</h4>
                        <table class="w-full text-left text-xs text-gray-700">
                            <thead class="bg-gray-50 text-gray-500 uppercase font-semibold">
                                <tr>
                                    <th class="p-2.5">{{ __('Invoice No') }}</th>
                                    <th class="p-2.5">{{ __('Customer') }}</th>
                                    <th class="p-2.5">{{ __('Issue Date') }}</th>
                                    <th class="p-2.5">{{ __('Age Duration') }}</th>
                                    <th class="p-2.5">{{ __('Category') }}</th>
                                    <th class="p-2.5 text-right">{{ __('Due Amount') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($data['aging']['invoices'] as $item)
                                    <tr>
                                        <td class="p-2.5 font-bold text-indigo-600">
                                            <a href="{{ route('invoices.show', $item['invoice']->id) }}">{{ $item['invoice']->invoice_no }}</a>
                                        </td>
                                        <td class="p-2.5 font-medium text-gray-900">{{ $item['invoice']->customer->name ?? 'N/A' }}</td>
                                        <td class="p-2.5 text-gray-500">{{ $item['invoice']->issue_date }}</td>
                                        <td class="p-2.5 font-semibold text-gray-700">{{ $item['days'] }} {{ __('days ago') }}</td>
                                        <td class="p-2.5">
                                            <span class="px-2 py-0.5 rounded text-[11px] font-semibold {{ $item['days'] > 60 ? 'bg-rose-100 text-rose-800' : 'bg-amber-100 text-amber-800' }}">
                                                {{ $item['bracket'] }}
                                            </span>
                                        </td>
                                        <td class="p-2.5 text-right font-bold text-rose-600">{{ __('BDT') }} {{ number_format($item['invoice']->due_amount, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            <!-- Expense Category Breakdown -->
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
                <h3 class="font-bold text-base text-gray-900 mb-4 flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-rose-600"></span>
                    {{ __('Expense Categories Analysis') }}
                </h3>
                @if(count($data['expense_categories']) > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        @foreach($data['expense_categories'] as $cat => $total)
                            @php
                                $pct = $data['total_expenses'] > 0 ? round(($total / $data['total_expenses']) * 100, 1) : 0;
                            @endphp
                            <div class="p-4 rounded-xl border border-gray-100 bg-gray-50/60">
                                <div class="text-xs font-bold text-gray-600">{{ $cat }}</div>
                                <div class="text-xl font-black text-gray-900 mt-1">{{ __('BDT') }} {{ number_format($total, 2) }}</div>
                                <div class="mt-2 w-full bg-gray-200 rounded-full h-1.5">
                                    <div class="bg-rose-500 h-1.5 rounded-full" style="width: {{ $pct }}%"></div>
                                </div>
                                <div class="text-[11px] text-gray-400 mt-1">{{ $pct }}% {{ __('of total expense') }}</div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-xs text-gray-400 italic py-4 text-center">{{ __('No expense records in this period.') }}</p>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
