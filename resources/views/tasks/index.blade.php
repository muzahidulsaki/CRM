<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight flex items-center gap-2">
                    <svg class="w-7 h-7 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                    {{ __('Task Management') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">{{ __('Project task list, team assignments and QA tracking') }}</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('tasks.kanban') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg font-semibold text-xs uppercase tracking-widest hover:bg-indigo-700 shadow-sm transition">
                    <svg class="w-4 h-4 me-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/></svg>
                    {{ __('View Kanban Board') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Metrics -->
            <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-7 gap-3">
                <div class="bg-white p-3.5 rounded-xl border border-gray-200 shadow-sm text-center">
                    <div class="text-[11px] font-semibold text-gray-500 uppercase">{{ __('Total Tasks') }}</div>
                    <div class="text-xl font-bold text-gray-900 mt-0.5">{{ $metrics['total'] }}</div>
                </div>
                <div class="bg-white p-3.5 rounded-xl border border-gray-200 shadow-sm text-center">
                    <div class="text-[11px] font-semibold text-gray-500 uppercase">{{ __('Pending') }}</div>
                    <div class="text-xl font-bold text-gray-700 mt-0.5">{{ $metrics['pending'] }}</div>
                </div>
                <div class="bg-white p-3.5 rounded-xl border border-blue-200 bg-blue-50/30 shadow-sm text-center">
                    <div class="text-[11px] font-semibold text-blue-600 uppercase">{{ __('Working') }}</div>
                    <div class="text-xl font-bold text-blue-700 mt-0.5">{{ $metrics['working'] }}</div>
                </div>
                <div class="bg-white p-3.5 rounded-xl border border-rose-200 bg-rose-50/30 shadow-sm text-center">
                    <div class="text-[11px] font-semibold text-rose-600 uppercase">{{ __('Reworking') }}</div>
                    <div class="text-xl font-bold text-rose-700 mt-0.5">{{ $metrics['reworking'] }}</div>
                </div>
                <div class="bg-white p-3.5 rounded-xl border border-amber-200 bg-amber-50/30 shadow-sm text-center">
                    <div class="text-[11px] font-semibold text-amber-600 uppercase">{{ __('In Review') }}</div>
                    <div class="text-xl font-bold text-amber-700 mt-0.5">{{ $metrics['completed'] }}</div>
                </div>
                <div class="bg-white p-3.5 rounded-xl border border-purple-200 bg-purple-50/30 shadow-sm text-center">
                    <div class="text-[11px] font-semibold text-purple-600 uppercase">{{ __('QA Checked') }}</div>
                    <div class="text-xl font-bold text-purple-700 mt-0.5">{{ $metrics['checked'] }}</div>
                </div>
                <div class="bg-white p-3.5 rounded-xl border border-emerald-200 bg-emerald-50/30 shadow-sm text-center">
                    <div class="text-[11px] font-semibold text-emerald-600 uppercase">{{ __('Delivered') }}</div>
                    <div class="text-xl font-bold text-emerald-700 mt-0.5">{{ $metrics['delivered'] }}</div>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white p-4 rounded-2xl border border-gray-200 shadow-sm">
                <form method="GET" action="{{ route('tasks.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-3 items-end">
                    <div class="lg:col-span-2">
                        <label class="block text-xs font-medium text-gray-700 mb-1">{{ __('Search') }}</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('Task code, title, project...') }}"
                            class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">{{ __('Project') }}</label>
                        <select name="project_id" class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">{{ __('All Projects') }}</option>
                            @foreach($projects as $proj)
                                <option value="{{ $proj->id }}" {{ request('project_id') == $proj->id ? 'selected' : '' }}>{{ $proj->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">{{ __('Team Member') }}</label>
                        <select name="assigned_to" class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">{{ __('All Members') }}</option>
                            @foreach($teamMembers as $tm)
                                <option value="{{ $tm->id }}" {{ request('assigned_to') == $tm->id ? 'selected' : '' }}>{{ $tm->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">{{ __('Status') }}</label>
                        <select name="status" class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">{{ __('All Statuses') }}</option>
                            @foreach(['Pending', 'Working', 'Reworking', 'Completed', 'Checked', 'Delivered'] as $st)
                                <option value="{{ $st }}" {{ request('status') == $st ? 'selected' : '' }}>{{ __($st) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex items-center gap-2">
                        <button type="submit" class="flex-1 py-2 px-4 bg-gray-900 text-white rounded-lg text-sm font-semibold hover:bg-gray-800 transition">
                            {{ __('Filter') }}
                        </button>
                        @if(request()->anyFilled(['search', 'project_id', 'assigned_to', 'status']))
                            <a href="{{ route('tasks.index') }}" class="py-2 px-3 bg-gray-100 text-gray-600 rounded-lg text-sm font-medium hover:bg-gray-200 transition">
                                {{ __('Reset') }}
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Tasks Table -->
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-left text-sm">
                        <thead class="bg-gray-50 text-gray-500 font-semibold uppercase text-xs">
                            <tr>
                                <th class="py-3.5 px-4">{{ __('Task') }}</th>
                                <th class="py-3.5 px-4">{{ __('Project & Client') }}</th>
                                <th class="py-3.5 px-4">{{ __('Assigned Staff') }}</th>
                                <th class="py-3.5 px-4">{{ __('Priority') }}</th>
                                <th class="py-3.5 px-4">{{ __('Status') }}</th>
                                <th class="py-3.5 px-4">{{ __('QA Status') }}</th>
                                <th class="py-3.5 px-4">{{ __('Deadline') }}</th>
                                <th class="py-3.5 px-4 text-right">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($tasks as $task)
                                <tr class="hover:bg-gray-50/60 transition">
                                    <td class="py-3.5 px-4">
                                        <span class="font-mono text-xs font-bold text-indigo-700 bg-indigo-50 px-1.5 py-0.5 rounded">{{ $task->task_code }}</span>
                                        <a href="{{ route('tasks.show', $task) }}" class="font-bold text-gray-900 hover:text-indigo-600 block mt-1">
                                            {{ $task->title }}
                                        </a>
                                        <span class="text-xs text-gray-400">{{ $task->task_type }}</span>
                                    </td>
                                    <td class="py-3.5 px-4">
                                        <a href="{{ route('projects.show', $task->project) }}" class="font-medium text-gray-800 hover:text-indigo-600 block">
                                            {{ $task->project->name }}
                                        </a>
                                        <span class="text-xs text-gray-500">{{ $task->project->customer?->name }}</span>
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
                                            <span class="text-xs font-bold text-emerald-600">{{ __('Passed') }}</span>
                                        @elseif($task->qa_status === 'Failed')
                                            <span class="text-xs font-bold text-rose-600">{{ __('Failed') }}</span>
                                        @else
                                            <span class="text-xs text-gray-400">{{ __('Pending') }}</span>
                                        @endif
                                    </td>
                                    <td class="py-3.5 px-4 text-xs text-gray-500">
                                        @if($task->deadline)
                                            <span class="{{ $task->deadline->isPast() && !in_array($task->status, ['Checked', 'Delivered']) ? 'text-rose-600 font-bold' : '' }}">
                                                {{ $task->deadline->format('d M, Y') }}
                                            </span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="py-3.5 px-4 text-right">
                                        <a href="{{ route('tasks.show', $task) }}" class="inline-flex items-center px-2.5 py-1 bg-gray-100 hover:bg-indigo-50 hover:text-indigo-600 text-gray-700 rounded text-xs font-semibold transition">
                                            {{ __('Details & QA') }} &rarr;
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="py-12 text-center text-gray-400">
                                        <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                                        <div class="text-base font-medium">{{ __('No tasks found') }}</div>
                                        <div class="text-sm mt-1">{{ __('Add tasks from the project detail page.') }}</div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($tasks->hasPages())
                    <div class="p-4 border-t border-gray-100">
                        {{ $tasks->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
