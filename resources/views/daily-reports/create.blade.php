<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-extrabold text-2xl text-slate-900 tracking-tight leading-tight">
                    {{ $existingReport ? "Edit Daily Report ({$existingReport->report_date->format('d M, Y')})" : "Submit Daily Report (" . \Carbon\Carbon::parse($today)->format('d M, Y') . ")" }}
                </h2>
                <p class="text-xs text-slate-500 mt-1">
                    Log your daily work achievements, challenges, and plan for tomorrow.
                </p>
            </div>
            <a href="{{ route('daily-reports.index') }}" class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition">
                &larr; Back to Daily Reports
            </a>
        </div>
    </x-slot>

    <div class="py-6 max-w-4xl mx-auto">
        <form action="{{ route('daily-reports.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Meta Information Card -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-2xs space-y-4">
                <h3 class="text-sm font-black text-slate-900 border-b border-slate-100 pb-2 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-indigo-600"></span>
                    General Work Details
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Report Date <span class="text-rose-500">*</span></label>
                        <input type="date" name="report_date" value="{{ old('report_date', $existingReport->report_date ? $existingReport->report_date->format('Y-m-d') : $today) }}" required class="w-full text-xs px-3 py-2 border border-slate-300 rounded-xl outline-none font-bold">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Work Mode <span class="text-rose-500">*</span></label>
                        <select name="work_mode" required class="w-full text-xs px-3 py-2 border border-slate-300 rounded-xl outline-none font-semibold">
                            <option value="Office" {{ old('work_mode', $existingReport->work_mode ?? 'Office') === 'Office' ? 'selected' : '' }}>🏢 Office (In-Person)</option>
                            <option value="Remote" {{ old('work_mode', $existingReport->work_mode ?? '') === 'Remote' ? 'selected' : '' }}>🏠 Remote (WFH)</option>
                            <option value="Field" {{ old('work_mode', $existingReport->work_mode ?? '') === 'Field' ? 'selected' : '' }}>🚗 Field Visit / Client Meeting</option>
                            <option value="Hybrid" {{ old('work_mode', $existingReport->work_mode ?? '') === 'Hybrid' ? 'selected' : '' }}>⚡ Hybrid</option>
                        </select>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Total Hours Worked <span class="text-rose-500">*</span></label>
                        <input type="number" step="0.5" min="0.5" max="24" name="hours_worked" value="{{ old('hours_worked', $existingReport->hours_worked ?? 8.0) }}" required class="w-full text-xs px-3 py-2 border border-slate-300 rounded-xl outline-none font-bold">
                    </div>
                </div>
            </div>

            <!-- Quantitative Activity Metrics -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-2xs space-y-4">
                <h3 class="text-sm font-black text-slate-900 border-b border-slate-100 pb-2 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-600"></span>
                    Quantitative Activity Metrics
                </h3>
                <p class="text-[11px] text-slate-500">Auto-detected or self-reported output metrics for today.</p>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Leads Contacted / Followed Up</label>
                        <input type="number" min="0" name="leads_contacted" value="{{ old('leads_contacted', $existingReport->leads_contacted ?? $leadsCountToday) }}" class="w-full text-xs px-3 py-2 border border-slate-300 rounded-xl outline-none font-bold font-mono">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Client Meetings / Calls Held</label>
                        <input type="number" min="0" name="meetings_done" value="{{ old('meetings_done', $existingReport->meetings_done ?? 0) }}" class="w-full text-xs px-3 py-2 border border-slate-300 rounded-xl outline-none font-bold font-mono">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Orders Closed / Won</label>
                        <input type="number" min="0" name="orders_closed" value="{{ old('orders_closed', $existingReport->orders_closed ?? 0) }}" class="w-full text-xs px-3 py-2 border border-slate-300 rounded-xl outline-none font-bold font-mono text-emerald-600">
                    </div>
                </div>
            </div>

            <!-- Task Descriptions Card -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-2xs space-y-5">
                <h3 class="text-sm font-black text-slate-900 border-b border-slate-100 pb-2 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-blue-600"></span>
                    Detailed Daily Work Log
                </h3>

                <div>
                    <label class="block font-bold text-slate-800 text-xs mb-1">
                        1. Tasks Completed Today <span class="text-rose-500">*</span>
                    </label>
                    <textarea name="tasks_completed" rows="4" required placeholder="• Completed homepage design review with client...&#10;• Configured SSL and domain routing...&#10;• Handled 15 follow-up calls with prospective leads..." class="w-full text-xs p-3 border border-slate-300 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500 font-sans leading-relaxed">{{ old('tasks_completed', $existingReport->tasks_completed ?? '') }}</textarea>
                </div>

                <div>
                    <label class="block font-bold text-slate-800 text-xs mb-1">
                        2. Ongoing / In-Progress Tasks
                    </label>
                    <textarea name="in_progress_tasks" rows="3" placeholder="• Working on the backend inventory module (80% complete)...&#10;• Awaiting client feedback on creative video storyboard..." class="w-full text-xs p-3 border border-slate-300 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500 leading-relaxed">{{ old('in_progress_tasks', $existingReport->in_progress_tasks ?? '') }}</textarea>
                </div>

                <div>
                    <label class="block font-bold text-slate-800 text-xs mb-1">
                        3. Challenges, Roadblocks or Assistance Needed
                    </label>
                    <textarea name="challenges" rows="2" placeholder="Mention any technical blockers, API credential delays, or team dependencies..." class="w-full text-xs p-3 border border-slate-300 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500 leading-relaxed">{{ old('challenges', $existingReport->challenges ?? '') }}</textarea>
                </div>

                <div>
                    <label class="block font-bold text-slate-800 text-xs mb-1">
                        4. Plan for Tomorrow
                    </label>
                    <textarea name="tomorrow_plan" rows="2" placeholder="Key goals and milestones scheduled for tomorrow..." class="w-full text-xs p-3 border border-slate-300 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500 leading-relaxed">{{ old('tomorrow_plan', $existingReport->tomorrow_plan ?? '') }}</textarea>
                </div>
            </div>

            <!-- Submit Button Bar -->
            <div class="flex items-center justify-end gap-3 pt-2">
                <a href="{{ route('daily-reports.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl transition shadow-sm">
                    {{ $existingReport ? 'Update Daily Report' : 'Submit Daily Report 🚀' }}
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
