<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-slate-800 leading-tight">
                    Support & Helpdesk Tickets
                </h2>
                <p class="text-xs text-slate-500 mt-1">Manage client issues, technical bugs, software customization, and resolution life-cycles.</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('public-support.create') }}" target="_blank" class="inline-flex items-center px-4 py-2 border border-slate-300 text-xs font-semibold rounded-lg text-slate-700 bg-white hover:bg-slate-50 transition shadow-xs">
                    <svg class="w-3.5 h-3.5 mr-1.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    Public Support Portal
                </a>
                <a href="{{ route('support-tickets.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-lg shadow-sm transition">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    New Support Ticket
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
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-xs">
                    <div class="flex items-center justify-between text-slate-500 mb-1">
                        <span class="text-xs font-semibold">Total Tickets</span>
                        <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
                    </div>
                    <div class="text-2xl font-black text-slate-900">{{ $metrics['total_tickets'] }}</div>
                </div>

                <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-xs">
                    <div class="flex items-center justify-between text-slate-500 mb-1">
                        <span class="text-xs font-semibold">Open / In Progress</span>
                        <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div class="text-2xl font-black text-amber-600">{{ $metrics['open_tickets'] }}</div>
                </div>

                <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-xs">
                    <div class="flex items-center justify-between text-slate-500 mb-1">
                        <span class="text-xs font-semibold">Critical Priority</span>
                        <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>
                    <div class="text-2xl font-black text-rose-600">{{ $metrics['critical_tickets'] }}</div>
                </div>

                <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-xs">
                    <div class="flex items-center justify-between text-slate-500 mb-1">
                        <span class="text-xs font-semibold">Resolved / Closed</span>
                        <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div class="text-2xl font-black text-emerald-600">{{ $metrics['resolved_tickets'] }}</div>
                </div>

                <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-xs col-span-2 md:col-span-1">
                    <div class="flex items-center justify-between text-slate-500 mb-1">
                        <span class="text-xs font-semibold">New Unassigned</span>
                        <svg class="w-4 h-4 text-cyan-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                    </div>
                    <div class="text-2xl font-black text-cyan-600">{{ $metrics['pending_assignment'] }}</div>
                </div>
            </div>

            <!-- Filter & Search Toolbar -->
            <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-xs">
                <form action="{{ route('support-tickets.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-3">
                    <div>
                        <input type="text" name="search" value="{{ request('search') }}"
                            class="w-full text-xs px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none"
                            placeholder="Search Ticket, Subject, Client...">
                    </div>

                    <div>
                        <select name="status" class="w-full text-xs px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                            <option value="">-- All Statuses --</option>
                            @foreach($statuses as $st)
                                <option value="{{ $st }}" {{ request('status') === $st ? 'selected' : '' }}>{{ $st }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <select name="priority" class="w-full text-xs px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                            <option value="">-- All Priorities --</option>
                            @foreach($priorities as $pr)
                                <option value="{{ $pr }}" {{ request('priority') === $pr ? 'selected' : '' }}>{{ $pr }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <select name="issue_type" class="w-full text-xs px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                            <option value="">-- All Issue Types --</option>
                            @foreach($issueTypes as $it)
                                <option value="{{ $it }}" {{ request('issue_type') === $it ? 'selected' : '' }}>{{ $it }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex items-center space-x-2">
                        <button type="submit" class="flex-1 py-2 bg-slate-800 hover:bg-slate-900 text-white text-xs font-bold rounded-lg transition">
                            Filter
                        </button>
                        @if(request()->anyFilled(['search', 'status', 'priority', 'issue_type', 'assigned_developer_id']))
                            <a href="{{ route('support-tickets.index') }}" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-bold rounded-lg transition">
                                Reset
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Tickets Table -->
            <div class="bg-white rounded-xl border border-slate-200 shadow-xs overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 text-left text-xs">
                        <thead class="bg-slate-50 text-slate-500 font-bold uppercase tracking-wider">
                            <tr>
                                <th class="px-5 py-3">Ticket ID</th>
                                <th class="px-5 py-3">Client & Company</th>
                                <th class="px-5 py-3">Subject & Category</th>
                                <th class="px-5 py-3">Priority</th>
                                <th class="px-5 py-3">Status</th>
                                <th class="px-5 py-3">Assigned Dev</th>
                                <th class="px-5 py-3">Created</th>
                                <th class="px-5 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                            @forelse($tickets as $ticket)
                                <tr class="hover:bg-slate-50/80 transition">
                                    <td class="px-5 py-3.5 whitespace-nowrap">
                                        <a href="{{ route('support-tickets.show', $ticket) }}" class="font-mono font-bold text-blue-600 hover:underline">
                                            {{ $ticket->ticket_no }}
                                        </a>
                                    </td>
                                    <td class="px-5 py-3.5">
                                        <div class="font-bold text-slate-900">{{ $ticket->contact_name }}</div>
                                        <div class="text-[11px] text-slate-400">
                                            {{ $ticket->company_name ?? ($ticket->customer?->company_name ?? 'Individual') }}
                                        </div>
                                    </td>
                                    <td class="px-5 py-3.5 max-w-xs">
                                        <a href="{{ route('support-tickets.show', $ticket) }}" class="font-semibold text-slate-900 hover:text-blue-600 line-clamp-1">
                                            {{ $ticket->subject }}
                                        </a>
                                        <span class="inline-block px-1.5 py-0.5 bg-slate-100 text-slate-600 text-[10px] rounded mt-0.5">
                                            {{ $ticket->issue_type }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-3.5 whitespace-nowrap">
                                        <span class="px-2 py-0.5 rounded-full text-[11px] font-bold border {{ $ticket->priority_badge_color }}">
                                            {{ $ticket->priority }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-3.5 whitespace-nowrap">
                                        <span class="px-2 py-0.5 rounded-full text-[11px] font-bold border {{ $ticket->status_badge_color }}">
                                            {{ $ticket->status }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-3.5 whitespace-nowrap text-slate-600">
                                        @if($ticket->assignedDeveloper)
                                            <span class="font-semibold text-slate-800">{{ $ticket->assignedDeveloper->name }}</span>
                                        @else
                                            <span class="text-slate-400 italic">Unassigned</span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-3.5 whitespace-nowrap text-slate-400 text-[11px]">
                                        {{ $ticket->created_at->format('d M, Y') }}
                                    </td>
                                    <td class="px-5 py-3.5 whitespace-nowrap text-right space-x-2">
                                        <a href="{{ route('support-tickets.show', $ticket) }}" class="inline-flex items-center px-2.5 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded transition">
                                            Manage
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-5 py-8 text-center text-slate-400">
                                        No support tickets found matching your filter criteria.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($tickets->hasPages())
                    <div class="p-4 border-t border-slate-200">
                        {{ $tickets->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
