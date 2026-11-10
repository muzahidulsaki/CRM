<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight flex items-center gap-2">
                    <svg class="w-7 h-7 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    {{ __('Lead Management') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">{{ __('Spreadsheet aligned lead database, follow-ups, and order history') }}</p>
            </div>
            <div class="flex items-center gap-2.5">
                <a href="{{ route('leads.pipeline') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 transition">
                    <svg class="w-4 h-4 me-1.5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/></svg>
                    {{ __('Pipeline (Kanban)') }}
                </a>
                <a href="{{ route('leads.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 shadow-sm transition">
                    <svg class="w-4 h-4 me-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    {{ __('New Lead Entry') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="w-full space-y-5">

            <!-- Quick Metrics Grid -->
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
                <a href="{{ route('leads.index') }}" class="bg-white p-3.5 rounded-xl border border-gray-200 shadow-sm hover:border-indigo-400 transition text-center {{ !request()->hasAny(['status', 'temperature', 'follow_up', 'today_followup', 'today_meeting', 'why_not_order', 'existing_order']) ? 'ring-2 ring-indigo-500 bg-indigo-50/20' : '' }}">
                    <div class="text-xs font-medium text-gray-500">{{ __('Total Leads') }}</div>
                    <div class="text-xl font-bold text-gray-900 mt-1">{{ $metrics['total'] }}</div>
                </a>

                <a href="{{ route('leads.index', ['follow_up' => 'overdue']) }}" class="bg-rose-50/70 p-3.5 rounded-xl border border-rose-200 shadow-sm hover:border-rose-400 transition text-center {{ request('follow_up') == 'overdue' || request('today_followup') == 'overdue' ? 'ring-2 ring-rose-500 bg-rose-100/50' : '' }}">
                    <div class="text-xs font-semibold text-rose-800 flex items-center justify-center gap-1">
                        <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                        {{ __('Prev. Follow-up') }}
                    </div>
                    <div class="text-xl font-bold text-rose-900 mt-1">{{ $metrics['overdue_followups'] }}</div>
                </a>

                <a href="{{ route('leads.index', ['today_followup' => 1]) }}" class="bg-amber-50/70 p-3.5 rounded-xl border border-amber-200 shadow-sm hover:border-amber-400 transition text-center {{ request('today_followup') == '1' || request('follow_up') == 'today' ? 'ring-2 ring-amber-500 bg-amber-100/50' : '' }}">
                    <div class="text-xs font-semibold text-amber-800 flex items-center justify-center gap-1">
                        <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                        {{ __('Today Follow-up') }}
                    </div>
                    <div class="text-xl font-bold text-amber-900 mt-1">{{ $metrics['today_followups'] }}</div>
                </a>

                <a href="{{ route('leads.index', ['today_meeting' => 1]) }}" class="bg-purple-50/70 p-3.5 rounded-xl border border-purple-200 shadow-sm hover:border-purple-400 transition text-center {{ request('today_meeting') ? 'ring-2 ring-purple-500 bg-purple-100/50' : '' }}">
                    <div class="text-xs font-semibold text-purple-800 flex items-center justify-center gap-1">
                        <span class="w-2 h-2 rounded-full bg-purple-500"></span>
                        {{ __('Today Meeting') }}
                    </div>
                    <div class="text-xl font-bold text-purple-900 mt-1">
                        {{ \App\Models\Lead::whereDate('next_meeting_datetime', now()->format('Y-m-d'))->count() }}
                    </div>
                </a>

                <a href="{{ route('leads.index', ['temperature' => 'Will Pay']) }}" class="bg-emerald-50/70 p-3.5 rounded-xl border border-emerald-200 shadow-sm hover:border-emerald-400 transition text-center {{ request('temperature') == 'Will Pay' ? 'ring-2 ring-emerald-500 bg-emerald-100/50' : '' }}">
                    <div class="text-xs font-semibold text-emerald-800 flex items-center justify-center gap-1">
                        <span>💰</span>
                        {{ __('Will Pay') }}
                    </div>
                    <div class="text-xl font-bold text-emerald-900 mt-1">{{ $metrics['will_pay'] }}</div>
                </a>

                <a href="{{ route('leads.index', ['temperature' => 'Hot']) }}" class="bg-rose-50/60 p-3.5 rounded-xl border border-rose-200 shadow-sm hover:border-rose-400 transition text-center {{ request('temperature') == 'Hot' ? 'ring-2 ring-rose-500 bg-rose-100/50' : '' }}">
                    <div class="text-xs font-semibold text-rose-800 flex items-center justify-center gap-1">
                        <span>🔥</span>
                        {{ __('Hot Leads') }}
                    </div>
                    <div class="text-xl font-bold text-rose-900 mt-1">{{ $metrics['hot'] }}</div>
                </a>
            </div>

            <!-- Filter & Search Bar with New Filters -->
            <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm space-y-4">
                <form method="GET" action="{{ route('leads.index') }}">
                    <input type="hidden" name="per_page" value="{{ request('per_page', 15) }}">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
                        <!-- Search input -->
                        <div class="lg:col-span-2">
                            <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">{{ __('Search (Name, Phone, WhatsApp, Code, Token)') }}</label>
                            <div class="relative">
                                <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('Search PS10001, phone, name, email...') }}" class="w-full text-sm border-gray-300 rounded-lg pl-9 focus:ring-indigo-500 focus:border-indigo-500">
                                <svg class="w-4 h-4 text-gray-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            </div>
                        </div>

                        <!-- Business Category Filter -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">{{ __('Business Category') }}</label>
                            <select name="business_type" class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">{{ __('All Categories') }}</option>
                                @foreach($businessCategories as $cat)
                                    <option value="{{ $cat }}" {{ (request('business_type') == $cat || request('business_category') == $cat) ? 'selected' : '' }}>{{ $cat }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Status -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">{{ __('Lead Status') }}</label>
                            <select name="status" class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">{{ __('All Statuses') }}</option>
                                @foreach(['New', 'Contacted', 'Interested', 'Meeting/Demo', 'Proposal Sent', 'Negotiation', 'Won', 'Lost'] as $st)
                                    <option value="{{ $st }}" {{ request('status') == $st ? 'selected' : '' }}>{{ __($st) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Temperature -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">{{ __('Temperature') }}</label>
                            <select name="temperature" class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">{{ __('All Temperatures') }}</option>
                                <option value="Will Pay" {{ request('temperature') == 'Will Pay' ? 'selected' : '' }}>💰 Will Pay</option>
                                <option value="Hot" {{ request('temperature') == 'Hot' ? 'selected' : '' }}>🔥 Hot</option>
                                <option value="Warm" {{ request('temperature') == 'Warm' ? 'selected' : '' }}>⚡ Warm</option>
                                <option value="Cold" {{ request('temperature') == 'Cold' ? 'selected' : '' }}>❄️ Cold</option>
                            </select>
                        </div>
                    </div>

                    <!-- Second Row: Dedicated 4 New Requested Filters -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 pt-3 border-t border-gray-100 mt-3">
                        <!-- 1. Follow up Today Date Filter -->
                        <div>
                            <label class="block text-xs font-bold text-amber-800 uppercase mb-1 flex items-center gap-1">
                                📅 {{ __('Follow up Today Date') }}
                            </label>
                            <select name="today_followup" class="w-full text-sm border-amber-300 bg-amber-50/40 rounded-lg focus:ring-amber-500 focus:border-amber-500">
                                <option value="">{{ __('All Follow-up Dates') }}</option>
                                <option value="1" {{ request('today_followup') == '1' || request('follow_up') == 'today' ? 'selected' : '' }}>🔔 {{ __('Follow-up Due Today') }}</option>
                                <option value="overdue" {{ request('today_followup') == 'overdue' || request('follow_up') == 'overdue' ? 'selected' : '' }}>⏳ {{ __('Previous / Overdue Follow-up') }}</option>
                            </select>
                        </div>

                        <!-- 2. Today Meeting Date Filter -->
                        <div>
                            <label class="block text-xs font-bold text-purple-800 uppercase mb-1 flex items-center gap-1">
                                ⏰ {{ __('Today Meeting Date') }}
                            </label>
                            <div class="flex items-center gap-1.5">
                                <select name="today_meeting" class="w-full text-sm border-purple-300 bg-purple-50/40 rounded-lg focus:ring-purple-500 focus:border-purple-500">
                                    <option value="">{{ __('All Meeting Dates') }}</option>
                                    <option value="1" {{ request('today_meeting') == '1' ? 'selected' : '' }}>🤝 {{ __('Meetings Scheduled Today') }}</option>
                                </select>
                            </div>
                        </div>

                        <!-- 3. Why Not Order Filter -->
                        <div>
                            <label class="block text-xs font-bold text-rose-800 uppercase mb-1 flex items-center gap-1">
                                ❓ {{ __('Why Not Order') }}
                            </label>
                            <select name="why_not_order" class="w-full text-sm border-rose-300 bg-rose-50/30 rounded-lg focus:ring-rose-500 focus:border-rose-500">
                                <option value="">{{ __('All Reasons') }}</option>
                                @foreach($whyNotOrderOptions as $reason)
                                    <option value="{{ $reason }}" {{ request('why_not_order') == $reason ? 'selected' : '' }}>{{ $reason }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- 4. Existing Order Filter -->
                        <div>
                            <label class="block text-xs font-bold text-emerald-800 uppercase mb-1 flex items-center gap-1">
                                📦 {{ __('Existing Order Filter') }}
                            </label>
                            <select name="existing_order" class="w-full text-sm border-emerald-300 bg-emerald-50/40 rounded-lg focus:ring-emerald-500 focus:border-emerald-500">
                                <option value="">{{ __('All Leads (With or Without Orders)') }}</option>
                                <option value="has_orders" {{ request('existing_order') == 'has_orders' ? 'selected' : '' }}>✅ {{ __('Has Existing Orders (Any)') }}</option>
                                <option value="erp" {{ request('existing_order') == 'erp' ? 'selected' : '' }}>💻 {{ __('Has ERP / Software Orders') }}</option>
                                <option value="website" {{ request('existing_order') == 'website' ? 'selected' : '' }}>🌐 {{ __('Has Website Orders') }}</option>
                                <option value="video" {{ request('existing_order') == 'video' ? 'selected' : '' }}>🎬 {{ __('Has Video Editing Orders') }}</option>
                                <option value="graphics" {{ request('existing_order') == 'graphics' ? 'selected' : '' }}>🎨 {{ __('Has Graphics Orders') }}</option>
                                <option value="smm" {{ request('existing_order') == 'smm' ? 'selected' : '' }}>📱 {{ __('Has SMM Orders') }}</option>
                                <option value="ads" {{ request('existing_order') == 'ads' ? 'selected' : '' }}>📈 {{ __('Has Ads Orders') }}</option>
                                <option value="no_orders" {{ request('existing_order') == 'no_orders' ? 'selected' : '' }}>❌ {{ __('No Prior Orders (Fresh Prospect)') }}</option>
                            </select>
                        </div>
                    </div>

                    <!-- Action buttons -->
                    <div class="flex items-center justify-end gap-2 pt-3">
                        <a href="{{ route('leads.index') }}" class="px-4 py-2 bg-gray-100 text-gray-600 rounded-lg text-xs font-semibold hover:bg-gray-200 transition">
                            {{ __('Reset Filters') }}
                        </a>
                        <button type="submit" class="px-5 py-2 bg-indigo-600 text-white rounded-lg text-xs font-bold uppercase tracking-wider hover:bg-indigo-700 transition flex items-center gap-1.5 shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                            {{ __('Apply Filters') }}
                        </button>
                    </div>
                </form>
            </div>

            <!-- Leads Table Matching Excel Columns & Existing Order Count -->
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                <!-- Table Top Header: Total Count & Per Page Selector (10 / 50 / 100 / All) -->
                <div class="px-5 py-3 border-b border-gray-100 flex flex-wrap items-center justify-between gap-3 bg-gray-50/70">
                    <div class="flex items-center gap-2 text-xs text-gray-600">
                        <span class="font-semibold">{{ __('Total Found:') }}</span>
                        <strong class="font-mono font-bold text-indigo-700 bg-indigo-50 px-2 py-0.5 rounded border border-indigo-100">
                            {{ $leads->total() }} {{ __('Leads') }}
                        </strong>
                        <span class="text-gray-400">|</span>
                        <span>
                            @if(request('per_page') === 'all')
                                {{ __('Showing all :total results on one page', ['total' => $leads->total()]) }}
                            @else
                                {{ __('Showing') }} <strong class="font-mono text-gray-900">{{ $leads->firstItem() ?? 0 }} - {{ $leads->lastItem() ?? 0 }}</strong> {{ __('of') }} <strong class="font-mono text-gray-900">{{ $leads->total() }}</strong>
                            @endif
                        </span>
                    </div>

                    <!-- Per Page Switcher (10 / 50 / 100 / All) -->
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-bold text-gray-600 uppercase tracking-wider">{{ __('Show:') }}</span>
                        <div class="inline-flex rounded-lg border border-gray-200 bg-white p-0.5 shadow-2xs">
                            @foreach([10, 50, 100, 'all'] as $opt)
                                @php
                                    $currentPerPage = request('per_page', '15');
                                    $isActive = ($currentPerPage == (string)$opt);
                                @endphp
                                <a href="{{ request()->fullUrlWithQuery(['per_page' => $opt, 'page' => 1]) }}"
                                   class="px-3 py-1 text-xs font-bold rounded-md transition {{ $isActive ? 'bg-indigo-600 text-white shadow-xs' : 'text-gray-600 hover:text-indigo-600 hover:bg-gray-100' }}">
                                    {{ $opt === 'all' ? __('All') : $opt }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="leads-table-container" style="max-height: 580px; min-height: 300px; overflow: auto; position: relative;">
                    <table class="text-left text-xs border-separate border-spacing-0" style="min-width: 1450px; width: 100%;">
                        <thead class="text-gray-700 font-bold uppercase tracking-wider" style="position: sticky; top: 0; z-index: 20;">
                            <tr>
                                <th class="py-2.5 px-2.5 whitespace-nowrap text-center w-12 text-[11px]">{{ __('Actions') }}</th>
                                <th class="py-2.5 px-2.5 whitespace-nowrap text-[11px] leading-tight">
                                    <div>{{ __('Lead ID') }}</div>
                                    <div class="text-[10px] text-gray-500 font-semibold uppercase">/ Token</div>
                                </th>
                                <th class="py-2.5 px-2.5 whitespace-nowrap text-[11px] leading-tight">
                                    <div>{{ __('WhatsApp') }}</div>
                                    <div class="text-[10px] text-gray-500 font-semibold uppercase">& Phone</div>
                                </th>
                                <th class="py-2.5 px-2.5 whitespace-nowrap text-[11px] leading-tight">
                                    <div>{{ __('Status') }}</div>
                                    <div class="text-[10px] text-gray-500 font-semibold uppercase">/ Why Not</div>
                                </th>
                                <th class="py-2.5 px-2.5 whitespace-nowrap text-[11px] leading-tight">
                                    <div>{{ __('Product') }}</div>
                                    <div class="text-[10px] text-gray-500 font-semibold uppercase">& Temp</div>
                                </th>
                                <th class="py-2.5 px-2.5 whitespace-nowrap text-[11px] leading-tight">
                                    <div>{{ __('Follow Up') }}</div>
                                    <div class="text-[10px] text-gray-500 font-semibold uppercase">Status</div>
                                </th>
                                <th class="py-2.5 px-2.5 whitespace-nowrap text-[11px] leading-tight">
                                    <div>{{ __('Follow Up') }}</div>
                                    <div class="text-[10px] text-gray-500 font-semibold uppercase">1st Date</div>
                                </th>
                                <th class="py-2.5 px-2.5 whitespace-nowrap text-[11px] leading-tight">
                                    <div>{{ __('Follow Up') }}</div>
                                    <div class="text-[10px] text-gray-500 font-semibold uppercase">Last Date</div>
                                </th>
                                <th class="py-2.5 px-2.5 whitespace-nowrap text-[11px] leading-tight">
                                    <div>{{ __('Follow Up') }}</div>
                                    <div class="text-[10px] text-gray-500 font-semibold uppercase">Next Date</div>
                                </th>
                                <th class="py-2.5 px-2.5 whitespace-nowrap text-[11px] leading-tight bg-indigo-sticky text-indigo-950 border-x border-indigo-200">
                                    <div>{{ __('Existing Order') }}</div>
                                    <div class="text-[10px] text-indigo-800 font-semibold uppercase">Count</div>
                                </th>
                                <th class="py-2.5 px-2.5 whitespace-nowrap text-[11px] leading-tight">
                                    <div>{{ __('Interested') }}</div>
                                    <div class="text-[10px] text-gray-500 font-semibold uppercase">Product</div>
                                </th>
                                <th class="py-2.5 px-2.5 whitespace-nowrap text-[11px] leading-tight">
                                    <div>{{ __('Potential') }}</div>
                                    <div class="text-[10px] text-gray-500 font-semibold uppercase">Order</div>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($leads as $lead)
                                @php
                                    $orderCounts = $lead->existing_order_counts;
                                @endphp
                                <tr class="hover:bg-indigo-50/20 transition">
                                    <!-- 1. Actions (Three Dots Menu) -->
                                    <td class="py-2.5 px-2.5 whitespace-nowrap text-center">
                                        <div class="relative inline-block text-left" x-data="{ open: false }" @click.outside="open = false">
                                            <button @click="open = !open" type="button" class="p-1.5 text-gray-500 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition focus:outline-none" title="{{ __('Actions') }}">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                                    <circle cx="12" cy="5" r="1.75" />
                                                    <circle cx="12" cy="12" r="1.75" />
                                                    <circle cx="12" cy="19" r="1.75" />
                                                </svg>
                                            </button>

                                            <!-- Dropdown Menu -->
                                            <div x-show="open"
                                                 x-cloak
                                                 x-transition:enter="transition ease-out duration-100"
                                                 x-transition:enter-start="transform opacity-0 scale-95"
                                                 x-transition:enter-end="transform opacity-100 scale-100"
                                                 x-transition:leave="transition ease-in duration-75"
                                                 x-transition:leave-start="transform opacity-100 scale-100"
                                                 x-transition:leave-end="transform opacity-0 scale-95"
                                                 class="absolute left-0 z-50 mt-1 w-48 origin-top-left rounded-xl bg-white p-1.5 shadow-xl ring-1 ring-slate-900/10 focus:outline-none text-left"
                                                 style="display: none;">
                                                
                                                <a href="{{ route('leads.show', array_filter(['lead' => $lead, 'return_to' => request()->fullUrl()])) }}" class="flex items-center gap-2 px-3 py-2 text-xs font-semibold text-gray-700 hover:bg-indigo-50 hover:text-indigo-700 rounded-lg transition">
                                                    <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                    <span>{{ __('View Details') }}</span>
                                                </a>

                                                <a href="{{ route('leads.edit', array_filter(['lead' => $lead, 'return_to' => request()->fullUrl()])) }}" class="flex items-center gap-2 px-3 py-2 text-xs font-semibold text-gray-700 hover:bg-amber-50 hover:text-amber-700 rounded-lg transition">
                                                    <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                    <span>{{ __('Edit Lead') }}</span>
                                                </a>

                                                @if(!$lead->customer_id && $lead->lead_status !== 'Lost')
                                                    <form method="POST" action="{{ route('leads.convert', $lead) }}">
                                                        @csrf
                                                        <button type="submit" class="w-full flex items-center gap-2 px-3 py-2 text-xs font-semibold text-emerald-700 hover:bg-emerald-50 rounded-lg transition text-left" onclick="return confirm('{{ __('Convert this lead to a customer?') }}');">
                                                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                                                            <span>{{ __('Convert to Customer') }}</span>
                                                        </button>
                                                    </form>
                                                @endif

                                                <div class="my-1 border-t border-gray-100"></div>

                                                <form method="POST" action="{{ route('leads.destroy', $lead) }}" onsubmit="return confirm('{{ __('Are you sure you want to delete this lead?') }}');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="w-full flex items-center gap-2 px-3 py-2 text-xs font-semibold text-rose-600 hover:bg-rose-50 rounded-lg transition text-left">
                                                        <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                        <span>{{ __('Delete') }}</span>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- 2. Lead ID / Token (Stacked) -->
                                    <td class="py-2.5 px-2.5 whitespace-nowrap">
                                        <a href="{{ route('leads.show', array_filter(['lead' => $lead, 'return_to' => request()->fullUrl()])) }}" class="font-bold font-mono text-indigo-600 hover:text-indigo-800 hover:underline text-xs">
                                            {{ $lead->lead_code }}
                                        </a>
                                        @if(!empty($lead->name) && $lead->name !== '-')
                                            <div class="text-[11px] font-semibold text-gray-900 truncate max-w-[130px]" title="{{ $lead->name }}">{{ $lead->name }}</div>
                                        @elseif(!empty($lead->company_name))
                                            <div class="text-[11px] font-semibold text-gray-900 truncate max-w-[130px]" title="{{ $lead->company_name }}">{{ $lead->company_name }}</div>
                                        @endif
                                        @if($lead->token_id)
                                            <div class="text-[10px] text-gray-500 font-mono">{{ $lead->token_id }}</div>
                                        @endif
                                    </td>

                                    <!-- 3. WhatsApp & Phone (Stacked) -->
                                    <td class="py-2.5 px-2.5 whitespace-nowrap">
                                        @if($lead->whatsapp)
                                            <div class="flex items-center gap-1 text-[11px] text-emerald-700">
                                                <a href="https://wa.me/{{ $lead->normalized_whatsapp ?? preg_replace('/\D/', '', $lead->whatsapp) }}" target="_blank" class="hover:scale-110 transition shrink-0" title="{{ __('Open in WhatsApp') }}">
                                                    <span>💬</span>
                                                </a>
                                                <button type="button" 
                                                        onclick="copyLeadText('{{ $lead->whatsapp }}', this)" 
                                                        class="group inline-flex items-center gap-1 font-mono hover:text-emerald-900 transition cursor-pointer text-left focus:outline-none" 
                                                        title="{{ __('Click to copy: ') }}{{ $lead->whatsapp }}">
                                                    <span class="copy-target-text group-hover:underline">{{ $lead->whatsapp }}</span>
                                                    <svg class="w-2.5 h-2.5 text-emerald-500 opacity-0 group-hover:opacity-100 transition shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                                </button>
                                                @if($lead->whatsapp_status)
                                                    <span class="px-1 py-0.2 rounded text-[9px] bg-emerald-100 text-emerald-800 font-semibold uppercase">
                                                        {{ $lead->whatsapp_status }}
                                                    </span>
                                                @endif
                                            </div>
                                        @endif
                                        @if($lead->phone)
                                            <div class="flex items-center gap-1 font-medium text-gray-800 text-[11px] mt-0.5">
                                                <span>📞</span>
                                                <button type="button" 
                                                        onclick="copyLeadText('{{ $lead->phone }}', this)" 
                                                        class="group inline-flex items-center gap-1 text-gray-800 hover:text-indigo-600 transition cursor-pointer text-left focus:outline-none" 
                                                        title="{{ __('Click to copy: ') }}{{ $lead->phone }}">
                                                    <span class="copy-target-text group-hover:underline">{{ $lead->phone }}</span>
                                                    <svg class="w-2.5 h-2.5 text-gray-400 group-hover:text-indigo-600 opacity-0 group-hover:opacity-100 transition shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                                </button>
                                            </div>
                                        @endif
                                        @if(!$lead->whatsapp && !$lead->phone)
                                            <span class="text-gray-400 text-2xs">-</span>
                                        @endif
                                    </td>

                                    <!-- 4. Status / Why Not (Stacked) -->
                                    <td class="py-2.5 px-2.5 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold {{ $lead->status_badge_class }}">
                                            {{ __($lead->lead_status) }}
                                        </span>
                                        @if($lead->why_not_order)
                                            <div class="text-2xs font-semibold text-rose-600 mt-0.5 max-w-[130px] truncate" title="{{ $lead->why_not_order }}">
                                                🚫 {{ $lead->why_not_order }}
                                            </div>
                                        @endif
                                    </td>

                                    <!-- 5. Product & Temp (Stacked) -->
                                    <td class="py-2.5 px-2.5 whitespace-nowrap">
                                        <div class="font-medium text-gray-900 truncate max-w-[130px] text-[11px]" title="{{ $lead->interested_products ?? $lead->interested_service }}">
                                            {{ $lead->interested_products ?? $lead->interested_service ?? '-' }}
                                        </div>
                                        <span class="inline-flex items-center px-1.5 py-0.2 rounded-full text-[10px] font-semibold border mt-0.5 {{ $lead->temperature_badge_class }}">
                                            {{ $lead->lead_temperature }}
                                        </span>
                                    </td>

                                    <!-- 6. Follow Up Status -->
                                    <td class="py-2.5 px-2.5 whitespace-nowrap">
                                        @php
                                            $fuNextDate = $lead->followup_next_date ?? $lead->next_followup_date;
                                            $fuIsToday = $fuNextDate && \Carbon\Carbon::parse($fuNextDate)->isToday();
                                            $fuIsOverdue = $fuNextDate && \Carbon\Carbon::parse($fuNextDate)->isPast() && !$fuIsToday && !in_array($lead->lead_status, ['Won', 'Lost']);
                                        @endphp
                                        @if($fuIsOverdue)
                                            <span class="inline-flex items-center px-1.5 py-0.2 rounded text-[10px] font-bold bg-rose-100 text-rose-700">⚠️ Overdue</span>
                                        @elseif($fuIsToday)
                                            <span class="inline-flex items-center px-1.5 py-0.2 rounded text-[10px] font-bold bg-amber-100 text-amber-800">⏳ Today</span>
                                        @elseif($fuNextDate)
                                            <span class="inline-flex items-center px-1.5 py-0.2 rounded text-[10px] font-semibold bg-blue-100 text-blue-700">Scheduled</span>
                                        @else
                                            <span class="text-gray-400 text-2xs">-</span>
                                        @endif
                                        @if($lead->followup_comment)
                                            <div class="text-[10px] text-gray-600 truncate max-w-[140px] mt-0.5" title="{{ $lead->followup_comment }}">
                                                "{{ $lead->followup_comment }}"
                                            </div>
                                        @endif
                                    </td>

                                    <!-- 7. Follow up 1st Date -->
                                    <td class="py-2.5 px-2.5 whitespace-nowrap text-[11px] font-mono text-gray-800">
                                        @if($lead->followup_first_date)
                                            <div>📅 {{ \Carbon\Carbon::parse($lead->followup_first_date)->format('d M, Y') }}</div>
                                        @else
                                            <span class="text-gray-400 text-2xs">-</span>
                                        @endif
                                    </td>

                                    <!-- 8. Follow up last date -->
                                    <td class="py-2.5 px-2.5 whitespace-nowrap text-[11px] font-mono text-gray-800">
                                        @if($lead->followup_last_date)
                                            <div>📅 {{ \Carbon\Carbon::parse($lead->followup_last_date)->format('d M, Y') }}</div>
                                        @else
                                            <span class="text-gray-400 text-2xs">-</span>
                                        @endif
                                    </td>

                                    <!-- 9. Follow up next date -->
                                    <td class="py-2.5 px-2.5 whitespace-nowrap text-[11px] font-mono">
                                        @php
                                            $nDate = $lead->followup_next_date ?? $lead->next_followup_date;
                                            $isToday = $nDate && \Carbon\Carbon::parse($nDate)->isToday();
                                            $isOverdue = $nDate && \Carbon\Carbon::parse($nDate)->isPast() && !$isToday && !in_array($lead->lead_status, ['Won', 'Lost']);
                                        @endphp
                                        @if($nDate)
                                            <div class="font-bold {{ $isOverdue ? 'text-rose-600' : ($isToday ? 'text-amber-700' : 'text-gray-800') }}">
                                                📅 {{ \Carbon\Carbon::parse($nDate)->format('d M, Y') }}
                                            </div>
                                        @else
                                            <span class="text-gray-400 text-2xs">-</span>
                                        @endif
                                    </td>

                                    <!-- 10. Existing Order Count (Categorized Breakdown) -->
                                    <td class="py-2.5 px-2.5 bg-indigo-50/30 border-x border-indigo-100 min-w-[190px]">
                                        @if($orderCounts['total'] > 0)
                                            <div class="font-bold text-indigo-900 text-[11px] mb-0.5 flex items-center gap-1">
                                                <span>📦 Total: {{ $orderCounts['total'] }}</span>
                                            </div>
                                            <div class="flex flex-wrap gap-1">
                                                @if($orderCounts['erp'] > 0)
                                                    <span class="px-1.5 py-0.2 rounded text-[10px] bg-indigo-100 text-indigo-800 font-bold" title="Software / ERP Orders">
                                                        ERP: {{ $orderCounts['erp'] }}
                                                    </span>
                                                @endif
                                                @if($orderCounts['website'] > 0)
                                                    <span class="px-1.5 py-0.2 rounded text-[10px] bg-blue-100 text-blue-800 font-bold" title="Website Orders">
                                                        Web: {{ $orderCounts['website'] }}
                                                    </span>
                                                @endif
                                                @if($orderCounts['video'] > 0)
                                                    <span class="px-1.5 py-0.2 rounded text-[10px] bg-purple-100 text-purple-800 font-bold" title="Video Editing Orders">
                                                        Video: {{ $orderCounts['video'] }}
                                                    </span>
                                                @endif
                                                @if($orderCounts['graphics'] > 0)
                                                    <span class="px-1.5 py-0.2 rounded text-[10px] bg-pink-100 text-pink-800 font-bold" title="Graphics Design Orders">
                                                        Design: {{ $orderCounts['graphics'] }}
                                                    </span>
                                                @endif
                                                @if($orderCounts['smm'] > 0)
                                                    <span class="px-1.5 py-0.2 rounded text-[10px] bg-sky-100 text-sky-800 font-bold" title="SMM Orders">
                                                        SMM: {{ $orderCounts['smm'] }}
                                                    </span>
                                                @endif
                                                @if($orderCounts['ads'] > 0)
                                                    <span class="px-1.5 py-0.2 rounded text-[10px] bg-amber-100 text-amber-800 font-bold" title="Ads Campaign Orders">
                                                        Ads: {{ $orderCounts['ads'] }}
                                                    </span>
                                                @endif
                                                @if($orderCounts['others'] > 0)
                                                    <span class="px-1.5 py-0.2 rounded text-[10px] bg-slate-100 text-slate-700 font-bold" title="Other Orders">
                                                        Other: {{ $orderCounts['others'] }}
                                                    </span>
                                                @endif
                                            </div>
                                        @else
                                            <span class="text-gray-400 text-2xs italic">{{ __('No prior orders') }}</span>
                                        @endif
                                    </td>

                                    <!-- 11. Interested Product -->
                                    <td class="py-2.5 px-2.5 whitespace-nowrap text-[11px]">
                                        @php $intProd = $lead->interested_products ?: ($lead->interested_service ?: null); @endphp
                                        @if($intProd)
                                            <span class="font-semibold text-gray-900 truncate max-w-[130px] block" title="{{ $intProd }}">{{ $intProd }}</span>
                                        @else
                                            <span class="text-gray-400 text-2xs">-</span>
                                        @endif
                                    </td>

                                    <!-- 12. Potential Order -->
                                    <td class="py-2.5 px-2.5 whitespace-nowrap text-[11px]">
                                        @if($lead->potential_order)
                                            <span class="font-semibold text-indigo-700 truncate max-w-[130px] block" title="{{ $lead->potential_order }}">{{ $lead->potential_order }}</span>
                                        @else
                                            <span class="text-gray-400 text-2xs">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="12" class="text-center py-12 text-gray-500">
                                        <div class="flex flex-col items-center justify-center">
                                            <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                                            <span class="text-base font-semibold text-gray-700">{{ __('No leads found') }}</span>
                                            <span class="text-xs text-gray-400 mt-1">{{ __('Adjust filters or create a new lead entry.') }}</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($leads->hasPages())
                    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                        {{ $leads->links() }}
                    </div>
                @endif
            </div>
        </div>

    <style>
        .leads-table-container {
            max-height: 580px !important;
            min-height: 300px;
            overflow-x: auto !important;
            overflow-y: auto !important;
            position: relative;
            scrollbar-width: thin;
            scrollbar-color: #94a3b8 #f1f5f9;
        }
        .leads-table-container::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        .leads-table-container::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 6px;
        }
        .leads-table-container::-webkit-scrollbar-thumb {
            background: #94a3b8;
            border-radius: 6px;
        }
        .leads-table-container::-webkit-scrollbar-thumb:hover {
            background: #64748b;
        }
        .leads-table-container table {
            min-width: 1450px !important;
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }
        .leads-table-container thead th {
            position: sticky !important;
            top: 0 !important;
            z-index: 20 !important;
            background-color: #f1f5f9 !important;
            border-bottom: 2px solid #cbd5e1 !important;
        }
        .leads-table-container thead th.bg-indigo-sticky {
            background-color: #e0e7ff !important;
        }
        .leads-table-container tbody td {
            border-bottom: 1px solid #f1f5f9;
        }
    </style>

    <!-- Top-Center Floating Copy Toast Notification -->
    <div id="copy-toast-banner" 
         class="fixed top-6 left-1/2 transform -translate-x-1/2 z-[99999] opacity-0 -translate-y-4 transition-all duration-300 pointer-events-none flex items-center gap-3 bg-slate-900/95 text-white px-5 py-3 rounded-2xl shadow-2xl border border-slate-700/80 backdrop-blur-md">
        <div class="w-6 h-6 rounded-full bg-emerald-500/20 text-emerald-400 flex items-center justify-center shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
        </div>
        <div class="flex items-center gap-2 text-xs sm:text-sm font-semibold">
            <span class="text-emerald-400 font-bold">Copied:</span>
            <span class="text-slate-100 font-mono" id="copy-toast-text"></span>
        </div>
    </div>

    <script>
        let copyToastTimer = null;

        function copyLeadText(text, btn) {
            if (!text) return;
            const cleanText = text.trim();

            // 1. Copy to clipboard
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(cleanText).catch(function() {
                    fallbackCopy(cleanText);
                });
            } else {
                fallbackCopy(cleanText);
            }

            // 2. Direct inline feedback on clicked text
            if (btn) {
                const textSpan = btn.querySelector('.copy-target-text');
                if (textSpan && !btn.dataset.isCopying) {
                    btn.dataset.isCopying = 'true';
                    const originalHtml = textSpan.innerHTML;
                    textSpan.innerHTML = '<span class="text-emerald-600 font-bold bg-emerald-50 px-1 py-0.5 rounded">✓ Copied!</span>';
                    setTimeout(function() {
                        textSpan.innerHTML = originalHtml;
                        delete btn.dataset.isCopying;
                    }, 1400);
                }
            }

            // 3. Show prominent top-center floating toast
            showCopyToast(cleanText);
        }

        function showCopyToast(text) {
            const toast = document.getElementById('copy-toast-banner');
            const toastText = document.getElementById('copy-toast-text');
            if (!toast || !toastText) return;

            toastText.textContent = text;
            toast.classList.remove('opacity-0', '-translate-y-4');
            toast.classList.add('opacity-100', 'translate-y-0');

            clearTimeout(copyToastTimer);
            copyToastTimer = setTimeout(function() {
                toast.classList.remove('opacity-100', 'translate-y-0');
                toast.classList.add('opacity-0', '-translate-y-4');
            }, 2200);
        }

        function fallbackCopy(text) {
            const el = document.createElement('textarea');
            el.value = text;
            el.setAttribute('readonly', '');
            el.style.position = 'fixed';
            el.style.left = '-9999px';
            el.style.top = '-9999px';
            document.body.appendChild(el);
            el.focus();
            el.select();
            try {
                document.execCommand('copy');
            } catch (e) {
                console.warn('Fallback copy error:', e);
            }
            document.body.removeChild(el);
        }
    </script>
</x-app-layout>
