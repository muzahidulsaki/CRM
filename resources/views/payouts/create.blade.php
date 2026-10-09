<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                    নতুন পে-আউট ভাউচার তৈরি (Create Payout Voucher)
                </h2>
                <p class="text-sm text-gray-500 mt-1">কর্মীর ভিডিও বিলিং, মাসিক বেতন বা বোনাস পরিশোধ ও মানি রিসিট প্রস্তুতকরণ</p>
            </div>
            <a href="{{ route('payouts.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-200 transition">
                <svg class="w-4 h-4 me-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                ভাউচার তালিকায় ফিরুন
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

            <form method="POST" action="{{ route('payouts.store') }}" class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 sm:p-8 space-y-6">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

                    <!-- Employee Selection -->
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">কর্মী নির্বাচন করুন <span class="text-rose-500">*</span></label>
                        <select name="employee_id" id="employee_id" required class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">কর্মী সিলেক্ট করুন</option>
                            @foreach($employees as $emp)
                                <option value="{{ $emp->id }}"
                                    data-paytype="{{ $emp->payment_type }}"
                                    data-rate="{{ $emp->salary_or_rate }}"
                                    data-due="{{ $emp->current_due }}"
                                    {{ (old('employee_id', $selectedEmployee?->id) == $emp->id) ? 'selected' : '' }}>
                                    {{ $emp->user->name }} ({{ $emp->designation }}) - বকেয়া: ৳{{ number_format($emp->current_due, 2) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Payout Type -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">পেমেন্ট ক্যাটাগরি <span class="text-rose-500">*</span></label>
                        <select name="payout_type" id="payout_type" required class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="video_billing" {{ old('payout_type') === 'video_billing' ? 'selected' : '' }}>ভিডিও এডিটর বিলিং (Video Billing)</option>
                            <option value="monthly_salary" {{ old('payout_type') === 'monthly_salary' ? 'selected' : '' }}>মাসিক ফিক্সড বেতন (Monthly Salary)</option>
                            <option value="task_contract" {{ old('payout_type') === 'task_contract' ? 'selected' : '' }}>টাস্ক চুক্তি বিল (Task Contract)</option>
                            <option value="advance" {{ old('payout_type') === 'advance' ? 'selected' : '' }}>অগ্রিম পারিশ্রমিক (Advance)</option>
                            <option value="bonus" {{ old('payout_type') === 'bonus' ? 'selected' : '' }}>পারফরম্যান্স বোনাস (Bonus)</option>
                        </select>
                    </div>

                    <!-- Salary Month -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">বেতনের মাস (যদি প্রযোজ্য হয়)</label>
                        <input type="month" name="salary_month" value="{{ old('salary_month', date('Y-m')) }}"
                            class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <!-- UNPAID VIDEO BILLINGS CONTAINER -->
                    <div id="videoBillingsContainer" class="sm:col-span-2 p-4 bg-indigo-50/40 border border-indigo-200 rounded-2xl space-y-3">
                        <div class="flex items-center justify-between text-xs font-bold text-indigo-900 uppercase">
                            <span>পরিশোধের জন্য অনুমোদিত বকেয়া ভিডিওসমূহ নির্বাচন করুন</span>
                            <span class="text-indigo-600 font-normal">চেকবক্সে টিক দিলে স্বয়ংক্রিয়ভাবে মূল বিল যোগ হবে</span>
                        </div>
                        <div id="videoBillingsList" class="space-y-2 max-h-48 overflow-y-auto">
                            @if($selectedBilling)
                                <label class="flex items-center justify-between p-2.5 bg-white rounded-xl border border-gray-200 cursor-pointer">
                                    <div class="flex items-center gap-2">
                                        <input type="checkbox" name="linked_video_billing_ids[]" value="{{ $selectedBilling->id }}" checked
                                            data-amount="{{ $selectedBilling->due_amount }}"
                                            class="billing-checkbox rounded text-indigo-600 focus:ring-indigo-500">
                                        <span class="text-xs font-bold text-gray-900">{{ $selectedBilling->billing_code }} - {{ $selectedBilling->video_title }}</span>
                                    </div>
                                    <span class="text-xs font-bold text-rose-600">৳{{ number_format($selectedBilling->due_amount, 2) }}</span>
                                </label>
                            @else
                                <p class="text-xs text-gray-400 italic">কর্মী নির্বাচন করলে বকেয়া ভিডিও তালিকা এখানে দৃশ্যমান হবে।</p>
                            @endif
                        </div>
                    </div>

                    <!-- Base Amount -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">মূল বিল / বেতন (৳) <span class="text-rose-500">*</span></label>
                        <input type="number" step="0.01" name="base_amount" id="base_amount" required
                            value="{{ old('base_amount', $selectedBilling?->due_amount ?? 0) }}"
                            class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <!-- Bonus Amount -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">অতিরিক্ত বোনাস / টিপ (৳)</label>
                        <input type="number" step="0.01" name="bonus_amount" id="bonus_amount" value="{{ old('bonus_amount', 0) }}"
                            class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <!-- Deduction Amount -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">কর্তন / জরিমানা (৳)</label>
                        <input type="number" step="0.01" name="deduction_amount" id="deduction_amount" value="{{ old('deduction_amount', 0) }}"
                            class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <!-- Paid Amount (Net) -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">প্রকৃত পরিশোধের পরিমাণ (৳) <span class="text-rose-500">*</span></label>
                        <input type="number" step="0.01" name="paid_amount" id="paid_amount" required
                            value="{{ old('paid_amount', $selectedBilling?->due_amount ?? 0) }}"
                            class="w-full text-sm rounded-lg font-bold text-gray-900 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 bg-gray-50">
                    </div>

                    <!-- Live Calculation Card -->
                    <div class="sm:col-span-2 p-3.5 bg-emerald-50 border border-emerald-200 rounded-xl flex items-center justify-between text-xs">
                        <span class="font-medium text-emerald-900">মোট প্রদেয় নিট অর্থ (Net Amount):</span>
                        <span id="display_net_amount" class="text-lg font-black text-emerald-800">৳0.00</span>
                    </div>

                    <!-- Payment Method -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">পেমেন্ট মেথড <span class="text-rose-500">*</span></label>
                        <select name="payment_method" required class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="bKash" selected>bKash (বিকাশ)</option>
                            <option value="Nagad">Nagad (নগদ)</option>
                            <option value="Rocket">Rocket (রকেট)</option>
                            <option value="Bank Transfer">Bank Transfer (ব্যাংক একাউন্ট)</option>
                            <option value="Cash">Cash (নগদ টাকা)</option>
                        </select>
                    </div>

                    <!-- Account Details -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">একাউন্ট / মোবাইল নম্বর</label>
                        <input type="text" name="account_details" placeholder="যেমন: 018XXXXXXXX"
                            class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <!-- Transaction Reference -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">ট্রানজেকশন রেফারেন্স / TrxID</label>
                        <input type="text" name="transaction_reference" placeholder="যেমন: 9J8A7K6L5M"
                            class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <!-- Payment Date -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">পরিশোধের তারিখ <span class="text-rose-500">*</span></label>
                        <input type="date" name="payment_date" required value="{{ old('payment_date', date('Y-m-d')) }}"
                            class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <!-- Notes -->
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">ভাউচার নোট</label>
                        <textarea name="notes" rows="2" placeholder="পেমেন্ট সংক্রান্ত বিশেষ কোনো মন্তব্য..."
                            class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">{{ old('notes') }}</textarea>
                    </div>

                </div>

                <div class="pt-4 border-t border-gray-100 flex items-center justify-end gap-3">
                    <a href="{{ route('payouts.index') }}" class="px-5 py-2.5 bg-gray-100 text-gray-700 rounded-lg text-sm font-semibold hover:bg-gray-200 transition">
                        বাতিল
                    </a>
                    <button type="submit" class="px-6 py-2.5 bg-emerald-600 text-white rounded-lg text-sm font-semibold hover:bg-emerald-700 shadow-sm transition">
                        ভাউচার নিশ্চিত ও পরিশোধ করুন
                    </button>
                </div>
            </form>

        </div>
    </div>

    <script>
        function recalculateNet() {
            const base = parseFloat(document.getElementById('base_amount').value) || 0;
            const bonus = parseFloat(document.getElementById('bonus_amount').value) || 0;
            const deduction = parseFloat(document.getElementById('deduction_amount').value) || 0;
            const net = Math.max(0, base + bonus - deduction);

            document.getElementById('display_net_amount').innerText = '৳' + net.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
            document.getElementById('paid_amount').value = net.toFixed(2);
        }

        document.getElementById('base_amount').addEventListener('input', recalculateNet);
        document.getElementById('bonus_amount').addEventListener('input', recalculateNet);
        document.getElementById('deduction_amount').addEventListener('input', recalculateNet);

        // Fetch Employee Pending Billings via AJAX
        document.getElementById('employee_id').addEventListener('change', function() {
            const empId = this.value;
            if (!empId) return;

            fetch(`/payouts/pending-ajax/${empId}`)
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') {
                        const billingsList = document.getElementById('videoBillingsList');
                        billingsList.innerHTML = '';

                        if (data.billings.length > 0) {
                            let totalPendingDue = 0;
                            data.billings.forEach(b => {
                                totalPendingDue += parseFloat(b.due_amount);
                                const item = document.createElement('label');
                                item.className = 'flex items-center justify-between p-2.5 bg-white rounded-xl border border-gray-200 cursor-pointer hover:bg-gray-50';
                                item.innerHTML = `
                                    <div class="flex items-center gap-2">
                                        <input type="checkbox" name="linked_video_billing_ids[]" value="${b.id}" checked
                                            data-amount="${b.due_amount}"
                                            class="billing-checkbox rounded text-indigo-600 focus:ring-indigo-500">
                                        <span class="text-xs font-bold text-gray-900">${b.billing_code} - ${b.video_title}</span>
                                    </div>
                                    <span class="text-xs font-bold text-rose-600">৳${parseFloat(b.due_amount).toLocaleString()}</span>
                                `;
                                billingsList.appendChild(item);
                            });

                            document.getElementById('base_amount').value = totalPendingDue.toFixed(2);
                            bindCheckboxEvents();
                        } else {
                            billingsList.innerHTML = '<p class="text-xs text-gray-500 italic">এই কর্মীর কোনো বকেয়া ভিডিও বিল নেই।</p>';
                            if (data.employee.payment_type === 'monthly_salary') {
                                document.getElementById('base_amount').value = parseFloat(data.employee.salary_or_rate || 0).toFixed(2);
                            } else {
                                document.getElementById('base_amount').value = '0.00';
                            }
                        }
                        recalculateNet();
                    }
                });
        });

        function bindCheckboxEvents() {
            document.querySelectorAll('.billing-checkbox').forEach(cb => {
                cb.addEventListener('change', function() {
                    let sum = 0;
                    document.querySelectorAll('.billing-checkbox:checked').forEach(c => {
                        sum += parseFloat(c.getAttribute('data-amount')) || 0;
                    });
                    document.getElementById('base_amount').value = sum.toFixed(2);
                    recalculateNet();
                });
            });
        }

        bindCheckboxEvents();
        recalculateNet();
    </script>
</x-app-layout>
