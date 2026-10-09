<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight flex items-center gap-2">
                    <svg class="w-7 h-7 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    কর্মীর পারিশ্রমিক ও পে-আউট ভাউচার (Payouts)
                </h2>
                <p class="text-sm text-gray-500 mt-1">ভিডিও এডিটর বিলিং, মাসিক বেতন এবং বোনাস পরিশোধের অফিসিয়াল মানি রিসিট ট্র্যাকিং</p>
            </div>
            <a href="{{ route('payouts.create') }}" class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white rounded-lg font-semibold text-xs uppercase tracking-widest hover:bg-emerald-700 shadow-sm transition">
                <svg class="w-4 h-4 me-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                নতুন পেমেন্ট ভাউচার তৈরি
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
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm text-center">
                    <div class="text-xs font-semibold text-gray-500 uppercase">মোট পরিশোধিত পে-আউট</div>
                    <div class="text-2xl font-bold text-gray-900 mt-1">৳{{ number_format($metrics['total_paid']) }}</div>
                </div>

                <div class="bg-white p-4 rounded-xl border border-indigo-200 bg-indigo-50/30 shadow-sm text-center">
                    <div class="text-xs font-semibold text-indigo-600 uppercase">ভিডিও এডিটর বিল পরিশোধ</div>
                    <div class="text-2xl font-bold text-indigo-700 mt-1">৳{{ number_format($metrics['video_billing_paid']) }}</div>
                </div>

                <div class="bg-white p-4 rounded-xl border border-emerald-200 bg-emerald-50/30 shadow-sm text-center">
                    <div class="text-xs font-semibold text-emerald-600 uppercase">মাসিক ফিক্সড বেতন</div>
                    <div class="text-2xl font-bold text-emerald-700 mt-1">৳{{ number_format($metrics['salary_paid']) }}</div>
                </div>

                <div class="bg-white p-4 rounded-xl border border-purple-200 bg-purple-50/30 shadow-sm text-center">
                    <div class="text-xs font-semibold text-purple-600 uppercase">প্রদত্ত বোনাস</div>
                    <div class="text-2xl font-bold text-purple-700 mt-1">৳{{ number_format($metrics['total_bonus']) }}</div>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white p-4 rounded-2xl border border-gray-200 shadow-sm">
                <form method="GET" action="{{ route('payouts.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 items-end">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">অনুসন্ধান</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="ভাউচার নং, ট্রানজেকশন আইডি..."
                            class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">টিম মেম্বার</label>
                        <select name="employee_id" class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">সকল কর্মী</option>
                            @foreach($employees as $emp)
                                <option value="{{ $emp->id }}" {{ request('employee_id') == $emp->id ? 'selected' : '' }}>
                                    {{ $emp->user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">পেমেন্ট টাইপ</label>
                        <select name="payout_type" class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">সকল টাইপ</option>
                            <option value="video_billing" {{ request('payout_type') == 'video_billing' ? 'selected' : '' }}>ভিডিও বিলিং</option>
                            <option value="monthly_salary" {{ request('payout_type') == 'monthly_salary' ? 'selected' : '' }}>মাসিক বেতন</option>
                            <option value="task_contract" {{ request('payout_type') == 'task_contract' ? 'selected' : '' }}>টাস্ক চুক্তি</option>
                            <option value="advance" {{ request('payout_type') == 'advance' ? 'selected' : '' }}>অগ্রিম</option>
                            <option value="bonus" {{ request('payout_type') == 'bonus' ? 'selected' : '' }}>বোনাস</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">পেমেন্ট মাধ্যম</label>
                        <select name="payment_method" class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">সকল মাধ্যম</option>
                            @foreach(['Cash', 'bKash', 'Nagad', 'Rocket', 'Bank Transfer'] as $meth)
                                <option value="{{ $meth }}" {{ request('payment_method') == $meth ? 'selected' : '' }}>{{ $meth }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex items-center gap-2">
                        <button type="submit" class="flex-1 py-2 px-4 bg-gray-900 text-white rounded-lg text-sm font-semibold hover:bg-gray-800 transition">
                            ফিল্টার
                        </button>
                        @if(request()->anyFilled(['search', 'employee_id', 'payout_type', 'payment_method']))
                            <a href="{{ route('payouts.index') }}" class="py-2 px-3 bg-gray-100 text-gray-600 rounded-lg text-sm font-medium hover:bg-gray-200 transition">
                                রিসেট
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Payouts Table -->
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-left text-sm">
                        <thead class="bg-gray-50 text-gray-500 font-semibold uppercase text-xs">
                            <tr>
                                <th class="py-3.5 px-4">ভাউচার নং</th>
                                <th class="py-3.5 px-4">তারিখ</th>
                                <th class="py-3.5 px-4">কর্মী</th>
                                <th class="py-3.5 px-4">পেমেন্ট ক্যাটাগরি</th>
                                <th class="py-3.5 px-4">পেমেন্ট মাধ্যম</th>
                                <th class="py-3.5 px-4">পরিশোধিত অর্থ (৳)</th>
                                <th class="py-3.5 px-4 text-right">মানি রিসিট</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($payouts as $pay)
                                <tr class="hover:bg-gray-50/60 transition">
                                    <td class="py-3.5 px-4 font-mono font-bold text-indigo-700">
                                        <a href="{{ route('payouts.show', $pay) }}" class="hover:underline">
                                            {{ $pay->voucher_no }}
                                        </a>
                                    </td>
                                    <td class="py-3.5 px-4 text-gray-600 text-xs">
                                        {{ $pay->payment_date->format('d M, Y') }}
                                    </td>
                                    <td class="py-3.5 px-4">
                                        <a href="{{ route('employees.show', $pay->employee) }}" class="font-bold text-gray-900 hover:text-indigo-600 block">
                                            {{ $pay->employee->user->name }}
                                        </a>
                                        <span class="text-xs text-gray-400">{{ $pay->employee->designation }}</span>
                                    </td>
                                    <td class="py-3.5 px-4 capitalize text-xs text-gray-700">
                                        {{ str_replace('_', ' ', $pay->payout_type) }}
                                        @if($pay->salary_month)
                                            <span class="block text-[11px] text-gray-400">({{ $pay->salary_month }})</span>
                                        @endif
                                    </td>
                                    <td class="py-3.5 px-4">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-gray-100 text-gray-800">
                                            {{ $pay->payment_method }}
                                        </span>
                                        @if($pay->transaction_reference)
                                            <div class="text-[11px] text-gray-400 font-mono mt-0.5">{{ $pay->transaction_reference }}</div>
                                        @endif
                                    </td>
                                    <td class="py-3.5 px-4 font-black text-gray-900">
                                        ৳{{ number_format($pay->paid_amount, 2) }}
                                    </td>
                                    <td class="py-3.5 px-4 text-right">
                                        <a href="{{ route('payouts.show', $pay) }}" class="inline-flex items-center gap-1 px-3 py-1 bg-emerald-50 text-emerald-700 hover:bg-emerald-100 rounded-lg text-xs font-semibold transition">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                            রিসিট &rarr;
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-12 text-center text-gray-400">
                                        <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                        <div class="text-base font-medium">কোনো পেমেন্ট ভাউচার পাওয়া যায়নি</div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($payouts->hasPages())
                    <div class="p-4 border-t border-gray-100">
                        {{ $payouts->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
