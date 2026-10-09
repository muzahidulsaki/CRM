<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div class="flex items-center gap-3">
                <a href="{{ route('leads.index') }}" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-white rounded-lg transition border border-gray-200 shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                </a>
                <div>
                    <div class="flex items-center gap-2.5">
                        <h2 class="font-bold text-2xl text-gray-900 leading-tight">
                            {{ $lead->name }}
                        </h2>
                        <span class="px-2.5 py-0.5 rounded-md text-xs font-bold bg-indigo-100 text-indigo-800">
                            {{ $lead->lead_code }}
                        </span>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold border {{ $lead->temperature_badge_class }}">
                            @if($lead->lead_temperature === 'Hot') 🔥 Hot
                            @elseif($lead->lead_temperature === 'Warm') ⚡ Warm
                            @else ❄️ Cold
                            @endif
                        </span>
                    </div>
                    <p class="text-xs text-gray-500 mt-1 flex items-center gap-2">
                        <span>{{ $lead->company_name ?? 'ব্যক্তিগত গ্রাহক' }}</span>
                        <span>&bull;</span>
                        <span>সোর্স: {{ $lead->lead_source }}</span>
                        <span>&bull;</span>
                        <span>সংগ্রহ: {{ $lead->created_at->format('d M, Y h:i A') }}</span>
                    </p>
                </div>
            </div>

            <!-- Quick Action Buttons -->
            <div class="flex flex-wrap items-center gap-2">
                @if(!$lead->customer_id)
                    <form method="POST" action="{{ route('leads.convert', $lead) }}" onsubmit="return confirm('আপনি কি এই লিডটিকে অফিশিয়াল কাস্টমার হিসেবে কনভার্ট করতে চান?');">
                        @csrf
                        <button type="submit" class="inline-flex items-center px-3.5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-bold uppercase tracking-wider shadow-sm transition">
                            <svg class="w-4 h-4 me-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            গ্রাহকে রূপান্তর (Convert)
                        </button>
                    </form>
                @else
                    <a href="{{ route('customers.show', $lead->customer_id) }}" class="inline-flex items-center px-3.5 py-2 bg-emerald-50 border border-emerald-300 text-emerald-800 rounded-lg text-xs font-bold uppercase tracking-wider hover:bg-emerald-100 transition">
                        <svg class="w-4 h-4 me-1.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        কাস্টমার প্রোফাইল ({{ $lead->customer->customer_code }})
                    </a>
                @endif

                <a href="{{ route('quotations.create', ['lead_id' => $lead->id]) }}" class="inline-flex items-center px-3.5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-bold uppercase tracking-wider shadow-sm transition">
                    <svg class="w-4 h-4 me-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    কোটেশন তৈরি
                </a>

                <a href="{{ route('leads.edit', $lead) }}" class="inline-flex items-center px-3.5 py-2 bg-white border border-gray-300 rounded-lg text-xs font-bold text-gray-700 uppercase tracking-wider hover:bg-gray-50 shadow-sm transition">
                    <svg class="w-4 h-4 me-1.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    এডিট
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

            <!-- Top Status Pipeline Bar -->
            <div class="bg-white p-4 rounded-2xl border border-gray-200 shadow-sm">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">বর্তমান স্ট্যাটাস:</span>
                        <span class="px-3 py-1 rounded-full text-xs font-bold {{ $lead->status_badge_class }}">
                            {{ $lead->lead_status }}
                        </span>
                    </div>

                    <!-- Quick Status Change Form -->
                    <form method="POST" action="{{ route('leads.update-status', $lead) }}" class="flex items-center gap-2">
                        @csrf
                        @method('PATCH')
                        <label class="text-xs font-semibold text-gray-600">স্ট্যাটাস পরিবর্তন:</label>
                        <select name="lead_status" onchange="this.form.submit()" class="text-xs font-semibold py-1.5 border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                            @foreach(['New', 'Contacted', 'Interested', 'Meeting/Demo', 'Proposal Sent', 'Negotiation', 'Won', 'Lost'] as $st)
                                <option value="{{ $st }}" {{ $lead->lead_status == $st ? 'selected' : '' }}>{{ $st }}</option>
                            @endforeach
                        </select>
                    </form>
                </div>
            </div>

            <!-- Two-Column Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- Left Column (1/3): Details & Contact Cards -->
                <div class="space-y-6">

                    <!-- Contact & Communication Card -->
                    <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm space-y-4">
                        <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider flex items-center gap-2 border-b pb-2.5">
                            <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            যোগাযোগের মাধ্যম
                        </h3>

                        <!-- Direct Call -->
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl border border-gray-100">
                            <div>
                                <div class="text-[11px] font-semibold text-gray-500 uppercase">ফোন নম্বর</div>
                                <div class="font-bold text-gray-900 text-sm mt-0.5">{{ $lead->phone }}</div>
                            </div>
                            <a href="tel:{{ $lead->phone }}" class="p-2 bg-indigo-50 text-indigo-600 hover:bg-indigo-100 rounded-lg transition" title="কল করুন">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            </a>
                        </div>

                        <!-- Direct WhatsApp -->
                        <div class="flex items-center justify-between p-3 bg-emerald-50/50 rounded-xl border border-emerald-100">
                            <div>
                                <div class="text-[11px] font-semibold text-emerald-800 uppercase">হোয়াটসঅ্যাপ</div>
                                <div class="font-bold text-emerald-900 text-sm mt-0.5">{{ $lead->whatsapp ?? $lead->phone }}</div>
                            </div>
                            <a href="https://wa.me/{{ $lead->normalized_whatsapp ? '88' . $lead->normalized_whatsapp : '88' . $lead->normalized_phone }}" target="_blank" class="px-3 py-1.5 bg-emerald-600 text-white rounded-lg text-xs font-semibold hover:bg-emerald-700 transition flex items-center gap-1.5 shadow-sm">
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.007c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.353.101.173.449.741.964 1.2 1.309 1.168 1.706.772 1.95.836.087.023.23-.014.354-.188.125-.174.529-.623.67-.837.14-.213.281-.178.472-.107.19.071 1.208.57 1.415.674.208.104.346.155.397.242.051.087.051.505-.093.91z"/></svg>
                                চ্যাট করুন
                            </a>
                        </div>

                        <!-- Email -->
                        @if($lead->email)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl border border-gray-100">
                                <div>
                                    <div class="text-[11px] font-semibold text-gray-500 uppercase">ইমেইল</div>
                                    <div class="font-medium text-gray-900 text-xs mt-0.5 truncate max-w-[200px]">{{ $lead->email }}</div>
                                </div>
                                <a href="mailto:{{ $lead->email }}" class="p-2 bg-indigo-50 text-indigo-600 hover:bg-indigo-100 rounded-lg transition" title="ইমেইল পাঠান">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                </a>
                            </div>
                        @endif

                        <!-- Address & Location -->
                        @if($lead->address || $lead->city)
                            <div class="pt-2 text-xs text-gray-600 space-y-1">
                                <div class="font-semibold text-gray-700">ঠিকানা ও অবস্থান:</div>
                                <p>{{ $lead->address ? $lead->address . ', ' : '' }}{{ $lead->city }} ({{ $lead->country }})</p>
                            </div>
                        @endif

                        <!-- Website / Links -->
                        @if($lead->website)
                            <div class="pt-1 text-xs">
                                <span class="font-semibold text-gray-700">ওয়েবসাইট:</span>
                                <a href="{{ $lead->website }}" target="_blank" class="text-indigo-600 hover:underline block truncate">{{ $lead->website }}</a>
                            </div>
                        @endif
                    </div>

                    <!-- Deal & Parameters Card -->
                    <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm space-y-3">
                        <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider border-b pb-2.5">
                            সার্ভিস ও বাজেট বিবরণ
                        </h3>

                        <div class="flex justify-between items-center text-xs">
                            <span class="text-gray-500">আগ্রহী সার্ভিস:</span>
                            <span class="font-bold text-gray-900 bg-gray-100 px-2 py-0.5 rounded">{{ $lead->interested_service ?? 'অনির্দিষ্ট' }}</span>
                        </div>

                        <div class="flex justify-between items-center text-xs">
                            <span class="text-gray-500">সম্ভাব্য বাজেট:</span>
                            <span class="font-bold text-indigo-700">{{ $lead->budget ? '৳' . number_format($lead->budget) : 'উল্লেখ নেই' }}</span>
                        </div>

                        <div class="flex justify-between items-center text-xs">
                            <span class="text-gray-500">আলোচিত রেট:</span>
                            <span class="font-bold text-emerald-700">{{ $lead->discussed_amount ? '৳' . number_format($lead->discussed_amount) : 'আলোচনা হয়নি' }}</span>
                        </div>

                        <div class="flex justify-between items-center text-xs">
                            <span class="text-gray-500">দায়িত্বপ্রাপ্ত কর্মী:</span>
                            <span class="font-semibold text-gray-800">{{ $lead->assignedUser->name ?? 'অনির্ধারিত' }}</span>
                        </div>

                        <div class="flex justify-between items-center text-xs">
                            <span class="text-gray-500">কাজ শুরুর সম্ভাব্য তারিখ:</span>
                            <span class="font-medium text-gray-800">{{ $lead->expected_work_date ? $lead->expected_work_date->format('d M, Y') : 'অনির্দিষ্ট' }}</span>
                        </div>

                        <div class="flex justify-between items-center text-xs">
                            <span class="text-gray-500">পরবর্তী ফলো-আপ:</span>
                            <span class="font-bold text-amber-700">{{ $lead->next_followup_date ? $lead->next_followup_date->format('d M, Y h:i A') : 'নির্ধারিত নেই' }}</span>
                        </div>

                        @if($lead->notes)
                            <div class="pt-2 border-t text-xs">
                                <div class="font-semibold text-gray-700 mb-1">নোট:</div>
                                <p class="text-gray-600 bg-gray-50 p-2.5 rounded-lg whitespace-pre-line">{{ $lead->notes }}</p>
                            </div>
                        @endif
                    </div>

                    <!-- Quotations List Card for this Lead -->
                    <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm space-y-3">
                        <div class="flex items-center justify-between border-b pb-2.5">
                            <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider">
                                কোটেশনসমূহ ({{ $lead->quotations->count() }})
                            </h3>
                            <a href="{{ route('quotations.create', ['lead_id' => $lead->id]) }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800">
                                + নতুন
                            </a>
                        </div>

                        <div class="space-y-2">
                            @forelse($lead->quotations as $quotation)
                                <a href="{{ route('quotations.show', $quotation) }}" class="block p-3 rounded-xl border border-gray-100 hover:border-indigo-300 hover:bg-indigo-50/20 transition">
                                    <div class="flex justify-between items-center">
                                        <span class="font-bold text-xs text-indigo-700">{{ $quotation->quotation_code }}</span>
                                        <span class="text-[11px] font-semibold px-2 py-0.5 rounded-full {{ $quotation->status === 'Accepted' ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-100 text-gray-700' }}">
                                            {{ $quotation->status }}
                                        </span>
                                    </div>
                                    <div class="flex justify-between items-center mt-1 text-xs">
                                        <span class="text-gray-500">{{ $quotation->quotation_date->format('d M, Y') }}</span>
                                        <span class="font-bold text-gray-900">৳{{ number_format($quotation->total_amount, 2) }}</span>
                                    </div>
                                </a>
                            @empty
                                <p class="text-xs text-gray-400 text-center py-3">এখনো কোনো কোটেশন তৈরি করা হয়নি।</p>
                            @endforelse
                        </div>
                    </div>

                </div>

                <!-- Right Column (2/3): Activity Timeline & Log Communication -->
                <div class="lg:col-span-2 space-y-6">

                    <!-- Log Communication Form Card -->
                    <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
                        <h3 class="text-base font-bold text-gray-900 flex items-center gap-2 border-b pb-3 mb-4">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            নতুন যোগাযোগ বা ফলো-আপ যুক্ত করুন (Add Activity)
                        </h3>

                        <form method="POST" action="{{ route('leads.follow-ups.store', $lead) }}" class="space-y-4">
                            @csrf

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                <!-- Type -->
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">যোগাযোগের ধরন <span class="text-rose-500">*</span></label>
                                    <select name="type" required class="w-full text-xs font-medium border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                                        <option value="phone_call">📞 ফোন কল (Phone Call)</option>
                                        <option value="whatsapp">💬 হোয়াটসঅ্যাপ (WhatsApp)</option>
                                        <option value="email">✉️ ইমেইল (Email)</option>
                                        <option value="meeting">🤝 মিটিং / ভিজিট (Meeting)</option>
                                        <option value="internal_note">📝 অভ্যন্তরীণ নোট (Internal Note)</option>
                                    </select>
                                </div>

                                <!-- Status -->
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">স্ট্যাটাস <span class="text-rose-500">*</span></label>
                                    <select name="status" required class="w-full text-xs font-medium border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                                        <option value="completed">সম্পন্ন হয়েছে (Completed)</option>
                                        <option value="scheduled">ভবিষ্যতে নির্ধারিত (Scheduled)</option>
                                    </select>
                                </div>

                                <!-- Next Follow-up Date -->
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">পরবর্তী ফলো-আপ তারিখ</label>
                                    <input type="datetime-local" name="next_followup_date" class="w-full text-xs border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                                </div>
                            </div>

                            <!-- Subject -->
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">বিষয় / হেডলাইন</label>
                                <input type="text" name="subject" placeholder="উদা: সফটওয়্যার ডেমো ফিডব্যাক আলোচনা..." class="w-full text-xs border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                            </div>

                            <!-- Notes -->
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">আলোচনার বিবরণ ও ফলাফল <span class="text-rose-500">*</span></label>
                                <textarea name="notes" rows="3" required placeholder="ক্লায়েন্ট কী বলল, কোনো বিশেষ শর্ত বা বাজেট প্রস্তাব দিলো কি না..." class="w-full text-xs border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                            </div>

                            <!-- Next Action -->
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">পরবর্তী পদক্ষেপ (Next Action)</label>
                                <input type="text" name="next_action" placeholder="উদা: কোটেশন পাঠানো, ডেমো লিংক প্রদান..." class="w-full text-xs border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                            </div>

                            <div class="flex justify-end">
                                <button type="submit" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-bold uppercase tracking-wider transition shadow-sm flex items-center gap-1.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                                    লগ সংরক্ষণ করুন
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Communication & Activity Timeline -->
                    <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm space-y-4">
                        <h3 class="text-base font-bold text-gray-900 flex items-center gap-2 border-b pb-3">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            যোগাযোগ ও ফলো-আপের ইতিহাস (Communication Timeline)
                        </h3>

                        <div class="relative pl-6 space-y-6 before:absolute before:left-2 before:top-2 before:bottom-2 before:w-0.5 before:bg-gray-200">
                            @forelse($lead->followUps as $item)
                                <div class="relative group">
                                    <!-- Timeline Icon Dot -->
                                    <div class="absolute -left-6 top-1 w-4 h-4 rounded-full border-2 border-white {{ $item->status === 'completed' ? 'bg-emerald-500 ring-2 ring-emerald-100' : 'bg-amber-500 ring-2 ring-amber-100' }}"></div>

                                    <div class="bg-gray-50/70 p-4 rounded-xl border border-gray-200/80 hover:bg-white hover:shadow-sm transition">
                                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1 mb-2">
                                            <div class="flex items-center gap-2">
                                                <span class="font-bold text-xs text-gray-900">{{ $item->type_label }}</span>
                                                <span class="text-[11px] px-2 py-0.5 rounded font-semibold {{ $item->status === 'completed' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                                    {{ $item->status === 'completed' ? 'সম্পন্ন' : 'নির্ধারিত (Scheduled)' }}
                                                </span>
                                            </div>
                                            <div class="text-[11px] text-gray-400">
                                                {{ ($item->conducted_at ?? $item->scheduled_at ?? $item->created_at)->format('d M, Y h:i A') }}
                                            </div>
                                        </div>

                                        @if($item->subject)
                                            <div class="font-semibold text-xs text-indigo-900 mb-1">{{ $item->subject }}</div>
                                        @endif

                                        <p class="text-xs text-gray-700 whitespace-pre-line leading-relaxed">{{ $item->notes }}</p>

                                        @if($item->next_action)
                                            <div class="mt-2 text-[11px] font-medium text-amber-800 bg-amber-50/80 p-2 rounded-lg border border-amber-100 flex items-center gap-1.5">
                                                <svg class="w-3.5 h-3.5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"/></svg>
                                                পরবর্তী করণীয়: {{ $item->next_action }}
                                            </div>
                                        @endif

                                        <div class="mt-2.5 pt-2 border-t border-gray-200/60 flex items-center justify-between text-[11px] text-gray-500">
                                            <span>কর্মী: <strong class="text-gray-700">{{ $item->user->name ?? 'System' }}</strong></span>

                                            <div class="flex items-center gap-2">
                                                @if($item->status !== 'completed')
                                                    <form method="POST" action="{{ route('follow-ups.complete', $item) }}" class="inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="text-emerald-600 hover:text-emerald-800 font-semibold hover:underline">
                                                            ✓ সম্পন্ন মার্ক করুন
                                                        </button>
                                                    </form>
                                                @endif
                                                <form method="POST" action="{{ route('follow-ups.destroy', $item) }}" onsubmit="return confirm('মুছে ফেলতে চান?');" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-gray-400 hover:text-rose-600">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-8 text-gray-400 text-xs">
                                    এখনো কোনো যোগাযোগের ইতিহাস যুক্ত করা হয়নি। উপরের ফর্ম ব্যবহার করে প্রথম কল বা নোট সংরক্ষণ করুন।
                                </div>
                            @endforelse
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </div>
</x-app-layout>
