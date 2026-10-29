<x-portal-layout>
    <x-slot name="title">{{ __('My Projects') }}</x-slot>

    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight">{{ __('My Contracted Projects') }}</h1>
                <p class="text-xs text-slate-500 mt-0.5">{{ __('Realtime progress tracking, live demo links & task milestones') }}</p>
            </div>
        </div>

        <!-- Projects Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @forelse($projects as $project)
                <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-2xs hover:shadow-md transition flex flex-col justify-between">
                    <div>
                        <!-- Top Row: Code & Status -->
                        <div class="flex items-center justify-between gap-2">
                            <span class="px-2.5 py-1 rounded-md text-[11px] font-mono font-bold bg-slate-100 text-slate-700">
                                {{ $project->project_code }}
                            </span>
                            <span class="px-2.5 py-1 rounded-full text-xs font-bold 
                                @if($project->status === 'Completed') bg-emerald-50 text-emerald-700 border border-emerald-200
                                @elseif($project->status === 'In Progress') bg-blue-50 text-blue-700 border border-blue-200
                                @elseif($project->status === 'Testing' || $project->status === 'QA/Review') bg-purple-50 text-purple-700 border border-purple-200
                                @else bg-slate-100 text-slate-700 border border-slate-200 @endif">
                                {{ $project->status }}
                            </span>
                        </div>

                        <!-- Project Name & Description -->
                        <h2 class="text-lg font-bold text-slate-900 mt-3 hover:text-indigo-600 transition">
                            <a href="{{ route('portal.projects.show', $project) }}">
                                {{ $project->name }}
                            </a>
                        </h2>
                        <p class="text-xs text-slate-500 mt-1 line-clamp-2">
                            {{ $project->description ?: __('No detailed description provided.') }}
                        </p>

                        <!-- Category & PM -->
                        <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                            <div>
                                <span class="font-medium text-slate-700">{{ __('Category') }}:</span>
                                <span>{{ $project->category ?? 'Software/Web' }}</span>
                            </div>
                            @if($project->projectManager)
                                <div>
                                    <span class="font-medium text-slate-700">{{ __('PM') }}:</span>
                                    <span>{{ $project->projectManager->name }}</span>
                                </div>
                            @endif
                        </div>

                        <!-- Progress Bar -->
                        <div class="mt-4">
                            <div class="flex justify-between text-xs font-semibold text-slate-600 mb-1">
                                <span>{{ __('Development Progress') }}</span>
                                <span class="text-indigo-600 font-bold">{{ $project->progress_percent }}%</span>
                            </div>
                            <div class="w-full bg-slate-100 rounded-full h-2.5 overflow-hidden">
                                <div class="h-full rounded-full bg-gradient-to-r from-indigo-500 to-blue-500" style="width: {{ $project->progress_percent }}%"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer Action Links -->
                    <div class="mt-5 pt-3 border-t border-slate-100 flex items-center justify-between gap-3">
                        <div class="text-xs text-slate-500">
                            @if($project->deadline)
                                <span>{{ __('Deadline') }}: <strong class="text-slate-800">{{ $project->deadline->format('d M, Y') }}</strong></span>
                            @endif
                        </div>
                        <div class="flex items-center gap-2">
                            @if($project->live_url)
                                <a href="{{ $project->live_url }}" target="_blank" class="px-3 py-1.5 rounded-lg text-xs font-semibold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 transition flex items-center gap-1">
                                    <span>Live Demo</span> ↗
                                </a>
                            @endif
                            <a href="{{ route('portal.projects.show', $project) }}" class="px-3 py-1.5 rounded-lg text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 transition">
                                {{ __('Details') }} &rarr;
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full bg-white p-12 rounded-2xl border border-slate-200 text-center text-slate-400">
                    <p class="text-base font-semibold">{{ __('No projects registered yet.') }}</p>
                    <p class="text-xs mt-1">{{ __('When your order is confirmed, projects will appear here automatically.') }}</p>
                </div>
            @endforelse
        </div>

        <div class="mt-4">
            {{ $projects->links() }}
        </div>
    </div>
</x-portal-layout>

