<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-slate-800 leading-tight">
                    Create New Invoice
                </h2>
                <p class="text-xs text-slate-500 mt-1">Generate an official bill with itemized services, VAT/Tax, and custom discounts.</p>
            </div>
            <a href="{{ route('invoices.index') }}" class="px-3.5 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-lg transition">
                &larr; Back to Invoices
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
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

                <form action="{{ route('invoices.store') }}" method="POST" id="invoiceForm" class="space-y-6">
                    @csrf

                    <!-- Invoice Header Section -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pb-6 border-b border-slate-100">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                                Client / Customer <span class="text-rose-500">*</span>
                            </label>
                            <select name="customer_id" id="customer_id" required class="w-full text-xs px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                                <option value="">-- Select Client --</option>
                                @foreach($customers as $c)
                                    <option value="{{ $c->id }}" {{ (old('customer_id', $prefilledCustomerId) == $c->id) ? 'selected' : '' }}>
                                        {{ $c->name }} ({{ $c->company_name ?? 'Individual' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                                Related Project (Optional)
                            </label>
                            <select name="project_id" class="w-full text-xs px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                                <option value="">-- None / General Billing --</option>
                                @foreach($projects as $p)
                                    <option value="{{ $p->id }}" {{ (old('project_id', $prefilledOrder?->project?->id) == $p->id) ? 'selected' : '' }}>
                                        {{ $p->name }} ({{ $p->project_code }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                                Related Order (Optional)
                            </label>
                            <select name="order_id" class="w-full text-xs px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                                <option value="">-- None --</option>
                                @foreach($orders as $o)
                                    <option value="{{ $o->id }}" {{ (old('order_id', $prefilledOrder?->id) == $o->id) ? 'selected' : '' }}>
                                        {{ $o->order_code }} (Due: {{ __('BDT') }} {{ number_format($o->due_amount) }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Issue Date <span class="text-rose-500">*</span></label>
                            <input type="date" name="issue_date" value="{{ old('issue_date', now()->toDateString()) }}" required
                                class="w-full text-xs px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Payment Due Date <span class="text-rose-500">*</span></label>
                            <input type="date" name="due_date" value="{{ old('due_date', now()->addDays(15)->toDateString()) }}" required
                                class="w-full text-xs px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Initial Status</label>
                            <select name="status" class="w-full text-xs px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                                <option value="Sent" selected>Sent (Issued to Client)</option>
                                <option value="Draft">Draft</option>
                            </select>
                        </div>
                    </div>

                    <!-- Itemized Line Items -->
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="text-sm font-bold uppercase tracking-wider text-slate-800">
                                Invoice Items & Services
                            </h3>
                            <button type="button" id="addRowBtn" class="inline-flex items-center px-3 py-1 bg-blue-50 hover:bg-blue-100 text-blue-700 text-xs font-bold rounded-lg border border-blue-200 transition">
                                + Add Another Item
                            </button>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-slate-200 text-left text-xs" id="itemsTable">
                                <thead class="bg-slate-50 text-slate-500 font-bold uppercase tracking-wider">
                                    <tr>
                                        <th class="px-3 py-2 w-1/4">Service Category</th>
                                        <th class="px-3 py-2 w-2/5">Description <span class="text-rose-500">*</span></th>
                                        <th class="px-3 py-2 w-20 text-center">Qty</th>
                                        <th class="px-3 py-2 w-28 text-right">Unit Price ({{ __('BDT') }})</th>
                                        <th class="px-3 py-2 w-28 text-right">Total ({{ __('BDT') }})</th>
                                        <th class="px-3 py-2 w-12 text-center"></th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100" id="itemsBody">
                                    <tr class="item-row">
                                        <td class="p-2">
                                            <input type="text" name="items[0][service_category]" value="Website Development"
                                                class="w-full text-xs px-2.5 py-1.5 border border-slate-300 rounded focus:ring-1 focus:ring-blue-500 outline-none">
                                        </td>
                                        <td class="p-2">
                                            <input type="text" name="items[0][item_description]" required placeholder="e.g. Corporate Web Portal Design & CMS"
                                                class="w-full text-xs px-2.5 py-1.5 border border-slate-300 rounded focus:ring-1 focus:ring-blue-500 outline-none">
                                        </td>
                                        <td class="p-2">
                                            <input type="number" step="1" min="1" name="items[0][quantity]" value="1" required
                                                class="item-qty w-full text-xs px-2.5 py-1.5 border border-slate-300 rounded text-center focus:ring-1 focus:ring-blue-500 outline-none">
                                        </td>
                                        <td class="p-2">
                                            <input type="number" step="0.01" min="0" name="items[0][unit_price]" value="35000" required
                                                class="item-price w-full text-xs px-2.5 py-1.5 border border-slate-300 rounded text-right focus:ring-1 focus:ring-blue-500 outline-none">
                                        </td>
                                        <td class="p-2 text-right font-bold text-slate-800 item-row-total">
                                            {{ __('BDT') }} 35,000.00
                                        </td>
                                        <td class="p-2 text-center">
                                            <button type="button" class="remove-row-btn text-rose-400 hover:text-rose-600 font-bold text-sm">
                                                &times;
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Summary & Discount/Tax Calculation Block -->
                    <div class="pt-4 border-t border-slate-100 grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-3">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Invoice Notes / Instructions</label>
                                <textarea name="notes" rows="2" class="w-full text-xs px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none"
                                    placeholder="Thank you for your business. Please include Invoice # in payment reference."></textarea>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Payment Terms</label>
                                <input type="text" name="terms" value="Payment due within 15 days of issue date."
                                    class="w-full text-xs px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                            </div>
                        </div>

                        <!-- Mathematical Summary Card -->
                        <div class="bg-slate-50 p-5 rounded-xl border border-slate-200 space-y-3 text-xs">
                            <div class="flex justify-between items-center text-slate-600">
                                <span>Subtotal:</span>
                                <span class="font-bold text-slate-900" id="subtotalDisplay">{{ __('BDT') }} 0.00</span>
                            </div>

                            <div class="flex items-center justify-between gap-3 pt-2 border-t border-slate-200">
                                <span class="text-slate-600">Discount:</span>
                                <div class="flex items-center space-x-2">
                                    <select name="discount_type" id="discountType" class="text-xs px-2 py-1 border border-slate-300 rounded bg-white">
                                        <option value="fixed">Fixed ({{ __('BDT') }})</option>
                                        <option value="percentage">Percent (%)</option>
                                    </select>
                                    <input type="number" step="0.01" min="0" name="discount_value" id="discountValue" value="0"
                                        class="w-24 text-xs px-2 py-1 border border-slate-300 rounded text-right bg-white">
                                </div>
                            </div>

                            <div class="flex justify-between items-center text-rose-600">
                                <span>Discount Amount:</span>
                                <span class="font-semibold" id="discountDisplay">-{{ __('BDT') }} 0.00</span>
                            </div>

                            <div class="flex items-center justify-between gap-3 pt-2 border-t border-slate-200">
                                <span class="text-slate-600">VAT / Tax (%):</span>
                                <input type="number" step="0.01" min="0" max="100" name="tax_percentage" id="taxPercentage" value="0"
                                    class="w-24 text-xs px-2 py-1 border border-slate-300 rounded text-right bg-white">
                            </div>

                            <div class="flex justify-between items-center text-slate-600">
                                <span>Tax Amount:</span>
                                <span class="font-semibold" id="taxDisplay">+{{ __('BDT') }} 0.00</span>
                            </div>

                            <div class="pt-3 border-t border-slate-300 flex justify-between items-center text-sm font-black text-slate-900">
                                <span class="uppercase tracking-wider">Net Total Payable:</span>
                                <span class="text-lg text-blue-600" id="grandTotalDisplay">{{ __('BDT') }} 0.00</span>
                            </div>
                        </div>
                    </div>

                    <!-- Submit -->
                    <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-3">
                        <a href="{{ route('invoices.index') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold text-xs rounded-lg transition">
                            Cancel
                        </a>
                        <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-lg shadow-md transition">
                            Generate & Save Invoice
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Live Javascript Calculator -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let rowCount = 1;
            const itemsBody = document.getElementById('itemsBody');
            const addRowBtn = document.getElementById('addRowBtn');

            function calculateTotals() {
                let subtotal = 0;
                document.querySelectorAll('.item-row').forEach(function(row) {
                    const qty = parseFloat(row.querySelector('.item-qty').value) || 0;
                    const price = parseFloat(row.querySelector('.item-price').value) || 0;
                    const rowTotal = qty * price;
                    row.querySelector('.item-row-total').textContent = '{{ __('BDT') }} ' + rowTotal.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                    subtotal += rowTotal;
                });

                document.getElementById('subtotalDisplay').textContent = '{{ __('BDT') }} ' + subtotal.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

                const discountType = document.getElementById('discountType').value;
                const discountVal = parseFloat(document.getElementById('discountValue').value) || 0;
                let discountAmount = 0;
                if (discountType === 'percentage') {
                    discountAmount = subtotal * (discountVal / 100);
                } else {
                    discountAmount = discountVal;
                }
                discountAmount = Math.min(subtotal, discountAmount);
                document.getElementById('discountDisplay').textContent = '-{{ __('BDT') }} ' + discountAmount.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

                const taxable = subtotal - discountAmount;
                const taxPercent = parseFloat(document.getElementById('taxPercentage').value) || 0;
                const taxAmount = taxable * (taxPercent / 100);
                document.getElementById('taxDisplay').textContent = '+{{ __('BDT') }} ' + taxAmount.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

                const grandTotal = taxable + taxAmount;
                document.getElementById('grandTotalDisplay').textContent = '{{ __('BDT') }} ' + grandTotal.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            }

            addRowBtn.addEventListener('click', function() {
                const tr = document.createElement('tr');
                tr.className = 'item-row';
                tr.innerHTML = `
                    <td class="p-2">
                        <input type="text" name="items[${rowCount}][service_category]" value="Software Development"
                            class="w-full text-xs px-2.5 py-1.5 border border-slate-300 rounded focus:ring-1 focus:ring-blue-500 outline-none">
                    </td>
                    <td class="p-2">
                        <input type="text" name="items[${rowCount}][item_description]" required placeholder="Description of service/deliverable"
                            class="w-full text-xs px-2.5 py-1.5 border border-slate-300 rounded focus:ring-1 focus:ring-blue-500 outline-none">
                    </td>
                    <td class="p-2">
                        <input type="number" step="1" min="1" name="items[${rowCount}][quantity]" value="1" required
                            class="item-qty w-full text-xs px-2.5 py-1.5 border border-slate-300 rounded text-center focus:ring-1 focus:ring-blue-500 outline-none">
                    </td>
                    <td class="p-2">
                        <input type="number" step="0.01" min="0" name="items[${rowCount}][unit_price]" value="10000" required
                            class="item-price w-full text-xs px-2.5 py-1.5 border border-slate-300 rounded text-right focus:ring-1 focus:ring-blue-500 outline-none">
                    </td>
                    <td class="p-2 text-right font-bold text-slate-800 item-row-total">
                        {{ __('BDT') }} 10,000.00
                    </td>
                    <td class="p-2 text-center">
                        <button type="button" class="remove-row-btn text-rose-400 hover:text-rose-600 font-bold text-sm">
                            &times;
                        </button>
                    </td>
                `;
                itemsBody.appendChild(tr);
                rowCount++;
                calculateTotals();
            });

            itemsBody.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-row-btn')) {
                    if (document.querySelectorAll('.item-row').length > 1) {
                        e.target.closest('tr').remove();
                        calculateTotals();
                    } else {
                        alert('At least one line item is required.');
                    }
                }
            });

            document.getElementById('invoiceForm').addEventListener('input', calculateTotals);
            calculateTotals();
        });
    </script>
</x-app-layout>
