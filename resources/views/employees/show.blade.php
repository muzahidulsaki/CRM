<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-full bg-indigo-600 text-white font-bold flex items-center justify-center text-xl shrink-0 shadow-sm">
                    {{ mb_substr($employee->user->name, 0, 1) }}
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                            {{ $employee->user->name }}
                        </h2>
                        <span class="text-xs font-mono bg-gray-100 text-gray-700 px-2 py-0.5 rounded">
                            {{ $employee->employee_code }}
                        </span>
                    </div>
                    <p class="text-xs text-gray-500 mt-0.5">
                        {{ $employee->designation }} • {{ $employee->department }} ({{ $employee->user->role->name ?? 'Staff' }})
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('payouts.create', ['employee_id' => $employee->id, 'payout_type' => ($employee->payment_type === 'per_second_video' ? 'video_billing' : 'monthly_salary')]) }}"
                   class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white rounded-lg text-sm font-semibold hover:bg-emerald-700 shadow-sm transition">
                    <svg class="w-4 h-4 me-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    পারিশ্রমিক পরিশোধ করুন
                </a>
                <a href="{{ route('employees.edit', $employee) }}" class="inline-flex items-center px-3.5 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm font-semibold hover:bg-gray-200 transition">
                    সম্পাদনা
                </a>
                <a href="{{ route('employees.index') }}" class="inline-flex items-center px-3.5 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm font-semibold hover:bg-gray-200 transition">
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

            <!-- Financial Ledger 3-Card Banner -->
            <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm">
                    <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider">পারিশ্রমিক মডেল ও রেট</div>
                    <div class="text-xl font-bold text-gray-900 mt-1">
                        @if($employee->payment_type === 'per_second_video')
                            ৳{{ number_format($employee->salary_or_rate, 2) }} <span class="text-xs font-normal text-gray-500">/সেকেন্ড</span>
                        @elseif($employee->payment_type === 'hourly')
                            ৳{{ number_format($employee->salary_or_rate, 2) }} <span class="text-xs font-normal text-gray-500">/ঘণ্টা</span>
                        @else
                            ৳{{ number_format($employee->salary_or_rate) }} <span class="text-xs font-normal text-gray-500">/মাস</span>
                        @endif
                    </div>
                    <div class="text-xs text-indigo-600 mt-0.5 capitalize">{{ str_replace('_', ' ', $employee->payment_type) }}</div>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-indigo-200 bg-indigo-50/20 shadow-sm">
                    <div class="text-xs font-semibold text-indigo-700 uppercase tracking-wider">মোট কাজের বিল (Earned)</div>
                    <div class="text-2xl font-black text-indigo-900 mt-1">
                        ৳{{ number_format($financials['total_earned'], 2) }}
                    </div>
                    <div class="text-xs text-indigo-600 mt-0.5">{{ $financials['total_videos'] }} টি ভিডিও ({{ number_format($financials['total_seconds']) }} সে.)</div>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-emerald-200 bg-emerald-50/20 shadow-sm">
                    <div class="text-xs font-semibold text-emerald-700 uppercase tracking-wider">মোট পরিশোধিত (Paid)</div>
                    <div class="text-2xl font-black text-emerald-800 mt-1">
                        ৳{{ number_format($financials['total_paid'], 2) }}
                    </div>
                    <div class="text-xs text-emerald-600 mt-0.5">{{ $employee->payouts->count() }} টি পেমেন্ট ভাউচার</div>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-rose-200 bg-rose-50/20 shadow-sm">
                    <div class="text-xs font-semibold text-rose-700 uppercase tracking-wider">বকেয়া পাওনা (Current Due)</div>
                    <div class="text-2xl font-black text-rose-700 mt-1">
                        ৳{{ number_format($financials['current_due'], 2) }}
                    </div>
                    <div class="text-xs text-rose-500 mt-0.5">অপেক্ষমাণ পরিশোধ</div>
                </div>
            </div>

            <!-- Two Columns: Profile Details + Financial Ledgers -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- Left Column: Personal & Employment Profile -->
                <div class="space-y-6">
                    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 space-y-4">
                        <h3 class="font-bold text-gray-900 border-b border-gray-100 pb-2">কর্মী তথ্য ও প্রোফাইল</h3>

                        <dl class="space-y-2.5 text-xs">
                            <div class="flex items-center justify-between">
                                <dt class="text-gray-500">কর্মী কোড:</dt>
                                <dd class="font-mono font-bold text-indigo-700">{{ $employee->employee_code }}</dd>
                            </div>
                            <div class="flex items-center justify-between">
                                <dt class="text-gray-500">চুক্তির ধরন:</dt>
                                <dd class="font-semibold text-gray-900 capitalize">{{ $employee->employment_type }}</dd>
                            </div>
                            <div class="flex items-center justify-between">
                                <dt class="text-gray-500">ডিপার্টমেন্ট:</dt>
                                <dd class="font-medium text-gray-900">{{ $employee->department }}</dd>
                            </div>
                            <div class="flex items-center justify-between">
                                <dt class="text-gray-500">ইমেইল:</dt>
                                <dd class="text-gray-800">{{ $employee->user->email }}</dd>
                            </div>
                            <div class="flex items-center justify-between">
                                <dt class="text-gray-500">ফোন নম্বর:</dt>
                                <dd class="font-medium text-gray-900">{{ $employee->user->phone ?? 'নেই' }}</dd>
                            </div>
                            <div class="flex items-center justify-between">
                                <dt class="text-gray-500">জরুরি যোগাযোগ:</dt>
                                <dd class="text-gray-800">{{ $employee->emergency_contact ?: '-' }}</dd>
                            </div>
                            <div class="flex items-center justify-between">
                                <dt class="text-gray-500">যোগদানের তারিখ:</dt>
                                <dd class="text-gray-800">{{ $employee->joining_date ? $employee->joining_date->format('d M, Y') : '-' }}</dd>
                            </div>
                            <div class="flex items-center justify-between">
                                <dt class="text-gray-500">পরিশোধ শিডিউল:</dt>
                                <dd class="text-gray-800 capitalize">{{ str_replace('_', ' ', $employee->payment_schedule) }}</dd>
                            </div>
                            <div class="flex items-center justify-between">
                                <dt class="text-gray-500">স্ট্যাটাস:</dt>
                                <dd>
                                    <span class="px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        {{ $employee->status }}
                                    </span>
                                </dd>
                            </div>
                        </dl>

                        @if($employee->address)
                            <div class="pt-3 border-t border-gray-100 text-xs text-gray-600">
                                <span class="font-semibold text-gray-500 block mb-0.5">ঠিকানা:</span>
                                <p>{{ $employee->address }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Right Column: Tables (Video Billings, Payouts, Worklogs) -->
                <div class="lg:col-span-2 space-y-6">

                    <!-- Recent Video Billings Table -->
                    @if($employee->payment_type === 'per_second_video' || $employee->videoBillings->isNotEmpty())
                        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                            <div class="p-4 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
                                <h3 class="font-bold text-gray-900 text-sm flex items-center gap-2">
                                    <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                    ভিডিও বিলিং হিস্ট্রি (Video Billings)
                                </h3>
                                <a href="{{ route('video-billings.create', ['employee_id' => $employee->id]) }}" class="text-xs font-bold text-indigo-600 hover:underline">
                                    + নতুন ভিডিও বিল
                                </a>
                            </div>

                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 text-left text-xs">
                                    <thead class="bg-gray-50 text-gray-500 font-semibold uppercase">
                                        <tr>
                                            <th class="py-2.5 px-3">বিলিং কোড</th>
                                            <th class="py-2.5 px-3">ভিডিও নাম</th>
                                            <th class="py-2.5 px-3">দৈর্ঘ্য</th>
                                            <th class="py-2.5 px-3">মোট বিল (৳)</th>
                                            <th class="py-2.5 px-3">বকেয়া</th>
                                            <th class="py-2.5 px-3">পেমেন্ট</th>
                                            <th class="py-2.5 px-3 text-right">অ্যাকশন</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100">
                                        @forelse($employee->videoBillings as $vbill)
                                            <tr class="hover:bg-gray-50/50">
                                                <td class="py-2.5 px-3 font-mono font-bold text-indigo-600">
                                                    {{ $vbill->billing_code }}
                                                </td>
                                                <td class="py-2.5 px-3">
                                                    <a href="{{ route('video-billings.show', $vbill) }}" class="font-bold text-gray-900 hover:text-indigo-600">
                                                        {{ $vbill->video_title }}
                                                    </a>
                                                </td>
                                                <td class="py-2.5 px-3">
                                                    {{ $vbill->duration_minutes }}মি. {{ $vbill->duration_seconds }}সে. ({{ number_format($vbill->total_seconds) }} সে.)
                                                </td>
                                                <td class="py-2.5 px-3 font-bold text-gray-900">
                                                    ৳{{ number_format($vbill->total_payable, 2) }}
                                                </td>
                                                <td class="py-2.5 px-3 font-bold text-rose-600">
                                                    ৳{{ number_format($vbill->due_amount, 2) }}
                                                </td>
                                                <td class="py-2.5 px-3">
                                                    <span class="px-1.5 py-0.5 rounded text-[10px] font-semibold {{ $vbill->payment_status === 'Paid' ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                                        {{ $vbill->payment_status }}
                                                    </span>
                                                </td>
                                                <td class="py-2.5 px-3 text-right">
                                                    <a href="{{ route('video-billings.show', $vbill) }}" class="text-indigo-600 hover:underline font-semibold">
                                                        দেখুন &rarr;
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="py-6 text-center text-gray-400">কোনো ভিডিও বিলিং নেই</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif

                    <!-- Recent Payout Vouchers Table -->
                    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                        <div class="p-4 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
                            <h3 class="font-bold text-gray-900 text-sm flex items-center gap-2">
                                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                পরিশোধিত ভাউচার ও মানি রিসিট হিস্ট্রি
                            </h3>
                            <a href="{{ route('payouts.create', ['employee_id' => $employee->id]) }}" class="text-xs font-bold text-emerald-600 hover:underline">
                                + নতুন পেমেন্ট ভাউচার
                            </a>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 text-left text-xs">
                                <thead class="bg-gray-50 text-gray-500 font-semibold uppercase">
                                    <tr>
                                        <th class="py-2.5 px-3">ভাউচার নং</th>
                                        <th class="py-2.5 px-3">তারিখ</th>
                                        <th class="py-2.5 px-3">ক্যাটাগরি</th>
                                        <th class="py-2.5 px-3">পদ্ধতি</th>
                                        <th class="py-2.5 px-3">পরিশোধ (৳)</th>
                                        <th class="py-2.5 px-3 text-right">রিসিট</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @forelse($employee->payouts as $payout)
                                        <tr class="hover:bg-gray-50/50">
                                            <td class="py-2.5 px-3 font-mono font-bold text-emerald-700">
                                                {{ $payout->voucher_no }}
                                            </td>
                                            <td class="py-2.5 px-3 text-gray-600">
                                                {{ $payout->payment_date->format('d M, Y') }}
                                            </td>
                                            <td class="py-2.5 px-3 capitalize">
                                                {{ str_replace('_', ' ', $payout->payout_type) }}
                                            </td>
                                            <td class="py-2.5 px-3">
                                                <span class="px-2 py-0.5 rounded font-semibold bg-gray-100 text-gray-800">
                                                    {{ $payout->payment_method }}
                                                </span>
                                            </td>
                                            <td class="py-2.5 px-3 font-bold text-gray-900">
                                                ৳{{ number_format($payout->paid_amount, 2) }}
                                            </td>
                                            <td class="py-2.5 px-3 text-right">
                                                <a href="{{ route('payouts.show', $payout) }}" class="text-emerald-700 font-semibold hover:underline">
                                                    মানি রিসিট &rarr;
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="py-6 text-center text-gray-400">কোনো পেমেন্ট ভাউচার নেই</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </div>
</x-app-layout>
