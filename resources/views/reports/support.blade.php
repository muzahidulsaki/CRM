<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('reports.index') }}" class="text-xs font-semibold text-indigo-600 hover:underline">&larr; Reports Hub</a>
                    <span class="text-gray-300">/</span>
                    <span class="text-xs font-semibold text-gray-500">Support & Helpdesk SLA</span>
                </div>
                <h2 class="font-bold text-2xl text-gray-900 leading-tight mt-1 flex items-center gap-2">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    {{ __('Support Helpdesk & SLA Performance Report') }}
                </h2>
                <p class="text-sm text-gray-500 mt-0.5">
                    ক্লায়েন্ট সাপোর্ট টিকেট সমাধান রেট, রিওয়ার্ক ট্র্যাকিং ও ডেভেলপার রেজোলিউশন পারফরম্যান্স
                </p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('reports.export', ['type' => 'support_tickets', 'range' => $filter]) }}" class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-semibold bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl shadow-sm transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    টিকেট CSV ডাউনলোড
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
                <a href="{{ route('reports.support', ['range' => 'today']) }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold {{ $filter === 'today' ? 'bg-purple-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">আজকে</a>
                <a href="{{ route('reports.support', ['range' => 'yesterday']) }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold {{ $filter === 'yesterday' ? 'bg-purple-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">গতকাল</a>
                <a href="{{ route('reports.support', ['range' => 'last_7_days']) }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold {{ $filter === 'last_7_days' ? 'bg-purple-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">গত ৭ দিন</a>
                <a href="{{ route('reports.support', ['range' => 'this_month']) }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold {{ $filter === 'this_month' ? 'bg-purple-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">চলতি মাস</a>
                <a href="{{ route('reports.support', ['range' => 'last_month']) }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold {{ $filter === 'last_month' ? 'bg-purple-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">গত মাস</a>
                <a href="{{ route('reports.support', ['range' => 'this_year']) }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold {{ $filter === 'this_year' ? 'bg-purple-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">চলতি বছর</a>
            </div>

            <!-- Key SLA Metrics Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm">
                    <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">মোট সাপোর্ট টিকেট</span>
                    <div class="mt-2 text-3xl font-black text-gray-900">{{ $data['total_tickets'] }}</div>
                    <p class="text-xs text-gray-500 mt-1">নির্বাচিত সময়ে আসা ইস্যু</p>
                </div>
                <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm">
                    <span class="text-xs font-bold text-emerald-600 uppercase tracking-wider">সমাধান হওয়া টিকেট</span>
                    <div class="mt-2 text-3xl font-black text-emerald-600">{{ $data['resolved_count'] }}</div>
                    <p class="text-xs text-emerald-700 font-medium mt-1">রেজোলিউশন রেট: <strong>{{ $data['resolution_rate'] }}%</strong></p>
                </div>
                <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm">
                    <span class="text-xs font-bold text-amber-600 uppercase tracking-wider">রিওয়ার্ক টিকেট (Rework)</span>
                    <div class="mt-2 text-3xl font-black {{ ($data['status_counts']['Reworking'] ?? 0) > 0 ? 'text-rose-600' : 'text-gray-900' }}">
                        {{ $data['status_counts']['Reworking'] ?? 0 }}
                    </div>
                    <p class="text-xs text-gray-500 mt-1">ক্লায়েন্ট কর্তৃক পুনরায় ওপেনকৃত</p>
                </div>
                <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm">
                    <span class="text-xs font-bold text-rose-600 uppercase tracking-wider">জরুরি ইস্যু (Critical/High)</span>
                    <div class="mt-2 text-3xl font-black text-rose-600">
                        {{ ($data['priority_counts']['Critical'] ?? 0) + ($data['priority_counts']['High'] ?? 0) }}
                    </div>
                    <p class="text-xs text-gray-500 mt-1">উচ্চ অগ্রাধিকার সম্পন্ন সমস্যা</p>
                </div>
            </div>

            <!-- Breakdown Grids -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Status Breakdown -->
                <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
                    <h3 class="font-bold text-base text-gray-900 mb-4 flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-purple-600"></span>
                        টিকেট স্ট্যাটাস ব্রেকডাউন (Ticket Statuses)
                    </h3>
                    @if(count($data['status_counts']) > 0)
                        <div class="space-y-3">
                            @foreach($data['status_counts'] as $status => $count)
                                @php
                                    $pct = $data['total_tickets'] > 0 ? round(($count / $data['total_tickets']) * 100, 1) : 0;
                                @endphp
                                <div>
                                    <div class="flex justify-between items-center text-xs font-semibold mb-1">
                                        <span class="text-gray-800">{{ $status }}</span>
                                        <span class="text-gray-600">{{ $count }} টি ({{ $pct }}%)</span>
                                    </div>
                                    <div class="w-full bg-gray-100 rounded-full h-2 overflow-hidden">
                                        <div class="bg-purple-600 h-2 rounded-full" style="width: {{ $pct }}%"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-xs text-gray-400 italic py-4 text-center">এই সময়কালে কোনো টিকেট নেই।</p>
                    @endif
                </div>

                <!-- Priority Breakdown -->
                <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
                    <h3 class="font-bold text-base text-gray-900 mb-4 flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-rose-600"></span>
                        অগ্রাধিকার বন্টন (Priority Distribution)
                    </h3>
                    @if(count($data['priority_counts']) > 0)
                        <div class="space-y-3">
                            @foreach($data['priority_counts'] as $priority => $count)
                                @php
                                    $pct = $data['total_tickets'] > 0 ? round(($count / $data['total_tickets']) * 100, 1) : 0;
                                    $color = $priority === 'Critical' ? 'bg-rose-600' : ($priority === 'High' ? 'bg-orange-500' : ($priority === 'Medium' ? 'bg-amber-500' : 'bg-emerald-500'));
                                @endphp
                                <div>
                                    <div class="flex justify-between items-center text-xs font-semibold mb-1">
                                        <span class="text-gray-800">{{ $priority }}</span>
                                        <span class="text-gray-600">{{ $count }} টি ({{ $pct }}%)</span>
                                    </div>
                                    <div class="w-full bg-gray-100 rounded-full h-2 overflow-hidden">
                                        <div class="{{ $color }} h-2 rounded-full" style="width: {{ $pct }}%"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-xs text-gray-400 italic py-4 text-center">এই সময়কালে কোনো প্রায়োরিটি ডাটা নেই।</p>
                    @endif
                </div>
            </div>

            <!-- Developer Resolution Leaderboard -->
            <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
                <h3 class="font-bold text-base text-gray-900 mb-4 flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-indigo-600"></span>
                    ডেভেলপার রেজোলিউশন পারফরম্যান্স (Developer Resolution Leaderboard)
                </h3>
                @if(count($data['dev_stats']) > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($data['dev_stats'] as $devName => $stat)
                            <div class="p-4 rounded-xl border border-gray-100 bg-gray-50/60 flex flex-col justify-between">
                                <div class="font-bold text-sm text-gray-900">{{ $devName }}</div>
                                <div class="mt-3 pt-2 border-t flex justify-between items-center text-xs">
                                    <span class="text-gray-600">মোট অ্যাসাইনড: <strong>{{ $stat['total'] }}</strong></span>
                                    <span class="text-emerald-700 font-bold">সমাধান: {{ $stat['resolved'] }} ({{ $stat['total'] > 0 ? round(($stat['resolved'] / $stat['total']) * 100) : 0 }}%)</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-xs text-gray-400 italic py-4 text-center">কোনো ডেভেলপার অ্যাসাইনমেন্ট পাওয়া যায়নি।</p>
                @endif
            </div>

            <!-- Tickets List Table -->
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="p-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                    <h3 class="font-bold text-base text-gray-900">
                        সাপোর্ট টিকেট তালিকা (Tickets: {{ count($data['tickets']) }})
                    </h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-gray-700">
                        <thead class="bg-gray-50 text-gray-500 uppercase tracking-wider font-semibold border-b">
                            <tr>
                                <th class="p-3.5">টিকেট নং</th>
                                <th class="p-3.5">কাস্টমার</th>
                                <th class="p-3.5">বিষয়</th>
                                <th class="p-3.5">ক্যাটাগরি</th>
                                <th class="p-3.5 text-center">প্রায়োরিটি</th>
                                <th class="p-3.5 text-center">স্ট্যাটাস</th>
                                <th class="p-3.5">অ্যাসাইনড ডেভেলপার</th>
                                <th class="p-3.5">তারিখ</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($data['tickets'] as $t)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="p-3.5 font-bold text-purple-600">
                                        <a href="{{ route('support-tickets.show', $t->id) }}">{{ $t->ticket_no }}</a>
                                    </td>
                                    <td class="p-3.5 font-medium text-gray-900">{{ $t->customer->name ?? $t->contact_name ?? 'Guest' }}</td>
                                    <td class="p-3.5 text-gray-800 font-medium">{{ \Illuminate\Support\Str::limit($t->subject, 35) }}</td>
                                    <td class="p-3.5 text-gray-500">{{ $t->issue_type }}</td>
                                    <td class="p-3.5 text-center">
                                        <span class="px-2 py-0.5 rounded text-[11px] font-semibold {{ $t->priority === 'Critical' ? 'bg-rose-100 text-rose-800' : ($t->priority === 'High' ? 'bg-orange-100 text-orange-800' : 'bg-gray-100 text-gray-700') }}">
                                            {{ $t->priority }}
                                        </span>
                                    </td>
                                    <td class="p-3.5 text-center">
                                        <span class="px-2 py-0.5 rounded-full text-[11px] font-semibold bg-gray-100 text-gray-800">
                                            {{ $t->status }}
                                        </span>
                                    </td>
                                    <td class="p-3.5 text-gray-700">{{ $t->assignedDeveloper->name ?? 'Unassigned' }}</td>
                                    <td class="p-3.5 text-gray-400">{{ $t->created_at->format('d M, Y') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="p-8 text-center text-gray-400 italic">
                                        এই সময়কালে কোনো সাপোর্ট টিকেট পাওয়া যায়নি।
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
