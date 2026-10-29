<x-portal-layout>
    <x-slot name="title">{{ __('Invoice') }} {{ $invoice->invoice_no }}</x-slot>

    <div class="space-y-6">
        <!-- Top Toolbar (Back & Print Button) -->
        <div class="flex items-center justify-between no-print">
            <a href="{{ route('portal.invoices.index') }}" class="inline-flex items-center gap-2 text-xs font-bold text-slate-600 hover:text-slate-900 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                <span>{{ __('Back to Invoices') }}</span>
            </a>

            <div class="flex items-center gap-2">
                <button onclick="window.print()" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow-xs transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    <span>{{ __('Print / Save PDF') }}</span>
                </button>
            </div>
        </div>

        <!-- Official Printable Invoice Sheet -->
        <div class="bg-white rounded-3xl border border-slate-200/80 p-8 sm:p-12 shadow-sm max-w-4xl mx-auto" id="printable-invoice">
            
            <!-- Header: Company & Invoice Info -->
            <div class="flex flex-col sm:flex-row justify-between items-start gap-6 border-b border-slate-200 pb-8">
                <div>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-blue-600 via-indigo-600 to-violet-500 flex items-center justify-center text-white font-black text-xl shadow-md">
                            PS
                        </div>
                        <div>
                            <h2 class="text-xl font-black text-slate-900 tracking-tight leading-none">Projukti Sheba</h2>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">Software & Creative ERP</p>
                        </div>
                    </div>
                    <div class="text-xs text-slate-500 mt-4 space-y-0.5">
                        <p>Dhaka, Bangladesh</p>
                        <p>Email: billing@projuktisheba.com | Phone: +880 1700-000000</p>
                        <p>Website: crm.softps.site</p>
                    </div>
                </div>

                <div class="sm:text-right">
                    <span class="text-3xl font-black tracking-tight text-slate-900 uppercase block">{{ __('INVOICE') }}</span>
                    <span class="font-mono text-sm font-bold text-indigo-600 mt-1 block">{{ $invoice->invoice_no }}</span>
                    <div class="mt-3 text-xs space-y-1 text-slate-600">
                        <div>{{ __('Issue Date') }}: <strong class="text-slate-900">{{ $invoice->issue_date ? $invoice->issue_date->format('d M, Y') : 'N/A' }}</strong></div>
                        <div>{{ __('Due Date') }}: <strong class="text-slate-900">{{ $invoice->due_date ? $invoice->due_date->format('d M, Y') : 'N/A' }}</strong></div>
                        <div class="pt-1">
                            <span class="px-3 py-1 rounded-full text-xs font-bold inline-block
                                {{ $invoice->status === 'Paid' ? 'bg-emerald-100 text-emerald-800' : ($invoice->status === 'Partially Paid' ? 'bg-amber-100 text-amber-800' : 'bg-rose-100 text-rose-800') }}">
                                {{ $invoice->status }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Billed To Section -->
            <div class="py-6 border-b border-slate-200 flex flex-col sm:flex-row justify-between gap-6 text-xs">
                <div>
                    <span class="text-slate-400 uppercase font-bold text-[10px] block">{{ __('Billed To (Client):') }}</span>
                    <div class="font-bold text-slate-900 text-sm mt-1">{{ $customer->name }}</div>
                    @if($customer->company_name)
                        <div class="text-slate-600 font-semibold">{{ $customer->company_name }}</div>
                    @endif
                    <div class="text-slate-500 mt-1">
                        <p>{{ $customer->phone }}</p>
                        <p>{{ $customer->email }}</p>
                        @if($customer->address)
                            <p>{{ $customer->address }}</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Line Items Table -->
            <div class="py-6">
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="border-b-2 border-slate-800 text-slate-800 uppercase font-bold text-[11px]">
                            <th class="py-3 px-2">#</th>
                            <th class="py-3 px-2">{{ __('Item Description') }}</th>
                            <th class="py-3 px-2 text-center">{{ __('Qty') }}</th>
                            <th class="py-3 px-2 text-right">{{ __('Unit Price') }}</th>
                            <th class="py-3 px-2 text-right">{{ __('Total Amount') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($invoice->items ?? [] as $idx => $item)
                            <tr>
                                <td class="py-3 px-2 text-slate-400">{{ $idx + 1 }}</td>
                                <td class="py-3 px-2 font-semibold text-slate-900">{{ $item->description ?? $item->item_name }}</td>
                                <td class="py-3 px-2 text-center text-slate-600">{{ $item->quantity ?? 1 }}</td>
                                <td class="py-3 px-2 text-right text-slate-700">{{ __('BDT') }} {{ number_format($item->unit_price ?? $item->rate, 2) }}</td>
                                <td class="py-3 px-2 text-right font-bold text-slate-900">{{ __('BDT') }} {{ number_format($item->total ?? ($item->quantity * $item->unit_price), 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td class="py-3 px-2 text-slate-400">1</td>
                                <td class="py-3 px-2 font-semibold text-slate-900">{{ __('Contracted Digital/IT Services as agreed') }}</td>
                                <td class="py-3 px-2 text-center text-slate-600">1</td>
                                <td class="py-3 px-2 text-right text-slate-700">{{ __('BDT') }} {{ number_format($invoice->subtotal, 2) }}</td>
                                <td class="py-3 px-2 text-right font-bold text-slate-900">{{ __('BDT') }} {{ number_format($invoice->subtotal, 2) }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Total Calculations & Payment Balance -->
            <div class="pt-4 border-t border-slate-200 flex flex-col sm:flex-row justify-between items-start gap-6">
                <!-- Notes / Payment Info -->
                <div class="text-xs text-slate-500 max-w-sm space-y-1">
                    <strong class="text-slate-800 block font-semibold">{{ __('Payment Method & Instructions:') }}</strong>
                    <p>{{ __('Please send payments to Projukti Sheba official accounts (Bank / bKash / Nagad) and reference this invoice number.') }}</p>
                    @if($invoice->notes)
                        <p class="pt-2 text-slate-600"><strong>{{ __('Note') }}:</strong> {{ $invoice->notes }}</p>
                    @endif
                </div>

                <!-- Financial Balance Column -->
                <div class="w-full sm:w-64 space-y-2 text-xs">
                    <div class="flex justify-between py-1 text-slate-600">
                        <span>{{ __('Subtotal') }}:</span>
                        <strong class="text-slate-900">{{ __('BDT') }} {{ number_format($invoice->subtotal, 2) }}</strong>
                    </div>

                    @if($invoice->discount_amount > 0)
                        <div class="flex justify-between py-1 text-emerald-600 font-semibold">
                            <span>{{ __('Discount') }}:</span>
                            <span>- {{ __('BDT') }} {{ number_format($invoice->discount_amount, 2) }}</span>
                        </div>
                    @endif

                    @if($invoice->tax_amount > 0)
                        <div class="flex justify-between py-1 text-slate-600">
                            <span>{{ __('Tax / VAT') }}:</span>
                            <span>+ {{ __('BDT') }} {{ number_format($invoice->tax_amount, 2) }}</span>
                        </div>
                    @endif

                    <div class="flex justify-between py-2 border-t-2 border-slate-800 text-sm font-black text-slate-900">
                        <span>{{ __('Total') }}:</span>
                        <span>{{ __('BDT') }} {{ number_format($invoice->total_amount, 2) }}</span>
                    </div>

                    <div class="flex justify-between py-1 text-emerald-700 font-bold">
                        <span>{{ __('Paid Amount') }}:</span>
                        <span>{{ __('BDT') }} {{ number_format($invoice->paid_amount, 2) }}</span>
                    </div>

                    <div class="flex justify-between py-2 bg-slate-50 p-2 rounded-lg font-black {{ $invoice->due_amount > 0 ? 'text-rose-600' : 'text-emerald-700' }}">
                        <span>{{ __('Balance Due') }}:</span>
                        <span>{{ __('BDT') }} {{ number_format($invoice->due_amount, 2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Footer Sign Off -->
            <div class="mt-12 pt-8 border-t border-slate-200 text-center text-xs text-slate-400">
                <p>{{ __('Thank you for choosing Projukti Sheba for your business growth!') }}</p>
                <p class="text-[10px] mt-1">{{ __('This is a computer-generated invoice and valid without a physical signature.') }}</p>
            </div>

        </div>
    </div>
</x-portal-layout>

