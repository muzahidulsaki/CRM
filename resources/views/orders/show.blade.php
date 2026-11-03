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
                                <dt class="text-xs font-semibold text-gray-500">{{ __('Order Source') }}</dt>
                                <dd class="font-medium text-gray-900 mt-0.5">
                                    <span class="px-2 py-0.5 rounded text-xs font-semibold bg-gray-100 text-gray-700">
                                        {{ $order->order_source ?? __('Direct') }}
                                    </span>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-xs font-semibold text-gray-500">{{ __('Activity Status') }}</dt>
                                <dd class="font-medium text-gray-900 mt-0.5">
                                    <span class="px-2 py-0.5 rounded text-xs font-semibold {{ ($order->activity_status ?? 'Active') === 'Active' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-gray-100 text-gray-600' }}">
                                        {{ $order->activity_status ?? __('Active') }}
                                    </span>
                                </dd>
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

                    <!-- Format-Specific Specifications (Creative, Ads, Soft/Web) -->
                    @php $meta = $order->meta ?? []; @endphp
                    @if(!empty($meta) || in_array($order->order_type, ['creative', 'ads', 'web_software']))
                        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 space-y-4">
                            <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                                <h3 class="font-bold text-gray-900 flex items-center gap-2">
                                    @if($order->order_type === 'creative')
                                        <span>🎨 {{ __('Creative Order Specifications') }}</span>
                                    @elseif($order->order_type === 'ads')
                                        <span>📈 {{ __('Ads Campaign Specifications') }}</span>
                                    @elseif($order->order_type === 'web_software')
                                        <span>💻 {{ __('Software & Website Specifications') }}</span>
                                    @else
                                        <span>📋 {{ __('Order Specifications') }}</span>
                                    @endif
                                </h3>
                                <span class="px-2.5 py-0.5 rounded text-xs font-bold uppercase {{ $order->order_type === 'creative' ? 'bg-indigo-100 text-indigo-800' : ($order->order_type === 'ads' ? 'bg-amber-100 text-amber-800' : 'bg-blue-100 text-blue-800') }}">
                                    {{ $order->order_type }}
                                </span>
                            </div>

                            @if($order->order_type === 'creative')
                                <dl class="grid grid-cols-2 sm:grid-cols-3 gap-3 text-xs">
                                    <div class="p-3 bg-gray-50 rounded-xl">
                                        <dt class="text-gray-500 font-semibold">{{ __('Script / Idea') }}</dt>
                                        <dd class="font-bold text-gray-900 mt-0.5">{{ $meta['script_idea'] ?? '-' }}</dd>
                                    </div>
                                    <div class="p-3 bg-gray-50 rounded-xl">
                                        <dt class="text-gray-500 font-semibold">{{ __('Script Code') }}</dt>
                                        <dd class="font-bold font-mono text-indigo-700 mt-0.5">{{ $meta['script_code'] ?? '-' }}</dd>
                                    </div>
                                    <div class="p-3 bg-gray-50 rounded-xl">
                                        <dt class="text-gray-500 font-semibold">{{ __('Voice / Design') }}</dt>
                                        <dd class="font-bold text-gray-900 mt-0.5">{{ $meta['voice_design'] ?? '-' }}</dd>
                                    </div>
                                    <div class="p-3 bg-gray-50 rounded-xl">
                                        <dt class="text-gray-500 font-semibold">{{ __('Duration') }}</dt>
                                        <dd class="font-bold text-gray-900 mt-0.5">{{ !empty($meta['second']) ? $meta['second'] . 's' : '-' }}</dd>
                                    </div>
                                    <div class="p-3 bg-gray-50 rounded-xl">
                                        <dt class="text-gray-500 font-semibold">{{ __('Language') }}</dt>
                                        <dd class="font-bold text-gray-900 mt-0.5">{{ $meta['language'] ?? '-' }}</dd>
                                    </div>
                                    <div class="p-3 bg-gray-50 rounded-xl">
                                        <dt class="text-gray-500 font-semibold">{{ __('Voice Link') }}</dt>
                                        <dd class="mt-0.5 truncate">
                                            @if(!empty($meta['voice_link']))
                                                <a href="{{ $meta['voice_link'] }}" target="_blank" class="text-indigo-600 hover:underline font-bold flex items-center gap-1">
                                                    <span>🎙️</span> <span class="truncate">{{ __('Open Audio Link') }}</span> ↗
                                                </a>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </dd>
                                    </div>
                                    <div class="p-3 bg-gray-50 rounded-xl">
                                        <dt class="text-gray-500 font-semibold">{{ __('Service Provider') }}</dt>
                                        <dd class="font-bold text-gray-900 mt-0.5">{{ $meta['service_provider'] ?? '-' }}</dd>
                                    </div>
                                    <div class="p-3 bg-gray-50 rounded-xl">
                                        <dt class="text-gray-500 font-semibold">{{ __('Starting Date') }}</dt>
                                        <dd class="font-bold font-mono text-gray-900 mt-0.5">{{ $meta['starting_date'] ?? $order->start_date?->format('d M, Y') ?? '-' }}</dd>
                                    </div>
                                    <div class="p-3 bg-gray-50 rounded-xl">
                                        <dt class="text-gray-500 font-semibold">{{ __('Complete Date') }}</dt>
                                        <dd class="font-bold font-mono text-gray-900 mt-0.5">{{ $meta['complete_date'] ?? '-' }}</dd>
                                    </div>
                                    <div class="p-3 bg-gray-50 rounded-xl sm:col-span-3">
                                        <dt class="text-gray-500 font-semibold">{{ __('Why Not Delivery') }}</dt>
                                        <dd class="font-bold text-rose-700 mt-0.5">{{ $meta['why_not_delivery'] ?? '-' }}</dd>
                                    </div>
                                    <div class="p-3 bg-gray-50 rounded-xl sm:col-span-3">
                                        <dt class="text-gray-500 font-semibold">{{ __('Reference Link / Asset Drive') }}</dt>
                                        <dd class="mt-0.5 truncate">
                                            @if(!empty($meta['reference_link']))
                                                <a href="{{ $meta['reference_link'] }}" target="_blank" class="text-indigo-600 hover:underline font-bold flex items-center gap-1.5">
                                                    <span>📁</span> <span class="truncate">{{ $meta['reference_link'] }}</span> ↗
                                                </a>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </dd>
                                    </div>
                                </dl>

                            @elseif($order->order_type === 'ads')
                                <dl class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs">
                                    <div class="p-3 bg-gray-50 rounded-xl sm:col-span-4">
                                        <dt class="text-gray-500 font-semibold">{{ __('Ads Post Link') }}</dt>
                                        <dd class="mt-0.5 truncate">
                                            @if(!empty($meta['ads_post_link']))
                                                <a href="{{ $meta['ads_post_link'] }}" target="_blank" class="text-amber-700 hover:underline font-bold flex items-center gap-1.5">
                                                    <span>🔗</span> <span class="truncate">{{ $meta['ads_post_link'] }}</span> ↗
                                                </a>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </dd>
                                    </div>
                                    <div class="p-3 bg-gray-50 rounded-xl">
                                        <dt class="text-gray-500 font-semibold">{{ __('Total Budget ($)') }}</dt>
                                        <dd class="font-bold font-mono text-amber-700 mt-0.5 text-sm">${{ number_format(floatval($meta['total_dollar'] ?? 0), 2) }}</dd>
                                    </div>
                                    <div class="p-3 bg-gray-50 rounded-xl">
                                        <dt class="text-gray-500 font-semibold">{{ __('Lifetime Budget ($)') }}</dt>
                                        <dd class="font-bold font-mono text-amber-700 mt-0.5 text-sm">${{ number_format(floatval($meta['lifetime_budget'] ?? 0), 2) }}</dd>
                                    </div>
                                    <div class="p-3 bg-gray-50 rounded-xl">
                                        <dt class="text-gray-500 font-semibold">{{ __('Starting Date') }}</dt>
                                        <dd class="font-bold font-mono text-gray-900 mt-0.5">{{ $meta['starting_date'] ?? $order->start_date?->format('d M, Y') ?? '-' }}</dd>
                                    </div>
                                    <div class="p-3 bg-gray-50 rounded-xl">
                                        <dt class="text-gray-500 font-semibold">{{ __('End Date') }}</dt>
                                        <dd class="font-bold font-mono text-gray-900 mt-0.5">{{ $meta['end_date'] ?? $order->expected_delivery_date?->format('d M, Y') ?? '-' }}</dd>
                                    </div>
                                    <div class="p-3 bg-gray-50 rounded-xl">
                                        <dt class="text-gray-500 font-semibold">{{ __('Daily Cost ($)') }}</dt>
                                        <dd class="font-bold font-mono text-gray-900 mt-0.5 text-sm">${{ number_format(floatval($meta['daily_cost'] ?? 0), 2) }}</dd>
                                    </div>
                                    <div class="p-3 bg-gray-50 rounded-xl">
                                        <dt class="text-gray-500 font-semibold">{{ __('Total Days') }}</dt>
                                        <dd class="font-bold font-mono text-gray-900 mt-0.5 text-sm">{{ $meta['total_days'] ?? '-' }} days</dd>
                                    </div>
                                    <div class="p-3 bg-gray-50 rounded-xl">
                                        <dt class="text-gray-500 font-semibold">{{ __('Today Check') }}</dt>
                                        <dd class="mt-0.5 font-bold">
                                            <span class="px-2 py-0.5 rounded text-xs {{ ($meta['today_check'] ?? 'Pending') === 'Checked' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                                {{ ($meta['today_check'] ?? 'Pending') === 'Checked' ? '✅ Checked' : '⏳ Pending' }}
                                            </span>
                                        </dd>
                                    </div>
                                    <div class="p-3 bg-gray-50 rounded-xl">
                                        <dt class="text-gray-500 font-semibold">{{ __('Previous Dollar ($)') }}</dt>
                                        <dd class="font-bold font-mono text-gray-700 mt-0.5">${{ number_format(floatval($meta['previous_dollar'] ?? 0), 2) }}</dd>
                                    </div>
                                    <div class="p-3 bg-gray-50 rounded-xl">
                                        <dt class="text-gray-500 font-semibold">{{ __('Running Dollar ($)') }}</dt>
                                        <dd class="font-bold font-mono text-indigo-700 mt-0.5">${{ number_format(floatval($meta['running_dollar'] ?? 0), 2) }}</dd>
                                    </div>
                                    <div class="p-3 bg-gray-50 rounded-xl">
                                        <dt class="text-gray-500 font-semibold">{{ __('Total $ Cost') }}</dt>
                                        <dd class="font-bold font-mono text-gray-900 mt-0.5">${{ number_format(floatval($meta['total_dollar_cost'] ?? 0), 2) }}</dd>
                                    </div>
                                    <div class="p-3 bg-gray-50 rounded-xl">
                                        <dt class="text-gray-500 font-semibold">{{ __('Dollar Due ($)') }}</dt>
                                        <dd class="font-bold font-mono text-rose-600 mt-0.5 text-sm">${{ number_format(floatval($meta['dollar_due'] ?? 0), 2) }}</dd>
                                    </div>
                                </dl>

                            @elseif($order->order_type === 'web_software')
                                <dl class="grid grid-cols-2 sm:grid-cols-3 gap-3 text-xs">
                                    <div class="p-3 bg-gray-50 rounded-xl">
                                        <dt class="text-gray-500 font-semibold">{{ __('Subscription System') }}</dt>
                                        <dd class="font-bold text-gray-900 mt-0.5">{{ $meta['subscription_system'] ?? 'None' }}</dd>
                                    </div>
                                    <div class="p-3 bg-gray-50 rounded-xl">
                                        <dt class="text-gray-500 font-semibold">{{ __('Subscription / Renew Fee') }}</dt>
                                        <dd class="font-bold font-mono text-blue-700 mt-0.5">{{ !empty($meta['subscription_fee']) ? 'BDT ' . number_format(floatval($meta['subscription_fee']), 2) : '-' }}</dd>
                                    </div>
                                    <div class="p-3 bg-gray-50 rounded-xl">
                                        <dt class="text-gray-500 font-semibold">{{ __('Next Renew Payment Date') }}</dt>
                                        <dd class="font-bold font-mono text-blue-800 mt-0.5">{{ !empty($meta['next_renew_date']) ? date('d M, Y', strtotime($meta['next_renew_date'])) : '-' }}</dd>
                                    </div>
                                    <div class="p-3 bg-gray-50 rounded-xl">
                                        <dt class="text-gray-500 font-semibold">{{ __('Working Priority') }}</dt>
                                        <dd class="font-bold mt-0.5">
                                            <span class="px-2 py-0.5 rounded text-xs {{ ($meta['working_priority'] ?? 'Normal') === 'Urgent' ? 'bg-rose-100 text-rose-800' : (($meta['working_priority'] ?? 'Normal') === 'High' ? 'bg-amber-100 text-amber-800' : 'bg-gray-100 text-gray-800') }}">
                                                {{ $meta['working_priority'] ?? 'Normal' }}
                                            </span>
                                        </dd>
                                    </div>
                                    <div class="p-3 bg-gray-50 rounded-xl">
                                        <dt class="text-gray-500 font-semibold">{{ __('Purchase Date') }}</dt>
                                        <dd class="font-bold font-mono text-gray-900 mt-0.5">{{ !empty($meta['purchase_date']) ? date('d M, Y', strtotime($meta['purchase_date'])) : '-' }}</dd>
                                    </div>
                                    <div class="p-3 bg-gray-50 rounded-xl">
                                        <dt class="text-gray-500 font-semibold">{{ __('Complete Date') }}</dt>
                                        <dd class="font-bold font-mono text-gray-900 mt-0.5">{{ !empty($meta['complete_date']) ? date('d M, Y', strtotime($meta['complete_date'])) : '-' }}</dd>
                                    </div>
                                    <div class="p-3 bg-gray-50 rounded-xl">
                                        <dt class="text-gray-500 font-semibold">{{ __('Renew Status') }}</dt>
                                        <dd class="font-bold mt-0.5">
                                            <span class="px-2 py-0.5 rounded text-xs {{ ($meta['renew_status'] ?? 'Active') === 'Active' ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-100 text-gray-700' }}">
                                                {{ $meta['renew_status'] ?? 'Active' }}
                                            </span>
                                        </dd>
                                    </div>
                                    <div class="p-3 bg-gray-50 rounded-xl">
                                        <dt class="text-gray-500 font-semibold">{{ __('Developed Assign') }}</dt>
                                        <dd class="font-bold text-gray-900 mt-0.5">{{ $meta['developed_assign'] ?? '-' }}</dd>
                                    </div>
                                    <div class="p-3 bg-gray-50 rounded-xl">
                                        <dt class="text-gray-500 font-semibold">{{ __('Customize Assign') }}</dt>
                                        <dd class="font-bold text-gray-900 mt-0.5">{{ $meta['customize_assign'] ?? '-' }}</dd>
                                    </div>
                                    <div class="p-3 bg-gray-50 rounded-xl sm:col-span-3">
                                        <dt class="text-gray-500 font-semibold">{{ __('Domain / Live URL') }}</dt>
                                        <dd class="mt-0.5 truncate">
                                            @if(!empty($meta['domain']))
                                                <a href="{{ str_starts_with($meta['domain'], 'http') ? $meta['domain'] : 'https://' . $meta['domain'] }}" target="_blank" class="text-blue-600 hover:underline font-bold flex items-center gap-1.5">
                                                    <span>🌐</span> <span class="truncate">{{ $meta['domain'] }}</span> ↗
                                                </a>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </dd>
                                    </div>
                                </dl>

                                @if(!empty($meta['official_admin_link']) || !empty($meta['client_admin_link']) || !empty($meta['client_other_credentials']))
                                    <div class="p-4 bg-gray-50/70 border border-gray-200 rounded-xl space-y-2 text-xs mt-4">
                                        <div class="font-bold text-gray-800 flex items-center gap-1">
                                            <span>🔐</span> {{ __('Stored Portals & Credentials') }}
                                        </div>
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-1">
                                            @if(!empty($meta['official_admin_link']))
                                                <div class="p-2.5 bg-white rounded-lg border border-gray-200">
                                                    <span class="text-2xs font-bold text-indigo-700 uppercase block mb-1">{{ __('Official Admin Access') }}</span>
                                                    <div class="font-mono text-2xs truncate">
                                                        <span class="text-gray-500">URL:</span> 
                                                        <a href="{{ str_starts_with($meta['official_admin_link'], 'http') ? $meta['official_admin_link'] : 'https://' . $meta['official_admin_link'] }}" target="_blank" class="text-indigo-600 hover:underline">{{ $meta['official_admin_link'] }} ↗</a>
                                                    </div>
                                                    <div class="font-mono text-2xs mt-0.5"><span class="text-gray-500">User:</span> {{ $meta['official_user_id'] ?? '-' }}</div>
                                                    <div class="font-mono text-2xs text-gray-600 mt-0.5"><span class="text-gray-500">Pass:</span> {{ $meta['official_password'] ?? '-' }}</div>
                                                </div>
                                            @endif
                                            @if(!empty($meta['client_admin_link']))
                                                <div class="p-2.5 bg-white rounded-lg border border-gray-200">
                                                    <span class="text-2xs font-bold text-emerald-700 uppercase block mb-1">{{ __('Client Admin Access') }}</span>
                                                    <div class="font-mono text-2xs truncate">
                                                        <span class="text-gray-500">URL:</span> 
                                                        <a href="{{ str_starts_with($meta['client_admin_link'], 'http') ? $meta['client_admin_link'] : 'https://' . $meta['client_admin_link'] }}" target="_blank" class="text-emerald-600 hover:underline">{{ $meta['client_admin_link'] }} ↗</a>
                                                    </div>
                                                    <div class="font-mono text-2xs mt-0.5"><span class="text-gray-500">User:</span> {{ $meta['client_user_id'] ?? '-' }}</div>
                                                    <div class="font-mono text-2xs text-gray-600 mt-0.5"><span class="text-gray-500">Pass:</span> {{ $meta['client_password'] ?? '-' }}</div>
                                                </div>
                                            @endif
                                            @if(!empty($meta['client_other_credentials']))
                                                <div class="p-2.5 bg-white rounded-lg border border-gray-200 sm:col-span-2">
                                                    <span class="text-2xs font-bold text-gray-700 uppercase block mb-1">{{ __('Other Client Credentials (cPanel / DB / Hosting)') }}</span>
                                                    <div class="font-mono text-2xs text-gray-700 whitespace-pre-line bg-gray-50 p-2 rounded border border-gray-100">{{ $meta['client_other_credentials'] }}</div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            @endif
                        </div>
                    @endif

                    <!-- Payment Installments & Transaction Log -->
                    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 space-y-4" x-data="{ showPayModal: false }">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-gray-100 pb-4">
                            <div>
                                <h3 class="font-bold text-gray-900 text-base flex items-center gap-2">
                                    <span>💳</span> {{ __('Payment Installments & Transaction Log') }}
                                </h3>
                                <p class="text-xs text-gray-500">{{ __('Payment installment receipts & live transaction history log') }}</p>
                            </div>
                            @if($order->due_amount > 0)
                                <button type="button" @click="showPayModal = !showPayModal" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-semibold shadow-xs transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                                    <span>{{ __('+ Record New Payment') }}</span>
                                </button>
                            @else
                                <span class="inline-flex items-center gap-1 px-3 py-1 bg-emerald-100 text-emerald-800 rounded-full text-xs font-bold">
                                    ✓ Fully Paid
                                </span>
                            @endif
                        </div>

                        <!-- Quick Summary Cards -->
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            <div class="p-4 bg-gray-50 rounded-xl border border-gray-200">
                                <span class="text-2xs font-semibold text-gray-500 block uppercase tracking-wider">{{ __('Initial Total') }}</span>
                                <div class="font-bold font-mono text-gray-900 text-lg mt-0.5">
                                    BDT {{ number_format($order->total_amount, 2) }}
                                </div>
                            </div>
                            <div class="p-4 bg-emerald-50/50 rounded-xl border border-emerald-200">
                                <span class="text-2xs font-semibold text-emerald-700 block uppercase tracking-wider">{{ __('Total Paid') }}</span>
                                <div class="font-bold font-mono text-emerald-800 text-lg mt-0.5">
                                    BDT {{ number_format($order->paid_amount, 2) }}
                                </div>
                            </div>
                            <div class="p-4 bg-rose-50/50 rounded-xl border border-rose-200">
                                <span class="text-2xs font-semibold text-rose-700 block uppercase tracking-wider">{{ __('Calculated Due') }}</span>
                                <div class="font-bold font-mono text-rose-600 text-lg mt-0.5">
                                    BDT {{ number_format($order->due_amount, 2) }}
                                </div>
                            </div>
                        </div>

                        @if(!empty($meta['initial_next_due_date']))
                            <div class="p-3 bg-amber-50 rounded-xl border border-amber-200 flex items-center justify-between text-xs">
                                <span class="font-semibold text-amber-900 flex items-center gap-1.5">
                                    <span>📅</span> {{ __('Initial Next Due Pay Date') }}:
                                </span>
                                <strong class="font-mono text-amber-900 font-bold">{{ date('d M, Y', strtotime($meta['initial_next_due_date'])) }}</strong>
                            </div>
                        @endif

                        <!-- Inline New Payment Recording Box -->
                        <div x-show="showPayModal" x-cloak class="p-4 bg-indigo-50/70 rounded-xl border border-indigo-200 shadow-sm space-y-3">
                            <div class="flex items-center justify-between border-b border-indigo-100 pb-2">
                                <span class="text-xs font-bold text-indigo-950 flex items-center gap-1.5">
                                    <span>📥</span> {{ __('Record New Payment Installment') }}
                                </span>
                                <button type="button" @click="showPayModal = false" class="text-gray-400 hover:text-gray-600 text-xs font-bold">&times; Close</button>
                            </div>
                            
                            <form method="POST" action="{{ route('orders.add-payment', $order) }}" class="space-y-3">
                                @csrf
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                    <div>
                                        <label class="block text-2xs font-semibold text-gray-700 uppercase mb-1">{{ __('Payment Date') }} <span class="text-rose-500">*</span></label>
                                        <input type="date" name="payment_date" required value="{{ date('Y-m-d') }}" class="w-full text-xs rounded-lg border-gray-300 focus:ring-indigo-500">
                                    </div>
                                    <div>
                                        <label class="block text-2xs font-semibold text-gray-700 uppercase mb-1">{{ __('Amount (BDT)') }} <span class="text-rose-500">*</span></label>
                                        <input type="number" step="0.01" name="amount" required placeholder="0.00" class="w-full text-xs font-mono font-bold rounded-lg border-gray-300 focus:ring-indigo-500">
                                    </div>
                                    <div>
                                        <label class="block text-2xs font-semibold text-gray-700 uppercase mb-1">{{ __('Payment Source / Method') }} <span class="text-rose-500">*</span></label>
                                        <select name="payment_method" required class="w-full text-xs rounded-lg border-gray-300 focus:ring-indigo-500 font-semibold">
                                            <option value="Cash">💵 Cash</option>
                                            <option value="Bank">🏦 Bank</option>
                                            <option value="bKash">📱 bKash</option>
                                            <option value="Nagad">📱 Nagad</option>
                                            <option value="Rocket">📱 Rocket</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="flex flex-col sm:flex-row items-center justify-between gap-2 pt-1">
                                    <input type="text" name="notes" placeholder="Notes / Description (Optional)" class="w-full sm:w-2/3 text-xs rounded-lg border-gray-300 focus:ring-indigo-500">
                                    <button type="submit" class="w-full sm:w-auto px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-semibold transition">
                                        {{ __('Save Payment') }}
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Payment History Table -->
                        <div class="overflow-x-auto bg-white rounded-xl border border-gray-200">
                            <table class="min-w-full divide-y divide-gray-200 text-xs">
                                <thead class="bg-gray-50/80 text-gray-600 font-semibold uppercase">
                                    <tr>
                                        <th class="py-2.5 px-3 text-left">#</th>
                                        <th class="py-2.5 px-3 text-left">{{ __('Receipt No') }}</th>
                                        <th class="py-2.5 px-3 text-left">{{ __('Payment Date') }}</th>
                                        <th class="py-2.5 px-3 text-left">{{ __('Amount (BDT)') }}</th>
                                        <th class="py-2.5 px-3 text-left">{{ __('Source / Method') }}</th>
                                        <th class="py-2.5 px-3 text-left">{{ __('Received By') }}</th>
                                        <th class="py-2.5 px-3 text-right">{{ __('Receipt') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @forelse($order->clientPayments as $idx => $pmt)
                                        <tr class="hover:bg-gray-50/50 transition">
                                            <td class="py-2.5 px-3 font-mono text-gray-400">{{ $idx + 1 }}</td>
                                            <td class="py-2.5 px-3 font-mono font-bold text-indigo-700">{{ $pmt->payment_no }}</td>
                                            <td class="py-2.5 px-3">{{ $pmt->payment_date?->format('d M, Y') }}</td>
                                            <td class="py-2.5 px-3 font-mono font-bold text-emerald-700">BDT {{ number_format($pmt->amount, 2) }}</td>
                                            <td class="py-2.5 px-3 font-semibold">
                                                @if($pmt->payment_method === 'Cash')
                                                    <span class="inline-flex items-center gap-1 text-emerald-800">💵 Cash</span>
                                                @elseif(str_contains(strtolower($pmt->payment_method), 'bank'))
                                                    <span class="inline-flex items-center gap-1 text-blue-800">🏦 Bank</span>
                                                @else
                                                    <span class="inline-flex items-center gap-1 text-purple-800">📱 {{ $pmt->payment_method }}</span>
                                                @endif
                                            </td>
                                            <td class="py-2.5 px-3 text-gray-600">{{ $pmt->receivedBy->name ?? 'System' }}</td>
                                            <td class="py-2.5 px-3 text-right">
                                                <a href="{{ route('client-payments.show', $pmt) }}" target="_blank" class="p-1 text-indigo-600 hover:underline font-semibold" title="{{ __('View Money Receipt') }}">
                                                    {{ __('Receipt ↗') }}
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="py-6 text-center text-gray-400">
                                                {{ __('No payments recorded yet. Click "+ Record New Payment" above to add one.') }}
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
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
                                @if($order->customer->whatsapp)
                                    <div class="flex items-center gap-2">
                                        <span class="text-emerald-600">💬</span>
                                        <a href="https://wa.me/{{ preg_replace('/\D/', '', $order->customer->whatsapp) }}" target="_blank" class="text-emerald-700 font-mono font-bold hover:underline">
                                            WhatsApp: {{ $order->customer->whatsapp }}
                                        </a>
                                    </div>
                                @endif
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
                                @foreach(['Pending', 'Working', 'Re-working', 'Pused', 'Complete', 'Checked', 'Delivery', 'Active', 'Cancel', 'Delay'] as $st)
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
