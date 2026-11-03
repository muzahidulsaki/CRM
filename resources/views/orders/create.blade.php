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

    <div class="py-6" x-data="orderForm()">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

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
                                    <span x-text="selectedCustomer.name.charAt(0)"></span>
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
                                        <span class="font-mono font-semibold" x-text="'📞 ' + selectedCustomer.phone"></span>
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
                                @input="isOpen = true"
                                placeholder="{{ __('Type Client WhatsApp Number (e.g. 966..., 017...) or Name to search...') }}"
                                class="w-full text-sm pl-10 pr-4 py-2.5 rounded-xl border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 shadow-2xs font-mono"
                            >
                            <svg class="w-5 h-5 text-emerald-600 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
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
                                            <span x-text="cust.whatsapp ? cust.whatsapp : cust.phone"></span>
                                        </div>
                                        <div class="text-[10px] text-gray-400 font-mono" x-text="'📞 ' + cust.phone"></div>
                                    </div>
                                </div>
                            </template>
                            <div x-show="filteredCustomers.length === 0" class="p-4 text-center text-xs text-gray-400">
                                {{ __('No matching client found for this WhatsApp number or name.') }}
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
                                        <option value="Video Edit">Video Edit</option>
                                        <option value="Graphics Design">Graphics Design</option>
                                        <option value="SMO & SMM">SMO & SMM</option>
                                        <option value="Voice Recording">Voice Recording</option>
                                        <option value="Others Work">Others Work</option>
                                    </optgroup>
                                </select>
                            </div>

                            <!-- Ads Campaign Category -->
                            <div x-show="orderType === 'ads'">
                                <select name="service_category" :disabled="orderType !== 'ads'" class="w-full text-sm rounded-lg border-gray-300 focus:ring-amber-500 focus:border-amber-500">
                                    <optgroup label="Ads Campaign">
                                        <option value="Meta Ads">Meta Ads</option>
                                        <option value="TikTok Ads">TikTok Ads</option>
                                        <option value="YouTube Ads">YouTube Ads</option>
                                        <option value="LinkedIn Ads">LinkedIn Ads</option>
                                        <option value="Google Ads">Google Ads</option>
                                        <option value="Others Ads">Others Ads</option>
                                    </optgroup>
                                </select>
                            </div>

                            <!-- Website & Software Category -->
                            <div x-show="orderType === 'web_software'">
                                <select name="service_category" :disabled="orderType !== 'web_software'" class="w-full text-sm rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                                    <optgroup label="Soft & Web Develop">
                                        <option value="ERP Solutions">ERP Solutions</option>
                                        <option value="E-commerce Management">E-commerce Management</option>
                                        <option value="Restaurant Management">Restaurant Management</option>
                                        <option value="Travel Agency Web">Travel Agency Web</option>
                                        <option value="Salon Management">Salon Management</option>
                                        <option value="Cleaning Service Web">Cleaning Service Web</option>
                                        <option value="Construction Web">Construction Web</option>
                                        <option value="Company ERP">Company ERP</option>
                                        <option value="School Management">School Management</option>
                                        <option value="Hotel Management">Hotel Management</option>
                                        <option value="APP Development">APP Development</option>
                                        <option value="Custom Soft & Web">Custom Soft & Web</option>
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
                                <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Order Source') }}</label>
                                <select name="order_source" :disabled="orderType !== 'creative'" class="w-full text-sm rounded-lg border-gray-300 focus:ring-indigo-500">
                                    <option value="Direct">Direct</option>
                                    <option value="Repeat">Repeat</option>
                                    <option value="Referral">Referral</option>
                                    <option value="Local">Local</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Activity Status') }}</label>
                                <select name="activity_status" :disabled="orderType !== 'creative'" class="w-full text-sm rounded-lg border-gray-300 focus:ring-indigo-500">
                                    <option value="Active">Active</option>
                                    <option value="Open">Open</option>
                                    <option value="Closed">Closed</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Service Provider') }}</label>
                                <input type="text" name="meta[service_provider]" :disabled="orderType !== 'creative'" placeholder="e.g. Mahfuz, Jahid Hasan" class="w-full text-sm rounded-lg border-gray-300 focus:ring-indigo-500">
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Starting Date') }}</label>
                                <input type="date" name="meta[starting_date]" :disabled="orderType !== 'creative'" class="w-full text-sm rounded-lg border-gray-300 focus:ring-indigo-500">
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Complete Date') }}</label>
                                <input type="date" name="meta[complete_date]" :disabled="orderType !== 'creative'" class="w-full text-sm rounded-lg border-gray-300 focus:ring-indigo-500">
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Why Not Delivery') }}</label>
                                <input type="text" name="meta[why_not_delivery]" :disabled="orderType !== 'creative'" placeholder="e.g. Due Payment, Client Response Pending" class="w-full text-sm rounded-lg border-gray-300 focus:ring-indigo-500">
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Language') }}</label>
                                <input type="text" name="meta[language]" :disabled="orderType !== 'creative'" placeholder="e.g. Bangla, Arabic, English" class="w-full text-sm rounded-lg border-gray-300 focus:ring-indigo-500">
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Script / Idea') }}</label>
                                <input type="text" name="meta[script_idea]" :disabled="orderType !== 'creative'" placeholder="e.g. Confirmed, Client Provided" class="w-full text-sm rounded-lg border-gray-300 focus:ring-indigo-500">
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Script Code') }}</label>
                                <input type="text" name="meta[script_code]" :disabled="orderType !== 'creative'" placeholder="e.g. SCR-901" class="w-full text-sm rounded-lg border-gray-300 focus:ring-indigo-500 font-mono">
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Voice / Design') }}</label>
                                <input type="text" name="meta[voice_design]" :disabled="orderType !== 'creative'" placeholder="e.g. Confirmed, Female VO" class="w-full text-sm rounded-lg border-gray-300 focus:ring-indigo-500">
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Video Duration (Second)') }}</label>
                                <input type="number" name="meta[second]" :disabled="orderType !== 'creative'" placeholder="e.g. 60" class="w-full text-sm rounded-lg border-gray-300 focus:ring-indigo-500 font-mono">
                            </div>

                            <div class="sm:col-span-2">
                                <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Voice Link / Audio URL') }}</label>
                                <input type="text" name="meta[voice_link]" :disabled="orderType !== 'creative'" placeholder="Google Drive audio link or URL" class="w-full text-sm rounded-lg border-gray-300 focus:ring-indigo-500">
                            </div>

                            <div class="sm:col-span-3">
                                <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Reference Link / Asset Drive') }}</label>
                                <input type="text" name="meta[reference_link]" :disabled="orderType !== 'creative'" placeholder="https://drive.google.com/..." class="w-full text-sm rounded-lg border-gray-300 focus:ring-indigo-500">
                            </div>
                        </div>

                        <!-- Creative Financials & 3-Step Payments -->
                        <div class="p-4 bg-indigo-50/50 rounded-xl border border-indigo-100 space-y-3 mt-4">
                            <h4 class="text-xs font-bold text-indigo-900 uppercase tracking-wider">{{ __('Creative Payment & Installments') }}</h4>
                            <div class="grid grid-cols-1 sm:grid-cols-5 gap-3">
                                <div>
                                    <label class="block text-2xs font-semibold text-gray-600 uppercase mb-1">{{ __('Initial Total (BDT)') }} <span class="text-rose-500">*</span></label>
                                    <input type="number" step="0.01" x-model.number="creativeTotal" placeholder="0.00" class="w-full text-sm font-bold font-mono rounded-lg border-gray-300 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-2xs font-semibold text-gray-600 uppercase mb-1">{{ __('1st Pay (BDT)') }}</label>
                                    <input type="number" step="0.01" name="meta[pay_1st]" :disabled="orderType !== 'creative'" x-model.number="creativePay1" placeholder="0.00" class="w-full text-sm font-mono rounded-lg border-gray-300 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-2xs font-semibold text-gray-600 uppercase mb-1">{{ __('2nd Pay (BDT)') }}</label>
                                    <input type="number" step="0.01" name="meta[pay_2nd]" :disabled="orderType !== 'creative'" x-model.number="creativePay2" placeholder="0.00" class="w-full text-sm font-mono rounded-lg border-gray-300 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-2xs font-semibold text-gray-600 uppercase mb-1">{{ __('3rd Pay (BDT)') }}</label>
                                    <input type="number" step="0.01" name="meta[pay_3rd]" :disabled="orderType !== 'creative'" x-model.number="creativePay3" placeholder="0.00" class="w-full text-sm font-mono rounded-lg border-gray-300 focus:ring-indigo-500">
                                </div>
                                <div class="bg-white p-2.5 rounded-lg border border-indigo-200 flex flex-col justify-center">
                                    <div class="text-2xs font-bold text-gray-500 uppercase">{{ __('Calculated Due') }}</div>
                                    <div class="text-base font-bold text-rose-600 font-mono" x-text="'BDT ' + (creativeTotal - (creativePay1 + creativePay2 + creativePay3)).toLocaleString()"></div>
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
                                <input type="text" name="meta[ads_post_link]" :disabled="orderType !== 'ads'" placeholder="https://facebook.com/post/..." class="w-full text-sm rounded-lg border-gray-300 focus:ring-amber-500">
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Order Source') }}</label>
                                <select name="order_source" :disabled="orderType !== 'ads'" class="w-full text-sm rounded-lg border-gray-300 focus:ring-amber-500">
                                    <option value="Direct">Direct</option>
                                    <option value="Repeat">Repeat</option>
                                    <option value="Referral">Referral</option>
                                    <option value="Local">Local</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Campaign Activity') }}</label>
                                <select name="activity_status" :disabled="orderType !== 'ads'" class="w-full text-sm rounded-lg border-gray-300 focus:ring-amber-500">
                                    <option value="Active">Active</option>
                                    <option value="Completed">Completed</option>
                                    <option value="Paused">Paused</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Daily Cost ($)') }}</label>
                                <input type="number" step="0.01" name="meta[daily_cost]" :disabled="orderType !== 'ads'" x-model.number="adsDailyCost" placeholder="e.g. 5.00" class="w-full text-sm rounded-lg border-gray-300 focus:ring-amber-500 font-mono">
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Total Days') }}</label>
                                <input type="number" name="meta[total_days]" :disabled="orderType !== 'ads'" x-model.number="adsTotalDays" placeholder="e.g. 15" class="w-full text-sm rounded-lg border-gray-300 focus:ring-amber-500 font-mono">
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Total Dollar ($ Budget)') }}</label>
                                <input type="number" step="0.01" name="meta[total_dollar]" :disabled="orderType !== 'ads'" x-model.number="adsTotalDollar" placeholder="e.g. 75.00" class="w-full text-sm rounded-lg border-gray-300 focus:ring-amber-500 font-mono font-bold">
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Today Check Status') }}</label>
                                <select name="meta[today_check]" :disabled="orderType !== 'ads'" class="w-full text-sm rounded-lg border-gray-300 focus:ring-amber-500 font-semibold">
                                    <option value="Checked">✅ Checked</option>
                                    <option value="Pending">⏳ Pending</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Previous Dollar ($)') }}</label>
                                <input type="number" step="0.01" name="meta[previous_dollar]" :disabled="orderType !== 'ads'" placeholder="0.00" class="w-full text-sm rounded-lg border-gray-300 focus:ring-amber-500 font-mono">
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Running Dollar ($)') }}</label>
                                <input type="number" step="0.01" name="meta[running_dollar]" :disabled="orderType !== 'ads'" placeholder="0.00" class="w-full text-sm rounded-lg border-gray-300 focus:ring-amber-500 font-mono">
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Total $ Cost') }}</label>
                                <input type="number" step="0.01" name="meta[total_dollar_cost]" :disabled="orderType !== 'ads'" placeholder="0.00" class="w-full text-sm rounded-lg border-gray-300 focus:ring-amber-500 font-mono">
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Dollar Due ($)') }}</label>
                                <input type="number" step="0.01" name="meta[dollar_due]" :disabled="orderType !== 'ads'" placeholder="0.00" class="w-full text-sm rounded-lg border-gray-300 focus:ring-amber-500 font-mono text-rose-600 font-bold">
                            </div>
                        </div>

                        <!-- Ads BDT Budget & Paid -->
                        <div class="p-4 bg-amber-50/50 rounded-xl border border-amber-200 grid grid-cols-1 sm:grid-cols-3 gap-4 mt-4">
                            <div>
                                <label class="block text-2xs font-semibold text-gray-600 uppercase mb-1">{{ __('Total Amount (BDT)') }} <span class="text-rose-500">*</span></label>
                                <input type="number" step="0.01" x-model.number="adsTotalAmount" placeholder="0.00" class="w-full text-sm font-bold font-mono rounded-lg border-gray-300 focus:ring-amber-500">
                            </div>
                            <div>
                                <label class="block text-2xs font-semibold text-gray-600 uppercase mb-1">{{ __('Paid Amount (BDT)') }}</label>
                                <input type="number" step="0.01" x-model.number="adsPaidAmount" placeholder="0.00" class="w-full text-sm font-mono rounded-lg border-gray-300 focus:ring-amber-500">
                            </div>
                            <div class="bg-white p-2.5 rounded-lg border border-amber-300 flex flex-col justify-center">
                                <div class="text-2xs font-bold text-gray-500 uppercase">{{ __('Calculated Due') }}</div>
                                <div class="text-base font-bold text-rose-600 font-mono" x-text="'BDT ' + (adsTotalAmount - adsPaidAmount).toLocaleString()"></div>
                            </div>
                        </div>
                    </div>

                    <!-- ============================================== -->
                    <!-- TAB 3: SOFT & WEB ORDER FORMAT (Excel Sheet)  -->
                    <!-- ============================================== -->
                    <div x-show="orderType === 'web_software'" class="space-y-4 pt-2">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Order Source') }}</label>
                                <select name="order_source" :disabled="orderType !== 'web_software'" class="w-full text-sm rounded-lg border-gray-300 focus:ring-blue-500">
                                    <option value="Direct">Direct</option>
                                    <option value="Repeat">Repeat</option>
                                    <option value="Referral">Referral</option>
                                    <option value="Local">Local</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Working Priority') }}</label>
                                <select name="meta[working_priority]" :disabled="orderType !== 'web_software'" class="w-full text-sm rounded-lg border-gray-300 focus:ring-blue-500">
                                    <option value="Normal">Normal</option>
                                    <option value="High">High</option>
                                    <option value="Urgent">Urgent</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Subscription System') }}</label>
                                <select name="meta[subscription_system]" :disabled="orderType !== 'web_software'" class="w-full text-sm rounded-lg border-gray-300 focus:ring-blue-500">
                                    <option value="Monthly">Monthly Subscription</option>
                                    <option value="Yearly">Yearly Renewal</option>
                                    <option value="One-time">One-time / No Subscription</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Subscription / Renew Fee') }}</label>
                                <input type="number" step="0.01" name="meta[subscription_fee]" :disabled="orderType !== 'web_software'" placeholder="e.g. 2000" class="w-full text-sm rounded-lg border-gray-300 focus:ring-blue-500 font-mono">
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Renew Status') }}</label>
                                <select name="meta[renew_status]" :disabled="orderType !== 'web_software'" class="w-full text-sm rounded-lg border-gray-300 focus:ring-blue-500">
                                    <option value="Active">Active</option>
                                    <option value="Pending">Pending</option>
                                    <option value="Expired">Expired</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Developed Assign') }}</label>
                                <input type="text" name="meta[developed_assign]" :disabled="orderType !== 'web_software'" placeholder="Developer Name" class="w-full text-sm rounded-lg border-gray-300 focus:ring-blue-500">
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Customize Assign') }}</label>
                                <input type="text" name="meta[customize_assign]" :disabled="orderType !== 'web_software'" placeholder="Customizer / PM Name" class="w-full text-sm rounded-lg border-gray-300 focus:ring-blue-500">
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Domain / Live URL') }}</label>
                                <input type="text" name="meta[domain]" :disabled="orderType !== 'web_software'" placeholder="https://clientdomain.com" class="w-full text-sm rounded-lg border-gray-300 focus:ring-blue-500">
                            </div>
                        </div>

                        <!-- 7 Installments Breakdown (Excel Sheet Soft & Web) -->
                        <div class="p-4 bg-blue-50/50 rounded-xl border border-blue-200 space-y-3 mt-4">
                            <h4 class="text-xs font-bold text-blue-900 uppercase tracking-wider">{{ __('Payment Installments (1st to 7th Pay Breakdown)') }}</h4>
                            <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-8 gap-2">
                                <div class="col-span-2 sm:col-span-2 lg:col-span-1">
                                    <label class="block text-2xs font-semibold text-gray-700 uppercase mb-1">{{ __('Initial Total') }} *</label>
                                    <input type="number" step="0.01" x-model.number="softTotal" placeholder="0.00" class="w-full text-xs font-bold font-mono rounded-lg border-gray-300 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block text-2xs font-semibold text-gray-600 uppercase mb-1">1st Pay</label>
                                    <input type="number" step="0.01" name="meta[pay_1st]" :disabled="orderType !== 'web_software'" x-model.number="softPay[1]" placeholder="0.00" class="w-full text-xs font-mono rounded-lg border-gray-300 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block text-2xs font-semibold text-gray-600 uppercase mb-1">2nd Pay</label>
                                    <input type="number" step="0.01" name="meta[pay_2nd]" :disabled="orderType !== 'web_software'" x-model.number="softPay[2]" placeholder="0.00" class="w-full text-xs font-mono rounded-lg border-gray-300 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block text-2xs font-semibold text-gray-600 uppercase mb-1">3rd Pay</label>
                                    <input type="number" step="0.01" name="meta[pay_3rd]" :disabled="orderType !== 'web_software'" x-model.number="softPay[3]" placeholder="0.00" class="w-full text-xs font-mono rounded-lg border-gray-300 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block text-2xs font-semibold text-gray-600 uppercase mb-1">4th Pay</label>
                                    <input type="number" step="0.01" name="meta[pay_4th]" :disabled="orderType !== 'web_software'" x-model.number="softPay[4]" placeholder="0.00" class="w-full text-xs font-mono rounded-lg border-gray-300 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block text-2xs font-semibold text-gray-600 uppercase mb-1">5th Pay</label>
                                    <input type="number" step="0.01" name="meta[pay_5th]" :disabled="orderType !== 'web_software'" x-model.number="softPay[5]" placeholder="0.00" class="w-full text-xs font-mono rounded-lg border-gray-300 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block text-2xs font-semibold text-gray-600 uppercase mb-1">6th Pay</label>
                                    <input type="number" step="0.01" name="meta[pay_6th]" :disabled="orderType !== 'web_software'" x-model.number="softPay[6]" placeholder="0.00" class="w-full text-xs font-mono rounded-lg border-gray-300 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block text-2xs font-semibold text-gray-600 uppercase mb-1">7th Pay</label>
                                    <input type="number" step="0.01" name="meta[pay_7th]" :disabled="orderType !== 'web_software'" x-model.number="softPay[7]" placeholder="0.00" class="w-full text-xs font-mono rounded-lg border-gray-300 focus:ring-blue-500">
                                </div>
                            </div>

                            <div class="flex items-center justify-between p-3 bg-white rounded-lg border border-blue-200 text-xs">
                                <span class="font-semibold text-gray-600">{{ __('Total Paid so far:') }} <strong class="text-emerald-700 font-mono" x-text="'BDT ' + softTotalPaid().toLocaleString()"></strong></span>
                                <span class="font-semibold text-gray-600">{{ __('Remaining Due:') }} <strong class="text-rose-600 font-mono text-sm" x-text="'BDT ' + (softTotal - softTotalPaid()).toLocaleString()"></strong></span>
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
                                    <input type="text" name="meta[official_admin_link]" :disabled="orderType !== 'web_software'" placeholder="https://crm.../admin" class="w-full text-xs font-mono rounded-lg border-gray-300">
                                </div>
                                <div>
                                    <label class="block text-2xs font-semibold text-gray-600 uppercase mb-1">{{ __('Official User ID') }}</label>
                                    <input type="text" name="meta[official_user_id]" :disabled="orderType !== 'web_software'" placeholder="admin_user" class="w-full text-xs font-mono rounded-lg border-gray-300">
                                </div>
                                <div>
                                    <label class="block text-2xs font-semibold text-gray-600 uppercase mb-1">{{ __('Official Password') }}</label>
                                    <input type="text" name="meta[official_password]" :disabled="orderType !== 'web_software'" placeholder="Secret password" class="w-full text-xs font-mono rounded-lg border-gray-300">
                                </div>

                                <div>
                                    <label class="block text-2xs font-semibold text-gray-600 uppercase mb-1">{{ __('Client Admin Link') }}</label>
                                    <input type="text" name="meta[client_admin_link]" :disabled="orderType !== 'web_software'" placeholder="https://client.../wp-admin" class="w-full text-xs font-mono rounded-lg border-gray-300">
                                </div>
                                <div>
                                    <label class="block text-2xs font-semibold text-gray-600 uppercase mb-1">{{ __('Client User ID') }}</label>
                                    <input type="text" name="meta[client_user_id]" :disabled="orderType !== 'web_software'" placeholder="client_admin" class="w-full text-xs font-mono rounded-lg border-gray-300">
                                </div>
                                <div>
                                    <label class="block text-2xs font-semibold text-gray-600 uppercase mb-1">{{ __('Client Password') }}</label>
                                    <input type="text" name="meta[client_password]" :disabled="orderType !== 'web_software'" placeholder="Client secret password" class="w-full text-xs font-mono rounded-lg border-gray-300">
                                </div>

                                <div class="sm:col-span-3">
                                    <label class="block text-2xs font-semibold text-gray-600 uppercase mb-1">{{ __('Client Others User ID & Password') }}</label>
                                    <input type="text" name="meta[client_other_credentials]" :disabled="orderType !== 'web_software'" placeholder="cPanel, DB, Hosting or extra staff credentials..." class="w-full text-xs font-mono rounded-lg border-gray-300">
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
                            <input type="date" name="order_date" required value="{{ date('Y-m-d') }}" class="w-full text-sm rounded-lg border-gray-300 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Expected Delivery Date') }}</label>
                            <input type="date" name="expected_delivery_date" value="{{ date('Y-m-d', strtotime('+15 days')) }}" class="w-full text-sm rounded-lg border-gray-300 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Order Status') }} <span class="text-rose-500">*</span></label>
                            <select name="status" required class="w-full text-sm rounded-lg border-gray-300 focus:ring-indigo-500">
                                @foreach(['Pending', 'Working', 'Re-working', 'Pused', 'Complete', 'Checked', 'Delivery', 'Active', 'Cancel', 'Delay'] as $st)
                                    <option value="{{ $st }}" {{ old('status', 'Pending') === $st ? 'selected' : '' }}>{{ $st }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Sales Person') }}</label>
                            <select name="sales_person_id" class="w-full text-sm rounded-lg border-gray-300 focus:ring-indigo-500">
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ auth()->id() == $user->id ? 'selected' : '' }}>
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
                                    <option value="{{ $user->id }}">
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="pt-2">
                        <label class="inline-flex items-center gap-2 cursor-pointer p-3 bg-indigo-50/60 rounded-xl border border-indigo-200 w-full hover:bg-indigo-50 transition">
                            <input type="checkbox" name="create_project" value="1" checked class="rounded border-indigo-300 text-indigo-600 focus:ring-indigo-500">
                            <div class="text-xs">
                                <span class="font-bold text-indigo-900 block">{{ __('Automatically initialize client project workspace') }}</span>
                                <span class="text-indigo-700">{{ __('Creates project tracking record linked to this order for tasks and milestones.') }}</span>
                            </div>
                        </label>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Order Notes & Specifications') }}</label>
                        <textarea name="notes" rows="2" placeholder="{{ __('Client terms, scope notes, special instructions...') }}" class="w-full text-sm rounded-lg border-gray-300 focus:ring-indigo-500"></textarea>
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
    </div>

    <!-- Alpine.js script for live customer WhatsApp searching & calculations -->
    <script>
        function orderForm() {
            const allCustomers = @json($customers);
            const preselected = @json($preselectedCustomer);

            return {
                orderType: '{{ $orderType ?? "creative" }}',
                orderTitle: '{{ old("title", $preselectedQuotation?->title ?? "") }}',
                serviceCategory: 'Video Edit',
                
                // Customer live search
                customers: allCustomers,
                selectedCustomer: preselected || null,
                searchQuery: '',
                isOpen: false,

                // Creative amounts
                creativeTotal: 0,
                creativePay1: 0,
                creativePay2: 0,
                creativePay3: 0,

                // Ads amounts
                adsDailyCost: 0,
                adsTotalDays: 0,
                adsTotalDollar: 0,
                adsTotalAmount: 0,
                adsPaidAmount: 0,

                // Soft amounts
                softTotal: 0,
                softPay: { 1: 0, 2: 0, 3: 0, 4: 0, 5: 0, 6: 0, 7: 0 },

                get filteredCustomers() {
                    if (!this.searchQuery) return this.customers.slice(0, 10);
                    const q = this.searchQuery.toLowerCase().trim();
                    return this.customers.filter(c => {
                        const name = (c.name || '').toLowerCase();
                        const company = (c.company_name || '').toLowerCase();
                        const phone = (c.phone || '').toLowerCase();
                        const whatsapp = (c.whatsapp || '').toLowerCase();
                        return name.includes(q) || company.includes(q) || phone.includes(q) || whatsapp.includes(q);
                    }).slice(0, 15);
                },

                selectCustomer(customer) {
                    this.selectedCustomer = customer;
                    this.isOpen = false;
                    this.searchQuery = '';
                },

                clearCustomer() {
                    this.selectedCustomer = null;
                    this.searchQuery = '';
                },

                softTotalPaid() {
                    let sum = 0;
                    for (let i = 1; i <= 7; i++) {
                        sum += parseFloat(this.softPay[i] || 0);
                    }
                    return sum;
                },

                currentTotal() {
                    if (this.orderType === 'creative') return parseFloat(this.creativeTotal) || 0;
                    if (this.orderType === 'ads') return parseFloat(this.adsTotalAmount) || 0;
                    if (this.orderType === 'web_software') return parseFloat(this.softTotal) || 0;
                    return 0;
                },

                currentPaid() {
                    if (this.orderType === 'creative') {
                        return (parseFloat(this.creativePay1) || 0) + (parseFloat(this.creativePay2) || 0) + (parseFloat(this.creativePay3) || 0);
                    }
                    if (this.orderType === 'ads') {
                        return parseFloat(this.adsPaidAmount) || 0;
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
