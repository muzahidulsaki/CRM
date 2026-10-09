<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-slate-800 leading-tight">
                    Add Service Renewal Tracker
                </h2>
                <p class="text-xs text-slate-500 mt-1">Set up recurring domain, hosting, server, or software maintenance payment alerts.</p>
            </div>
            <a href="{{ route('renewals.index') }}" class="px-3.5 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-lg transition">
                &larr; Back to Renewals
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

                <form action="{{ route('renewals.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                                Client / Customer <span class="text-rose-500">*</span>
                            </label>
                            <select name="customer_id" required class="w-full text-xs px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                                <option value="">-- Select Client --</option>
                                @foreach($customers as $c)
                                    <option value="{{ $c->id }}" {{ old('customer_id') == $c->id ? 'selected' : '' }}>
                                        {{ $c->name }} ({{ $c->company_name ?? 'Individual' }}) - {{ $c->phone }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                                Service / Product Name <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="service_name" value="{{ old('service_name') }}" required
                                class="w-full text-xs px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none"
                                placeholder="e.g. .com Domain (xyz.com) + 50GB NVMe Cloud Hosting">
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                                Category <span class="text-rose-500">*</span>
                            </label>
                            <select name="category" required class="w-full text-xs px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                                @foreach($categories as $cat)
                                    <option value="{{ $cat }}" {{ old('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                                Billing Cycle <span class="text-rose-500">*</span>
                            </label>
                            <select name="billing_cycle" required class="w-full text-xs px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                                @foreach($cycles as $cyc)
                                    <option value="{{ $cyc }}" {{ old('billing_cycle', 'Yearly') === $cyc ? 'selected' : '' }}>{{ $cyc }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                                Renewal Fee (৳) <span class="text-rose-500">*</span>
                            </label>
                            <input type="number" step="0.01" min="0" name="renewal_fee" value="{{ old('renewal_fee', 10000) }}" required
                                class="w-full text-xs font-bold px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none"
                                placeholder="e.g. 12000">
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                                Next Due Date <span class="text-rose-500">*</span>
                            </label>
                            <input type="date" name="next_due_date" value="{{ old('next_due_date', now()->addYear()->toDateString()) }}" required
                                class="w-full text-xs px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                                Last Payment Date (Optional)
                            </label>
                            <input type="date" name="last_payment_date" value="{{ old('last_payment_date', now()->toDateString()) }}"
                                class="w-full text-xs px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                                Alert Days Before Due
                            </label>
                            <input type="number" name="reminder_days" value="{{ old('reminder_days', 7) }}" min="1" max="60" required
                                class="w-full text-xs px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                            <p class="text-[11px] text-slate-400 mt-0.5">System alerts start this many days in advance.</p>
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Related Project (Optional)</label>
                            <select name="project_id" class="w-full text-xs px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                                <option value="">-- No project link --</option>
                                @foreach($projects as $p)
                                    <option value="{{ $p->id }}" {{ old('project_id') == $p->id ? 'selected' : '' }}>
                                        {{ $p->name }} ({{ $p->project_code }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Notes / Credentials Info</label>
                            <textarea name="notes" rows="2" class="w-full text-xs px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none"
                                placeholder="Registrar: Namecheap, Server IP: 159.xx.xx.xx, Nameservers: ns1.projuktisheba.com">{{ old('notes') }}</textarea>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-3">
                        <a href="{{ route('renewals.index') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold text-xs rounded-lg transition">
                            Cancel
                        </a>
                        <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-lg shadow-md transition">
                            Save Renewal Tracker
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
