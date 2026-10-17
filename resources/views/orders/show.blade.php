<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <div class="flex items-center gap-2">
                    <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                        {{ $order->order_code }}
                    </h2>
                    @php
                        $stClass = match($order->status) {
                            'Confirmed' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                            'Project Started', 'Working' => 'bg-blue-50 text-blue-700 border-blue-200',
                            'Under Review' => 'bg-purple-50 text-purple-700 border-purple-200',
                            'Completed', 'Delivered' => 'bg-green-50 text-green-700 border-green-200',
                            'Cancelled' => 'bg-rose-50 text-rose-700 border-rose-200',
                            default => 'bg-amber-50 text-amber-700 border-amber-200'
                        };
                    @endphp
                    <span class="px-3 py-1 rounded-full text-xs font-semibold border {{ $stClass }}">
                        {{ __($order->status) }}
                    </span>
                </div>
                <p class="text-sm text-gray-500 mt-1">{{ $order->title }} ({{ __($order->service_category) }})</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('orders.edit', $order) }}" class="inline-flex items-center px-4 py-2 bg-indigo-50 text-indigo-700 border border-indigo-200 rounded-lg text-sm font-semibold hover:bg-indigo-100 transition">
                    <svg class="w-4 h-4 me-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    {{ __('Edit') }}
                </a>
                <a href="{{ route('orders.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm font-semibold hover:bg-gray-200 transition">
                    {{ __('Back to List') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Alerts -->
            @if(session('success'))
                <div class="p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 rounded-r-lg shadow-sm flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        <span class="font-medium text-sm">{{ __(session('success')) }}</span>
                    </div>
                </div>
            @endif

            <!-- Financial Summary Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm">
                    <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('Total Contract Value') }}</div>
                    <div class="text-2xl font-black text-gray-900 mt-1">{{ __('BDT') }} {{ number_format($order->total_amount, 2) }}</div>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-emerald-200 bg-emerald-50/30 shadow-sm">
                    <div class="text-xs font-semibold text-emerald-700 uppercase tracking-wider">{{ __('Paid') }}</div>
                    <div class="text-2xl font-black text-emerald-700 mt-1">{{ __('BDT') }} {{ number_format($order->paid_amount, 2) }}</div>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-rose-200 bg-rose-50/30 shadow-sm">
                    <div class="text-xs font-semibold text-rose-700 uppercase tracking-wider">{{ __('Due') }}</div>
                    <div class="text-2xl font-black text-rose-600 mt-1">{{ __('BDT') }} {{ number_format($order->due_amount, 2) }}</div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- Left Column (Order Details & Project) -->
                <div class="lg:col-span-2 space-y-6">

                    <!-- Project Management Link Card -->
                    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
                        <div class="flex items-center justify-between pb-4 border-b border-gray-100">
                            <h3 class="font-bold text-gray-900 flex items-center gap-2">
                                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                                {{ __('Project Status') }}
                            </h3>
                            @if($order->project)
                                <a href="{{ route('projects.show', $order->project) }}" class="inline-flex items-center text-xs font-bold text-indigo-600 hover:text-indigo-800 bg-indigo-50 px-3 py-1.5 rounded-lg border border-indigo-200">
                                    {{ __('Go to Project Dashboard') }} &rarr;
                                </a>
                            @endif
                        </div>

                        <div class="mt-5">
                            @if($order->project)
                                <div class="space-y-4">
                                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                                        <div>
                                            <span class="text-xs font-mono bg-gray-100 text-gray-700 px-2 py-0.5 rounded">{{ $order->project->project_code }}</span>
                                            <h4 class="text-lg font-bold text-gray-900 mt-1">{{ $order->project->name }}</h4>
                                            <p class="text-xs text-gray-500">{{ __('PM') }}: {{ $order->project->projectManager?->name ?? __('Not Assigned') }}</p>
                                        </div>
                                        <div class="text-right">
                                            <div class="text-2xl font-black text-indigo-600">{{ $order->project->progress_percent }}%</div>
                                            <div class="text-xs text-gray-500">{{ __('Overall Progress') }}</div>
                                        </div>
                                    </div>

                                    <!-- Progress Bar -->
                                    <div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden">
                                        <div class="bg-indigo-600 h-3 rounded-full transition-all duration-500" style="width: {{ $order->project->progress_percent }}%"></div>
                                    </div>

                                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 pt-2 text-center text-xs">
                                        <div class="p-2 bg-gray-50 rounded-lg">
                                            <div class="text-gray-500">{{ __('Total Tasks') }}</div>
                                            <div class="font-bold text-gray-900 text-sm mt-0.5">{{ $order->project->tasks->count() }}</div>
                                        </div>
                                        <div class="p-2 bg-blue-50 rounded-lg">
                                            <div class="text-blue-600">{{ __('Working') }}</div>
                                            <div class="font-bold text-blue-900 text-sm mt-0.5">{{ $order->project->tasks->where('status', 'Working')->count() }}</div>
                                        </div>
                                        <div class="p-2 bg-purple-50 rounded-lg">
                                            <div class="text-purple-600">{{ __('QA Passed') }}</div>
                                            <div class="font-bold text-purple-900 text-sm mt-0.5">{{ $order->project->tasks->where('status', 'Checked')->count() }}</div>
                                        </div>
                                        <div class="p-2 bg-emerald-50 rounded-lg">
                                            <div class="text-emerald-600">{{ __('Delivered') }}</div>
                                            <div class="font-bold text-emerald-900 text-sm mt-0.5">{{ $order->project->tasks->where('status', 'Delivered')->count() }}</div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="text-center py-6 bg-gray-50 rounded-xl border border-dashed border-gray-300">
                                    <svg class="w-12 h-12 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                    <h4 class="font-bold text-gray-800">{{ __('No project has been initialized yet') }}</h4>
                                    <p class="text-xs text-gray-500 mt-1 max-w-sm mx-auto">{{ __('Initialize a dedicated project for this order to assign tasks to developers, designers and editors.') }}</p>
                                    
                                    <form method="POST" action="{{ route('orders.initialize-project', $order) }}" class="mt-4">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white rounded-lg text-sm font-semibold hover:bg-emerald-700 shadow-sm transition">
                                            <svg class="w-4 h-4 me-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                            {{ __('Start Project Now') }}
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Order Information Table -->
                    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 space-y-4">
                        <h3 class="font-bold text-gray-900 border-b border-gray-100 pb-3">{{ __('Order Details') }}</h3>
                        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                            <div>
                                <dt class="text-xs font-semibold text-gray-500">{{ __('Order Code') }}</dt>
                                <dd class="font-bold text-gray-900 mt-0.5">{{ $order->order_code }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs font-semibold text-gray-500">{{ __('Service Category') }}</dt>
                                <dd class="font-medium text-gray-900 mt-0.5">{{ __($order->service_category) }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs font-semibold text-gray-500">{{ __('Order Date') }}</dt>
                                <dd class="font-medium text-gray-900 mt-0.5">{{ $order->order_date ? $order->order_date->format('d M, Y') : '-' }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs font-semibold text-gray-500">{{ __('Expected Delivery') }}</dt>
                                <dd class="font-medium text-gray-900 mt-0.5">{{ $order->expected_delivery_date ? $order->expected_delivery_date->format('d M, Y') : __('Not Specified') }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs font-semibold text-gray-500">{{ __('Sales Representative') }}</dt>
                                <dd class="font-medium text-gray-900 mt-0.5">{{ $order->salesPerson?->name ?? '-' }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs font-semibold text-gray-500">{{ __('Project Manager') }}</dt>
                                <dd class="font-medium text-gray-900 mt-0.5">{{ $order->projectManager?->name ?? __('Not Assigned') }}</dd>
                            </div>
                        </dl>

                        @if($order->notes)
                            <div class="pt-4 border-t border-gray-100">
                                <span class="text-xs font-semibold text-gray-500 block mb-1">{{ __('Notes / Special Terms') }}:</span>
                                <div class="p-3 bg-gray-50 rounded-lg text-sm text-gray-700 whitespace-pre-line">{{ $order->notes }}</div>
                            </div>
                        @endif
                    </div>

                </div>

                <!-- Right Column (Customer & Quick Actions) -->
                <div class="space-y-6">

                    <!-- Customer 360 Card -->
                    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 space-y-4">
                        <div class="flex items-center justify-between">
                            <h3 class="font-bold text-gray-900">{{ __('Customer Information') }}</h3>
                            @if($order->customer)
                                <a href="{{ route('customers.show', $order->customer) }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800">
                                    {{ __('Customer 360°') }} &rarr;
                                </a>
                            @endif
                        </div>

                        @if($order->customer)
                            <div class="flex items-start gap-3">
                                <div class="w-12 h-12 rounded-full bg-indigo-100 text-indigo-700 font-bold flex items-center justify-center text-lg shrink-0">
                                    {{ mb_substr($order->customer->name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="font-bold text-gray-900">{{ $order->customer->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $order->customer->company_name ?? __('Individual Client') }}</div>
                                    <div class="text-xs font-mono text-indigo-600 mt-0.5">{{ $order->customer->customer_code }}</div>
                                </div>
                            </div>

                            <div class="space-y-2 text-xs text-gray-600 pt-2 border-t border-gray-100">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                    <a href="tel:{{ $order->customer->phone }}" class="hover:text-indigo-600 font-medium">{{ $order->customer->phone }}</a>
                                </div>
                                @if($order->customer->email)
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                        <a href="mailto:{{ $order->customer->email }}" class="hover:text-indigo-600">{{ $order->customer->email }}</a>
                                    </div>
                                @endif
                                @if($order->customer->city)
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        <span>{{ $order->customer->city }}</span>
                                    </div>
                                @endif
                            </div>
                        @else
                            <div class="text-xs text-gray-400">{{ __('No client linked') }}</div>
                        @endif
                    </div>

                    <!-- Linked Quotation Card -->
                    @if($order->quotation)
                        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
                            <h3 class="font-bold text-gray-900 mb-2">{{ __('Linked Quotation') }}</h3>
                            <div class="p-3 bg-indigo-50/50 border border-indigo-100 rounded-xl flex items-center justify-between">
                                <div>
                                    <a href="{{ route('quotations.show', $order->quotation) }}" class="font-bold text-sm text-indigo-700 hover:underline">
                                        {{ $order->quotation->quotation_code }}
                                    </a>
                                    <div class="text-xs text-gray-500 mt-0.5">{{ __('Accepted') }}: {{ $order->quotation->quotation_date?->format('d M, Y') }}</div>
                                </div>
                                <span class="text-xs font-bold text-emerald-700 bg-emerald-100 px-2 py-0.5 rounded">{{ __('Accepted') }}</span>
                            </div>
                        </div>
                    @endif

                    <!-- Quick Status Update -->
                    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 space-y-3">
                        <h3 class="font-bold text-gray-900 text-sm">{{ __('Quick Status Update') }}</h3>
                        <form method="POST" action="{{ route('orders.update-status', $order) }}" class="space-y-3">
                            @csrf
                            @method('PATCH')
                            <select name="status" class="w-full text-xs rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                @foreach(['Pending', 'Confirmed', 'Project Started', 'Working', 'Under Review', 'Completed', 'Delivered', 'Cancelled'] as $st)
                                    <option value="{{ $st }}" {{ $order->status === $st ? 'selected' : '' }}>{{ __($st) }}</option>
                                @endforeach
                            </select>
                            <button type="submit" class="w-full py-2 bg-gray-900 text-white rounded-lg text-xs font-semibold hover:bg-gray-800 transition">
                                {{ __('Update Status') }}
                            </button>
                        </form>
                    </div>

                    <!-- Delete Order -->
                    <div class="bg-white rounded-2xl border border-rose-100 shadow-sm p-6">
                        <h4 class="text-xs font-bold text-rose-800 uppercase tracking-wider mb-2">{{ __('Delete Order') }}</h4>
                        <p class="text-xs text-gray-500 mb-3">{{ __('Warning: Deleting this order will remove all its history.') }}</p>
                        <form method="POST" action="{{ route('orders.destroy', $order) }}" onsubmit="return confirm('{{ __('Are you sure you want to delete this order?') }}');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full py-2 bg-rose-50 text-rose-700 border border-rose-200 rounded-lg text-xs font-semibold hover:bg-rose-100 transition">
                                {{ __('Delete Order') }}
                            </button>
                        </form>
                    </div>

                </div>

            </div>

        </div>
    </div>
</x-app-layout>
