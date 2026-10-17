<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight flex items-center gap-2">
                    <svg class="w-7 h-7 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ __('Worklogs & Hourly Tracking') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">{{ __('Daily work hours and billable tracking for developers and designers') }}</p>
            </div>
            <button type="button" onclick="document.getElementById('logFormContainer').classList.toggle('hidden')"
                class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg font-semibold text-xs uppercase tracking-widest hover:bg-indigo-700 shadow-sm transition">
                <svg class="w-4 h-4 me-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                {{ __('Add Worklog Entry') }}
            </button>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Metrics -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm text-center">
                    <div class="text-xs font-semibold text-gray-500 uppercase">{{ __('Total Time Spent') }}</div>
                    <div class="text-2xl font-bold text-gray-900 mt-1">
                        {{ number_format($metrics['total_hours'], 1) }} <span class="text-xs font-normal text-gray-500">{{ __('hrs') }}</span>
                    </div>
                </div>

                <div class="bg-white p-4 rounded-xl border border-indigo-200 bg-indigo-50/30 shadow-sm text-center">
                    <div class="text-xs font-semibold text-indigo-600 uppercase">{{ __('Approved Time') }}</div>
                    <div class="text-2xl font-bold text-indigo-700 mt-1">
                        {{ number_format($metrics['approved_hours'], 1) }} <span class="text-xs font-normal text-gray-500">{{ __('hrs') }}</span>
                    </div>
                </div>

                <div class="bg-white p-4 rounded-xl border border-emerald-200 bg-emerald-50/30 shadow-sm text-center">
                    <div class="text-xs font-semibold text-emerald-600 uppercase">{{ __('Total Billable Amount') }}</div>
                    <div class="text-2xl font-bold text-emerald-700 mt-1">BDT {{ number_format($metrics['total_billable']) }}</div>
                </div>
            </div>

            <!-- Collapsible Worklog Entry Form -->
            <div id="logFormContainer" class="hidden bg-white p-6 rounded-2xl border border-indigo-200 shadow-sm">
                <h3 class="font-bold text-base text-gray-900 mb-4 border-b pb-2">{{ __('New Worklog Entry') }}</h3>
                <form method="POST" action="{{ route('worklogs.store') }}" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">{{ __('Employee') }} <span class="text-rose-500">*</span></label>
                            <select name="employee_id" required class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">{{ __('Select Employee') }}</option>
                                @foreach($employees as $emp)
                                    <option value="{{ $emp->id }}">
                                        {{ $emp->user->name }} ({{ $emp->designation }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">{{ __('Date') }} <span class="text-rose-500">*</span></label>
                            <input type="date" name="log_date" required value="{{ date('Y-m-d') }}"
                                class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">{{ __('Work Hours') }} <span class="text-rose-500">*</span></label>
                            <input type="number" step="0.25" min="0.25" name="hours_worked" required placeholder="e.g. 4.5"
                                class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">{{ __('Project (Optional)') }}</label>
                            <select name="project_id" class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">{{ __('Select Project') }}</option>
                                @foreach($projects as $proj)
                                    <option value="{{ $proj->id }}">{{ $proj->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">{{ __('Task (Optional)') }}</label>
                            <select name="task_id" class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">{{ __('Select Task') }}</option>
                                @foreach($tasks as $tsk)
                                    <option value="{{ $tsk->id }}">{{ $tsk->task_code }} - {{ $tsk->title }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">{{ __('Hourly Rate (BDT, Optional)') }}</label>
                            <input type="number" step="0.01" name="hourly_rate" placeholder="{{ __('Leave blank to use employee profile rate') }}"
                                class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div class="sm:col-span-3">
                            <label class="block text-xs font-semibold text-gray-700 mb-1">{{ __('Work Summary & Progress') }} <span class="text-rose-500">*</span></label>
                            <textarea name="work_summary" rows="2" required placeholder="{{ __('Summary of modules or bugs worked on today...') }}"
                                class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-2">
                        <button type="button" onclick="document.getElementById('logFormContainer').classList.add('hidden')"
                            class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg text-xs font-semibold hover:bg-gray-200 transition">
                            {{ __('Cancel') }}
                        </button>
                        <button type="submit" class="px-5 py-2 bg-indigo-600 text-white rounded-lg text-xs font-semibold hover:bg-indigo-700 transition">
                            {{ __('Save Worklog') }}
                        </button>
                    </div>
                </form>
            </div>

            <!-- Worklogs Table -->
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-left text-sm">
                        <thead class="bg-gray-50 text-gray-500 font-semibold uppercase text-xs">
                            <tr>
                                <th class="py-3.5 px-4">{{ __('Date') }}</th>
                                <th class="py-3.5 px-4">{{ __('Employee') }}</th>
                                <th class="py-3.5 px-4">{{ __('Work Summary') }}</th>
                                <th class="py-3.5 px-4">{{ __('Project & Task') }}</th>
                                <th class="py-3.5 px-4">{{ __('Time Spent') }}</th>
                                <th class="py-3.5 px-4">{{ __('Billable Amount') }}</th>
                                <th class="py-3.5 px-4">{{ __('Status') }}</th>
                                <th class="py-3.5 px-4 text-right">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($worklogs as $log)
                                <tr class="hover:bg-gray-50/60 transition">
                                    <td class="py-3.5 px-4 text-xs text-gray-600">
                                        {{ $log->log_date->format('d M, Y') }}
                                    </td>
                                    <td class="py-3.5 px-4">
                                        <div class="font-bold text-gray-900">{{ $log->employee->user->name }}</div>
                                        <div class="text-xs text-gray-400">{{ $log->employee->designation }}</div>
                                    </td>
                                    <td class="py-3.5 px-4 text-xs text-gray-800 max-w-xs">
                                        {{ $log->work_summary }}
                                    </td>
                                    <td class="py-3.5 px-4 text-xs text-gray-600">
                                        @if($log->project)
                                            <div>📁 {{ $log->project->name }}</div>
                                        @endif
                                        @if($log->task)
                                            <div class="text-indigo-600 font-medium mt-0.5">{{ $log->task->task_code }}</div>
                                        @endif
                                    </td>
                                    <td class="py-3.5 px-4 font-bold text-gray-900">
                                        {{ $log->hours_worked }} {{ __('hrs') }}
                                    </td>
                                    <td class="py-3.5 px-4 font-bold text-emerald-700">
                                        BDT {{ number_format($log->billable_amount, 2) }}
                                    </td>
                                    <td class="py-3.5 px-4">
                                        <span class="px-2 py-0.5 rounded text-xs font-semibold {{ $log->status === 'Approved' ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-100 text-gray-800' }}">
                                            {{ __($log->status) }}
                                        </span>
                                    </td>
                                    <td class="py-3.5 px-4 text-right">
                                        @if($log->status !== 'Approved')
                                            <form method="POST" action="{{ route('worklogs.approve', $log) }}" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="px-2.5 py-1 bg-emerald-50 text-emerald-700 hover:bg-emerald-100 rounded text-xs font-semibold transition">
                                                    {{ __('Approve') }}
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="py-12 text-center text-gray-400">
                                        <div class="text-base font-medium">{{ __('No worklogs found') }}</div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($worklogs->hasPages())
                    <div class="p-4 border-t border-gray-100">
                        {{ $worklogs->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
