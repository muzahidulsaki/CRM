<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between no-print">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                    পেমেন্ট ভাউচার ও মানি রিসিট: {{ $payout->voucher_no }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">কর্মীর পারিশ্রমিক পরিশোধের অফিসিয়াল প্রমাণপত্র ও ভাউচার</p>
            </div>
            <div class="flex items-center gap-2">
                <button type="button" onclick="window.print()" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm font-semibold hover:bg-indigo-700 shadow-sm transition">
                    <svg class="w-4 h-4 me-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    ভাউচার প্রিন্ট করুন
                </button>
                <a href="{{ route('payouts.index') }}" class="inline-flex items-center px-3.5 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm font-semibold hover:bg-gray-200 transition">
                    তালিকায় ফিরুন
                </a>
            </div>
        </div>
    </x-slot>

    <style>
        @media print {
            body { background: white !important; }
            header, nav, .no-print { display: none !important; }
            .print-container { box-shadow: none !important; border: none !important; padding: 0 !important; width: 100% !important; max-width: 100% !important; }
        }
    </style>

    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="print-container bg-white rounded-3xl border border-gray-200 shadow-sm p-8 sm:p-12 space-y-8">

                <!-- Company Header & Voucher Title -->
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center pb-6 border-b-2 border-gray-900 gap-4">
                    <div>
                        <h1 class="text-2xl font-black text-gray-900 tracking-tight">প্রযুক্তি সেবা (Projukti Sheba)</h1>
                        <p class="text-xs text-gray-500 mt-0.5">আইটি সল্যুশন, সফটওয়্যার, ওয়েব ডেভেলপমেন্ট ও ক্রিয়েটিভ মিডিয়া</p>
                        <p class="text-xs text-gray-400">ঢাকা, বাংলাদেশ • contact@projuktisheba.com</p>
                    </div>

                    <div class="sm:text-right">
                        <span class="inline-block px-3 py-1 bg-emerald-100 text-emerald-800 text-xs font-bold rounded-lg uppercase tracking-wider mb-1">
                            PAYMENT VOUCHER / MONEY RECEIPT
                        </span>
                        <div class="text-lg font-black text-gray-900 font-mono">{{ $payout->voucher_no }}</div>
                        <div class="text-xs text-gray-500">তারিখ: {{ $payout->payment_date->format('d F, Y') }}</div>
                    </div>
                </div>

                <!-- Employee & Payment Meta Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 bg-gray-50 p-5 rounded-2xl border border-gray-200 text-xs">
                    <div>
                        <span class="text-gray-400 font-bold uppercase tracking-wider block mb-1">প্রাপক / কর্মী বিবরণ:</span>
                        <div class="text-base font-bold text-gray-900">{{ $payout->employee->user->name }}</div>
                        <div class="text-gray-600 mt-0.5">{{ $payout->employee->designation }} • {{ $payout->employee->department }}</div>
                        <div class="text-gray-500 mt-0.5 font-mono">কর্মী কোড: {{ $payout->employee->employee_code }}</div>
                        <div class="text-gray-500">{{ $payout->employee->user->email }} | {{ $payout->employee->user->phone ?? 'ফোন নেই' }}</div>
                    </div>

                    <div class="sm:text-right space-y-1">
                        <span class="text-gray-400 font-bold uppercase tracking-wider block mb-1">পরিশোধের বিবরণ:</span>
                        <div><strong>পেমেন্ট ক্যাটাগরি:</strong> <span class="capitalize">{{ str_replace('_', ' ', $payout->payout_type) }}</span></div>
                        <div><strong>পেমেন্ট মাধ্যম:</strong> {{ $payout->payment_method }}</div>
                        @if($payout->account_details)
                            <div><strong>একাউন্ট নম্বর:</strong> {{ $payout->account_details }}</div>
                        @endif
                        @if($payout->transaction_reference)
                            <div class="font-mono"><strong>TrxID / রেফারেন্স:</strong> {{ $payout->transaction_reference }}</div>
                        @endif
                        <div><strong>প্রস্তুতকারক:</strong> {{ $payout->creator->name }}</div>
                    </div>
                </div>

                <!-- Financial Breakdown Table -->
                <div>
                    <h3 class="font-bold text-sm text-gray-900 uppercase tracking-wider mb-3">হিসাবের বিবরণী (Payment Breakdown)</h3>
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-100 text-gray-700 font-semibold text-xs uppercase">
                            <tr>
                                <th class="py-3 px-4 text-left">বিবরণ</th>
                                <th class="py-3 px-4 text-right">পরিমাণ (টাকা)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-xs">
                            <tr>
                                <td class="py-3 px-4 font-medium text-gray-800">
                                    মূল বিল / বেতন (Base Amount)
                                    @if($payout->salary_month)
                                        <span class="text-gray-400 block text-[11px]">মাস: {{ $payout->salary_month }}</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-right font-bold text-gray-900">
                                    ৳{{ number_format($payout->base_amount, 2) }}
                                </td>
                            </tr>

                            @if($payout->bonus_amount > 0)
                                <tr class="bg-emerald-50/30 text-emerald-900">
                                    <td class="py-3 px-4 font-medium">অতিরিক্ত পারফরম্যান্স বোনাস / ইনসেনটিভ (+)</td>
                                    <td class="py-3 px-4 text-right font-bold">
                                        + ৳{{ number_format($payout->bonus_amount, 2) }}
                                    </td>
                                </tr>
                            @endif

                            @if($payout->deduction_amount > 0)
                                <tr class="bg-rose-50/30 text-rose-900">
                                    <td class="py-3 px-4 font-medium">অগ্রিম সমন্বয় / কর্তন (-)</td>
                                    <td class="py-3 px-4 text-right font-bold">
                                        - ৳{{ number_format($payout->deduction_amount, 2) }}
                                    </td>
                                </tr>
                            @endif

                            <tr class="bg-gray-50 border-t-2 border-gray-900 text-sm font-black text-gray-900">
                                <td class="py-4 px-4 text-base">সর্বমোট পরিশোধিত অর্থ (Net Paid):</td>
                                <td class="py-4 px-4 text-right text-lg text-emerald-700 font-black">
                                    ৳{{ number_format($payout->paid_amount, 2) }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Linked Video Billings List if applicable -->
                @if($linkedBillings->isNotEmpty())
                    <div class="p-4 bg-gray-50 rounded-2xl border border-gray-200 space-y-2">
                        <span class="text-xs font-bold text-gray-700 uppercase tracking-wider block">এই ভাউচারে পরিশোধিত ভিডিও বিলিং তালিকা:</span>
                        <div class="space-y-1.5 text-xs text-gray-700">
                            @foreach($linkedBillings as $lb)
                                <div class="flex items-center justify-between border-b border-gray-200/60 pb-1">
                                    <span>• <strong>{{ $lb->billing_code }}</strong>: {{ $lb->video_title }} ({{ $lb->duration_minutes }}মি. {{ $lb->duration_seconds }}সে.)</span>
                                    <span class="font-bold text-gray-900">৳{{ number_format($lb->total_payable, 2) }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if($payout->notes)
                    <div class="p-3 bg-gray-50 rounded-xl text-xs text-gray-600">
                        <strong>নোট:</strong> {{ $payout->notes }}
                    </div>
                @endif

                <!-- Signature Section -->
                <div class="pt-16 grid grid-cols-2 gap-8 text-center text-xs">
                    <div>
                        <div class="border-t border-gray-400 w-48 mx-auto mb-1"></div>
                        <span class="font-bold text-gray-800">কর্মীর স্বাক্ষর (Receiver)</span>
                        <div class="text-[11px] text-gray-400">অর্থ বুঝে পেয়ে স্বাক্ষর করলাম</div>
                    </div>

                    <div>
                        <div class="border-t border-gray-400 w-48 mx-auto mb-1"></div>
                        <span class="font-bold text-gray-800">অনুমোদিত স্বাক্ষর (Accounts / Management)</span>
                        <div class="text-[11px] text-gray-400">প্রযুক্তি সেবা</div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>
