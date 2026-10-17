<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="font-extrabold text-2xl sm:text-3xl text-slate-900 tracking-tight leading-tight">
                    {{ __('Management Dashboard') }}
                </h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">
                    {{ __('Welcome') }}, <span class="font-bold text-slate-800">{{ Auth::user()->name }}</span> ({{ Auth::user()->role->name ?? 'Admin' }}) &nbsp;|&nbsp; {{ __("Here's what's happening with your business today.") }}
                </p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('reports.index') }}" 
                   class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold bg-indigo-50/90 text-indigo-700 border border-indigo-200/80 hover:bg-indigo-100 hover:border-indigo-300 shadow-2xs transition">
                    <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    <span>{{ __('Business Reports Hub') }}</span>
                    <span class="text-indigo-400">&rarr;</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">

        <!-- Row 1: 4 Module Overview Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            
            <!-- Card 01: CRM & Sales -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-2xs hover:shadow-md transition flex flex-col justify-between group relative overflow-hidden">
                <div>
                    <!-- Header Pill & Circular Chevron -->
                    <div class="flex items-center justify-between">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-blue-50 text-blue-700 border border-blue-100/80">
                            <svg class="w-3.5 h-3.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            <span>01. {{ __('CRM & SALES') }}</span>
                        </span>
                        <a href="{{ route('leads.index') }}" class="w-6 h-6 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center hover:bg-blue-100 transition" title="{{ __('View Details') }}">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>

                    <!-- Metrics and Sparkline Wave -->
                    <div class="flex items-center justify-between mt-4 gap-2">
                        <div class="min-w-0">
                            <div class="text-3xl font-black text-slate-900 tracking-tight leading-none">
                                {{ $cardMetrics['leads'] ?? 0 }}
                                <span class="text-sm font-semibold text-slate-700 ml-1">{{ __('Leads') }}</span>
                            </div>
                            <div class="text-xs text-slate-500 mt-2 font-medium flex items-center gap-2 sm:gap-3 flex-wrap">
                                <span><strong class="text-slate-900">{{ $cardMetrics['clients'] ?? 0 }}</strong> {{ __('Clients') }}</span>
                                <span class="text-slate-300">&bull;</span>
                                <span><strong class="text-slate-900">{{ $cardMetrics['quotations'] ?? 0 }}</strong> {{ __('Quotations') }}</span>
                            </div>
                        </div>

                        <!-- Sparkline Wave SVG (Blue) -->
                        <div class="relative w-24 sm:w-28 h-11 shrink-0 overflow-hidden">
                            <svg viewBox="0 0 100 45" class="w-full h-full block overflow-hidden" fill="none">
                                <defs>
                                    <linearGradient id="blueGrad" x1="0" y1="0" x2="0" y2="1">
                                        <stop offset="0%" stop-color="#3b82f6" stop-opacity="0.30"/>
                                        <stop offset="100%" stop-color="#3b82f6" stop-opacity="0.0"/>
                                    </linearGradient>
                                </defs>
                                <path d="M 0 32 Q 25 12, 50 25 T 100 12 L 100 45 L 0 45 Z" fill="url(#blueGrad)"/>
                                <path d="M 0 32 Q 25 12, 50 25 T 100 12" stroke="#3b82f6" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Footer Links -->
                <div class="mt-4 pt-3 border-t border-slate-100 flex items-center gap-4 text-xs font-bold text-blue-600">
                    <a href="{{ route('leads.index') }}" class="hover:text-blue-800 transition flex items-center gap-1">
                        {{ __('Leads') }} &rarr;
                    </a>
                    <a href="{{ route('leads.pipeline') }}" class="hover:text-blue-800 transition flex items-center gap-1">
                        {{ __('Pipeline') }} &rarr;
                    </a>
                </div>
            </div>

            <!-- Card 02: Orders & Projects -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-2xs hover:shadow-md transition flex flex-col justify-between group relative overflow-hidden">
                <div>
                    <!-- Header Pill & Circular Chevron -->
                    <div class="flex items-center justify-between">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-amber-50 text-amber-700 border border-amber-100/80">
                            <svg class="w-3.5 h-3.5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <span>02. {{ __('ORDERS & PROJECTS') }}</span>
                        </span>
                        <a href="{{ route('projects.index') }}" class="w-6 h-6 rounded-full bg-amber-50 text-amber-600 flex items-center justify-center hover:bg-amber-100 transition" title="{{ __('View Details') }}">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>

                    <!-- Metrics and Sparkline Wave -->
                    <div class="flex items-center justify-between mt-4 gap-2">
                        <div class="min-w-0">
                            <div class="text-3xl font-black text-slate-900 tracking-tight leading-none">
                                {{ $cardMetrics['projects'] ?? 0 }}
                                <span class="text-sm font-semibold text-slate-700 ml-1">{{ __('Projects') }}</span>
                            </div>
                            <div class="text-xs text-slate-500 mt-2 font-medium flex items-center gap-2 sm:gap-3 flex-wrap">
                                <span><strong class="text-slate-900">{{ $cardMetrics['orders'] ?? 0 }}</strong> {{ __('Orders') }}</span>
                                <span class="text-slate-300">&bull;</span>
                                <span><strong class="text-slate-900">{{ $cardMetrics['tasks'] ?? 0 }}</strong> {{ __('Tasks') }}</span>
                            </div>
                        </div>

                        <!-- Sparkline Wave SVG (Orange) -->
                        <div class="relative w-24 sm:w-28 h-11 shrink-0 overflow-hidden">
                            <svg viewBox="0 0 100 45" class="w-full h-full block overflow-hidden" fill="none">
                                <defs>
                                    <linearGradient id="amberGrad" x1="0" y1="0" x2="0" y2="1">
                                        <stop offset="0%" stop-color="#f59e0b" stop-opacity="0.30"/>
                                        <stop offset="100%" stop-color="#f59e0b" stop-opacity="0.0"/>
                                    </linearGradient>
                                </defs>
                                <path d="M 0 35 Q 28 20, 55 30 T 100 14 L 100 45 L 0 45 Z" fill="url(#amberGrad)"/>
                                <path d="M 0 35 Q 28 20, 55 30 T 100 14" stroke="#f59e0b" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Footer Links -->
                <div class="mt-4 pt-3 border-t border-slate-100 flex items-center gap-4 text-xs font-bold text-amber-600">
                    <a href="{{ route('orders.index') }}" class="hover:text-amber-800 transition flex items-center gap-1">
                        {{ __('Orders') }} &rarr;
                    </a>
                    <a href="{{ route('tasks.kanban') }}" class="hover:text-amber-800 transition flex items-center gap-1">
                        {{ __('Kanban') }} &rarr;
                    </a>
                </div>
            </div>

            <!-- Card 03: Team & Billing -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-2xs hover:shadow-md transition flex flex-col justify-between group relative overflow-hidden">
                <div>
                    <!-- Header Pill & Circular Chevron -->
                    <div class="flex items-center justify-between">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-100/80">
                            <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                            </svg>
                            <span>03. {{ __('TEAM & BILLING') }}</span>
                        </span>
                        <a href="{{ route('video-billings.index') }}" class="w-6 h-6 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center hover:bg-emerald-100 transition" title="{{ __('View Details') }}">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>

                    <!-- Metrics and Sparkline Wave -->
                    <div class="flex items-center justify-between mt-4 gap-2">
                        <div class="min-w-0 flex-1">
                            <div class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight leading-none whitespace-nowrap">
                                {{ __('BDT') }} {{ number_format($cardMetrics['team_paid'] ?? 0) }}
                            </div>
                            <div class="text-xs text-slate-500 mt-2 font-medium flex items-center gap-2 sm:gap-3 flex-wrap">
                                <span><strong class="text-slate-900">{{ $cardMetrics['staff'] ?? 0 }}</strong> {{ __('Staff') }}</span>
                                <span class="text-slate-300">&bull;</span>
                                <span>{{ __('Due') }}: <strong class="text-slate-900">{{ __('BDT') }} {{ number_format($cardMetrics['billing_due'] ?? 0) }}</strong></span>
                            </div>
                        </div>

                        <!-- Sparkline Wave SVG (Green) -->
                        <div class="relative w-20 sm:w-24 h-10 shrink-0 overflow-hidden">
                            <svg viewBox="0 0 100 45" class="w-full h-full block overflow-hidden" fill="none">
                                <defs>
                                    <linearGradient id="emeraldGrad" x1="0" y1="0" x2="0" y2="1">
                                        <stop offset="0%" stop-color="#10b981" stop-opacity="0.30"/>
                                        <stop offset="100%" stop-color="#10b981" stop-opacity="0.0"/>
                                    </linearGradient>
                                </defs>
                                <path d="M 0 32 Q 18 16, 36 28 T 70 18 Q 85 28, 100 10 L 100 45 L 0 45 Z" fill="url(#emeraldGrad)"/>
                                <path d="M 0 32 Q 18 16, 36 28 T 70 18 Q 85 28, 100 10" stroke="#10b981" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Footer Links -->
                <div class="mt-4 pt-3 border-t border-slate-100 flex items-center gap-4 text-xs font-bold text-emerald-600">
                    <a href="{{ route('video-billings.index') }}" class="hover:text-emerald-800 transition flex items-center gap-1">
                        {{ __('Video Billing') }} &rarr;
                    </a>
                    <a href="{{ route('payouts.index') }}" class="hover:text-emerald-800 transition flex items-center gap-1">
                        {{ __('Payouts') }} &rarr;
                    </a>
                </div>
            </div>

            <!-- Card 04: Support & Tickets -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-2xs hover:shadow-md transition flex flex-col justify-between group relative overflow-hidden">
                <div>
                    <!-- Header Pill & Circular Chevron -->
                    <div class="flex items-center justify-between">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-purple-50 text-purple-700 border border-purple-100/80">
                            <svg class="w-3.5 h-3.5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                            <span>04. {{ __('SUPPORT & TICKETS') }}</span>
                        </span>
                        <a href="{{ route('support-tickets.index') }}" class="w-6 h-6 rounded-full bg-purple-50 text-purple-600 flex items-center justify-center hover:bg-purple-100 transition" title="{{ __('View Details') }}">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>

                    <!-- Metrics and Sparkline Wave -->
                    <div class="flex items-center justify-between mt-4 gap-2">
                        <div class="min-w-0">
                            <div class="text-3xl font-black text-slate-900 tracking-tight leading-none">
                                {{ $cardMetrics['tickets'] ?? 0 }}
                                <span class="text-sm font-semibold text-slate-700 ml-1">{{ __('Tickets') }}</span>
                            </div>
                            <div class="text-xs text-slate-500 mt-2 font-medium flex items-center gap-2 sm:gap-3 flex-wrap">
                                <span><strong class="text-slate-900">{{ $cardMetrics['open_tickets'] ?? 0 }}</strong> {{ __('Open') }}</span>
                                <span class="text-slate-300">&bull;</span>
                                <span><strong class="text-slate-900">{{ $cardMetrics['resolved_tickets'] ?? 0 }}</strong> {{ __('Resolved') }}</span>
                            </div>
                        </div>

                        <!-- Sparkline Wave SVG with Soft Gradient Fill (Purple) -->
                        <div class="relative w-24 sm:w-28 h-11 shrink-0 overflow-hidden">
                            <svg viewBox="0 0 100 45" class="w-full h-full block overflow-hidden" fill="none">
                                <defs>
                                    <linearGradient id="purpleGrad" x1="0" y1="0" x2="0" y2="1">
                                        <stop offset="0%" stop-color="#8b5cf6" stop-opacity="0.30"/>
                                        <stop offset="100%" stop-color="#8b5cf6" stop-opacity="0.0"/>
                                    </linearGradient>
                                </defs>
                                <path d="M 0 35 Q 25 15, 50 28 T 100 12 L 100 45 L 0 45 Z" fill="url(#purpleGrad)"/>
                                <path d="M 0 35 Q 25 15, 50 28 T 100 12" stroke="#8b5cf6" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Footer Links -->
                <div class="mt-4 pt-3 border-t border-slate-100 flex items-center gap-4 text-xs font-bold text-purple-600">
                    <a href="{{ route('support-tickets.index') }}" class="hover:text-purple-800 transition flex items-center gap-1">
                        {{ __('All Tickets') }} &rarr;
                    </a>
                    <a href="{{ route('public-support.create') }}" target="_blank" class="hover:text-purple-800 transition flex items-center gap-1">
                        {{ __('Support Portal') }} &rarr;
                    </a>
                </div>
            </div>

        </div>

        <!-- Row 2: Dark Navy Executive Realtime Pulse Banner -->
        <div class="rounded-2xl p-6 text-white shadow-lg border" style="background-color: #131b31 !important; border-color: rgba(51, 65, 85, 0.7) !important; color: #ffffff !important;">
            
            <!-- Banner Header -->
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
                <div>
                    <div class="flex items-center gap-1.5 text-[10px] font-semibold uppercase tracking-wider text-slate-400 font-mono">
                        <svg class="w-3.5 h-3.5 text-slate-400" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8l-6-6zm2 16H8v-2h8v2zm0-4H8v-2h8v2zm-3-5V3.5L18.5 9H13z"/>
                        </svg>
                        <span>{{ __('EXECUTIVE COMPLETE VIEW') }}</span>
                    </div>
                    <h2 class="text-xl font-bold text-white tracking-tight mt-1">
                        {{ __('Daily Realtime Pulse & Operational Summary') }}
                    </h2>
                </div>

                <!-- Right Action Buttons & Exact Date Badge -->
                <div class="flex flex-wrap items-center gap-3">
                    <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-lg text-slate-300 text-xs font-medium border" style="background-color: #1a233d !important; border-color: rgba(71, 85, 105, 0.6) !important;">
                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span>Wednesday, Oct 15, 2026</span>
                    </div>

                    <a href="{{ route('reports.index') }}" 
                       class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold text-white shadow-sm transition hover:opacity-95" style="background-color: #4f46e5 !important;">
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20">
                            <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/>
                        </svg>
                        <span>{{ __('Full Reports & Analytics') }}</span>
                        <span>&rarr;</span>
                    </a>
                </div>
            </div>

            <!-- Subtle Full-Width Horizontal Divider -->
            <div class="h-px my-5 w-full" style="background-color: rgba(51, 65, 85, 0.7) !important;"></div>

            <!-- Banner 5 Metric Pulse Columns with Vertical Dividers -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 lg:gap-0" style="color: #ffffff !important;">
                
                <!-- Pulse 1: New Leads Today -->
                <div class="px-0 lg:px-4 first:lg:pl-0 last:lg:pr-0 flex flex-col justify-between" style="border-right: 1px solid rgba(51, 65, 85, 0.7);">
                    <div>
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 border" style="background-color: #1c2646 !important; border-color: rgba(71, 85, 105, 0.5) !important;">
                                <svg class="w-4 h-4 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <span class="text-xs text-slate-400 block font-normal truncate">{{ __('New Leads Today') }}</span>
                                <div class="text-2xl font-black text-white mt-0.5">
                                    {{ $summary['sales']['new_leads_today'] ?? 0 }}
                                </div>
                                <div class="flex items-center justify-between gap-1 mt-1">
                                    <span class="text-[11px] font-semibold text-emerald-400 flex items-center gap-0.5 whitespace-nowrap">
                                        &uarr; {{ $cardMetrics['trends']['leads'] ?? 0 }}% <span class="text-slate-400 font-normal">vs yesterday</span>
                                    </span>
                                    <!-- 6 Micro Bars (Blue) -->
                                    <div class="flex items-end gap-[3px] h-3.5 shrink-0 ml-1">
                                        <span class="w-[3px] rounded-full" style="height: 30%; background-color: #3b82f6 !important;"></span>
                                        <span class="w-[3px] rounded-full" style="height: 45%; background-color: #3b82f6 !important;"></span>
                                        <span class="w-[3px] rounded-full" style="height: 60%; background-color: #3b82f6 !important;"></span>
                                        <span class="w-[3px] rounded-full" style="height: 80%; background-color: #3b82f6 !important;"></span>
                                        <span class="w-[3px] rounded-full" style="height: 50%; background-color: #3b82f6 !important;"></span>
                                        <span class="w-[3px] rounded-full" style="height: 100%; background-color: #60a5fa !important;"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="pt-2.5 mt-2.5" style="border-top: 1px solid rgba(51, 65, 85, 0.6);">
                        <span class="text-[11px] text-slate-400 block font-normal">
                            {{ __('In Follow-up') }}: {{ $summary['sales']['leads_in_follow_up'] ?? 0 }}
                        </span>
                    </div>
                </div>

                <!-- Pulse 2: Running Projects -->
                <div class="px-0 lg:px-4 first:lg:pl-0 last:lg:pr-0 flex flex-col justify-between" style="border-right: 1px solid rgba(51, 65, 85, 0.7);">
                    <div>
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 border" style="background-color: #1c2646 !important; border-color: rgba(71, 85, 105, 0.5) !important;">
                                <svg class="w-4 h-4 text-cyan-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <span class="text-xs text-slate-400 block font-normal truncate">{{ __('Running Projects') }}</span>
                                <div class="text-2xl font-black text-white mt-0.5">
                                    {{ $summary['projects']['running_projects'] ?? 0 }}
                                </div>
                                <div class="flex items-center justify-between gap-1 mt-1">
                                    <span class="text-[11px] font-semibold text-emerald-400 flex items-center gap-0.5 whitespace-nowrap">
                                        &uarr; {{ $cardMetrics['trends']['projects'] ?? 0 }}% <span class="text-slate-400 font-normal">vs yesterday</span>
                                    </span>
                                    <!-- 6 Micro Bars (Cyan) -->
                                    <div class="flex items-end gap-[3px] h-3.5 shrink-0 ml-1">
                                        <span class="w-[3px] rounded-full" style="height: 35%; background-color: #0284c7 !important;"></span>
                                        <span class="w-[3px] rounded-full" style="height: 50%; background-color: #0284c7 !important;"></span>
                                        <span class="w-[3px] rounded-full" style="height: 65%; background-color: #0284c7 !important;"></span>
                                        <span class="w-[3px] rounded-full" style="height: 80%; background-color: #0284c7 !important;"></span>
                                        <span class="w-[3px] rounded-full" style="height: 60%; background-color: #0284c7 !important;"></span>
                                        <span class="w-[3px] rounded-full" style="height: 100%; background-color: #38bdf8 !important;"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="pt-2.5 mt-2.5" style="border-top: 1px solid rgba(51, 65, 85, 0.6);">
                        <span class="text-[11px] block font-medium" style="color: #10b981 !important;">
                            {{ __('Delayed') }}: {{ $summary['projects']['delayed_projects_count'] ?? 0 }}
                        </span>
                    </div>
                </div>

                <!-- Pulse 3: Video Production -->
                <div class="px-0 lg:px-4 first:lg:pl-0 last:lg:pr-0 flex flex-col justify-between" style="border-right: 1px solid rgba(51, 65, 85, 0.7);">
                    <div>
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 border" style="background-color: #1c2646 !important; border-color: rgba(71, 85, 105, 0.5) !important;">
                                <svg class="w-4 h-4 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <span class="text-xs text-slate-400 block font-normal truncate">{{ __('Video Production') }}</span>
                                <div class="text-2xl font-black text-white mt-0.5">
                                    {{ number_format($summary['production']['total_seconds_edited'] ?? 0) }}s
                                </div>
                                <div class="flex items-center justify-between gap-1 mt-1">
                                    <span class="text-[11px] font-semibold text-emerald-400 flex items-center gap-0.5 whitespace-nowrap">
                                        &uarr; {{ $cardMetrics['trends']['video'] ?? 0 }}% <span class="text-slate-400 font-normal">vs yesterday</span>
                                    </span>
                                    <!-- 6 Micro Bars (Purple/Indigo) -->
                                    <div class="flex items-end gap-[3px] h-3.5 shrink-0 ml-1">
                                        <span class="w-[3px] rounded-full" style="height: 40%; background-color: #6366f1 !important;"></span>
                                        <span class="w-[3px] rounded-full" style="height: 55%; background-color: #6366f1 !important;"></span>
                                        <span class="w-[3px] rounded-full" style="height: 70%; background-color: #6366f1 !important;"></span>
                                        <span class="w-[3px] rounded-full" style="height: 50%; background-color: #6366f1 !important;"></span>
                                        <span class="w-[3px] rounded-full" style="height: 85%; background-color: #6366f1 !important;"></span>
                                        <span class="w-[3px] rounded-full" style="height: 100%; background-color: #818cf8 !important;"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="pt-2.5 mt-2.5" style="border-top: 1px solid rgba(51, 65, 85, 0.6);">
                        <span class="text-[11px] text-slate-400 block font-normal">
                            {{ $summary['production']['completed_videos'] ?? 0 }} {{ __('Videos Completed') }}
                        </span>
                    </div>
                </div>

                <!-- Pulse 4: Monthly Net Profit -->
                <div class="px-0 lg:px-4 first:lg:pl-0 last:lg:pr-0 flex flex-col justify-between" style="border-right: 1px solid rgba(51, 65, 85, 0.7);">
                    <div>
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 border" style="background-color: #1c2646 !important; border-color: rgba(71, 85, 105, 0.5) !important;">
                                <svg class="w-4 h-4 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <span class="text-xs text-slate-400 block font-normal truncate">{{ __('Monthly Net Profit') }}</span>
                                <div class="text-2xl font-black mt-0.5" style="color: #00e396 !important;">
                                    + {{ __('BDT') }} {{ number_format($summary['finance']['net_profit_month'] ?? 0, 0) }}
                                </div>
                                <div class="flex items-center justify-between gap-1 mt-1">
                                    <span class="text-[11px] font-semibold text-emerald-400 flex items-center gap-0.5 whitespace-nowrap">
                                        &uarr; {{ $cardMetrics['trends']['profit'] ?? 0 }}% <span class="text-slate-400 font-normal">vs last month</span>
                                    </span>
                                    <!-- 6 Micro Bars (Emerald) -->
                                    <div class="flex items-end gap-[3px] h-3.5 shrink-0 ml-1">
                                        <span class="w-[3px] rounded-full" style="height: 30%; background-color: #10b981 !important;"></span>
                                        <span class="w-[3px] rounded-full" style="height: 50%; background-color: #10b981 !important;"></span>
                                        <span class="w-[3px] rounded-full" style="height: 70%; background-color: #10b981 !important;"></span>
                                        <span class="w-[3px] rounded-full" style="height: 60%; background-color: #10b981 !important;"></span>
                                        <span class="w-[3px] rounded-full" style="height: 85%; background-color: #10b981 !important;"></span>
                                        <span class="w-[3px] rounded-full" style="height: 100%; background-color: #34d399 !important;"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="pt-2.5 mt-2.5" style="border-top: 1px solid rgba(51, 65, 85, 0.6);">
                        <span class="text-[11px] text-slate-400 block font-normal">
                            {{ __('Outflow') }}: {{ __('BDT') }} {{ number_format($summary['finance']['total_outflow_month'] ?? 0, 0) }}
                        </span>
                    </div>
                </div>

                <!-- Pulse 5: Pending Support Tickets -->
                <div class="px-0 lg:px-4 first:lg:pl-0 last:lg:pr-0 flex flex-col justify-between">
                    <div>
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 border" style="background-color: #1c2646 !important; border-color: rgba(71, 85, 105, 0.5) !important;">
                                <svg class="w-4 h-4 text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <span class="text-xs text-slate-400 block font-normal truncate">{{ __('Pending Support Tickets') }}</span>
                                <div class="text-2xl font-black text-white mt-0.5">
                                    {{ $summary['support']['pending_tickets'] ?? 0 }}
                                </div>
                                <div class="flex items-center justify-between gap-1 mt-1">
                                    <span class="text-[11px] font-semibold text-emerald-400 flex items-center gap-0.5 whitespace-nowrap">
                                        &uarr; {{ $cardMetrics['trends']['tickets'] ?? 0 }}% <span class="text-slate-400 font-normal">vs yesterday</span>
                                    </span>
                                    <!-- 6 Micro Bars (Purple/Violet) -->
                                    <div class="flex items-end gap-[3px] h-3.5 shrink-0 ml-1">
                                        <span class="w-[3px] rounded-full" style="height: 35%; background-color: #a855f7 !important;"></span>
                                        <span class="w-[3px] rounded-full" style="height: 60%; background-color: #a855f7 !important;"></span>
                                        <span class="w-[3px] rounded-full" style="height: 80%; background-color: #a855f7 !important;"></span>
                                        <span class="w-[3px] rounded-full" style="height: 95%; background-color: #a855f7 !important;"></span>
                                        <span class="w-[3px] rounded-full" style="height: 70%; background-color: #a855f7 !important;"></span>
                                        <span class="w-[3px] rounded-full" style="height: 100%; background-color: #c084fc !important;"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="pt-2.5 mt-2.5" style="border-top: 1px solid rgba(51, 65, 85, 0.6);">
                        <span class="text-[11px] text-slate-400 block font-normal">
                            {{ __('Rework') }}: {{ $summary['support']['rework_tickets'] ?? 0 }}
                        </span>
                    </div>
                </div>

            </div>

        </div>

        <!-- Row 3: Quick Workflow Actions & Project Control -->
        <div class="bg-white rounded-2xl shadow-2xs border border-slate-200/80 p-6">
            
            <!-- Section Header -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2 mb-5">
                <div class="flex items-center gap-2.5">
                    <span class="text-indigo-600">
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                            <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/>
                        </svg>
                    </span>
                    <h3 class="font-extrabold text-base sm:text-lg text-slate-900 tracking-tight">
                        {{ __('Quick Workflow Actions & Project Control') }}
                    </h3>
                </div>
                <p class="text-xs text-slate-400">
                    {{ __('Access core modules quickly to manage your business operations.') }}
                </p>
            </div>

            <!-- 6 Module Action Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                
                <!-- Action 1: Order Management -->
                <a href="{{ route('orders.index') }}" 
                   class="p-4 rounded-2xl border border-slate-100 bg-[#f8f9fc] hover:border-indigo-300 hover:shadow-md transition flex flex-col justify-between group">
                    <div>
                        <div class="w-10 h-10 rounded-xl bg-purple-50 text-indigo-600 flex items-center justify-center group-hover:scale-105 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                            </svg>
                        </div>
                        <h4 class="font-extrabold text-slate-900 group-hover:text-indigo-600 transition text-sm mt-3">
                            {{ __('Order Management') }}
                        </h4>
                        <p class="text-xs text-slate-500 mt-1 leading-snug">
                            {{ __('New orders, dues and sales invoices') }}
                        </p>
                    </div>
                    <div class="w-7 h-7 rounded-full bg-white shadow-2xs border border-slate-100 flex items-center justify-center text-slate-400 group-hover:text-indigo-600 group-hover:border-indigo-300 transition mt-4 ml-auto">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                    </div>
                </a>

                <!-- Action 2: Project Directory -->
                <a href="{{ route('projects.index') }}" 
                   class="p-4 rounded-2xl border border-slate-100 bg-[#f4f9fd] hover:border-sky-300 hover:shadow-md transition flex flex-col justify-between group">
                    <div>
                        <div class="w-10 h-10 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center group-hover:scale-105 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                            </svg>
                        </div>
                        <h4 class="font-extrabold text-slate-900 group-hover:text-sky-600 transition text-sm mt-3">
                            {{ __('Project Directory') }}
                        </h4>
                        <p class="text-xs text-slate-500 mt-1 leading-snug">
                            {{ __('Progress, deadlines and project tracking') }}
                        </p>
                    </div>
                    <div class="w-7 h-7 rounded-full bg-white shadow-2xs border border-slate-100 flex items-center justify-center text-slate-400 group-hover:text-sky-600 group-hover:border-sky-300 transition mt-4 ml-auto">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                    </div>
                </a>

                <!-- Action 3: Task Kanban Board -->
                <a href="{{ route('tasks.kanban') }}" 
                   class="p-4 rounded-2xl border border-slate-100 bg-[#fefaf3] hover:border-amber-300 hover:shadow-md transition flex flex-col justify-between group">
                    <div>
                        <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center group-hover:scale-105 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                            </svg>
                        </div>
                        <h4 class="font-extrabold text-slate-900 group-hover:text-amber-600 transition text-sm mt-3">
                            {{ __('Task Kanban Board') }}
                        </h4>
                        <p class="text-xs text-slate-500 mt-1 leading-snug">
                            {{ __('6-stage lifecycle and drag-and-drop') }}
                        </p>
                    </div>
                    <div class="w-7 h-7 rounded-full bg-white shadow-2xs border border-slate-100 flex items-center justify-center text-slate-400 group-hover:text-amber-600 group-hover:border-amber-300 transition mt-4 ml-auto">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                    </div>
                </a>

                <!-- Action 4: Video Billing -->
                <a href="{{ route('video-billings.index') }}" 
                   class="p-4 rounded-2xl border border-slate-100 bg-[#f2fbf7] hover:border-emerald-300 hover:shadow-md transition flex flex-col justify-between group">
                    <div>
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center group-hover:scale-105 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <h4 class="font-extrabold text-slate-900 group-hover:text-emerald-600 transition text-sm mt-3">
                            {{ __('Video Billing') }}
                        </h4>
                        <p class="text-xs text-slate-500 mt-1 leading-snug">
                            {{ __('Per-second rates and billing records') }}
                        </p>
                    </div>
                    <div class="w-7 h-7 rounded-full bg-white shadow-2xs border border-slate-100 flex items-center justify-center text-slate-400 group-hover:text-emerald-600 group-hover:border-emerald-300 transition mt-4 ml-auto">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                    </div>
                </a>

                <!-- Action 5: Payout Vouchers -->
                <a href="{{ route('payouts.index') }}" 
                   class="p-4 rounded-2xl border border-slate-100 bg-[#fdf4f5] hover:border-rose-300 hover:shadow-md transition flex flex-col justify-between group">
                    <div>
                        <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center group-hover:scale-105 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                        <h4 class="font-extrabold text-slate-900 group-hover:text-rose-600 transition text-sm mt-3">
                            {{ __('Payout Vouchers') }}
                        </h4>
                        <p class="text-xs text-slate-500 mt-1 leading-snug">
                            {{ __('Salary and compensation receipts') }}
                        </p>
                    </div>
                    <div class="w-7 h-7 rounded-full bg-white shadow-2xs border border-slate-100 flex items-center justify-center text-slate-400 group-hover:text-rose-600 group-hover:border-rose-300 transition mt-4 ml-auto">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                    </div>
                </a>

                <!-- Action 6: System Settings -->
                <a href="{{ route('settings.index') }}" 
                   class="p-4 rounded-2xl border border-slate-100 bg-[#f7f4fd] hover:border-violet-300 hover:shadow-md transition flex flex-col justify-between group">
                    <div>
                        <div class="w-10 h-10 rounded-xl bg-violet-50 text-violet-600 flex items-center justify-center group-hover:scale-105 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <h4 class="font-extrabold text-slate-900 group-hover:text-violet-600 transition text-sm mt-3">
                            {{ __('System Settings') }}
                        </h4>
                        <p class="text-xs text-slate-500 mt-1 leading-snug">
                            {{ __('Service categories, roles and permissions') }}
                        </p>
                    </div>
                    <div class="w-7 h-7 rounded-full bg-white shadow-2xs border border-slate-100 flex items-center justify-center text-slate-400 group-hover:text-violet-600 group-hover:border-violet-300 transition mt-4 ml-auto">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                    </div>
                </a>

            </div>

        </div>

    </div>
</x-app-layout>
