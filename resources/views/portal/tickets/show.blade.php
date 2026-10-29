<x-portal-layout>
    <x-slot name="title">{{ $ticket->ticket_no }} — {{ $ticket->subject }}</x-slot>

    <div class="max-w-4xl mx-auto space-y-6">
        <!-- Back Link & Header -->
        <div class="flex items-center gap-4">
            <a href="{{ route('portal.tickets.index') }}" class="p-2 rounded-xl bg-white border border-slate-200 text-slate-500 hover:text-slate-900 shadow-2xs transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2 flex-wrap">
                    <span class="px-2.5 py-0.5 rounded text-[11px] font-mono font-bold bg-indigo-50 text-indigo-700 border border-indigo-100">
                        {{ $ticket->ticket_no }}
                    </span>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold 
                        @if($ticket->status === 'Resolved' || $ticket->status === 'Closed') bg-emerald-50 text-emerald-700 border border-emerald-200
                        @elseif($ticket->status === 'New') bg-blue-50 text-blue-700 border border-blue-200
                        @elseif($ticket->status === 'Reopened') bg-rose-50 text-rose-700 border border-rose-200
                        @else bg-amber-50 text-amber-700 border border-amber-200 @endif">
                        {{ $ticket->status }}
                    </span>
                    <span class="px-2 py-0.5 rounded text-[10px] font-bold 
                        @if($ticket->priority === 'Critical') bg-rose-100 text-rose-800
                        @elseif($ticket->priority === 'High') bg-amber-100 text-amber-800
                        @else bg-slate-100 text-slate-700 @endif">
                        {{ $ticket->priority }}
                    </span>
                </div>
                <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight mt-1 truncate">
                    {{ $ticket->subject }}
                </h1>
            </div>
        </div>

        <!-- Ticket Details Card -->
        <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-2xs space-y-4">
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 pb-4 border-b border-slate-100 text-xs">
                <div>
                    <span class="text-slate-400 block font-semibold">{{ __('Category') }}</span>
                    <span class="font-bold text-slate-800 text-sm mt-0.5">{{ $ticket->issue_type }}</span>
                </div>
                <div>
                    <span class="text-slate-400 block font-semibold">{{ __('Related Project') }}</span>
                    <span class="font-bold text-slate-800 text-sm mt-0.5">{{ $ticket->project->name ?? __('None') }}</span>
                </div>
                <div>
                    <span class="text-slate-400 block font-semibold">{{ __('Submitted On') }}</span>
                    <span class="font-bold text-slate-800 text-sm mt-0.5">{{ $ticket->created_at->format('d M, Y h:i A') }}</span>
                </div>
                <div>
                    <span class="text-slate-400 block font-semibold">{{ __('Last Activity') }}</span>
                    <span class="font-bold text-slate-800 text-sm mt-0.5">{{ $ticket->updated_at->diffForHumans() }}</span>
                </div>
            </div>

            <!-- Original Problem Description -->
            <div class="text-xs sm:text-sm text-slate-700 leading-relaxed bg-slate-50/70 p-4 rounded-2xl border border-slate-100">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block mb-1.5">{{ __('Original Request:') }}</span>
                <p class="whitespace-pre-line">{{ $ticket->description }}</p>
                @if($ticket->attachment_path)
                    <div class="mt-3 pt-3 border-t border-slate-200 flex items-center gap-2">
                        <span class="text-xs text-slate-500 font-semibold">{{ __('Attachment:') }}</span>
                        <a href="{{ asset('storage/' . $ticket->attachment_path) }}" target="_blank" class="px-2.5 py-1 bg-white border border-slate-200 hover:border-indigo-400 rounded-lg text-xs font-bold text-indigo-600 transition flex items-center gap-1">
                            📎 {{ __('Download Attachment') }} ↗
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Two-Way Conversation Thread -->
        <div class="space-y-4">
            <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
                <span>{{ __('Communication History') }}</span>
                <span class="px-2 py-0.5 rounded-full text-xs font-bold bg-slate-100 text-slate-600">
                    {{ $ticket->replies->count() }} {{ __('messages') }}
                </span>
            </h3>

            @forelse($ticket->replies as $reply)
                @if($reply->is_internal)
                    @continue {{-- Internal staff notes are hidden from client --}}
                @endif

                <div class="bg-white p-5 rounded-2xl border {{ in_array(strtolower($reply->sender_type), ['client', 'customer']) ? 'border-slate-200 bg-white' : 'border-indigo-100 bg-indigo-50/20' }} shadow-2xs space-y-2">
                    <div class="flex items-center justify-between text-xs pb-2 border-b border-slate-100">
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 rounded-full flex items-center justify-center font-bold text-xs {{ in_array(strtolower($reply->sender_type), ['client', 'customer']) ? 'bg-slate-200 text-slate-700' : 'bg-indigo-600 text-white' }}">
                                {{ in_array(strtolower($reply->sender_type), ['client', 'customer']) ? 'C' : 'S' }}
                            </div>
                            <span class="font-bold text-slate-900">
                                {{ in_array(strtolower($reply->sender_type), ['client', 'customer']) ? __('You (Client)') : ($reply->user->name ?? __('Support Staff')) }}
                            </span>
                            @if(!in_array(strtolower($reply->sender_type), ['client', 'customer']))
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-indigo-100 text-indigo-800">
                                    {{ __('Projukti Sheba Team') }}
                                </span>
                            @endif
                        </div>
                        <span class="text-slate-400 text-[11px]">{{ $reply->created_at->diffForHumans() }}</span>
                    </div>

                    <div class="text-xs sm:text-sm text-slate-700 leading-relaxed whitespace-pre-line pt-1">
                        {{ $reply->message }}
                    </div>

                    @if($reply->attachment_path)
                        <div class="pt-2">
                            <a href="{{ asset('storage/' . $reply->attachment_path) }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1 bg-slate-100 hover:bg-slate-200 rounded-lg text-xs font-semibold text-slate-700 transition">
                                📎 {{ __('Download Attachment') }} ↗
                            </a>
                        </div>
                    @endif
                </div>
            @empty
                <div class="p-6 bg-white rounded-2xl border border-slate-200 text-center text-xs text-slate-400">
                    {{ __('No replies posted yet. Our support team has received your ticket.') }}
                </div>
            @endforelse
        </div>

        <!-- Reply Form -->
        <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-2xs space-y-4">
            <h3 class="text-sm font-bold uppercase tracking-wider text-slate-800 flex items-center gap-2">
                <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
                <span>{{ __('Post Reply or Additional Information') }}</span>
            </h3>

            <form action="{{ route('portal.tickets.reply', $ticket) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <textarea name="message" 
                              rows="4" 
                              required 
                              placeholder="{{ __('Type your reply, updates or questions here...') }}" 
                              class="w-full text-xs sm:text-sm rounded-2xl border border-slate-200 p-3.5 text-slate-900 focus:ring-2 focus:ring-indigo-500"></textarea>
                    @error('message')
                        <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pt-1">
                    <div>
                        <input type="file" 
                               name="attachment" 
                               class="text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-slate-100 hover:file:bg-slate-200">
                    </div>
                    <button type="submit" class="px-5 py-2.5 rounded-xl text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 shadow-xs transition shrink-0">
                        {{ __('Post Reply') }} &rarr;
                    </button>
                </div>
            </form>
        </div>

    </div>
</x-portal-layout>
