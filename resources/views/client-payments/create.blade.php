<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-slate-800 leading-tight">
                    Record Client Payment
                </h2>
                <p class="text-xs text-slate-500 mt-1">Receive collection, generate an official Money Receipt, and auto-settle balances.</p>
            </div>
            <a href="{{ route('client-payments.index') }}" class="px-3.5 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-lg transition">
                &larr; Back to Payments
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sm:p-8">

                @if($errors->any())
                    <div class="mb-6 p-4 bg-rose-50 border border-rose-300 rounded-xl text-rose-800 text-sm">
                        <h4 class="font-bold mb-1">Please fix the following issues:</h4>
                        <ul class="list-disc list-inside space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('client-payments.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <!-- Client and Bill Linkage -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                                Client / Customer <span class="text-rose-500">*</span>
                            </label>
                            <select name="customer_id" id="customer_id" required class="w-full text-xs px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none">
                                <option value="">-- Select Client --</option>
                                @foreach($customers as $c)
                                    <option value="{{ $c->id }}" {{ (old('customer_id', $prefilledCustomerId) == $c->id) ? 'selected' : '' }}>
                                        {{ $c->name }} ({{ $c->company_name ?? 'Individual' }}) - {{ $c->phone }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                                Link to Invoice (Optional)
                            </label>
                            <select name="invoice_id" id="invoice_id" class="w-full text-xs px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none">
                                <option value="">-- No specific invoice --</option>
                                @foreach($invoices as $inv)
                                    <option value="{{ $inv->id }}" data-due="{{ $inv->due_amount }}" data-customer="{{ $inv->customer_id }}"
                                        {{ (old('invoice_id', $prefilledInvoice?->id) == $inv->id) ? 'selected' : '' }}>
                                        {{ $inv->invoice_no }} ({{ $inv->customer->name }} - Due: {{ __('BDT') }} {{ number_format($inv->due_amount) }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                                Link to Order (Optional)
                            </label>
                            <select name="order_id" id="order_id" class="w-full text-xs px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none">
                                <option value="">-- No specific order --</option>
                                @foreach($orders as $ord)
                                    <option value="{{ $ord->id }}" data-due="{{ $ord->due_amount }}" data-customer="{{ $ord->customer_id }}"
                                        {{ (old('order_id', $prefilledOrder?->id) == $ord->id) ? 'selected' : '' }}>
                                        {{ $ord->order_code }} (Due: {{ __('BDT') }} {{ number_format($ord->due_amount) }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                                Amount Paid ({{ __('BDT') }}) <span class="text-rose-500">*</span>
                            </label>
                            <input type="number" step="0.01" min="1" name="amount" id="amount"
                                value="{{ old('amount', $prefilledInvoice?->due_amount ?? ($prefilledOrder?->due_amount ?? '')) }}" required
                                class="w-full text-sm font-bold text-emerald-700 px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none"
                                placeholder="e.g. 25000">
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                                Payment Date <span class="text-rose-500">*</span>
                            </label>
                            <input type="date" name="payment_date" value="{{ old('payment_date', now()->toDateString()) }}" required
                                class="w-full text-xs px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                                Payment Method <span class="text-rose-500">*</span>
                            </label>
                            <select name="payment_method" required class="w-full text-xs px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none">
                                @foreach($methods as $m)
                                    <option value="{{ $m }}" {{ old('payment_method', 'Bank Transfer') === $m ? 'selected' : '' }}>
                                        {{ $m }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                                Transaction ID / Cheque # / Ref
                            </label>
                            <input type="text" name="transaction_reference" value="{{ old('transaction_reference') }}"
                                class="w-full text-xs px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none"
                                placeholder="e.g. TRX-998811 or Bank Slip #">
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Payment Receipt Notes</label>
                            <textarea name="notes" rows="2" class="w-full text-xs px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none"
                                placeholder="e.g. 50% milestone advance payment received via City Bank Online Transfer.">{{ old('notes') }}</textarea>
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Deposit Slip / Screenshot (Optional)</label>
                            <input type="file" name="attachment" class="w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded file:border-0 file:bg-slate-100 file:font-semibold">
                        </div>
                    </div>

                    <!-- Submit -->
                    <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-3">
                        <a href="{{ route('client-payments.index') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold text-xs rounded-lg transition">
                            Cancel
                        </a>
                        <button type="submit" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-lg shadow-md transition">
                            Save & Generate Money Receipt
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const invoiceSelect = document.getElementById('invoice_id');
            const customerSelect = document.getElementById('customer_id');
            const amountInput = document.getElementById('amount');

            invoiceSelect.addEventListener('change', function () {
                const selected = this.options[this.selectedIndex];
                if (selected && selected.value) {
                    const due = selected.getAttribute('data-due');
                    const custId = selected.getAttribute('data-customer');
                    if (due && !amountInput.value) {
                        amountInput.value = due;
                    }
                    if (custId && !customerSelect.value) {
                        customerSelect.value = custId;
                    }
                }
            });
        });
    </script>
</x-app-layout>
