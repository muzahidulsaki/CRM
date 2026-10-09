<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center space-x-3">
                <span class="font-mono text-sm font-black px-3 py-1 bg-slate-200 text-slate-800 rounded-lg">
                    {{ $renewal->renewal_no }}
                </span>
                <div>
                    <h2 class="font-bold text-xl text-slate-900 leading-tight">
                        {{ $renewal->service_name }}
                    </h2>
                    <p class="text-xs text-slate-500">
                        Category: <span class="font-semibold">{{ $renewal->category }}</span> &bull; 
                        Billing Cycle: <span class="font-semibold">{{ $renewal->billing_cycle }}</span>
                    </p>
                </div>
            </div>

            <div class="flex items-center space-x-2">
                <a href="{{ route('renewals.edit', $renewal) }}" class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-lg transition">
                    Edit Details
                </a>
                <a href="{{ route('renewals.index') }}" class="px-3.5 py-2 bg-slate-800 hover:bg-slate-900 text-white text-xs font-bold rounded-lg transition">
                    &larr; All Renewals
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="p-4 bg-emerald-50 border border-emerald-300 rounded-xl text-emerald-800 text-sm flex items-center">
                    <svg class="w-5 h-5 mr-3 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                <!-- Left Column (2 Cols): Details & Renewal Information -->
                <div class="md:col-span-2 space-y-6">

                    <!-- Status Banner -->
                    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs flex items-center justify-between">
                        <div>
                            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Current Alert Status</span>
                            <div class="mt-1">
                                <span class="px-3 py-1 rounded-full text-xs font-bold border {{ $renewal->status_badge_color }}">
                                    {{ $renewal->computed_status }}
                                </span>
                            </div>
                        </div>

                        <div class="text-right">
                            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Renewal Fee</span>
                            <div class="text-2xl font-black text-slate-900">৳{{ number_format($renewal->renewal_fee, 2) }}</div>
                            <span class="text-[11px] text-slate-500">Per {{ $renewal->billing_cycle }}</span>
                        </div>
                    </div>

                    <!-- Details Card -->
                    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs space-y-4 text-xs">
                        <h3 class="text-sm font-bold uppercase tracking-wider text-slate-800 border-b border-slate-100 pb-2">
                            Subscription Parameters
                        </h3>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <span class="text-slate-400 uppercase font-bold block mb-0.5">Service Name</span>
                                <p class="text-sm font-bold text-slate-900">{{ $renewal->service_name }}</p>
                            </div>

                            <div>
                                <span class="text-slate-400 uppercase font-bold block mb-0.5">Category</span>
                                <p class="text-sm font-semibold text-slate-800">{{ $renewal->category }}</p>
                            </div>

                            <div>
                                <span class="text-slate-400 uppercase font-bold block mb-0.5">Next Due Date</span>
                                <p class="text-sm font-bold text-blue-600 font-mono">{{ $renewal->next_due_date->format('d M, Y') }}</p>
                                <span class="text-[11px] text-slate-400">{{ $renewal->next_due_date->diffForHumans() }}</span>
                            </div>

                            <div>
                                <span class="text-slate-400 uppercase font-bold block mb-0.5">Last Paid Date</span>
                                <p class="text-sm font-medium text-slate-700">
                                    {{ $renewal->last_payment_date ? $renewal->last_payment_date->format('d M, Y') : 'Not yet logged' }}
                                </p>
                            </div>
                        </div>

                        @if($renewal->notes)
                            <div class="pt-3 border-t border-slate-100">
                                <span class="text-slate-400 uppercase font-bold block mb-1">Credentials / Operational Notes:</span>
                                <p class="text-slate-700 font-mono bg-slate-50 p-3 rounded-lg border border-slate-200 leading-relaxed">{{ $renewal->notes }}</p>
                            </div>
                        @endif
                    </div>

                </div>

                <!-- Right Column (1 Col): Client Profile & Instant Renewal Form -->
                <div class="space-y-6">

                    <!-- Instant Renewal Action Card -->
                    <div class="bg-emerald-50/70 p-6 rounded-2xl border border-emerald-200 shadow-xs">
                        <h3 class="text-xs font-black uppercase tracking-wider text-emerald-900 mb-2 flex items-center">
                            <svg class="w-4 h-4 mr-1 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Process Renewal Payment
                        </h3>
                        <p class="text-[11px] text-emerald-700 mb-4">
                            Collect payment and automatically advance the next renewal date by <strong>1 {{ $renewal->billing_cycle }}</strong>.
                        </p>

                        <form action="{{ route('renewals.renew', $renewal) }}" method="POST" class="space-y-3">
                            @csrf

                            <div>
                                <label class="block text-[11px] font-bold uppercase text-emerald-900 mb-1">Amount Paid (৳)</label>
                                <input type="number" step="0.01" min="1" name="paid_amount" value="{{ $renewal->renewal_fee }}" required
                                    class="w-full text-xs font-bold px-3 py-2 border border-emerald-300 rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none bg-white">
                            </div>

                            <div>
                                <label class="block text-[11px] font-bold uppercase text-emerald-900 mb-1">Payment Method</label>
                                <select name="payment_method" required class="w-full text-xs px-3 py-2 border border-emerald-300 rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none bg-white">
                                    @foreach($methods as $m)
                                        <option value="{{ $m }}">{{ $m }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-[11px] font-bold uppercase text-emerald-900 mb-1">Transaction Ref / Slip #</label>
                                <input type="text" name="transaction_reference"
                                    class="w-full text-xs px-3 py-2 border border-emerald-300 rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none bg-white"
                                    placeholder="e.g. Bank Ref #112233">
                            </div>

                            <button type="submit" class="w-full py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-lg transition shadow-xs mt-2">
                                Renew & Extend Due Date
                            </button>
                        </form>
                    </div>

                    <!-- Client Profile Card -->
                    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs space-y-2 text-xs">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-2">
                            <span class="font-bold uppercase tracking-wider text-slate-400">Subscriber</span>
                            <a href="{{ route('customers.show', $renewal->customer) }}" class="text-[11px] font-bold text-blue-600 hover:underline">
                                Profile &rarr;
                            </a>
                        </div>
                        <p class="text-sm font-bold text-slate-900">{{ $renewal->customer->name }}</p>
                        @if($renewal->customer->company_name)
                            <p class="text-slate-600 font-medium">{{ $renewal->customer->company_name }}</p>
                        @endif
                        <p class="text-slate-600 font-mono">Phone: {{ $renewal->customer->phone }}</p>
                    </div>

                </div>

            </div>

        </div>
    </div>
</x-app-layout>
