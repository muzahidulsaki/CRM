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

            <!-- Quick Metrics Grid -->
            <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-6 gap-3">
                <a href="{{ route('leads.index') }}" class="bg-white p-3.5 rounded-xl border border-gray-200 shadow-sm hover:border-indigo-400 transition text-center {{ !request()->hasAny(['status', 'follow_up', 'today_followup', 'today_meeting', 'why_not_order', 'existing_order']) ? 'ring-2 ring-indigo-500 bg-indigo-50/20' : '' }}">
                    <div class="text-xs font-medium text-gray-500">{{ __('Total Leads') }}</div>
                    <div class="text-xl font-bold text-gray-900 mt-1">{{ $metrics['total'] }}</div>
                </a>

                <a href="{{ route('leads.index', ['today_followup' => 1]) }}" class="bg-amber-50/70 p-3.5 rounded-xl border border-amber-200 shadow-sm hover:border-amber-400 transition text-center {{ request('today_followup') || request('follow_up') == 'today' ? 'ring-2 ring-amber-500 bg-amber-100/50' : '' }}">
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

                <a href="{{ route('leads.index', ['status' => 'Interested']) }}" class="bg-white p-3.5 rounded-xl border border-gray-200 shadow-sm hover:border-cyan-400 transition text-center {{ request('status') == 'Interested' ? 'ring-2 ring-cyan-500 bg-cyan-50/20' : '' }}">
                    <div class="text-xs font-medium text-cyan-600">{{ __('Interested') }}</div>
                    <div class="text-xl font-bold text-cyan-700 mt-1">{{ $metrics['interested'] }}</div>
                </a>

                <a href="{{ route('leads.index', ['status' => 'Won']) }}" class="bg-white p-3.5 rounded-xl border border-gray-200 shadow-sm hover:border-emerald-400 transition text-center {{ request('status') == 'Won' ? 'ring-2 ring-emerald-500 bg-emerald-50/20' : '' }}">
                    <div class="text-xs font-medium text-emerald-600">{{ __('Won (Converted)') }}</div>
                    <div class="text-xl font-bold text-emerald-700 mt-1">{{ $metrics['won'] }}</div>
                </a>

                <a href="{{ route('leads.index', ['existing_order' => 'has_orders']) }}" class="bg-emerald-50/50 p-3.5 rounded-xl border border-emerald-200 shadow-sm hover:border-emerald-400 transition text-center {{ request('existing_order') == 'has_orders' ? 'ring-2 ring-emerald-500' : '' }}">
                    <div class="text-xs font-semibold text-emerald-700">{{ __('Existing Clients') }}</div>
                    <div class="text-xl font-bold text-emerald-800 mt-1">
                        {{ \App\Models\Lead::whereHas('customer.orders')->count() }}
                    </div>
                </a>
            </div>

            <!-- Filter & Search Bar with New Filters -->
            <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm space-y-4">
                <form method="GET" action="{{ route('leads.index') }}">
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
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-left text-xs">
                        <thead class="bg-gray-50/90 text-gray-600 font-bold uppercase tracking-wider">
                            <tr>
                                <th class="py-3 px-3 whitespace-nowrap">{{ __('Lead ID / Token') }}</th>
                                <th class="py-3 px-3 whitespace-nowrap">{{ __('Client & Business') }}</th>
                                <th class="py-3 px-3 whitespace-nowrap">{{ __('WhatsApp & Phone') }}</th>
                                <th class="py-3 px-3 whitespace-nowrap">{{ __('Country / Map') }}</th>
                                <th class="py-3 px-3 whitespace-nowrap">{{ __('Follow-up Status') }}</th>
                                <th class="py-3 px-3 whitespace-nowrap">{{ __('Meeting Info') }}</th>
                                <th class="py-3 px-3 whitespace-nowrap">{{ __('Product & Temp') }}</th>
                                <th class="py-3 px-3 whitespace-nowrap">{{ __('Status / Why Not') }}</th>
                                <th class="py-3 px-3 whitespace-nowrap bg-indigo-50/40 text-indigo-900 border-x border-indigo-100">
                                    {{ __('Existing Order Count') }}
                                </th>
                                <th class="py-3 px-3 text-right whitespace-nowrap">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($leads as $lead)
                                @php
                                    $orderCounts = $lead->existing_order_counts;
                                @endphp
                                <tr class="hover:bg-indigo-50/20 transition">
                                    <!-- Lead ID & Token -->
                                    <td class="py-3 px-3 whitespace-nowrap">
                                        <a href="{{ route('leads.show', $lead) }}" class="font-bold font-mono text-indigo-600 hover:text-indigo-800 hover:underline">
                                            {{ $lead->lead_code }}
                                        </a>
                                        @if($lead->token_id)
                                            <div class="text-2xs text-gray-500 font-mono">{{ $lead->token_id }}</div>
                                        @endif
                                        <div class="text-2xs text-slate-400 mt-0.5">{{ $lead->lead_source }}</div>
                                    </td>

                                    <!-- Client & Business -->
                                    <td class="py-3 px-3 min-w-[160px]">
                                        <div class="font-bold text-gray-900">{{ !empty($lead->name) && $lead->name !== '-' ? $lead->name : ($lead->company_name ?: '-') }}</div>
                                        @if($lead->company_name)
                                            <div class="text-gray-600 text-2xs truncate max-w-[180px]">{{ $lead->company_name }}</div>
                                        @endif
                                        @if($lead->business_type)
                                            <span class="inline-block px-1.5 py-0.2 rounded text-[10px] bg-slate-100 text-slate-600 font-medium mt-0.5">
                                                {{ $lead->business_type }}
                                            </span>
                                        @endif
                                    </td>

                                    <!-- WhatsApp & Phone & Status -->
                                    <td class="py-3 px-3 whitespace-nowrap">
                                        <div class="flex items-center gap-1 font-medium text-gray-800">
                                            <span>📞</span>
                                            <a href="tel:{{ $lead->phone }}" class="hover:underline">{{ $lead->phone }}</a>
                                        </div>
                                        @if($lead->whatsapp)
                                            <div class="flex items-center gap-1 text-[11px] mt-0.5 text-emerald-700">
                                                <span>💬</span>
                                                <a href="https://wa.me/{{ $lead->normalized_whatsapp ?? preg_replace('/\D/', '', $lead->whatsapp) }}" target="_blank" class="hover:underline font-mono">
                                                    {{ $lead->whatsapp }}
                                                </a>
                                                @if($lead->whatsapp_status)
                                                    <span class="px-1 py-0.2 rounded text-[9px] bg-emerald-100 text-emerald-800 font-semibold uppercase">
                                                        {{ $lead->whatsapp_status }}
                                                    </span>
                                                @endif
                                            </div>
                                        @endif
                                        @if($lead->email)
                                            <div class="text-2xs text-gray-500 truncate max-w-[140px] mt-0.5" title="{{ $lead->email }}">
                                                ✉️ {{ $lead->email }}
                                            </div>
                                        @endif
                                    </td>

                                    <!-- Location / Country -->
                                    <td class="py-3 px-3 whitespace-nowrap">
                                        <div class="text-gray-900 font-medium">
                                            {{ $lead->current_country ?? $lead->country ?? '-' }}
                                        </div>
                                        @if($lead->business_country && $lead->business_country !== $lead->current_country)
                                            <div class="text-2xs text-gray-500">Biz: {{ $lead->business_country }}</div>
                                        @endif
                                        @if($lead->google_maps_link)
                                            <a href="{{ $lead->google_maps_link }}" target="_blank" class="inline-flex items-center gap-0.5 text-2xs text-indigo-600 hover:underline mt-0.5">
                                                🗺️ Maps ↗
                                            </a>
                                        @endif
                                    </td>

                                    <!-- Follow-up -->
                                    <td class="py-3 px-3 whitespace-nowrap">
                                        @php
                                            $nextDate = $lead->followup_next_date ?? $lead->next_followup_date;
                                            $isToday = $nextDate && \Carbon\Carbon::parse($nextDate)->isToday();
                                            $isOverdue = $nextDate && \Carbon\Carbon::parse($nextDate)->isPast() && !$isToday && !in_array($lead->lead_status, ['Won', 'Lost']);
                                        @endphp
                                        @if($nextDate)
                                            <div class="font-semibold {{ $isOverdue ? 'text-rose-600 font-bold' : ($isToday ? 'text-amber-700 font-bold' : 'text-gray-800') }}">
                                                📅 {{ \Carbon\Carbon::parse($nextDate)->format('d M, Y') }}
                                            </div>
                                        @else
                                            <span class="text-gray-400 text-2xs">{{ __('No date set') }}</span>
                                        @endif
                                        @if($lead->followup_comment)
                                            <div class="text-2xs text-gray-500 truncate max-w-[150px] mt-0.5" title="{{ $lead->followup_comment }}">
                                                "{{ $lead->followup_comment }}"
                                            </div>
                                        @endif
                                    </td>

                                    <!-- Meeting Info -->
                                    <td class="py-3 px-3 whitespace-nowrap">
                                        @if($lead->next_meeting_datetime)
                                            <div class="font-semibold text-purple-700 flex items-center gap-1">
                                                <span>🤝</span>
                                                <span>{{ $lead->next_meeting_datetime->format('d M, h:i A') }}</span>
                                            </div>
                                            @if($lead->meeting_platform)
                                                <div class="text-2xs text-purple-600 font-medium">via {{ $lead->meeting_platform }}</div>
                                            @endif
                                        @else
                                            <span class="text-gray-400 text-2xs">{{ __('No meeting scheduled') }}</span>
                                        @endif
                                    </td>

                                    <!-- Product & Temperature -->
                                    <td class="py-3 px-3 whitespace-nowrap">
                                        <div class="font-medium text-gray-900 truncate max-w-[140px]" title="{{ $lead->interested_products ?? $lead->interested_service }}">
                                            {{ $lead->interested_products ?? $lead->interested_service ?? '-' }}
                                        </div>
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-[10px] font-semibold border mt-0.5 {{ $lead->temperature_badge_class }}">
                                            {{ $lead->lead_temperature }}
                                        </span>
                                    </td>

                                    <!-- Pipeline Status & Why Not Order -->
                                    <td class="py-3 px-3 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold {{ $lead->status_badge_class }}">
                                            {{ __($lead->lead_status) }}
                                        </span>
                                        @if($lead->why_not_order)
                                            <div class="text-2xs font-semibold text-rose-600 mt-1 max-w-[130px] truncate" title="{{ $lead->why_not_order }}">
                                                🚫 {{ $lead->why_not_order }}
                                            </div>
                                        @endif
                                    </td>

                                    <!-- Existing Order Count (Categorized Breakdown) -->
                                    <td class="py-3 px-3 bg-indigo-50/30 border-x border-indigo-100 min-w-[210px]">
                                        @if($orderCounts['total'] > 0)
                                            <div class="font-bold text-indigo-900 text-[11px] mb-1 flex items-center gap-1">
                                                <span>📦 Total: {{ $orderCounts['total'] }} orders</span>
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

                                    <!-- Actions -->
                                    <td class="py-3 px-3 text-right whitespace-nowrap">
                                        <div class="flex items-center justify-end gap-1">
                                            <a href="{{ route('leads.show', $lead) }}" class="p-1.5 text-gray-500 hover:text-indigo-600 hover:bg-gray-100 rounded-lg transition" title="{{ __('View Details') }}">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            </a>
                                            <a href="{{ route('leads.edit', $lead) }}" class="p-1.5 text-gray-500 hover:text-amber-600 hover:bg-gray-100 rounded-lg transition" title="{{ __('Edit') }}">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            </a>
                                            <form method="POST" action="{{ route('leads.destroy', $lead) }}" onsubmit="return confirm('{{ __('Are you sure you want to delete this lead?') }}');" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-1.5 text-gray-400 hover:text-rose-600 hover:bg-gray-100 rounded-lg transition" title="{{ __('Delete') }}">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center py-12 text-gray-500">
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
    </div>
</x-app-layout>
