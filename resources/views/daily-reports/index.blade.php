<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-extrabold text-2xl text-slate-900 tracking-tight leading-tight flex items-center gap-2">
                    <span class="p-2 bg-indigo-50 text-indigo-600 rounded-xl">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                    </span>
                    Employee Daily Reports
                </h2>
                <p class="text-xs text-slate-500 mt-1">
                    End-of-day task accountability, work progress tracking, team productivity hours, and management review.
                </p>
            </div>
            <div class="flex items-center gap-2">
                @if($myTodayReport)
                    <a href="{{ route('daily-reports.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl transition shadow-sm">
                        <span>✏️ Update Today's Report</span>
                        <span class="text-[10px] bg-emerald-800/60 px-1.5 py-0.5 rounded font-mono">Submitted</span>
                    </a>
                @else
                    <a href="{{ route('daily-reports.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl transition shadow-sm animate-pulse">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        <span>+ Submit Today's Report</span>
                    </a>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-6 space-y-6">
        <!-- Metric Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-2xs">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Today's Submissions</span>
                <div class="text-3xl font-black text-slate-900 mt-1">{{ $todaySubmissions }}</div>
                <p class="text-xs text-slate-500 mt-1">Reports logged for {{ \Carbon\Carbon::today()->format('d M, Y') }}</p>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-2xs">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Hours Today</span>
                <div class="text-3xl font-black text-indigo-600 mt-1">{{ number_format($totalHoursToday, 1) }} <span class="text-base font-semibold text-slate-400">hrs</span></div>
                <p class="text-xs text-slate-500 mt-1">Productive work logged across the team</p>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-2xs">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Pending Reviews</span>
                <div class="text-3xl font-black text-amber-600 mt-1">{{ $pendingReviews }}</div>
                <p class="text-xs text-slate-500 mt-1">Awaiting manager remarks & approval</p>
            </div>
        </div>

        <!-- Filter Bar -->
        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-2xs">
            <form action="{{ route('daily-reports.index') }}" method="GET" class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div class="flex flex-wrap items-center gap-3">
                    <div>
                        <label class="block text-[10px] font-bold uppercase text-slate-400 mb-1">Date</label>
                        <input type="date" name="date" value="{{ request('date', $selectedDate) }}" class="text-xs px-3 py-1.5 border border-slate-300 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>

                    @if($isAdminOrManager)
                    <div>
                        <label class="block text-[10px] font-bold uppercase text-slate-400 mb-1">Employee</label>
                        <select name="user_id" class="text-xs px-3 py-1.5 border border-slate-300 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500">
                            <option value="">All Team Members</option>
                            @foreach($employees as $emp)
                                <option value="{{ $emp->id }}" {{ request('user_id') == $emp->id ? 'selected' : '' }}>{{ $emp->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif

                    <div>
                        <label class="block text-[10px] font-bold uppercase text-slate-400 mb-1">Status</label>
                        <select name="status" class="text-xs px-3 py-1.5 border border-slate-300 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500">
                            <option value="">All Statuses</option>
                            <option value="Submitted" {{ request('status') === 'Submitted' ? 'selected' : '' }}>Submitted</option>
                            <option value="Reviewed" {{ request('status') === 'Reviewed' ? 'selected' : '' }}>Reviewed</option>
                            <option value="Needs Improvement" {{ request('status') === 'Needs Improvement' ? 'selected' : '' }}>Needs Improvement</option>
                        </select>
                    </div>

                    <div class="self-end pt-5">
                        <button type="submit" class="px-4 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl transition shadow-2xs">
                            Filter
                        </button>
                    </div>
                </div>

                @if(request()->anyFilled(['date', 'user_id', 'status']))
                    <a href="{{ route('daily-reports.index') }}" class="text-xs text-indigo-600 font-bold hover:underline self-end">
                        Reset Filters
                    </a>
                @endif
            </form>
        </div>

        <!-- Reports List Table -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-2xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-100 text-xs">
                    <thead class="bg-slate-50 text-slate-600 font-bold uppercase tracking-wider text-[11px]">
                        <tr>
                            <th class="px-4 py-3 text-left">Employee</th>
                            <th class="px-4 py-3 text-left">Report Date</th>
                            <th class="px-4 py-3 text-center">Work Mode</th>
                            <th class="px-4 py-3 text-center">Hours</th>
                            <th class="px-4 py-3 text-left">Completed Tasks Preview</th>
                            <th class="px-4 py-3 text-center">Activity Metrics</th>
                            <th class="px-4 py-3 text-center">Status</th>
                            <th class="px-4 py-3 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($reports as $row)
                            <tr class="hover:bg-slate-50/70 transition">
                                <td class="px-4 py-3">
                                    <div class="font-bold text-slate-900">{{ $row->user->name ?? 'Unknown' }}</div>
                                    <div class="text-[11px] text-slate-400">{{ $row->user->employee->designation ?? $row->user->role->name ?? 'Team Member' }}</div>
                                </td>
                                <td class="px-4 py-3 font-semibold text-slate-800">
                                    {{ $row->report_date->format('d M, Y') }}
                                    <div class="text-[10px] text-slate-400">{{ $row->report_date->format('l') }}</div>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-700">
                                        {{ $row->work_mode }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center font-bold text-slate-900">
                                    {{ $row->hours_worked }} hrs
                                </td>
                                <td class="px-4 py-3 max-w-xs truncate text-slate-600">
                                    {{ Str::limit($row->tasks_completed, 80) }}
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <div class="flex items-center justify-center gap-1.5 text-[10px] font-mono font-bold">
                                        <span class="px-1.5 py-0.5 bg-blue-50 text-blue-700 rounded" title="Leads Contacted">📞 {{ $row->leads_contacted }}</span>
                                        <span class="px-1.5 py-0.5 bg-purple-50 text-purple-700 rounded" title="Meetings Done">🤝 {{ $row->meetings_done }}</span>
                                        <span class="px-1.5 py-0.5 bg-emerald-50 text-emerald-700 rounded" title="Orders Closed">🎯 {{ $row->orders_closed }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    @if($row->status === 'Reviewed')
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">
                                            ✓ Reviewed
                                        </span>
                                    @elseif($row->status === 'Needs Improvement')
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-rose-100 text-rose-800">
                                            ⚠️ Needs Improvement
                                        </span>
                                    @else
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800">
                                            ⏳ Submitted
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <a href="{{ route('daily-reports.show', $row->id) }}" class="px-3 py-1 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-xs font-bold rounded-lg transition">
                                        View Details &rarr;
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-4 py-12 text-center text-slate-400 italic">
                                    No employee daily reports found for this filter.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-slate-100">
                {{ $reports->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
