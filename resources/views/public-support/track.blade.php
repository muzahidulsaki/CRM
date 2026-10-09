<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Track Support Ticket | Projukti Sheba</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 font-sans text-slate-800 antialiased min-h-screen flex flex-col justify-between">
    <!-- Header -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-30">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 py-4 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <a href="{{ route('public-support.create') }}" class="w-10 h-10 rounded-xl bg-blue-600 flex items-center justify-center text-white font-bold text-xl shadow-md">
                    PS
                </a>
                <div>
                    <h1 class="text-lg font-bold text-slate-900 leading-tight">Projukti Sheba</h1>
                    <p class="text-xs text-slate-500 font-medium">Support Ticket Tracker</p>
                </div>
            </div>
            <div>
                <a href="{{ route('public-support.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-sm font-semibold rounded-lg text-white hover:bg-blue-700 transition shadow-sm">
                    + New Support Request
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-4xl mx-auto px-4 sm:px-6 py-10 w-full flex-1">
        <!-- Tracker Lookup Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 mb-8">
            <h2 class="text-lg font-bold text-slate-900 mb-2">Track Your Ticket Status</h2>
            <p class="text-xs text-slate-500 mb-4">Enter your 14-digit Ticket ID (e.g. SUP-{{ date('Y') }}-00001) to view status updates and chat with engineers.</p>
            
            <form action="{{ route('public-support.track') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
                <input type="text" name="ticket_no" value="{{ $searchedTicketNo ?? '' }}" required
                    class="flex-1 px-4 py-2.5 text-sm uppercase font-mono tracking-wider border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                    placeholder="Enter Ticket ID (e.g. SUP-{{ date('Y') }}-00001)">
                <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm rounded-lg shadow-sm transition">
                    Search Ticket
                </button>
            </form>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-50 border border-emerald-300 rounded-xl text-emerald-800 text-sm flex items-start">
                <svg class="w-5 h-5 mr-3 text-emerald-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <div>
                    <h4 class="font-bold">Success</h4>
                    <p class="mt-1">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if($error)
            <div class="mb-6 p-4 bg-rose-50 border border-rose-300 rounded-xl text-rose-800 text-sm flex items-center">
                <svg class="w-5 h-5 mr-3 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                <span>{{ $error }}</span>
            </div>
        @endif

        @if($ticket)
            <!-- Ticket Found Details Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden mb-8">
                <!-- Card Header -->
                <div class="p-6 bg-slate-50 border-b border-slate-200 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <div class="flex items-center space-x-2 mb-2">
                            <span class="font-mono text-xs font-bold px-2.5 py-1 bg-slate-200 text-slate-800 rounded">
                                {{ $ticket->ticket_no }}
                            </span>
                            <span class="text-xs font-semibold px-2.5 py-1 rounded-full border {{ $ticket->priority_badge_color }}">
                                {{ $ticket->priority }} Priority
                            </span>
                            <span class="text-xs font-semibold px-2.5 py-1 rounded-full border {{ $ticket->status_badge_color }}">
                                Status: {{ $ticket->status }}
                            </span>
                        </div>
                        <h2 class="text-xl font-extrabold text-slate-900 leading-snug">{{ $ticket->subject }}</h2>
                        <p class="text-xs text-slate-500 mt-1">Submitted on {{ $ticket->created_at->format('d M, Y \a\t h:i A') }}</p>
                    </div>

                    <div class="text-right flex flex-col md:items-end">
                        <span class="text-xs text-slate-500">Category</span>
                        <span class="text-sm font-bold text-slate-800">{{ $ticket->issue_type }}</span>
                    </div>
                </div>

                <!-- Info Grid -->
                <div class="p-6 grid grid-cols-1 sm:grid-cols-3 gap-4 border-b border-slate-100 bg-white">
                    <div>
                        <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Client Name</span>
                        <p class="text-sm font-semibold text-slate-800">{{ $ticket->contact_name }}</p>
                        @if($ticket->company_name)
                            <p class="text-xs text-slate-500">{{ $ticket->company_name }}</p>
                        @endif
                    </div>

                    <div>
                        <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Website / Service</span>
                        @if($ticket->service_url)
                            <p class="text-sm font-semibold text-blue-600 truncate">
                                <a href="{{ $ticket->service_url }}" target="_blank" class="hover:underline">{{ $ticket->service_url }}</a>
                            </p>
                        @else
                            <p class="text-xs text-slate-400 italic">Not specified</p>
                        @endif
                    </div>

                    <div>
                        <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Assigned Engineer</span>
                        <p class="text-sm font-semibold text-slate-800">
                            {{ $ticket->assignedDeveloper?->name ?? 'Under Review by Dispatcher' }}
                        </p>
                    </div>
                </div>

                <!-- Original Description -->
                <div class="p-6 border-b border-slate-100">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Original Problem Description</h3>
                    <div class="bg-slate-50 p-4 rounded-xl text-sm text-slate-700 whitespace-pre-line leading-relaxed border border-slate-100">
                        {{ $ticket->description }}
                    </div>
                    @if($ticket->attachment_path)
                        <div class="mt-3 flex items-center">
                            <svg class="w-4 h-4 text-slate-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                            <a href="{{ Storage::url($ticket->attachment_path) }}" target="_blank" class="text-xs font-bold text-blue-600 hover:underline">
                                View Initial Attached File / Screenshot
                            </a>
                        </div>
                    @endif
                </div>

                @if($ticket->resolution_notes)
                    <div class="p-6 bg-emerald-50 border-b border-emerald-100">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-emerald-800 mb-1 flex items-center">
                            <svg class="w-4 h-4 mr-1 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Official Resolution Note
                        </h3>
                        <p class="text-sm text-emerald-900 leading-relaxed font-medium">{{ $ticket->resolution_notes }}</p>
                    </div>
                @endif

                <!-- Conversation Thread -->
                <div class="p-6 bg-slate-50/50">
                    <h3 class="text-base font-bold text-slate-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                        Updates & Discussion ({{ $ticket->publicReplies->count() }})
                    </h3>

                    <div class="space-y-4 mb-6">
                        @forelse($ticket->publicReplies as $reply)
                            <div class="p-4 rounded-xl border text-sm {{ $reply->sender_type === 'Client' ? 'bg-blue-50 border-blue-200' : 'bg-white border-slate-200 shadow-xs' }}">
                                <div class="flex items-center justify-between mb-2">
                                    <div class="flex items-center space-x-2">
                                        <span class="font-bold text-slate-900">{{ $reply->sender_name }}</span>
                                        <span class="text-xs px-2 py-0.5 rounded-full {{ $reply->sender_type === 'Client' ? 'bg-blue-200 text-blue-800' : 'bg-emerald-100 text-emerald-800 font-semibold' }}">
                                            {{ $reply->sender_type === 'Client' ? 'Client' : 'Technical Support' }}
                                        </span>
                                    </div>
                                    <span class="text-xs text-slate-400">{{ $reply->created_at->diffForHumans() }}</span>
                                </div>
                                <div class="text-slate-700 whitespace-pre-line leading-relaxed">
                                    {{ $reply->message }}
                                </div>
                                @if($reply->attachment_path)
                                    <div class="mt-2 pt-2 border-t border-slate-200/50 flex items-center">
                                        <svg class="w-3.5 h-3.5 text-slate-400 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                                        <a href="{{ Storage::url($reply->attachment_path) }}" target="_blank" class="text-xs font-semibold text-blue-600 hover:underline">
                                            Attachment Download
                                        </a>
                                    </div>
                                @endif
                            </div>
                        @empty
                            <p class="text-xs text-slate-400 italic">No additional replies yet.</p>
                        @endforelse
                    </div>

                    <!-- Client Reply Box -->
                    <div class="bg-white p-5 rounded-xl border border-slate-200">
                        <h4 class="text-sm font-bold text-slate-800 mb-2">Send a Message / Add Follow-up Details</h4>
                        <form action="{{ route('public-support.reply', $ticket->ticket_no) }}" method="POST" enctype="multipart/form-data" class="space-y-3">
                            @csrf
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-bold text-slate-600 mb-1">Your Name <span class="text-rose-500">*</span></label>
                                    <input type="text" name="sender_name" value="{{ old('sender_name', $ticket->contact_name) }}" required
                                        class="w-full px-3 py-1.5 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-600 mb-1">Attach File / Screenshot (Optional)</label>
                                    <input type="file" name="attachment" class="w-full text-xs text-slate-500 file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:text-xs file:bg-slate-100">
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-600 mb-1">Message <span class="text-rose-500">*</span></label>
                                <textarea name="message" rows="3" required
                                    class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none"
                                    placeholder="Write your response, additional test results, or feedback here..."></textarea>
                            </div>
                            <div class="text-right">
                                <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-lg shadow transition">
                                    Send Reply
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 py-6 text-center text-xs text-slate-500">
        <p>&copy; {{ date('Y') }} Projukti Sheba. All rights reserved. Secure Support & Helpdesk Platform.</p>
    </footer>
</body>
</html>
