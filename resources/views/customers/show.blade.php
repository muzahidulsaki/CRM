<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div class="flex items-center gap-3">
                <a href="{{ route('customers.index') }}" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-white rounded-lg transition border border-gray-200 shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                </a>
                <div>
                    <div class="flex items-center gap-2">
                        <h2 class="font-bold text-2xl text-gray-900 leading-tight">
                            {{ $customer->name }}
                        </h2>
                        <span class="px-2.5 py-0.5 rounded-md text-xs font-bold bg-emerald-100 text-emerald-800">
                            {{ $customer->customer_code }}
                        </span>
                        <span class="px-2 py-0.5 rounded-full text-xs font-semibold {{ $customer->status === 'active' ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-100 text-gray-600' }}">
                            {{ $customer->status === 'active' ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                    <p class="text-xs text-gray-500 mt-0.5">
                        {{ $customer->company_name ?? 'ব্যক্তিগত ক্লায়েন্ট' }}
                        @if($customer->business_type) &bull; {{ $customer->business_type }} @endif
                    </p>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('quotations.create', ['customer_id' => $customer->id]) }}" class="inline-flex items-center px-3.5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-bold uppercase tracking-wider shadow-sm transition">
                    <svg class="w-4 h-4 me-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    কোটেশন তৈরি
                </a>

                <a href="{{ route('customers.edit', $customer) }}" class="inline-flex items-center px-3.5 py-2 bg-white border border-gray-300 rounded-lg text-xs font-bold text-gray-700 uppercase tracking-wider hover:bg-gray-50 shadow-sm transition">
                    <svg class="w-4 h-4 me-1.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    সম্পাদনা
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Alerts -->
            @if(session('success'))
                <div class="p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 rounded-r-lg shadow-sm flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        <span class="font-medium text-sm">{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            <!-- Customer 360 Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- Left Column (1/3): Client Profile Card -->
                <div class="space-y-6">
                    <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm space-y-4">
                        <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider border-b pb-3 flex items-center gap-2">
                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            গ্রাহকের যোগাযোগ তথ্য
                        </h3>

                        <!-- Phone -->
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                            <div>
                                <div class="text-[11px] font-semibold text-gray-500 uppercase">মোবাইল</div>
                                <div class="font-bold text-gray-900 text-sm">{{ $customer->phone }}</div>
                            </div>
                            <a href="tel:{{ $customer->phone }}" class="p-2 bg-indigo-50 text-indigo-600 hover:bg-indigo-100 rounded-lg transition">
                                📞
                            </a>
                        </div>

                        <!-- WhatsApp -->
                        <div class="flex items-center justify-between p-3 bg-emerald-50 rounded-xl">
                            <div>
                                <div class="text-[11px] font-semibold text-emerald-800 uppercase">হোয়াটসঅ্যাপ</div>
                                <div class="font-bold text-emerald-900 text-sm">{{ $customer->whatsapp ?? $customer->phone }}</div>
                            </div>
                            <a href="https://wa.me/{{ $customer->normalized_whatsapp ? '88' . $customer->normalized_whatsapp : '88' . $customer->normalized_phone }}" target="_blank" class="px-3 py-1 bg-emerald-600 text-white rounded text-xs font-semibold hover:bg-emerald-700 transition">
                                WA চ্যাট 💬
                            </a>
                        </div>

                        @if($customer->email)
                            <div class="p-3 bg-gray-50 rounded-xl text-xs">
                                <div class="font-semibold text-gray-500 uppercase text-[10px]">ইমেইল</div>
                                <a href="mailto:{{ $customer->email }}" class="text-indigo-600 hover:underline font-medium">{{ $customer->email }}</a>
                            </div>
                        @endif

                        @if($customer->address || $customer->city)
                            <div class="text-xs text-gray-600 space-y-1">
                                <div class="font-semibold text-gray-700">ঠিকানা:</div>
                                <p>{{ $customer->address ? $customer->address . ', ' : '' }}{{ $customer->city }} ({{ $customer->country }})</p>
                            </div>
                        @endif

                        @if($customer->notes)
                            <div class="pt-2 border-t text-xs">
                                <div class="font-semibold text-gray-700 mb-1">নোট:</div>
                                <p class="text-gray-600 bg-gray-50 p-2.5 rounded-lg whitespace-pre-line">{{ $customer->notes }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Right Column (2/3): Customer 360 Tabs (Leads, Quotations, Communications) -->
                <div class="lg:col-span-2 space-y-6">

                    <!-- Associated Leads -->
                    <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm space-y-3">
                        <div class="flex items-center justify-between border-b pb-3">
                            <h3 class="text-base font-bold text-gray-900 flex items-center gap-2">
                                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                সমস্ত ইনকোয়ারি ও লিড ({{ $customer->leads->count() }})
                            </h3>
                        </div>

                        <div class="space-y-2">
                            @forelse($customer->leads as $lead)
                                <div class="p-3.5 rounded-xl border border-gray-100 hover:border-indigo-300 hover:bg-indigo-50/20 transition flex flex-col sm:flex-row justify-between sm:items-center gap-2">
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <a href="{{ route('leads.show', $lead) }}" class="font-bold text-xs text-indigo-600 hover:underline">
                                                {{ $lead->lead_code }}
                                            </a>
                                            <span class="text-xs font-semibold text-gray-800">{{ $lead->interested_service }}</span>
                                        </div>
                                        <div class="text-[11px] text-gray-500 mt-0.5">
                                            তারিখ: {{ $lead->created_at->format('d M, Y') }} &bull; বাজেট: ৳{{ number_format($lead->budget) }}
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="px-2 py-0.5 rounded-full text-xs font-semibold {{ $lead->status_badge_class }}">
                                            {{ $lead->lead_status }}
                                        </span>
                                        <a href="{{ route('leads.show', $lead) }}" class="text-xs text-indigo-600 font-semibold hover:underline">
                                            বিস্তারিত &rarr;
                                        </a>
                                    </div>
                                </div>
                            @empty
                                <p class="text-xs text-gray-400 py-3 text-center">কোন লিড রেকর্ড নেই।</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Associated Quotations -->
                    <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm space-y-3">
                        <div class="flex items-center justify-between border-b pb-3">
                            <h3 class="text-base font-bold text-gray-900 flex items-center gap-2">
                                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                কোটেশন হিস্ট্রি ({{ $customer->quotations->count() }})
                            </h3>
                        </div>

                        <div class="space-y-2">
                            @forelse($customer->quotations as $quotation)
                                <div class="p-3.5 rounded-xl border border-gray-100 hover:border-indigo-300 transition flex flex-col sm:flex-row justify-between sm:items-center gap-2">
                                    <div>
                                        <div class="font-bold text-xs text-indigo-700">{{ $quotation->quotation_code }}</div>
                                        <div class="text-[11px] text-gray-500 mt-0.5">
                                            ইস্যু তারিখ: {{ $quotation->quotation_date->format('d M, Y') }}
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <span class="font-bold text-gray-900 text-sm">৳{{ number_format($quotation->total_amount, 2) }}</span>
                                        <span class="px-2 py-0.5 rounded-full text-xs font-semibold {{ $quotation->status === 'Accepted' ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-100 text-gray-700' }}">
                                            {{ $quotation->status }}
                                        </span>
                                        <a href="{{ route('quotations.show', $quotation) }}" class="text-xs text-indigo-600 font-semibold hover:underline">
                                            প্রিন্ট &rarr;
                                        </a>
                                    </div>
                                </div>
                            @empty
                                <p class="text-xs text-gray-400 py-3 text-center">কোন কোটেশন পাওয়া যায়নি।</p>
                            @endforelse
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </div>
</x-app-layout>
