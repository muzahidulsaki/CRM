<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center space-x-3">
                <span class="font-mono text-sm font-black px-3 py-1 bg-slate-200 text-slate-800 rounded-lg">
                    {{ $ticket->ticket_no }}
                </span>
                <div>
                    <h2 class="font-bold text-xl text-slate-900 leading-tight">
                        {{ $ticket->subject }}
                    </h2>
                    <p class="text-xs text-slate-500">
                        Category: <span class="font-semibold text-slate-700">{{ $ticket->issue_type }}</span> &bull; 
                        Logged {{ $ticket->created_at->format('d M, Y \a\t h:i A') }} ({{ $ticket->created_at->diffForHumans() }})
                    </p>
                </div>
            </div>

            <div class="flex items-center space-x-2">
                <a href="{{ route('public-support.track', $ticket->ticket_no) }}" target="_blank" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-lg transition inline-flex items-center">
                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    Client View
                </a>
                <a href="{{ route('support-tickets.edit', $ticket) }}" class="px-3 py-1.5 bg-amber-500 hover:bg-amber-600 text-white text-xs font-bold rounded-lg transition">
                    Edit Details
                </a>
                <a href="{{ route('support-tickets.index') }}" class="px-3 py-1.5 bg-slate-800 hover:bg-slate-900 text-white text-xs font-bold rounded-lg transition">
                    &larr; All Tickets
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

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- Left Column (2 Cols): Ticket Narrative, Description, Resolution & Conversation Thread -->
                <div class="lg:col-span-2 space-y-6">

                    <!-- Status & Quick Action Bar -->
                    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs flex flex-wrap items-center justify-between gap-4">
                        <div class="flex items-center space-x-3">
                            <span class="px-3 py-1 rounded-full text-xs font-bold border {{ $ticket->priority_badge_color }}">
                                {{ $ticket->priority }} Priority
                            </span>
                            <span class="px-3 py-1 rounded-full text-xs font-bold border {{ $ticket->status_badge_color }}">
                                {{ $ticket->status }}
                            </span>
                        </div>

                        <!-- Quick Status Form -->
                        <form action="{{ route('support-tickets.status', $ticket) }}" method="POST" class="flex items-center space-x-2">
                            @csrf
                            @method('PATCH')
                            <select name="status" class="text-xs px-2.5 py-1.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 font-medium">
                                @foreach($statuses as $st)
                                    <option value="{{ $st }}" {{ $ticket->status === $st ? 'selected' : '' }}>
                                        Mark as {{ $st }}
                                    </option>
                                @endforeach
                            </select>
                            <button type="submit" class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-lg transition">
                                Update
                            </button>
                        </form>
                    </div>

                    <!-- Original Description Card -->
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-6">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">Initial Problem Report</h3>
                        <div class="text-sm text-slate-700 whitespace-pre-line leading-relaxed bg-slate-50 p-4 rounded-xl border border-slate-100">
                            {{ $ticket->description }}
                        </div>

                        @if($ticket->attachment_path)
                            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center">
                                <svg class="w-4 h-4 text-slate-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                                <a href="{{ Storage::url($ticket->attachment_path) }}" target="_blank" class="text-xs font-bold text-blue-600 hover:underline">
                                    View Attached File / Screenshot
                                </a>
                            </div>
                        @endif
                    </div>

                    <!-- Resolution Card (If Resolved/Closed) -->
                    @if($ticket->resolution_notes || in_array($ticket->status, ['Resolved', 'Closed', 'Client Confirmed']))
                        <div class="bg-emerald-50 rounded-2xl border border-emerald-200 p-6">
                            <h3 class="text-xs font-bold uppercase tracking-wider text-emerald-800 mb-2 flex items-center">
                                <svg class="w-4 h-4 mr-1.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Technical Resolution
                            </h3>
                            <p class="text-sm text-emerald-950 font-medium whitespace-pre-line leading-relaxed mb-3">
                                {{ $ticket->resolution_notes ?: 'Marked resolved without explicit resolution note.' }}
                            </p>
                            <div class="text-[11px] text-emerald-700 flex items-center space-x-4">
                                @if($ticket->resolved_at)
                                    <span>Resolved: {{ $ticket->resolved_at->format('d M, Y h:i A') }}</span>
                                @endif
                                @if($ticket->actual_hours)
                                    <span>Actual Work: <strong>{{ $ticket->actual_hours }} hrs</strong></span>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Conversation & Audit Thread -->
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-6 space-y-4">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                            <h3 class="font-bold text-base text-slate-900 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                Ticket Timeline & Communication ({{ $ticket->replies->count() }})
                            </h3>
                            <span class="text-xs text-slate-400">Internal notes marked in yellow</span>
                        </div>

                        <!-- Thread Messages -->
                        <div class="space-y-4">
                            @forelse($ticket->replies as $reply)
                                <div class="p-4 rounded-xl border text-sm {{ $reply->is_internal ? 'bg-amber-50/70 border-amber-200' : ($reply->sender_type === 'Client' ? 'bg-blue-50/70 border-blue-200' : ($reply->sender_type === 'System' ? 'bg-slate-50 border-slate-200 text-slate-600' : 'bg-white border-slate-200 shadow-xs')) }}">
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="flex items-center space-x-2">
                                            <span class="font-bold text-slate-900">{{ $reply->sender_name }}</span>
                                            
                                            @if($reply->is_internal)
                                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-200 text-amber-900 flex items-center">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                                    INTERNAL NOTE
                                                </span>
                                            @else
                                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $reply->sender_type === 'Client' ? 'bg-blue-200 text-blue-900' : ($reply->sender_type === 'Developer' ? 'bg-purple-100 text-purple-800' : 'bg-emerald-100 text-emerald-800') }}">
                                                    {{ $reply->sender_type }}
                                                </span>
                                            @endif
                                        </div>
                                        <span class="text-xs text-slate-400">{{ $reply->created_at->diffForHumans() }}</span>
                                    </div>

                                    <div class="text-slate-700 whitespace-pre-line leading-relaxed">
                                        {{ $reply->message }}
                                    </div>

                                    @if($reply->attachment_path)
                                        <div class="mt-2 pt-2 border-t border-slate-200/60 flex items-center">
                                            <svg class="w-3.5 h-3.5 text-slate-400 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                                            <a href="{{ Storage::url($reply->attachment_path) }}" target="_blank" class="text-xs font-bold text-blue-600 hover:underline">
                                                Download Attached File
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            @empty
                                <p class="text-xs text-slate-400 italic">No activity logs or replies yet.</p>
                            @endforelse
                        </div>

                        <!-- Post Reply / Internal Note Form -->
                        <div class="pt-4 border-t border-slate-100">
                            <form action="{{ route('support-tickets.reply', $ticket) }}" method="POST" enctype="multipart/form-data" class="space-y-3">
                                @csrf
                                <div class="flex items-center justify-between">
                                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600">
                                        Post Message / Work Update
                                    </label>
                                    <label class="inline-flex items-center text-xs font-bold text-amber-700 cursor-pointer">
                                        <input type="checkbox" name="is_internal" value="1" class="rounded border-amber-300 text-amber-600 focus:ring-amber-500 mr-1.5">
                                        Internal Staff Note (Hide from Client)
                                    </label>
                                </div>

                                <textarea name="message" rows="3" required
                                    class="w-full text-xs px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none"
                                    placeholder="Type your response to client or technical worklog note here..."></textarea>

                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                                    <input type="file" name="attachment" class="text-xs text-slate-500 file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:text-xs file:bg-slate-100">
                                    
                                    <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-lg shadow-sm transition">
                                        Post Update
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>

                <!-- Right Column (1 Col): Client Profile, Related Project, Assign Staff & Resolution Form -->
                <div class="space-y-6">

                    <!-- Staff Assignment Card -->
                    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">Staff Assignment</h3>
                        
                        <form action="{{ route('support-tickets.assign', $ticket) }}" method="POST" class="space-y-3">
                            @csrf
                            @method('PATCH')

                            <div>
                                <label class="block text-xs font-medium text-slate-700 mb-1">Assigned Developer</label>
                                <select name="assigned_developer_id" class="w-full text-xs px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                                    <option value="">-- No Developer Assigned --</option>
                                    @foreach($users as $u)
                                        <option value="{{ $u->id }}" {{ $ticket->assigned_developer_id == $u->id ? 'selected' : '' }}>
                                            {{ $u->name }} ({{ $u->role?->name ?? 'Staff' }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-slate-700 mb-1">Assigned Project/Support Manager</label>
                                <select name="assigned_manager_id" class="w-full text-xs px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                                    <option value="">-- No Manager Assigned --</option>
                                    @foreach($users as $u)
                                        <option value="{{ $u->id }}" {{ $ticket->assigned_manager_id == $u->id ? 'selected' : '' }}>
                                            {{ $u->name }} ({{ $u->role?->name ?? 'Staff' }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <button type="submit" class="w-full py-2 bg-slate-800 hover:bg-slate-900 text-white font-bold text-xs rounded-lg transition">
                                Save Assignment
                            </button>
                        </form>
                    </div>

                    <!-- Client 360 Information Card -->
                    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs space-y-3">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-2">
                            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">Client Information</h3>
                            @if($ticket->customer)
                                <a href="{{ route('customers.show', $ticket->customer) }}" class="text-[11px] font-bold text-blue-600 hover:underline">
                                    Customer 360° &rarr;
                                </a>
                            @endif
                        </div>

                        <div>
                            <span class="text-[10px] uppercase font-bold text-slate-400">Name</span>
                            <p class="text-sm font-bold text-slate-900">{{ $ticket->contact_name }}</p>
                        </div>

                        <div>
                            <span class="text-[10px] uppercase font-bold text-slate-400">Company</span>
                            <p class="text-xs font-semibold text-slate-700">{{ $ticket->company_name ?? ($ticket->customer?->company_name ?? 'N/A') }}</p>
                        </div>

                        <div>
                            <span class="text-[10px] uppercase font-bold text-slate-400">Phone</span>
                            <p class="text-xs font-mono font-semibold text-slate-800">{{ $ticket->phone ?? 'N/A' }}</p>
                        </div>

                        <div>
                            <span class="text-[10px] uppercase font-bold text-slate-400">Email</span>
                            <p class="text-xs text-slate-700">{{ $ticket->email ?? 'N/A' }}</p>
                        </div>

                        @if($ticket->service_url)
                            <div>
                                <span class="text-[10px] uppercase font-bold text-slate-400">Software / Website URL</span>
                                <p class="text-xs text-blue-600 truncate">
                                    <a href="{{ $ticket->service_url }}" target="_blank" class="hover:underline">{{ $ticket->service_url }}</a>
                                </p>
                            </div>
                        @endif
                    </div>

                    <!-- Related Project / Order -->
                    @if($ticket->project || $ticket->order)
                        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs space-y-3">
                            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100 pb-2">
                                Related Operations
                            </h3>

                            @if($ticket->project)
                                <div>
                                    <span class="text-[10px] uppercase font-bold text-slate-400">Project</span>
                                    <p class="text-xs font-bold text-slate-800">
                                        <a href="{{ route('projects.show', $ticket->project) }}" class="text-blue-600 hover:underline">
                                            {{ $ticket->project->name }} ({{ $ticket->project->project_code }})
                                        </a>
                                    </p>
                                </div>
                            @endif

                            @if($ticket->order)
                                <div>
                                    <span class="text-[10px] uppercase font-bold text-slate-400">Order</span>
                                    <p class="text-xs font-bold text-slate-800">
                                        <a href="{{ route('orders.show', $ticket->order) }}" class="text-blue-600 hover:underline">
                                            {{ $ticket->order->order_code }}
                                        </a>
                                    </p>
                                </div>
                            @endif
                        </div>
                    @endif

                    <!-- Mark as Resolved Form Card -->
                    @if(!in_array($ticket->status, ['Resolved', 'Closed']))
                        <div class="bg-emerald-50/70 p-5 rounded-2xl border border-emerald-200">
                            <h3 class="text-xs font-bold uppercase tracking-wider text-emerald-900 mb-2 flex items-center">
                                <svg class="w-4 h-4 mr-1 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                Resolve & Close Ticket
                            </h3>
                            <form action="{{ route('support-tickets.status', $ticket) }}" method="POST" class="space-y-3">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="Resolved">

                                <div>
                                    <label class="block text-xs font-medium text-emerald-900 mb-1">Resolution Summary</label>
                                    <textarea name="resolution_notes" rows="3" required
                                        class="w-full text-xs px-3 py-2 border border-emerald-300 rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none bg-white"
                                        placeholder="Describe technical changes made, bugs fixed, or guidance provided..."></textarea>
                                </div>

                                <div>
                                    <label class="block text-xs font-medium text-emerald-900 mb-1">Actual Work Hours Spent</label>
                                    <input type="number" step="0.25" min="0" name="actual_hours" value="{{ $ticket->estimated_hours }}"
                                        class="w-full text-xs px-3 py-2 border border-emerald-300 rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none bg-white"
                                        placeholder="e.g. 1.5">
                                </div>

                                <button type="submit" class="w-full py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-lg transition shadow-xs">
                                    Mark as Resolved
                                </button>
                            </form>
                        </div>
                    @endif

                </div>

            </div>

        </div>
    </div>
</x-app-layout>
