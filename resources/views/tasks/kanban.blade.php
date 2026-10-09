<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight flex items-center gap-2">
                    <svg class="w-7 h-7 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/></svg>
                    ভিজ্যুয়াল কানবান বোর্ড (Task Kanban Board)
                </h2>
                <p class="text-sm text-gray-500 mt-1">কাজের ৬-ধাপ লাইফসাইকেল: Pending &rarr; Working &rarr; Reworking &rarr; QA Review &rarr; Checked &rarr; Delivered</p>
            </div>
            <a href="{{ route('tasks.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm font-semibold hover:bg-gray-200 transition">
                <svg class="w-4 h-4 me-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                টাস্ক টেবিল ভিউ
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-[1700px] mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Filter Bar -->
            <div class="bg-white p-4 rounded-2xl border border-gray-200 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-4">
                <form method="GET" action="{{ route('tasks.kanban') }}" class="flex flex-wrap items-center gap-3 w-full sm:w-auto">
                    <div>
                        <select name="project_id" onchange="this.form.submit()" class="text-xs rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">সকল প্রজেক্টের টাস্ক</option>
                            @foreach($projects as $proj)
                                <option value="{{ $proj->id }}" {{ request('project_id') == $proj->id ? 'selected' : '' }}>
                                    {{ $proj->name }} ({{ $proj->project_code }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <select name="assigned_to" onchange="this.form.submit()" class="text-xs rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">সকল টিম মেম্বার</option>
                            @foreach($teamMembers as $tm)
                                <option value="{{ $tm->id }}" {{ request('assigned_to') == $tm->id ? 'selected' : '' }}>
                                    {{ $tm->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    @if(request()->anyFilled(['project_id', 'assigned_to']))
                        <a href="{{ route('tasks.kanban') }}" class="text-xs font-semibold text-gray-500 hover:text-gray-800">
                            ফিল্টার মুছুন
                        </a>
                    @endif
                </form>

                <div class="text-xs text-gray-500 hidden sm:block">
                    প্রতিটি কলামে টাস্কের বর্তমান অবস্থা ও পর্যালোচনার অগ্রগতি দেখুন
                </div>
            </div>

            <!-- Kanban Board Columns (6 Stages) -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4 items-start overflow-x-auto pb-4">
                @php
                    $stageStyles = [
                        'Pending' => ['header' => 'bg-gray-100 text-gray-700 border-gray-300', 'badge' => 'bg-gray-200 text-gray-800'],
                        'Working' => ['header' => 'bg-blue-50 text-blue-800 border-blue-200', 'badge' => 'bg-blue-100 text-blue-800'],
                        'Reworking' => ['header' => 'bg-rose-50 text-rose-800 border-rose-200', 'badge' => 'bg-rose-100 text-rose-800'],
                        'Completed' => ['header' => 'bg-amber-50 text-amber-800 border-amber-200', 'badge' => 'bg-amber-100 text-amber-800'],
                        'Checked' => ['header' => 'bg-purple-50 text-purple-800 border-purple-200', 'badge' => 'bg-purple-100 text-purple-800'],
                        'Delivered' => ['header' => 'bg-emerald-50 text-emerald-800 border-emerald-200', 'badge' => 'bg-emerald-100 text-emerald-800'],
                    ];
                @endphp

                @foreach($stages as $stageKey => $stageLabel)
                    @php
                        $stageTasks = $tasksByStage[$stageKey] ?? collect();
                        $style = $stageStyles[$stageKey] ?? $stageStyles['Pending'];
                    @endphp

                    <div class="bg-gray-100/70 rounded-2xl border border-gray-200 flex flex-col max-h-[85vh]">
                        <!-- Column Header -->
                        <div class="p-3.5 border-b {{ $style['header'] }} rounded-t-2xl flex items-center justify-between">
                            <span class="font-bold text-xs">{{ $stageLabel }}</span>
                            <span class="px-2 py-0.5 rounded-full text-xs font-bold {{ $style['badge'] }}">
                                {{ $stageTasks->count() }}
                            </span>
                        </div>

                        <!-- Cards Container -->
                        <div class="p-2.5 space-y-3 overflow-y-auto flex-1">
                            @forelse($stageTasks as $task)
                                <div class="bg-white rounded-xl border border-gray-200 hover:border-indigo-400 p-3.5 shadow-sm hover:shadow transition group relative">
                                    
                                    <!-- Priority & Task Code -->
                                    <div class="flex items-center justify-between gap-1 mb-1.5">
                                        <span class="font-mono text-[10px] font-bold text-indigo-700 bg-indigo-50 px-1.5 py-0.5 rounded">
                                            {{ $task->task_code }}
                                        </span>
                                        @php
                                            $pClass = match($task->priority) {
                                                'Urgent' => 'bg-rose-100 text-rose-700',
                                                'High' => 'bg-amber-100 text-amber-700',
                                                'Medium' => 'bg-blue-100 text-blue-700',
                                                default => 'bg-gray-100 text-gray-700'
                                            };
                                        @endphp
                                        <span class="text-[10px] font-semibold px-1.5 py-0.5 rounded {{ $pClass }}">
                                            {{ $task->priority }}
                                        </span>
                                    </div>

                                    <!-- Title -->
                                    <a href="{{ route('tasks.show', $task) }}" class="font-bold text-xs text-gray-900 group-hover:text-indigo-600 block line-clamp-2">
                                        {{ $task->title }}
                                    </a>

                                    <!-- Project Name -->
                                    <div class="text-[11px] text-gray-500 mt-1 line-clamp-1">
                                        📁 {{ $task->project?->name }}
                                    </div>

                                    <!-- Worker & Deadline Footer -->
                                    <div class="pt-2.5 mt-2 border-t border-gray-100 flex items-center justify-between text-[11px]">
                                        <div class="flex items-center gap-1.5">
                                            @if($task->assignedUser)
                                                <div class="w-5 h-5 rounded-full bg-indigo-100 text-indigo-700 font-bold flex items-center justify-center text-[10px]">
                                                    {{ mb_substr($task->assignedUser->name, 0, 1) }}
                                                </div>
                                                <span class="text-gray-700 truncate max-w-[75px]">{{ $task->assignedUser->name }}</span>
                                            @else
                                                <span class="text-gray-400 italic">অ্যাসাইন নেই</span>
                                            @endif
                                        </div>

                                        @if($task->deadline)
                                            <span class="text-gray-400 text-[10px] {{ $task->deadline->isPast() && !in_array($task->status, ['Checked', 'Delivered']) ? 'text-rose-600 font-bold' : '' }}">
                                                {{ $task->deadline->format('d M') }}
                                            </span>
                                        @endif
                                    </div>

                                    <!-- QA Tag if applicable -->
                                    @if($task->qa_status === 'Failed')
                                        <div class="mt-2 text-[10px] font-bold text-rose-600 bg-rose-50 px-2 py-0.5 rounded border border-rose-200">
                                            ⚠️ রি-ওয়ার্কিং প্রয়োজন
                                        </div>
                                    @elseif($task->qa_status === 'Passed')
                                        <div class="mt-2 text-[10px] font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200">
                                            ✓ কিউএ পাসড
                                        </div>
                                    @endif

                                </div>
                            @empty
                                <div class="py-8 text-center text-gray-400 text-xs">
                                    কোনো টাস্ক নেই
                                </div>
                            @endforelse
                        </div>

                    </div>
                @endforeach
            </div>

        </div>
    </div>
</x-app-layout>
