<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <div class="flex items-center gap-2">
                    <span class="text-xs font-mono font-bold bg-indigo-100 text-indigo-700 px-2.5 py-1 rounded-lg">
                        {{ $project->project_code }}
                    </span>
                    <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                        {{ $project->name }}
                    </h2>
                    @php
                        $stClass = match($project->status) {
                            'In Development' => 'bg-blue-50 text-blue-700 border-blue-200',
                            'In QA' => 'bg-purple-50 text-purple-700 border-purple-200',
                            'Client Review' => 'bg-amber-50 text-amber-700 border-amber-200',
                            'Completed', 'Delivered' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                            'Delayed', 'Cancelled' => 'bg-rose-50 text-rose-700 border-rose-200',
                            default => 'bg-gray-50 text-gray-700 border-gray-200'
                        };
                    @endphp
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold border {{ $stClass }}">
                        {{ __($project->status) }}
                    </span>
                </div>
                <div class="flex items-center gap-2 text-xs text-gray-500 mt-1">
                    <span>{{ __('Client') }}: <strong class="text-gray-700">{{ $project->customer?->name }}</strong></span>
                    <span>•</span>
                    <span>{{ __('Category') }}: <strong class="text-gray-700">{{ __($project->category) }}</strong></span>
                    <span>•</span>
                    <span>{{ __('PM') }}: <strong class="text-gray-700">{{ $project->projectManager?->name ?? __('Unassigned') }}</strong></span>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('tasks.kanban', ['project_id' => $project->id]) }}" class="inline-flex items-center px-4 py-2 bg-indigo-50 text-indigo-700 border border-indigo-200 rounded-lg text-sm font-semibold hover:bg-indigo-100 transition">
                    <svg class="w-4 h-4 me-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/></svg>
                    {{ __('Kanban Board') }}
                </a>
                <a href="{{ route('projects.edit', $project) }}" class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm font-semibold hover:bg-gray-200 transition">
                    <svg class="w-4 h-4 me-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    {{ __('Edit') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Overall Progress & Key Stats -->
            <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm space-y-4">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('Project Progress') }}</div>
                        <div class="text-3xl font-black text-indigo-600 mt-1">{{ $project->progress_percent }}% {{ __('Completed') }}</div>
                        <div class="text-xs text-gray-400 mt-0.5">{{ __('Overall progress is automatically calculated based on task lifecycle') }}</div>
                    </div>

                    <!-- External links -->
                    <div class="flex items-center gap-2">
                        @if($project->git_repository)
                            <a href="{{ $project->git_repository }}" target="_blank" class="inline-flex items-center px-3 py-1.5 bg-gray-900 text-white rounded-lg text-xs font-semibold hover:bg-gray-800 transition">
                                <svg class="w-4 h-4 me-1.5" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" clip-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.53 1.032 1.53 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z"/></svg>
                                GitHub Repo
                            </a>
                        @endif
                        @if($project->live_url)
                            <a href="{{ $project->live_url }}" target="_blank" class="inline-flex items-center px-3 py-1.5 bg-emerald-600 text-white rounded-lg text-xs font-semibold hover:bg-emerald-700 transition">
                                <svg class="w-4 h-4 me-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                {{ __('Live Preview') }}
                            </a>
                        @endif
                        @if($project->order)
                            <a href="{{ route('orders.show', $project->order) }}" class="inline-flex items-center px-3 py-1.5 bg-gray-100 text-gray-700 rounded-lg text-xs font-semibold hover:bg-gray-200 transition">
                                {{ __('Order') }}: {{ $project->order->order_code }}
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Animated Progress Bar -->
                <div class="w-full bg-gray-100 rounded-full h-4 overflow-hidden border border-gray-200">
                    <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 h-4 rounded-full transition-all duration-700 flex items-center justify-end pr-2 text-[10px] text-white font-bold"
                         style="width: {{ max(5, $project->progress_percent) }}%">
                        @if($project->progress_percent >= 10)
                            {{ $project->progress_percent }}%
                        @endif
                    </div>
                </div>

                <!-- Task Metrics Counter Grid -->
                <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-7 gap-2 pt-2">
                    <div class="p-3 bg-gray-50 rounded-xl border border-gray-200 text-center">
                        <div class="text-[11px] font-semibold text-gray-500 uppercase">{{ __('Total Tasks') }}</div>
                        <div class="text-xl font-bold text-gray-900 mt-0.5">{{ $taskCounts['total'] }}</div>
                    </div>
                    <div class="p-3 bg-gray-50 rounded-xl border border-gray-200 text-center">
                        <div class="text-[11px] font-semibold text-gray-500 uppercase">{{ __('Pending') }}</div>
                        <div class="text-xl font-bold text-gray-700 mt-0.5">{{ $taskCounts['pending'] }}</div>
                    </div>
                    <div class="p-3 bg-blue-50 rounded-xl border border-blue-200 text-center">
                        <div class="text-[11px] font-semibold text-blue-600 uppercase">{{ __('Working') }}</div>
                        <div class="text-xl font-bold text-blue-700 mt-0.5">{{ $taskCounts['working'] }}</div>
                    </div>
                    <div class="p-3 bg-rose-50 rounded-xl border border-rose-200 text-center">
                        <div class="text-[11px] font-semibold text-rose-600 uppercase">{{ __('Reworking (Bug)') }}</div>
                        <div class="text-xl font-bold text-rose-700 mt-0.5">{{ $taskCounts['reworking'] }}</div>
                    </div>
                    <div class="p-3 bg-amber-50 rounded-xl border border-amber-200 text-center">
                        <div class="text-[11px] font-semibold text-amber-600 uppercase">{{ __('In QA Review') }}</div>
                        <div class="text-xl font-bold text-amber-700 mt-0.5">{{ $taskCounts['completed'] }}</div>
                    </div>
                    <div class="p-3 bg-purple-50 rounded-xl border border-purple-200 text-center">
                        <div class="text-[11px] font-semibold text-purple-600 uppercase">{{ __('QA Passed') }}</div>
                        <div class="text-xl font-bold text-purple-700 mt-0.5">{{ $taskCounts['checked'] }}</div>
                    </div>
                    <div class="p-3 bg-emerald-50 rounded-xl border border-emerald-200 text-center">
                        <div class="text-[11px] font-semibold text-emerald-600 uppercase">{{ __('Delivered') }}</div>
                        <div class="text-xl font-bold text-emerald-700 mt-0.5">{{ $taskCounts['delivered'] }}</div>
                    </div>
                </div>
            </div>

            <!-- Task Management Section -->
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="p-5 border-b border-gray-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 bg-gray-50/50">
                    <div>
                        <h3 class="font-bold text-lg text-gray-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                            {{ __('Project Task List & Actions') }}
                        </h3>
                        <p class="text-xs text-gray-500 mt-0.5">{{ __('Developer & designer task lifecycle (Pending &rarr; Working &rarr; QA Checked &rarr; Delivered)') }}</p>
                    </div>

                    <button type="button" onclick="document.getElementById('taskFormContainer').classList.toggle('hidden')"
                        class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg text-xs font-semibold hover:bg-indigo-700 shadow-sm transition">
                        <svg class="w-4 h-4 me-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        {{ __('Assign New Task') }}
                    </button>
                </div>

                <!-- Collapsible New Task Form -->
                <div id="taskFormContainer" class="hidden p-6 bg-indigo-50/30 border-b border-indigo-100">
                    <form method="POST" action="{{ route('tasks.store') }}" class="space-y-4">
                        @csrf
                        <input type="hidden" name="project_id" value="{{ $project->id }}">

                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                            <div class="sm:col-span-2">
                                <label class="block text-xs font-semibold text-gray-700 mb-1">{{ __('Task Title') }} <span class="text-rose-500">*</span></label>
                                <input type="text" name="title" required placeholder="{{ __('e.g. User Authentication & Role Permissions Setup') }}"
                                    class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">{{ __('Task Type') }} <span class="text-rose-500">*</span></label>
                                <select name="task_type" required class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="Development">{{ __('Development') }}</option>
                                    <option value="Graphics Design">{{ __('UI/UX & Graphics Design') }}</option>
                                    <option value="Video Editing">{{ __('Creative & Video Editing') }}</option>
                                    <option value="Marketing Campaign">{{ __('Marketing & Ads') }}</option>
                                    <option value="QA / Testing">{{ __('QA & Testing') }}</option>
                                    <option value="Bug Fix">{{ __('Bug Fix') }}</option>
                                    <option value="Other">{{ __('Other') }}</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">{{ __('Priority') }} <span class="text-rose-500">*</span></label>
                                <select name="priority" required class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="Low">{{ __('Low') }}</option>
                                    <option value="Medium" selected>{{ __('Medium') }}</option>
                                    <option value="High">{{ __('High') }}</option>
                                    <option value="Urgent">{{ __('Urgent') }}</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">{{ __('Assign Team Member') }}</label>
                                <select name="assigned_to" class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">{{ __('Select') }}</option>
                                    @foreach($teamMembers as $tm)
                                        <option value="{{ $tm->id }}">
                                            {{ $tm->name }} ({{ $tm->role->name ?? 'Staff' }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">{{ __('Deadline') }}</label>
                                <input type="date" name="deadline" value="{{ $project->deadline?->format('Y-m-d') }}"
                                    class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">{{ __('Estimated Hours') }}</label>
                                <input type="number" step="0.5" name="estimated_hours" placeholder="e.g. 8"
                                    class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            </div>

                            <div class="sm:col-span-2">
                                <label class="block text-xs font-semibold text-gray-700 mb-1">{{ __('Task Details & Instructions') }}</label>
                                <textarea name="description" rows="2" placeholder="{{ __('Details and instructions for completing the task...') }}"
                                    class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-2 pt-2">
                            <button type="button" onclick="document.getElementById('taskFormContainer').classList.add('hidden')"
                                class="px-4 py-2 bg-white text-gray-700 border border-gray-300 rounded-lg text-xs font-semibold hover:bg-gray-50 transition">
                                {{ __('Cancel') }}
                            </button>
                            <button type="submit" class="px-5 py-2 bg-indigo-600 text-white rounded-lg text-xs font-semibold hover:bg-indigo-700 transition">
                                {{ __('Save Task') }}
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Tasks Data Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-left text-sm">
                        <thead class="bg-gray-50 text-gray-500 font-semibold uppercase text-xs">
                            <tr>
                                <th class="py-3.5 px-4">{{ __('Task Code') }}</th>
                                <th class="py-3.5 px-4">{{ __('Title & Type') }}</th>
                                <th class="py-3.5 px-4">{{ __('Assigned Member') }}</th>
                                <th class="py-3.5 px-4">{{ __('Priority') }}</th>
                                <th class="py-3.5 px-4">{{ __('Status') }}</th>
                                <th class="py-3.5 px-4">{{ __('QA Status') }}</th>
                                <th class="py-3.5 px-4">{{ __('Deadline') }}</th>
                                <th class="py-3.5 px-4 text-right">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($project->tasks as $task)
                                <tr class="hover:bg-gray-50/60 transition">
                                    <td class="py-3.5 px-4 font-mono font-bold text-xs text-indigo-700">
                                        {{ $task->task_code }}
                                    </td>
                                    <td class="py-3.5 px-4">
                                        <a href="{{ route('tasks.show', $task) }}" class="font-bold text-gray-900 hover:text-indigo-600 block">
                                            {{ $task->title }}
                                        </a>
                                        <span class="text-xs text-gray-500">{{ __($task->task_type) }}</span>
                                    </td>
                                    <td class="py-3.5 px-4">
                                        @if($task->assignedUser)
                                            <div class="flex items-center gap-2">
                                                <div class="w-6 h-6 rounded-full bg-indigo-100 text-indigo-700 font-bold flex items-center justify-center text-xs">
                                                    {{ mb_substr($task->assignedUser->name, 0, 1) }}
                                                </div>
                                                <span class="text-xs text-gray-800 font-medium">{{ $task->assignedUser->name }}</span>
                                            </div>
                                        @else
                                            <span class="text-xs text-gray-400">{{ __('Unassigned') }}</span>
                                        @endif
                                    </td>
                                    <td class="py-3.5 px-4">
                                        @php
                                            $pClass = match($task->priority) {
                                                'Urgent' => 'bg-rose-100 text-rose-700',
                                                'High' => 'bg-amber-100 text-amber-700',
                                                'Medium' => 'bg-blue-100 text-blue-700',
                                                default => 'bg-gray-100 text-gray-700'
                                            };
                                        @endphp
                                        <span class="px-2 py-0.5 rounded text-[11px] font-semibold {{ $pClass }}">
                                            {{ __($task->priority) }}
                                        </span>
                                    </td>
                                    <td class="py-3.5 px-4">
                                        @php
                                            $taskStClass = match($task->status) {
                                                'Working' => 'bg-blue-50 text-blue-700 border-blue-200',
                                                'Reworking' => 'bg-rose-50 text-rose-700 border-rose-200',
                                                'Completed' => 'bg-amber-50 text-amber-700 border-amber-200',
                                                'Checked' => 'bg-purple-50 text-purple-700 border-purple-200',
                                                'Delivered' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                                default => 'bg-gray-50 text-gray-700 border-gray-200'
                                            };
                                        @endphp
                                        <span class="px-2 py-0.5 rounded-full text-xs font-semibold border {{ $taskStClass }}">
                                            {{ __($task->status) }}
                                        </span>
                                    </td>
                                    <td class="py-3.5 px-4">
                                        @if($task->qa_status === 'Passed')
                                            <span class="text-xs font-bold text-emerald-600 flex items-center gap-1">
                                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                                {{ __('Passed') }}
                                            </span>
                                        @elseif($task->qa_status === 'Failed')
                                            <span class="text-xs font-bold text-rose-600 flex items-center gap-1">
                                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                                                {{ __('Failed') }}
                                            </span>
                                        @else
                                            <span class="text-xs text-gray-400">{{ __('Pending') }}</span>
                                        @endif
                                    </td>
                                    <td class="py-3.5 px-4 text-xs text-gray-500">
                                        {{ $task->deadline ? $task->deadline->format('d M, Y') : '-' }}
                                    </td>
                                    <td class="py-3.5 px-4 text-right">
                                        <a href="{{ route('tasks.show', $task) }}" class="inline-flex items-center px-2.5 py-1 bg-gray-100 hover:bg-indigo-50 hover:text-indigo-600 text-gray-700 rounded text-xs font-semibold transition">
                                            {{ __('Details & QA') }} &rarr;
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="py-8 text-center text-gray-400">
                                        <div class="text-sm">{{ __('No tasks created for this project yet.') }}</div>
                                        <button type="button" onclick="document.getElementById('taskFormContainer').classList.remove('hidden')" class="mt-2 text-xs font-bold text-indigo-600 hover:underline">
                                            {{ __('+ Add first task') }}
                                        </button>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>
