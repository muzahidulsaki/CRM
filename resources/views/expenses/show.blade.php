<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center space-x-3">
                <span class="font-mono text-sm font-black px-3 py-1 bg-rose-100 text-rose-800 rounded-lg">
                    {{ $expense->expense_no }}
                </span>
                <div>
                    <h2 class="font-bold text-xl text-slate-900 leading-tight">
                        {{ $expense->title }}
                    </h2>
                    <p class="text-xs text-slate-500">
                        Category: <span class="font-semibold">{{ $expense->category }}</span> &bull; 
                        Logged on {{ $expense->expense_date->format('d M, Y') }}
                    </p>
                </div>
            </div>

            <div class="flex items-center space-x-2">
                <a href="{{ route('expenses.edit', $expense) }}" class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-lg transition">
                    Edit
                </a>
                <a href="{{ route('expenses.index') }}" class="px-3.5 py-2 bg-slate-800 hover:bg-slate-900 text-white text-xs font-bold rounded-lg transition">
                    &larr; All Expenses
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sm:p-8 space-y-6">
                
                <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Expense Category</span>
                        <p class="text-sm font-bold text-slate-900 mt-0.5">{{ $expense->category }}</p>
                    </div>

                    <div class="text-right">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Amount</span>
                        <div class="text-2xl font-black text-rose-600">{{ __('BDT') }} {{ number_format($expense->amount, 2) }}</div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 text-xs">
                    <div>
                        <span class="text-slate-400 uppercase font-bold block mb-0.5">Paid To / Recipient</span>
                        <p class="text-sm font-bold text-slate-800">{{ $expense->paid_to ?? 'General Cost' }}</p>
                    </div>

                    <div>
                        <span class="text-slate-400 uppercase font-bold block mb-0.5">Payment Method</span>
                        <p class="text-sm font-semibold text-slate-800">{{ $expense->payment_method }}</p>
                    </div>

                    <div>
                        <span class="text-slate-400 uppercase font-bold block mb-0.5">Expense Date</span>
                        <p class="text-sm font-medium text-slate-800">{{ $expense->expense_date->format('d M, Y') }}</p>
                    </div>

                    <div>
                        <span class="text-slate-400 uppercase font-bold block mb-0.5">Logged By Officer</span>
                        <p class="text-sm font-medium text-slate-800">{{ $expense->paidBy?->name ?? 'Accounts' }}</p>
                    </div>
                </div>

                @if($expense->notes)
                    <div class="p-4 bg-slate-50 rounded-xl border border-slate-200 text-xs">
                        <span class="font-bold uppercase tracking-wider text-slate-400 block mb-1">Notes:</span>
                        <p class="text-slate-700 leading-relaxed">{{ $expense->notes }}</p>
                    </div>
                @endif

                @if($expense->attachment_path)
                    <div class="pt-4 border-t border-slate-100">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block mb-2">Voucher / Receipt Attachment:</span>
                        <a href="{{ Storage::url($expense->attachment_path) }}" target="_blank" class="inline-flex items-center text-xs font-bold text-blue-600 hover:underline">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                            View / Download Uploaded Receipt
                        </a>
                    </div>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>
