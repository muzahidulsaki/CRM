<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 print:hidden">
            <div class="flex items-center gap-3">
                <a href="{{ route('quotations.index') }}" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-white rounded-lg transition border border-gray-200 shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                </a>
                <div>
                    <h2 class="font-bold text-2xl text-gray-900 leading-tight flex items-center gap-2">
                        কোটেশন: {{ $quotation->quotation_code }}
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $quotation->status === 'Accepted' ? 'bg-emerald-100 text-emerald-800' : ($quotation->status === 'Rejected' ? 'bg-rose-100 text-rose-800' : 'bg-gray-100 text-gray-800') }}">
                            {{ $quotation->status }}
                        </span>
                    </h2>
                    <p class="text-xs text-gray-500 mt-0.5">তারিখ: {{ $quotation->quotation_date->format('d M, Y') }}</p>
                </div>
            </div>

            <!-- Actions: Print, Edit, Status -->
            <div class="flex flex-wrap items-center gap-2">
                <!-- Status Change Dropdown -->
                <form method="POST" action="{{ route('quotations.update-status', $quotation) }}" class="inline">
                    @csrf
                    @method('PATCH')
                    <select name="status" onchange="this.form.submit()" class="text-xs font-semibold py-1.5 border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        @foreach(['Draft', 'Sent', 'Negotiation', 'Accepted', 'Rejected', 'Expired'] as $st)
                            <option value="{{ $st }}" {{ $quotation->status == $st ? 'selected' : '' }}>স্ট্যাটাস: {{ $st }}</option>
                        @endforeach
                    </select>
                </form>

                <a href="{{ route('quotations.edit', $quotation) }}" class="inline-flex items-center px-3.5 py-2 bg-white border border-gray-300 rounded-lg text-xs font-bold text-gray-700 uppercase tracking-wider hover:bg-gray-50 shadow-sm transition">
                    <svg class="w-4 h-4 me-1.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    সম্পাদনা
                </a>

                <button onclick="window.print()" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-bold uppercase tracking-wider shadow-sm transition">
                    <svg class="w-4 h-4 me-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    প্রিন্ট / PDF সেভ
                </button>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Official Quotation / Invoice Paper -->
            <div class="bg-white p-8 sm:p-12 rounded-2xl border border-gray-200 shadow-md print:shadow-none print:border-none print:p-0 space-y-8">

                <!-- Company Header & Quotation Code -->
                <div class="flex flex-col sm:flex-row justify-between items-start border-b pb-8 gap-6">
                    <div class="space-y-1">
                        <div class="flex items-center gap-2">
                            <div class="w-10 h-10 rounded-xl bg-indigo-600 flex items-center justify-center text-white font-black text-xl shadow">
                                PS
                            </div>
                            <span class="font-extrabold text-2xl text-gray-900 tracking-tight">
                                {{ $companySettings['name'] }}
                            </span>
                        </div>
                        <p class="text-xs text-gray-500 pt-1">আইটি, সফটওয়্যার, ওয়েবসাইট ও ক্রিয়েটিভ মিডিয়া সেবা</p>
                        <p class="text-xs text-gray-600">ফোন: {{ $companySettings['phone'] }} | ইমেইল: {{ $companySettings['email'] }}</p>
                        <p class="text-xs text-gray-600">ওয়েবসাইট: https://projuktisheba.com</p>
                    </div>

                    <div class="text-left sm:text-right space-y-1 sm:space-y-1.5">
                        <h1 class="text-2xl font-black text-indigo-700 uppercase tracking-wider">OFFICIAL QUOTATION</h1>
                        <div class="font-bold text-gray-900 text-sm">কোটেশন নং: {{ $quotation->quotation_code }}</div>
                        <div class="text-xs text-gray-600">ইস্যু তারিখ: <span class="font-semibold text-gray-800">{{ $quotation->quotation_date->format('d M, Y') }}</span></div>
                        <div class="text-xs text-gray-600">মেয়াদ: <span class="font-semibold text-gray-800">{{ $quotation->valid_until ? $quotation->valid_until->format('d M, Y') : '১৫ দিন পর্যন্ত বৈধ' }}</span></div>
                    </div>
                </div>

                <!-- Client Details Card -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 bg-gray-50 p-5 rounded-xl border border-gray-100">
                    <div>
                        <div class="text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1">প্রাপক / বিলিং তথ্য:</div>
                        @if($quotation->customer)
                            <div class="font-bold text-gray-900 text-base">{{ $quotation->customer->name }}</div>
                            @if($quotation->customer->company_name)
                                <div class="text-xs font-semibold text-indigo-700">{{ $quotation->customer->company_name }}</div>
                            @endif
                            <div class="text-xs text-gray-600 mt-1">ফোন: {{ $quotation->customer->phone }}</div>
                            @if($quotation->customer->email)
                                <div class="text-xs text-gray-600">ইমেইল: {{ $quotation->customer->email }}</div>
                            @endif
                            @if($quotation->customer->address)
                                <div class="text-xs text-gray-600">{{ $quotation->customer->address }}</div>
                            @endif
                        @elseif($quotation->lead)
                            <div class="font-bold text-gray-900 text-base">{{ $quotation->lead->name }}</div>
                            @if($quotation->lead->company_name)
                                <div class="text-xs font-semibold text-indigo-700">{{ $quotation->lead->company_name }}</div>
                            @endif
                            <div class="text-xs text-gray-600 mt-1">ফোন: {{ $quotation->lead->phone }}</div>
                            @if($quotation->lead->email)
                                <div class="text-xs text-gray-600">ইমেইল: {{ $quotation->lead->email }}</div>
                            @endif
                            @if($quotation->lead->address)
                                <div class="text-xs text-gray-600">{{ $quotation->lead->address }}</div>
                            @endif
                        @else
                            <div class="text-xs text-gray-400 italic">কোন নির্দিষ্ট ক্লায়েন্ট নির্বাচিত নেই</div>
                        @endif
                    </div>

                    <div class="sm:text-right text-xs space-y-1">
                        <div class="text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1">প্রস্তুতকারী:</div>
                        <div class="font-bold text-gray-800">{{ $quotation->creator->name ?? 'Sales Executive' }}</div>
                        <div class="text-gray-500">প্রযুক্তি সেবা (Projukti Sheba)</div>
                        <div class="text-gray-500">ঢাকা, বাংলাদেশ</div>
                    </div>
                </div>

                <!-- Line Items Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-left text-sm">
                        <thead class="bg-gray-100 text-gray-700 font-bold uppercase text-xs">
                            <tr>
                                <th class="py-3 px-3 w-12 text-center">#</th>
                                <th class="py-3 px-4">সার্ভিস ও বিবরণ</th>
                                <th class="py-3 px-4 text-center w-24">পরিমাণ</th>
                                <th class="py-3 px-4 text-right w-32">দর (৳)</th>
                                <th class="py-3 px-4 text-right w-32">মোট টাকা (৳)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($quotation->items as $idx => $item)
                                <tr>
                                    <td class="py-3.5 px-3 text-center text-xs text-gray-400">{{ $idx + 1 }}</td>
                                    <td class="py-3.5 px-4">
                                        <div class="font-bold text-gray-900 text-xs">{{ $item->service_category ?? 'সার্ভিস' }}</div>
                                        <div class="text-xs text-gray-600 mt-0.5 whitespace-pre-line">{{ $item->description }}</div>
                                    </td>
                                    <td class="py-3.5 px-4 text-center font-medium text-gray-800 text-xs">
                                        {{ rtrim(rtrim(number_format($item->quantity, 2), '0'), '.') }}
                                    </td>
                                    <td class="py-3.5 px-4 text-right font-medium text-gray-800 text-xs">
                                        ৳{{ number_format($item->unit_price, 2) }}
                                    </td>
                                    <td class="py-3.5 px-4 text-right font-bold text-gray-900 text-xs">
                                        ৳{{ number_format($item->total_price, 2) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Financial Calculation Summary -->
                <div class="flex flex-col sm:flex-row justify-between items-start gap-6 pt-4 border-t">
                    <div class="flex-1 space-y-3 text-xs text-gray-600">
                        @if($quotation->payment_terms)
                            <div>
                                <strong class="text-gray-800 uppercase block text-[11px] mb-0.5">পেমেন্ট শর্তাবলী:</strong>
                                <p class="bg-gray-50 p-2.5 rounded-lg border border-gray-100">{{ $quotation->payment_terms }}</p>
                            </div>
                        @endif

                        @if($quotation->delivery_terms)
                            <div>
                                <strong class="text-gray-800 uppercase block text-[11px] mb-0.5">ডেলিভারি শর্তাবলী:</strong>
                                <p class="bg-gray-50 p-2.5 rounded-lg border border-gray-100">{{ $quotation->delivery_terms }}</p>
                            </div>
                        @endif
                    </div>

                    <div class="w-full sm:w-72 space-y-2 text-xs">
                        <div class="flex justify-between py-1 border-b border-gray-100">
                            <span class="text-gray-600">উপমোট (Subtotal):</span>
                            <span class="font-bold text-gray-900">৳{{ number_format($quotation->subtotal, 2) }}</span>
                        </div>
                        @if($quotation->discount_amount > 0)
                            <div class="flex justify-between py-1 border-b border-gray-100 text-rose-600 font-medium">
                                <span>ডিসকাউন্ট (Discount):</span>
                                <span>- ৳{{ number_format($quotation->discount_amount, 2) }}</span>
                            </div>
                        @endif
                        @if($quotation->tax_amount > 0)
                            <div class="flex justify-between py-1 border-b border-gray-100 text-gray-600">
                                <span>ভ্যাট / ট্যাক্স (Tax):</span>
                                <span class="font-semibold text-gray-900">+ ৳{{ number_format($quotation->tax_amount, 2) }}</span>
                            </div>
                        @endif
                        <div class="flex justify-between py-2 border-t-2 border-gray-900 text-sm font-extrabold text-indigo-900">
                            <span>সর্বমোট প্রদেয়:</span>
                            <span class="text-base">৳{{ number_format($quotation->total_amount, 2) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Signatures -->
                <div class="pt-16 grid grid-cols-2 gap-8 text-center text-xs text-gray-600">
                    <div>
                        <div class="w-44 border-b border-gray-400 mx-auto mb-2"></div>
                        <p class="font-bold text-gray-800">গ্রাহকের স্বাক্ষর</p>
                        <p class="text-[11px] text-gray-400">অনুমোদনের তারিখসহ</p>
                    </div>
                    <div>
                        <div class="w-44 border-b border-gray-400 mx-auto mb-2"></div>
                        <p class="font-bold text-gray-800">কর্তৃপক্ষের স্বাক্ষর</p>
                        <p class="text-[11px] text-gray-400">প্রযুক্তি সেবা (Projukti Sheba)</p>
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>
