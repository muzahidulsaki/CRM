<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight flex items-center gap-2">
                    <svg class="w-7 h-7 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    {{ __('Project Directory') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">{{ __('Track progress of ongoing software, website, and creative projects') }}</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('tasks.kanban') }}" class="inline-flex items-center px-4 py-2 bg-indigo-50 text-indigo-700 border border-indigo-200 rounded-lg font-semibold text-xs uppercase tracking-widest hover:bg-indigo-100 transition">
                    <svg class="w-4 h-4 me-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/></svg>
                    {{ __('Kanban Board') }}
                </a>
                <a href="{{ route('projects.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 shadow-sm transition">
                    <svg class="w-4 h-4 me-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    {{ __('New Project') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Metrics Cards -->
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
                <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm text-center">
                    <div class="text-xs font-semibold text-gray-500 uppercase">{{ __('Total Projects') }}</div>
                    <div class="text-2xl font-bold text-gray-900 mt-1">{{ $metrics['total'] }}</div>
                </div>

                <div class="bg-white p-4 rounded-xl border border-blue-200 bg-blue-50/30 shadow-sm text-center">
                    <div class="text-xs font-semibold text-blue-600 uppercase">{{ __('Active') }}</div>
                    <div class="text-2xl font-bold text-blue-700 mt-1">{{ $metrics['active'] }}</div>
                </div>

                <div class="bg-white p-4 rounded-xl border border-indigo-200 bg-indigo-50/30 shadow-sm text-center">
                    <div class="text-xs font-semibold text-indigo-600 uppercase">{{ __('In Development') }}</div>
                    <div class="text-2xl font-bold text-indigo-700 mt-1">{{ $metrics['in_development'] }}</div>
                </div>

                <div class="bg-white p-4 rounded-xl border border-purple-200 bg-purple-50/30 shadow-sm text-center">
                    <div class="text-xs font-semibold text-purple-600 uppercase">{{ __('In QA') }}</div>
                    <div class="text-2xl font-bold text-purple-700 mt-1">{{ $metrics['in_qa'] }}</div>
                </div>

                <div class="bg-white p-4 rounded-xl border border-emerald-200 bg-emerald-50/30 shadow-sm text-center">
                    <div class="text-xs font-semibold text-emerald-600 uppercase">{{ __('Completed') }}</div>
                    <div class="text-2xl font-bold text-emerald-700 mt-1">{{ $metrics['completed'] }}</div>
                </div>

                <div class="bg-white p-4 rounded-xl border border-rose-200 bg-rose-50/30 shadow-sm text-center">
                    <div class="text-xs font-semibold text-rose-600 uppercase">{{ __('Delayed') }}</div>
                    <div class="text-2xl font-bold text-rose-700 mt-1">{{ $metrics['delayed'] }}</div>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white p-4 rounded-2xl border border-gray-200 shadow-sm">
                <form method="GET" action="{{ route('projects.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 items-end">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">{{ __('Search') }}</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('Code, name, client...') }}"
                            class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">{{ __('Category') }}</label>
                        <select name="category" class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">{{ __('All Categories') }}</option>
                            @foreach($serviceCategories as $cat)
                                <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ __($cat) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">{{ __('Project Manager') }}</label>
                        <select name="pm_id" class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">{{ __('All Managers') }}</option>
                            @foreach($projectManagers as $pm)
                                <option value="{{ $pm->id }}" {{ request('pm_id') == $pm->id ? 'selected' : '' }}>{{ $pm->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">{{ __('Status') }}</label>
                        <select name="status" class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">{{ __('All Statuses') }}</option>
                            @foreach(['Pending', 'Planning', 'In Development', 'In QA', 'Client Review', 'Completed', 'Delivered', 'Delayed', 'Cancelled'] as $st)
                                <option value="{{ $st }}" {{ request('status') == $st ? 'selected' : '' }}>{{ __($st) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex items-center gap-2">
                        <button type="submit" class="flex-1 py-2 px-4 bg-gray-900 text-white rounded-lg text-sm font-semibold hover:bg-gray-800 transition">
                            {{ __('Filter') }}
                        </button>
                        @if(request()->anyFilled(['search', 'category', 'pm_id', 'status']))
                            <a href="{{ route('projects.index') }}" class="py-2 px-3 bg-gray-100 text-gray-600 rounded-lg text-sm font-medium hover:bg-gray-200 transition">
                                {{ __('Reset') }}
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Projects Grid / Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                @forelse($projects as $project)
                    <div class="bg-white rounded-2xl border border-gray-200 hover:border-indigo-300 shadow-sm hover:shadow-md transition p-5 flex flex-col justify-between space-y-4">
                        
                        <div>
                            <!-- Header & Status -->
                            <div class="flex items-start justify-between gap-2">
                                <span class="text-xs font-mono font-bold text-indigo-700 bg-indigo-50 px-2 py-0.5 rounded">
                                    {{ $project->project_code }}
                                </span>
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
                                <span class="px-2 py-0.5 rounded-full text-xs font-semibold border {{ $stClass }}">
                                    {{ __($project->status) }}
                                </span>
                            </div>

                            <!-- Title & Customer -->
                            <h3 class="font-bold text-gray-900 text-lg mt-2 line-clamp-1">
                                <a href="{{ route('projects.show', $project) }}" class="hover:text-indigo-600 transition">
                                    {{ $project->name }}
                                </a>
                            </h3>
                            <div class="text-xs text-gray-500 mt-1 flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                <span>{{ $project->customer?->name ?? __('No Client') }}</span>
                                <span class="text-gray-300">•</span>
                                <span class="text-gray-600">{{ __($project->category) }}</span>
                            </div>
                        </div>

                        <!-- Progress Bar & Tasks -->
                        <div class="space-y-2 pt-2 border-t border-gray-100">
                            <div class="flex items-center justify-between text-xs">
                                <span class="font-medium text-gray-600">{{ __('Progress') }}</span>
                                <span class="font-bold text-indigo-600">{{ $project->progress_percent }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2 overflow-hidden">
                                <div class="bg-indigo-600 h-2 rounded-full transition-all duration-300" style="width: {{ $project->progress_percent }}%"></div>
                            </div>
                            <div class="flex items-center justify-between text-xs text-gray-500 pt-1">
                                <span>{{ __('Total Tasks') }}: {{ $project->tasks->count() }}</span>
                                <span>{{ __('Completed') }}: {{ $project->tasks->whereIn('status', ['Checked', 'Delivered'])->count() }}</span>
                            </div>
                        </div>

                        <!-- Footer Info & Actions -->
                        <div class="pt-3 border-t border-gray-100 flex items-center justify-between text-xs">
                            <div class="text-gray-500">
                                @if($project->deadline)
                                    @php
                                        $isOverdue = $project->deadline->isPast() && !in_array($project->status, ['Completed', 'Delivered']);
                                    @endphp
                                    <span class="{{ $isOverdue ? 'text-rose-600 font-bold' : '' }}">
                                        {{ __('Deadline') }}: {{ $project->deadline->format('d M, Y') }}
                                    </span>
                                @else
                                    <span>{{ __('No Deadline') }}</span>
                                @endif
                            </div>

                            <a href="{{ route('projects.show', $project) }}" class="inline-flex items-center font-bold text-indigo-600 hover:text-indigo-800">
                                {{ __('Dashboard') }} &rarr;
                            </a>
                        </div>

                    </div>
                @empty
                    <div class="col-span-full py-12 text-center text-gray-400 bg-white rounded-2xl border border-gray-200">
                        <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                        <div class="text-base font-medium">{{ __('No projects found') }}</div>
                        <div class="text-sm mt-1">{{ __('Create projects automatically from confirmed orders or setup manually.') }}</div>
                    </div>
                @endforelse
            </div>

            @if($projects->hasPages())
                <div class="bg-white p-4 rounded-2xl border border-gray-200 shadow-sm">
                    {{ $projects->links() }}
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
