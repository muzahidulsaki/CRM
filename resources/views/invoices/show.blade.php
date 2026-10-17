<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 print:hidden">
            <div class="flex items-center space-x-3">
                <span class="font-mono text-sm font-black px-3 py-1 bg-slate-200 text-slate-800 rounded-lg">
                    {{ $invoice->invoice_no }}
                </span>
                <div>
                    <h2 class="font-bold text-xl text-slate-900 leading-tight">
                        Client Invoice: {{ $invoice->customer->name }}
                    </h2>
                    <p class="text-xs text-slate-500">
                        Status: <span class="font-semibold">{{ $invoice->status }}</span> &bull; 
                        Issued on {{ $invoice->issue_date->format('d M, Y') }}
                    </p>
                </div>
            </div>

            <div class="flex items-center space-x-2">
                @if($invoice->due_amount > 0)
                    <a href="{{ route('client-payments.create', ['invoice_id' => $invoice->id]) }}" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-lg transition shadow-xs flex items-center">
                        <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        Record Payment
                    </a>
                @endif
                <button type="button" onclick="window.print()" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-lg transition shadow-xs flex items-center">
                    <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    Print Invoice
                </button>
                <a href="{{ route('invoices.edit', $invoice) }}" class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-lg transition">
                    Edit
                </a>
                <a href="{{ route('invoices.index') }}" class="px-3.5 py-2 bg-slate-800 hover:bg-slate-900 text-white text-xs font-bold rounded-lg transition">
                    &larr; Invoices
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-6 p-4 bg-emerald-50 border border-emerald-300 rounded-xl text-emerald-800 text-sm flex items-center print:hidden">
                    <svg class="w-5 h-5 mr-3 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <!-- Print-Ready Official Invoice Card -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-8 sm:p-12 print:border-none print:shadow-none print:p-0">
                
                <!-- Company Header & Invoice Badge -->
                <div class="flex flex-col sm:flex-row justify-between items-start pb-8 border-b border-slate-200">
                    <div>
                        <div class="flex items-center space-x-3 mb-2">
                            <div class="w-12 h-12 rounded-xl bg-blue-600 text-white flex items-center justify-center font-black text-2xl shadow">
                                PS
                            </div>
                            <div>
                                <h1 class="text-2xl font-black text-slate-900 tracking-tight">PROJUKTI SHEBA</h1>
                                <p class="text-xs font-medium text-slate-500">Software, Web & Creative Technology Solutions</p>
                            </div>
                        </div>
                        <p class="text-xs text-slate-600">Dhaka, Bangladesh &bull; Phone: +880 1700-000000</p>
                        <p class="text-xs text-slate-600">Email: billing@projuktisheba.com &bull; Web: www.projuktisheba.com</p>
                    </div>

                    <div class="mt-6 sm:mt-0 text-left sm:text-right">
                        <span class="inline-block px-3 py-1 bg-slate-100 text-slate-800 font-mono text-sm font-black rounded uppercase tracking-wider mb-2">
                            INVOICE
                        </span>
                        <div class="text-xl font-mono font-black text-blue-600">{{ $invoice->invoice_no }}</div>
                        <p class="text-xs text-slate-500 mt-1">Issue Date: <strong>{{ $invoice->issue_date->format('d M, Y') }}</strong></p>
                        <p class="text-xs text-slate-500">Payment Due: <strong class="text-rose-600">{{ $invoice->due_date->format('d M, Y') }}</strong></p>
                    </div>
                </div>

                <!-- Bill To & Order/Project Metadata -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 py-6 border-b border-slate-200 text-xs">
                    <div>
                        <span class="font-bold uppercase tracking-wider text-slate-400 block mb-1">Billed To:</span>
                        <h3 class="text-base font-bold text-slate-900">{{ $invoice->customer->name }}</h3>
                        @if($invoice->customer->company_name)
                            <p class="text-slate-700 font-medium">{{ $invoice->customer->company_name }}</p>
                        @endif
                        <p class="text-slate-600 mt-1">Phone: {{ $invoice->customer->phone }}</p>
                        @if($invoice->customer->email)
                            <p class="text-slate-600">Email: {{ $invoice->customer->email }}</p>
                        @endif
                        @if($invoice->customer->address)
                            <p class="text-slate-600">{{ $invoice->customer->address }}</p>
                        @endif
                    </div>

                    <div class="sm:text-right space-y-1">
                        <span class="font-bold uppercase tracking-wider text-slate-400 block mb-1">Invoice Details:</span>
                        @if($invoice->order)
                            <p class="text-slate-600">Related Order: <strong class="text-slate-800">{{ $invoice->order->order_code }}</strong></p>
                        @endif
                        @if($invoice->project)
                            <p class="text-slate-600">Project: <strong class="text-slate-800">{{ $invoice->project->name }} ({{ $invoice->project->project_code }})</strong></p>
                        @endif
                        <p class="text-slate-600">Payment Status: 
                            <span class="px-2 py-0.5 rounded font-bold border {{ $invoice->status_badge_color }}">
                                {{ $invoice->status }}
                            </span>
                        </p>
                    </div>
                </div>

                <!-- Line Items Table -->
                <div class="py-6">
                    <table class="min-w-full text-left text-xs">
                        <thead>
                            <tr class="border-b-2 border-slate-200 text-slate-500 font-bold uppercase tracking-wider">
                                <th class="py-3 px-2 w-12 text-center">#</th>
                                <th class="py-3 px-2">Item Description & Scope</th>
                                <th class="py-3 px-2 w-20 text-center">Qty</th>
                                <th class="py-3 px-2 w-28 text-right">Rate</th>
                                <th class="py-3 px-2 w-28 text-right">Amount</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700 font-medium">
                            @foreach($invoice->items as $index => $item)
                                <tr>
                                    <td class="py-3 px-2 text-center text-slate-400">{{ $index + 1 }}</td>
                                    <td class="py-3 px-2">
                                        <div class="font-bold text-slate-900">{{ $item->item_description }}</div>
                                        @if($item->service_category)
                                            <div class="text-[11px] text-slate-400">{{ $item->service_category }}</div>
                                        @endif
                                    </td>
                                    <td class="py-3 px-2 text-center">{{ $item->quantity }}</td>
                                    <td class="py-3 px-2 text-right">{{ __('BDT') }} {{ number_format($item->unit_price, 2) }}</td>
                                    <td class="py-3 px-2 text-right font-bold text-slate-900">{{ __('BDT') }} {{ number_format($item->total_price, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Subtotals, Discount, Tax & Due Balance Calculation -->
                <div class="pt-4 border-t-2 border-slate-200 flex flex-col sm:flex-row justify-between items-start gap-6 text-xs">
                    <div class="w-full sm:w-1/2 space-y-3">
                        @if($invoice->notes)
                            <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
                                <span class="font-bold uppercase tracking-wider text-slate-400 block mb-1">Notes:</span>
                                <p class="text-slate-700 leading-relaxed">{{ $invoice->notes }}</p>
                            </div>
                        @endif

                        @if($invoice->terms)
                            <div class="text-[11px] text-slate-500">
                                <span class="font-bold">Terms:</span> {{ $invoice->terms }}
                            </div>
                        @endif
                    </div>

                    <div class="w-full sm:w-2/5 space-y-2 text-slate-600">
                        <div class="flex justify-between">
                            <span>Subtotal:</span>
                            <span class="font-bold text-slate-900">{{ __('BDT') }} {{ number_format($invoice->subtotal, 2) }}</span>
                        </div>

                        @if($invoice->discount_amount > 0)
                            <div class="flex justify-between text-rose-600">
                                <span>Discount ({{ $invoice->discount_type === 'percentage' ? $invoice->discount_value.'%' : 'Fixed' }}):</span>
                                <span class="font-semibold">-{{ __('BDT') }} {{ number_format($invoice->discount_amount, 2) }}</span>
                            </div>
                        @endif

                        @if($invoice->tax_amount > 0)
                            <div class="flex justify-between text-slate-600">
                                <span>VAT / Tax ({{ $invoice->tax_percentage }}%):</span>
                                <span class="font-semibold">+{{ __('BDT') }} {{ number_format($invoice->tax_amount, 2) }}</span>
                            </div>
                        @endif

                        <div class="flex justify-between pt-2 border-t border-slate-200 font-black text-sm text-slate-900">
                            <span>Total Invoiced:</span>
                            <span class="text-blue-600">{{ __('BDT') }} {{ number_format($invoice->total_amount, 2) }}</span>
                        </div>

                        <div class="flex justify-between text-emerald-600 font-bold">
                            <span>Amount Paid:</span>
                            <span>{{ __('BDT') }} {{ number_format($invoice->paid_amount, 2) }}</span>
                        </div>

                        <div class="flex justify-between pt-2 border-t border-slate-300 font-black text-sm {{ $invoice->due_amount > 0 ? 'text-amber-600' : 'text-slate-400' }}">
                            <span class="uppercase tracking-wider">Balance Due:</span>
                            <span>{{ __('BDT') }} {{ number_format($invoice->due_amount, 2) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Payment History & Money Receipts -->
                @if($invoice->payments->count() > 0)
                    <div class="mt-8 pt-6 border-t border-slate-200">
                        <h4 class="font-bold text-xs uppercase tracking-wider text-slate-500 mb-3">
                            Payment Receipts for this Invoice
                        </h4>
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-left text-xs divide-y divide-slate-100">
                                <thead class="bg-slate-50 text-slate-400 font-semibold uppercase">
                                    <tr>
                                        <th class="py-2 px-2">Receipt #</th>
                                        <th class="py-2 px-2">Date</th>
                                        <th class="py-2 px-2">Method</th>
                                        <th class="py-2 px-2">Reference</th>
                                        <th class="py-2 px-2 text-right">Amount</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 text-slate-700">
                                    @foreach($invoice->payments as $p)
                                        <tr>
                                            <td class="py-2 px-2 font-mono font-bold text-blue-600">
                                                <a href="{{ route('client-payments.show', $p) }}" class="hover:underline">{{ $p->payment_no }}</a>
                                            </td>
                                            <td class="py-2 px-2 text-slate-500">{{ $p->payment_date->format('d M, Y') }}</td>
                                            <td class="py-2 px-2">{{ $p->payment_method }}</td>
                                            <td class="py-2 px-2 text-slate-400 font-mono">{{ $p->transaction_reference ?? 'N/A' }}</td>
                                            <td class="py-2 px-2 text-right font-bold text-emerald-600">{{ __('BDT') }} {{ number_format($p->amount, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

                <!-- Official Signatures -->
                <div class="mt-16 pt-8 border-t border-slate-200 grid grid-cols-2 gap-8 text-center text-xs">
                    <div>
                        <div class="border-b border-slate-300 w-48 mx-auto pb-8 mb-2"></div>
                        <p class="font-bold text-slate-800">Authorized Signature</p>
                        <p class="text-[11px] text-slate-400">Projukti Sheba Accounts Department</p>
                    </div>

                    <div>
                        <div class="border-b border-slate-300 w-48 mx-auto pb-8 mb-2"></div>
                        <p class="font-bold text-slate-800">Customer Acceptance</p>
                        <p class="text-[11px] text-slate-400">Received & Acknowledged</p>
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>
