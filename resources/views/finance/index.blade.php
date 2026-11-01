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

            <!-- ACCOUNTS DUE PAYMENT ALERTS (Excel CRM Parity) -->
            @if(isset($dueAlerts))
            <div class="space-y-4" x-data="{ activeDueTab: 'creative' }">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-black text-slate-900 flex items-center gap-2">
                            <span class="flex h-3 w-3 relative">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-3 w-3 bg-rose-500"></span>
                            </span>
                            Accounts Due Payment Alerts
                        </h3>
                        <p class="text-xs text-slate-500 mt-0.5">Real-time alerts for Creative Dues, Website Initial Dues, and Recurring Subscription Dues.</p>
                    </div>
                    <div class="flex items-center gap-1 bg-slate-100 p-1 rounded-xl text-xs font-bold">
                        <button type="button" @click="activeDueTab = 'creative'" :class="activeDueTab === 'creative' ? 'bg-white text-purple-700 shadow-xs' : 'text-slate-600 hover:text-slate-900'" class="px-3 py-1.5 rounded-lg transition">
                            🎨 Creative ({{ $dueAlerts['creative']['count'] }})
                        </button>
                        <button type="button" @click="activeDueTab = 'website_initial'" :class="activeDueTab === 'website_initial' ? 'bg-white text-blue-700 shadow-xs' : 'text-slate-600 hover:text-slate-900'" class="px-3 py-1.5 rounded-lg transition">
                            💻 Website Initial ({{ $dueAlerts['website_initial']['count'] }})
                        </button>
                        <button type="button" @click="activeDueTab = 'subscription_fee'" :class="activeDueTab === 'subscription_fee' ? 'bg-white text-emerald-700 shadow-xs' : 'text-slate-600 hover:text-slate-900'" class="px-3 py-1.5 rounded-lg transition">
                            🔄 Subscription Fee ({{ $dueAlerts['subscription_fee']['count'] }})
                        </button>
                    </div>
                </div>

                <!-- 3 Due Summary Metric Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    <!-- Creative Due Card -->
                    <div @click="activeDueTab = 'creative'" class="cursor-pointer bg-white p-5 rounded-2xl border-2 transition hover:shadow-md" :class="activeDueTab === 'creative' ? 'border-purple-500 bg-purple-50/20 shadow-xs' : 'border-slate-200'">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-bold uppercase tracking-wider text-purple-700 flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-purple-500"></span>
                                Creative Due Alert
                            </span>
                            <span class="px-2 py-0.5 rounded-full text-[11px] font-black bg-purple-100 text-purple-800">
                                {{ $dueAlerts['creative']['count'] }} Orders
                            </span>
                        </div>
                        <div class="text-2xl font-black text-slate-900">
                            {{ __('BDT') }} {{ number_format($dueAlerts['creative']['total_due'], 2) }}
                        </div>
                        <p class="text-[11px] text-slate-500 mt-1">Pending payments on video editing, graphic design, and creative services.</p>
                    </div>

                    <!-- Website Initial Due Card -->
                    <div @click="activeDueTab = 'website_initial'" class="cursor-pointer bg-white p-5 rounded-2xl border-2 transition hover:shadow-md" :class="activeDueTab === 'website_initial' ? 'border-blue-500 bg-blue-50/20 shadow-xs' : 'border-slate-200'">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-bold uppercase tracking-wider text-blue-700 flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                                Website Initial Due Alert
                            </span>
                            <span class="px-2 py-0.5 rounded-full text-[11px] font-black bg-blue-100 text-blue-800">
                                {{ $dueAlerts['website_initial']['count'] }} Orders
                            </span>
                        </div>
                        <div class="text-2xl font-black text-slate-900">
                            {{ __('BDT') }} {{ number_format($dueAlerts['website_initial']['total_due'], 2) }}
                        </div>
                        <p class="text-[11px] text-slate-500 mt-1">Remaining project milestones/installments on web and ERP software development.</p>
                    </div>

                    <!-- Website Monthly/Yearly Subscription Fee Due Card -->
                    <div @click="activeDueTab = 'subscription_fee'" class="cursor-pointer bg-white p-5 rounded-2xl border-2 transition hover:shadow-md" :class="activeDueTab === 'subscription_fee' ? 'border-emerald-500 bg-emerald-50/20 shadow-xs' : 'border-slate-200'">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-bold uppercase tracking-wider text-emerald-700 flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                Subscription Fee Due Alert
                            </span>
                            <span class="px-2 py-0.5 rounded-full text-[11px] font-black bg-emerald-100 text-emerald-800">
                                {{ $dueAlerts['subscription_fee']['count'] }} Renewals
                            </span>
                        </div>
                        <div class="text-2xl font-black text-slate-900">
                            {{ __('BDT') }} {{ number_format($dueAlerts['subscription_fee']['total_due'], 2) }}
                        </div>
                        <p class="text-[11px] text-slate-500 mt-1">Monthly/Yearly domain, hosting, maintenance, and ERP SaaS recurring dues.</p>
                    </div>
                </div>

                <!-- Tab 1: Creative Due Detail Table -->
                <div x-show="activeDueTab === 'creative'" class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
                    <div class="p-4 bg-purple-50/50 border-b border-purple-100 flex items-center justify-between">
                        <div class="font-bold text-xs text-purple-900 flex items-center gap-2">
                            <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                            Creative Orders with Outstanding Due
                        </div>
                        <a href="{{ route('orders.index', ['type' => 'creative']) }}" class="text-xs font-bold text-purple-700 hover:underline">View All Creative Orders &rarr;</a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-100 text-xs">
                            <thead class="bg-slate-50 font-bold text-slate-600 uppercase tracking-wider text-[11px]">
                                <tr>
                                    <th class="px-4 py-2.5 text-left">Order #</th>
                                    <th class="px-4 py-2.5 text-left">Client & WhatsApp</th>
                                    <th class="px-4 py-2.5 text-left">Service / Title</th>
                                    <th class="px-4 py-2.5 text-right">Total Price</th>
                                    <th class="px-4 py-2.5 text-right">Paid</th>
                                    <th class="px-4 py-2.5 text-right text-rose-600">Due Amount</th>
                                    <th class="px-4 py-2.5 text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($dueAlerts['creative']['items'] as $item)
                                    @php $phoneClean = preg_replace('/[^0-9]/', '', $item->customer->phone ?? ''); @endphp
                                    <tr class="hover:bg-slate-50/70">
                                        <td class="px-4 py-2.5 font-bold text-slate-800">
                                            <a href="{{ route('orders.show', $item->id) }}" class="text-blue-600 hover:underline">{{ $item->order_number }}</a>
                                        </td>
                                        <td class="px-4 py-2.5">
                                            <div class="font-bold text-slate-900">{{ $item->customer->name ?? 'N/A' }}</div>
                                            <div class="text-[11px] text-slate-500 font-mono">{{ $item->customer->phone ?? 'N/A' }}</div>
                                        </td>
                                        <td class="px-4 py-2.5">
                                            <span class="px-2 py-0.5 rounded bg-purple-50 text-purple-700 font-medium">{{ $item->service->name ?? $item->title }}</span>
                                        </td>
                                        <td class="px-4 py-2.5 text-right font-medium text-slate-700">{{ __('BDT') }} {{ number_format($item->price, 2) }}</td>
                                        <td class="px-4 py-2.5 text-right font-semibold text-emerald-600">{{ __('BDT') }} {{ number_format($item->paid_amount, 2) }}</td>
                                        <td class="px-4 py-2.5 text-right font-black text-rose-600">{{ __('BDT') }} {{ number_format($item->due_amount, 2) }}</td>
                                        <td class="px-4 py-2.5 text-center">
                                            @if($phoneClean)
                                                <a href="https://wa.me/{{ $phoneClean }}?text={{ urlencode('Assalamu Alaikum ' . ($item->customer->name ?? '') . ', this is a gentle reminder regarding your pending due of BDT ' . number_format($item->due_amount, 2) . ' for Order #' . $item->order_number . '. Thank you!') }}" target="_blank" class="inline-flex items-center gap-1 px-2.5 py-1 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-lg transition shadow-xs text-[11px]">
                                                    💬 Send Reminder
                                                </a>
                                            @else
                                                <span class="text-slate-400 italic">No WhatsApp</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-4 py-6 text-center text-slate-400 italic">No creative order dues pending at this moment! 🎉</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Tab 2: Website Initial Due Detail Table -->
                <div x-show="activeDueTab === 'website_initial'" class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
                    <div class="p-4 bg-blue-50/50 border-b border-blue-100 flex items-center justify-between">
                        <div class="font-bold text-xs text-blue-900 flex items-center gap-2">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                            Website & Software Development Orders with Initial Due
                        </div>
                        <a href="{{ route('orders.index', ['type' => 'web_software']) }}" class="text-xs font-bold text-blue-700 hover:underline">View All Soft/Web Orders &rarr;</a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-100 text-xs">
                            <thead class="bg-slate-50 font-bold text-slate-600 uppercase tracking-wider text-[11px]">
                                <tr>
                                    <th class="px-4 py-2.5 text-left">Order #</th>
                                    <th class="px-4 py-2.5 text-left">Client & WhatsApp</th>
                                    <th class="px-4 py-2.5 text-left">Subscription System</th>
                                    <th class="px-4 py-2.5 text-right">Project Total</th>
                                    <th class="px-4 py-2.5 text-right">Paid Installments</th>
                                    <th class="px-4 py-2.5 text-right text-rose-600">Initial Due</th>
                                    <th class="px-4 py-2.5 text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($dueAlerts['website_initial']['items'] as $item)
                                    @php $phoneClean = preg_replace('/[^0-9]/', '', $item->customer->phone ?? ''); @endphp
                                    <tr class="hover:bg-slate-50/70">
                                        <td class="px-4 py-2.5 font-bold text-slate-800">
                                            <a href="{{ route('orders.show', $item->id) }}" class="text-blue-600 hover:underline">{{ $item->order_number }}</a>
                                        </td>
                                        <td class="px-4 py-2.5">
                                            <div class="font-bold text-slate-900">{{ $item->customer->name ?? 'N/A' }}</div>
                                            <div class="text-[11px] text-slate-500 font-mono">{{ $item->customer->phone ?? 'N/A' }}</div>
                                        </td>
                                        <td class="px-4 py-2.5 font-medium text-slate-700">
                                            {{ $item->getMeta('subscription_system', 'Regular') }}
                                        </td>
                                        <td class="px-4 py-2.5 text-right font-medium text-slate-700">{{ __('BDT') }} {{ number_format($item->price, 2) }}</td>
                                        <td class="px-4 py-2.5 text-right font-semibold text-emerald-600">{{ __('BDT') }} {{ number_format($item->paid_amount, 2) }}</td>
                                        <td class="px-4 py-2.5 text-right font-black text-rose-600">{{ __('BDT') }} {{ number_format($item->due_amount, 2) }}</td>
                                        <td class="px-4 py-2.5 text-center">
                                            @if($phoneClean)
                                                <a href="https://wa.me/{{ $phoneClean }}?text={{ urlencode('Assalamu Alaikum ' . ($item->customer->name ?? '') . ', this is an update from Projukti Sheba regarding the milestone due payment of BDT ' . number_format($item->due_amount, 2) . ' for Order #' . $item->order_number . '. Thank you!') }}" target="_blank" class="inline-flex items-center gap-1 px-2.5 py-1 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-lg transition shadow-xs text-[11px]">
                                                    💬 Send Reminder
                                                </a>
                                            @else
                                                <span class="text-slate-400 italic">No WhatsApp</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-4 py-6 text-center text-slate-400 italic">No website initial dues pending! 🎉</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Tab 3: Website Monthly/Yearly Subscription Fee Due Detail Table -->
                <div x-show="activeDueTab === 'subscription_fee'" class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
                    <div class="p-4 bg-emerald-50/50 border-b border-emerald-100 flex items-center justify-between">
                        <div class="font-bold text-xs text-emerald-900 flex items-center gap-2">
                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                            Website & Software Recurring Subscription Fees (Monthly/Yearly)
                        </div>
                        <a href="{{ route('renewals.index') }}" class="text-xs font-bold text-emerald-700 hover:underline">Manage All Subscriptions &rarr;</a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-100 text-xs">
                            <thead class="bg-slate-50 font-bold text-slate-600 uppercase tracking-wider text-[11px]">
                                <tr>
                                    <th class="px-4 py-2.5 text-left">Customer & WhatsApp</th>
                                    <th class="px-4 py-2.5 text-left">Service / Plan</th>
                                    <th class="px-4 py-2.5 text-left">Billing Cycle</th>
                                    <th class="px-4 py-2.5 text-left">Next Due Date</th>
                                    <th class="px-4 py-2.5 text-right text-rose-600">Renewal Fee</th>
                                    <th class="px-4 py-2.5 text-center">Status</th>
                                    <th class="px-4 py-2.5 text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($dueAlerts['subscription_fee']['items'] as $item)
                                    @php
                                        $phoneClean = preg_replace('/[^0-9]/', '', $item->customer->phone ?? '');
                                        $isOverdue = $item->next_due_date && $item->next_due_date->isPast();
                                    @endphp
                                    <tr class="hover:bg-slate-50/70">
                                        <td class="px-4 py-2.5">
                                            <div class="font-bold text-slate-900">{{ $item->customer->name ?? 'N/A' }}</div>
                                            <div class="text-[11px] text-slate-500 font-mono">{{ $item->customer->phone ?? 'N/A' }}</div>
                                        </td>
                                        <td class="px-4 py-2.5 font-bold text-slate-800">
                                            {{ $item->service_name ?? $item->service->name ?? 'Maintenance / SaaS' }}
                                        </td>
                                        <td class="px-4 py-2.5 uppercase font-semibold text-slate-600">
                                            {{ $item->billing_cycle ?? 'Monthly' }}
                                        </td>
                                        <td class="px-4 py-2.5">
                                            <span class="font-bold {{ $isOverdue ? 'text-rose-600' : 'text-amber-600' }}">
                                                {{ $item->next_due_date ? $item->next_due_date->format('d M, Y') : 'N/A' }}
                                            </span>
                                            @if($item->next_due_date)
                                                <div class="text-[10px] text-slate-400">
                                                    {{ $item->next_due_date->diffForHumans() }}
                                                </div>
                                            @endif
                                        </td>
                                        <td class="px-4 py-2.5 text-right font-black text-rose-600">{{ __('BDT') }} {{ number_format($item->renewal_fee, 2) }}</td>
                                        <td class="px-4 py-2.5 text-center">
                                            @if($isOverdue)
                                                <span class="px-2 py-0.5 rounded text-[10px] font-black bg-rose-100 text-rose-800 uppercase">Overdue</span>
                                            @else
                                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-800 uppercase">Due Soon</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-2.5 text-center">
                                            @if($phoneClean)
                                                <a href="https://wa.me/{{ $phoneClean }}?text={{ urlencode('Assalamu Alaikum ' . ($item->customer->name ?? '') . ', this is a notice regarding your ' . ($item->billing_cycle ?? '') . ' subscription renewal fee of BDT ' . number_format($item->renewal_fee, 2) . ' due on ' . ($item->next_due_date ? $item->next_due_date->format('d M, Y') : '') . '. Thank you!') }}" target="_blank" class="inline-flex items-center gap-1 px-2.5 py-1 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-lg transition shadow-xs text-[11px]">
                                                    💬 Send Reminder
                                                </a>
                                            @else
                                                <span class="text-slate-400 italic">No WhatsApp</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-4 py-6 text-center text-slate-400 italic">No upcoming subscription dues within the next 30 days! 🎉</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif

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
