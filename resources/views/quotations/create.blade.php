<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight flex items-center gap-2">
                    <a href="{{ route('quotations.index') }}" class="text-gray-400 hover:text-gray-600 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    </a>
                    {{ __('New Quotation') }}
                </h2>
                <p class="text-sm text-gray-500 mt-0.5">{{ __('Define service and itemized proposal breakdown and pricing') }}</p>
            </div>
            <a href="{{ route('quotations.index') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900 bg-white px-3.5 py-2 border border-gray-300 rounded-lg shadow-sm">
                {{ __('Back to List') }}
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

            <form method="POST" action="{{ route('quotations.store') }}" class="space-y-6">
                @csrf

                <!-- Header Information -->
                <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm space-y-4">
                    <h3 class="text-base font-bold text-gray-900 border-b pb-3">{{ __('Quotation Information') }}</h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <!-- Select Lead or Customer -->
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Select Client / Lead') }} <span class="text-rose-500">*</span></label>
                            <select name="lead_id" class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">{{ __('Select Lead / Client...') }}</option>
                                @foreach($leads as $l)
                                    <option value="{{ $l->id }}" {{ (old('lead_id') == $l->id || ($preselectedLead && $preselectedLead->id == $l->id)) ? 'selected' : '' }}>
                                        {{ $l->lead_code }} - {{ $l->name }} ({{ $l->phone }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Quotation Date -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Quotation Date') }} <span class="text-rose-500">*</span></label>
                            <input type="date" name="quotation_date" value="{{ old('quotation_date', date('Y-m-d')) }}" required class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <!-- Valid Until -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Valid Until') }}</label>
                            <input type="date" name="valid_until" value="{{ old('valid_until', date('Y-m-d', strtotime('+15 days'))) }}" class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <!-- Status -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Status') }}</label>
                            <select name="status" class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                                @foreach(['Draft', 'Sent', 'Negotiation', 'Accepted', 'Rejected'] as $st)
                                    <option value="{{ $st }}" {{ old('status') == $st ? 'selected' : '' }}>{{ __($st) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Line Items Table -->
                <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm space-y-4">
                    <div class="flex items-center justify-between border-b pb-3">
                        <h3 class="text-base font-bold text-gray-900">{{ __('Itemized Service List') }}</h3>
                        <button type="button" id="add-item-btn" class="px-3 py-1.5 bg-indigo-50 text-indigo-700 hover:bg-indigo-100 font-semibold rounded-lg text-xs transition flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            {{ __('Add Item') }}
                        </button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-left text-xs" id="items-table">
                            <thead class="bg-gray-50 text-gray-600 font-semibold uppercase">
                                <tr>
                                    <th class="py-2.5 px-3 w-1/4">{{ __('Category') }}</th>
                                    <th class="py-2.5 px-3 w-2/5">{{ __('Description / Specification') }}</th>
                                    <th class="py-2.5 px-3 w-24">{{ __('Quantity') }}</th>
                                    <th class="py-2.5 px-3 w-32">{{ __('Unit Price') }} ({{ __('BDT') }})</th>
                                    <th class="py-2.5 px-3 w-32">{{ __('Total') }} ({{ __('BDT') }})</th>
                                    <th class="py-2.5 px-2 w-10"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100" id="items-body">
                                <tr class="item-row">
                                    <td class="p-2">
                                        <select name="items[0][service_category]" class="w-full text-xs border-gray-300 rounded-md">
                                            @foreach($serviceCategories as $cat)
                                                <option value="{{ $cat }}">{{ __($cat) }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="p-2">
                                        <input type="text" name="items[0][description]" required placeholder="{{ __('Service or deliverable description...') }}" class="w-full text-xs border-gray-300 rounded-md">
                                    </td>
                                    <td class="p-2">
                                        <input type="number" step="0.01" name="items[0][quantity]" value="1" min="0.01" required class="qty-input w-full text-xs border-gray-300 rounded-md text-center">
                                    </td>
                                    <td class="p-2">
                                        <input type="number" step="0.01" name="items[0][unit_price]" value="0" min="0" required class="price-input w-full text-xs border-gray-300 rounded-md text-right">
                                    </td>
                                    <td class="p-2">
                                        <input type="text" readonly value="0.00" class="row-total w-full text-xs bg-gray-50 border-gray-200 rounded-md font-bold text-right text-gray-800">
                                    </td>
                                    <td class="p-2 text-center">
                                        <button type="button" class="remove-row-btn text-gray-400 hover:text-rose-600 transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Totals Calculation Box -->
                    <div class="pt-4 border-t flex flex-col sm:flex-row justify-between gap-6">
                        <div class="flex-1 space-y-3">
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Payment Terms') }}</label>
                                <textarea name="payment_terms" rows="2" placeholder="{{ __('e.g. 50% advance, 50% upon final delivery.') }}" class="w-full text-xs border-gray-300 rounded-lg">50% advance, 50% upon project delivery and sign-off.</textarea>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Delivery Terms & Conditions') }}</label>
                                <textarea name="delivery_terms" rows="2" placeholder="{{ __('Timeline and warranty conditions...') }}" class="w-full text-xs border-gray-300 rounded-lg">Delivery within 7-10 business days of agreement. Includes 3 months complimentary maintenance.</textarea>
                            </div>
                        </div>

                        <div class="w-full sm:w-80 bg-gray-50 p-4 rounded-xl border border-gray-200 space-y-2 text-xs">
                            <div class="flex justify-between items-center text-gray-600">
                                <span>{{ __('Subtotal') }}:</span>
                                <span class="font-bold text-gray-900" id="subtotal-display">{{ __('BDT') }} 0.00</span>
                            </div>

                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">{{ __('Discount') }} ({{ __('BDT') }}):</span>
                                <input type="number" step="0.01" id="discount-input" name="discount_amount" value="0" class="w-24 text-xs border-gray-300 rounded p-1 text-right">
                            </div>

                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">{{ __('Tax / VAT') }} ({{ __('BDT') }}):</span>
                                <input type="number" step="0.01" id="tax-input" name="tax_amount" value="0" class="w-24 text-xs border-gray-300 rounded p-1 text-right">
                            </div>

                            <div class="pt-2 border-t border-gray-200 flex justify-between items-center text-sm font-bold text-gray-900">
                                <span>{{ __('Total') }}:</span>
                                <span class="text-indigo-700 text-base" id="grand-total-display">{{ __('BDT') }} 0.00</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex items-center justify-end gap-3">
                    <a href="{{ route('quotations.index') }}" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-lg text-sm transition">
                        {{ __('Cancel') }}
                    </a>
                    <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg text-sm shadow-sm transition flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        {{ __('Create Quotation') }}
                    </button>
                </div>
            </form>

        </div>
    </div>

    <!-- Live Calculation Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let rowIdx = 1;
            const categories = @json($serviceCategories);
            const currencyLabel = '{{ __('BDT') }} ';

            function recalculate() {
                let subtotal = 0;
                document.querySelectorAll('.item-row').forEach(row => {
                    const qty = parseFloat(row.querySelector('.qty-input').value) || 0;
                    const price = parseFloat(row.querySelector('.price-input').value) || 0;
                    const rowTotal = qty * price;
                    row.querySelector('.row-total').value = rowTotal.toFixed(2);
                    subtotal += rowTotal;
                });

                const discount = parseFloat(document.getElementById('discount-input').value) || 0;
                const tax = parseFloat(document.getElementById('tax-input').value) || 0;
                const grandTotal = Math.max(0, subtotal - discount + tax);

                document.getElementById('subtotal-display').innerText = currencyLabel + subtotal.toLocaleString('en-US', { minimumFractionDigits: 2 });
                document.getElementById('grand-total-display').innerText = currencyLabel + grandTotal.toLocaleString('en-US', { minimumFractionDigits: 2 });
            }

            document.getElementById('items-body').addEventListener('input', function (e) {
                if (e.target.classList.contains('qty-input') || e.target.classList.contains('price-input')) {
                    recalculate();
                }
            });

            document.getElementById('discount-input').addEventListener('input', recalculate);
            document.getElementById('tax-input').addEventListener('input', recalculate);

            document.getElementById('add-item-btn').addEventListener('click', function () {
                const tbody = document.getElementById('items-body');
                const tr = document.createElement('tr');
                tr.className = 'item-row';

                let optionsHtml = '';
                categories.forEach(c => { optionsHtml += `<option value="${c}">${c}</option>`; });

                tr.innerHTML = `
                    <td class="p-2">
                        <select name="items[${rowIdx}][service_category]" class="w-full text-xs border-gray-300 rounded-md">
                            ${optionsHtml}
                        </select>
                    </td>
                    <td class="p-2">
                        <input type="text" name="items[${rowIdx}][description]" required placeholder="{{ __('Service or deliverable description...') }}" class="w-full text-xs border-gray-300 rounded-md">
                    </td>
                    <td class="p-2">
                        <input type="number" step="0.01" name="items[${rowIdx}][quantity]" value="1" min="0.01" required class="qty-input w-full text-xs border-gray-300 rounded-md text-center">
                    </td>
                    <td class="p-2">
                        <input type="number" step="0.01" name="items[${rowIdx}][unit_price]" value="0" min="0" required class="price-input w-full text-xs border-gray-300 rounded-md text-right">
                    </td>
                    <td class="p-2">
                        <input type="text" readonly value="0.00" class="row-total w-full text-xs bg-gray-50 border-gray-200 rounded-md font-bold text-right text-gray-800">
                    </td>
                    <td class="p-2 text-center">
                        <button type="button" class="remove-row-btn text-gray-400 hover:text-rose-600 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </td>
                `;

                tbody.appendChild(tr);
                rowIdx++;
            });

            document.getElementById('items-body').addEventListener('click', function (e) {
                const btn = e.target.closest('.remove-row-btn');
                if (btn) {
                    const rows = document.querySelectorAll('.item-row');
                    if (rows.length > 1) {
                        btn.closest('tr').remove();
                        recalculate();
                    } else {
                        alert('{{ __('At least one item is required.') }}');
                    }
                }
            });

            recalculate();
        });
    </script>
</x-app-layout>
