<x-portal-layout>
    <x-slot name="title">{{ __('Dashboard') }}</x-slot>

    <!-- Welcome Hero Banner -->
    <div class="rounded-3xl p-6 sm:p-8 text-white shadow-xl relative overflow-hidden" 
         style="background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 50%, #312e81 100%);">
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-white/10 text-indigo-200 border border-white/10 mb-3">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    {{ __('Client Self-Service Portal') }}
                </span>
                <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white">
                    {{ __('Welcome back') }}, {{ $customer->name }}!
                </h1>
                <p class="text-xs sm:text-sm text-slate-300 mt-1 max-w-xl">
                    {{ $customer->company_name ?? __('Client Portal') }} &bull; {{ __('Here is an overview of your active projects, billings, and support requests.') }}
                </p>
            </div>

            <!-- Quick Action Buttons -->
            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('portal.tickets.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold bg-gradient-to-r from-indigo-500 to-blue-600 hover:from-indigo-600 hover:to-blue-700 text-white shadow-md transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>{{ __('Submit New Ticket') }}</span>
                </a>
                <a href="{{ route('portal.invoices.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold bg-white/10 hover:bg-white/20 text-white border border-white/20 shadow-xs transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/></svg>
                    <span>{{ __('View Invoices') }}</span>
                </a>
            </div>
        </div>
    </div>

    <!-- 4 Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mt-6">
        
        <!-- Active Projects -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-2xs hover:shadow-md transition">
            <div class="flex items-center justify-between text-indigo-600">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500">{{ __('Active Projects') }}</span>
                <div class="w-9 h-9 rounded-xl bg-indigo-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
            </div>
            <div class="text-3xl font-black text-slate-900 mt-2">{{ $stats['active_projects'] }}</div>
            <div class="text-xs text-slate-500 mt-1">
                <span>{{ $stats['total_projects'] }} {{ __('Total contracted projects') }}</span>
            </div>
        </div>

        <!-- Total Contracted Projects -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-2xs hover:shadow-md transition">
            <div class="flex items-center justify-between text-blue-600">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500">{{ __('Total Projects') }}</span>
                <div class="w-9 h-9 rounded-xl bg-blue-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                </div>
            </div>
            <div class="text-3xl font-black text-slate-900 mt-2">{{ $stats['total_projects'] }}</div>
            <div class="text-xs text-slate-500 mt-1">
                <span><a href="{{ route('portal.projects.index') }}" class="text-indigo-600 font-semibold hover:underline">{{ __('View all projects') }} &rarr;</a></span>
            </div>
        </div>

        <!-- Outstanding Invoices -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-2xs hover:shadow-md transition">
            <div class="flex items-center justify-between text-amber-600">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500">{{ __('Pending Due') }}</span>
                <div class="w-9 h-9 rounded-xl bg-amber-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <div class="text-2xl sm:text-3xl font-black text-slate-900 mt-2">
                {{ __('BDT') }} {{ number_format($stats['total_due'], 2) }}
            </div>
            <div class="text-xs text-slate-500 mt-1">
                <span>{{ $stats['unpaid_invoices_count'] }} {{ __('unpaid/partial invoices') }}</span>
            </div>
        </div>

        <!-- Open Support Tickets -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-2xs hover:shadow-md transition">
            <div class="flex items-center justify-between text-rose-600">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500">{{ __('Open Tickets') }}</span>
                <div class="w-9 h-9 rounded-xl bg-rose-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </div>
            </div>
            <div class="text-3xl font-black text-slate-900 mt-2">{{ $stats['open_tickets'] }}</div>
            <div class="text-xs text-slate-500 mt-1">
                <span><a href="{{ route('portal.tickets.index') }}" class="text-rose-600 font-semibold hover:underline">{{ __('Track tickets') }} &rarr;</a></span>
            </div>
        </div>

    </div>

    <!-- Main Content 2-Column Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">
        
        <!-- Left 2 Cols: My Active Projects -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-2xs overflow-hidden">
                <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-bold text-slate-900">{{ __('My Projects') }}</h3>
                        <p class="text-xs text-slate-500">{{ __('Track development progress, milestones & deliverables') }}</p>
                    </div>
                    <a href="{{ route('portal.projects.index') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 transition">
                        {{ __('View All') }} &rarr;
                    </a>
                </div>

                <div class="divide-y divide-slate-100">
                    @forelse($projects as $project)
                        <div class="p-5 hover:bg-slate-50/50 transition">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('portal.projects.show', $project) }}" class="font-bold text-sm text-slate-900 hover:text-indigo-600 transition">
                                            {{ $project->name }}
                                        </a>
                                        <span class="px-2 py-0.5 rounded text-[10px] font-mono font-bold bg-slate-100 text-slate-700">
                                            {{ $project->project_code }}
                                        </span>
                                    </div>
                                    <div class="text-xs text-slate-500 mt-1 flex items-center gap-3">
                                        <span>{{ $project->category ?? 'Web & Software' }}</span>
                                        @if($project->deadline)
                                            <span>&bull;</span>
                                            <span>{{ __('Deadline') }}: <strong class="text-slate-800">{{ $project->deadline->format('d M, Y') }}</strong></span>
                                        @endif
                                    </div>
                                </div>

                                <div class="flex items-center gap-3">
                                    <span class="px-2.5 py-1 rounded-full text-xs font-bold 
                                        @if($project->status === 'Completed') bg-emerald-50 text-emerald-700 border border-emerald-200
                                        @elseif($project->status === 'In Progress') bg-blue-50 text-blue-700 border border-blue-200
                                        @elseif($project->status === 'Testing' || $project->status === 'QA/Review') bg-purple-50 text-purple-700 border border-purple-200
                                        @else bg-slate-100 text-slate-700 border border-slate-200 @endif">
                                        {{ $project->status }}
                                    </span>
                                    <a href="{{ route('portal.projects.show', $project) }}" class="p-2 rounded-lg bg-slate-100 text-slate-600 hover:bg-indigo-50 hover:text-indigo-600 transition" title="View details">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                    </a>
                                </div>
                            </div>

                            <!-- Progress Bar -->
                            <div class="mt-3.5">
                                <div class="flex justify-between text-xs font-semibold text-slate-600 mb-1">
                                    <span>{{ __('Overall Progress') }}</span>
                                    <span class="text-indigo-600 font-bold">{{ $project->progress_percent }}%</span>
                                </div>
                                <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                                    <div class="h-full rounded-full bg-gradient-to-r from-indigo-500 to-blue-500 transition-all duration-500" style="width: {{ $project->progress_percent }}%"></div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center text-xs text-slate-400">
                            {{ __('No projects found for your account.') }}
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Right 1 Col: Recent Invoices & Support Tickets -->
        <div class="space-y-6">
            
            <!-- Invoices Summary -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-2xs overflow-hidden">
                <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">{{ __('Recent Invoices') }}</h3>
                    <a href="{{ route('portal.invoices.index') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 transition">
                        {{ __('All') }} &rarr;
                    </a>
                </div>

                <div class="divide-y divide-slate-100">
                    @forelse($invoices as $inv)
                        <div class="p-4 flex items-center justify-between gap-3 text-xs">
                            <div>
                                <a href="{{ route('portal.invoices.show', $inv) }}" class="font-bold text-slate-900 hover:text-indigo-600 transition block">
                                    {{ $inv->invoice_no }}
                                </a>
                                <span class="text-slate-400 text-[11px]">{{ $inv->issue_date ? $inv->issue_date->format('d M, Y') : '' }}</span>
                            </div>
                            <div class="text-right">
                                <div class="font-black text-slate-900">{{ __('BDT') }} {{ number_format($inv->total_amount, 2) }}</div>
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold 
                                    {{ $inv->status === 'Paid' ? 'bg-emerald-50 text-emerald-700' : ($inv->status === 'Partially Paid' ? 'bg-amber-50 text-amber-700' : 'bg-rose-50 text-rose-700') }}">
                                    {{ $inv->status }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="p-6 text-center text-xs text-slate-400">
                            {{ __('No invoices generated yet.') }}
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Support Tickets -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-2xs overflow-hidden">
                <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">{{ __('Support Tickets') }}</h3>
                    <a href="{{ route('portal.tickets.create') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 transition flex items-center gap-1">
                        + {{ __('New') }}
                    </a>
                </div>

                <div class="divide-y divide-slate-100">
                    @forelse($tickets as $ticket)
                        <div class="p-4 text-xs space-y-1.5">
                            <div class="flex items-center justify-between">
                                <span class="font-mono text-[10px] font-bold text-slate-500">{{ $ticket->ticket_no }}</span>
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold 
                                    @if($ticket->status === 'Resolved' || $ticket->status === 'Closed') bg-emerald-50 text-emerald-700
                                    @elseif($ticket->status === 'New') bg-blue-50 text-blue-700
                                    @else bg-amber-50 text-amber-700 @endif">
                                    {{ $ticket->status }}
                                </span>
                            </div>
                            <a href="{{ route('portal.tickets.show', $ticket) }}" class="font-bold text-slate-900 hover:text-indigo-600 block line-clamp-1">
                                {{ $ticket->subject }}
                            </a>
                        </div>
                    @empty
                        <div class="p-6 text-center text-xs text-slate-400">
                            {{ __('No support tickets submitted.') }}
                        </div>
                    @endforelse
                </div>
            </div>

        </div>

    </div>

</x-portal-layout>

