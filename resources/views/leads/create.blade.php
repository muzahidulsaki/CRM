<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight flex items-center gap-2">
                    <a href="{{ route('leads.index') }}" class="text-gray-400 hover:text-gray-600 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    </a>
                    {{ __('New Lead Entry') }}
                </h2>
                <p class="text-sm text-gray-500 mt-0.5">{{ __('Capture prospective client inquiry aligned with standard CRM format') }}</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-indigo-100 text-indigo-700">
                    {{ __('Next Lead ID') }}: {{ $nextLeadCode ?? 'Auto' }}
                </span>
                <a href="{{ route('leads.index') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900 bg-white px-3.5 py-2 border border-gray-300 rounded-lg shadow-sm">
                    {{ __('Back to List') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="w-full space-y-6">

            <!-- Duplicate Warning Card from Session -->
            @if(session('duplicate_warning'))
                @php $dup = session('duplicate_warning'); @endphp
                <div class="mb-6 p-4 bg-rose-50 border-l-4 border-rose-500 rounded-r-xl shadow-sm">
                    <div class="flex items-start gap-3">
                        <svg class="w-6 h-6 text-rose-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        <div class="flex-1">
                            <h3 class="text-sm font-bold text-rose-800">{{ __('Warning: Duplicate Contact Details Detected!') }}</h3>
                            <p class="text-sm text-rose-700 mt-1">{{ $dup['message'] }}</p>
                            <div class="mt-3 flex items-center gap-3">
                                @if($dup['type'] === 'lead')
                                    <a href="{{ route('leads.show', $dup['record']->id) }}" target="_blank" class="inline-flex items-center text-xs font-semibold px-3 py-1.5 bg-rose-600 text-white rounded-md hover:bg-rose-700 transition">
                                        {{ __('View Existing Lead') }} ({{ $dup['record']->lead_code }}) &rarr;
                                    </a>
                                @else
                                    <a href="{{ route('customers.show', $dup['record']->id) }}" target="_blank" class="inline-flex items-center text-xs font-semibold px-3 py-1.5 bg-emerald-600 text-white rounded-md hover:bg-emerald-700 transition">
                                        {{ __('View Customer Profile') }} ({{ $dup['record']->customer_code }}) &rarr;
                                    </a>
                                @endif
                                <span class="text-xs font-semibold text-rose-700 bg-rose-100 px-2.5 py-1 rounded">
                                    {{ __('Duplicate entries are strictly not allowed.') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Live Duplicate Alert Container (via AJAX) -->
            <div id="live-duplicate-alert" class="hidden mb-6 p-4 bg-rose-50 border-l-4 border-rose-500 rounded-r-xl shadow-sm">
                <div class="flex items-start gap-3">
                    <svg class="w-6 h-6 text-rose-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <div class="flex-1">
                        <h3 class="text-sm font-bold text-rose-800">{{ __('Warning: Duplicate Contact Details!') }}</h3>
                        <p id="live-duplicate-msg" class="text-sm text-rose-700 mt-1"></p>
                        <div class="mt-2" id="live-duplicate-link-container"></div>
                    </div>
                </div>
            </div>

            <form id="lead-form" method="POST" action="{{ route('leads.store') }}" class="space-y-6">
                @csrf

                <!-- Section 1: Lead Identification & Business Category -->
                <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm space-y-4">
                    <h3 class="text-base font-bold text-gray-900 flex items-center justify-between border-b pb-3">
                        <span class="flex items-center gap-2">
                            <span class="w-7 h-7 rounded-lg bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold text-sm">1</span>
                            {{ __('Lead Identification & Category') }}
                        </span>
                        <span class="text-xs font-semibold px-2.5 py-1 bg-slate-100 text-slate-600 rounded">
                            {{ __('Lead ID') }}: <strong class="text-indigo-600 font-mono">{{ $nextLeadCode }}</strong>
                        </span>
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <!-- Token ID -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Token ID') }}</label>
                            <input type="text" name="token_id" value="{{ old('token_id') }}" placeholder="e.g. TKN-9821" class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <!-- Lead Source -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Lead Source') }} <span class="text-rose-500">*</span></label>
                            <input list="lead_sources_list" name="lead_source" value="{{ old('lead_source') }}" required class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" placeholder="{{ __('Type or select source') }}">
                            <datalist id="lead_sources_list">
                                @foreach($leadSources as $source)
                                    <option value="{{ $source }}"></option>
                                @endforeach
                            </datalist>
                            @error('lead_source') <span class="text-rose-600 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Business Category -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Business Category') }} <span class="text-rose-500">*</span></label>
                            <input list="business_categories_list" name="business_type" value="{{ old('business_type') }}" required class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" placeholder="{{ __('e.g. Travel Agency') }}">
                            <datalist id="business_categories_list">
                                @foreach($businessCategories as $cat)
                                    <option value="{{ $cat }}"></option>
                                @endforeach
                            </datalist>
                            @error('business_type') <span class="text-rose-600 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Temperature -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Client Temperature') }} <span class="text-rose-500">*</span></label>
                            <select name="lead_temperature" required class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="Warm" {{ old('lead_temperature', 'Warm') === 'Warm' ? 'selected' : '' }}>🔥 Warm</option>
                                <option value="Hot" {{ old('lead_temperature') === 'Hot' ? 'selected' : '' }}>⚡ Hot</option>
                                <option value="Cold" {{ old('lead_temperature') === 'Cold' ? 'selected' : '' }}>❄️ Cold</option>
                            </select>
                            @error('lead_temperature') <span class="text-rose-600 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Client Name, Business Name & Pipeline Status -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-2">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Client Name (Person)') }}</label>
                            <input type="text" name="name" value="{{ old('name') }}" placeholder="e.g. Bashir Ahmed (Optional)" class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                            @error('name') <span class="text-rose-600 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Business Name (Company)') }}</label>
                            <input type="text" name="company_name" value="{{ old('company_name') }}" placeholder="e.g. Moonway Tour & Travels" class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Pipeline Status') }} <span class="text-rose-500">*</span></label>
                            <select name="lead_status" required class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 font-semibold">
                                @foreach(['New', 'Contacted', 'Interested', 'Meeting/Demo', 'Proposal Sent', 'Negotiation', 'Won', 'Lost'] as $st)
                                    <option value="{{ $st }}" {{ old('lead_status', 'New') === $st ? 'selected' : '' }}>{{ $st }}</option>
                                @endforeach
                            </select>
                            @error('lead_status') <span class="text-rose-600 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <!-- Section 2: Contact Channels & Live Status -->
                <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm space-y-4">
                    <h3 class="text-base font-bold text-gray-900 flex items-center gap-2 border-b pb-3">
                        <span class="w-7 h-7 rounded-lg bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold text-sm">2</span>
                        {{ __('Contact Numbers & Messaging Channels') }}
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <!-- WhatsApp -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">
                                🟢 {{ __('WhatsApp Number') }}
                            </label>
                            <input type="text" id="whatsapp" name="whatsapp" value="{{ old('whatsapp') }}" placeholder="966 56 858 3441 / 017..." class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 @error('whatsapp') border-rose-500 @enderror">
                            @error('whatsapp') 
                                <p class="text-rose-600 text-xs font-semibold mt-1 flex items-center gap-1">
                                    <span>⚠️</span> {{ $message }}
                                </p> 
                            @enderror
                            <p id="whatsapp-dup-msg" class="hidden text-rose-600 text-xs font-semibold mt-1 flex items-center gap-1"></p>
                        </div>

                        <!-- WhatsApp Status -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('WhatsApp Status') }}</label>
                            <select name="whatsapp_status" class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">{{ __('Select Status') }}</option>
                                @foreach($whatsappStatuses as $st)
                                    <option value="{{ $st }}" {{ old('whatsapp_status') === $st ? 'selected' : '' }}>{{ $st }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Imo Number -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">
                                🟣 {{ __('Imo Number') }}
                            </label>
                            <input type="text" id="imo_number" name="imo_number" value="{{ old('imo_number') }}" placeholder="Imo contact number" class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 @error('imo_number') border-rose-500 @enderror">
                            @error('imo_number') 
                                <p class="text-rose-600 text-xs font-semibold mt-1 flex items-center gap-1">
                                    <span>⚠️</span> {{ $message }}
                                </p> 
                            @enderror
                            <p id="imo-dup-msg" class="hidden text-rose-600 text-xs font-semibold mt-1 flex items-center gap-1"></p>
                        </div>

                        <!-- Mobile / Primary Phone -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">
                                📞 {{ __('Mobile (Phone)') }} <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" id="phone" name="phone" value="{{ old('phone') }}" required placeholder="Primary call number" class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 @error('phone') border-rose-500 @enderror">
                            @error('phone') 
                                <p class="text-rose-600 text-xs font-semibold mt-1 flex items-center gap-1">
                                    <span>⚠️</span> {{ $message }}
                                </p> 
                            @enderror
                            <p id="phone-dup-msg" class="hidden text-rose-600 text-xs font-semibold mt-1 flex items-center gap-1"></p>
                        </div>

                        <!-- Email Address -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">
                                ✉️ {{ __('Email Address') }}
                            </label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="client@example.com" class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 @error('email') border-rose-500 @enderror">
                            @error('email') 
                                <p class="text-rose-600 text-xs font-semibold mt-1 flex items-center gap-1">
                                    <span>⚠️</span> {{ $message }}
                                </p> 
                            @enderror
                            <p id="email-dup-msg" class="hidden text-rose-600 text-xs font-semibold mt-1 flex items-center gap-1"></p>
                        </div>

                        <!-- Email Status -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Email Status') }}</label>
                            <select name="email_status" class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">{{ __('Select Status') }}</option>
                                @foreach($emailStatuses as $st)
                                    <option value="{{ $st }}" {{ old('email_status') === $st ? 'selected' : '' }}>{{ $st }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Messenger Link -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">
                                💬 {{ __('Messenger Link') }}
                            </label>
                            <input type="text" name="messenger_link" value="{{ old('messenger_link') }}" placeholder="m.me/pagename" class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <!-- Messenger Status -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Messenger Status') }}</label>
                            <select name="messenger_status" class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">{{ __('Select Status') }}</option>
                                @foreach($messengerStatuses as $st)
                                    <option value="{{ $st }}" {{ old('messenger_status') === $st ? 'selected' : '' }}>{{ $st }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Section 3: Web & Social Media Links -->
                <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm space-y-4">
                    <h3 class="text-base font-bold text-gray-900 flex items-center gap-2 border-b pb-3">
                        <span class="w-7 h-7 rounded-lg bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold text-sm">3</span>
                        {{ __('Website & Social Media Links') }}
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">🌐 {{ __('Website / Domain') }}</label>
                            <input type="text" name="website" value="{{ old('website') }}" placeholder="https://example.com" class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">📘 {{ __('FB Page') }}</label>
                            <input type="text" name="facebook" value="{{ old('facebook') }}" placeholder="https://facebook.com/..." class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">📸 {{ __('Instagram') }}</label>
                            <input type="text" name="instagram" value="{{ old('instagram') }}" placeholder="https://instagram.com/..." class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">🔴 {{ __('YouTube') }}</label>
                            <input type="text" name="youtube" value="{{ old('youtube') }}" placeholder="https://youtube.com/@..." class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">🎵 {{ __('TikTok') }}</label>
                            <input type="text" name="tiktok" value="{{ old('tiktok') }}" placeholder="https://tiktok.com/@..." class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">🐦 {{ __('Twitter / X') }}</label>
                            <input type="text" name="twitter" value="{{ old('twitter') }}" placeholder="https://x.com/..." class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">👻 {{ __('Snapchat') }}</label>
                            <input type="text" name="snapchat" value="{{ old('snapchat') }}" placeholder="Snapchat link or username" class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">💼 {{ __('LinkedIn') }}</label>
                            <input type="text" name="linkedin" value="{{ old('linkedin') }}" placeholder="https://linkedin.com/in/..." class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">🔗 {{ __('Others Link') }}</label>
                            <input type="text" name="other_socials" value="{{ old('other_socials') }}" placeholder="Google Drive, Portfolio, etc." class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                    </div>
                </div>

                <!-- Section 4: Geographical Location & Maps -->
                <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm space-y-4">
                    <h3 class="text-base font-bold text-gray-900 flex items-center gap-2 border-b pb-3">
                        <span class="w-7 h-7 rounded-lg bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold text-sm">4</span>
                        {{ __('Location & Geographical Intelligence') }}
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">📍 {{ __('Current Country') }}</label>
                            <input list="countries_list" name="current_country" value="{{ old('current_country') }}" class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" placeholder="e.g. Saudi Arabia">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">🏢 {{ __('Business Country') }}</label>
                            <input list="countries_list" name="business_country" value="{{ old('business_country') }}" class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" placeholder="e.g. Saudi Arabia">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">🗺️ {{ __('Google Maps Link') }}</label>
                            <input type="text" name="google_maps_link" value="{{ old('google_maps_link') }}" placeholder="https://maps.app.goo.gl/..." class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <datalist id="countries_list">
                            @foreach($countries as $cnt)
                                <option value="{{ $cnt }}"></option>
                            @endforeach
                        </datalist>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Full Physical Address') }}</label>
                        <textarea name="address" rows="2" placeholder="Street, Suite, District, Postal Code..." class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">{{ old('address') }}</textarea>
                    </div>
                </div>

                <!-- Section 5: Follow-up & Meeting Operations -->
                <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm space-y-4">
                    <h3 class="text-base font-bold text-gray-900 flex items-center gap-2 border-b pb-3">
                        <span class="w-7 h-7 rounded-lg bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold text-sm">5</span>
                        {{ __('Follow-up & Meeting Operations') }}
                    </h3>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">📝 {{ __('Follow-up Comment / Note') }}</label>
                        <textarea name="followup_comment" rows="2" placeholder="{{ __('Client feedback, objections, discussion recap...') }}" class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">{{ old('followup_comment') }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Follow-up 1st Date') }}</label>
                            <input type="date" name="followup_first_date" value="{{ old('followup_first_date') }}" class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Follow-up Last Date') }}</label>
                            <input type="date" name="followup_last_date" value="{{ old('followup_last_date') }}" class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Follow-up Next Date') }}</label>
                            <input type="date" name="followup_next_date" value="{{ old('followup_next_date') }}" class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">💻 {{ __('Meeting Platform') }}</label>
                            <select name="meeting_platform" class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">{{ __('Select Meeting Platform') }}</option>
                                @foreach($meetingPlatforms as $mp)
                                    <option value="{{ $mp }}" {{ old('meeting_platform') === $mp ? 'selected' : '' }}>{{ $mp }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">⏰ {{ __('Next Meeting Date & Time') }}</label>
                            <input type="datetime-local" name="next_meeting_datetime" value="{{ old('next_meeting_datetime') }}" class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                    </div>
                </div>

                <!-- Section 6: SRS, Product Interest & Sales Pipeline -->
                <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm space-y-4">
                    <h3 class="text-base font-bold text-gray-900 flex items-center gap-2 border-b pb-3">
                        <span class="w-7 h-7 rounded-lg bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold text-sm">6</span>
                        {{ __('SRS, Product Interest & Sales Pipeline') }}
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <!-- SRS Status -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('SRS Status') }}</label>
                            <select name="srs_status" class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">{{ __('Select SRS Status') }}</option>
                                @foreach($srsStatuses as $st)
                                    <option value="{{ $st }}" {{ old('srs_status') === $st ? 'selected' : '' }}>{{ $st }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- SRS Number -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('SRS Number') }}</label>
                            <input type="text" name="srs_number" value="{{ old('srs_number') }}" placeholder="SRS-2026-001" class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <!-- Interested Product -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Interested Product / Service') }}</label>
                            <input type="text" name="interested_products" value="{{ old('interested_products') }}" placeholder="{{ __('Type interested product or service...') }}" class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <!-- Potential Order -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Potential Order') }}</label>
                            <input type="text" name="potential_order" value="{{ old('potential_order') }}" placeholder="{{ __('e.g. ERP software, 3 promo videos') }}" class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <!-- Why Not Order -->
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1 text-rose-700">{{ __('Why Not Order (Reason if Lost or Delayed)') }}</label>
                            <select name="why_not_order" class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">{{ __('Select Reason (or leave blank if ongoing)') }}</option>
                                @foreach($whyNotOrderOptions as $reason)
                                    <option value="{{ $reason }}" {{ old('why_not_order') === $reason ? 'selected' : '' }}>{{ $reason }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Retentions Marketing -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Retentions Marketing') }}</label>
                            <input type="text" name="retentions_marketing" value="{{ old('retentions_marketing') }}" placeholder="{{ __('e.g. Call after Eid offer') }}" class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <!-- Repeat Marketing -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Repeat Marketing') }}</label>
                            <input type="text" name="repeat_marketing" value="{{ old('repeat_marketing') }}" placeholder="{{ __('e.g. Next month website redesign pitch') }}" class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                    </div>
                </div>

                <!-- Section 7: Assignment & Financial Estimation -->
                <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm space-y-4">
                    <h3 class="text-base font-bold text-gray-900 flex items-center gap-2 border-b pb-3">
                        <span class="w-7 h-7 rounded-lg bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold text-sm">7</span>
                        {{ __('Staff Assignment & Budget Estimation') }}
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Assigned Sales / Account Manager') }}</label>
                            <select name="assigned_to" class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">{{ __('Unassigned') }}</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('assigned_to', auth()->id()) == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }} ({{ $user->role->name ?? 'Staff' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Client Budget') }} ({{ __('BDT') }})</label>
                            <input type="number" step="0.01" name="budget" value="{{ old('budget') }}" placeholder="e.g. 50000" class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Discussed Amount') }} ({{ __('BDT') }})</label>
                            <input type="number" step="0.01" name="discussed_amount" value="{{ old('discussed_amount') }}" placeholder="e.g. 45000" class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Internal Notes') }}</label>
                        <textarea name="notes" rows="2" placeholder="{{ __('Internal staff notes...') }}" class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">{{ old('notes') }}</textarea>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex items-center justify-end gap-3 pt-4">
                    <a href="{{ route('leads.index') }}" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-lg text-sm transition">
                        {{ __('Cancel') }}
                    </a>
                    <button type="submit" id="submit-lead-btn" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 disabled:bg-gray-400 disabled:cursor-not-allowed text-white font-semibold rounded-lg text-sm shadow-sm transition flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>{{ __('Save Lead Entry') }}</span>
                    </button>
                </div>
            </form>
        </div>

    <!-- Live Duplicate Checking Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const phoneInput = document.getElementById('phone');
            const whatsappInput = document.getElementById('whatsapp');
            const imoInput = document.getElementById('imo_number');
            const emailInput = document.getElementById('email');

            const alertBox = document.getElementById('live-duplicate-alert');
            const msgBox = document.getElementById('live-duplicate-msg');
            const linkContainer = document.getElementById('live-duplicate-link-container');
            const submitBtn = document.getElementById('submit-lead-btn');
            const leadForm = document.getElementById('lead-form');

            const phoneMsg = document.getElementById('phone-dup-msg');
            const whatsappMsg = document.getElementById('whatsapp-dup-msg');
            const imoMsg = document.getElementById('imo-dup-msg');
            const emailMsg = document.getElementById('email-dup-msg');

            let timer = null;
            let hasDuplicate = false;

            function clearFieldErrors() {
                [phoneMsg, whatsappMsg, imoMsg, emailMsg].forEach(el => {
                    if (el) {
                        el.classList.add('hidden');
                        el.innerHTML = '';
                    }
                });
                [phoneInput, whatsappInput, imoInput, emailInput].forEach(el => {
                    if (el) {
                        el.classList.remove('border-rose-500', 'focus:ring-rose-500', 'focus:border-rose-500');
                    }
                });
            }

            function setFieldError(inputEl, msgEl, text) {
                if (msgEl) {
                    msgEl.innerHTML = `<span>⚠️</span> <span>${text}</span>`;
                    msgEl.classList.remove('hidden');
                }
                if (inputEl) {
                    inputEl.classList.add('border-rose-500', 'focus:ring-rose-500', 'focus:border-rose-500');
                }
            }

            function checkDuplicates() {
                const phone = phoneInput ? phoneInput.value.trim() : '';
                const whatsapp = whatsappInput ? whatsappInput.value.trim() : '';
                const imo = imoInput ? imoInput.value.trim() : '';
                const email = emailInput ? emailInput.value.trim() : '';

                if (phone.length < 4 && whatsapp.length < 4 && imo.length < 4 && email.length < 4) {
                    alertBox.classList.add('hidden');
                    clearFieldErrors();
                    hasDuplicate = false;
                    if (submitBtn) {
                        submitBtn.disabled = false;
                        submitBtn.removeAttribute('title');
                    }
                    return;
                }

                fetch('{{ route('leads.check-duplicate') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ phone, whatsapp, imo_number: imo, email })
                })
                .then(res => res.json())
                .then(data => {
                    clearFieldErrors();

                    if (data.is_duplicate) {
                        hasDuplicate = true;
                        if (submitBtn) {
                            submitBtn.disabled = true;
                            submitBtn.title = 'Duplicate contact details detected. Please fix before saving.';
                        }

                        // Top warning banner
                        msgBox.innerText = data.message;
                        if (data.type === 'lead') {
                            linkContainer.innerHTML = `<a href="/leads/${data.record.id}" target="_blank" class="inline-flex items-center text-xs font-semibold px-2.5 py-1 bg-rose-600 text-white rounded hover:bg-rose-700 transition">{{ __('View Existing Lead') }} (${data.record.lead_code}) &rarr;</a>`;
                        } else if (data.type === 'customer') {
                            linkContainer.innerHTML = `<a href="/customers/${data.record.id}" target="_blank" class="inline-flex items-center text-xs font-semibold px-2.5 py-1 bg-emerald-600 text-white rounded hover:bg-emerald-700 transition">{{ __('View Customer Profile') }} (${data.record.customer_code}) &rarr;</a>`;
                        }
                        alertBox.classList.remove('hidden');

                        // Inline red error under each matching column
                        if (data.field_errors) {
                            if (data.field_errors.phone) {
                                setFieldError(phoneInput, phoneMsg, data.field_errors.phone);
                            }
                            if (data.field_errors.whatsapp) {
                                setFieldError(whatsappInput, whatsappMsg, data.field_errors.whatsapp);
                            }
                            if (data.field_errors.imo_number) {
                                setFieldError(imoInput, imoMsg, data.field_errors.imo_number);
                            }
                            if (data.field_errors.email) {
                                setFieldError(emailInput, emailMsg, data.field_errors.email);
                            }
                        }
                    } else {
                        hasDuplicate = false;
                        if (submitBtn) {
                            submitBtn.disabled = false;
                            submitBtn.removeAttribute('title');
                        }
                        alertBox.classList.add('hidden');
                    }
                })
                .catch(err => console.error('Duplicate check error:', err));
            }

            [phoneInput, whatsappInput, imoInput, emailInput].forEach(el => {
                if (el) {
                    el.addEventListener('input', () => {
                        clearTimeout(timer);
                        timer = setTimeout(checkDuplicates, 400);
                    });
                }
            });

            if (leadForm) {
                leadForm.addEventListener('submit', function (e) {
                    if (hasDuplicate) {
                        e.preventDefault();
                        alert('Cannot save: Duplicate contact details detected! Please check the red-highlighted fields.');
                        window.scrollTo({ top: 0, behavior: 'smooth' });
                    }
                });
            }
        });
    </script>
</x-app-layout>
