<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                    {{ __('Edit Order') }}: {{ $order->order_code }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">{{ __('Update order details, pricing and delivery schedule') }}</p>
            </div>
            <a href="{{ route('orders.show', $order) }}" class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-200 transition">
                <svg class="w-4 h-4 me-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                {{ __('Back to Order') }}
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            @if ($errors->any())
                <div class="mb-6 p-4 bg-rose-50 border-l-4 border-rose-500 text-rose-800 rounded-r-lg shadow-sm">
                    <div class="font-bold text-sm">{{ __('Please fix the following errors:') }}</div>
                    <ul class="mt-2 list-disc list-inside text-xs space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('orders.update', $order) }}" class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 sm:p-8 space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

                    <!-- Customer (read-only reference) -->
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('Client') }}</label>
                        <div class="p-3 bg-gray-50 border border-gray-200 rounded-lg text-sm text-gray-900 font-medium">
                            {{ $order->customer?->name }} ({{ $order->customer?->company_name ?? __('Individual Client') }}) - {{ $order->customer?->phone }}
                        </div>
                    </div>

                    <!-- Title -->
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('Order Title') }} <span class="text-rose-500">*</span></label>
                        <input type="text" name="title" required value="{{ old('title', $order->title) }}"
                            class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <!-- Service Category -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('Service Category') }} <span class="text-rose-500">*</span></label>
                        <select name="service_category" required class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            @foreach($serviceCategories as $cat)
                                <option value="{{ $cat }}" {{ old('service_category', $order->service_category) == $cat ? 'selected' : '' }}>
                                    {{ __($cat) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('Status') }} <span class="text-rose-500">*</span></label>
                        <select name="status" required class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            @foreach(['Pending', 'Confirmed', 'Project Started', 'Working', 'Under Review', 'Completed', 'Delivered', 'Cancelled'] as $st)
                                <option value="{{ $st }}" {{ old('status', $order->status) == $st ? 'selected' : '' }}>{{ __($st) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Total Amount -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('Total Contract Value (BDT)') }} <span class="text-rose-500">*</span></label>
                        <input type="number" step="0.01" name="total_amount" id="total_amount" required
                            value="{{ old('total_amount', $order->total_amount) }}"
                            class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <!-- Paid Amount -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('Paid Amount (BDT)') }}</label>
                        <input type="number" step="0.01" name="paid_amount" id="paid_amount"
                            value="{{ old('paid_amount', $order->paid_amount) }}"
                            class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <!-- Due Display -->
                    <div class="sm:col-span-2 p-3 bg-gray-50 rounded-xl flex items-center justify-between border border-gray-200">
                        <span class="text-sm font-medium text-gray-700">{{ __('Remaining Due:') }}</span>
                        <span id="due_amount_display" class="text-base font-bold text-rose-600">{{ __('BDT') }} 0.00</span>
                    </div>

                    <!-- Order Date -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('Order Date') }} <span class="text-rose-500">*</span></label>
                        <input type="date" name="order_date" required value="{{ old('order_date', $order->order_date?->format('Y-m-d')) }}"
                            class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <!-- Expected Delivery Date -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('Expected Delivery Date') }}</label>
                        <input type="date" name="expected_delivery_date" value="{{ old('expected_delivery_date', $order->expected_delivery_date?->format('Y-m-d')) }}"
                            class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <!-- PM -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('Project Manager') }}</label>
                        <select name="project_manager_id" class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">{{ __('Select Manager') }}</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('project_manager_id', $order->project_manager_id) == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Sales Person -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('Sales Representative') }}</label>
                        <select name="sales_person_id" class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">{{ __('Select Representative') }}</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('sales_person_id', $order->sales_person_id) == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Notes -->
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('Notes / Details') }}</label>
                        <textarea name="notes" rows="3" class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">{{ old('notes', $order->notes) }}</textarea>
                    </div>

                </div>

                <div class="pt-4 border-t border-gray-100 flex items-center justify-end gap-3">
                    <a href="{{ route('orders.show', $order) }}" class="px-5 py-2.5 bg-gray-100 text-gray-700 rounded-lg text-sm font-semibold hover:bg-gray-200 transition">
                        {{ __('Cancel') }}
                    </a>
                    <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white rounded-lg text-sm font-semibold hover:bg-indigo-700 shadow-sm transition">
                        {{ __('Save Changes') }}
                    </button>
                </div>
            </form>

        </div>
    </div>

    <script>
        function updateDue() {
            const total = parseFloat(document.getElementById('total_amount').value) || 0;
            const paid = parseFloat(document.getElementById('paid_amount').value) || 0;
            const due = Math.max(0, total - paid);
            document.getElementById('due_amount_display').innerText = '{{ __('BDT') }} ' + due.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
        }
        document.getElementById('total_amount').addEventListener('input', updateDue);
        document.getElementById('paid_amount').addEventListener('input', updateDue);
        updateDue();
    </script>
</x-app-layout>
