<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-slate-800 leading-tight">
                    Edit Renewal Tracker: {{ $renewal->renewal_no }}
                </h2>
                <p class="text-xs text-slate-500 mt-1">Update subscription parameters, due date, or status.</p>
            </div>
            <a href="{{ route('renewals.show', $renewal) }}" class="px-3.5 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-lg transition">
                &larr; View Renewal
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

                <form action="{{ route('renewals.update', $renewal) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Client</label>
                            <select name="customer_id" required class="w-full text-xs px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                                @foreach($customers as $c)
                                    <option value="{{ $c->id }}" {{ old('customer_id', $renewal->customer_id) == $c->id ? 'selected' : '' }}>
                                        {{ $c->name }} ({{ $c->company_name ?? 'Individual' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Service Name</label>
                            <input type="text" name="service_name" value="{{ old('service_name', $renewal->service_name) }}" required
                                class="w-full text-xs px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Category</label>
                            <select name="category" required class="w-full text-xs px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                                @foreach($categories as $cat)
                                    <option value="{{ $cat }}" {{ old('category', $renewal->category) === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Billing Cycle</label>
                            <select name="billing_cycle" required class="w-full text-xs px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                                @foreach($cycles as $cyc)
                                    <option value="{{ $cyc }}" {{ old('billing_cycle', $renewal->billing_cycle) === $cyc ? 'selected' : '' }}>{{ $cyc }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Renewal Fee (৳)</label>
                            <input type="number" step="0.01" min="0" name="renewal_fee" value="{{ old('renewal_fee', $renewal->renewal_fee) }}" required
                                class="w-full text-xs font-bold px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Next Due Date</label>
                            <input type="date" name="next_due_date" value="{{ old('next_due_date', $renewal->next_due_date->format('Y-m-d')) }}" required
                                class="w-full text-xs px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Status Override</label>
                            <select name="status" required class="w-full text-xs px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                                @foreach($statuses as $st)
                                    <option value="{{ $st }}" {{ old('status', $renewal->status) === $st ? 'selected' : '' }}>{{ $st }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Notes</label>
                            <textarea name="notes" rows="2" class="w-full text-xs px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">{{ old('notes', $renewal->notes) }}</textarea>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-3">
                        <a href="{{ route('renewals.show', $renewal) }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold text-xs rounded-lg transition">
                            Cancel
                        </a>
                        <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-lg shadow transition">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
