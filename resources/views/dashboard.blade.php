<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-900 leading-tight">
                    {{ __('Management Dashboard') }}
                </h2>
                <p class="text-sm text-gray-500 mt-0.5">
                    স্বাগতম, <span class="font-semibold text-gray-800">{{ Auth::user()->name }}</span> ({{ Auth::user()->role->name ?? 'User' }})
                </p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('reports.index') }}" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-indigo-100 text-indigo-800 border border-indigo-200 hover:bg-indigo-200 transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    বিজনেজ রিপোর্টস হাব &rarr;
                </a>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800 border border-emerald-200">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    Phase 1 - 7 সচল (ERP, Finance & BI)
                </span>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- System Overview Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                <!-- Module 1: CRM & Sales -->
                <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm hover:shadow-md transition">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-indigo-600 uppercase tracking-wider">01. CRM & Sales</span>
                        <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        </div>
                    </div>
                    <div class="mt-4">
                        <div class="text-2xl font-black text-gray-900">{{ \App\Models\Lead::count() }} <span class="text-xs font-semibold text-gray-500">টি লিড</span></div>
                        <p class="text-xs text-gray-500 mt-1">{{ \App\Models\Customer::count() }} ক্লায়েন্ট &bull; {{ \App\Models\Quotation::count() }} কোটেশন</p>
                        <div class="mt-3 pt-2 border-t flex items-center gap-2">
                            <a href="{{ route('leads.index') }}" class="text-xs font-semibold text-indigo-600 hover:underline">Leads &rarr;</a>
                            <span class="text-gray-300">|</span>
                            <a href="{{ route('leads.pipeline') }}" class="text-xs font-semibold text-indigo-600 hover:underline">Pipeline &rarr;</a>
                        </div>
                    </div>
                </div>

                <!-- Module 2: Orders & Projects -->
                <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm hover:shadow-md transition">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-amber-600 uppercase tracking-wider">02. Orders & Projects</span>
                        <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                        </div>
                    </div>
                    <div class="mt-4">
                        <div class="text-2xl font-black text-gray-900">{{ \App\Models\Project::count() }} <span class="text-xs font-semibold text-gray-500">প্রজেক্ট</span></div>
                        <p class="text-xs text-gray-500 mt-1">{{ \App\Models\Order::count() }} টি অর্ডার &bull; {{ \App\Models\Task::count() }} টি টাস্ক</p>
                        <div class="mt-3 pt-2 border-t flex items-center gap-2">
                            <a href="{{ route('orders.index') }}" class="text-xs font-semibold text-amber-600 hover:underline">অর্ডার &rarr;</a>
                            <span class="text-gray-300">|</span>
                            <a href="{{ route('tasks.kanban') }}" class="text-xs font-semibold text-amber-600 hover:underline">কানবান &rarr;</a>
                        </div>
                    </div>
                </div>

                <!-- Module 3: Team & Work -->
                <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm hover:shadow-md transition">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-emerald-600 uppercase tracking-wider">03. Team & Billing</span>
                        <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                        </div>
                    </div>
                    <div class="mt-4">
                        <div class="text-2xl font-black text-gray-900">৳{{ number_format(\App\Models\VideoBilling::sum('paid_amount') + \App\Models\EmployeePayout::sum('paid_amount')) }}</div>
                        <p class="text-xs text-gray-500 mt-1">{{ \App\Models\Employee::count() }} কর্মী &bull; বকেয়া: ৳{{ number_format(\App\Models\VideoBilling::sum('due_amount')) }}</p>
                        <div class="mt-3 pt-2 border-t flex items-center gap-2">
                            <a href="{{ route('video-billings.index') }}" class="text-xs font-semibold text-emerald-600 hover:underline">ভিডিও বিলিং &rarr;</a>
                            <span class="text-gray-300">|</span>
                            <a href="{{ route('payouts.index') }}" class="text-xs font-semibold text-emerald-600 hover:underline">পে-আউট &rarr;</a>
                        </div>
                    </div>
                </div>

                <!-- Module 4: Support & Tickets -->
                <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm hover:shadow-md transition">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-blue-600 uppercase tracking-wider">04. Support & Tickets</span>
                        <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        </div>
                    </div>
                    <div class="mt-4">
                        <div class="text-2xl font-black text-gray-900">{{ \App\Models\SupportTicket::count() }} <span class="text-xs font-semibold text-gray-500">টিকেট</span></div>
                        <p class="text-xs text-gray-500 mt-1">
                            {{ \App\Models\SupportTicket::whereIn('status', ['New', 'Assigned', 'Working'])->count() }} টি ওপেন &bull; {{ \App\Models\SupportTicket::whereIn('status', ['Resolved', 'Closed'])->count() }} টি সমাধান
                        </p>
                        <div class="mt-3 pt-2 border-t flex items-center gap-2">
                            <a href="{{ route('support-tickets.index') }}" class="text-xs font-semibold text-blue-600 hover:underline">সব টিকেট &rarr;</a>
                            <span class="text-gray-300">|</span>
                            <a href="{{ route('public-support.create') }}" target="_blank" class="text-xs font-semibold text-blue-600 hover:underline">সাপোর্ট পোর্টাল &rarr;</a>
                        </div>
                    </div>
                </div>
            </div>

            @if(isset($summary))
                <!-- Section 49 Executive Pulse -->
                <div class="bg-gradient-to-r from-slate-900 to-indigo-950 rounded-2xl p-6 text-white shadow-md">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 pb-4 border-b border-indigo-900/60">
                        <div>
                            <span class="text-[11px] font-bold uppercase tracking-wider text-indigo-300">SRS Section 49: Executive Complete View</span>
                            <h3 class="text-base font-bold text-white mt-0.5">কোম্পানির আজকের রিয়েলটাইম পালস ও অপারেশনাল সারাংশ</h3>
                        </div>
                        <a href="{{ route('reports.index') }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-indigo-600 hover:bg-indigo-500 text-white transition flex items-center gap-1.5 shadow-sm">
                            সম্পূর্ণ রিপোর্টস ও অ্যানালিটিক্স &rarr;
                        </a>
                    </div>
                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4 pt-4 text-xs">
                        <div>
                            <span class="text-indigo-300">আজকের নতুন লিড</span>
                            <div class="text-xl font-black text-white mt-0.5">{{ $summary['sales']['new_leads_today'] }} টি</div>
                            <span class="text-[11px] text-indigo-400">ফলো-আপে: {{ $summary['sales']['leads_in_follow_up'] }} টি</span>
                        </div>
                        <div>
                            <span class="text-indigo-300">চলমান প্রজেক্ট</span>
                            <div class="text-xl font-black text-white mt-0.5">{{ $summary['projects']['running_projects'] }} টি</div>
                            <span class="text-[11px] {{ $summary['projects']['delayed_projects_count'] > 0 ? 'text-rose-400 font-bold' : 'text-emerald-400' }}">
                                বিলম্বিত: {{ $summary['projects']['delayed_projects_count'] }} টি
                            </span>
                        </div>
                        <div>
                            <span class="text-indigo-300">ভিডিও প্রোডাকশন</span>
                            <div class="text-xl font-black text-white mt-0.5">{{ number_format($summary['production']['total_seconds_edited']) }}s</div>
                            <span class="text-[11px] text-indigo-400">{{ $summary['production']['completed_videos'] }} টি ভিডিও সম্পন্ন</span>
                        </div>
                        <div>
                            <span class="text-indigo-300">চলতি মাসের নেট প্রফিট</span>
                            <div class="text-xl font-black {{ $summary['finance']['net_profit_month'] >= 0 ? 'text-emerald-400' : 'text-rose-400' }} mt-0.5">
                                {{ $summary['finance']['net_profit_month'] >= 0 ? '+' : '' }}৳{{ number_format($summary['finance']['net_profit_month'], 0) }}
                            </div>
                            <span class="text-[11px] text-indigo-400">আউটফ্লো: ৳{{ number_format($summary['finance']['total_outflow_month'], 0) }}</span>
                        </div>
                        <div>
                            <span class="text-indigo-300">পেন্ডিং সাপোর্ট টিকেট</span>
                            <div class="text-xl font-black text-white mt-0.5">{{ $summary['support']['pending_tickets'] }} টি</div>
                            <span class="text-[11px] text-indigo-400">রিওয়ার্ক: {{ $summary['support']['rework_tickets'] }} টি</span>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Quick Workflow Actions -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-base font-bold text-gray-900 mb-4">দ্রুত অ্যাকশন ও প্রজেক্ট কন্ট্রোল</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4">
                    <a href="{{ route('orders.index') }}" class="p-4 rounded-xl border border-gray-100 bg-gray-50 hover:bg-indigo-50 hover:border-indigo-200 transition group flex items-start gap-3">
                        <div class="p-2.5 bg-white rounded-lg shadow-sm text-indigo-600 group-hover:scale-105 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900 group-hover:text-indigo-600 transition text-sm">অর্ডার ম্যানেজমেন্ট</h4>
                            <p class="text-xs text-gray-500 mt-0.5">নতুন অর্ডার, বকেয়া এবং সেলস ইনভয়েস</p>
                        </div>
                    </a>

                    <a href="{{ route('projects.index') }}" class="p-4 rounded-xl border border-gray-100 bg-gray-50 hover:bg-indigo-50 hover:border-indigo-200 transition group flex items-start gap-3">
                        <div class="p-2.5 bg-white rounded-lg shadow-sm text-indigo-600 group-hover:scale-105 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900 group-hover:text-indigo-600 transition text-sm">প্রজেক্ট ডিরেক্টরি</h4>
                            <p class="text-xs text-gray-500 mt-0.5">অগ্রগতি, ডেডলাইন ও প্রজেক্ট ট্র্যাকিং</p>
                        </div>
                    </a>

                    <a href="{{ route('tasks.kanban') }}" class="p-4 rounded-xl border border-gray-100 bg-gray-50 hover:bg-indigo-50 hover:border-indigo-200 transition group flex items-start gap-3">
                        <div class="p-2.5 bg-white rounded-lg shadow-sm text-indigo-600 group-hover:scale-105 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/></svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900 group-hover:text-indigo-600 transition text-sm">টাস্ক কানবান বোর্ড</h4>
                            <p class="text-xs text-gray-500 mt-0.5">৬-ধাপ লাইফসাইকেল ও ড্র্যাগ-অ্যান্ড-ড্রপ</p>
                        </div>
                    </a>

                    <a href="{{ route('video-billings.index') }}" class="p-4 rounded-xl border border-gray-100 bg-gray-50 hover:bg-indigo-50 hover:border-indigo-200 transition group flex items-start gap-3">
                        <div class="p-2.5 bg-white rounded-lg shadow-sm text-indigo-600 group-hover:scale-105 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900 group-hover:text-indigo-600 transition text-sm">ভিডিও বিলিং</h4>
                            <p class="text-xs text-gray-500 mt-0.5">প্রতি সেকেন্ডের রেট ও হিসাব</p>
                        </div>
                    </a>

                    <a href="{{ route('payouts.index') }}" class="p-4 rounded-xl border border-gray-100 bg-gray-50 hover:bg-emerald-50 hover:border-emerald-200 transition group flex items-start gap-3">
                        <div class="p-2.5 bg-white rounded-lg shadow-sm text-emerald-600 group-hover:scale-105 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900 group-hover:text-emerald-600 transition text-sm">পে-আউট ভাউচার</h4>
                            <p class="text-xs text-gray-500 mt-0.5">বেতন ও পারিশ্রমিক রিসিট</p>
                        </div>
                    </a>

                    <a href="{{ route('settings.index') }}" class="p-4 rounded-xl border border-gray-100 bg-gray-50 hover:bg-indigo-50 hover:border-indigo-200 transition group flex items-start gap-3">
                        <div class="p-2.5 bg-white rounded-lg shadow-sm text-indigo-600 group-hover:scale-105 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900 group-hover:text-indigo-600 transition text-sm">সিস্টেম কনফিগারেশন</h4>
                            <p class="text-xs text-gray-500 mt-0.5">সার্ভিস ক্যাটাগরি, রোল ও পারমিশন</p>
                        </div>
                    </a>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
