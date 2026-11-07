<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight flex items-center gap-2">
                    <svg class="w-7 h-7 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    {{ __('Order Management') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">{{ __('Orders segmented into Creative, Ads Campaign, and Software & Web spreadsheet formats') }}</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('orders.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 shadow-sm transition">
                    <svg class="w-4 h-4 me-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    {{ __('New Order Entry') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="w-full space-y-5">

            <!-- Excel-Format Tab Bar (Creative, Client Ads, Website Order List) -->
            <div class="flex flex-wrap items-center justify-between gap-3 bg-white p-2.5 rounded-2xl border border-gray-200 shadow-sm">
                <div class="flex flex-wrap items-center gap-1.5">
                    @php $currentType = request('type', 'all'); @endphp
                    
                    <a href="{{ route('orders.index', array_merge(request()->except('type', 'page'), ['type' => 'all'])) }}"
                       class="px-4 py-2 rounded-xl text-xs font-bold transition flex items-center gap-2 {{ $currentType === 'all' ? 'bg-gray-900 text-white shadow-xs' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        <span>📋</span>
                        <span>{{ __('All Orders') }}</span>
                        <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ $currentType === 'all' ? 'bg-gray-700 text-white' : 'bg-gray-200 text-gray-700' }}">{{ $metrics['total'] }}</span>
                    </a>

                    <a href="{{ route('orders.index', array_merge(request()->except('type', 'page'), ['type' => 'creative'])) }}"
                       class="px-4 py-2 rounded-xl text-xs font-bold transition flex items-center gap-2 {{ $currentType === 'creative' ? 'bg-indigo-600 text-white shadow-xs' : 'bg-indigo-50 text-indigo-700 hover:bg-indigo-100' }}">
                        <span>🎨</span>
                        <span>{{ __('Creative Orders') }}</span>
                        <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ $currentType === 'creative' ? 'bg-indigo-800 text-white' : 'bg-indigo-200 text-indigo-800' }}">{{ $metrics['creative_count'] ?? 0 }}</span>
                    </a>

                    <a href="{{ route('orders.index', array_merge(request()->except('type', 'page'), ['type' => 'ads'])) }}"
                       class="px-4 py-2 rounded-xl text-xs font-bold transition flex items-center gap-2 {{ $currentType === 'ads' ? 'bg-amber-600 text-white shadow-xs' : 'bg-amber-50 text-amber-800 hover:bg-amber-100' }}">
                        <span>📈</span>
                        <span>{{ __('Ads Campaign Orders') }}</span>
                        <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ $currentType === 'ads' ? 'bg-amber-800 text-white' : 'bg-amber-200 text-amber-900' }}">{{ $metrics['ads_count'] ?? 0 }}</span>
                    </a>

                    <a href="{{ route('orders.index', array_merge(request()->except('type', 'page'), ['type' => 'web_software'])) }}"
                       class="px-4 py-2 rounded-xl text-xs font-bold transition flex items-center gap-2 {{ $currentType === 'web_software' ? 'bg-blue-600 text-white shadow-xs' : 'bg-blue-50 text-blue-800 hover:bg-blue-100' }}">
                        <span>💻</span>
                        <span>{{ __('Software & Website Orders') }}</span>
                        <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ $currentType === 'web_software' ? 'bg-blue-800 text-white' : 'bg-blue-200 text-blue-900' }}">{{ $metrics['soft_count'] ?? 0 }}</span>
                    </a>
                </div>

                <div class="text-xs text-gray-500 font-semibold px-2">
                    {{ __('Showing') }} <span class="font-bold text-gray-900">{{ $orders->total() }}</span> {{ __('orders') }}
                </div>
            </div>

            <!-- Order Metrics -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                <div class="bg-white p-4 rounded-xl border border-blue-200 bg-blue-50/30 shadow-sm text-center">
                    <div class="text-xs font-semibold text-blue-600 uppercase">{{ __('Active Orders') }}</div>
                    <div class="text-2xl font-bold text-blue-700 mt-1">{{ $metrics['active'] }}</div>
                </div>

                <div class="bg-white p-4 rounded-xl border border-emerald-200 bg-emerald-50/30 shadow-sm text-center">
                    <div class="text-xs font-semibold text-emerald-600 uppercase">{{ __('Delivered / Completed') }}</div>
                    <div class="text-2xl font-bold text-emerald-700 mt-1">{{ $metrics['completed'] }}</div>
                </div>

                <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm text-center">
                    <div class="text-xs font-semibold text-indigo-700 uppercase">{{ __('Total Sales Value') }}</div>
                    <div class="text-xl font-bold text-indigo-800 mt-1">{{ __('BDT') }} {{ number_format($metrics['total_sales']) }}</div>
                </div>

                <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm text-center">
                    <div class="text-xs font-semibold text-gray-600 uppercase">{{ __('Overall Total Due') }}</div>
                    <div class="text-xl font-bold text-gray-900 mt-1">{{ __('BDT') }} {{ number_format($metrics['initial_due'] + $metrics['monthly_due'] + $metrics['yearly_due']) }}</div>
                </div>
            </div>

            <!-- Due Payment Breakdown: Initial Due, Monthly Due & Yearly Due -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <!-- Initial Due Card -->
                <a href="{{ route('orders.index', array_merge(request()->except('due_type', 'page'), ['due_type' => 'initial'])) }}"
                   class="bg-white p-4 rounded-2xl border transition shadow-sm hover:shadow-md {{ request('due_type') === 'initial' ? 'border-rose-500 ring-2 ring-rose-500/20 bg-rose-50/30' : 'border-rose-200 bg-rose-50/20 hover:border-rose-300' }}">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-1.5 text-xs font-bold text-rose-800 uppercase tracking-wider">
                            <span>💳</span>
                            <span>{{ __('Initial Due') }}</span>
                        </div>
                        <span class="text-2xs font-bold px-2 py-0.5 rounded-full bg-rose-100 text-rose-700 uppercase tracking-wider font-mono">{{ __('Project Due') }}</span>
                    </div>
                    <div class="text-2xl font-black font-mono text-rose-600 mt-2">
                        BDT {{ number_format($metrics['initial_due'], 2) }}
                    </div>
                    <div class="text-2xs text-rose-600/80 mt-1 font-medium flex items-center justify-between">
                        <span>{{ __('Contract & delivery remaining due') }}</span>
                        <span class="text-rose-600 font-bold">&rarr;</span>
                    </div>
                </a>

                <!-- Monthly Due Card -->
                <a href="{{ route('orders.index', array_merge(request()->except('due_type', 'page'), ['due_type' => 'monthly'])) }}"
                   class="bg-white p-4 rounded-2xl border transition shadow-sm hover:shadow-md {{ request('due_type') === 'monthly' ? 'border-amber-500 ring-2 ring-amber-500/20 bg-amber-50/30' : 'border-amber-200 bg-amber-50/20 hover:border-amber-300' }}">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-1.5 text-xs font-bold text-amber-800 uppercase tracking-wider">
                            <span>🗓️</span>
                            <span>{{ __('Monthly Due') }}</span>
                        </div>
                        <span class="text-2xs font-bold px-2 py-0.5 rounded-full bg-amber-100 text-amber-800 uppercase tracking-wider font-mono">{{ __('Monthly Service') }}</span>
                    </div>
                    <div class="text-2xl font-black font-mono text-amber-600 mt-2">
                        BDT {{ number_format($metrics['monthly_due'], 2) }}
                    </div>
                    <div class="text-2xs text-amber-600/80 mt-1 font-medium flex items-center justify-between">
                        <span>{{ __('Ads retainer, software & maintenance due') }}</span>
                        <span class="text-amber-700 font-bold">&rarr;</span>
                    </div>
                </a>

                <!-- Yearly Due Card -->
                <a href="{{ route('orders.index', array_merge(request()->except('due_type', 'page'), ['due_type' => 'yearly'])) }}"
                   class="bg-white p-4 rounded-2xl border transition shadow-sm hover:shadow-md {{ request('due_type') === 'yearly' ? 'border-purple-500 ring-2 ring-purple-500/20 bg-purple-50/30' : 'border-purple-200 bg-purple-50/20 hover:border-purple-300' }}">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-1.5 text-xs font-bold text-purple-800 uppercase tracking-wider">
                            <span>📅</span>
                            <span>{{ __('Yearly Due') }}</span>
                        </div>
                        <span class="text-2xs font-bold px-2 py-0.5 rounded-full bg-purple-100 text-purple-800 uppercase tracking-wider font-mono">{{ __('Renewal Fee') }}</span>
                    </div>
                    <div class="text-2xl font-black font-mono text-purple-600 mt-2">
                        BDT {{ number_format($metrics['yearly_due'], 2) }}
                    </div>
                    <div class="text-2xs text-purple-600/80 mt-1 font-medium flex items-center justify-between">
                        <span>{{ __('Domain, hosting & annual license due') }}</span>
                        <span class="text-purple-700 font-bold">&rarr;</span>
                    </div>
                </a>
            </div>

            <!-- Filters & Search -->
            <div class="bg-white p-4 rounded-2xl border border-gray-200 shadow-sm">
                <form method="GET" action="{{ route('orders.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 items-end">
                    <input type="hidden" name="type" value="{{ $currentType }}">

                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">{{ __('Search (Order / Client / WhatsApp)') }}</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('Order code, client, WhatsApp, title...') }}"
                            class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 font-mono">
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">{{ __('Service Category') }}</label>
                        <select name="category" class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">{{ __('All Categories') }}</option>
                            @foreach($serviceCategories as $cat)
                                <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ __($cat) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">{{ __('Due Payment Filter') }}</label>
                        <select name="due_type" class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 font-semibold">
                            <option value="">{{ __('All Due Statuses') }}</option>
                            <option value="initial" {{ request('due_type') === 'initial' ? 'selected' : '' }}>💳 Initial Due Only</option>
                            <option value="monthly" {{ request('due_type') === 'monthly' ? 'selected' : '' }}>🗓️ Monthly Due Only</option>
                            <option value="yearly" {{ request('due_type') === 'yearly' ? 'selected' : '' }}>📅 Yearly Due Only</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">{{ __('Order Status') }}</label>
                        <select name="status" class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">{{ __('All Statuses') }}</option>
                            @foreach(['Pending', 'Working', 'Re-working', 'Pused', 'Complete', 'Checked', 'Delivery', 'Active', 'Cancel', 'Delay'] as $st)
                                <option value="{{ $st }}" {{ request('status') == $st ? 'selected' : '' }}>{{ __($st) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex items-center gap-2">
                        <button type="submit" class="flex-1 py-2 px-4 bg-gray-900 text-white rounded-lg text-sm font-semibold hover:bg-gray-800 transition">
                            {{ __('Filter') }}
                        </button>
                        @if(request()->anyFilled(['search', 'category', 'status', 'due_type']))
                            <a href="{{ route('orders.index', ['type' => $currentType]) }}" class="py-2 px-3 bg-gray-100 text-gray-600 rounded-lg text-sm font-medium hover:bg-gray-200 transition">
                                {{ __('Reset') }}
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- ============================================== -->
            <!-- DYNAMIC ORDERS TABLE (Matching Excel Formats)  -->
            <!-- ============================================== -->
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    
                    @if($currentType === 'creative')
                        <!-- 🎨 CREATIVE ORDERS TABLE (Excel Sheet Creative format) -->
                        <table class="min-w-full divide-y divide-gray-200 text-left text-xs">
                            <thead class="bg-indigo-50/70 text-indigo-900 font-bold uppercase tracking-wider">
                                <tr>
                                    <th class="py-3 px-3">{{ __('Order Code & Date') }}</th>
                                    <th class="py-3 px-3">{{ __('Client & WhatsApp') }}</th>
                                    <th class="py-3 px-3">{{ __('Service & Details') }}</th>
                                    <th class="py-3 px-3">{{ __('Script Code / Idea') }}</th>
                                    <th class="py-3 px-3">{{ __('Voice / Seconds') }}</th>
                                    <th class="py-3 px-3">{{ __('Initial Total') }}</th>
                                    <th class="py-3 px-3">{{ __('1st / 2nd / 3rd Pay') }}</th>
                                    <th class="py-3 px-3">{{ __('Due (BDT)') }}</th>
                                    <th class="py-3 px-3">{{ __('Working Status') }}</th>
                                    <th class="py-3 px-3 text-right">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($orders as $order)
                                    @php $meta = $order->meta ?? []; @endphp
                                    <tr class="hover:bg-indigo-50/20 transition">
                                        <td class="py-3 px-3 whitespace-nowrap">
                                            <a href="{{ route('orders.show', $order) }}" class="font-bold font-mono text-indigo-600 hover:underline">
                                                {{ $order->order_code }}
                                            </a>
                                            <div class="text-2xs text-gray-400">{{ $order->order_date?->format('d M, Y') }}</div>
                                        </td>
                                        <td class="py-3 px-3 whitespace-nowrap">
                                            <div class="font-bold text-gray-900">{{ $order->customer->name ?? '-' }}</div>
                                            <div class="text-2xs font-mono text-emerald-700 flex items-center gap-1 mt-0.5">
                                                <span>💬</span>
                                                <a href="https://wa.me/{{ preg_replace('/\D/', '', $order->customer->whatsapp ?? $order->customer->phone ?? '') }}" target="_blank" class="hover:underline">
                                                    {{ $order->customer->whatsapp ?? $order->customer->phone ?? '-' }}
                                                </a>
                                            </div>
                                        </td>
                                        <td class="py-3 px-3">
                                            <div class="font-semibold text-gray-900">{{ $order->service_category }}</div>
                                            <div class="text-2xs text-gray-500 truncate max-w-[150px]">{{ $order->title }}</div>
                                            @if(!empty($meta['service_provider']))
                                                <div class="text-[10px] text-indigo-600 font-semibold mt-0.5">👤 {{ $meta['service_provider'] }}</div>
                                            @endif
                                        </td>
                                        <td class="py-3 px-3 whitespace-nowrap">
                                            <div class="font-mono font-bold text-indigo-700">{{ $meta['script_code'] ?? '-' }}</div>
                                            <div class="text-2xs text-gray-500">{{ $meta['script_idea'] ?? '-' }}</div>
                                        </td>
                                        <td class="py-3 px-3 whitespace-nowrap">
                                            <div class="font-semibold text-gray-800">{{ $meta['second'] ? $meta['second'] . 's' : '-' }}</div>
                                            @if(!empty($meta['voice_link']))
                                                <a href="{{ $meta['voice_link'] }}" target="_blank" class="text-2xs text-indigo-600 hover:underline flex items-center gap-0.5 mt-0.5">
                                                    🎙️ Voice Link ↗
                                                </a>
                                            @endif
                                        </td>
                                        <td class="py-3 px-3 whitespace-nowrap font-mono font-bold text-gray-900">
                                            {{ number_format($order->total_amount) }}
                                        </td>
                                        <td class="py-3 px-3 whitespace-nowrap text-2xs font-mono">
                                            <div>1st: {{ number_format(floatval($meta['pay_1st'] ?? 0)) }}</div>
                                            <div class="text-gray-500">2nd: {{ number_format(floatval($meta['pay_2nd'] ?? 0)) }} | 3rd: {{ number_format(floatval($meta['pay_3rd'] ?? 0)) }}</div>
                                        </td>
                                        <td class="py-3 px-3 whitespace-nowrap font-mono font-bold {{ $order->due_amount > 0 ? 'text-rose-600' : 'text-emerald-600' }}">
                                            {{ number_format($order->due_amount) }}
                                        </td>
                                        <td class="py-3 px-3 whitespace-nowrap">
                                            <span class="px-2 py-0.5 rounded-full text-2xs font-bold {{ $order->status === 'Delivered' || $order->status === 'Completed' ? 'bg-emerald-100 text-emerald-800' : 'bg-indigo-100 text-indigo-800' }}">
                                                {{ $order->status }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-3 text-right whitespace-nowrap">
                                            <div class="relative inline-block text-left" x-data="{ open: false }" @click.outside="open = false">
                                                <button @click="open = !open" type="button" class="p-1.5 text-gray-500 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition focus:outline-none" title="{{ __('Actions') }}">
                                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                                        <circle cx="12" cy="5" r="1.75" />
                                                        <circle cx="12" cy="12" r="1.75" />
                                                        <circle cx="12" cy="19" r="1.75" />
                                                    </svg>
                                                </button>

                                                <!-- Dropdown Menu -->
                                                <div x-show="open"
                                                     x-cloak
                                                     x-transition:enter="transition ease-out duration-100"
                                                     x-transition:enter-start="transform opacity-0 scale-95"
                                                     x-transition:enter-end="transform opacity-100 scale-100"
                                                     x-transition:leave="transition ease-in duration-75"
                                                     x-transition:leave-start="transform opacity-100 scale-100"
                                                     x-transition:leave-end="transform opacity-0 scale-95"
                                                     class="absolute right-0 z-50 mt-1 w-44 origin-top-right rounded-xl bg-white p-1.5 shadow-xl ring-1 ring-slate-900/10 focus:outline-none text-left"
                                                     style="display: none;">
                                                    
                                                    <a href="{{ route('orders.show', $order) }}" class="flex items-center gap-2 px-3 py-2 text-xs font-semibold text-gray-700 hover:bg-indigo-50 hover:text-indigo-700 rounded-lg transition">
                                                        <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                        <span>{{ __('View Details') }}</span>
                                                    </a>

                                                    <a href="{{ route('orders.edit', $order) }}" class="flex items-center gap-2 px-3 py-2 text-xs font-semibold text-gray-700 hover:bg-amber-50 hover:text-amber-700 rounded-lg transition">
                                                        <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                        <span>{{ __('Edit Order') }}</span>
                                                    </a>

                                                    @if($order->project)
                                                        <a href="{{ route('projects.show', $order->project) }}" class="flex items-center gap-2 px-3 py-2 text-xs font-semibold text-indigo-700 hover:bg-indigo-50 rounded-lg transition">
                                                            <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                                            <span>{{ __('Project Workspace') }}</span>
                                                        </a>
                                                    @endif

                                                    <div class="my-1 border-t border-gray-100"></div>

                                                    <form method="POST" action="{{ route('orders.destroy', $order) }}" onsubmit="return confirm('{{ __('Are you sure you want to delete this order?') }}');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="w-full flex items-center gap-2 px-3 py-2 text-xs font-semibold text-rose-600 hover:bg-rose-50 rounded-lg transition text-left">
                                                            <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                            <span>{{ __('Delete') }}</span>
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center py-8 text-gray-400">{{ __('No Creative Orders found.') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                    @elseif($currentType === 'ads')
                        <!-- 📈 ADS CAMPAIGN ORDERS TABLE (Excel Sheet Ads Campaign format) -->
                        <table class="min-w-full divide-y divide-gray-200 text-left text-xs">
                            <thead class="bg-amber-50/70 text-amber-900 font-bold uppercase tracking-wider">
                                <tr>
                                    <th class="py-3 px-3">{{ __('Order Code & Date') }}</th>
                                    <th class="py-3 px-3">{{ __('Business & WhatsApp') }}</th>
                                    <th class="py-3 px-3">{{ __('Ads Post Link') }}</th>
                                    <th class="py-3 px-3">{{ __('Days & Budget') }}</th>
                                    <th class="py-3 px-3">{{ __('Dollar Budget ($)') }}</th>
                                    <th class="py-3 px-3">{{ __('Total BDT') }}</th>
                                    <th class="py-3 px-3">{{ __('Paid / Due (BDT)') }}</th>
                                    <th class="py-3 px-3">{{ __('Dollar Due ($)') }}</th>
                                    <th class="py-3 px-3">{{ __('Today Check') }}</th>
                                    <th class="py-3 px-3 text-right">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($orders as $order)
                                    @php $meta = $order->meta ?? []; @endphp
                                    <tr class="hover:bg-amber-50/20 transition">
                                        <td class="py-3 px-3 whitespace-nowrap">
                                            <a href="{{ route('orders.show', $order) }}" class="font-bold font-mono text-amber-700 hover:underline">
                                                {{ $order->order_code }}
                                            </a>
                                            <div class="text-2xs text-gray-400">{{ $order->order_date?->format('d M, Y') }}</div>
                                        </td>
                                        <td class="py-3 px-3 whitespace-nowrap">
                                            <div class="font-bold text-gray-900">{{ $order->customer->company_name ?? $order->customer->name ?? '-' }}</div>
                                            <div class="text-2xs font-mono text-emerald-700 flex items-center gap-1 mt-0.5">
                                                <span>💬</span>
                                                <a href="https://wa.me/{{ preg_replace('/\D/', '', $order->customer->whatsapp ?? $order->customer->phone ?? '') }}" target="_blank" class="hover:underline">
                                                    {{ $order->customer->whatsapp ?? $order->customer->phone ?? '-' }}
                                                </a>
                                            </div>
                                        </td>
                                        <td class="py-3 px-3">
                                            @if(!empty($meta['ads_post_link']))
                                                <a href="{{ $meta['ads_post_link'] }}" target="_blank" class="text-indigo-600 hover:underline text-2xs font-medium block truncate max-w-[160px]">
                                                    🔗 {{ $meta['ads_post_link'] }}
                                                </a>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                        <td class="py-3 px-3 whitespace-nowrap font-mono">
                                            <div class="font-semibold">{{ $meta['total_days'] ?? '-' }} days</div>
                                            @if(!empty($meta['lifetime_daily_budget']))
                                                <div class="text-2xs text-amber-700 font-bold">${{ number_format(floatval($meta['lifetime_daily_budget']), 2) }}</div>
                                            @elseif(!empty($meta['daily_cost']))
                                                <div class="text-2xs text-gray-500">${{ number_format(floatval($meta['daily_cost']), 2) }}/day</div>
                                            @endif
                                        </td>
                                        <td class="py-3 px-3 whitespace-nowrap font-mono font-bold text-amber-800">
                                            ${{ number_format(floatval($meta['total_dollar'] ?? 0), 2) }}
                                            @if(!empty($meta['lifetime_daily_budget']))
                                                <div class="text-[10px] text-gray-500 font-normal">Budget: ${{ number_format(floatval($meta['lifetime_daily_budget']), 2) }}</div>
                                            @elseif(!empty($meta['lifetime_budget']))
                                                <div class="text-[10px] text-gray-500 font-normal">Life: ${{ number_format(floatval($meta['lifetime_budget']), 2) }}</div>
                                            @endif
                                        </td>
                                        <td class="py-3 px-3 whitespace-nowrap font-mono font-bold text-gray-900">
                                            {{ number_format($order->total_amount) }}
                                        </td>
                                        <td class="py-3 px-3 whitespace-nowrap text-2xs font-mono">
                                            <div class="text-emerald-700 font-semibold">Paid: {{ number_format($order->paid_amount) }}</div>
                                            <div class="text-rose-600 font-bold">Due: {{ number_format($order->due_amount) }}</div>
                                        </td>
                                        <td class="py-3 px-3 whitespace-nowrap font-mono font-bold text-rose-600">
                                            ${{ number_format(floatval($meta['dollar_due'] ?? 0), 2) }}
                                        </td>
                                        <td class="py-3 px-3 whitespace-nowrap">
                                            @if(($meta['today_check'] ?? '') === 'Checked')
                                                <span class="px-2 py-0.5 rounded-full text-2xs font-bold bg-emerald-100 text-emerald-800">✅ Checked</span>
                                            @else
                                                <span class="px-2 py-0.5 rounded-full text-2xs font-bold bg-amber-100 text-amber-800">⏳ Pending</span>
                                            @endif
                                        </td>
                                        <td class="py-3 px-3 text-right whitespace-nowrap">
                                            <div class="relative inline-block text-left" x-data="{ open: false }" @click.outside="open = false">
                                                <button @click="open = !open" type="button" class="p-1.5 text-gray-500 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition focus:outline-none" title="{{ __('Actions') }}">
                                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                                        <circle cx="12" cy="5" r="1.75" />
                                                        <circle cx="12" cy="12" r="1.75" />
                                                        <circle cx="12" cy="19" r="1.75" />
                                                    </svg>
                                                </button>

                                                <!-- Dropdown Menu -->
                                                <div x-show="open"
                                                     x-cloak
                                                     x-transition:enter="transition ease-out duration-100"
                                                     x-transition:enter-start="transform opacity-0 scale-95"
                                                     x-transition:enter-end="transform opacity-100 scale-100"
                                                     x-transition:leave="transition ease-in duration-75"
                                                     x-transition:leave-start="transform opacity-100 scale-100"
                                                     x-transition:leave-end="transform opacity-0 scale-95"
                                                     class="absolute right-0 z-50 mt-1 w-44 origin-top-right rounded-xl bg-white p-1.5 shadow-xl ring-1 ring-slate-900/10 focus:outline-none text-left"
                                                     style="display: none;">
                                                    
                                                    <a href="{{ route('orders.show', $order) }}" class="flex items-center gap-2 px-3 py-2 text-xs font-semibold text-gray-700 hover:bg-indigo-50 hover:text-indigo-700 rounded-lg transition">
                                                        <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                        <span>{{ __('View Details') }}</span>
                                                    </a>

                                                    <a href="{{ route('orders.edit', $order) }}" class="flex items-center gap-2 px-3 py-2 text-xs font-semibold text-gray-700 hover:bg-amber-50 hover:text-amber-700 rounded-lg transition">
                                                        <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                        <span>{{ __('Edit Order') }}</span>
                                                    </a>

                                                    @if($order->project)
                                                        <a href="{{ route('projects.show', $order->project) }}" class="flex items-center gap-2 px-3 py-2 text-xs font-semibold text-indigo-700 hover:bg-indigo-50 rounded-lg transition">
                                                            <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                                            <span>{{ __('Project Workspace') }}</span>
                                                        </a>
                                                    @endif

                                                    <div class="my-1 border-t border-gray-100"></div>

                                                    <form method="POST" action="{{ route('orders.destroy', $order) }}" onsubmit="return confirm('{{ __('Are you sure you want to delete this order?') }}');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="w-full flex items-center gap-2 px-3 py-2 text-xs font-semibold text-rose-600 hover:bg-rose-50 rounded-lg transition text-left">
                                                            <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                            <span>{{ __('Delete') }}</span>
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center py-8 text-gray-400">{{ __('No Ads Campaign Orders found.') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                    @elseif($currentType === 'web_software')
                        <!-- 💻 SOFTWARE & WEBSITE ORDERS TABLE (Excel Sheet Soft & Web format) -->
                        <table class="min-w-full divide-y divide-gray-200 text-left text-xs">
                            <thead class="bg-blue-50/70 text-blue-900 font-bold uppercase tracking-wider">
                                <tr>
                                    <th class="py-3 px-3">{{ __('Order Code & Date') }}</th>
                                    <th class="py-3 px-3">{{ __('Business & WhatsApp') }}</th>
                                    <th class="py-3 px-3">{{ __('Domain / Link') }}</th>
                                    <th class="py-3 px-3">{{ __('Subscription / Fee') }}</th>
                                    <th class="py-3 px-3">{{ __('Initial Total') }}</th>
                                    <th class="py-3 px-3">{{ __('Installments (1st-7th)') }}</th>
                                    <th class="py-3 px-3">{{ __('Total Due') }}</th>
                                    <th class="py-3 px-3">{{ __('Credentials') }}</th>
                                    <th class="py-3 px-3 text-right">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($orders as $order)
                                    @php $meta = $order->meta ?? []; @endphp
                                    <tr class="hover:bg-blue-50/20 transition">
                                        <td class="py-3 px-3 whitespace-nowrap">
                                            <a href="{{ route('orders.show', $order) }}" class="font-bold font-mono text-blue-700 hover:underline">
                                                {{ $order->order_code }}
                                            </a>
                                            <div class="text-2xs text-gray-400">{{ $order->order_date?->format('d M, Y') }}</div>
                                        </td>
                                        <td class="py-3 px-3 whitespace-nowrap">
                                            <div class="font-bold text-gray-900">{{ $order->customer->company_name ?? $order->customer->name ?? '-' }}</div>
                                            <div class="text-2xs font-mono text-emerald-700 flex items-center gap-1 mt-0.5">
                                                <span>💬</span>
                                                <a href="https://wa.me/{{ preg_replace('/\D/', '', $order->customer->whatsapp ?? $order->customer->phone ?? '') }}" target="_blank" class="hover:underline">
                                                    {{ $order->customer->whatsapp ?? $order->customer->phone ?? '-' }}
                                                </a>
                                            </div>
                                        </td>
                                        <td class="py-3 px-3">
                                            @if(!empty($meta['domain']))
                                                <a href="{{ $meta['domain'] }}" target="_blank" class="text-indigo-600 hover:underline text-2xs font-mono font-semibold block truncate max-w-[150px]">
                                                    🌐 {{ $meta['domain'] }}
                                                </a>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                        <td class="py-3 px-3 whitespace-nowrap">
                                            <div class="font-semibold text-gray-900">{{ $meta['subscription_system'] ?? 'None' }}</div>
                                            @if(!empty($meta['subscription_fee']))
                                                <div class="text-2xs font-mono text-blue-700 font-bold">BDT {{ number_format(floatval($meta['subscription_fee'])) }}</div>
                                            @endif
                                        </td>
                                        <td class="py-3 px-3 whitespace-nowrap font-mono font-bold text-gray-900">
                                            {{ number_format($order->total_amount) }}
                                        </td>
                                        <td class="py-3 px-3 whitespace-nowrap text-2xs font-mono text-emerald-700 font-semibold">
                                            Paid: BDT {{ number_format($order->paid_amount) }}
                                        </td>
                                        <td class="py-3 px-3 whitespace-nowrap font-mono font-bold {{ $order->due_amount > 0 ? 'text-rose-600' : 'text-emerald-600' }}">
                                            BDT {{ number_format($order->due_amount) }}
                                        </td>
                                        <td class="py-3 px-3 whitespace-nowrap">
                                            @if(!empty($meta['client_admin_link']) || !empty($meta['official_admin_link']))
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-gray-100 text-gray-700 border border-gray-200">
                                                    🔐 Credentials Stored
                                                </span>
                                            @else
                                                <span class="text-gray-400 text-2xs">-</span>
                                            @endif
                                        </td>
                                        <td class="py-3 px-3 text-right whitespace-nowrap">
                                            <div class="relative inline-block text-left" x-data="{ open: false }" @click.outside="open = false">
                                                <button @click="open = !open" type="button" class="p-1.5 text-gray-500 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition focus:outline-none" title="{{ __('Actions') }}">
                                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                                        <circle cx="12" cy="5" r="1.75" />
                                                        <circle cx="12" cy="12" r="1.75" />
                                                        <circle cx="12" cy="19" r="1.75" />
                                                    </svg>
                                                </button>

                                                <!-- Dropdown Menu -->
                                                <div x-show="open"
                                                     x-cloak
                                                     x-transition:enter="transition ease-out duration-100"
                                                     x-transition:enter-start="transform opacity-0 scale-95"
                                                     x-transition:enter-end="transform opacity-100 scale-100"
                                                     x-transition:leave="transition ease-in duration-75"
                                                     x-transition:leave-start="transform opacity-100 scale-100"
                                                     x-transition:leave-end="transform opacity-0 scale-95"
                                                     class="absolute right-0 z-50 mt-1 w-44 origin-top-right rounded-xl bg-white p-1.5 shadow-xl ring-1 ring-slate-900/10 focus:outline-none text-left"
                                                     style="display: none;">
                                                    
                                                    <a href="{{ route('orders.show', $order) }}" class="flex items-center gap-2 px-3 py-2 text-xs font-semibold text-gray-700 hover:bg-indigo-50 hover:text-indigo-700 rounded-lg transition">
                                                        <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                        <span>{{ __('View Details') }}</span>
                                                    </a>

                                                    <a href="{{ route('orders.edit', $order) }}" class="flex items-center gap-2 px-3 py-2 text-xs font-semibold text-gray-700 hover:bg-amber-50 hover:text-amber-700 rounded-lg transition">
                                                        <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                        <span>{{ __('Edit Order') }}</span>
                                                    </a>

                                                    @if($order->project)
                                                        <a href="{{ route('projects.show', $order->project) }}" class="flex items-center gap-2 px-3 py-2 text-xs font-semibold text-indigo-700 hover:bg-indigo-50 rounded-lg transition">
                                                            <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                                            <span>{{ __('Project Workspace') }}</span>
                                                        </a>
                                                    @endif

                                                    <div class="my-1 border-t border-gray-100"></div>

                                                    <form method="POST" action="{{ route('orders.destroy', $order) }}" onsubmit="return confirm('{{ __('Are you sure you want to delete this order?') }}');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="w-full flex items-center gap-2 px-3 py-2 text-xs font-semibold text-rose-600 hover:bg-rose-50 rounded-lg transition text-left">
                                                            <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                            <span>{{ __('Delete') }}</span>
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-8 text-gray-400">{{ __('No Software or Website Orders found.') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                    @else
                        <!-- 📋 ALL ORDERS STANDARD OVERVIEW TABLE -->
                        <table class="min-w-full divide-y divide-gray-200 text-left text-xs">
                            <thead class="bg-gray-50 text-gray-500 font-semibold uppercase">
                                <tr>
                                    <th class="py-3.5 px-4">{{ __('Order Code') }}</th>
                                    <th class="py-3.5 px-4">{{ __('Order Name & Category') }}</th>
                                    <th class="py-3.5 px-4">{{ __('Client & WhatsApp') }}</th>
                                    <th class="py-3.5 px-4">{{ __('Price & Payment (BDT)') }}</th>
                                    <th class="py-3.5 px-4">{{ __('Project Workspace') }}</th>
                                    <th class="py-3.5 px-4">{{ __('Status') }}</th>
                                    <th class="py-3.5 px-4 text-right">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($orders as $order)
                                    <tr class="hover:bg-gray-50/60 transition">
                                        <td class="py-3.5 px-4 whitespace-nowrap">
                                            <a href="{{ route('orders.show', $order) }}" class="font-bold text-indigo-600 hover:text-indigo-800">
                                                {{ $order->order_code }}
                                            </a>
                                            <div class="text-2xs text-gray-400">{{ $order->order_date ? $order->order_date->format('d M, Y') : '' }}</div>
                                        </td>
                                        <td class="py-3.5 px-4">
                                            <div class="font-semibold text-gray-800">{{ $order->title }}</div>
                                            <div class="flex items-center gap-1 mt-0.5">
                                                <span class="px-2 py-0.5 bg-gray-100 text-gray-600 text-2xs rounded-md">
                                                    {{ __($order->service_category) }}
                                                </span>
                                                @if($order->order_type && $order->order_type !== 'standard')
                                                    <span class="px-1.5 py-0.2 rounded text-[10px] font-bold uppercase {{ $order->order_type === 'creative' ? 'bg-indigo-100 text-indigo-800' : ($order->order_type === 'ads' ? 'bg-amber-100 text-amber-800' : 'bg-blue-100 text-blue-800') }}">
                                                        {{ $order->order_type }}
                                                    </span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="py-3.5 px-4 whitespace-nowrap">
                                            @if($order->customer)
                                                <a href="{{ route('customers.show', $order->customer) }}" class="font-medium text-gray-900 hover:text-indigo-600">
                                                    {{ $order->customer->name }}
                                                </a>
                                                <div class="text-2xs font-mono text-emerald-700 flex items-center gap-1 mt-0.5">
                                                    <span>💬</span>
                                                    <a href="https://wa.me/{{ preg_replace('/\D/', '', $order->customer->whatsapp ?? $order->customer->phone ?? '') }}" target="_blank" class="hover:underline">
                                                        {{ $order->customer->whatsapp ?? $order->customer->phone ?? '-' }}
                                                    </a>
                                                </div>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                        <td class="py-3.5 px-4 whitespace-nowrap font-mono">
                                            <div class="font-bold text-gray-900">{{ __('BDT') }} {{ number_format($order->total_amount, 2) }}</div>
                                            <div class="text-2xs text-emerald-600">{{ __('Paid') }}: {{ __('BDT') }} {{ number_format($order->paid_amount, 2) }}</div>
                                            @if($order->due_amount > 0)
                                                <div class="text-2xs text-rose-600 font-bold">{{ __('Due') }}: {{ __('BDT') }} {{ number_format($order->due_amount, 2) }}</div>
                                            @else
                                                <div class="text-2xs text-emerald-600 font-bold">{{ __('Paid in Full') }}</div>
                                            @endif
                                        </td>
                                        <td class="py-3.5 px-4 whitespace-nowrap">
                                            @if($order->project)
                                                <a href="{{ route('projects.show', $order->project) }}" class="inline-flex items-center gap-1 text-xs font-semibold text-indigo-600 hover:text-indigo-800 bg-indigo-50 px-2.5 py-1 rounded-lg border border-indigo-200">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                                    {{ $order->project->project_code }} ({{ $order->project->progress_percent }}%)
                                                </a>
                                            @else
                                                <form method="POST" action="{{ route('orders.initialize-project', $order) }}">
                                                    @csrf
                                                    <button type="submit" class="text-xs font-medium text-emerald-700 bg-emerald-50 border border-emerald-300 hover:bg-emerald-100 px-2 py-1 rounded-md transition flex items-center gap-1">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                                        {{ __('Start Project') }}
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                        <td class="py-3.5 px-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $order->status === 'Delivered' || $order->status === 'Completed' ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-100 text-gray-800' }}">
                                                {{ __($order->status) }}
                                            </span>
                                        </td>
                                        <td class="py-3.5 px-4 text-right whitespace-nowrap">
                                            <div class="relative inline-block text-left" x-data="{ open: false }" @click.outside="open = false">
                                                <button @click="open = !open" type="button" class="p-1.5 text-gray-500 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition focus:outline-none" title="{{ __('Actions') }}">
                                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                                        <circle cx="12" cy="5" r="1.75" />
                                                        <circle cx="12" cy="12" r="1.75" />
                                                        <circle cx="12" cy="19" r="1.75" />
                                                    </svg>
                                                </button>

                                                <!-- Dropdown Menu -->
                                                <div x-show="open"
                                                     x-cloak
                                                     x-transition:enter="transition ease-out duration-100"
                                                     x-transition:enter-start="transform opacity-0 scale-95"
                                                     x-transition:enter-end="transform opacity-100 scale-100"
                                                     x-transition:leave="transition ease-in duration-75"
                                                     x-transition:leave-start="transform opacity-100 scale-100"
                                                     x-transition:leave-end="transform opacity-0 scale-95"
                                                     class="absolute right-0 z-50 mt-1 w-44 origin-top-right rounded-xl bg-white p-1.5 shadow-xl ring-1 ring-slate-900/10 focus:outline-none text-left"
                                                     style="display: none;">
                                                    
                                                    <a href="{{ route('orders.show', $order) }}" class="flex items-center gap-2 px-3 py-2 text-xs font-semibold text-gray-700 hover:bg-indigo-50 hover:text-indigo-700 rounded-lg transition">
                                                        <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                        <span>{{ __('View Details') }}</span>
                                                    </a>

                                                    <a href="{{ route('orders.edit', $order) }}" class="flex items-center gap-2 px-3 py-2 text-xs font-semibold text-gray-700 hover:bg-amber-50 hover:text-amber-700 rounded-lg transition">
                                                        <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                        <span>{{ __('Edit Order') }}</span>
                                                    </a>

                                                    @if($order->project)
                                                        <a href="{{ route('projects.show', $order->project) }}" class="flex items-center gap-2 px-3 py-2 text-xs font-semibold text-indigo-700 hover:bg-indigo-50 rounded-lg transition">
                                                            <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                                            <span>{{ __('Project Workspace') }}</span>
                                                        </a>
                                                    @endif

                                                    <div class="my-1 border-t border-gray-100"></div>

                                                    <form method="POST" action="{{ route('orders.destroy', $order) }}" onsubmit="return confirm('{{ __('Are you sure you want to delete this order?') }}');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="w-full flex items-center gap-2 px-3 py-2 text-xs font-semibold text-rose-600 hover:bg-rose-50 rounded-lg transition text-left">
                                                            <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                            <span>{{ __('Delete') }}</span>
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-8 text-gray-500">{{ __('No orders found.') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    @endif

                </div>

                <!-- Pagination -->
                @if($orders->hasPages())
                    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                        {{ $orders->links() }}
                    </div>
                @endif
            </div>
        </div>
</x-app-layout>
