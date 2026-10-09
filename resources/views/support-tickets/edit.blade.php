<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-slate-800 leading-tight">
                    Edit Support Ticket: {{ $ticket->ticket_no }}
                </h2>
                <p class="text-xs text-slate-500 mt-1">Update ticket details, assignees, or priority.</p>
            </div>
            <a href="{{ route('support-tickets.show', $ticket) }}" class="px-3.5 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-lg transition">
                &larr; View Ticket
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sm:p-8">
                
                @if($errors->any())
                    <div class="mb-6 p-4 bg-rose-50 border border-rose-300 rounded-xl text-rose-800 text-sm">
                        <h4 class="font-bold mb-1">Please fix the following issues:</h4>
                        <ul class="list-disc list-inside space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('support-tickets.update', $ticket) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Client Identification -->
                    <div>
                        <h3 class="text-sm font-bold uppercase tracking-wider text-slate-400 mb-3 border-b border-slate-100 pb-2">
                            Client & Account Link
                        </h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Customer</label>
                                <select name="customer_id" class="w-full text-xs px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                                    <option value="">-- Non-linked or New Client --</option>
                                    @foreach($customers as $c)
                                        <option value="{{ $c->id }}" {{ old('customer_id', $ticket->customer_id) == $c->id ? 'selected' : '' }}>
                                            {{ $c->name }} ({{ $c->company_name ?? 'Individual' }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Related Project</label>
                                <select name="project_id" class="w-full text-xs px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                                    <option value="">-- Select Project if applicable --</option>
                                    @foreach($projects as $p)
                                        <option value="{{ $p->id }}" {{ old('project_id', $ticket->project_id) == $p->id ? 'selected' : '' }}>
                                            {{ $p->name }} ({{ $p->project_code }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Contact Name <span class="text-rose-500">*</span></label>
                                <input type="text" name="contact_name" value="{{ old('contact_name', $ticket->contact_name) }}" required
                                    class="w-full text-xs px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Company Name</label>
                                <input type="text" name="company_name" value="{{ old('company_name', $ticket->company_name) }}"
                                    class="w-full text-xs px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Phone Number</label>
                                <input type="text" name="phone" value="{{ old('phone', $ticket->phone) }}"
                                    class="w-full text-xs px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Email Address</label>
                                <input type="email" name="email" value="{{ old('email', $ticket->email) }}"
                                    class="w-full text-xs px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                            </div>

                            <div class="sm:col-span-2">
                                <label class="block text-xs font-bold text-slate-700 mb-1">Website or Software URL</label>
                                <input type="url" name="service_url" value="{{ old('service_url', $ticket->service_url) }}"
                                    class="w-full text-xs px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                            </div>
                        </div>
                    </div>

                    <!-- Ticket Details -->
                    <div>
                        <h3 class="text-sm font-bold uppercase tracking-wider text-slate-400 mb-3 border-b border-slate-100 pb-2">
                            Issue Specifications & Status
                        </h3>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Issue Category <span class="text-rose-500">*</span></label>
                                <select name="issue_type" required class="w-full text-xs px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                                    @foreach($issueTypes as $it)
                                        <option value="{{ $it }}" {{ old('issue_type', $ticket->issue_type) === $it ? 'selected' : '' }}>{{ $it }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Priority <span class="text-rose-500">*</span></label>
                                <select name="priority" required class="w-full text-xs px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                                    @foreach($priorities as $pr)
                                        <option value="{{ $pr }}" {{ old('priority', $ticket->priority) === $pr ? 'selected' : '' }}>{{ $pr }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Status <span class="text-rose-500">*</span></label>
                                <select name="status" required class="w-full text-xs px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                                    @foreach($statuses as $st)
                                        <option value="{{ $st }}" {{ old('status', $ticket->status) === $st ? 'selected' : '' }}>{{ $st }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block text-xs font-bold text-slate-700 mb-1">Subject <span class="text-rose-500">*</span></label>
                            <input type="text" name="subject" value="{{ old('subject', $ticket->subject) }}" required
                                class="w-full text-xs px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                        </div>

                        <div class="mb-4">
                            <label class="block text-xs font-bold text-slate-700 mb-1">Problem Description <span class="text-rose-500">*</span></label>
                            <textarea name="description" rows="4" required
                                class="w-full text-xs px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">{{ old('description', $ticket->description) }}</textarea>
                        </div>
                    </div>

                    <!-- Assignment, Hours & Resolution -->
                    <div>
                        <h3 class="text-sm font-bold uppercase tracking-wider text-slate-400 mb-3 border-b border-slate-100 pb-2">
                            Assignments, Hours & Resolution
                        </h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Assign Developer</label>
                                <select name="assigned_developer_id" class="w-full text-xs px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                                    <option value="">-- No Developer Assigned --</option>
                                    @foreach($users as $u)
                                        <option value="{{ $u->id }}" {{ old('assigned_developer_id', $ticket->assigned_developer_id) == $u->id ? 'selected' : '' }}>
                                            {{ $u->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Assign Manager</label>
                                <select name="assigned_manager_id" class="w-full text-xs px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                                    <option value="">-- No Manager Assigned --</option>
                                    @foreach($users as $u)
                                        <option value="{{ $u->id }}" {{ old('assigned_manager_id', $ticket->assigned_manager_id) == $u->id ? 'selected' : '' }}>
                                            {{ $u->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Estimated Hours</label>
                                <input type="number" step="0.25" min="0" name="estimated_hours" value="{{ old('estimated_hours', $ticket->estimated_hours) }}"
                                    class="w-full text-xs px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Actual Work Hours</label>
                                <input type="number" step="0.25" min="0" name="actual_hours" value="{{ old('actual_hours', $ticket->actual_hours) }}"
                                    class="w-full text-xs px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block text-xs font-bold text-slate-700 mb-1">Resolution Summary</label>
                            <textarea name="resolution_notes" rows="2"
                                class="w-full text-xs px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">{{ old('resolution_notes', $ticket->resolution_notes) }}</textarea>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Internal Staff Notes</label>
                            <textarea name="internal_notes" rows="2"
                                class="w-full text-xs px-3 py-2 border border-amber-200 bg-amber-50/30 rounded-lg focus:ring-2 focus:ring-amber-500 outline-none">{{ old('internal_notes', $ticket->internal_notes) }}</textarea>
                        </div>
                    </div>

                    <!-- Submit -->
                    <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-3">
                        <a href="{{ route('support-tickets.show', $ticket) }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold text-xs rounded-lg transition">
                            Cancel
                        </a>
                        <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-lg shadow transition">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
