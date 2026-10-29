<x-portal-layout>
    <x-slot name="title">{{ __('My Invoices') }}</x-slot>

    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight">{{ __('Billing Statements & Invoices') }}</h1>
                <p class="text-xs text-slate-500 mt-0.5">{{ __('View billing history, outstanding balances and print official invoices') }}</p>
            </div>
        </div>

        <!-- 3 Summary Pills -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-2xs">
                <span class="text-xs font-bold uppercase text-slate-400 block">{{ __('Total Billed') }}</span>
                <div class="text-2xl font-black text-slate-900 mt-1">
                    {{ __('BDT') }} {{ number_format($summary['total_billed'], 2) }}
                </div>
            </div>
            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-2xs">
                <span class="text-xs font-bold uppercase text-emerald-600 block">{{ __('Total Paid') }}</span>
                <div class="text-2xl font-black text-emerald-700 mt-1">
                    {{ __('BDT') }} {{ number_format($summary['total_paid'], 2) }}
                </div>
            </div>
            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-2xs">
                <span class="text-xs font-bold uppercase text-rose-500 block">{{ __('Remaining Due Balance') }}</span>
                <div class="text-2xl font-black text-rose-600 mt-1">
                    {{ __('BDT') }} {{ number_format($summary['total_due'], 2) }}
                </div>
            </div>
        </div>

        <!-- Invoices Table -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-2xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="bg-slate-50/75 border-b border-slate-100 text-slate-500 font-bold uppercase tracking-wider text-[11px]">
                            <th class="py-3.5 px-4">{{ __('Invoice No') }}</th>
                            <th class="py-3.5 px-4">{{ __('Date') }}</th>
                            <th class="py-3.5 px-4">{{ __('Due Date') }}</th>
                            <th class="py-3.5 px-4 text-right">{{ __('Total Amount') }}</th>
                            <th class="py-3.5 px-4 text-right">{{ __('Paid') }}</th>
                            <th class="py-3.5 px-4 text-right">{{ __('Due Balance') }}</th>
                            <th class="py-3.5 px-4 text-center">{{ __('Status') }}</th>
                            <th class="py-3.5 px-4 text-right">{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($invoices as $invoice)
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="py-4 px-4 font-mono font-bold text-indigo-600">
                                    <a href="{{ route('portal.invoices.show', $invoice) }}" class="hover:underline">
                                        {{ $invoice->invoice_no }}
                                    </a>
                                </td>
                                <td class="py-4 px-4 text-slate-600 font-medium">
                                    {{ $invoice->issue_date ? $invoice->issue_date->format('d M, Y') : 'N/A' }}
                                </td>
                                <td class="py-4 px-4 text-slate-600">
                                    {{ $invoice->due_date ? $invoice->due_date->format('d M, Y') : 'N/A' }}
                                </td>
                                <td class="py-4 px-4 font-bold text-slate-900 text-right">
                                    {{ __('BDT') }} {{ number_format($invoice->total_amount, 2) }}
                                </td>
                                <td class="py-4 px-4 text-emerald-600 font-semibold text-right">
                                    {{ __('BDT') }} {{ number_format($invoice->paid_amount, 2) }}
                                </td>
                                <td class="py-4 px-4 text-right">
                                    @if($invoice->due_amount > 0)
                                        <span class="font-bold text-rose-600">{{ __('BDT') }} {{ number_format($invoice->due_amount, 2) }}</span>
                                    @else
                                        <span class="text-slate-400 font-semibold">{{ __('BDT 0.00') }}</span>
                                    @endif
                                </td>
                                <td class="py-4 px-4 text-center">
                                    <span class="px-2.5 py-1 rounded-full text-[11px] font-bold 
                                        {{ $invoice->status === 'Paid' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : ($invoice->status === 'Partially Paid' ? 'bg-amber-50 text-amber-700 border border-amber-200' : 'bg-rose-50 text-rose-700 border border-rose-200') }}">
                                        {{ $invoice->status }}
                                    </span>
                                </td>
                                <td class="py-4 px-4 text-right">
                                    <a href="{{ route('portal.invoices.show', $invoice) }}" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-indigo-600 hover:text-white text-slate-700 text-xs font-bold transition">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        <span>{{ __('View & Print') }}</span>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="py-10 text-center text-xs text-slate-400">
                                    {{ __('No invoices generated for your account yet.') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-slate-100">
                {{ $invoices->links() }}
            </div>
        </div>
    </div>
</x-portal-layout>

