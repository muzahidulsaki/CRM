<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-extrabold text-2xl text-slate-900 tracking-tight leading-tight flex items-center gap-2">
                    Daily Report: {{ $dailyReport->user->name }}
                    <span class="text-xs px-2.5 py-0.5 rounded-full font-bold {{ $dailyReport->status === 'Reviewed' ? 'bg-emerald-100 text-emerald-800' : ($dailyReport->status === 'Needs Improvement' ? 'bg-rose-100 text-rose-800' : 'bg-amber-100 text-amber-800') }}">
                        {{ $dailyReport->status }}
                    </span>
                </h2>
                <p class="text-xs text-slate-500 mt-1">
                    {{ $dailyReport->report_date->format('l, d F, Y') }} &bull; Work Mode: {{ $dailyReport->work_mode }} &bull; {{ $dailyReport->hours_worked }} Hours Logged
                </p>
            </div>
            <div class="flex items-center gap-2">
                @if(Auth::id() === $dailyReport->user_id)
                    <a href="{{ route('daily-reports.create') }}" class="px-3.5 py-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-xs font-bold rounded-xl transition">
                        ✏️ Edit My Report
                    </a>
                @endif
                <a href="{{ route('daily-reports.index') }}" class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition">
                    &larr; Back to List
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6 max-w-4xl mx-auto space-y-6">

        <!-- Activity Metrics Bar -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-2xs text-center">
                <span class="text-[11px] font-bold uppercase text-slate-400">Total Hours</span>
                <div class="text-2xl font-black text-slate-900 mt-1">{{ $dailyReport->hours_worked }} <span class="text-xs font-medium text-slate-400">hrs</span></div>
            </div>

            <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-2xs text-center">
                <span class="text-[11px] font-bold uppercase text-slate-400">Leads Contacted</span>
                <div class="text-2xl font-black text-blue-600 mt-1">{{ $dailyReport->leads_contacted }}</div>
            </div>

            <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-2xs text-center">
                <span class="text-[11px] font-bold uppercase text-slate-400">Meetings Done</span>
                <div class="text-2xl font-black text-purple-600 mt-1">{{ $dailyReport->meetings_done }}</div>
            </div>

            <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-2xs text-center">
                <span class="text-[11px] font-bold uppercase text-slate-400">Orders Closed</span>
                <div class="text-2xl font-black text-emerald-600 mt-1">{{ $dailyReport->orders_closed }}</div>
            </div>
        </div>

        <!-- Detailed Work Breakdown -->
        <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200/80 shadow-2xs space-y-6">
            <div>
                <h3 class="text-xs font-black uppercase tracking-wider text-emerald-700 mb-2 flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Tasks Completed Today
                </h3>
                <div class="bg-slate-50 p-4 rounded-xl text-xs text-slate-800 whitespace-pre-line leading-relaxed font-sans border border-slate-200/60">
                    {{ $dailyReport->tasks_completed }}
                </div>
            </div>

            @if($dailyReport->in_progress_tasks)
            <div>
                <h3 class="text-xs font-black uppercase tracking-wider text-blue-700 mb-2 flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Ongoing / In-Progress Tasks
                </h3>
                <div class="bg-slate-50 p-4 rounded-xl text-xs text-slate-800 whitespace-pre-line leading-relaxed font-sans border border-slate-200/60">
                    {{ $dailyReport->in_progress_tasks }}
                </div>
            </div>
            @endif

            @if($dailyReport->challenges)
            <div>
                <h3 class="text-xs font-black uppercase tracking-wider text-rose-700 mb-2 flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    Challenges, Roadblocks & Support Needed
                </h3>
                <div class="bg-rose-50/50 p-4 rounded-xl text-xs text-slate-800 whitespace-pre-line leading-relaxed font-sans border border-rose-100">
                    {{ $dailyReport->challenges }}
                </div>
            </div>
            @endif

            @if($dailyReport->tomorrow_plan)
            <div>
                <h3 class="text-xs font-black uppercase tracking-wider text-indigo-700 mb-2 flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    Plan for Tomorrow
                </h3>
                <div class="bg-slate-50 p-4 rounded-xl text-xs text-slate-800 whitespace-pre-line leading-relaxed font-sans border border-slate-200/60">
                    {{ $dailyReport->tomorrow_plan }}
                </div>
            </div>
            @endif
        </div>

        <!-- Manager Feedback & Review Section -->
        <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200/80 shadow-2xs space-y-4">
            <h3 class="text-sm font-black text-slate-900 border-b border-slate-100 pb-2 flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-purple-600"></span>
                Manager Review & Feedback
            </h3>

            @if($dailyReport->manager_feedback)
                <div class="p-4 rounded-xl {{ $dailyReport->status === 'Needs Improvement' ? 'bg-rose-50 border border-rose-200' : 'bg-purple-50/60 border border-purple-200' }}">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-bold text-slate-800">
                            Reviewed by: <span class="font-extrabold text-purple-900">{{ $dailyReport->reviewer->name ?? 'Admin' }}</span>
                        </span>
                        <span class="text-[11px] text-slate-400">
                            {{ $dailyReport->reviewed_at ? $dailyReport->reviewed_at->diffForHumans() : '' }}
                        </span>
                    </div>
                    <p class="text-xs text-slate-800 leading-relaxed whitespace-pre-line">{{ $dailyReport->manager_feedback }}</p>
                </div>
            @endif

            @if($isAdminOrManager)
                <form action="{{ route('daily-reports.feedback', $dailyReport->id) }}" method="POST" class="space-y-4 pt-2">
                    @csrf
                    <div>
                        <label class="block font-bold text-xs text-slate-700 mb-1">
                            {{ $dailyReport->manager_feedback ? 'Update Review Remarks / Feedback:' : 'Submit Review Remarks / Feedback:' }}
                        </label>
                        <textarea name="manager_feedback" rows="3" required placeholder="Write feedback, recognition, or recommendations for this daily report..." class="w-full text-xs p-3 border border-slate-300 rounded-xl outline-none focus:ring-2 focus:ring-purple-500">{{ old('manager_feedback', $dailyReport->manager_feedback) }}</textarea>
                    </div>

                    <div class="flex items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <label class="text-xs font-bold text-slate-700">Set Status:</label>
                            <label class="inline-flex items-center gap-1.5 text-xs font-semibold cursor-pointer">
                                <input type="radio" name="status" value="Reviewed" {{ old('status', $dailyReport->status) === 'Reviewed' || $dailyReport->status === 'Submitted' ? 'checked' : '' }} class="text-emerald-600 focus:ring-emerald-500">
                                <span class="text-emerald-700">✓ Reviewed & Approved</span>
                            </label>
                            <label class="inline-flex items-center gap-1.5 text-xs font-semibold cursor-pointer">
                                <input type="radio" name="status" value="Needs Improvement" {{ old('status', $dailyReport->status) === 'Needs Improvement' ? 'checked' : '' }} class="text-rose-600 focus:ring-rose-500">
                                <span class="text-rose-700">⚠️ Needs Improvement</span>
                            </label>
                        </div>

                        <button type="submit" class="px-5 py-2 bg-purple-600 hover:bg-purple-700 text-white text-xs font-bold rounded-xl transition shadow-xs">
                            Save Review Feedback
                        </button>
                    </div>
                </form>
            @endif
        </div>
    </div>
</x-app-layout>

