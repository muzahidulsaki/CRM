<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight flex items-center gap-2">
                    <svg class="w-7 h-7 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    CRM & লিড ম্যানেজমেন্ট (Leads)
                </h2>
                <p class="text-sm text-gray-500 mt-1">সমস্ত ইনকোয়ারি, কাস্টমার আগ্রহ ও সেলস ট্র্যাকিং</p>
            </div>
            <div class="flex items-center gap-2.5">
                <a href="{{ route('leads.pipeline') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 transition">
                    <svg class="w-4 h-4 me-1.5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/></svg>
                    পাইপলাইন কানবান
                </a>
                <a href="{{ route('leads.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 shadow-sm transition">
                    <svg class="w-4 h-4 me-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    নতুন লিড যোগ করুন
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
                        <span class="font-medium text-sm">{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            <!-- Quick Metrics Grid -->
            <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-7 gap-3">
                <a href="{{ route('leads.index') }}" class="bg-white p-3.5 rounded-xl border border-gray-200 shadow-sm hover:border-indigo-400 transition text-center {{ !request('status') && !request('follow_up') ? 'ring-2 ring-indigo-500 bg-indigo-50/20' : '' }}">
                    <div class="text-xs font-medium text-gray-500">মোট লিড</div>
                    <div class="text-xl font-bold text-gray-900 mt-1">{{ $metrics['total'] }}</div>
                </a>

                <a href="{{ route('leads.index', ['status' => 'New']) }}" class="bg-white p-3.5 rounded-xl border border-gray-200 shadow-sm hover:border-indigo-400 transition text-center {{ request('status') == 'New' ? 'ring-2 ring-indigo-500 bg-indigo-50/20' : '' }}">
                    <div class="text-xs font-medium text-indigo-600">নতুন (New)</div>
                    <div class="text-xl font-bold text-indigo-700 mt-1">{{ $metrics['new'] }}</div>
                </a>

                <a href="{{ route('leads.index', ['status' => 'Interested']) }}" class="bg-white p-3.5 rounded-xl border border-gray-200 shadow-sm hover:border-cyan-400 transition text-center {{ request('status') == 'Interested' ? 'ring-2 ring-cyan-500 bg-cyan-50/20' : '' }}">
                    <div class="text-xs font-medium text-cyan-600">আগ্রহী (Interested)</div>
                    <div class="text-xl font-bold text-cyan-700 mt-1">{{ $metrics['interested'] }}</div>
                </a>

                <a href="{{ route('leads.index', ['status' => 'Proposal Sent']) }}" class="bg-white p-3.5 rounded-xl border border-gray-200 shadow-sm hover:border-amber-400 transition text-center {{ request('status') == 'Proposal Sent' ? 'ring-2 ring-amber-500 bg-amber-50/20' : '' }}">
                    <div class="text-xs font-medium text-amber-600">কোটেশন পাঠানো</div>
                    <div class="text-xl font-bold text-amber-700 mt-1">{{ $metrics['proposal'] }}</div>
                </a>

                <a href="{{ route('leads.index', ['status' => 'Won']) }}" class="bg-white p-3.5 rounded-xl border border-gray-200 shadow-sm hover:border-emerald-400 transition text-center {{ request('status') == 'Won' ? 'ring-2 ring-emerald-500 bg-emerald-50/20' : '' }}">
                    <div class="text-xs font-medium text-emerald-600">অর্ডার কনফার্ম (Won)</div>
                    <div class="text-xl font-bold text-emerald-700 mt-1">{{ $metrics['won'] }}</div>
                </a>

                <a href="{{ route('leads.index', ['follow_up' => 'today']) }}" class="bg-amber-50/60 p-3.5 rounded-xl border border-amber-200 shadow-sm hover:border-amber-400 transition text-center {{ request('follow_up') == 'today' ? 'ring-2 ring-amber-500' : '' }}">
                    <div class="text-xs font-semibold text-amber-800 flex items-center justify-center gap-1">
                        <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                        আজকের ফলো-আপ
                    </div>
                    <div class="text-xl font-bold text-amber-900 mt-1">{{ $metrics['today_followups'] }}</div>
                </a>

                <a href="{{ route('leads.index', ['follow_up' => 'overdue']) }}" class="bg-rose-50/60 p-3.5 rounded-xl border border-rose-200 shadow-sm hover:border-rose-400 transition text-center {{ request('follow_up') == 'overdue' ? 'ring-2 ring-rose-500' : '' }}">
                    <div class="text-xs font-semibold text-rose-800 flex items-center justify-center gap-1">
                        <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                        বকেয়া ফলো-আপ
                    </div>
                    <div class="text-xl font-bold text-rose-900 mt-1">{{ $metrics['overdue_followups'] }}</div>
                </a>
            </div>

            <!-- Filter & Search Bar -->
            <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm">
                <form method="GET" action="{{ route('leads.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-3">
                    <!-- Search input -->
                    <div class="lg:col-span-2">
                        <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">সার্চ করুন</label>
                        <div class="relative">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="নাম, ফোন, লিড কোড বা কোম্পানি..." class="w-full text-sm border-gray-300 rounded-lg pl-9 focus:ring-indigo-500 focus:border-indigo-500">
                            <svg class="w-4 h-4 text-gray-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </div>
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">স্ট্যাটাস</label>
                        <select name="status" class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">সকল স্ট্যাটাস</option>
                            @foreach(['New', 'Contacted', 'Interested', 'Meeting/Demo', 'Proposal Sent', 'Negotiation', 'Won', 'Lost'] as $st)
                                <option value="{{ $st }}" {{ request('status') == $st ? 'selected' : '' }}>{{ $st }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Temperature -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">টেম্পারেচার</label>
                        <select name="temperature" class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">সকল ধরন</option>
                            <option value="Hot" {{ request('temperature') == 'Hot' ? 'selected' : '' }}>🔥 Hot (খুব আগ্রহী)</option>
                            <option value="Warm" {{ request('temperature') == 'Warm' ? 'selected' : '' }}>⚡ Warm (মাঝারি)</option>
                            <option value="Cold" {{ request('temperature') == 'Cold' ? 'selected' : '' }}>❄️ Cold (কম আগ্রহী)</option>
                        </select>
                    </div>

                    <!-- Service -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">আগ্রহী সার্ভিস</label>
                        <select name="service" class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">সকল সার্ভিস</option>
                            @foreach($serviceCategories as $cat)
                                <option value="{{ $cat }}" {{ request('service') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Action buttons -->
                    <div class="flex items-end gap-2">
                        <button type="submit" class="flex-1 px-4 py-2.5 bg-gray-900 text-white rounded-lg text-sm font-semibold hover:bg-gray-800 transition flex items-center justify-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                            ফিল্টার
                        </button>
                        <a href="{{ route('leads.index') }}" class="px-3 py-2.5 bg-gray-100 text-gray-600 rounded-lg text-sm font-medium hover:bg-gray-200 transition" title="রিসেট">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        </a>
                    </div>
                </form>
            </div>

            <!-- Leads Table -->
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-left text-sm">
                        <thead class="bg-gray-50/75 text-gray-500 font-semibold uppercase text-xs tracking-wider">
                            <tr>
                                <th class="py-3.5 px-4">লিড কোড & নাম</th>
                                <th class="py-3.5 px-4">যোগাযোগ (Phone/WA)</th>
                                <th class="py-3.5 px-4">সার্ভিস ও বাজেট</th>
                                <th class="py-3.5 px-4">টেম্পারেচার</th>
                                <th class="py-3.5 px-4">স্ট্যাটাস</th>
                                <th class="py-3.5 px-4">ফলো-আপ শিডিউল</th>
                                <th class="py-3.5 px-4">দায়িত্বপ্রাপ্ত</th>
                                <th class="py-3.5 px-4 text-right">অ্যাকশন</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($leads as $lead)
                                <tr class="hover:bg-indigo-50/30 transition">
                                    <!-- Lead & Name -->
                                    <td class="py-3.5 px-4">
                                        <div class="flex items-center gap-2">
                                            <a href="{{ route('leads.show', $lead) }}" class="font-bold text-indigo-600 hover:text-indigo-800 hover:underline">
                                                {{ $lead->lead_code }}
                                            </a>
                                            @if($lead->customer_id)
                                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-semibold bg-emerald-100 text-emerald-800" title="গ্রাহকে রূপান্তরিত">
                                                    Customer
                                                </span>
                                            @endif
                                        </div>
                                        <div class="font-medium text-gray-900">{{ $lead->name }}</div>
                                        @if($lead->company_name)
                                            <div class="text-xs text-gray-500">{{ $lead->company_name }}</div>
                                        @endif
                                    </td>

                                    <!-- Contact Info -->
                                    <td class="py-3.5 px-4 whitespace-nowrap">
                                        <div class="flex items-center gap-1.5 font-medium text-gray-800">
                                            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                            <a href="tel:{{ $lead->phone }}" class="hover:underline hover:text-indigo-600">{{ $lead->phone }}</a>
                                        </div>
                                        @if($lead->whatsapp || $lead->normalized_phone)
                                            <div class="mt-1">
                                                <a href="https://wa.me/{{ $lead->normalized_whatsapp ? '88' . $lead->normalized_whatsapp : '88' . $lead->normalized_phone }}" target="_blank" class="inline-flex items-center gap-1 text-[11px] font-semibold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded hover:bg-emerald-100 transition">
                                                    <svg class="w-3 h-3 text-emerald-600" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.007c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.353.101.173.449.741.964 1.2 1.309 1.168 1.706.772 1.95.836.087.023.23-.014.354-.188.125-.174.529-.623.67-.837.14-.213.281-.178.472-.107.19.071 1.208.57 1.415.674.208.104.346.155.397.242.051.087.051.505-.093.91z"/></svg>
                                                    WhatsApp
                                                </a>
                                            </div>
                                        @endif
                                    </td>

                                    <!-- Service & Budget -->
                                    <td class="py-3.5 px-4">
                                        <div class="font-medium text-gray-900 text-xs">{{ $lead->interested_service ?? 'অন্যান্য' }}</div>
                                        <div class="text-xs text-gray-500 mt-0.5">
                                            @if($lead->budget)
                                                বাজেট: <span class="font-semibold text-gray-800">৳{{ number_format($lead->budget) }}</span>
                                            @else
                                                <span class="text-gray-400">বাজেট উল্লেখ নেই</span>
                                            @endif
                                        </div>
                                    </td>

                                    <!-- Temperature -->
                                    <td class="py-3.5 px-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold border {{ $lead->temperature_badge_class }}">
                                            @if($lead->lead_temperature === 'Hot') 🔥 Hot
                                            @elseif($lead->lead_temperature === 'Warm') ⚡ Warm
                                            @else ❄️ Cold
                                            @endif
                                        </span>
                                    </td>

                                    <!-- Status -->
                                    <td class="py-3.5 px-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $lead->status_badge_class }}">
                                            {{ $lead->lead_status }}
                                        </span>
                                    </td>

                                    <!-- Next Follow-up -->
                                    <td class="py-3.5 px-4 whitespace-nowrap">
                                        @if($lead->next_followup_date)
                                            @php
                                                $isToday = $lead->next_followup_date->isToday();
                                                $isOverdue = $lead->next_followup_date->isPast() && !$isToday && !in_array($lead->lead_status, ['Won', 'Lost']);
                                            @endphp
                                            <div class="text-xs font-semibold {{ $isOverdue ? 'text-rose-600' : ($isToday ? 'text-amber-600 font-bold' : 'text-gray-700') }}">
                                                {{ $lead->next_followup_date->format('d M, Y h:i A') }}
                                            </div>
                                            <div class="text-[10px] {{ $isOverdue ? 'text-rose-500 font-medium' : 'text-gray-400' }}">
                                                {{ $lead->next_followup_date->diffForHumans() }}
                                            </div>
                                        @else
                                            <span class="text-xs text-gray-400">কোন তারিখ নেই</span>
                                        @endif
                                    </td>

                                    <!-- Assigned Staff -->
                                    <td class="py-3.5 px-4 whitespace-nowrap text-xs text-gray-600">
                                        @if($lead->assignedUser)
                                            <span class="inline-flex items-center gap-1 font-medium text-gray-800">
                                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                                {{ $lead->assignedUser->name }}
                                            </span>
                                        @else
                                            <span class="text-gray-400 italic">অনির্ধারিত</span>
                                        @endif
                                    </td>

                                    <!-- Actions -->
                                    <td class="py-3.5 px-4 text-right whitespace-nowrap">
                                        <div class="flex items-center justify-end gap-1.5">
                                            <a href="{{ route('leads.show', $lead) }}" class="p-1.5 text-gray-500 hover:text-indigo-600 hover:bg-gray-100 rounded-lg transition" title="বিস্তারিত প্রোফাইল">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            </a>
                                            <a href="{{ route('leads.edit', $lead) }}" class="p-1.5 text-gray-500 hover:text-amber-600 hover:bg-gray-100 rounded-lg transition" title="সম্পাদনা">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            </a>
                                            <form method="POST" action="{{ route('leads.destroy', $lead) }}" onsubmit="return confirm('আপনি কি নিশ্চিত যে এই লিডটি মুছে ফেলতে চান?');" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-1.5 text-gray-400 hover:text-rose-600 hover:bg-gray-100 rounded-lg transition" title="মুছে ফেলুন">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-12 text-gray-500">
                                        <div class="flex flex-col items-center justify-center">
                                            <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                                            <span class="text-base font-semibold text-gray-700">কোন লিড পাওয়া যায়নি</span>
                                            <span class="text-xs text-gray-400 mt-1">ফিল্টার রিসেট করুন অথবা একটি নতুন লিড যোগ করুন।</span>
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
