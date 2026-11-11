<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                    {{ __('Create New Order') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">{{ __('Enter client orders matching Creative, Ads Campaign, or Software & Web spreadsheet formats') }}</p>
            </div>
            <a href="{{ route('orders.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-200 transition">
                <svg class="w-4 h-4 me-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                {{ __('Back to Orders') }}
            </a>
        </div>
    </x-slot>

    <div class="w-full space-y-6" x-data="orderForm()">

            @if($preselectedQuotation)
                <div class="p-4 bg-indigo-50 border border-indigo-200 rounded-xl flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <span class="p-2 bg-indigo-600 text-white rounded-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </span>
                        <div>
                            <div class="text-sm font-bold text-indigo-900">{{ __('Converting from Quotation') }}: {{ $preselectedQuotation->quotation_code }}</div>
                            <div class="text-xs text-indigo-700">{{ __('Client') }}: {{ $preselectedQuotation->customer?->name ?? $preselectedQuotation->lead?->name }} | {{ __('Quoted Amount') }}: {{ __('BDT') }} {{ number_format($preselectedQuotation->grand_total, 2) }}</div>
                        </div>
                    </div>
                </div>
            @endif

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

            <form method="POST" action="{{ route('orders.store') }}" class="space-y-6">
                @csrf

                @if($preselectedQuotation)
                    <input type="hidden" name="quotation_id" value="{{ $preselectedQuotation->id }}">
                @endif

                <!-- SECTION 1: SEARCH & SELECT CLIENT VIA WHATSAPP (Live Searching Dropdown) -->
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 space-y-4">
                    <div class="flex items-center justify-between border-b pb-3">
                        <h3 class="text-base font-bold text-gray-900 flex items-center gap-2">
                            <span class="w-7 h-7 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold text-sm">1</span>
                            {{ __('Client Selection') }}
                            <span class="text-xs font-normal text-gray-500">({{ __('Search directly by WhatsApp number, Phone, or Name') }})</span>
                        </h3>
                        <span class="text-xs text-rose-500 font-semibold">* {{ __('Required') }}</span>
                    </div>

                    <!-- Hidden Customer ID input submitted with form -->
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
                                            <span x-text="selectedCustomer.company_name + ' &bull;'"></span>
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
                    <div x-show="!selectedCustomer" class="relative" @click.away="isOpen = false">
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

                        <!-- Dropdown Results -->
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

                <!-- SECTION 2: ORDER FORMAT SELECTOR (Tabs matching Excel sheets) -->
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 space-y-6">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between border-b pb-4 gap-3">
                        <div class="flex items-center gap-2">
                            <span class="w-7 h-7 rounded-lg bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold text-sm">2</span>
                            <div>
                                <h3 class="text-base font-bold text-gray-900">{{ __('Order Format & Type') }}</h3>
                                <p class="text-xs text-gray-500">{{ __('Select the exact format matching your operation') }}</p>
                            </div>
                        </div>

                        <!-- Tab buttons -->
                        <div class="flex items-center gap-1 bg-gray-100 p-1 rounded-xl">
                            <button type="button" @click="orderType = 'creative'" :class="orderType === 'creative' ? 'bg-white text-indigo-700 shadow-xs font-bold' : 'text-gray-600 hover:text-gray-900 font-medium'" class="px-3.5 py-1.5 rounded-lg text-xs transition flex items-center gap-1.5">
                                <span>🎨</span> {{ __('Creative Order') }}
                            </button>
                            <button type="button" @click="orderType = 'ads'" :class="orderType === 'ads' ? 'bg-white text-amber-700 shadow-xs font-bold' : 'text-gray-600 hover:text-gray-900 font-medium'" class="px-3.5 py-1.5 rounded-lg text-xs transition flex items-center gap-1.5">
                                <span>📈</span> {{ __('Ads Campaign') }}
                            </button>
                            <button type="button" @click="orderType = 'web_software'" :class="orderType === 'web_software' ? 'bg-white text-blue-700 shadow-xs font-bold' : 'text-gray-600 hover:text-gray-900 font-medium'" class="px-3.5 py-1.5 rounded-lg text-xs transition flex items-center gap-1.5">
                                <span>💻</span> {{ __('Website & Software') }}
                            </button>
                        </div>
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
                                        @foreach(['Video Edit', 'Graphics Design', 'SMO & SMM', 'Voice Recording', 'Others Work'] as $cat)
                                            <option value="{{ $cat }}" @selected(old('service_category') === $cat)>{{ $cat }}</option>
                                        @endforeach
                                    </optgroup>
                                </select>
                            </div>

                            <!-- Ads Campaign Category -->
                            <div x-show="orderType === 'ads'">
                                <select name="service_category" :disabled="orderType !== 'ads'" class="w-full text-sm rounded-lg border-gray-300 focus:ring-amber-500 focus:border-amber-500">
                                    <optgroup label="Ads Campaign">
                                        @foreach(['Meta Ads', 'TikTok Ads', 'YouTube Ads', 'LinkedIn Ads', 'Google Ads', 'Others Ads'] as $cat)
                                            <option value="{{ $cat }}" @selected(old('service_category') === $cat)>{{ $cat }}</option>
                                        @endforeach
                                    </optgroup>
                                </select>
                            </div>

                            <!-- Website & Software Category -->
                            <div x-show="orderType === 'web_software'">
                                <select name="service_category" :disabled="orderType !== 'web_software'" class="w-full text-sm rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                                    <optgroup label="Soft & Web Develop">
                                        @foreach(['ERP Solutions', 'E-commerce Management', 'Restaurant Management', 'Travel Agency Web', 'Salon Management', 'Cleaning Service Web', 'Construction Web', 'Company ERP', 'School Management', 'Hotel Management', 'APP Development', 'Custom Soft & Web'] as $cat)
                                            <option value="{{ $cat }}" @selected(old('service_category') === $cat)>{{ $cat }}</option>
                                        @endforeach
                                    </optgroup>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- ========================================== -->
                    <!-- TAB 1: CREATIVE ORDER FORMAT (Excel Sheet) -->
                    <!-- ========================================== -->
                    <div x-show="orderType === 'creative'" class="space-y-4 pt-2">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Service Provider') }}</label>
                                <input type="text" name="meta[service_provider]" value="{{ old('meta.service_provider') }}" :disabled="orderType !== 'creative'" placeholder="e.g. Mahfuz, Jahid Hasan" class="w-full text-sm rounded-lg border-gray-300 focus:ring-indigo-500">
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Starting Date') }}</label>
                                <input type="date" name="meta[starting_date]" value="{{ old('meta.starting_date') }}" :disabled="orderType !== 'creative'" class="w-full text-sm rounded-lg border-gray-300 focus:ring-indigo-500">
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Complete Date') }}</label>
                                <input type="date" name="meta[complete_date]" value="{{ old('meta.complete_date') }}" :disabled="orderType !== 'creative'" class="w-full text-sm rounded-lg border-gray-300 focus:ring-indigo-500">
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Why Not Delivery') }}</label>
                                <input type="text" name="meta[why_not_delivery]" value="{{ old('meta.why_not_delivery') }}" :disabled="orderType !== 'creative'" placeholder="e.g. Due Payment, Client Response Pending" class="w-full text-sm rounded-lg border-gray-300 focus:ring-indigo-500">
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Language') }}</label>
                                <input type="text" name="meta[language]" value="{{ old('meta.language') }}" :disabled="orderType !== 'creative'" placeholder="e.g. Bangla, Arabic, English" class="w-full text-sm rounded-lg border-gray-300 focus:ring-indigo-500">
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Script Status') }}</label>
                                <select name="meta[script_status]" :disabled="orderType !== 'creative'" class="w-full text-sm rounded-lg border-gray-300 focus:ring-indigo-500">
                                    @foreach(['Not ready', 'Ready', 'Cl-Checking', 'Confirmed', 'No need'] as $opt)
                                        <option value="{{ $opt }}" @selected(old('meta.script_status', 'Not ready') === $opt)>{{ $opt }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Script Code') }}</label>
                                <input type="text" name="meta[script_code]" value="{{ old('meta.script_code') }}" :disabled="orderType !== 'creative'" placeholder="e.g. SCR-901" class="w-full text-sm rounded-lg border-gray-300 focus:ring-indigo-500 font-mono">
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Voice Status') }}</label>
                                <select name="meta[voice_status]" :disabled="orderType !== 'creative'" class="w-full text-sm rounded-lg border-gray-300 focus:ring-indigo-500">
                                    @foreach(['Not ready', 'Ready', 'Cl-Checking', 'Confirmed', 'No need'] as $opt)
                                        <option value="{{ $opt }}" @selected(old('meta.voice_status', 'Not ready') === $opt)>{{ $opt }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Video Duration (Second)') }}</label>
                                <input type="number" name="meta[second]" value="{{ old('meta.second') }}" :disabled="orderType !== 'creative'" placeholder="e.g. 60" class="w-full text-sm rounded-lg border-gray-300 focus:ring-indigo-500 font-mono">
                            </div>

                            <div class="sm:col-span-2">
                                <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Voice Link / Audio URL') }}</label>
                                <input type="text" name="meta[voice_link]" value="{{ old('meta.voice_link') }}" :disabled="orderType !== 'creative'" placeholder="Google Drive audio link or URL" class="w-full text-sm rounded-lg border-gray-300 focus:ring-indigo-500">
                            </div>

                            <div class="sm:col-span-3">
                                <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Reference Link / Asset Drive') }}</label>
                                <input type="text" name="meta[reference_link]" value="{{ old('meta.reference_link') }}" :disabled="orderType !== 'creative'" placeholder="https://drive.google.com/..." class="w-full text-sm rounded-lg border-gray-300 focus:ring-indigo-500">
                            </div>
                        </div>

                        <!-- Creative Financials & Dynamic Payments -->
                        <div class="p-5 bg-indigo-50/50 rounded-2xl border border-indigo-100 space-y-4 mt-4">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-indigo-100 pb-3">
                                <div>
                                    <h4 class="text-xs font-bold text-indigo-950 uppercase tracking-wider flex items-center gap-1.5">
                                        <span>💳</span> {{ __('Creative Payment & Dynamic Installments') }}
                                    </h4>
                                    <p class="text-2xs text-gray-500">{{ __('Enter date, amount, and source for each full or partial installment payment received') }}</p>
                                </div>
                                <div class="flex items-center gap-4">
                                    <div>
                                        <label class="block text-2xs font-semibold text-gray-700 uppercase mb-1">{{ __('Initial Total (BDT)') }} <span class="text-rose-500">*</span></label>
                                        <input type="number" step="0.01" x-model.number="creativeTotal" placeholder="0.00" class="w-40 text-sm font-bold font-mono rounded-lg border-gray-300 focus:ring-indigo-500">
                                    </div>
                                </div>
                            </div>

                            <!-- Dynamic Payment Rows -->
                            <div class="space-y-2.5">
                                <label class="block text-2xs font-bold text-gray-600 uppercase">{{ __('Payment History / Installments (Date, Amount & Source)') }}</label>
                                
                                <template x-for="(pay, index) in creativePayments" :key="index">
                                    <div class="flex flex-wrap sm:flex-nowrap items-center gap-2 p-2.5 bg-white rounded-xl border border-indigo-100 shadow-2xs">
                                        <span class="text-xs font-bold text-indigo-600 font-mono w-6 text-center" x-text="'#' + (index + 1)"></span>
                                        
                                        <!-- Payment Date -->
                                        <div class="w-full sm:w-1/3">
                                            <input type="date" :name="'creative_payments[' + index + '][payment_date]'" x-model="pay.payment_date" :disabled="orderType !== 'creative'" class="w-full text-xs rounded-lg border-gray-300 focus:ring-indigo-500">
                                        </div>

                                        <!-- Amount -->
                                        <div class="w-full sm:w-1/3">
                                            <div class="relative">
                                                <span class="absolute left-2.5 top-2 text-xs text-gray-400 font-bold">BDT</span>
                                                <input type="number" step="0.01" :name="'creative_payments[' + index + '][amount]'" x-model.number="pay.amount" placeholder="0.00" :disabled="orderType !== 'creative'" class="w-full text-xs font-mono font-bold pl-12 rounded-lg border-gray-300 focus:ring-indigo-500">
                                            </div>
                                        </div>

                                        <!-- Payment Source -->
                                        <div class="w-full sm:w-1/3">
                                            <select :name="'creative_payments[' + index + '][payment_method]'" x-model="pay.payment_method" :disabled="orderType !== 'creative'" class="w-full text-xs rounded-lg border-gray-300 focus:ring-indigo-500 font-semibold">
                                                <option value="Cash">💵 Cash</option>
                                                <option value="Bank">🏦 Bank</option>
                                                <option value="bKash">📱 bKash</option>
                                                <option value="Nagad">📱 Nagad</option>
                                                <option value="Rocket">📱 Rocket</option>
                                            </select>
                                        </div>

                                        <!-- Remove Row Button -->
                                        <button type="button" @click="removeCreativePayment(index)" x-show="creativePayments.length > 1" class="p-1.5 text-gray-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition" title="{{ __('Remove') }}">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </div>
                                </template>

                                <button type="button" @click="addCreativePayment()" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 rounded-lg text-xs font-semibold transition mt-1 border border-indigo-200">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                                    <span>{{ __('+ Add Another Payment / Installment') }}</span>
                                </button>
                            </div>

                            <!-- Live Calculation Bar -->
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between p-3 bg-white rounded-xl border border-indigo-200 text-xs gap-3 mt-3">
                                <div class="flex items-center gap-4">
                                    <span class="text-gray-600">{{ __('Initial Total:') }} <strong class="text-indigo-900 font-mono font-bold" x-text="'BDT ' + (parseFloat(creativeTotal) || 0).toLocaleString()"></strong></span>
                                    <span class="text-gray-600">{{ __('Total Paid:') }} <strong class="text-emerald-700 font-mono font-bold" x-text="'BDT ' + creativeTotalPaid().toLocaleString()"></strong></span>
                                </div>
                                <div>
                                    <span class="text-gray-600">{{ __('Calculated Due:') }} <strong class="text-rose-600 font-mono font-bold text-sm" x-text="'BDT ' + creativeDue().toLocaleString()"></strong></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ============================================== -->
                    <!-- TAB 2: ADS CAMPAIGN ORDER FORMAT (Excel Sheet) -->
                    <!-- ============================================== -->
                    <div x-show="orderType === 'ads'" class="space-y-4 pt-2">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                            <div class="sm:col-span-2">
                                <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Ads Post Link') }}</label>
                                <input type="text" name="meta[ads_post_link]" value="{{ old('meta.ads_post_link') }}" :disabled="orderType !== 'ads'" placeholder="https://facebook.com/post/..." class="w-full text-sm rounded-lg border-gray-300 focus:ring-amber-500">
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Lifetime/ Daily Budget ($)') }}</label>
                                <input type="number" step="0.01" name="meta[lifetime_daily_budget]" value="{{ old('meta.lifetime_daily_budget') }}" :disabled="orderType !== 'ads'" placeholder="e.g. 5.00 or 150.00" class="w-full text-sm rounded-lg border-gray-300 focus:ring-amber-500 font-mono font-bold">
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
                                <input type="date" name="meta[starting_date]" value="{{ old('meta.starting_date') }}" :disabled="orderType !== 'ads'" class="w-full text-sm rounded-lg border-gray-300 focus:ring-amber-500">
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('End Date') }}</label>
                                <input type="date" name="meta[end_date]" value="{{ old('meta.end_date') }}" :disabled="orderType !== 'ads'" class="w-full text-sm rounded-lg border-gray-300 focus:ring-amber-500">
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

                        <!-- Ads BDT Budget (Auto Full Paid) -->
                        <div class="p-4 bg-amber-50/50 rounded-xl border border-amber-200 space-y-3 mt-4">
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 items-center">
                                <div>
                                    <label class="block text-2xs font-semibold text-gray-700 uppercase mb-1">{{ __('Total Amount (BDT)') }} <span class="text-rose-500">*</span></label>
                                    <input type="number" step="0.01" x-model.number="adsTotalAmount" placeholder="0.00" class="w-full text-sm font-bold font-mono rounded-lg border-gray-300 focus:ring-amber-500">
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

                            <!-- Live Full-Paid Bar -->
                            <div class="flex flex-wrap items-center justify-between p-2.5 bg-white rounded-xl border border-amber-200 text-xs gap-3">
                                <div class="flex items-center gap-4">
                                    <span class="text-gray-600">{{ __('Total Amount:') }} <strong class="text-amber-900 font-mono font-bold" x-text="'BDT ' + (parseFloat(adsTotalAmount) || 0).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})"></strong></span>
                                    <span class="text-gray-600">{{ __('Paid Amount (Auto Receipt):') }} <strong class="text-emerald-700 font-mono font-bold" x-text="'BDT ' + (parseFloat(adsTotalAmount) || 0).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})"></strong></span>
                                </div>
                                <div>
                                    <span class="text-gray-600">{{ __('Due:') }} <strong class="text-emerald-600 font-mono font-bold">BDT 0.00 ({{ __('Full Paid') }})</strong></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ============================================== -->
                    <!-- TAB 3: SOFT & WEB ORDER FORMAT (Excel Sheet)  -->
                    <!-- ============================================== -->
                    <div x-show="orderType === 'web_software'" class="space-y-4 pt-2">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Model') }}</label>
                                <select name="meta[model]" :disabled="orderType !== 'web_software'" class="w-full text-sm rounded-lg border-gray-300 focus:ring-blue-500 font-medium">
                                    <option value="Agile Model" @selected(old('meta.model', 'Agile Model') === 'Agile Model')>Agile Model</option>
                                    <option value="Waterfall Model" @selected(old('meta.model') === 'Waterfall Model')>Waterfall Model</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Working Priority') }}</label>
                                <select name="meta[working_priority]" :disabled="orderType !== 'web_software'" class="w-full text-sm rounded-lg border-gray-300 focus:ring-blue-500">
                                    <option value="Normal" @selected(old('meta.working_priority', 'Normal') === 'Normal')>Normal</option>
                                    <option value="High" @selected(old('meta.working_priority') === 'High')>High</option>
                                    <option value="Urgent" @selected(old('meta.working_priority') === 'Urgent')>Urgent</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Subscription System') }}</label>
                                <select name="meta[subscription_system]" :disabled="orderType !== 'web_software'" class="w-full text-sm rounded-lg border-gray-300 focus:ring-blue-500">
                                    @foreach(['One-Time Payment', 'Monthly Subscription', 'Yearly Subscription'] as $subOpt)
                                        <option value="{{ $subOpt }}" @selected(old('meta.subscription_system', 'One-Time Payment') === $subOpt)>{{ $subOpt }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Subscription / Renew Fee') }}</label>
                                <input type="number" step="0.01" name="meta[subscription_fee]" value="{{ old('meta.subscription_fee') }}" :disabled="orderType !== 'web_software'" placeholder="e.g. 2000" class="w-full text-sm rounded-lg border-gray-300 focus:ring-blue-500 font-mono">
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Monthly Due (BDT)') }}</label>
                                <input type="number" step="0.01" name="meta[monthly_due]" value="{{ old('meta.monthly_due') }}" :disabled="orderType !== 'web_software'" placeholder="0.00" class="w-full text-sm rounded-lg border-gray-300 focus:ring-blue-500 font-mono">
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Yearly Due (BDT)') }}</label>
                                <input type="number" step="0.01" name="meta[yearly_due]" value="{{ old('meta.yearly_due') }}" :disabled="orderType !== 'web_software'" placeholder="0.00" class="w-full text-sm rounded-lg border-gray-300 focus:ring-blue-500 font-mono">
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Next Renew Payment Date') }}</label>
                                <input type="date" name="meta[next_renew_date]" value="{{ old('meta.next_renew_date') }}" :disabled="orderType !== 'web_software'" class="w-full text-sm rounded-lg border-gray-300 focus:ring-blue-500">
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Renew Status') }}</label>
                                <select name="meta[renew_status]" :disabled="orderType !== 'web_software'" class="w-full text-sm rounded-lg border-gray-300 focus:ring-blue-500">
                                    @foreach(['Not Required', 'Active', 'Upcoming', 'Due', 'Expired', 'Cancelled'] as $rOpt)
                                        <option value="{{ $rOpt }}" @selected(old('meta.renew_status', 'Not Required') === $rOpt)>{{ $rOpt }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Purchase Date') }}</label>
                                <input type="date" name="meta[purchase_date]" value="{{ old('meta.purchase_date') }}" :disabled="orderType !== 'web_software'" class="w-full text-sm rounded-lg border-gray-300 focus:ring-blue-500">
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Complete Date') }}</label>
                                <input type="date" name="meta[complete_date]" value="{{ old('meta.complete_date') }}" :disabled="orderType !== 'web_software'" class="w-full text-sm rounded-lg border-gray-300 focus:ring-blue-500">
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Developed Assign') }}</label>
                                <input type="text" name="meta[developed_assign]" value="{{ old('meta.developed_assign') }}" :disabled="orderType !== 'web_software'" placeholder="Developer Name" class="w-full text-sm rounded-lg border-gray-300 focus:ring-blue-500">
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Customize Assign') }}</label>
                                <input type="text" name="meta[customize_assign]" value="{{ old('meta.customize_assign') }}" :disabled="orderType !== 'web_software'" placeholder="Customizer / PM Name" class="w-full text-sm rounded-lg border-gray-300 focus:ring-blue-500">
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Domain / Live URL') }}</label>
                                <input type="text" name="meta[domain]" value="{{ old('meta.domain') }}" :disabled="orderType !== 'web_software'" placeholder="https://clientdomain.com" class="w-full text-sm rounded-lg border-gray-300 focus:ring-blue-500">
                            </div>
                        </div>

                        <!-- Website & Software Payment & Dynamic Installments -->
                        <div class="p-5 bg-blue-50/50 rounded-2xl border border-blue-200 space-y-4 mt-4">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-blue-200 pb-3">
                                <div>
                                    <h4 class="text-xs font-bold text-blue-950 uppercase tracking-wider flex items-center gap-1.5">
                                        <span>💳</span> {{ __('Software & Web Payment & Dynamic Installments') }}
                                    </h4>
                                    <p class="text-2xs text-gray-500">{{ __('Enter date, amount, and source for each full or partial installment payment received') }}</p>
                                </div>
                                <div class="flex items-center gap-4">
                                    <div>
                                        <label class="block text-2xs font-semibold text-gray-700 uppercase mb-1">{{ __('Initial Total (BDT)') }} <span class="text-rose-500">*</span></label>
                                        <input type="number" step="0.01" x-model.number="softTotal" placeholder="0.00" class="w-40 text-sm font-bold font-mono rounded-lg border-gray-300 focus:ring-blue-500">
                                    </div>
                                </div>
                            </div>

                            <!-- Dynamic Payment Rows -->
                            <div class="space-y-2.5">
                                <label class="block text-2xs font-bold text-gray-600 uppercase">{{ __('Payment History / Installments (Date, Amount & Source)') }}</label>
                                
                                <template x-for="(pay, index) in softPayments" :key="index">
                                    <div class="flex flex-wrap sm:flex-nowrap items-center gap-2 p-2.5 bg-white rounded-xl border border-blue-100 shadow-2xs">
                                        <span class="text-xs font-bold text-blue-600 font-mono w-6 text-center" x-text="'#' + (index + 1)"></span>
                                        
                                        <!-- Payment Date -->
                                        <div class="w-full sm:w-1/3">
                                            <input type="date" :name="'soft_payments[' + index + '][payment_date]'" x-model="pay.payment_date" :disabled="orderType !== 'web_software'" class="w-full text-xs rounded-lg border-gray-300 focus:ring-blue-500">
                                        </div>

                                        <!-- Amount -->
                                        <div class="w-full sm:w-1/3">
                                            <div class="relative">
                                                <span class="absolute left-2.5 top-2 text-xs text-gray-400 font-bold">BDT</span>
                                                <input type="number" step="0.01" :name="'soft_payments[' + index + '][amount]'" x-model.number="pay.amount" placeholder="0.00" :disabled="orderType !== 'web_software'" class="w-full text-xs font-mono font-bold pl-12 rounded-lg border-gray-300 focus:ring-blue-500">
                                            </div>
                                        </div>

                                        <!-- Payment Source -->
                                        <div class="w-full sm:w-1/3">
                                            <select :name="'soft_payments[' + index + '][payment_method]'" x-model="pay.payment_method" :disabled="orderType !== 'web_software'" class="w-full text-xs rounded-lg border-gray-300 focus:ring-blue-500 font-semibold">
                                                <option value="Cash">💵 Cash</option>
                                                <option value="Bank">🏦 Bank</option>
                                                <option value="bKash">📱 bKash</option>
                                                <option value="Nagad">📱 Nagad</option>
                                                <option value="Rocket">📱 Rocket</option>
                                            </select>
                                        </div>

                                        <!-- Remove Row Button -->
                                        <button type="button" @click="removeSoftPayment(index)" x-show="softPayments.length > 1" class="p-1.5 text-gray-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition" title="{{ __('Remove') }}">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </div>
                                </template>

                                <button type="button" @click="addSoftPayment()" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-700 rounded-lg text-xs font-semibold transition mt-1 border border-blue-200">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                                    <span>{{ __('+ Add Another Payment / Installment') }}</span>
                                </button>
                            </div>

                            <!-- Live Calculation Bar & Next Due Date -->
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between p-3 bg-white rounded-xl border border-blue-200 text-xs gap-3 mt-3">
                                <div class="flex items-center gap-4">
                                    <span class="text-gray-600">{{ __('Initial Total:') }} <strong class="text-blue-950 font-mono font-bold" x-text="'BDT ' + (parseFloat(softTotal) || 0).toLocaleString()"></strong></span>
                                    <span class="text-gray-600">{{ __('Total Paid:') }} <strong class="text-emerald-700 font-mono font-bold" x-text="'BDT ' + softTotalPaid().toLocaleString()"></strong></span>
                                    <span class="text-gray-600">{{ __('Calculated Due:') }} <strong class="text-rose-600 font-mono font-bold text-sm" x-text="'BDT ' + softDue().toLocaleString()"></strong></span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <label class="text-2xs font-semibold text-gray-700 uppercase whitespace-nowrap">{{ __('Initial Next Due Pay Date') }}:</label>
                                    <input type="date" name="meta[initial_next_due_date]" value="{{ old('meta.initial_next_due_date') }}" :disabled="orderType !== 'web_software'" class="text-xs rounded-lg border-gray-300 focus:ring-blue-500 py-1 px-2 font-mono">
                                </div>
                            </div>
                        </div>

                        <!-- Credentials & Links (Excel Sheet Soft & Web columns 35-41) -->
                        <div class="p-4 bg-gray-50 rounded-xl border border-gray-200 space-y-3 mt-4">
                            <h4 class="text-xs font-bold text-gray-800 uppercase tracking-wider flex items-center gap-1.5">
                                <span>🔐</span> {{ __('Admin Credentials & Access Portals') }}
                            </h4>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                <div>
                                    <label class="block text-2xs font-semibold text-gray-600 uppercase mb-1">{{ __('Official Admin Link') }}</label>
                                    <input type="text" name="meta[official_admin_link]" value="{{ old('meta.official_admin_link') }}" :disabled="orderType !== 'web_software'" placeholder="https://crm.../admin" class="w-full text-xs font-mono rounded-lg border-gray-300">
                                </div>
                                <div>
                                    <label class="block text-2xs font-semibold text-gray-600 uppercase mb-1">{{ __('Official User ID') }}</label>
                                    <input type="text" name="meta[official_user_id]" value="{{ old('meta.official_user_id') }}" :disabled="orderType !== 'web_software'" placeholder="admin_user" class="w-full text-xs font-mono rounded-lg border-gray-300">
                                </div>
                                <div>
                                    <label class="block text-2xs font-semibold text-gray-600 uppercase mb-1">{{ __('Official Password') }}</label>
                                    <input type="text" name="meta[official_password]" value="{{ old('meta.official_password') }}" :disabled="orderType !== 'web_software'" placeholder="Secret password" class="w-full text-xs font-mono rounded-lg border-gray-300">
                                </div>

                                <div>
                                    <label class="block text-2xs font-semibold text-gray-600 uppercase mb-1">{{ __('Client Admin Link') }}</label>
                                    <input type="text" name="meta[client_admin_link]" value="{{ old('meta.client_admin_link') }}" :disabled="orderType !== 'web_software'" placeholder="https://client.../wp-admin" class="w-full text-xs font-mono rounded-lg border-gray-300">
                                </div>
                                <div>
                                    <label class="block text-2xs font-semibold text-gray-600 uppercase mb-1">{{ __('Client User ID') }}</label>
                                    <input type="text" name="meta[client_user_id]" value="{{ old('meta.client_user_id') }}" :disabled="orderType !== 'web_software'" placeholder="client_admin" class="w-full text-xs font-mono rounded-lg border-gray-300">
                                </div>
                                <div>
                                    <label class="block text-2xs font-semibold text-gray-600 uppercase mb-1">{{ __('Client Password') }}</label>
                                    <input type="text" name="meta[client_password]" value="{{ old('meta.client_password') }}" :disabled="orderType !== 'web_software'" placeholder="Client secret password" class="w-full text-xs font-mono rounded-lg border-gray-300">
                                </div>

                                <div class="sm:col-span-3">
                                    <label class="block text-2xs font-semibold text-gray-600 uppercase mb-1">{{ __('Client Others User ID & Password') }}</label>
                                    <input type="text" name="meta[client_other_credentials]" value="{{ old('meta.client_other_credentials') }}" :disabled="orderType !== 'web_software'" placeholder="cPanel, DB, Hosting or extra staff credentials..." class="w-full text-xs font-mono rounded-lg border-gray-300">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SECTION 3: COMMON SCHEDULE & ASSIGNMENT -->
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 space-y-4">
                    <h3 class="text-base font-bold text-gray-900 flex items-center gap-2 border-b pb-3">
                        <span class="w-7 h-7 rounded-lg bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold text-sm">3</span>
                        {{ __('Schedule, Project Assignment & Auto-Initialization') }}
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Order Date') }} <span class="text-rose-500">*</span></label>
                            <input type="date" name="order_date" required value="{{ old('order_date', date('Y-m-d')) }}" class="w-full text-sm rounded-lg border-gray-300 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Expected Delivery Date') }}</label>
                            <input type="date" name="expected_delivery_date" value="{{ old('expected_delivery_date', date('Y-m-d', strtotime('+15 days'))) }}" class="w-full text-sm rounded-lg border-gray-300 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Order Status') }} <span class="text-rose-500">*</span></label>
                            
                            <!-- Creative Order Status -->
                            <div x-show="orderType === 'creative'">
                                <select name="status" :disabled="orderType !== 'creative'" required class="w-full text-sm rounded-lg border-gray-300 focus:ring-indigo-500">
                                    @foreach(['Pending', 'Working', 'Re-working', 'Pused', 'Complete', 'Delivery', 'Delay', 'Closed'] as $st)
                                        <option value="{{ $st }}" {{ old('status', 'Pending') === $st ? 'selected' : '' }}>{{ $st }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Ads Campaign Status -->
                            <div x-show="orderType === 'ads'">
                                <select name="status" :disabled="orderType !== 'ads'" required class="w-full text-sm rounded-lg border-gray-300 focus:ring-amber-500">
                                    @foreach(['Pending', 'Pused', 'Active', 'Closed'] as $st)
                                        <option value="{{ $st }}" {{ old('status', 'Pending') === $st ? 'selected' : '' }}>{{ $st }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Website & Software Status -->
                            <div x-show="orderType === 'web_software'">
                                <select name="status" :disabled="orderType !== 'web_software'" required class="w-full text-sm rounded-lg border-gray-300 focus:ring-blue-500">
                                    @foreach(['New Order', 'Requirements', 'Planning/ SRS', 'UI/UX Design', 'On Hold', 'In Development', 'Dev. Review', 'Testing/ QA', 'Customization', 'Client Review', 'Modification', 'Delivery Ready', 'Delivered', 'Active', 'Cancel', 'Support'] as $st)
                                        <option value="{{ $st }}" {{ old('status', 'New Order') === $st ? 'selected' : '' }}>{{ $st }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Sales Person') }}</label>
                            <select name="sales_person_id" class="w-full text-sm rounded-lg border-gray-300 focus:ring-indigo-500">
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" @selected(old('sales_person_id', auth()->id()) == $user->id)>
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
                                    <option value="{{ $user->id }}" @selected(old('project_manager_id') == $user->id)>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="pt-2">
                        <label class="inline-flex items-center gap-2 cursor-pointer p-3 bg-indigo-50/60 rounded-xl border border-indigo-200 w-full hover:bg-indigo-50 transition">
                            <input type="checkbox" name="create_project" value="1" @checked(old('_token') !== null ? (bool) old('create_project') : true) class="rounded border-indigo-300 text-indigo-600 focus:ring-indigo-500">
                            <div class="text-xs">
                                <span class="font-bold text-indigo-900 block">{{ __('Automatically initialize client project workspace') }}</span>
                                <span class="text-indigo-700">{{ __('Creates project tracking record linked to this order for tasks and milestones.') }}</span>
                            </div>
                        </label>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Order Notes & Specifications') }}</label>
                        <textarea name="notes" rows="2" placeholder="{{ __('Client terms, scope notes, special instructions...') }}" class="w-full text-sm rounded-lg border-gray-300 focus:ring-indigo-500">{{ old('notes') }}</textarea>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="flex items-center justify-end gap-3 pt-4">
                    <a href="{{ route('orders.index') }}" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-lg text-sm transition">
                        {{ __('Cancel') }}
                    </a>
                    <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg text-sm shadow-sm transition flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        {{ __('Create & Confirm Order') }}
                    </button>
                </div>
            </form>
        </div>

    <!-- Alpine.js script for live customer WhatsApp searching & calculations -->
    <script>
        function orderForm() {
            const allCustomers = @json($customers);
            const preselected = @json($preselectedCustomer);
            @php
                $oldOrderType = old('order_type', $orderType ?? 'creative');
                $defaultPaymentRow = [['payment_date' => date('Y-m-d'), 'amount' => '', 'payment_method' => 'Cash']];
                $oldCreativePayments = array_values(old('creative_payments', $defaultPaymentRow));
                $oldSoftPayments = array_values(old('soft_payments', $defaultPaymentRow));
            @endphp

            return {
                orderType: @json($oldOrderType),
                orderTitle: @json(old('title', $preselectedQuotation?->title ?? '')),
                serviceCategory: @json(old('service_category', 'Video Edit')),
                
                // Customer live search (AJAX across all Customers & Leads)
                initialCustomers: allCustomers,
                searchResults: allCustomers,
                selectedCustomer: preselected || null,
                searchQuery: '',
                isOpen: false,
                isSearching: false,
                searchTimer: null,
                searchAbortController: null,

                // Creative amounts
                creativeTotal: {{ $oldOrderType === 'creative' ? floatval(old('total_amount', 0)) : 0 }},
                creativePayments: @json($oldCreativePayments),
                addCreativePayment() {
                    this.creativePayments.push({
                        payment_date: '{{ date("Y-m-d") }}',
                        amount: '',
                        payment_method: 'Cash'
                    });
                },
                removeCreativePayment(index) {
                    this.creativePayments.splice(index, 1);
                },
                creativeTotalPaid() {
                    return this.creativePayments.reduce((sum, p) => sum + (parseFloat(p.amount) || 0), 0);
                },
                creativeDue() {
                    return Math.max(0, (parseFloat(this.creativeTotal) || 0) - this.creativeTotalPaid());
                },

                // Ads amounts
                adsDailyCost: 0,
                adsTotalDays: {{ is_numeric(old('meta.total_days')) ? floatval(old('meta.total_days')) : 0 }},
                adsTotalDollar: 0,
                adsTotalAmount: {{ $oldOrderType === 'ads' ? floatval(old('total_amount', 0)) : 0 }},
                adsPaidAmount: 0,
                adsPreviousDollar: {{ floatval(old('meta.previous_dollar', 0)) }},
                adsRunningDollar: {{ floatval(old('meta.running_dollar', 0)) }},
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

                // Soft amounts
                softTotal: {{ $oldOrderType === 'web_software' ? floatval(old('total_amount', 0)) : 0 }},
                softPayments: @json($oldSoftPayments),
                addSoftPayment() {
                    this.softPayments.push({
                        payment_date: '{{ date("Y-m-d") }}',
                        amount: '',
                        payment_method: 'Cash'
                    });
                },
                removeSoftPayment(index) {
                    this.softPayments.splice(index, 1);
                },
                softTotalPaid() {
                    return this.softPayments.reduce((sum, p) => sum + (parseFloat(p.amount) || 0), 0);
                },
                softDue() {
                    return Math.max(0, (parseFloat(this.softTotal) || 0) - this.softTotalPaid());
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
                    if (this.orderType === 'creative') {
                        return this.creativeTotalPaid();
                    }
                    if (this.orderType === 'ads') {
                        return parseFloat(this.adsTotalAmount) || 0;
                    }
                    if (this.orderType === 'web_software') {
                        return this.softTotalPaid();
                    }
                    return 0;
                }
            };
        }
    </script>
</x-app-layout>
