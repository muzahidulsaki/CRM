<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 print:hidden">
            <div class="flex items-center space-x-3">
                <span class="font-mono text-sm font-black px-3 py-1 bg-emerald-100 text-emerald-800 rounded-lg">
                    {{ $payment->payment_no }}
                </span>
                <div>
                    <h2 class="font-bold text-xl text-slate-900 leading-tight">
                        Money Receipt: {{ $payment->customer->name }}
                    </h2>
                    <p class="text-xs text-slate-500">
                        Received on {{ $payment->payment_date->format('d M, Y') }} via {{ $payment->payment_method }}
                    </p>
                </div>
            </div>

            <div class="flex items-center space-x-2">
                <button type="button" onclick="window.print()" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-lg transition shadow-xs flex items-center">
                    <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    Print Money Receipt
                </button>
                @if($payment->invoice)
                    <a href="{{ route('invoices.show', $payment->invoice) }}" class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-lg transition">
                        View Invoice
                    </a>
                @endif
                <a href="{{ route('client-payments.index') }}" class="px-3.5 py-2 bg-slate-800 hover:bg-slate-900 text-white text-xs font-bold rounded-lg transition">
                    &larr; All Payments
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            <!-- Official Print-Ready Money Receipt -->
            <div class="bg-white rounded-2xl border-2 border-emerald-500 shadow-md p-8 sm:p-10 relative overflow-hidden print:border print:shadow-none print:p-6">
                
                <!-- Watermark Background (Soft 'PAID') -->
                <div class="absolute right-12 top-20 text-emerald-100 font-black text-8xl pointer-events-none select-none -rotate-12 opacity-50">
                    PAID
                </div>

                <!-- Company Header -->
                <div class="flex flex-col sm:flex-row justify-between items-start pb-6 border-b-2 border-slate-200">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 rounded-xl bg-emerald-600 text-white flex items-center justify-center font-black text-2xl shadow">
                            PS
                        </div>
                        <div>
                            <h1 class="text-2xl font-black text-slate-900 tracking-tight">PROJUKTI SHEBA</h1>
                            <p class="text-xs text-slate-500 font-medium">Software, Web & Digital Marketing Agency</p>
                            <p class="text-[11px] text-slate-400">Dhaka, Bangladesh &bull; Hotline: +880 1700-000000</p>
                        </div>
                    </div>

                    <div class="mt-4 sm:mt-0 text-left sm:text-right">
                        <span class="inline-block px-3 py-1 bg-emerald-100 text-emerald-900 font-mono text-xs font-black rounded uppercase tracking-wider mb-1">
                            OFFICIAL MONEY RECEIPT
                        </span>
                        <div class="text-lg font-mono font-black text-emerald-700">{{ $payment->payment_no }}</div>
                        <p class="text-xs text-slate-500">Date: <strong>{{ $payment->payment_date->format('d M, Y') }}</strong></p>
                    </div>
                </div>

                <!-- Receipt Body Content -->
                <div class="py-6 space-y-4 text-xs sm:text-sm text-slate-700">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between py-2 border-b border-slate-100">
                        <span class="text-slate-500 uppercase tracking-wider text-xs font-bold">Received With Thanks From:</span>
                        <span class="font-bold text-slate-900 text-sm sm:text-base">
                            {{ $payment->customer->name }} 
                            @if($payment->customer->company_name)
                                ({{ $payment->customer->company_name }})
                            @endif
                        </span>
                    </div>

                    <div class="flex flex-col sm:flex-row sm:items-center justify-between py-2 border-b border-slate-100">
                        <span class="text-slate-500 uppercase tracking-wider text-xs font-bold">The Sum of (Amount):</span>
                        <div class="text-right">
                            <span class="text-xl sm:text-2xl font-black text-emerald-600">
                                ৳{{ number_format($payment->amount, 2) }}
                            </span>
                            <span class="text-xs text-slate-400 block">BDT (Bangladeshi Taka)</span>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row sm:items-center justify-between py-2 border-b border-slate-100">
                        <span class="text-slate-500 uppercase tracking-wider text-xs font-bold">Paid By / Method:</span>
                        <span class="font-bold text-slate-800">
                            {{ $payment->payment_method }}
                            @if($payment->transaction_reference)
                                <span class="font-mono text-slate-500 text-xs font-normal">(Ref: {{ $payment->transaction_reference }})</span>
                            @endif
                        </span>
                    </div>

                    @if($payment->invoice || $payment->order)
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between py-2 border-b border-slate-100">
                            <span class="text-slate-500 uppercase tracking-wider text-xs font-bold">On Account Of / Bill Ref:</span>
                            <span class="font-bold text-slate-800">
                                @if($payment->invoice)
                                    Invoice #{{ $payment->invoice->invoice_no }} (Remaining Due: ৳{{ number_format($payment->invoice->due_amount, 2) }})
                                @elseif($payment->order)
                                    Order #{{ $payment->order->order_code }}
                                @endif
                            </span>
                        </div>
                    @endif

                    @if($payment->notes)
                        <div class="py-2 border-b border-slate-100">
                            <span class="text-slate-500 uppercase tracking-wider text-xs font-bold block mb-1">Remarks / Description:</span>
                            <p class="text-slate-700 font-medium italic">{{ $payment->notes }}</p>
                        </div>
                    @endif
                </div>

                <!-- Signatures -->
                <div class="mt-14 pt-6 border-t border-slate-200 grid grid-cols-2 gap-8 text-center text-xs">
                    <div>
                        <div class="border-b border-slate-300 w-44 mx-auto pb-8 mb-2"></div>
                        <p class="font-bold text-slate-800">Customer Signature</p>
                        <p class="text-[11px] text-slate-400">Paid by Client</p>
                    </div>

                    <div>
                        <div class="border-b border-slate-300 w-44 mx-auto pb-8 mb-2"></div>
                        <p class="font-bold text-slate-800">Authorized Officer</p>
                        <p class="text-[11px] text-slate-400">Projukti Sheba (Received by: {{ $payment->receivedBy?->name ?? 'Accounts' }})</p>
                    </div>
                </div>

                <!-- Security Note -->
                <div class="mt-8 text-center text-[10px] text-slate-400 border-t border-slate-100 pt-3">
                    This is an electronically generated official receipt. For any billing queries, contact accounts@projuktisheba.com.
                </div>

            </div>

        </div>
    </div>
</x-app-layout>
