<x-portal-layout>
    <x-slot name="title">{{ $project->name }}</x-slot>

    <div class="space-y-6">
        <!-- Back Link & Header -->
        <div class="flex items-center gap-4">
            <a href="{{ route('portal.projects.index') }}" class="p-2 rounded-xl bg-white border border-slate-200 text-slate-500 hover:text-slate-900 shadow-2xs transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2 flex-wrap">
                    <span class="px-2.5 py-0.5 rounded text-[11px] font-mono font-bold bg-indigo-50 text-indigo-700 border border-indigo-100">
                        {{ $project->project_code }}
                    </span>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold 
                        @if($project->status === 'Completed') bg-emerald-50 text-emerald-700 border border-emerald-200
                        @elseif($project->status === 'In Progress') bg-blue-50 text-blue-700 border border-blue-200
                        @else bg-purple-50 text-purple-700 border border-purple-200 @endif">
                        {{ $project->status }}
                    </span>
                </div>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight mt-1 truncate">
                    {{ $project->name }}
                </h1>
            </div>
            @if($project->live_url)
                <a href="{{ $project->live_url }}" target="_blank" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold shadow-xs transition flex items-center gap-1.5">
                    <span>{{ __('View Live Project') }}</span> ↗
                </a>
            @endif
        </div>

        <!-- Project Overview Card -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-2xs space-y-4">
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 pb-4 border-b border-slate-100 text-xs">
                <div>
                    <span class="text-slate-400 block font-semibold">{{ __('Category') }}</span>
                    <span class="font-bold text-slate-800 text-sm mt-0.5">{{ $project->category ?? 'Software Development' }}</span>
                </div>
                <div>
                    <span class="text-slate-400 block font-semibold">{{ __('Project Manager') }}</span>
                    <span class="font-bold text-slate-800 text-sm mt-0.5">{{ $project->projectManager->name ?? 'Unassigned' }}</span>
                </div>
                <div>
                    <span class="text-slate-400 block font-semibold">{{ __('Start Date') }}</span>
                    <span class="font-bold text-slate-800 text-sm mt-0.5">{{ $project->start_date ? $project->start_date->format('d M, Y') : 'N/A' }}</span>
                </div>
                <div>
                    <span class="text-slate-400 block font-semibold">{{ __('Delivery Deadline') }}</span>
                    <span class="font-bold text-slate-800 text-sm mt-0.5">{{ $project->deadline ? $project->deadline->format('d M, Y') : 'N/A' }}</span>
                </div>
            </div>

            <!-- Description -->
            @if($project->description)
                <div class="text-xs text-slate-600 leading-relaxed">
                    <strong class="text-slate-800 block mb-1 font-semibold">{{ __('Project Scope & Description:') }}</strong>
                    <p class="whitespace-pre-line">{{ $project->description }}</p>
                </div>
            @endif

            <!-- Overall Progress Bar -->
            <div class="pt-2">
                <div class="flex justify-between text-xs font-bold text-slate-700 mb-1.5">
                    <span>{{ __('Milestone Progress') }}</span>
                    <span class="text-indigo-600 font-black text-sm">{{ $project->progress_percent }}%</span>
                </div>
                <div class="w-full bg-slate-100 rounded-full h-3 overflow-hidden">
                    <div class="h-full rounded-full bg-gradient-to-r from-indigo-500 via-indigo-600 to-emerald-500 transition-all duration-500" style="width: {{ $project->progress_percent }}%"></div>
                </div>
            </div>
        </div>

        <!-- Project Tasks / Milestones -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-2xs overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="text-base font-bold text-slate-900">{{ __('Project Tasks & Milestones') }}</h3>
                    <p class="text-xs text-slate-500">{{ __('Work status submitted by developer team') }}</p>
                </div>
                <span class="px-2.5 py-1 rounded-md text-xs font-bold bg-slate-100 text-slate-700">
                    {{ $project->tasks->count() }} {{ __('Tasks') }}
                </span>
            </div>

            <div class="divide-y divide-slate-100">
                @forelse($project->tasks as $task)
                    <div class="p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3 hover:bg-slate-50/50 transition text-xs">
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="font-mono font-bold text-[10px] text-slate-400">{{ $task->task_code }}</span>
                                <span class="font-bold text-slate-900 text-sm">{{ $task->title }}</span>
                            </div>
                            @if($task->description)
                                <p class="text-slate-500 mt-1 line-clamp-1">{{ $task->description }}</p>
                            @endif
                        </div>
                        <div class="flex items-center gap-3 shrink-0">
                            <span class="px-2.5 py-0.5 rounded text-[10px] font-bold 
                                @if($task->status === 'Delivered' || $task->status === 'Completed' || $task->status === 'Checked') bg-emerald-50 text-emerald-700
                                @elseif($task->status === 'Working' || $task->status === 'In Progress') bg-blue-50 text-blue-700
                                @elseif($task->status === 'Reworking') bg-amber-50 text-amber-700
                                @else bg-slate-100 text-slate-600 @endif">
                                {{ $task->status }}
                            </span>
                            @if($task->due_date)
                                <span class="text-slate-400 text-[11px]">{{ $task->due_date->format('d M, Y') }}</span>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-xs text-slate-400">
                        {{ __('No specific task breakdowns logged for this project yet.') }}
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Need Help / Support on this Project Callout -->
        <div class="p-6 rounded-2xl bg-gradient-to-r from-indigo-50 to-blue-50 border border-indigo-100 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <h4 class="font-bold text-indigo-950 text-sm">{{ __('Have questions or modification requests regarding this project?') }}</h4>
                <p class="text-xs text-indigo-700/80 mt-0.5">{{ __('Our dedicated support engineering team is ready to assist you.') }}</p>
            </div>
            <a href="{{ route('portal.tickets.create', ['project_id' => $project->id]) }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl shadow-xs transition shrink-0">
                {{ __('Open Support Ticket') }} &rarr;
            </a>
        </div>
    </div>
</x-portal-layout>

