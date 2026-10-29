<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div class="flex items-center gap-3">
                <a href="{{ route('customers.index') }}" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-white rounded-lg transition border border-gray-200 shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                </a>
                <div>
                    <div class="flex items-center gap-2">
                        <h2 class="font-bold text-2xl text-gray-900 leading-tight">
                            {{ $customer->name }}
                        </h2>
                        <span class="px-2.5 py-0.5 rounded-md text-xs font-bold bg-emerald-100 text-emerald-800">
                            {{ $customer->customer_code }}
                        </span>
                        <span class="px-2 py-0.5 rounded-full text-xs font-semibold {{ $customer->status === 'active' ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-100 text-gray-600' }}">
                            {{ $customer->status === 'active' ? __('Active') : __('Inactive') }}
                        </span>
                    </div>
                    <p class="text-xs text-gray-500 mt-0.5">
                        {{ $customer->company_name ?? __('Individual Client') }}
                        @if($customer->business_type) &bull; {{ $customer->business_type }} @endif
                    </p>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('quotations.create', ['customer_id' => $customer->id]) }}" class="inline-flex items-center px-3.5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-bold uppercase tracking-wider shadow-sm transition">
                    <svg class="w-4 h-4 me-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    {{ __('Create Quotation') }}
                </a>

                <a href="{{ route('customers.edit', $customer) }}" class="inline-flex items-center px-3.5 py-2 bg-white border border-gray-300 rounded-lg text-xs font-bold text-gray-700 uppercase tracking-wider hover:bg-gray-50 shadow-sm transition">
                    <svg class="w-4 h-4 me-1.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    {{ __('Edit') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            @if(session('portal_credentials'))
                <div class="p-5 bg-gradient-to-r from-emerald-600 via-teal-600 to-emerald-700 text-white rounded-2xl shadow-md space-y-3">
                    <div class="flex items-center gap-2">
                        <span class="text-2xl">🎉</span>
                        <h4 class="font-black text-base">{{ session('portal_credentials')['message'] }}</h4>
                    </div>
                    <div class="bg-black/20 p-3.5 rounded-xl flex flex-wrap items-center gap-6 text-xs sm:text-sm font-mono border border-white/20">
                        <div>
                            <span class="text-emerald-200 text-[11px] block font-sans uppercase font-bold">{{ __('Login URL') }}</span>
                            <a href="{{ route('login') }}" class="font-bold underline text-white hover:text-emerald-100">{{ route('login') }}</a>
                        </div>
                        <div>
                            <span class="text-emerald-200 text-[11px] block font-sans uppercase font-bold">{{ __('Email') }}</span>
                            <span class="font-bold text-white">{{ session('portal_credentials')['email'] }}</span>
                        </div>
                        <div>
                            <span class="text-emerald-200 text-[11px] block font-sans uppercase font-bold">{{ __('Password') }}</span>
                            <span class="font-bold bg-white text-emerald-900 px-2.5 py-1 rounded select-all">{{ session('portal_credentials')['password'] }}</span>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Customer 360 Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- Left Column (1/3): Client Profile Card -->
                <div class="space-y-6">
                    <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm space-y-4">
                        <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider border-b pb-3 flex items-center gap-2">
                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            {{ __('Customer Contact Info') }}
                        </h3>

                        <!-- Phone -->
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                            <div>
                                <div class="text-[11px] font-semibold text-gray-500 uppercase">{{ __('Mobile') }}</div>
                                <div class="font-bold text-gray-900 text-sm">{{ $customer->phone }}</div>
                            </div>
                            <a href="tel:{{ $customer->phone }}" class="p-2 bg-indigo-50 text-indigo-600 hover:bg-indigo-100 rounded-lg transition">
                                📞
                            </a>
                        </div>

                        <!-- WhatsApp -->
                        <div class="flex items-center justify-between p-3 bg-emerald-50 rounded-xl">
                            <div>
                                <div class="text-[11px] font-semibold text-emerald-800 uppercase">{{ __('WhatsApp') }}</div>
                                <div class="font-bold text-emerald-900 text-sm">{{ $customer->whatsapp ?? $customer->phone }}</div>
                            </div>
                            <a href="https://wa.me/{{ $customer->normalized_whatsapp ? '88' . $customer->normalized_whatsapp : '88' . $customer->normalized_phone }}" target="_blank" class="px-3 py-1 bg-emerald-600 text-white rounded text-xs font-semibold hover:bg-emerald-700 transition">
                                {{ __('WA Chat') }} 💬
                            </a>
                        </div>

                        @if($customer->email)
                            <div class="p-3 bg-gray-50 rounded-xl text-xs">
                                <div class="font-semibold text-gray-500 uppercase text-[10px">{{ __('Email') }}</div>
                                <a href="mailto:{{ $customer->email }}" class="text-indigo-600 hover:underline font-medium">{{ $customer->email }}</a>
                            </div>
                        @endif

                        @if($customer->address || $customer->city)
                            <div class="text-xs text-gray-600 space-y-1">
                                <div class="font-semibold text-gray-700">{{ __('Address') }}:</div>
                                <p>{{ $customer->address ? $customer->address . ', ' : '' }}{{ $customer->city }} ({{ $customer->country }})</p>
                            </div>
                        @endif

                        @if($customer->notes)
                            <div class="pt-2 border-t text-xs">
                                <div class="font-semibold text-gray-700 mb-1">{{ __('Notes') }}:</div>
                                <p class="text-gray-600 bg-gray-50 p-2.5 rounded-lg whitespace-pre-line">{{ $customer->notes }}</p>
                            </div>
                        @endif
                    </div>

                    <!-- Client Portal Access Management Card -->
                    <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm space-y-4" x-data="{ showResetModal: false, showCreateModal: false }">
                        <div class="flex items-center justify-between border-b pb-3">
                            <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider flex items-center gap-2">
                                <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                                {{ __('Client Portal Account') }}
                            </h3>
                            @if($customer->user)
                                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold {{ $customer->user->status === 'active' ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                    {{ $customer->user->status === 'active' ? __('Portal Active') : __('Suspended') }}
                                </span>
                            @else
                                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-gray-100 text-gray-600">
                                    {{ __('Not Created') }}
                                </span>
                            @endif
                        </div>

                        @if($customer->user)
                            <div class="p-3 bg-indigo-50/50 rounded-xl space-y-2 border border-indigo-100">
                                <div class="flex items-center justify-between text-xs">
                                    <span class="text-gray-500 font-medium">{{ __('Login Email:') }}</span>
                                    <strong class="text-indigo-900 font-mono">{{ $customer->user->email }}</strong>
                                </div>
                                <div class="flex items-center justify-between text-xs">
                                    <span class="text-gray-500 font-medium">{{ __('Role:') }}</span>
                                    <span class="font-semibold text-gray-800">Client Portal</span>
                                </div>
                                <div class="flex items-center justify-between text-xs">
                                    <span class="text-gray-500 font-medium">{{ __('Created:') }}</span>
                                    <span class="text-gray-600">{{ $customer->user->created_at->format('d M, Y') }}</span>
                                </div>
                            </div>

                            <div class="flex items-center gap-2 pt-1">
                                <button type="button" @click="showResetModal = true" class="flex-1 px-3 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-lg transition text-center shadow-xs">
                                    {{ __('Reset Password') }}
                                </button>
                                
                                <form action="{{ route('customers.portal-toggle', $customer) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="px-3 py-2 border rounded-lg text-xs font-bold transition {{ $customer->user->status === 'active' ? 'border-rose-300 text-rose-700 hover:bg-rose-50' : 'border-emerald-300 text-emerald-700 hover:bg-emerald-50' }}">
                                        {{ $customer->user->status === 'active' ? __('Deactivate') : __('Activate') }}
                                    </button>
                                </form>
                            </div>

                            <!-- Reset Password Modal -->
                            <div x-show="showResetModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4" x-cloak>
                                <div class="bg-white rounded-2xl max-w-sm w-full p-6 space-y-4 shadow-xl" @click.away="showResetModal = false">
                                    <h4 class="font-bold text-gray-900 text-base">{{ __('Reset Portal Password') }}</h4>
                                    <p class="text-xs text-gray-500">{{ __('Enter a new password or leave blank to auto-generate a secure random password.') }}</p>
                                    
                                    <form action="{{ route('customers.portal-reset-password', $customer) }}" method="POST" class="space-y-3">
                                        @csrf
                                        <div>
                                            <label class="block text-xs font-semibold text-gray-700 mb-1">{{ __('New Password (Optional)') }}</label>
                                            <input type="text" name="password" placeholder="Leave blank to auto-generate" class="w-full text-xs rounded-lg border-gray-300 focus:ring-indigo-500">
                                        </div>
                                        <div class="flex justify-end gap-2 pt-2">
                                            <button type="button" @click="showResetModal = false" class="px-3 py-1.5 text-xs text-gray-600 hover:bg-gray-100 rounded-lg">{{ __('Cancel') }}</button>
                                            <button type="submit" class="px-4 py-1.5 text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg">{{ __('Reset & Update') }}</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @else
                            <div class="text-xs text-gray-500 space-y-3">
                                <p>{{ __('Create a client login account so this customer can sign in to view their projects, download invoices, and submit support tickets directly.') }}</p>
                                
                                <form action="{{ route('customers.portal-account', $customer) }}" method="POST" class="space-y-3 pt-1">
                                    @csrf
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-700 mb-1">{{ __('Login Email') }} <span class="text-rose-500">*</span></label>
                                        <input type="email" name="email" value="{{ old('email', $customer->email) }}" required class="w-full text-xs rounded-lg border-gray-300 focus:ring-indigo-500" placeholder="client@example.com">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-700 mb-1">{{ __('Set Password (Optional)') }}</label>
                                        <input type="text" name="password" placeholder="Leave blank to auto-generate" class="w-full text-xs rounded-lg border-gray-300 focus:ring-indigo-500">
                                    </div>
                                    <button type="submit" class="w-full py-2.5 px-4 bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-700 hover:to-blue-700 text-white text-xs font-bold rounded-xl shadow-xs transition flex items-center justify-center gap-1.5">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                                        {{ __('Create Client Portal Account') }}
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Right Column (2/3): Customer 360 Tabs (Leads, Quotations, Communications) -->
                <div class="lg:col-span-2 space-y-6">

                    <!-- Associated Leads -->
                    <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm space-y-3">
                        <div class="flex items-center justify-between border-b pb-3">
                            <h3 class="text-base font-bold text-gray-900 flex items-center gap-2">
                                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                {{ __('All Inquiries & Leads') }} ({{ $customer->leads->count() }})
                            </h3>
                        </div>

                        <div class="space-y-2">
                            @forelse($customer->leads as $lead)
                                <div class="p-3.5 rounded-xl border border-gray-100 hover:border-indigo-300 hover:bg-indigo-50/20 transition flex flex-col sm:flex-row justify-between sm:items-center gap-2">
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <a href="{{ route('leads.show', $lead) }}" class="font-bold text-xs text-indigo-600 hover:underline">
                                                {{ $lead->lead_code }}
                                            </a>
                                            <span class="text-xs font-semibold text-gray-800">{{ $lead->interested_service }}</span>
                                        </div>
                                        <div class="text-[11px] text-gray-500 mt-0.5">
                                            {{ __('Date') }}: {{ $lead->created_at->format('d M, Y') }} &bull; {{ __('Budget') }}: {{ __('BDT') }} {{ number_format($lead->budget) }}
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="px-2 py-0.5 rounded-full text-xs font-semibold {{ $lead->status_badge_class }}">
                                            {{ __($lead->lead_status) }}
                                        </span>
                                        <a href="{{ route('leads.show', $lead) }}" class="text-xs text-indigo-600 font-semibold hover:underline">
                                            {{ __('Details') }} &rarr;
                                        </a>
                                    </div>
                                </div>
                            @empty
                                <p class="text-xs text-gray-400 py-3 text-center">{{ __('No lead records found.') }}</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Associated Quotations -->
                    <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm space-y-3">
                        <div class="flex items-center justify-between border-b pb-3">
                            <h3 class="text-base font-bold text-gray-900 flex items-center gap-2">
                                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                {{ __('Quotation History') }} ({{ $customer->quotations->count() }})
                            </h3>
                        </div>

                        <div class="space-y-2">
                            @forelse($customer->quotations as $quotation)
                                <div class="p-3.5 rounded-xl border border-gray-100 hover:border-indigo-300 transition flex flex-col sm:flex-row justify-between sm:items-center gap-2">
                                    <div>
                                        <div class="font-bold text-xs text-indigo-700">{{ $quotation->quotation_code }}</div>
                                        <div class="text-[11px] text-gray-500 mt-0.5">
                                            {{ __('Issue Date') }}: {{ $quotation->quotation_date->format('d M, Y') }}
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <span class="font-bold text-gray-900 text-sm">{{ __('BDT') }} {{ number_format($quotation->total_amount, 2) }}</span>
                                        <span class="px-2 py-0.5 rounded-full text-xs font-semibold {{ $quotation->status === 'Accepted' ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-100 text-gray-700' }}">
                                            {{ __($quotation->status) }}
                                        </span>
                                        <a href="{{ route('quotations.show', $quotation) }}" class="text-xs text-indigo-600 font-semibold hover:underline">
                                            {{ __('Print') }} &rarr;
                                        </a>
                                    </div>
                                </div>
                            @empty
                                <p class="text-xs text-gray-400 py-3 text-center">{{ __('No quotations found.') }}</p>
                            @endforelse
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </div>
</x-app-layout>
