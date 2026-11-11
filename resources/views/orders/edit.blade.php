@php
    $meta = $order->meta ?? [];
    $currentOrderType = old('order_type', $order->order_type ?? 'creative');
    $selectedCategory = old('service_category', $order->service_category);
    $currentStatus = old('status', $order->status);

    $creativeCategories = ['Video Edit', 'Graphics Design', 'SMO & SMM', 'Voice Recording', 'Others Work'];
    $adsCategories = ['Meta Ads', 'TikTok Ads', 'YouTube Ads', 'LinkedIn Ads', 'Google Ads', 'Others Ads'];
    $softCategories = [
        'ERP Solutions', 'E-commerce Management', 'Restaurant Management', 'Travel Agency Web',
        'Salon Management', 'Cleaning Service Web', 'Construction Web', 'Company ERP',
        'School Management', 'Hotel Management', 'APP Development', 'Custom Soft & Web',
    ];

    $creativeStatuses = ['Pending', 'Working', 'Re-working', 'Pused', 'Complete', 'Delivery', 'Delay', 'Closed'];
    $adsStatuses = ['Pending', 'Pused', 'Active', 'Closed'];
    $softStatuses = [
        'New Order', 'Requirements', 'Planning/ SRS', 'UI/UX Design', 'On Hold', 'In Development',
        'Dev. Review', 'Testing/ QA', 'Customization', 'Client Review', 'Modification',
        'Delivery Ready', 'Delivered', 'Active', 'Cancel', 'Support',
    ];

    $rawSubSystem = old('meta.subscription_system', $meta['subscription_system'] ?? 'One-Time Payment');
    $normalizedSubSystem = match ($rawSubSystem) {
        'One Time/No Subscription', 'No Subscription' => 'One-Time Payment',
        'Yearly Renewal', 'Yearly' => 'Yearly Subscription',
        'Monthly' => 'Monthly Subscription',
        default => $rawSubSystem,
    };
    $subOptions = ['One-Time Payment', 'Monthly Subscription', 'Yearly Subscription'];

    $modelVal = old('meta.model', $meta['model'] ?? 'Agile Model');
    $priorityVal = old('meta.working_priority', $meta['working_priority'] ?? 'Normal');
    $renewStatusVal = old('meta.renew_status', $meta['renew_status'] ?? 'Not Required');
    $renewOptions = ['Not Required', 'Active', 'Upcoming', 'Due', 'Expired', 'Cancelled'];

    $initialTotal = floatval(old('total_amount', $order->total_amount));
    $initialPaid = $hasReceipts ? floatval($receiptPaidTotal) : floatval(old('paid_amount', $order->paid_amount));
@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                    {{ __('Edit Order') }}: <span class="text-indigo-600 font-mono">{{ $order->order_code }}</span>
                </h2>
                <p class="text-sm text-gray-500 mt-1">{{ __('Update full specifications, schedule, and format details for this order') }}</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('orders.show', $order) }}" class="inline-flex items-center px-4 py-2 bg-indigo-50 text-indigo-700 border border-indigo-200 text-sm font-semibold rounded-lg hover:bg-indigo-100 transition">
                    {{ __('View Order') }}
                </a>
                <a href="{{ route('orders.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-200 transition">
                    <svg class="w-4 h-4 me-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    {{ __('Back to Orders') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="w-full space-y-6" x-data="orderEditForm()">

        @if ($errors->any())
            <div class="p-4 bg-rose-50 border-l-4 border-rose-500 text-rose-800 rounded-r-lg shadow-sm">
                <div class="font-bold text-sm">{{ __('Please fix the following errors:') }}</div>
                <ul class="mt-2 list-disc list-inside text-xs space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('orders.update', $order) }}" @submit="confirmTypeChange($event)" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- SECTION 1: CLIENT SELECTION (Pre-filled + Live Search) -->
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 space-y-4">
                <div class="flex items-center justify-between border-b pb-3">
                    <h3 class="text-base font-bold text-gray-900 flex items-center gap-2">
                        <span class="w-7 h-7 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold text-sm">1</span>
                        {{ __('Client Selection') }}
                        <span class="text-xs font-normal text-gray-500">({{ __('Search directly by WhatsApp number, Phone, or Name') }})</span>
                    </h3>
                    <span class="text-xs text-rose-500 font-semibold">* {{ __('Required') }}</span>
                </div>

                <input type="hidden" name="customer_id" :value="selectedCustomer ? selectedCustomer.id : ''" required>

                <!-- Selected Customer Preview Card -->
                <template x-if="selectedCustomer">
                    <div class="p-4 rounded-xl bg-emerald-50/70 border border-emerald-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-emerald-600 text-white flex items-center justify-center font-bold text-sm">
                                <span x-text="(selectedCustomer.name || '?').charAt(0)"></span>
                            </div>
                            <div>
                                <div class="flex items-center gap-2">
                                    <h4 class="font-bold text-emerald-950 text-sm" x-text="selectedCustomer.name"></h4>
                                    <span class="px-2 py-0.5 rounded text-[10px] font-mono font-bold bg-emerald-200/60 text-emerald-900" x-text="selectedCustomer.customer_code"></span>
                                    <template x-if="selectedCustomer.badge">
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold" :class="selectedCustomer.type === 'lead' ? 'bg-amber-100 text-amber-800' : 'bg-emerald-100 text-emerald-800'" x-text="selectedCustomer.badge"></span>
                                    </template>
                                </div>
                                <div class="text-xs text-emerald-800 flex flex-wrap items-center gap-2 mt-0.5">
                                    <template x-if="selectedCustomer.company_name">
                                        <span x-text="selectedCustomer.company_name + ' •'"></span>
                                    </template>
                                    <template x-if="selectedCustomer.phone">
                                        <span class="font-mono font-semibold" x-text="'📞 ' + selectedCustomer.phone"></span>
                                    </template>
                                    <template x-if="selectedCustomer.whatsapp">
                                        <span class="inline-flex items-center gap-1 font-mono font-bold text-emerald-700">
                                            <span>💬 WhatsApp:</span>
                                            <span x-text="selectedCustomer.whatsapp"></span>
                                        </span>
                                    </template>
                                </div>
                            </div>
                        </div>
                        <button type="button" @click="clearCustomer()" class="px-3 py-1.5 bg-white border border-gray-200 hover:bg-rose-50 hover:border-rose-300 text-rose-600 rounded-lg text-xs font-semibold transition">
                            {{ __('Change Client') }}
                        </button>
                    </div>
                </template>

                <!-- Interactive Live Search Dropdown -->
                <div x-show="!selectedCustomer" x-cloak class="relative" @click.away="isOpen = false">
                    <div class="relative">
                        <input
                            type="text"
                            x-model="searchQuery"
                            @focus="isOpen = true"
                            @input="isOpen = true; onSearchInput()"
                            placeholder="{{ __('Type Client WhatsApp Number, Phone, Lead ID/Token, or Name to search...') }}"
                            class="w-full text-sm pl-10 pr-24 py-2.5 rounded-xl border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 shadow-2xs font-mono"
                        >
                        <svg class="w-5 h-5 text-emerald-600 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        <span x-show="isSearching" x-cloak class="absolute right-3 top-3 text-xs font-semibold text-emerald-600">
                            {{ __('Searching...') }}
                        </span>
                    </div>

                    <div x-show="isOpen" class="absolute z-30 mt-1 w-full bg-white rounded-xl shadow-lg border border-gray-200 max-h-64 overflow-y-auto divide-y divide-gray-100">
                        <template x-for="cust in filteredCustomers" :key="cust.id">
                            <div @click="selectCustomer(cust)" class="p-3 hover:bg-emerald-50/50 cursor-pointer transition flex items-center justify-between">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="font-bold text-gray-900 text-sm" x-text="cust.name"></span>
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold" :class="cust.type === 'lead' ? 'bg-amber-100 text-amber-800' : 'bg-emerald-100 text-emerald-800'" x-text="cust.badge"></span>
                                    </div>
                                    <div class="text-xs text-gray-500" x-text="cust.company_name ? cust.company_name : 'Individual'"></div>
                                </div>
                                <div class="text-right">
                                    <div class="text-xs font-mono font-bold text-emerald-700 flex items-center gap-1 justify-end">
                                        <span>💬</span>
                                        <span x-text="cust.whatsapp ? cust.whatsapp : (cust.phone || '-')"></span>
                                    </div>
                                    <div class="text-[10px] text-gray-400 font-mono" x-text="cust.phone ? ('📞 ' + cust.phone) : ''"></div>
                                </div>
                            </div>
                        </template>
                        <div x-show="isSearching && filteredCustomers.length === 0" class="p-4 text-center text-xs text-emerald-600 font-medium">
                            {{ __('Searching database...') }}
                        </div>
                        <div x-show="!isSearching && filteredCustomers.length === 0" class="p-4 text-center text-xs text-gray-400">
                            {{ __('No matching client found for this WhatsApp number, code, or name.') }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECTION 2: ORDER FORMAT & TYPE -->
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 space-y-6">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between border-b pb-4 gap-3">
                    <div class="flex items-center gap-2">
                        <span class="w-7 h-7 rounded-lg bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold text-sm">2</span>
                        <div>
                            <h3 class="text-base font-bold text-gray-900">{{ __('Order Format & Type') }}</h3>
                            <p class="text-xs text-gray-500">{{ __('Edit exact format specifications for Creative, Ads Campaign, or Website & Software') }}</p>
                        </div>
                    </div>

                    <!-- Tab buttons + Format Lock Controls -->
                    <div class="flex flex-wrap items-center gap-2">
                        <div class="flex items-center gap-1 bg-gray-100 p-1 rounded-xl">
                            <button type="button"
                                @click="switchOrderType('creative')"
                                :disabled="!typeUnlocked && orderType !== 'creative'"
                                :title="!typeUnlocked && orderType !== 'creative' ? '{{ __('Locked! Click Change Order Type to switch format.') }}' : ''"
                                :class="orderType === 'creative' ? 'bg-white text-indigo-700 shadow-xs font-bold' : (typeUnlocked ? 'text-gray-600 hover:text-gray-900 font-medium cursor-pointer' : 'text-gray-400 cursor-not-allowed opacity-60')"
                                class="px-3.5 py-1.5 rounded-lg text-xs transition flex items-center gap-1.5">
                                <span>🎨</span>
                                <span>{{ __('Creative Order') }}</span>
                                <span x-show="!typeUnlocked && orderType !== 'creative'" class="text-[10px]">🔒</span>
                            </button>
                            <button type="button"
                                @click="switchOrderType('ads')"
                                :disabled="!typeUnlocked && orderType !== 'ads'"
                                :title="!typeUnlocked && orderType !== 'ads' ? '{{ __('Locked! Click Change Order Type to switch format.') }}' : ''"
                                :class="orderType === 'ads' ? 'bg-white text-amber-700 shadow-xs font-bold' : (typeUnlocked ? 'text-gray-600 hover:text-gray-900 font-medium cursor-pointer' : 'text-gray-400 cursor-not-allowed opacity-60')"
                                class="px-3.5 py-1.5 rounded-lg text-xs transition flex items-center gap-1.5">
                                <span>📈</span>
                                <span>{{ __('Ads Campaign') }}</span>
                                <span x-show="!typeUnlocked && orderType !== 'ads'" class="text-[10px]">🔒</span>
                            </button>
                            <button type="button"
                                @click="switchOrderType('web_software')"
                                :disabled="!typeUnlocked && orderType !== 'web_software'"
                                :title="!typeUnlocked && orderType !== 'web_software' ? '{{ __('Locked! Click Change Order Type to switch format.') }}' : ''"
                                :class="orderType === 'web_software' ? 'bg-white text-blue-700 shadow-xs font-bold' : (typeUnlocked ? 'text-gray-600 hover:text-gray-900 font-medium cursor-pointer' : 'text-gray-400 cursor-not-allowed opacity-60')"
                                class="px-3.5 py-1.5 rounded-lg text-xs transition flex items-center gap-1.5">
                                <span>💻</span>
                                <span>{{ __('Website & Software') }}</span>
                                <span x-show="!typeUnlocked && orderType !== 'web_software'" class="text-[10px]">🔒</span>
                            </button>
                        </div>

                        <button type="button" x-show="!typeUnlocked" @click="unlockOrderType()"
                            class="px-3 py-1.5 rounded-lg text-xs font-semibold border border-amber-300 bg-amber-50 text-amber-800 hover:bg-amber-100 transition flex items-center gap-1">
                            <span>🔓</span> {{ __('Change Type') }}
                        </button>
                        <button type="button" x-show="typeUnlocked" x-cloak @click="revertOrderType()"
                            class="px-3 py-1.5 rounded-lg text-xs font-semibold border border-gray-300 bg-white text-gray-700 hover:bg-gray-100 transition flex items-center gap-1">
                            <span>🔒</span> {{ __('Lock / Revert') }}
                        </button>
                    </div>
                </div>

                <!-- Type Switching Lock & Warning Banner -->
                <div x-show="typeUnlocked || orderType !== originalOrderType" x-cloak
                    :class="orderType !== originalOrderType ? 'bg-rose-50 border-rose-300 text-rose-900' : 'bg-amber-50 border-amber-300 text-amber-900'"
                    class="p-3.5 rounded-xl border text-xs flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                    <div class="flex items-start gap-2">
                        <span class="text-base leading-none">⚠️</span>
                        <div>
                            <div class="font-bold">
                                <span x-show="orderType === originalOrderType">{{ __('Order Type Switching Unlocked') }}</span>
                                <span x-show="orderType !== originalOrderType">
                                    {{ __('Warning: Order type will change from') }}
                                    <span class="uppercase font-mono underline" x-text="typeLabel(originalOrderType)"></span>
                                    {{ __('to') }}
                                    <span class="uppercase font-mono underline" x-text="typeLabel(orderType)"></span>!
                                </span>
                            </div>
                            <p class="mt-0.5 opacity-90">
                                {{ __('Switching order type converts this single order to the new format and hides previous format specifications on the details page. To keep separate services for the same client, create a separate order instead.') }}
                            </p>
                        </div>
                    </div>
                    <button type="button" @click="revertOrderType()" class="shrink-0 px-3 py-1.5 bg-white border border-gray-300 rounded-lg font-semibold text-gray-800 hover:bg-gray-100 transition">
                        {{ __('Keep Original Type') }}
                    </button>
                </div>

                <input type="hidden" name="order_type" :value="orderType">
                <input type="hidden" name="total_amount" :value="currentTotal()">
                <input type="hidden" name="paid_amount" :value="currentPaid()">

                <!-- Common Title & Category -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Order Title / Project Name') }} <span class="text-rose-500">*</span></label>
                        <input type="text" name="title" required x-model="orderTitle" placeholder="e.g. Promotional Video for Moonway Tour" class="w-full text-sm rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Service Category') }} <span class="text-rose-500">*</span></label>

                        <!-- Creative Services Category -->
                        <div x-show="orderType === 'creative'">
                            <select name="service_category" :disabled="orderType !== 'creative'" class="w-full text-sm rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                                <optgroup label="Creative Services">
                                    @foreach($creativeCategories as $cat)
                                        <option value="{{ $cat }}" @selected($selectedCategory === $cat)>{{ $cat }}</option>
                                    @endforeach
                                </optgroup>
                                @if($selectedCategory && !in_array($selectedCategory, $creativeCategories))
                                    <optgroup label="Current Category">
                                        <option value="{{ $selectedCategory }}" selected>{{ $selectedCategory }}</option>
                                    </optgroup>
                                @endif
                            </select>
                        </div>

                        <!-- Ads Campaign Category -->
                        <div x-show="orderType === 'ads'" x-cloak>
                            <select name="service_category" :disabled="orderType !== 'ads'" class="w-full text-sm rounded-lg border-gray-300 focus:ring-amber-500 focus:border-amber-500">
                                <optgroup label="Ads Campaign">
                                    @foreach($adsCategories as $cat)
                                        <option value="{{ $cat }}" @selected($selectedCategory === $cat)>{{ $cat }}</option>
                                    @endforeach
                                </optgroup>
                                @if($selectedCategory && !in_array($selectedCategory, $adsCategories))
                                    <optgroup label="Current Category">
                                        <option value="{{ $selectedCategory }}" selected>{{ $selectedCategory }}</option>
                                    </optgroup>
                                @endif
                            </select>
                        </div>

                        <!-- Website & Software Category -->
                        <div x-show="orderType === 'web_software'" x-cloak>
                            <select name="service_category" :disabled="orderType !== 'web_software'" class="w-full text-sm rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                                <optgroup label="Soft & Web Develop">
                                    @foreach($softCategories as $cat)
                                        <option value="{{ $cat }}" @selected($selectedCategory === $cat)>{{ $cat }}</option>
                                    @endforeach
                                </optgroup>
                                @if($selectedCategory && !in_array($selectedCategory, $softCategories))
                                    <optgroup label="Current Category">
                                        <option value="{{ $selectedCategory }}" selected>{{ $selectedCategory }}</option>
                                    </optgroup>
                                @endif
                            </select>
                        </div>
                    </div>
                </div>

                <!-- ========================================== -->
                <!-- TAB 1: CREATIVE ORDER FORMAT -->
                <!-- ========================================== -->
                <div x-show="orderType === 'creative'" class="space-y-4 pt-2">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Service Provider') }}</label>
                            <input type="text" name="meta[service_provider]" value="{{ old('meta.service_provider', $meta['service_provider'] ?? '') }}" :disabled="orderType !== 'creative'" placeholder="e.g. Mahfuz, Jahid Hasan" class="w-full text-sm rounded-lg border-gray-300 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Starting Date') }}</label>
                            <input type="date" name="meta[starting_date]" value="{{ old('meta.starting_date', $meta['starting_date'] ?? '') }}" :disabled="orderType !== 'creative'" class="w-full text-sm rounded-lg border-gray-300 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Complete Date') }}</label>
                            <input type="date" name="meta[complete_date]" value="{{ old('meta.complete_date', $meta['complete_date'] ?? '') }}" :disabled="orderType !== 'creative'" class="w-full text-sm rounded-lg border-gray-300 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Why Not Delivery') }}</label>
                            <input type="text" name="meta[why_not_delivery]" value="{{ old('meta.why_not_delivery', $meta['why_not_delivery'] ?? '') }}" :disabled="orderType !== 'creative'" placeholder="e.g. Due Payment, Client Response Pending" class="w-full text-sm rounded-lg border-gray-300 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Language') }}</label>
                            <input type="text" name="meta[language]" value="{{ old('meta.language', $meta['language'] ?? '') }}" :disabled="orderType !== 'creative'" placeholder="e.g. Bangla, Arabic, English" class="w-full text-sm rounded-lg border-gray-300 focus:ring-indigo-500">
                        </div>

                        @php
                            $creativeStatusOptions = ['Not ready', 'Ready', 'Cl-Checking', 'Confirmed', 'No need'];
                            $scriptStatusVal = old('meta.script_status', $meta['script_status'] ?? ($meta['script_idea'] ?? 'Not ready'));
                            $voiceStatusVal = old('meta.voice_status', $meta['voice_status'] ?? ($meta['voice_design'] ?? 'Not ready'));
                        @endphp

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Script Status') }}</label>
                            <select name="meta[script_status]" :disabled="orderType !== 'creative'" class="w-full text-sm rounded-lg border-gray-300 focus:ring-indigo-500">
                                @foreach($creativeStatusOptions as $opt)
                                    <option value="{{ $opt }}" @selected($scriptStatusVal === $opt)>{{ $opt }}</option>
                                @endforeach
                                @if($scriptStatusVal && !in_array($scriptStatusVal, $creativeStatusOptions))
                                    <option value="{{ $scriptStatusVal }}" selected>{{ $scriptStatusVal }}</option>
                                @endif
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Script Code') }}</label>
                            <input type="text" name="meta[script_code]" value="{{ old('meta.script_code', $meta['script_code'] ?? '') }}" :disabled="orderType !== 'creative'" placeholder="e.g. SCR-901" class="w-full text-sm rounded-lg border-gray-300 focus:ring-indigo-500 font-mono">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Voice Status') }}</label>
                            <select name="meta[voice_status]" :disabled="orderType !== 'creative'" class="w-full text-sm rounded-lg border-gray-300 focus:ring-indigo-500">
                                @foreach($creativeStatusOptions as $opt)
                                    <option value="{{ $opt }}" @selected($voiceStatusVal === $opt)>{{ $opt }}</option>
                                @endforeach
                                @if($voiceStatusVal && !in_array($voiceStatusVal, $creativeStatusOptions))
                                    <option value="{{ $voiceStatusVal }}" selected>{{ $voiceStatusVal }}</option>
                                @endif
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Video Duration (Second)') }}</label>
                            <input type="number" name="meta[second]" value="{{ old('meta.second', $meta['second'] ?? '') }}" :disabled="orderType !== 'creative'" placeholder="e.g. 60" class="w-full text-sm rounded-lg border-gray-300 focus:ring-indigo-500 font-mono">
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Voice Link / Audio URL') }}</label>
                            <input type="text" name="meta[voice_link]" value="{{ old('meta.voice_link', $meta['voice_link'] ?? '') }}" :disabled="orderType !== 'creative'" placeholder="Google Drive audio link or URL" class="w-full text-sm rounded-lg border-gray-300 focus:ring-indigo-500">
                        </div>

                        <div class="sm:col-span-3">
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Reference Link / Asset Drive') }}</label>
                            <input type="text" name="meta[reference_link]" value="{{ old('meta.reference_link', $meta['reference_link'] ?? '') }}" :disabled="orderType !== 'creative'" placeholder="https://drive.google.com/..." class="w-full text-sm rounded-lg border-gray-300 focus:ring-indigo-500">
                        </div>
                    </div>

                    <!-- Creative Financials & Payment Ledger Summary -->
                    <div class="p-5 bg-indigo-50/50 rounded-2xl border border-indigo-100 space-y-4 mt-4">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-indigo-100 pb-3">
                            <div>
                                <h4 class="text-xs font-bold text-indigo-950 uppercase tracking-wider flex items-center gap-1.5">
                                    <span>💳</span> {{ __('Creative Financials & Payment Ledger') }}
                                </h4>
                                <p class="text-2xs text-gray-500">
                                    @if($hasReceipts)
                                        {{ __('Paid amount is synced automatically from recorded Money Receipts.') }}
                                    @else
                                        {{ __('Adjust Initial Total or Paid Amount, or record receipts from the Order Details page.') }}
                                    @endif
                                </p>
                            </div>
                            <div class="flex flex-wrap items-center gap-3">
                                <div>
                                    <label class="block text-2xs font-semibold text-gray-700 uppercase mb-1">{{ __('Initial Total (BDT)') }} <span class="text-rose-500">*</span></label>
                                    <input type="number" step="0.01" min="0" x-model.number="creativeTotal" class="w-40 text-sm font-bold font-mono rounded-lg border-gray-300 focus:ring-indigo-500">
                                </div>
                                @if(!$hasReceipts)
                                    <div>
                                        <label class="block text-2xs font-semibold text-gray-700 uppercase mb-1">{{ __('Paid Amount (BDT)') }}</label>
                                        <input type="number" step="0.01" min="0" x-model.number="manualPaid" class="w-40 text-sm font-bold font-mono rounded-lg border-gray-300 focus:ring-indigo-500">
                                    </div>
                                @endif
                            </div>
                        </div>

                        @if($hasReceipts)
                            <div class="space-y-2">
                                <div class="flex items-center justify-between">
                                    <span class="text-2xs font-bold text-gray-600 uppercase">{{ __('Recorded Payment Receipts') }} ({{ $order->clientPayments->count() }})</span>
                                    <a href="{{ route('orders.show', $order) }}" class="text-xs font-semibold text-indigo-700 hover:underline">
                                        + {{ __('Record / Manage Payments on Order Page') }} &rarr;
                                    </a>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2">
                                    @foreach($order->clientPayments as $pay)
                                        <div class="p-2.5 bg-white rounded-xl border border-indigo-100 flex items-center justify-between text-xs">
                                            <div>
                                                <div class="font-bold text-gray-800 font-mono">{{ $pay->payment_date?->format('d M Y') ?? '-' }}</div>
                                                <div class="text-[11px] text-gray-500">{{ $pay->payment_method }}</div>
                                            </div>
                                            <div class="font-mono font-bold text-emerald-700">BDT {{ number_format($pay->amount, 2) }}</div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Live Calculation Bar -->
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between p-3 bg-white rounded-xl border border-indigo-200 text-xs gap-3">
                            <div class="flex items-center gap-4">
                                <span class="text-gray-600">{{ __('Initial Total:') }} <strong class="text-indigo-900 font-mono font-bold" x-text="'BDT ' + (parseFloat(creativeTotal) || 0).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})"></strong></span>
                                <span class="text-gray-600">{{ __('Total Paid:') }} <strong class="text-emerald-700 font-mono font-bold" x-text="'BDT ' + currentPaid().toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})"></strong></span>
                            </div>
                            <div>
                                <span class="text-gray-600">{{ __('Calculated Due:') }} <strong class="text-rose-600 font-mono font-bold text-sm" x-text="'BDT ' + Math.max(0, (parseFloat(creativeTotal) || 0) - currentPaid()).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})"></strong></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ============================================== -->
                <!-- TAB 2: ADS CAMPAIGN ORDER FORMAT -->
                <!-- ============================================== -->
                <div x-show="orderType === 'ads'" x-cloak class="space-y-4 pt-2">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Ads Post Link') }}</label>
                            <input type="text" name="meta[ads_post_link]" value="{{ old('meta.ads_post_link', $meta['ads_post_link'] ?? '') }}" :disabled="orderType !== 'ads'" placeholder="https://facebook.com/post/..." class="w-full text-sm rounded-lg border-gray-300 focus:ring-amber-500">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Lifetime/ Daily Budget ($)') }}</label>
                            <input type="number" step="0.01" name="meta[lifetime_daily_budget]" value="{{ old('meta.lifetime_daily_budget', $meta['lifetime_daily_budget'] ?? '') }}" :disabled="orderType !== 'ads'" placeholder="e.g. 5.00 or 150.00" class="w-full text-sm rounded-lg border-gray-300 focus:ring-amber-500 font-mono font-bold">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Total Days') }}</label>
                            <input type="number" name="meta[total_days]" :disabled="orderType !== 'ads'" x-model.number="adsTotalDays" placeholder="e.g. 15" class="w-full text-sm rounded-lg border-gray-300 focus:ring-amber-500 font-mono">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1 flex items-center justify-between">
                                <span>{{ __('Total Dollar ($ Budget)') }}</span>
                                <span class="text-[10px] text-amber-700 font-normal">({{ __('Auto: BDT ÷ 150') }})</span>
                            </label>
                            <input type="number" step="0.01" name="meta[total_dollar]" :disabled="orderType !== 'ads'" :value="adsTotalAmount > 0 ? (adsTotalAmount / 150).toFixed(2) : ''" readonly placeholder="0.00" class="w-full text-sm rounded-lg border-gray-300 bg-gray-100 cursor-not-allowed focus:ring-0 font-mono font-bold text-amber-900">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Starting Date') }}</label>
                            <input type="date" name="meta[starting_date]" value="{{ old('meta.starting_date', $meta['starting_date'] ?? '') }}" :disabled="orderType !== 'ads'" class="w-full text-sm rounded-lg border-gray-300 focus:ring-amber-500">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('End Date') }}</label>
                            <input type="date" name="meta[end_date]" value="{{ old('meta.end_date', $meta['end_date'] ?? '') }}" :disabled="orderType !== 'ads'" class="w-full text-sm rounded-lg border-gray-300 focus:ring-amber-500">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Previous Dollar ($)') }}</label>
                            <input type="number" step="0.01" name="meta[previous_dollar]" :disabled="orderType !== 'ads'" x-model.number="adsPreviousDollar" placeholder="0.00" class="w-full text-sm rounded-lg border-gray-300 focus:ring-amber-500 font-mono">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Running Dollar ($)') }}</label>
                            <input type="number" step="0.01" name="meta[running_dollar]" :disabled="orderType !== 'ads'" x-model.number="adsRunningDollar" placeholder="0.00" class="w-full text-sm rounded-lg border-gray-300 focus:ring-amber-500 font-mono">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1 flex items-center justify-between">
                                <span>{{ __('Total $ Cost') }}</span>
                                <span class="text-[10px] text-gray-500 font-normal">({{ __('Auto: Running - Previous') }})</span>
                            </label>
                            <input type="number" step="0.01" name="meta[total_dollar_cost]" :disabled="orderType !== 'ads'" :value="adsTotalDollarCost()" readonly placeholder="0.00" class="w-full text-sm rounded-lg border-gray-300 bg-gray-100 cursor-not-allowed focus:ring-0 font-mono font-bold text-gray-800">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1 flex items-center justify-between">
                                <span>{{ __('Dollar Due ($)') }}</span>
                                <span class="text-[10px] text-rose-500 font-normal">({{ __('Auto: Budget - Cost') }})</span>
                            </label>
                            <input type="number" step="0.01" name="meta[dollar_due]" :disabled="orderType !== 'ads'" :value="adsDollarDue()" readonly placeholder="0.00" class="w-full text-sm rounded-lg border-gray-300 bg-gray-100 cursor-not-allowed focus:ring-0 font-mono font-bold text-rose-600">
                        </div>
                    </div>

                    <!-- Ads BDT Budget & Payment Summary (Auto Full Paid) -->
                    <div class="p-4 bg-amber-50/50 rounded-xl border border-amber-200 space-y-3 mt-4">
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 items-center">
                            <div>
                                <label class="block text-2xs font-semibold text-gray-700 uppercase mb-1">{{ __('Total Amount (BDT)') }} <span class="text-rose-500">*</span></label>
                                <input type="number" step="0.01" min="0" x-model.number="adsTotalAmount" placeholder="0.00" class="w-full text-sm font-bold font-mono rounded-lg border-gray-300 focus:ring-amber-500">
                            </div>
                            <div>
                                <label class="block text-2xs font-semibold text-gray-700 uppercase mb-1">{{ __('Payment Method (Auto Receipt)') }}</label>
                                <select name="ads_payment_method" :disabled="orderType !== 'ads'" class="w-full text-sm font-semibold rounded-lg border-gray-300 focus:ring-amber-500">
                                    @foreach(['Cash' => '💵 Cash', 'Bank Transfer' => '🏦 Bank Transfer', 'bKash' => '📱 bKash', 'Nagad' => '📱 Nagad', 'Rocket' => '📱 Rocket'] as $mVal => $mLabel)
                                        <option value="{{ $mVal }}" @selected(old('ads_payment_method', 'Cash') === $mVal)>{{ $mLabel }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="p-3 bg-white rounded-xl border border-amber-200 flex items-center justify-between">
                                <div>
                                    <div class="text-2xs font-bold text-gray-500 uppercase">{{ __('Total Dollar ($ Budget)') }}</div>
                                    <div class="text-[10px] text-gray-400">{{ __('Auto-calculated (BDT ÷ 150)') }}</div>
                                </div>
                                <div class="text-lg font-black text-amber-700 font-mono" x-text="'$' + (adsTotalAmount > 0 ? (adsTotalAmount / 150).toFixed(2) : '0.00')"></div>
                            </div>
                        </div>

                        <div class="flex flex-wrap items-center justify-between p-2.5 bg-white rounded-xl border border-amber-200 text-xs gap-3">
                            <div class="flex items-center gap-4">
                                <span class="text-gray-600">{{ __('Total Paid (Auto):') }} <strong class="text-emerald-700 font-mono font-bold" x-text="'BDT ' + currentPaid().toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})"></strong></span>
                                <span class="text-gray-600">{{ __('Due (BDT):') }} <strong class="text-emerald-600 font-mono font-bold" x-text="'BDT ' + Math.max(0, (parseFloat(adsTotalAmount) || 0) - currentPaid()).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})"></strong></span>
                            </div>
                            <a href="{{ route('orders.show', $order) }}" class="text-xs font-semibold text-amber-700 hover:underline">
                                + {{ __('Record / Manage Payments on Order Page') }} &rarr;
                            </a>
                        </div>
                    </div>
                </div>

                <!-- ============================================== -->
                <!-- TAB 3: SOFT & WEB ORDER FORMAT -->
                <!-- ============================================== -->
                <div x-show="orderType === 'web_software'" x-cloak class="space-y-4 pt-2">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Model') }}</label>
                            <select name="meta[model]" :disabled="orderType !== 'web_software'" class="w-full text-sm rounded-lg border-gray-300 focus:ring-blue-500 font-medium">
                                <option value="Agile Model" @selected($modelVal === 'Agile Model')>Agile Model</option>
                                <option value="Waterfall Model" @selected($modelVal === 'Waterfall Model')>Waterfall Model</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Working Priority') }}</label>
                            <select name="meta[working_priority]" :disabled="orderType !== 'web_software'" class="w-full text-sm rounded-lg border-gray-300 focus:ring-blue-500">
                                <option value="Normal" @selected($priorityVal === 'Normal')>Normal</option>
                                <option value="High" @selected($priorityVal === 'High')>High</option>
                                <option value="Urgent" @selected($priorityVal === 'Urgent')>Urgent</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Subscription System') }}</label>
                            <select name="meta[subscription_system]" :disabled="orderType !== 'web_software'" class="w-full text-sm rounded-lg border-gray-300 focus:ring-blue-500">
                                @foreach($subOptions as $subOpt)
                                    <option value="{{ $subOpt }}" @selected($normalizedSubSystem === $subOpt)>{{ $subOpt }}</option>
                                @endforeach
                                @if($normalizedSubSystem && !in_array($normalizedSubSystem, $subOptions))
                                    <option value="{{ $normalizedSubSystem }}" selected>{{ $normalizedSubSystem }}</option>
                                @endif
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Subscription / Renew Fee') }}</label>
                            <input type="number" step="0.01" name="meta[subscription_fee]" value="{{ old('meta.subscription_fee', $meta['subscription_fee'] ?? '') }}" :disabled="orderType !== 'web_software'" placeholder="e.g. 2000" class="w-full text-sm rounded-lg border-gray-300 focus:ring-blue-500 font-mono">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Monthly Due (BDT)') }}</label>
                            <input type="number" step="0.01" name="meta[monthly_due]" value="{{ old('meta.monthly_due', $meta['monthly_due'] ?? '') }}" :disabled="orderType !== 'web_software'" placeholder="0.00" class="w-full text-sm rounded-lg border-gray-300 focus:ring-blue-500 font-mono">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Yearly Due (BDT)') }}</label>
                            <input type="number" step="0.01" name="meta[yearly_due]" value="{{ old('meta.yearly_due', $meta['yearly_due'] ?? '') }}" :disabled="orderType !== 'web_software'" placeholder="0.00" class="w-full text-sm rounded-lg border-gray-300 focus:ring-blue-500 font-mono">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Next Renew Payment Date') }}</label>
                            <input type="date" name="meta[next_renew_date]" value="{{ old('meta.next_renew_date', $meta['next_renew_date'] ?? '') }}" :disabled="orderType !== 'web_software'" class="w-full text-sm rounded-lg border-gray-300 focus:ring-blue-500">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Renew Status') }}</label>
                            <select name="meta[renew_status]" :disabled="orderType !== 'web_software'" class="w-full text-sm rounded-lg border-gray-300 focus:ring-blue-500">
                                @foreach($renewOptions as $rOpt)
                                    <option value="{{ $rOpt }}" @selected($renewStatusVal === $rOpt)>{{ $rOpt }}</option>
                                @endforeach
                                @if($renewStatusVal && !in_array($renewStatusVal, $renewOptions))
                                    <option value="{{ $renewStatusVal }}" selected>{{ $renewStatusVal }}</option>
                                @endif
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Purchase Date') }}</label>
                            <input type="date" name="meta[purchase_date]" value="{{ old('meta.purchase_date', $meta['purchase_date'] ?? '') }}" :disabled="orderType !== 'web_software'" class="w-full text-sm rounded-lg border-gray-300 focus:ring-blue-500">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Complete Date') }}</label>
                            <input type="date" name="meta[complete_date]" value="{{ old('meta.complete_date', $meta['complete_date'] ?? '') }}" :disabled="orderType !== 'web_software'" class="w-full text-sm rounded-lg border-gray-300 focus:ring-blue-500">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Developed Assign') }}</label>
                            <input type="text" name="meta[developed_assign]" value="{{ old('meta.developed_assign', $meta['developed_assign'] ?? '') }}" :disabled="orderType !== 'web_software'" placeholder="Developer Name" class="w-full text-sm rounded-lg border-gray-300 focus:ring-blue-500">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Customize Assign') }}</label>
                            <input type="text" name="meta[customize_assign]" value="{{ old('meta.customize_assign', $meta['customize_assign'] ?? '') }}" :disabled="orderType !== 'web_software'" placeholder="Customizer / PM Name" class="w-full text-sm rounded-lg border-gray-300 focus:ring-blue-500">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Domain / Live URL') }}</label>
                            <input type="text" name="meta[domain]" value="{{ old('meta.domain', $meta['domain'] ?? '') }}" :disabled="orderType !== 'web_software'" placeholder="https://clientdomain.com" class="w-full text-sm rounded-lg border-gray-300 focus:ring-blue-500">
                        </div>
                    </div>

                    <!-- Software & Web Financials & Payment Ledger -->
                    <div class="p-5 bg-blue-50/50 rounded-2xl border border-blue-200 space-y-4 mt-4">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-blue-200 pb-3">
                            <div>
                                <h4 class="text-xs font-bold text-blue-950 uppercase tracking-wider flex items-center gap-1.5">
                                    <span>💳</span> {{ __('Software & Web Financials & Payment Ledger') }}
                                </h4>
                                <p class="text-2xs text-gray-500">
                                    @if($hasReceipts)
                                        {{ __('Paid amount is synced automatically from recorded Money Receipts.') }}
                                    @else
                                        {{ __('Adjust Initial Total or Paid Amount, or record receipts from the Order Details page.') }}
                                    @endif
                                </p>
                            </div>
                            <div class="flex flex-wrap items-center gap-3">
                                <div>
                                    <label class="block text-2xs font-semibold text-gray-700 uppercase mb-1">{{ __('Initial Total (BDT)') }} <span class="text-rose-500">*</span></label>
                                    <input type="number" step="0.01" min="0" x-model.number="softTotal" class="w-40 text-sm font-bold font-mono rounded-lg border-gray-300 focus:ring-blue-500">
                                </div>
                                @if(!$hasReceipts)
                                    <div>
                                        <label class="block text-2xs font-semibold text-gray-700 uppercase mb-1">{{ __('Paid Amount (BDT)') }}</label>
                                        <input type="number" step="0.01" min="0" x-model.number="manualPaid" class="w-40 text-sm font-bold font-mono rounded-lg border-gray-300 focus:ring-blue-500">
                                    </div>
                                @endif
                            </div>
                        </div>

                        @if($hasReceipts)
                            <div class="space-y-2">
                                <div class="flex items-center justify-between">
                                    <span class="text-2xs font-bold text-gray-600 uppercase">{{ __('Recorded Payment Receipts') }} ({{ $order->clientPayments->count() }})</span>
                                    <a href="{{ route('orders.show', $order) }}" class="text-xs font-semibold text-blue-700 hover:underline">
                                        + {{ __('Record / Manage Payments on Order Page') }} &rarr;
                                    </a>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2">
                                    @foreach($order->clientPayments as $pay)
                                        <div class="p-2.5 bg-white rounded-xl border border-blue-100 flex items-center justify-between text-xs">
                                            <div>
                                                <div class="font-bold text-gray-800 font-mono">{{ $pay->payment_date?->format('d M Y') ?? '-' }}</div>
                                                <div class="text-[11px] text-gray-500">{{ $pay->payment_method }}</div>
                                            </div>
                                            <div class="font-mono font-bold text-emerald-700">BDT {{ number_format($pay->amount, 2) }}</div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Live Calculation Bar & Next Due Date -->
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between p-3 bg-white rounded-xl border border-blue-200 text-xs gap-3 mt-3">
                            <div class="flex flex-wrap items-center gap-4">
                                <span class="text-gray-600">{{ __('Initial Total:') }} <strong class="text-blue-950 font-mono font-bold" x-text="'BDT ' + (parseFloat(softTotal) || 0).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})"></strong></span>
                                <span class="text-gray-600">{{ __('Total Paid:') }} <strong class="text-emerald-700 font-mono font-bold" x-text="'BDT ' + currentPaid().toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})"></strong></span>
                                <span class="text-gray-600">{{ __('Calculated Due:') }} <strong class="text-rose-600 font-mono font-bold text-sm" x-text="'BDT ' + Math.max(0, (parseFloat(softTotal) || 0) - currentPaid()).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})"></strong></span>
                            </div>
                            <div class="flex items-center gap-2">
                                <label class="text-2xs font-semibold text-gray-700 uppercase whitespace-nowrap">{{ __('Initial Next Due Pay Date') }}:</label>
                                <input type="date" name="meta[initial_next_due_date]" value="{{ old('meta.initial_next_due_date', $meta['initial_next_due_date'] ?? '') }}" :disabled="orderType !== 'web_software'" class="text-xs rounded-lg border-gray-300 focus:ring-blue-500 py-1 px-2 font-mono">
                            </div>
                        </div>
                    </div>

                    <!-- Credentials & Links -->
                    <div class="p-4 bg-gray-50 rounded-xl border border-gray-200 space-y-3 mt-4">
                        <h4 class="text-xs font-bold text-gray-800 uppercase tracking-wider flex items-center gap-1.5">
                            <span>🔐</span> {{ __('Admin Credentials & Access Portals') }}
                        </h4>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            <div>
                                <label class="block text-2xs font-semibold text-gray-600 uppercase mb-1">{{ __('Official Admin Link') }}</label>
                                <input type="text" name="meta[official_admin_link]" value="{{ old('meta.official_admin_link', $meta['official_admin_link'] ?? '') }}" :disabled="orderType !== 'web_software'" placeholder="https://crm.../admin" class="w-full text-xs font-mono rounded-lg border-gray-300">
                            </div>
                            <div>
                                <label class="block text-2xs font-semibold text-gray-600 uppercase mb-1">{{ __('Official User ID') }}</label>
                                <input type="text" name="meta[official_user_id]" value="{{ old('meta.official_user_id', $meta['official_user_id'] ?? '') }}" :disabled="orderType !== 'web_software'" placeholder="admin_user" class="w-full text-xs font-mono rounded-lg border-gray-300">
                            </div>
                            <div>
                                <label class="block text-2xs font-semibold text-gray-600 uppercase mb-1">{{ __('Official Password') }}</label>
                                <input type="text" name="meta[official_password]" value="{{ old('meta.official_password', $meta['official_password'] ?? '') }}" :disabled="orderType !== 'web_software'" placeholder="Secret password" class="w-full text-xs font-mono rounded-lg border-gray-300">
                            </div>

                            <div>
                                <label class="block text-2xs font-semibold text-gray-600 uppercase mb-1">{{ __('Client Admin Link') }}</label>
                                <input type="text" name="meta[client_admin_link]" value="{{ old('meta.client_admin_link', $meta['client_admin_link'] ?? '') }}" :disabled="orderType !== 'web_software'" placeholder="https://client.../wp-admin" class="w-full text-xs font-mono rounded-lg border-gray-300">
                            </div>
                            <div>
                                <label class="block text-2xs font-semibold text-gray-600 uppercase mb-1">{{ __('Client User ID') }}</label>
                                <input type="text" name="meta[client_user_id]" value="{{ old('meta.client_user_id', $meta['client_user_id'] ?? '') }}" :disabled="orderType !== 'web_software'" placeholder="client_admin" class="w-full text-xs font-mono rounded-lg border-gray-300">
                            </div>
                            <div>
                                <label class="block text-2xs font-semibold text-gray-600 uppercase mb-1">{{ __('Client Password') }}</label>
                                <input type="text" name="meta[client_password]" value="{{ old('meta.client_password', $meta['client_password'] ?? '') }}" :disabled="orderType !== 'web_software'" placeholder="Client secret password" class="w-full text-xs font-mono rounded-lg border-gray-300">
                            </div>

                            <div class="sm:col-span-3">
                                <label class="block text-2xs font-semibold text-gray-600 uppercase mb-1">{{ __('Client Others User ID & Password') }}</label>
                                <input type="text" name="meta[client_other_credentials]" value="{{ old('meta.client_other_credentials', $meta['client_other_credentials'] ?? '') }}" :disabled="orderType !== 'web_software'" placeholder="cPanel, DB, Hosting or extra staff credentials..." class="w-full text-xs font-mono rounded-lg border-gray-300">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECTION 3: COMMON SCHEDULE & ASSIGNMENT -->
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 space-y-4">
                <h3 class="text-base font-bold text-gray-900 flex items-center gap-2 border-b pb-3">
                    <span class="w-7 h-7 rounded-lg bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold text-sm">3</span>
                    {{ __('Schedule & Team Assignment') }}
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Order Date') }} <span class="text-rose-500">*</span></label>
                        <input type="date" name="order_date" required value="{{ old('order_date', $order->order_date?->format('Y-m-d')) }}" class="w-full text-sm rounded-lg border-gray-300 focus:ring-indigo-500">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Expected Delivery Date') }}</label>
                        <input type="date" name="expected_delivery_date" value="{{ old('expected_delivery_date', $order->expected_delivery_date?->format('Y-m-d')) }}" class="w-full text-sm rounded-lg border-gray-300 focus:ring-indigo-500">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Order Status') }} <span class="text-rose-500">*</span></label>

                        <!-- Creative Order Status -->
                        <div x-show="orderType === 'creative'">
                            <select name="status" :disabled="orderType !== 'creative'" required class="w-full text-sm rounded-lg border-gray-300 focus:ring-indigo-500">
                                @if($currentStatus && !in_array($currentStatus, $creativeStatuses))
                                    <option value="{{ $currentStatus }}" selected>{{ $currentStatus }}</option>
                                @endif
                                @foreach($creativeStatuses as $st)
                                    <option value="{{ $st }}" @selected($currentStatus === $st)>{{ $st }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Ads Campaign Status -->
                        <div x-show="orderType === 'ads'" x-cloak>
                            <select name="status" :disabled="orderType !== 'ads'" required class="w-full text-sm rounded-lg border-gray-300 focus:ring-amber-500">
                                @if($currentStatus && !in_array($currentStatus, $adsStatuses))
                                    <option value="{{ $currentStatus }}" selected>{{ $currentStatus }}</option>
                                @endif
                                @foreach($adsStatuses as $st)
                                    <option value="{{ $st }}" @selected($currentStatus === $st)>{{ $st }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Website & Software Status -->
                        <div x-show="orderType === 'web_software'" x-cloak>
                            <select name="status" :disabled="orderType !== 'web_software'" required class="w-full text-sm rounded-lg border-gray-300 focus:ring-blue-500">
                                @if($currentStatus && !in_array($currentStatus, $softStatuses))
                                    <option value="{{ $currentStatus }}" selected>{{ $currentStatus }}</option>
                                @endif
                                @foreach($softStatuses as $st)
                                    <option value="{{ $st }}" @selected($currentStatus === $st)>{{ $st }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Sales Person') }}</label>
                        <select name="sales_person_id" class="w-full text-sm rounded-lg border-gray-300 focus:ring-indigo-500">
                            <option value="">{{ __('Unassigned') }}</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" @selected(old('sales_person_id', $order->sales_person_id) == $user->id)>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Project Manager (PM)') }}</label>
                        <select name="project_manager_id" class="w-full text-sm rounded-lg border-gray-300 focus:ring-indigo-500">
                            <option value="">{{ __('Assign PM Later') }}</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" @selected(old('project_manager_id', $order->project_manager_id) == $user->id)>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Order Notes & Specifications') }}</label>
                    <textarea name="notes" rows="3" placeholder="{{ __('Client terms, scope notes, special instructions...') }}" class="w-full text-sm rounded-lg border-gray-300 focus:ring-indigo-500">{{ old('notes', $order->notes) }}</textarea>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex items-center justify-end gap-3 pt-2">
                <a href="{{ route('orders.show', $order) }}" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-lg text-sm transition">
                    {{ __('Cancel') }}
                </a>
                <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg text-sm shadow-sm transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    {{ __('Save Changes') }}
                </button>
            </div>
        </form>
    </div>

    <script>
        function orderEditForm() {
            const allCustomers = @json($customers);
            const preselected = @json($selectedCustomer);
            const hasReceipts = @json($hasReceipts);
            const receiptPaid = {{ floatval($receiptPaidTotal) }};
            const initialTotal = {{ $initialTotal }};

            return {
                originalOrderType: @json($order->order_type ?? 'creative'),
                orderType: @json($currentOrderType),
                typeUnlocked: @json($currentOrderType !== ($order->order_type ?? 'creative')),
                orderTitle: @json(old('title', $order->title)),

                typeLabel(type) {
                    if (type === 'creative') return 'Creative Order';
                    if (type === 'ads') return 'Ads Campaign';
                    if (type === 'web_software') return 'Website & Software';
                    return type;
                },

                unlockOrderType() {
                    const msg = "{{ __('Warning: Changing order type converts this order to another format and hides previous format details. For separate services, create a separate order instead. Are you sure you want to unlock order type switching?') }}";
                    if (confirm(msg)) {
                        this.typeUnlocked = true;
                    }
                },

                switchOrderType(targetType) {
                    if (!this.typeUnlocked) return;
                    this.orderType = targetType;
                },

                revertOrderType() {
                    this.orderType = this.originalOrderType;
                    this.typeUnlocked = false;
                },

                confirmTypeChange(event) {
                    if (this.orderType !== this.originalOrderType) {
                        const msg = `{{ __('Confirm order type change from') }} "${this.typeLabel(this.originalOrderType)}" {{ __('to') }} "${this.typeLabel(this.orderType)}"?`;
                        if (!confirm(msg)) {
                            event.preventDefault();
                        }
                    }
                },

                // Client live search
                initialCustomers: allCustomers,
                searchResults: allCustomers,
                selectedCustomer: preselected || null,
                searchQuery: '',
                isOpen: false,
                isSearching: false,
                searchTimer: null,
                searchAbortController: null,

                // Totals & Paid state (synced across tabs from order's saved amount)
                creativeTotal: initialTotal,
                adsTotalAmount: initialTotal,
                softTotal: initialTotal,
                manualPaid: {{ $initialPaid }},

                // Ads calculations
                adsTotalDays: {{ is_numeric(old('meta.total_days', $meta['total_days'] ?? null)) ? floatval(old('meta.total_days', $meta['total_days'])) : 'null' }},
                adsPreviousDollar: {{ floatval(old('meta.previous_dollar', $meta['previous_dollar'] ?? 0)) }},
                adsRunningDollar: {{ floatval(old('meta.running_dollar', $meta['running_dollar'] ?? 0)) }},

                adsTotalDollarCost() {
                    const running = parseFloat(this.adsRunningDollar) || 0;
                    const previous = parseFloat(this.adsPreviousDollar) || 0;
                    return (running - previous).toFixed(2);
                },
                adsDollarDue() {
                    const budgetDollar = (parseFloat(this.adsTotalAmount) || 0) / 150;
                    const costDollar = parseFloat(this.adsTotalDollarCost()) || 0;
                    return (budgetDollar - costDollar).toFixed(2);
                },

                get filteredCustomers() {
                    if (!this.searchQuery.trim()) {
                        return this.initialCustomers.slice(0, 15);
                    }
                    return this.searchResults;
                },

                onSearchInput() {
                    const q = this.searchQuery.trim();
                    if (this.searchTimer) clearTimeout(this.searchTimer);
                    if (this.searchAbortController) {
                        this.searchAbortController.abort();
                    }

                    if (!q) {
                        this.searchResults = this.initialCustomers;
                        this.isSearching = false;
                        return;
                    }

                    this.isSearching = true;
                    this.searchTimer = setTimeout(async () => {
                        this.searchAbortController = new AbortController();
                        try {
                            const url = `{{ route('orders.search-clients') }}?q=${encodeURIComponent(q)}`;
                            const res = await fetch(url, {
                                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                                signal: this.searchAbortController.signal
                            });
                            if (!res.ok) throw new Error('Search failed');
                            const data = await res.json();
                            this.searchResults = data.results || [];
                        } catch (err) {
                            if (err.name !== 'AbortError') {
                                this.searchResults = [];
                            }
                        } finally {
                            this.isSearching = false;
                        }
                    }, 220);
                },

                selectCustomer(customer) {
                    this.selectedCustomer = customer;
                    this.isOpen = false;
                    this.searchQuery = '';
                    this.searchResults = this.initialCustomers;
                    this.isSearching = false;
                },

                clearCustomer() {
                    this.selectedCustomer = null;
                    this.searchQuery = '';
                    this.searchResults = this.initialCustomers;
                    this.isSearching = false;
                },

                currentTotal() {
                    if (this.orderType === 'creative') return parseFloat(this.creativeTotal) || 0;
                    if (this.orderType === 'ads') return parseFloat(this.adsTotalAmount) || 0;
                    if (this.orderType === 'web_software') return parseFloat(this.softTotal) || 0;
                    return 0;
                },

                currentPaid() {
                    if (this.orderType === 'ads') {
                        return Math.max(hasReceipts ? receiptPaid : 0, parseFloat(this.adsTotalAmount) || 0);
                    }
                    if (hasReceipts) {
                        return receiptPaid;
                    }
                    return parseFloat(this.manualPaid) || 0;
                }
            };
        }
    </script>
</x-app-layout>
