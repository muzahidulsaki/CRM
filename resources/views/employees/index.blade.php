<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight flex items-center gap-2">
                    <svg class="w-7 h-7 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    টিম ডিরেক্টরি ও কর্মী লেজার (Employees)
                </h2>
                <p class="text-sm text-gray-500 mt-1">স্থায়ী, চুক্তিভিত্তিক ও ফ্রিল্যান্সার কর্মী প্রোফাইল এবং আর্থিক পাওনা-দেনা ট্র্যাকিং</p>
            </div>
            <a href="{{ route('employees.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 shadow-sm transition">
                <svg class="w-4 h-4 me-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                নতুন কর্মী যুক্ত করুন
            </a>
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

            <!-- Metrics -->
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3">
                <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm text-center">
                    <div class="text-xs font-semibold text-gray-500 uppercase">মোট কর্মী</div>
                    <div class="text-2xl font-bold text-gray-900 mt-1">{{ $metrics['total'] }}</div>
                </div>

                <div class="bg-white p-4 rounded-xl border border-indigo-200 bg-indigo-50/30 shadow-sm text-center">
                    <div class="text-xs font-semibold text-indigo-600 uppercase">স্থায়ী (Permanent)</div>
                    <div class="text-2xl font-bold text-indigo-700 mt-1">{{ $metrics['permanent'] }}</div>
                </div>

                <div class="bg-white p-4 rounded-xl border border-amber-200 bg-amber-50/30 shadow-sm text-center">
                    <div class="text-xs font-semibold text-amber-600 uppercase">চুক্তিভিত্তিক (Contractual)</div>
                    <div class="text-2xl font-bold text-amber-700 mt-1">{{ $metrics['contractual'] }}</div>
                </div>

                <div class="bg-white p-4 rounded-xl border border-purple-200 bg-purple-50/30 shadow-sm text-center">
                    <div class="text-xs font-semibold text-purple-600 uppercase">ফ্রিল্যান্সার (Freelance)</div>
                    <div class="text-2xl font-bold text-purple-700 mt-1">{{ $metrics['freelance'] }}</div>
                </div>

                <div class="bg-white p-4 rounded-xl border border-emerald-200 bg-emerald-50/30 shadow-sm text-center col-span-2 sm:col-span-1">
                    <div class="text-xs font-semibold text-emerald-600 uppercase">সক্রিয় কর্মী (Active)</div>
                    <div class="text-2xl font-bold text-emerald-700 mt-1">{{ $metrics['active'] }}</div>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white p-4 rounded-2xl border border-gray-200 shadow-sm">
                <form method="GET" action="{{ route('employees.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 items-end">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">অনুসন্ধান</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="নাম, ইমেইল, পদবী, কোড..."
                            class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">চুক্তির ধরন</label>
                        <select name="employment_type" class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">সকল ধরন</option>
                            <option value="permanent" {{ request('employment_type') == 'permanent' ? 'selected' : '' }}>Permanent (স্থায়ী)</option>
                            <option value="contractual" {{ request('employment_type') == 'contractual' ? 'selected' : '' }}>Contractual (চুক্তিভিত্তিক)</option>
                            <option value="freelance" {{ request('employment_type') == 'freelance' ? 'selected' : '' }}>Freelance (ফ্রিল্যান্স)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">পারিশ্রমিক মডেল</label>
                        <select name="payment_type" class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">সকল পেমেন্ট টাইপ</option>
                            <option value="monthly_salary" {{ request('payment_type') == 'monthly_salary' ? 'selected' : '' }}>মাসিক ফিক্সড বেতন</option>
                            <option value="per_second_video" {{ request('payment_type') == 'per_second_video' ? 'selected' : '' }}>ভিডিও প্রতি-সেকেন্ড রেট</option>
                            <option value="hourly" {{ request('payment_type') == 'hourly' ? 'selected' : '' }}>ঘণ্টাভিত্তিক (Hourly)</option>
                            <option value="per_task" {{ request('payment_type') == 'per_task' ? 'selected' : '' }}>টাস্ক চুক্তি</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">স্ট্যাটাস</label>
                        <select name="status" class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">সকল স্ট্যাটাস</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>সক্রিয়</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>নিষ্ক্রিয়</option>
                            <option value="on_leave" {{ request('status') == 'on_leave' ? 'selected' : '' }}>ছুটিতে</option>
                        </select>
                    </div>

                    <div class="flex items-center gap-2">
                        <button type="submit" class="flex-1 py-2 px-4 bg-gray-900 text-white rounded-lg text-sm font-semibold hover:bg-gray-800 transition">
                            ফিল্টার
                        </button>
                        @if(request()->anyFilled(['search', 'employment_type', 'payment_type', 'status']))
                            <a href="{{ route('employees.index') }}" class="py-2 px-3 bg-gray-100 text-gray-600 rounded-lg text-sm font-medium hover:bg-gray-200 transition">
                                রিসেট
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Employees Grid / Table -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                @forelse($employees as $emp)
                    <div class="bg-white rounded-2xl border border-gray-200 hover:border-indigo-300 shadow-sm hover:shadow-md transition p-5 flex flex-col justify-between space-y-4">
                        <div>
                            <!-- Header & Code -->
                            <div class="flex items-start justify-between gap-2">
                                <span class="text-xs font-mono font-bold text-indigo-700 bg-indigo-50 px-2 py-0.5 rounded">
                                    {{ $emp->employee_code }}
                                </span>
                                @php
                                    $empTypeClass = match($emp->employment_type) {
                                        'permanent' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                        'contractual' => 'bg-amber-50 text-amber-700 border-amber-200',
                                        default => 'bg-purple-50 text-purple-700 border-purple-200'
                                    };
                                @endphp
                                <span class="px-2 py-0.5 rounded-full text-xs font-semibold border {{ $empTypeClass }} capitalize">
                                    {{ $emp->employment_type }}
                                </span>
                            </div>

                            <!-- User info -->
                            <div class="flex items-center gap-3 mt-3">
                                <div class="w-12 h-12 rounded-full bg-indigo-100 text-indigo-700 font-bold flex items-center justify-center text-lg shrink-0">
                                    {{ mb_substr($emp->user->name, 0, 1) }}
                                </div>
                                <div>
                                    <h3 class="font-bold text-gray-900 text-base leading-tight">
                                        <a href="{{ route('employees.show', $emp) }}" class="hover:text-indigo-600 transition">
                                            {{ $emp->user->name }}
                                        </a>
                                    </h3>
                                    <div class="text-xs text-gray-500 mt-0.5">{{ $emp->designation }} • {{ $emp->department }}</div>
                                    <div class="text-xs text-indigo-600 font-medium">{{ $emp->user->role->name ?? 'Staff' }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Financial Ledger Mini Cards -->
                        <div class="grid grid-cols-2 gap-2 pt-3 border-t border-gray-100 text-xs">
                            <div class="p-2.5 bg-gray-50 rounded-xl">
                                <div class="text-gray-500">পারিশ্রমিক রেট</div>
                                <div class="font-bold text-gray-900 text-sm mt-0.5">
                                    @if($emp->payment_type === 'per_second_video')
                                        ৳{{ number_format($emp->salary_or_rate, 2) }}/সে.
                                    @elseif($emp->payment_type === 'hourly')
                                        ৳{{ number_format($emp->salary_or_rate, 2) }}/ঘণ্টা
                                    @else
                                        ৳{{ number_format($emp->salary_or_rate) }}/মাস
                                    @endif
                                </div>
                            </div>

                            <div class="p-2.5 bg-rose-50/50 border border-rose-100 rounded-xl">
                                <div class="text-rose-600 font-medium">বকেয়া পাওনা</div>
                                <div class="font-bold text-rose-700 text-sm mt-0.5">
                                    ৳{{ number_format($emp->current_due, 2) }}
                                </div>
                            </div>
                        </div>

                        <!-- Footer -->
                        <div class="pt-3 border-t border-gray-100 flex items-center justify-between text-xs">
                            <span class="text-gray-400">যোগদান: {{ $emp->joining_date ? $emp->joining_date->format('d M, Y') : '-' }}</span>
                            <a href="{{ route('employees.show', $emp) }}" class="inline-flex items-center font-bold text-indigo-600 hover:text-indigo-800">
                                লেজার ও প্রোফাইল &rarr;
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-12 text-center text-gray-400 bg-white rounded-2xl border border-gray-200">
                        <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        <div class="text-base font-medium">কোনো কর্মী প্রোফাইল পাওয়া যায়নি</div>
                    </div>
                @endforelse
            </div>

            @if($employees->hasPages())
                <div class="bg-white p-4 rounded-2xl border border-gray-200 shadow-sm">
                    {{ $employees->links() }}
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
