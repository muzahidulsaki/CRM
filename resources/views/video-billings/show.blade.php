<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <div class="flex items-center gap-2">
                    <span class="text-xs font-mono font-bold bg-indigo-100 text-indigo-700 px-2.5 py-1 rounded-lg">
                        {{ $videoBilling->billing_code }}
                    </span>
                    <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                        {{ $videoBilling->video_title }}
                    </h2>
                    @php
                        $stClass = match($videoBilling->status) {
                            'Approved' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                            'Submitted' => 'bg-amber-50 text-amber-700 border-amber-200',
                            'Rejected' => 'bg-rose-50 text-rose-700 border-rose-200',
                            default => 'bg-gray-50 text-gray-700 border-gray-200'
                        };
                    @endphp
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold border {{ $stClass }}">
                        {{ $videoBilling->status }}
                    </span>
                </div>
                <p class="text-sm text-gray-500 mt-1">ভিডিও এডিটর: <strong>{{ $videoBilling->employee->user->name }}</strong> ({{ $videoBilling->employee->designation }})</p>
            </div>

            <div class="flex items-center gap-2">
                @if($videoBilling->status !== 'Approved')
                    <form method="POST" action="{{ route('video-billings.approve', $videoBilling) }}">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white rounded-lg text-sm font-semibold hover:bg-emerald-700 shadow-sm transition">
                            <svg class="w-4 h-4 me-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            অনুমোদন করুন
                        </button>
                    </form>
                @elseif($videoBilling->due_amount > 0)
                    <a href="{{ route('payouts.create', ['employee_id' => $videoBilling->employee_id, 'video_billing_id' => $videoBilling->id, 'payout_type' => 'video_billing']) }}"
                       class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm font-semibold hover:bg-indigo-700 shadow-sm transition">
                        <svg class="w-4 h-4 me-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        পারিশ্রমিক পরিশোধ করুন
                    </a>
                @endif

                <a href="{{ route('video-billings.edit', $videoBilling) }}" class="inline-flex items-center px-3.5 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm font-semibold hover:bg-gray-200 transition">
                    সম্পাদনা
                </a>
                <a href="{{ route('video-billings.index') }}" class="inline-flex items-center px-3.5 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm font-semibold hover:bg-gray-200 transition">
                    তালিকায় ফিরুন
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Flash Alert -->
            @if(session('success'))
                <div class="p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 rounded-r-lg shadow-sm flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        <span class="font-medium text-sm">{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            <!-- Three-Card Financial & Duration Formula Banner -->
            <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm">
                    <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider">ভিডিও দৈর্ঘ্য ও সেকেন্ড</div>
                    <div class="text-2xl font-black text-gray-900 mt-1">
                        {{ $videoBilling->duration_minutes }}মি. {{ $videoBilling->duration_seconds }}সে.
                    </div>
                    <div class="text-xs font-mono text-indigo-600 mt-0.5">মোট = {{ number_format($videoBilling->total_seconds) }} সেকেন্ড</div>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm">
                    <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider">রেট ও পরিমাণ</div>
                    <div class="text-2xl font-black text-indigo-700 mt-1">
                        ৳{{ number_format($videoBilling->rate_per_second, 2) }} <span class="text-xs font-normal text-gray-500">/সে.</span>
                    </div>
                    <div class="text-xs text-gray-500 mt-0.5">ভিডিও সংখ্যা: {{ $videoBilling->number_of_videos }} টি</div>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-emerald-200 bg-emerald-50/30 shadow-sm">
                    <div class="text-xs font-semibold text-emerald-700 uppercase tracking-wider">মোট প্রদেয় পারিশ্রমিক</div>
                    <div class="text-2xl font-black text-emerald-800 mt-1">
                        ৳{{ number_format($videoBilling->total_payable, 2) }}
                    </div>
                    <div class="text-xs text-emerald-600 mt-0.5">পরিশোধ: ৳{{ number_format($videoBilling->paid_amount, 2) }}</div>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-rose-200 bg-rose-50/30 shadow-sm">
                    <div class="text-xs font-semibold text-rose-700 uppercase tracking-wider">বকেয়া পাওনা (Due)</div>
                    <div class="text-2xl font-black text-rose-700 mt-1">
                        ৳{{ number_format($videoBilling->due_amount, 2) }}
                    </div>
                    <div class="text-xs text-rose-600 mt-0.5">স্ট্যাটাস: {{ $videoBilling->payment_status }}</div>
                </div>
            </div>

            <!-- Two-Column Breakdown -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- Left Column: Calculation Details & Deliverables -->
                <div class="lg:col-span-2 space-y-6">

                    <!-- Formula Explanation Card -->
                    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 space-y-4">
                        <h3 class="font-bold text-gray-900 border-b border-gray-100 pb-3 flex items-center gap-2">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                            পার-সেকেন্ড বিলিং হিসাব সূত্র (SRS সেকশন ১৩)
                        </h3>

                        <div class="p-4 bg-gray-50 rounded-xl border border-gray-200 space-y-3 font-mono text-xs">
                            <div class="flex items-center justify-between border-b pb-2">
                                <span class="text-gray-600">১. ভিডিওর মোট দৈর্ঘ্য:</span>
                                <span class="font-bold text-gray-900">({{ $videoBilling->duration_minutes }} মিনিট &times; ৬০) + {{ $videoBilling->duration_seconds }} সেকেন্ড = {{ number_format($videoBilling->total_seconds) }} সেকেন্ড</span>
                            </div>
                            <div class="flex items-center justify-between border-b pb-2">
                                <span class="text-gray-600">২. প্রতি সেকেন্ডের চুক্তি রেট:</span>
                                <span class="font-bold text-gray-900">৳{{ number_format($videoBilling->rate_per_second, 2) }}</span>
                            </div>
                            <div class="flex items-center justify-between border-b pb-2">
                                <span class="text-gray-600">৩. ভিডিও সংখ্যা (Quantity):</span>
                                <span class="font-bold text-gray-900">{{ $videoBilling->number_of_videos }} টি</span>
                            </div>
                            <div class="flex items-center justify-between pt-1 text-sm text-emerald-800 font-bold">
                                <span>মোট প্রদেয় বিল (Total Payable):</span>
                                <span>{{ number_format($videoBilling->total_seconds) }} &times; ৳{{ number_format($videoBilling->rate_per_second, 2) }} &times; {{ $videoBilling->number_of_videos }} = ৳{{ number_format($videoBilling->total_payable, 2) }}</span>
                            </div>
                        </div>

                        <!-- Deliverable URL preview -->
                        @if($videoBilling->video_url)
                            <div class="pt-2">
                                <span class="text-xs font-semibold text-gray-500 block mb-1">ভিডিও ডেলিভারেবল লিংক:</span>
                                <a href="{{ $videoBilling->video_url }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-50 text-indigo-700 hover:bg-indigo-100 border border-indigo-200 rounded-xl text-xs font-semibold transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                    Google Drive / YouTube এ ভিডিওটি দেখুন &rarr;
                                </a>
                            </div>
                        @endif

                        @if($videoBilling->notes)
                            <div class="pt-2">
                                <span class="text-xs font-semibold text-gray-500 block mb-1">কাজের নোট:</span>
                                <div class="p-3 bg-gray-50 rounded-lg text-sm text-gray-700 whitespace-pre-line">{{ $videoBilling->notes }}</div>
                            </div>
                        @endif
                    </div>

                    <!-- Approval & Verification Card -->
                    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 space-y-3">
                        <h3 class="font-bold text-gray-900 border-b border-gray-100 pb-2">অনুমোদন ও যাচাইকরণ</h3>
                        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                            <div>
                                <dt class="text-gray-500 font-medium">কাজের বর্তমান অবস্থা:</dt>
                                <dd class="font-bold text-gray-900 mt-0.5">{{ $videoBilling->status }}</dd>
                            </div>
                            <div>
                                <dt class="text-gray-500 font-medium">অনুমোদনকারী:</dt>
                                <dd class="font-semibold text-gray-900 mt-0.5">{{ $videoBilling->approvedByUser?->name ?? 'এখনও অনুমোদিত হয়নি' }}</dd>
                            </div>
                            <div>
                                <dt class="text-gray-500 font-medium">অনুমোদনের তারিখ:</dt>
                                <dd class="text-gray-900 mt-0.5">{{ $videoBilling->approved_at ? $videoBilling->approved_at->format('d M, Y h:i A') : '-' }}</dd>
                            </div>
                            <div>
                                <dt class="text-gray-500 font-medium">এন্ট্রি তৈরি করেছেন:</dt>
                                <dd class="text-gray-900 mt-0.5">{{ $videoBilling->creator?->name }} ({{ $videoBilling->created_at->format('d M, Y') }})</dd>
                            </div>
                        </dl>
                    </div>

                </div>

                <!-- Right Column: Employee Profile & Links -->
                <div class="space-y-6">

                    <!-- Video Editor Profile Card -->
                    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 space-y-4">
                        <div class="flex items-center justify-between">
                            <h3 class="font-bold text-gray-900">ভিডিও এডিটর প্রোফাইল</h3>
                            <a href="{{ route('employees.show', $videoBilling->employee) }}" class="text-xs font-semibold text-indigo-600 hover:underline">
                                প্রোফাইল &rarr;
                            </a>
                        </div>

                        <div class="flex items-start gap-3">
                            <div class="w-12 h-12 rounded-full bg-indigo-100 text-indigo-700 font-bold flex items-center justify-center text-lg shrink-0">
                                {{ mb_substr($videoBilling->employee->user->name, 0, 1) }}
                            </div>
                            <div>
                                <div class="font-bold text-gray-900">{{ $videoBilling->employee->user->name }}</div>
                                <div class="text-xs text-gray-500">{{ $videoBilling->employee->designation }}</div>
                                <div class="text-xs font-mono text-indigo-600 mt-0.5">{{ $videoBilling->employee->employee_code }}</div>
                            </div>
                        </div>

                        <div class="space-y-2 text-xs text-gray-600 pt-2 border-t border-gray-100">
                            <div class="flex items-center justify-between">
                                <span class="text-gray-500">চুক্তির ধরন:</span>
                                <span class="font-semibold text-gray-900 capitalize">{{ $videoBilling->employee->employment_type }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-500">রেট:</span>
                                <span class="font-bold text-indigo-700">৳{{ number_format($videoBilling->employee->salary_or_rate, 2) }}/সে.</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-500">সর্বমোট বকেয়া পাওনা:</span>
                                <span class="font-bold text-rose-600">৳{{ number_format($videoBilling->employee->current_due, 2) }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Linked Project & Task -->
                    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 space-y-3">
                        <h3 class="font-bold text-gray-900 text-sm">সংযুক্ত প্রজেক্ট ও টাস্ক</h3>
                        <div class="space-y-2 text-xs">
                            @if($videoBilling->project)
                                <div class="p-3 bg-gray-50 rounded-xl">
                                    <div class="text-gray-500">প্রজেক্ট:</div>
                                    <a href="{{ route('projects.show', $videoBilling->project) }}" class="font-bold text-indigo-600 hover:underline">
                                        {{ $videoBilling->project->name }} ({{ $videoBilling->project->project_code }})
                                    </a>
                                </div>
                            @endif

                            @if($videoBilling->task)
                                <div class="p-3 bg-gray-50 rounded-xl">
                                    <div class="text-gray-500">টাস্ক:</div>
                                    <a href="{{ route('tasks.show', $videoBilling->task) }}" class="font-bold text-indigo-600 hover:underline">
                                        {{ $videoBilling->task->title }} ({{ $videoBilling->task->task_code }})
                                    </a>
                                </div>
                            @endif

                            @if($videoBilling->customer)
                                <div class="p-3 bg-gray-50 rounded-xl">
                                    <div class="text-gray-500">ক্লায়েন্ট:</div>
                                    <div class="font-bold text-gray-800">{{ $videoBilling->customer->name }}</div>
                                </div>
                            @endif
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </div>
</x-app-layout>
