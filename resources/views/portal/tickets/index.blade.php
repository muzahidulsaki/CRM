<x-portal-layout>
    <x-slot name="title">{{ __('Support Tickets') }}</x-slot>

    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight">{{ __('Support & Issue Tickets') }}</h1>
                <p class="text-xs text-slate-500 mt-0.5">{{ __('Submit issues, track bug fixes and chat directly with our technical developers') }}</p>
            </div>

            <a href="{{ route('portal.tickets.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow-xs transition shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>{{ __('Submit New Ticket') }}</span>
            </a>
        </div>

        <!-- Tickets Table -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-2xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="bg-slate-50/75 border-b border-slate-100 text-slate-500 font-bold uppercase tracking-wider text-[11px]">
                            <th class="py-3.5 px-4">{{ __('Ticket No') }}</th>
                            <th class="py-3.5 px-4">{{ __('Subject') }}</th>
                            <th class="py-3.5 px-4">{{ __('Issue Category') }}</th>
                            <th class="py-3.5 px-4">{{ __('Priority') }}</th>
                            <th class="py-3.5 px-4">{{ __('Status') }}</th>
                            <th class="py-3.5 px-4">{{ __('Last Updated') }}</th>
                            <th class="py-3.5 px-4 text-right">{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($tickets as $ticket)
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="py-4 px-4 font-mono font-bold text-indigo-600">
                                    <a href="{{ route('portal.tickets.show', $ticket) }}" class="hover:underline">
                                        {{ $ticket->ticket_no }}
                                    </a>
                                </td>
                                <td class="py-4 px-4 font-bold text-slate-900 max-w-xs">
                                    <a href="{{ route('portal.tickets.show', $ticket) }}" class="hover:text-indigo-600 transition block truncate">
                                        {{ $ticket->subject }}
                                    </a>
                                </td>
                                <td class="py-4 px-4 text-slate-600">
                                    {{ $ticket->issue_type }}
                                </td>
                                <td class="py-4 px-4">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold 
                                        @if($ticket->priority === 'Critical') bg-rose-100 text-rose-800
                                        @elseif($ticket->priority === 'High') bg-amber-100 text-amber-800
                                        @elseif($ticket->priority === 'Medium') bg-blue-100 text-blue-800
                                        @else bg-slate-100 text-slate-700 @endif">
                                        {{ $ticket->priority }}
                                    </span>
                                </td>
                                <td class="py-4 px-4">
                                    <span class="px-2.5 py-1 rounded-full text-[11px] font-bold 
                                        @if($ticket->status === 'Resolved' || $ticket->status === 'Closed' || $ticket->status === 'Client Confirmed') bg-emerald-50 text-emerald-700 border border-emerald-200
                                        @elseif($ticket->status === 'New') bg-blue-50 text-blue-700 border border-blue-200
                                        @elseif($ticket->status === 'Reopened') bg-rose-50 text-rose-700 border border-rose-200
                                        @else bg-amber-50 text-amber-700 border border-amber-200 @endif">
                                        {{ $ticket->status }}
                                    </span>
                                </td>
                                <td class="py-4 px-4 text-slate-500 text-[11px]">
                                    {{ $ticket->updated_at->diffForHumans() }}
                                </td>
                                <td class="py-4 px-4 text-right">
                                    <a href="{{ route('portal.tickets.show', $ticket) }}" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-indigo-600 hover:text-white text-slate-700 text-xs font-bold transition">
                                        <span>{{ __('Conversation') }}</span> &rarr;
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-12 text-center text-xs text-slate-400">
                                    <p class="font-semibold text-sm text-slate-500">{{ __('No support tickets found.') }}</p>
                                    <p class="mt-1">{{ __('Need technical assistance or changes? Click "Submit New Ticket" to reach our team.') }}</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-slate-100">
                {{ $tickets->links() }}
            </div>
        </div>
    </div>
</x-portal-layout>

