<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                    নতুন অর্ডার তৈরি (Create Sales Order)
                </h2>
                <p class="text-sm text-gray-500 mt-1">ক্লায়েন্টের অনুমোদিত কাজের অর্ডার এন্ট্রি ও প্রজেক্ট সেটআপ</p>
            </div>
            <a href="{{ route('orders.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-200 transition">
                <svg class="w-4 h-4 me-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                অর্ডার তালিকায় ফিরে যান
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            @if($preselectedQuotation)
                <div class="mb-6 p-4 bg-indigo-50 border border-indigo-200 rounded-xl flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <span class="p-2 bg-indigo-600 text-white rounded-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </span>
                        <div>
                            <div class="text-sm font-bold text-indigo-900">কোটেশন থেকে কনভার্ট করা হচ্ছে: {{ $preselectedQuotation->quotation_code }}</div>
                            <div class="text-xs text-indigo-700">ক্লায়েন্ট: {{ $preselectedQuotation->customer?->name ?? $preselectedQuotation->lead?->name }} | প্রস্তাবিত পরিমাণ: ৳{{ number_format($preselectedQuotation->grand_total, 2) }}</div>
                        </div>
                    </div>
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 p-4 bg-rose-50 border-l-4 border-rose-500 text-rose-800 rounded-r-lg shadow-sm">
                    <div class="font-bold text-sm">অনুগ্রহ করে নিচের ত্রুটিগুলো সংশোধন করুন:</div>
                    <ul class="mt-2 list-disc list-inside text-xs space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('orders.store') }}" class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 sm:p-8 space-y-6">
                @csrf

                @if($preselectedQuotation)
                    <input type="hidden" name="quotation_id" value="{{ $preselectedQuotation->id }}">
                @endif

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

                    <!-- Customer -->
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">ক্লায়েন্ট (Customer) <span class="text-rose-500">*</span></label>
                        <select name="customer_id" required class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">ক্লায়েন্ট নির্বাচন করুন</option>
                            @foreach($customers as $cust)
                                <option value="{{ $cust->id }}" {{ (old('customer_id', $preselectedCustomer?->id) == $cust->id) ? 'selected' : '' }}>
                                    {{ $cust->name }} {{ $cust->company_name ? "({$cust->company_name})" : '' }} - {{ $cust->phone }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Order Title -->
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">অর্ডারের নাম / প্রজেক্ট টাইটেল <span class="text-rose-500">*</span></label>
                        <input type="text" name="title" required value="{{ old('title', $preselectedQuotation?->title) }}" placeholder="যেমন: ই-কমার্স ওয়েবসাইট ডেভেলপমেন্ট"
                            class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <!-- Service Category -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">সার্ভিস ক্যাটাগরি <span class="text-rose-500">*</span></label>
                        <select name="service_category" required class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">ক্যাটাগরি নির্বাচন করুন</option>
                            @foreach($serviceCategories as $cat)
                                <option value="{{ $cat }}" {{ old('service_category', $preselectedQuotation?->lead?->service_category) == $cat ? 'selected' : '' }}>
                                    {{ $cat }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">স্ট্যাটাস <span class="text-rose-500">*</span></label>
                        <select name="status" required class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            @foreach(['Pending', 'Confirmed', 'Project Started', 'Working', 'Under Review', 'Completed', 'Delivered'] as $st)
                                <option value="{{ $st }}" {{ old('status', 'Confirmed') == $st ? 'selected' : '' }}>{{ $st }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Total Amount -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">মোট চুক্তি মূল্য (৳) <span class="text-rose-500">*</span></label>
                        <input type="number" step="0.01" name="total_amount" id="total_amount" required
                            value="{{ old('total_amount', $preselectedQuotation?->grand_total ?? 0) }}"
                            class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <!-- Paid Amount (Advance) -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">অগ্রিম / পরিশোধিত পরিমাণ (৳)</label>
                        <input type="number" step="0.01" name="paid_amount" id="paid_amount"
                            value="{{ old('paid_amount', 0) }}"
                            class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <!-- Due Amount Display -->
                    <div class="sm:col-span-2 p-3 bg-gray-50 rounded-xl flex items-center justify-between border border-gray-200">
                        <span class="text-sm font-medium text-gray-700">অবশিষ্ট বকেয়া (Due):</span>
                        <span id="due_amount_display" class="text-base font-bold text-rose-600">৳0.00</span>
                    </div>

                    <!-- Order Date -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">অর্ডার তারিখ <span class="text-rose-500">*</span></label>
                        <input type="date" name="order_date" required value="{{ old('order_date', date('Y-m-d')) }}"
                            class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <!-- Expected Delivery Date -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">সম্ভাব্য ডেলিভারি তারিখ</label>
                        <input type="date" name="expected_delivery_date" value="{{ old('expected_delivery_date') }}"
                            class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <!-- Project Manager -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">প্রজেক্ট ম্যানেজার (PM)</label>
                        <select name="project_manager_id" class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">প্রজেক্ট ম্যানেজার নির্বাচন করুন</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('project_manager_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }} ({{ $user->role->name ?? 'Staff' }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Sales Person -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">সেলস প্রতিনিধি</label>
                        <select name="sales_person_id" class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">সেলস কর্মী নির্বাচন করুন</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('sales_person_id', auth()->id()) == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Auto Launch Project -->
                    <div class="sm:col-span-2 p-4 bg-emerald-50/60 border border-emerald-200 rounded-xl flex items-start gap-3">
                        <input type="checkbox" name="auto_create_project" id="auto_create_project" value="1" checked
                            class="mt-1 h-4 w-4 rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                        <label for="auto_create_project" class="text-sm font-medium text-gray-800 cursor-pointer">
                            <span class="font-bold text-emerald-900">স্বয়ংক্রিয়ভাবে প্রজেক্ট তৈরি করুন (Auto-create Project)</span>
                            <p class="text-xs text-gray-500 mt-0.5">অর্ডার সেভ হওয়ার সাথে সাথে প্রোডাকশন টিম ও প্রজেক্ট ম্যানেজারের জন্য প্রজেক্ট ড্যাশবোর্ড চালু হয়ে যাবে।</p>
                        </label>
                    </div>

                    <!-- Notes -->
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">অর্ডার নোট / রিকোয়ারমেন্ট বিস্তারিত</label>
                        <textarea name="notes" rows="3" placeholder="ক্লায়েন্টের স্পেসিফিক চাহিদা বা চুক্তির শর্তাবলী..."
                            class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">{{ old('notes') }}</textarea>
                    </div>

                </div>

                <div class="pt-4 border-t border-gray-100 flex items-center justify-end gap-3">
                    <a href="{{ route('orders.index') }}" class="px-5 py-2.5 bg-gray-100 text-gray-700 rounded-lg text-sm font-semibold hover:bg-gray-200 transition">
                        বাতিল
                    </a>
                    <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white rounded-lg text-sm font-semibold hover:bg-indigo-700 shadow-sm transition">
                        অর্ডার নিশ্চিত করুন
                    </button>
                </div>
            </form>

        </div>
    </div>

    <script>
        function updateDue() {
            const total = parseFloat(document.getElementById('total_amount').value) || 0;
            const paid = parseFloat(document.getElementById('paid_amount').value) || 0;
            const due = Math.max(0, total - paid);
            document.getElementById('due_amount_display').innerText = '৳' + due.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
        }
        document.getElementById('total_amount').addEventListener('input', updateDue);
        document.getElementById('paid_amount').addEventListener('input', updateDue);
        updateDue();
    </script>
</x-app-layout>
