<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                    ভিডিও বিলিং সম্পাদনা: {{ $videoBilling->billing_code }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">{{ $videoBilling->video_title }} এর সময় ও বিলিং রেট পরিবর্তন</p>
            </div>
            <a href="{{ route('video-billings.show', $videoBilling) }}" class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-200 transition">
                <svg class="w-4 h-4 me-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                বিলিং বিস্তারিতে ফিরে যান
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            @if ($errors->any())
                <div class="mb-6 p-4 bg-rose-50 border-l-4 border-rose-500 text-rose-800 rounded-r-lg shadow-sm">
                    <div class="font-bold text-sm">ত্রুটি সংশোধন করুন:</div>
                    <ul class="mt-1 list-disc list-inside text-xs">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('video-billings.update', $videoBilling) }}" class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 sm:p-8 space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

                    <!-- Employee (Read-only) -->
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">ভিডিও এডিটর</label>
                        <div class="p-3 bg-gray-50 border border-gray-200 rounded-lg text-sm text-gray-900 font-medium">
                            {{ $videoBilling->employee->user->name }} ({{ $videoBilling->employee->designation }}) - {{ $videoBilling->employee->employee_code }}
                        </div>
                    </div>

                    <!-- Title -->
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">ভিডিও টাইটেল <span class="text-rose-500">*</span></label>
                        <input type="text" name="video_title" required value="{{ old('video_title', $videoBilling->video_title) }}"
                            class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <!-- Deliverable URL -->
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">ডেলিভারেবল লিংক</label>
                        <input type="url" name="video_url" value="{{ old('video_url', $videoBilling->video_url) }}"
                            class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <!-- Minutes -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">মিনিট <span class="text-rose-500">*</span></label>
                        <input type="number" name="duration_minutes" id="duration_minutes" required min="0" value="{{ old('duration_minutes', $videoBilling->duration_minutes) }}"
                            class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <!-- Seconds -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">সেকেন্ড <span class="text-rose-500">*</span></label>
                        <input type="number" name="duration_seconds" id="duration_seconds" required min="0" max="59" value="{{ old('duration_seconds', $videoBilling->duration_seconds) }}"
                            class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <!-- Rate -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">প্রতি সেকেন্ডের রেট (৳) <span class="text-rose-500">*</span></label>
                        <input type="number" step="0.01" name="rate_per_second" id="rate_per_second" required value="{{ old('rate_per_second', $videoBilling->rate_per_second) }}"
                            class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <!-- Quantity -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">ভিডিও সংখ্যা <span class="text-rose-500">*</span></label>
                        <input type="number" name="number_of_videos" id="number_of_videos" required min="1" value="{{ old('number_of_videos', $videoBilling->number_of_videos) }}"
                            class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <!-- Live Calculator Display Card -->
                    <div class="sm:col-span-2 p-5 bg-gradient-to-br from-indigo-50 to-blue-50 border border-indigo-200 rounded-2xl shadow-sm space-y-3">
                        <div class="flex items-center justify-between text-xs font-bold text-indigo-900 uppercase">
                            <span>আপডেট হিসাব ফলাফল</span>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 pt-1">
                            <div class="bg-white p-3 rounded-xl border border-indigo-100 text-center">
                                <div class="text-xs text-gray-500">মোট সেকেন্ড</div>
                                <div id="display_total_seconds" class="text-xl font-bold text-indigo-900 mt-1">0 সে.</div>
                            </div>
                            <div class="bg-white p-3 rounded-xl border border-indigo-100 text-center">
                                <div class="text-xs text-gray-500">হিসাব সূত্র</div>
                                <div id="display_formula" class="text-xs font-mono font-medium text-gray-700 mt-2">0 সে. &times; ৳0.00</div>
                            </div>
                            <div class="bg-white p-3 rounded-xl border border-indigo-100 text-center bg-emerald-50/50">
                                <div class="text-xs font-semibold text-emerald-700">মোট প্রদেয় বিল</div>
                                <div id="display_total_payable" class="text-2xl font-black text-emerald-800 mt-0.5">৳0.00</div>
                            </div>
                        </div>
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">কাজের অবস্থা</label>
                        <select name="status" class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            @foreach(['Submitted', 'Approved', 'Rejected', 'Draft', 'In Progress'] as $st)
                                <option value="{{ $st }}" {{ old('status', $videoBilling->status) === $st ? 'selected' : '' }}>{{ $st }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Payment Status -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">পেমেন্ট অবস্থা</label>
                        <select name="payment_status" class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            @foreach(['Unpaid', 'Partially Paid', 'Paid'] as $pst)
                                <option value="{{ $pst }}" {{ old('payment_status', $videoBilling->payment_status) === $pst ? 'selected' : '' }}>{{ $pst }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Notes -->
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">নোট</label>
                        <textarea name="notes" rows="2" class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">{{ old('notes', $videoBilling->notes) }}</textarea>
                    </div>

                </div>

                <div class="pt-4 border-t border-gray-100 flex items-center justify-end gap-3">
                    <a href="{{ route('video-billings.show', $videoBilling) }}" class="px-5 py-2.5 bg-gray-100 text-gray-700 rounded-lg text-sm font-semibold hover:bg-gray-200 transition">
                        বাতিল
                    </a>
                    <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white rounded-lg text-sm font-semibold hover:bg-indigo-700 shadow-sm transition">
                        পরিবর্তন সংরক্ষণ করুন
                    </button>
                </div>
            </form>

        </div>
    </div>

    <script>
        function recalculate() {
            const mins = parseInt(document.getElementById('duration_minutes').value) || 0;
            const secs = parseInt(document.getElementById('duration_seconds').value) || 0;
            const rate = parseFloat(document.getElementById('rate_per_second').value) || 0;
            const qty = parseInt(document.getElementById('number_of_videos').value) || 1;

            const totalSeconds = (mins * 60) + secs;
            const totalPayable = (totalSeconds * rate * qty);

            document.getElementById('display_total_seconds').innerText = totalSeconds.toLocaleString() + ' সেকেন্ড';
            document.getElementById('display_formula').innerText = `${totalSeconds} সে. × ৳${rate.toFixed(2)}` + (qty > 1 ? ` × ${qty}` : '');
            document.getElementById('display_total_payable').innerText = '৳' + totalPayable.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
        }

        document.getElementById('duration_minutes').addEventListener('input', recalculate);
        document.getElementById('duration_seconds').addEventListener('input', recalculate);
        document.getElementById('rate_per_second').addEventListener('input', recalculate);
        document.getElementById('number_of_videos').addEventListener('input', recalculate);

        recalculate();
    </script>
</x-app-layout>
