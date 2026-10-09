<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('reports.index') }}" class="text-xs font-semibold text-indigo-600 hover:underline">&larr; Reports Hub</a>
                    <span class="text-gray-300">/</span>
                    <span class="text-xs font-semibold text-gray-500">Creative & Video Production</span>
                </div>
                <h2 class="font-bold text-2xl text-gray-900 leading-tight mt-1 flex items-center gap-2">
                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                    {{ __('Creative Video & Production Report') }}
                </h2>
                <p class="text-sm text-gray-500 mt-0.5">
                    ভিডিও এডিটিং প্রতি সেকেন্ড রেট বিলিং, টাস্ক প্রোডাক্টিভিটি ও সফটওয়্যার ডেলিভারি
                </p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('reports.export', ['type' => 'video_production', 'range' => $filter]) }}" class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-semibold bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl shadow-sm transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    ভিডিও বিলিং CSV
                </a>
                <button onclick="window.print()" class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-semibold bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 rounded-xl shadow-sm transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    প্রিন্ট
                </button>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Filter Range Bar -->
            <div class="bg-white p-4 rounded-2xl border border-gray-200 shadow-sm flex flex-wrap items-center gap-2">
                <span class="text-xs font-bold text-gray-500 uppercase tracking-wider me-2">ফিল্টার:</span>
                <a href="{{ route('reports.production', ['range' => 'today']) }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold {{ $filter === 'today' ? 'bg-amber-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">আজকে</a>
                <a href="{{ route('reports.production', ['range' => 'yesterday']) }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold {{ $filter === 'yesterday' ? 'bg-amber-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">গতকাল</a>
                <a href="{{ route('reports.production', ['range' => 'last_7_days']) }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold {{ $filter === 'last_7_days' ? 'bg-amber-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">গত ৭ দিন</a>
                <a href="{{ route('reports.production', ['range' => 'this_month']) }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold {{ $filter === 'this_month' ? 'bg-amber-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">চলতি মাস</a>
                <a href="{{ route('reports.production', ['range' => 'last_month']) }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold {{ $filter === 'last_month' ? 'bg-amber-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">গত মাস</a>
                <a href="{{ route('reports.production', ['range' => 'this_year']) }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold {{ $filter === 'this_year' ? 'bg-amber-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">চলতি বছর</a>
            </div>

            <!-- Key Performance Metric Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm">
                    <span class="text-xs font-bold text-amber-600 uppercase tracking-wider">মোট ভিডিও সংখ্যা</span>
                    <div class="mt-2 text-3xl font-black text-gray-900">{{ $data['total_videos'] }} <span class="text-xs font-semibold text-gray-500">টি ভিডিও</span></div>
                    <p class="text-xs text-gray-500 mt-1">নির্বাচিত সময়ে এডিট হয়েছে</p>
                </div>
                <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm">
                    <span class="text-xs font-bold text-blue-600 uppercase tracking-wider">মোট সেকেন্ড এডিট</span>
                    <div class="mt-2 text-3xl font-black text-blue-600">{{ number_format($data['total_seconds']) }} <span class="text-xs font-semibold text-gray-500">সেকেন্ড</span></div>
                    <p class="text-xs text-gray-500 mt-1">গড়ে প্রতি ভিডিও {{ $data['total_videos'] > 0 ? round($data['total_seconds'] / $data['total_videos']) : 0 }} সেকেন্ড</p>
                </div>
                <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm">
                    <span class="text-xs font-bold text-emerald-600 uppercase tracking-wider">মোট ভিডিও বিলিং কস্ট</span>
                    <div class="mt-2 text-3xl font-black text-emerald-600">৳{{ number_format($data['total_video_cost'], 2) }}</div>
                    <p class="text-xs text-gray-500 mt-1">এডিটরদের প্রদেয় পারিশ্রমিক</p>
                </div>
                <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm">
                    <span class="text-xs font-bold text-indigo-600 uppercase tracking-wider">ডেভেলপার আওয়ারস</span>
                    <div class="mt-2 text-3xl font-black text-indigo-600">{{ number_format($data['total_worklog_hours'], 1) }} <span class="text-xs font-semibold text-gray-500">ঘণ্টা</span></div>
                    <p class="text-xs text-gray-500 mt-1">টাস্ক সম্পন্ন: {{ $data['tasks_completed'] }} টি</p>
                </div>
            </div>

            <!-- Editor Performance Leaderboard -->
            <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
                <h3 class="font-bold text-base text-gray-900 mb-4 flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-amber-600"></span>
                    ভিডিও এডিটর পারফরম্যান্স লিডারবোর্ড (Video Editors Productivity)
                </h3>
                @if(count($data['editor_stats']) > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($data['editor_stats'] as $editorName => $stat)
                            <div class="p-4 rounded-xl border border-gray-100 bg-gray-50/60 flex flex-col justify-between">
                                <div>
                                    <div class="font-bold text-sm text-gray-900">{{ $editorName }}</div>
                                    <div class="text-xs text-gray-500 mt-1">{{ $stat['count'] }} টি ভিডিও সম্পন্ন</div>
                                </div>
                                <div class="mt-3 pt-2 border-t flex justify-between items-center text-xs">
                                    <span class="font-bold text-blue-600">{{ number_format($stat['seconds']) }} সেকেন্ড</span>
                                    <span class="font-bold text-emerald-600">৳{{ number_format($stat['amount'], 2) }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-xs text-gray-400 italic py-4 text-center">এই সময়কালে কোনো ভিডিও বিলিং রেকর্ড নেই।</p>
                @endif
            </div>

            <!-- Video Billings Table -->
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="p-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                    <h3 class="font-bold text-base text-gray-900">
                        সম্পাদিত ভিডিওর তালিকা ও সেকেন্ড ভিত্তিক রেট (Video Billings: {{ count($data['video_billings']) }})
                    </h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-gray-700">
                        <thead class="bg-gray-50 text-gray-500 uppercase tracking-wider font-semibold border-b">
                            <tr>
                                <th class="p-3.5">বিলিং নং</th>
                                <th class="p-3.5">ভিডিও এডিটর</th>
                                <th class="p-3.5">প্রজেক্ট / রেফারেন্স</th>
                                <th class="p-3.5">দৈর্ঘ্য (মিনিট:সেকেন্ড)</th>
                                <th class="p-3.5 text-center">মোট সেকেন্ড</th>
                                <th class="p-3.5 text-right">রেট / সেকেন্ড</th>
                                <th class="p-3.5 text-right">মোট বিল</th>
                                <th class="p-3.5 text-center">স্ট্যাটাস</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($data['video_billings'] as $vb)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="p-3.5 font-bold text-indigo-600">{{ $vb->billing_code }}</td>
                                    <td class="p-3.5 font-semibold text-gray-900">{{ $vb->employee->user->name ?? 'N/A' }}</td>
                                    <td class="p-3.5 text-gray-600">{{ $vb->project->name ?? $vb->video_title ?? 'Direct Order' }}</td>
                                    <td class="p-3.5 font-mono">{{ sprintf('%02d:%02d', $vb->duration_minutes, $vb->duration_seconds) }}</td>
                                    <td class="p-3.5 font-bold text-blue-600 text-center">{{ $vb->total_seconds }}s</td>
                                    <td class="p-3.5 text-right text-gray-700">৳{{ number_format($vb->rate_per_second, 2) }}</td>
                                    <td class="p-3.5 font-bold text-emerald-600 text-right">৳{{ number_format($vb->total_payable, 2) }}</td>
                                    <td class="p-3.5 text-center">
                                        <span class="px-2 py-0.5 rounded-full text-[11px] font-semibold {{ strtolower($vb->status) === 'approved' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                            {{ ucfirst($vb->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="p-8 text-center text-gray-400 italic">
                                        এই সময়কালে কোনো ভিডিও বিলিং পাওয়া যায়নি।
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Delivered Projects in Range -->
            @if(count($data['delivered_projects']) > 0)
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
                    <h3 class="font-bold text-base text-gray-900 mb-4 flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-600"></span>
                        এই সময়কালে ডেলিভারি হওয়া প্রজেক্টসমূহ (Delivered Projects)
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        @foreach($data['delivered_projects'] as $dp)
                            <div class="p-3.5 rounded-xl border border-emerald-100 bg-emerald-50/40 flex justify-between items-center text-xs">
                                <div>
                                    <span class="font-bold text-gray-900">{{ $dp->project_code }} - {{ $dp->name }}</span>
                                    <p class="text-gray-500 mt-0.5">ক্লায়েন্ট: {{ $dp->customer->name ?? 'N/A' }}</p>
                                </div>
                                <span class="px-2.5 py-1 bg-emerald-100 text-emerald-800 font-semibold rounded-lg text-[11px]">
                                    Delivered
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
