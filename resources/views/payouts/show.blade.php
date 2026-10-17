<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between no-print">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                    {{ __('Payment Voucher & Receipt') }}: {{ $payout->voucher_no }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">{{ __('Official remuneration certificate and payment receipt') }}</p>
            </div>
            <div class="flex items-center gap-2">
                <button type="button" onclick="window.print()" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm font-semibold hover:bg-indigo-700 shadow-sm transition">
                    <svg class="w-4 h-4 me-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    {{ __('Print Voucher') }}
                </button>
                <a href="{{ route('payouts.index') }}" class="inline-flex items-center px-3.5 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm font-semibold hover:bg-gray-200 transition">
                    {{ __('Back to List') }}
                </a>
            </div>
        </div>
    </x-slot>

    <style>
        @media print {
            body { background: white !important; }
            header, nav, .no-print { display: none !important; }
            .print-container { box-shadow: none !important; border: none !important; padding: 0 !important; width: 100% !important; max-width: 100% !important; }
        }
    </style>

    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="print-container bg-white rounded-3xl border border-gray-200 shadow-sm p-8 sm:p-12 space-y-8">

                <!-- Company Header & Voucher Title -->
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center pb-6 border-b-2 border-gray-900 gap-4">
                    <div>
                        <h1 class="text-2xl font-black text-gray-900 tracking-tight">{{ __('Projukti Sheba') }}</h1>
                        <p class="text-xs text-gray-500 mt-0.5">{{ __('IT Solutions, Software, Web Development & Creative Media') }}</p>
                        <p class="text-xs text-gray-400">Dhaka, Bangladesh • contact@projuktisheba.com</p>
                    </div>

                    <div class="sm:text-right">
                        <span class="inline-block px-3 py-1 bg-emerald-100 text-emerald-800 text-xs font-bold rounded-lg uppercase tracking-wider mb-1">
                            PAYMENT VOUCHER / MONEY RECEIPT
                        </span>
                        <div class="text-lg font-black text-gray-900 font-mono">{{ $payout->voucher_no }}</div>
                        <div class="text-xs text-gray-500">{{ __('Date') }}: {{ $payout->payment_date->format('d F, Y') }}</div>
                    </div>
                </div>

                <!-- Employee & Payment Meta Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 bg-gray-50 p-5 rounded-2xl border border-gray-200 text-xs">
                    <div>
                        <span class="text-gray-400 font-bold uppercase tracking-wider block mb-1">{{ __('Recipient / Staff Details') }}:</span>
                        <div class="text-base font-bold text-gray-900">{{ $payout->employee->user->name }}</div>
                        <div class="text-gray-600 mt-0.5">{{ $payout->employee->designation }} • {{ $payout->employee->department }}</div>
                        <div class="text-gray-500 mt-0.5 font-mono">{{ __('Employee Code') }}: {{ $payout->employee->employee_code }}</div>
                        <div class="text-gray-500">{{ $payout->employee->user->email }} | {{ $payout->employee->user->phone ?? '-' }}</div>
                    </div>

                    <div class="sm:text-right space-y-1">
                        <span class="text-gray-400 font-bold uppercase tracking-wider block mb-1">{{ __('Payment Details') }}:</span>
                        <div><strong>{{ __('Payment Category') }}:</strong> <span class="capitalize">{{ str_replace('_', ' ', $payout->payout_type) }}</span></div>
                        <div><strong>{{ __('Payment Method') }}:</strong> {{ $payout->payment_method }}</div>
                        @if($payout->account_details)
                            <div><strong>{{ __('Account Details') }}:</strong> {{ $payout->account_details }}</div>
                        @endif
                        @if($payout->transaction_reference)
                            <div class="font-mono"><strong>TrxID / {{ __('Reference') }}:</strong> {{ $payout->transaction_reference }}</div>
                        @endif
                        <div><strong>{{ __('Prepared By') }}:</strong> {{ $payout->creator->name }}</div>
                    </div>
                </div>

                <!-- Financial Breakdown Table -->
                <div>
                    <h3 class="font-bold text-sm text-gray-900 uppercase tracking-wider mb-3">{{ __('Payment Breakdown') }}</h3>
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-100 text-gray-700 font-semibold text-xs uppercase">
                            <tr>
                                <th class="py-3 px-4 text-left">{{ __('Description') }}</th>
                                <th class="py-3 px-4 text-right">{{ __('Amount') }} ({{ __('BDT') }})</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-xs">
                            <tr>
                                <td class="py-3 px-4 font-medium text-gray-800">
                                    {{ __('Base Bill / Salary') }}
                                    @if($payout->salary_month)
                                        <span class="text-gray-400 block text-[11px]">{{ __('Month') }}: {{ $payout->salary_month }}</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-right font-bold text-gray-900">
                                    {{ __('BDT') }} {{ number_format($payout->base_amount, 2) }}
                                </td>
                            </tr>

                            @if($payout->bonus_amount > 0)
                                <tr class="bg-emerald-50/30 text-emerald-900">
                                    <td class="py-3 px-4 font-medium">{{ __('Additional Performance Bonus / Incentive (+)') }}</td>
                                    <td class="py-3 px-4 text-right font-bold">
                                        + {{ __('BDT') }} {{ number_format($payout->bonus_amount, 2) }}
                                    </td>
                                </tr>
                            @endif

                            @if($payout->deduction_amount > 0)
                                <tr class="bg-rose-50/30 text-rose-900">
                                    <td class="py-3 px-4 font-medium">{{ __('Advance Settlement / Deductions (-)') }}</td>
                                    <td class="py-3 px-4 text-right font-bold">
                                        - {{ __('BDT') }} {{ number_format($payout->deduction_amount, 2) }}
                                    </td>
                                </tr>
                            @endif

                            <tr class="bg-gray-50 border-t-2 border-gray-900 text-sm font-black text-gray-900">
                                <td class="py-4 px-4 text-base">{{ __('Net Paid Amount') }}:</td>
                                <td class="py-4 px-4 text-right text-lg text-emerald-700 font-black">
                                    {{ __('BDT') }} {{ number_format($payout->paid_amount, 2) }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Linked Video Billings List if applicable -->
                @if($linkedBillings->isNotEmpty())
                    <div class="p-4 bg-gray-50 rounded-2xl border border-gray-200 space-y-2">
                        <span class="text-xs font-bold text-gray-700 uppercase tracking-wider block">{{ __('Settled Video Billings in this Voucher') }}:</span>
                        <div class="space-y-1.5 text-xs text-gray-700">
                            @foreach($linkedBillings as $lb)
                                <div class="flex items-center justify-between border-b border-gray-200/60 pb-1">
                                    <span>• <strong>{{ $lb->billing_code }}</strong>: {{ $lb->video_title }} ({{ $lb->duration_minutes }}m {{ $lb->duration_seconds }}s)</span>
                                    <span class="font-bold text-gray-900">{{ __('BDT') }} {{ number_format($lb->total_payable, 2) }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if($payout->notes)
                    <div class="p-3 bg-gray-50 rounded-xl text-xs text-gray-600">
                        <strong>{{ __('Notes') }}:</strong> {{ $payout->notes }}
                    </div>
                @endif

                <!-- Signature Section -->
                <div class="pt-16 grid grid-cols-2 gap-8 text-center text-xs">
                    <div>
                        <div class="border-t border-gray-400 w-48 mx-auto mb-1"></div>
                        <span class="font-bold text-gray-800">{{ __('Staff Signature (Receiver)') }}</span>
                        <div class="text-[11px] text-gray-400">{{ __('Acknowledged receipt of payment') }}</div>
                    </div>

                    <div>
                        <div class="border-t border-gray-400 w-48 mx-auto mb-1"></div>
                        <span class="font-bold text-gray-800">{{ __('Authorized Signature (Accounts / Management)') }}</span>
                        <div class="text-[11px] text-gray-400">{{ __('Projukti Sheba') }}</div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>
