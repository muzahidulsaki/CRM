<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                    {{ __('Create Payout Voucher') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">{{ __('Issue payment voucher and money receipt for video billing, monthly salary, or bonus') }}</p>
            </div>
            <a href="{{ route('payouts.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-200 transition">
                <svg class="w-4 h-4 me-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                {{ __('Back to Vouchers') }}
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            @if ($errors->any())
                <div class="mb-6 p-4 bg-rose-50 border-l-4 border-rose-500 text-rose-800 rounded-r-lg shadow-sm">
                    <div class="font-bold text-sm">{{ __('Please correct the errors:') }}</div>
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
                        <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('Select Staff Member') }} <span class="text-rose-500">*</span></label>
                        <select name="employee_id" id="employee_id" required class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">{{ __('Select Staff') }}</option>
                            @foreach($employees as $emp)
                                <option value="{{ $emp->id }}"
                                    data-paytype="{{ $emp->payment_type }}"
                                    data-rate="{{ $emp->salary_or_rate }}"
                                    data-due="{{ $emp->current_due }}"
                                    {{ (old('employee_id', $selectedEmployee?->id) == $emp->id) ? 'selected' : '' }}>
                                    {{ $emp->user->name }} ({{ $emp->designation }}) - {{ __('Due') }}: {{ __('BDT') }} {{ number_format($emp->current_due, 2) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Payout Type -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('Payment Category') }} <span class="text-rose-500">*</span></label>
                        <select name="payout_type" id="payout_type" required class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="video_billing" {{ old('payout_type') === 'video_billing' ? 'selected' : '' }}>{{ __('Video Billing') }}</option>
                            <option value="monthly_salary" {{ old('payout_type') === 'monthly_salary' ? 'selected' : '' }}>{{ __('Monthly Salary') }}</option>
                            <option value="task_contract" {{ old('payout_type') === 'task_contract' ? 'selected' : '' }}>{{ __('Task Contract') }}</option>
                            <option value="advance" {{ old('payout_type') === 'advance' ? 'selected' : '' }}>{{ __('Advance') }}</option>
                            <option value="bonus" {{ old('payout_type') === 'bonus' ? 'selected' : '' }}>{{ __('Performance Bonus') }}</option>
                        </select>
                    </div>

                    <!-- Salary Month -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('Salary Month (If applicable)') }}</label>
                        <input type="month" name="salary_month" value="{{ old('salary_month', date('Y-m')) }}"
                            class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <!-- UNPAID VIDEO BILLINGS CONTAINER -->
                    <div id="videoBillingsContainer" class="sm:col-span-2 p-4 bg-indigo-50/40 border border-indigo-200 rounded-2xl space-y-3">
                        <div class="flex items-center justify-between text-xs font-bold text-indigo-900 uppercase">
                            <span>{{ __('Select pending approved video billings to settle') }}</span>
                            <span class="text-indigo-600 font-normal">{{ __('Checking items will automatically sum base amount') }}</span>
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
                                    <span class="text-xs font-bold text-rose-600">{{ __('BDT') }} {{ number_format($selectedBilling->due_amount, 2) }}</span>
                                </label>
                            @else
                                <p class="text-xs text-gray-400 italic">{{ __('Select a staff member to view pending video billings.') }}</p>
                            @endif
                        </div>
                    </div>

                    <!-- Base Amount -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('Base Bill / Salary') }} ({{ __('BDT') }}) <span class="text-rose-500">*</span></label>
                        <input type="number" step="0.01" name="base_amount" id="base_amount" required
                            value="{{ old('base_amount', $selectedBilling?->due_amount ?? 0) }}"
                            class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <!-- Bonus Amount -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('Additional Bonus / Incentive') }} ({{ __('BDT') }})</label>
                        <input type="number" step="0.01" name="bonus_amount" id="bonus_amount" value="{{ old('bonus_amount', 0) }}"
                            class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <!-- Deduction Amount -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('Deduction / Fine') }} ({{ __('BDT') }})</label>
                        <input type="number" step="0.01" name="deduction_amount" id="deduction_amount" value="{{ old('deduction_amount', 0) }}"
                            class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <!-- Paid Amount (Net) -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('Net Amount to Pay') }} ({{ __('BDT') }}) <span class="text-rose-500">*</span></label>
                        <input type="number" step="0.01" name="paid_amount" id="paid_amount" required
                            value="{{ old('paid_amount', $selectedBilling?->due_amount ?? 0) }}"
                            class="w-full text-sm rounded-lg font-bold text-gray-900 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 bg-gray-50">
                    </div>

                    <!-- Live Calculation Card -->
                    <div class="sm:col-span-2 p-3.5 bg-emerald-50 border border-emerald-200 rounded-xl flex items-center justify-between text-xs">
                        <span class="font-medium text-emerald-900">{{ __('Net Payable Amount') }}:</span>
                        <span id="display_net_amount" class="text-lg font-black text-emerald-800">{{ __('BDT') }} 0.00</span>
                    </div>

                    <!-- Payment Method -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('Payment Method') }} <span class="text-rose-500">*</span></label>
                        <select name="payment_method" required class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="bKash" selected>bKash</option>
                            <option value="Nagad">Nagad</option>
                            <option value="Rocket">Rocket</option>
                            <option value="Bank Transfer">Bank Transfer</option>
                            <option value="Cash">Cash</option>
                        </select>
                    </div>

                    <!-- Account Details -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('Account / Mobile Number') }}</label>
                        <input type="text" name="account_details" placeholder="e.g. 018XXXXXXXX"
                            class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <!-- Transaction Reference -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('Transaction Reference / TrxID') }}</label>
                        <input type="text" name="transaction_reference" placeholder="e.g. 9J8A7K6L5M"
                            class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <!-- Payment Date -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('Payment Date') }} <span class="text-rose-500">*</span></label>
                        <input type="date" name="payment_date" required value="{{ old('payment_date', date('Y-m-d')) }}"
                            class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <!-- Notes -->
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('Voucher Notes') }}</label>
                        <textarea name="notes" rows="2" placeholder="{{ __('Optional payment remarks...') }}"
                            class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">{{ old('notes') }}</textarea>
                    </div>

                </div>

                <div class="pt-4 border-t border-gray-100 flex items-center justify-end gap-3">
                    <a href="{{ route('payouts.index') }}" class="px-5 py-2.5 bg-gray-100 text-gray-700 rounded-lg text-sm font-semibold hover:bg-gray-200 transition">
                        {{ __('Cancel') }}
                    </a>
                    <button type="submit" class="px-6 py-2.5 bg-emerald-600 text-white rounded-lg text-sm font-semibold hover:bg-emerald-700 shadow-sm transition">
                        {{ __('Confirm & Pay Voucher') }}
                    </button>
                </div>
            </form>

        </div>
    </div>

    <script>
        const currencyPrefix = '{{ __('BDT') }} ';

        function recalculateNet() {
            const base = parseFloat(document.getElementById('base_amount').value) || 0;
            const bonus = parseFloat(document.getElementById('bonus_amount').value) || 0;
            const deduction = parseFloat(document.getElementById('deduction_amount').value) || 0;
            const net = Math.max(0, base + bonus - deduction);

            document.getElementById('display_net_amount').innerText = currencyPrefix + net.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
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
                                    <span class="text-xs font-bold text-rose-600">${currencyPrefix}${parseFloat(b.due_amount).toLocaleString()}</span>
                                `;
                                billingsList.appendChild(item);
                            });

                            document.getElementById('base_amount').value = totalPendingDue.toFixed(2);
                            bindCheckboxEvents();
                        } else {
                            billingsList.innerHTML = '<p class="text-xs text-gray-500 italic">{{ __('No pending video billings for this staff.') }}</p>';
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
