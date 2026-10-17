<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-900 leading-tight flex items-center gap-2">
                    <svg class="w-7 h-7 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    {{ __('Business Intelligence & Reports Hub') }}
                </h2>
                <p class="text-sm text-gray-500 mt-0.5">
                    {{ __('Central Executive Dashboard & Analytics') }}
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Date Range Filter & Quick Selectors -->
            <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm flex flex-col md:flex-row justify-between items-center gap-4">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="text-xs font-bold text-gray-500 uppercase tracking-wider me-2">{{ __('Filter Range:') }}</span>
                    <a href="{{ route('reports.index', ['range' => 'today']) }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold {{ $filter === 'today' ? 'bg-indigo-600 text-white shadow' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">{{ __('Today') }}</a>
                    <a href="{{ route('reports.index', ['range' => 'yesterday']) }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold {{ $filter === 'yesterday' ? 'bg-indigo-600 text-white shadow' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">{{ __('Yesterday') }}</a>
                    <a href="{{ route('reports.index', ['range' => 'last_7_days']) }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold {{ $filter === 'last_7_days' ? 'bg-indigo-600 text-white shadow' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">{{ __('Last 7 Days') }}</a>
                    <a href="{{ route('reports.index', ['range' => 'this_month']) }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold {{ $filter === 'this_month' ? 'bg-indigo-600 text-white shadow' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">{{ __('This Month') }}</a>
                    <a href="{{ route('reports.index', ['range' => 'last_month']) }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold {{ $filter === 'last_month' ? 'bg-indigo-600 text-white shadow' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">{{ __('Last Month') }}</a>
                    <a href="{{ route('reports.index', ['range' => 'this_year']) }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold {{ $filter === 'this_year' ? 'bg-indigo-600 text-white shadow' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">{{ __('This Year') }}</a>
                </div>

                <div class="flex items-center gap-3">
                    <span class="text-xs text-gray-500 font-medium bg-gray-50 px-3 py-1.5 rounded-lg border border-gray-200">
                        {{ __('Date:') }} <strong class="text-gray-800">{{ $startDate->format('d M, Y') }}</strong> {{ __('to') }} <strong class="text-gray-800">{{ $endDate->format('d M, Y') }}</strong>
                    </span>
                </div>
            </div>

            <!-- Report Navigation Module Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                <!-- Sales Report Link Card -->
                <a href="{{ route('reports.sales', ['range' => $filter]) }}" class="group bg-white p-5 rounded-2xl border border-gray-200 shadow-sm hover:border-indigo-500 hover:shadow-md transition">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-indigo-600 uppercase tracking-wider">01. Sales & CRM</span>
                        <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center group-hover:bg-indigo-600 group-hover:text-white transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                        </div>
                    </div>
                    <div class="mt-4">
                        <div class="text-2xl font-black text-gray-900">{{ __('BDT') }} {{ number_format($summary['sales']['total_sold_amount_month'], 2) }}</div>
                        <p class="text-xs text-gray-500 mt-1">{{ __('Sales this month') }} &bull; {{ $summary['sales']['total_sales_month'] }} {{ __('Orders') }}</p>
                    </div>
                    <div class="mt-4 pt-3 border-t flex items-center justify-between text-xs font-semibold text-indigo-600">
                        <span>{{ __('View Sales Report') }}</span>
                        <span>&rarr;</span>
                    </div>
                </a>

                <!-- Production Report Link Card -->
                <a href="{{ route('reports.production', ['range' => $filter]) }}" class="group bg-white p-5 rounded-2xl border border-gray-200 shadow-sm hover:border-amber-500 hover:shadow-md transition">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-amber-600 uppercase tracking-wider">02. Creative & Video</span>
                        <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center group-hover:bg-amber-600 group-hover:text-white transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                        </div>
                    </div>
                    <div class="mt-4">
                        <div class="text-2xl font-black text-gray-900">{{ number_format($summary['production']['total_seconds_edited']) }} <span class="text-xs font-semibold text-gray-500">{{ __('Seconds') }}</span></div>
                        <p class="text-xs text-gray-500 mt-1">{{ $summary['production']['completed_videos'] }} {{ __('Videos') }} &bull; {{ $summary['production']['delivered_websites'] }} {{ __('Software') }}</p>
                    </div>
                    <div class="mt-4 pt-3 border-t flex items-center justify-between text-xs font-semibold text-amber-600">
                        <span>{{ __('View Production Report') }}</span>
                        <span>&rarr;</span>
                    </div>
                </a>

                <!-- Finance Report Link Card -->
                <a href="{{ route('reports.finance', ['range' => $filter]) }}" class="group bg-white p-5 rounded-2xl border border-gray-200 shadow-sm hover:border-emerald-500 hover:shadow-md transition">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-emerald-600 uppercase tracking-wider">03. Finance & P&L</span>
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center group-hover:bg-emerald-600 group-hover:text-white transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                    </div>
                    <div class="mt-4">
                        <div class="text-2xl font-black {{ $summary['finance']['net_profit_month'] >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                            {{ $summary['finance']['net_profit_month'] >= 0 ? '+' : '' }}{{ __('BDT') }} {{ number_format($summary['finance']['net_profit_month'], 2) }}
                        </div>
                        <p class="text-xs text-gray-500 mt-1">{{ __('Net Profit') }} &bull; {{ __('Due:') }} {{ __('BDT') }} {{ number_format($summary['finance']['total_receivable_due'], 2) }}</p>
                    </div>
                    <div class="mt-4 pt-3 border-t flex items-center justify-between text-xs font-semibold text-emerald-600">
                        <span>{{ __('View Financial Statement') }}</span>
                        <span>&rarr;</span>
                    </div>
                </a>

                <!-- Support Report Link Card -->
                <a href="{{ route('reports.support', ['range' => $filter]) }}" class="group bg-white p-5 rounded-2xl border border-gray-200 shadow-sm hover:border-purple-500 hover:shadow-md transition">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-purple-600 uppercase tracking-wider">04. Support & SLA</span>
                        <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center group-hover:bg-purple-600 group-hover:text-white transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        </div>
                    </div>
                    <div class="mt-4">
                        <div class="text-2xl font-black text-gray-900">{{ $summary['support']['pending_tickets'] }} <span class="text-xs font-semibold text-gray-500">{{ __('Pending Tickets') }}</span></div>
                        <p class="text-xs text-gray-500 mt-1">{{ $summary['support']['rework_tickets'] }} {{ __('Rework') }} &bull; {{ $summary['support']['resolved_tickets_month'] }} {{ __('Resolved') }}</p>
                    </div>
                    <div class="mt-4 pt-3 border-t flex items-center justify-between text-xs font-semibold text-purple-600">
                        <span>{{ __('View Support Report') }}</span>
                        <span>&rarr;</span>
                    </div>
                </a>
            </div>

            <!-- Admin's Complete View Matrix -->
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-gray-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-gray-50/50">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-indigo-600"></span>
                            {{ __("Admin's Complete View (Company Overview at a Glance)") }}
                        </h3>
                        <p class="text-xs text-gray-500 mt-0.5">{{ __('Realtime operational & financial metrics for management') }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <button onclick="window.print()" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 shadow-sm">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                            {{ __('Print Report') }}
                        </button>
                    </div>
                </div>

                <div class="p-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Column 1: Sales Questions -->
                    <div class="bg-indigo-50/40 p-5 rounded-xl border border-indigo-100 space-y-3">
                        <h4 class="font-bold text-sm text-indigo-950 flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-indigo-600"></span>
                            Sales Condition ({{ __('Sales Status') }})
                        </h4>
                        <ul class="text-xs space-y-2.5 text-gray-700">
                            <li class="flex justify-between items-center py-1 border-b border-indigo-100/60">
                                <span>{{ __('How many new leads received today?') }}</span>
                                <strong class="text-indigo-700 bg-white px-2 py-0.5 rounded shadow-xs">{{ $summary['sales']['new_leads_today'] }}</strong>
                            </li>
                            <li class="flex justify-between items-center py-1 border-b border-indigo-100/60">
                                <span>{{ __('How many leads in follow-up?') }}</span>
                                <strong class="text-indigo-700 bg-white px-2 py-0.5 rounded shadow-xs">{{ $summary['sales']['leads_in_follow_up'] }}</strong>
                            </li>
                            <li class="flex justify-between items-center py-1 border-b border-indigo-100/60">
                                <span>{{ __('How many sales closed this month?') }}</span>
                                <strong class="text-indigo-700 bg-white px-2 py-0.5 rounded shadow-xs">{{ $summary['sales']['total_sales_month'] }} {{ __('Orders') }}</strong>
                            </li>
                            <li class="flex justify-between items-center py-1 border-b border-indigo-100/60">
                                <span>{{ __('Total sales amount closed?') }}</span>
                                <strong class="text-indigo-700 bg-white px-2 py-0.5 rounded shadow-xs">{{ __('BDT') }} {{ number_format($summary['sales']['total_sold_amount_month'], 2) }}</strong>
                            </li>
                            <li class="flex justify-between items-center py-1">
                                <span>{{ __('Total collection this month?') }}</span>
                                <strong class="text-emerald-700 bg-white px-2 py-0.5 rounded shadow-xs">{{ __('BDT') }} {{ number_format($summary['sales']['total_collected_month'], 2) }}</strong>
                            </li>
                        </ul>
                    </div>

                    <!-- Column 2: Projects & Tasks Questions -->
                    <div class="bg-amber-50/40 p-5 rounded-xl border border-amber-100 space-y-3">
                        <h4 class="font-bold text-sm text-amber-950 flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-amber-600"></span>
                            Projects & Operations ({{ __('Projects & Operations') }})
                        </h4>
                        <ul class="text-xs space-y-2.5 text-gray-700">
                            <li class="flex justify-between items-center py-1 border-b border-amber-100/60">
                                <span>{{ __('Total running projects:') }}</span>
                                <strong class="text-amber-800 bg-white px-2 py-0.5 rounded shadow-xs">{{ $summary['projects']['running_projects'] }}</strong>
                            </li>
                            <li class="flex justify-between items-center py-1 border-b border-amber-100/60">
                                <span>{{ __('Delayed projects:') }}</span>
                                <strong class="{{ $summary['projects']['delayed_projects_count'] > 0 ? 'text-rose-600 font-bold bg-rose-50' : 'text-gray-600 bg-white' }} px-2 py-0.5 rounded shadow-xs">
                                    {{ $summary['projects']['delayed_projects_count'] }}
                                </strong>
                            </li>
                            <li class="flex justify-between items-center py-1 border-b border-amber-100/60">
                                <span>{{ __('Currently in-progress tasks:') }}</span>
                                <strong class="text-amber-800 bg-white px-2 py-0.5 rounded shadow-xs">{{ $summary['projects']['tasks_in_progress'] }}</strong>
                            </li>
                            <li class="flex justify-between items-center py-1 border-b border-amber-100/60">
                                <span>{{ __('Tasks due today:') }}</span>
                                <strong class="text-amber-800 bg-white px-2 py-0.5 rounded shadow-xs">{{ $summary['projects']['tasks_due_today'] }}</strong>
                            </li>
                            <li class="flex justify-between items-center py-1">
                                <span>{{ __('Tasks due tomorrow:') }}</span>
                                <strong class="text-amber-800 bg-white px-2 py-0.5 rounded shadow-xs">{{ $summary['projects']['tasks_due_tomorrow'] }}</strong>
                            </li>
                        </ul>
                    </div>

                    <!-- Column 3: Finance & Cashflow Questions -->
                    <div class="bg-emerald-50/40 p-5 rounded-xl border border-emerald-100 space-y-3">
                        <h4 class="font-bold text-sm text-emerald-950 flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-emerald-600"></span>
                            Financial Health ({{ __('Income, Expenses & Profit') }})
                        </h4>
                        <ul class="text-xs space-y-2.5 text-gray-700">
                            <li class="flex justify-between items-center py-1 border-b border-emerald-100/60">
                                <span>{{ __('Total month income (Inflow):') }}</span>
                                <strong class="text-emerald-700 bg-white px-2 py-0.5 rounded shadow-xs">{{ __('BDT') }} {{ number_format($summary['finance']['income_month'], 2) }}</strong>
                            </li>
                            <li class="flex justify-between items-center py-1 border-b border-emerald-100/60">
                                <span>{{ __('Total month expense (Outflow):') }}</span>
                                <strong class="text-rose-700 bg-white px-2 py-0.5 rounded shadow-xs">{{ __('BDT') }} {{ number_format($summary['finance']['total_outflow_month'], 2) }}</strong>
                            </li>
                            <li class="flex justify-between items-center py-1 border-b border-emerald-100/60">
                                <span>{{ __('Month net profit (Net Profit):') }}</span>
                                <strong class="{{ $summary['finance']['net_profit_month'] >= 0 ? 'text-emerald-700' : 'text-rose-700' }} bg-white px-2 py-0.5 rounded shadow-xs font-bold">
                                    {{ __('BDT') }} {{ number_format($summary['finance']['net_profit_month'], 2) }}
                                </strong>
                            </li>
                            <li class="flex justify-between items-center py-1 border-b border-emerald-100/60">
                                <span>{{ __('Total client receivable (Due):') }}</span>
                                <strong class="text-amber-700 bg-white px-2 py-0.5 rounded shadow-xs">{{ __('BDT') }} {{ number_format($summary['finance']['total_receivable_due'], 2) }}</strong>
                            </li>
                            <li class="flex justify-between items-center py-1">
                                <span>{{ __('Overdue service renewals:') }}</span>
                                <strong class="{{ $summary['finance']['overdue_renewals_count'] > 0 ? 'text-rose-600' : 'text-gray-600' }} bg-white px-2 py-0.5 rounded shadow-xs">
                                    {{ $summary['finance']['overdue_renewals_count'] }}
                                </strong>
                            </li>
                        </ul>
                    </div>

                    <!-- Column 4: Production & Creative -->
                    <div class="bg-blue-50/40 p-5 rounded-xl border border-blue-100 space-y-3">
                        <h4 class="font-bold text-sm text-blue-950 flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-blue-600"></span>
                            Creative Production ({{ __('Video & Delivery') }})
                        </h4>
                        <ul class="text-xs space-y-2.5 text-gray-700">
                            <li class="flex justify-between items-center py-1 border-b border-blue-100/60">
                                <span>{{ __('Total edited videos:') }}</span>
                                <strong class="text-blue-700 bg-white px-2 py-0.5 rounded shadow-xs">{{ $summary['production']['completed_videos'] }}</strong>
                            </li>
                            <li class="flex justify-between items-center py-1 border-b border-blue-100/60">
                                <span>{{ __('Total seconds edited:') }}</span>
                                <strong class="text-blue-700 bg-white px-2 py-0.5 rounded shadow-xs">{{ number_format($summary['production']['total_seconds_edited']) }} {{ __('Seconds') }}</strong>
                            </li>
                            <li class="flex justify-between items-center py-1 border-b border-blue-100/60">
                                <span>{{ __('Delivered websites:') }}</span>
                                <strong class="text-blue-700 bg-white px-2 py-0.5 rounded shadow-xs">{{ $summary['production']['delivered_websites'] }}</strong>
                            </li>
                            <li class="flex justify-between items-center py-1 border-b border-blue-100/60">
                                <span>{{ __('Tasks completed this month:') }}</span>
                                <strong class="text-blue-700 bg-white px-2 py-0.5 rounded shadow-xs">{{ $summary['production']['completed_tasks_month'] }}</strong>
                            </li>
                            <li class="flex justify-between items-center py-1">
                                <span>{{ __('Total pending work items:') }}</span>
                                <strong class="text-amber-700 bg-white px-2 py-0.5 rounded shadow-xs">{{ $summary['production']['pending_work_count'] }}</strong>
                            </li>
                        </ul>
                    </div>

                    <!-- Column 5: Support & Customer Satisfaction -->
                    <div class="bg-purple-50/40 p-5 rounded-xl border border-purple-100 space-y-3">
                        <h4 class="font-bold text-sm text-purple-950 flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-purple-600"></span>
                            Support & Helpdesk ({{ __('Customer Support') }})
                        </h4>
                        <ul class="text-xs space-y-2.5 text-gray-700">
                            <li class="flex justify-between items-center py-1 border-b border-purple-100/60">
                                <span>{{ __('Pending or active support tickets:') }}</span>
                                <strong class="text-purple-700 bg-white px-2 py-0.5 rounded shadow-xs">{{ $summary['support']['pending_tickets'] }}</strong>
                            </li>
                            <li class="flex justify-between items-center py-1 border-b border-purple-100/60">
                                <span>{{ __('Rework tickets:') }}</span>
                                <strong class="{{ $summary['support']['rework_tickets'] > 0 ? 'text-rose-600' : 'text-gray-600' }} bg-white px-2 py-0.5 rounded shadow-xs">
                                    {{ $summary['support']['rework_tickets'] }}
                                </strong>
                            </li>
                            <li class="flex justify-between items-center py-1 border-b border-purple-100/60">
                                <span>{{ __('Tickets resolved this month:') }}</span>
                                <strong class="text-emerald-700 bg-white px-2 py-0.5 rounded shadow-xs">{{ $summary['support']['resolved_tickets_month'] }}</strong>
                            </li>
                            <li class="flex justify-between items-center py-1">
                                <span>{{ __('Urgent (High/Critical) tickets:') }}</span>
                                <strong class="{{ count($summary['support']['urgent_tickets']) > 0 ? 'text-rose-600 font-bold' : 'text-emerald-600' }} bg-white px-2 py-0.5 rounded shadow-xs">
                                    {{ count($summary['support']['urgent_tickets']) }}
                                </strong>
                            </li>
                        </ul>
                    </div>

                    <!-- Column 6: Quick Data Export Center -->
                    <div class="bg-gray-100/70 p-5 rounded-xl border border-gray-200 space-y-3">
                        <h4 class="font-bold text-sm text-gray-900 flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                            Export Center ({{ __('Excel & CSV Download') }})
                        </h4>
                        <p class="text-xs text-gray-500">{{ __('Export current range data directly to Microsoft Excel-compatible CSV:') }}</p>
                        <div class="grid grid-cols-2 gap-2 pt-1">
                            <a href="{{ route('reports.export', ['type' => 'sales', 'range' => $filter]) }}" class="px-3 py-2 bg-white hover:bg-gray-50 text-indigo-700 text-xs font-semibold rounded-lg border border-gray-200 text-center shadow-xs">
                                &darr; Sales Leads
                            </a>
                            <a href="{{ route('reports.export', ['type' => 'video_production', 'range' => $filter]) }}" class="px-3 py-2 bg-white hover:bg-gray-50 text-amber-700 text-xs font-semibold rounded-lg border border-gray-200 text-center shadow-xs">
                                &darr; Video Billings
                            </a>
                            <a href="{{ route('reports.export', ['type' => 'finance_inflows', 'range' => $filter]) }}" class="px-3 py-2 bg-white hover:bg-gray-50 text-emerald-700 text-xs font-semibold rounded-lg border border-gray-200 text-center shadow-xs">
                                &darr; Cash Inflows
                            </a>
                            <a href="{{ route('reports.export', ['type' => 'finance_expenses', 'range' => $filter]) }}" class="px-3 py-2 bg-white hover:bg-gray-50 text-rose-700 text-xs font-semibold rounded-lg border border-gray-200 text-center shadow-xs">
                                &darr; Expenses
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Delayed Projects Warning if any -->
            @if($summary['projects']['delayed_projects_count'] > 0)
                <div class="bg-rose-50 border border-rose-200 rounded-2xl p-5 shadow-xs">
                    <div class="flex items-center gap-2 text-rose-900 font-bold text-sm mb-3">
                        <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        {{ __('Warning: Delayed Projects with Missed Deadlines') }}
                    </div>
                    <div class="divide-y divide-rose-200/60">
                        @foreach($summary['projects']['delayed_projects'] as $dp)
                            <div class="py-2.5 flex flex-col sm:flex-row justify-between items-start sm:items-center text-xs gap-2">
                                <div>
                                    <span class="font-bold text-gray-900">{{ $dp->project_code }} - {{ $dp->name }}</span>
                                    <span class="text-gray-500">({{ $dp->customer->name ?? 'Customer' }})</span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <span class="text-rose-700 font-semibold">{{ __('Deadline was:') }} {{ $dp->deadline ? \Carbon\Carbon::parse($dp->deadline)->format('d M, Y') : '-' }}</span>
                                    <a href="{{ route('projects.show', $dp->id) }}" class="text-indigo-600 hover:underline font-semibold">{{ __('View Project') }} &rarr;</a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
