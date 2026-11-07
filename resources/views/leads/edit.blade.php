<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight flex items-center gap-2">
                    <a href="{{ $returnTo ?? route('leads.index') }}" class="text-gray-400 hover:text-gray-600 transition" title="{{ __('Back to Lead Management') }}">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    </a>
                    {{ __('Edit Lead') }}: {{ $lead->lead_code }}
                </h2>
                <p class="text-sm text-gray-500 mt-0.5">{{ $lead->name }} - {{ $lead->company_name ?? __('Individual') }}</p>
            </div>
            <a href="{{ $returnTo ?? route('leads.index') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900 bg-white px-3.5 py-2 border border-gray-300 rounded-lg shadow-sm">
                &larr; {{ __('Back to Leads') }}
            </a>
        </div>
    </x-slot>

    <div class="w-full space-y-6">

            <form method="POST" action="{{ route('leads.update', $lead) }}" class="space-y-6">
                @csrf
                @method('PUT')
                <input type="hidden" name="return_to" value="{{ old('return_to', $returnTo ?? request('return_to')) }}">

                <!-- Section 1: Lead Identification & Category -->
                <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm space-y-4">
                    <h3 class="text-base font-bold text-gray-900 flex items-center justify-between border-b pb-3">
                        <span class="flex items-center gap-2">
                            <span class="w-7 h-7 rounded-lg bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold text-sm">1</span>
                            {{ __('Lead Identification & Category') }}
                        </span>
                        <span class="text-xs font-semibold px-2.5 py-1 bg-slate-100 text-slate-700 rounded">
                            {{ __('Lead ID') }}: <strong class="text-indigo-600 font-mono">{{ $lead->lead_code }}</strong>
                        </span>
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Token ID') }}</label>
                            <input type="text" name="token_id" value="{{ old('token_id', $lead->token_id) }}" placeholder="e.g. TKN-9821" class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Lead Source') }} <span class="text-rose-500">*</span></label>
                            <input list="lead_sources_list" name="lead_source" value="{{ old('lead_source', $lead->lead_source) }}" required class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                            <datalist id="lead_sources_list">
                                @foreach($leadSources as $source)
                                    <option value="{{ $source }}"></option>
                                @endforeach
                            </datalist>
                            @error('lead_source') <span class="text-rose-600 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Business Category') }} <span class="text-rose-500">*</span></label>
                            <input list="business_categories_list" name="business_type" value="{{ old('business_type', $lead->business_type) }}" required class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                            <datalist id="business_categories_list">
                                @foreach($businessCategories as $cat)
                                    <option value="{{ $cat }}"></option>
                                @endforeach
                            </datalist>
                            @error('business_type') <span class="text-rose-600 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Client Temperature') }} <span class="text-rose-500">*</span></label>
                            <select name="lead_temperature" required class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="Warm" {{ old('lead_temperature', $lead->lead_temperature) === 'Warm' ? 'selected' : '' }}>🔥 Warm</option>
                                <option value="Hot" {{ old('lead_temperature', $lead->lead_temperature) === 'Hot' ? 'selected' : '' }}>⚡ Hot</option>
                                <option value="Cold" {{ old('lead_temperature', $lead->lead_temperature) === 'Cold' ? 'selected' : '' }}>❄️ Cold</option>
                            </select>
                            @error('lead_temperature') <span class="text-rose-600 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-2">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Client Name') }}</label>
                            <input type="text" name="name" value="{{ old('name', $lead->name) }}" class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" placeholder="{{ __('Optional') }}">
                            @error('name') <span class="text-rose-600 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Business Name') }}</label>
                            <input type="text" name="company_name" value="{{ old('company_name', $lead->company_name) }}" class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Pipeline Status') }} <span class="text-rose-500">*</span></label>
                            <select name="lead_status" required class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 font-semibold">
                                @foreach(['New', 'Contacted', 'Interested', 'Meeting/Demo', 'Proposal Sent', 'Negotiation', 'Won', 'Lost'] as $st)
                                    <option value="{{ $st }}" {{ old('lead_status', $lead->lead_status) === $st ? 'selected' : '' }}>{{ $st }}</option>
                                @endforeach
                            </select>
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
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">🟢 {{ __('WhatsApp Number') }}</label>
                            <input type="text" name="whatsapp" value="{{ old('whatsapp', $lead->whatsapp) }}" class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('WhatsApp Status') }}</label>
                            <select name="whatsapp_status" class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">{{ __('Select Status') }}</option>
                                @foreach($whatsappStatuses as $st)
                                    <option value="{{ $st }}" {{ old('whatsapp_status', $lead->whatsapp_status) === $st ? 'selected' : '' }}>{{ $st }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">🟣 {{ __('Imo Number') }}</label>
                            <input type="text" name="imo_number" value="{{ old('imo_number', $lead->imo_number) }}" class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">📞 {{ __('Mobile (Phone)') }} <span class="text-rose-500">*</span></label>
                            <input type="text" name="phone" value="{{ old('phone', $lead->phone) }}" required class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                            @error('phone') <span class="text-rose-600 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">✉️ {{ __('Email Address') }}</label>
                            <input type="email" name="email" value="{{ old('email', $lead->email) }}" class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Email Status') }}</label>
                            <select name="email_status" class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">{{ __('Select Status') }}</option>
                                @foreach($emailStatuses as $st)
                                    <option value="{{ $st }}" {{ old('email_status', $lead->email_status) === $st ? 'selected' : '' }}>{{ $st }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">💬 {{ __('Messenger Link') }}</label>
                            <input type="text" name="messenger_link" value="{{ old('messenger_link', $lead->messenger_link) }}" class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Messenger Status') }}</label>
                            <select name="messenger_status" class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">{{ __('Select Status') }}</option>
                                @foreach($messengerStatuses as $st)
                                    <option value="{{ $st }}" {{ old('messenger_status', $lead->messenger_status) === $st ? 'selected' : '' }}>{{ $st }}</option>
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
                            <input type="text" name="website" value="{{ old('website', $lead->website) }}" class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">📘 {{ __('FB Page') }}</label>
                            <input type="text" name="facebook" value="{{ old('facebook', $lead->facebook) }}" class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">📸 {{ __('Instagram') }}</label>
                            <input type="text" name="instagram" value="{{ old('instagram', $lead->instagram) }}" class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">🔴 {{ __('YouTube') }}</label>
                            <input type="text" name="youtube" value="{{ old('youtube', $lead->youtube) }}" class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">🎵 {{ __('TikTok') }}</label>
                            <input type="text" name="tiktok" value="{{ old('tiktok', $lead->tiktok) }}" class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">🐦 {{ __('Twitter / X') }}</label>
                            <input type="text" name="twitter" value="{{ old('twitter', $lead->twitter) }}" class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">👻 {{ __('Snapchat') }}</label>
                            <input type="text" name="snapchat" value="{{ old('snapchat', $lead->snapchat) }}" class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">💼 {{ __('LinkedIn') }}</label>
                            <input type="text" name="linkedin" value="{{ old('linkedin', $lead->linkedin) }}" class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">🔗 {{ __('Others Link') }}</label>
                            <input type="text" name="other_socials" value="{{ old('other_socials', $lead->other_socials) }}" class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
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
                            <input list="countries_list" name="current_country" value="{{ old('current_country', $lead->current_country ?? 'Saudi Arab') }}" class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">🏢 {{ __('Business Country') }}</label>
                            <input list="countries_list" name="business_country" value="{{ old('business_country', $lead->business_country ?? 'Saudi Arab') }}" class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">🗺️ {{ __('Google Maps Link') }}</label>
                            <input type="text" name="google_maps_link" value="{{ old('google_maps_link', $lead->google_maps_link) }}" class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <datalist id="countries_list">
                            @foreach($countries as $cnt)
                                <option value="{{ $cnt }}"></option>
                            @endforeach
                        </datalist>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Full Physical Address') }}</label>
                        <textarea name="address" rows="2" class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">{{ old('address', $lead->address) }}</textarea>
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
                        <textarea name="followup_comment" rows="2" class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">{{ old('followup_comment', $lead->followup_comment) }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Follow-up 1st Date') }}</label>
                            <input type="date" name="followup_first_date" value="{{ old('followup_first_date', $lead->followup_first_date?->format('Y-m-d')) }}" class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Follow-up Last Date') }}</label>
                            <input type="date" name="followup_last_date" value="{{ old('followup_last_date', $lead->followup_last_date?->format('Y-m-d')) }}" class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Follow-up Next Date') }}</label>
                            <input type="date" name="followup_next_date" value="{{ old('followup_next_date', $lead->followup_next_date?->format('Y-m-d') ?? $lead->next_followup_date?->format('Y-m-d')) }}" class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">💻 {{ __('Meeting Platform') }}</label>
                            <select name="meeting_platform" class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">{{ __('Select Meeting Platform') }}</option>
                                @foreach($meetingPlatforms as $mp)
                                    <option value="{{ $mp }}" {{ old('meeting_platform', $lead->meeting_platform) === $mp ? 'selected' : '' }}>{{ $mp }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">⏰ {{ __('Next Meeting Date & Time') }}</label>
                            <input type="datetime-local" name="next_meeting_datetime" value="{{ old('next_meeting_datetime', $lead->next_meeting_datetime?->format('Y-m-d\TH:i')) }}" class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
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
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('SRS Status') }}</label>
                            <select name="srs_status" class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">{{ __('Select SRS Status') }}</option>
                                @foreach($srsStatuses as $st)
                                    <option value="{{ $st }}" {{ old('srs_status', $lead->srs_status) === $st ? 'selected' : '' }}>{{ $st }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('SRS Number') }}</label>
                            <input type="text" name="srs_number" value="{{ old('srs_number', $lead->srs_number) }}" class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Interested Product / Service') }}</label>
                            <input type="text" name="interested_products" value="{{ old('interested_products', $lead->interested_products ?? $lead->interested_service) }}" placeholder="{{ __('Type interested product or service...') }}" class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Potential Order') }}</label>
                            <input type="text" name="potential_order" value="{{ old('potential_order', $lead->potential_order) }}" placeholder="{{ __('e.g. ERP software, 3 promo videos') }}" class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1 text-rose-700">{{ __('Why Not Order (Reason if Lost or Delayed)') }}</label>
                            <select name="why_not_order" class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">{{ __('Select Reason') }}</option>
                                @foreach($whyNotOrderOptions as $reason)
                                    <option value="{{ $reason }}" {{ old('why_not_order', $lead->why_not_order ?? $lead->lost_reason) === $reason ? 'selected' : '' }}>{{ $reason }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Retentions Marketing') }}</label>
                            <input type="text" name="retentions_marketing" value="{{ old('retentions_marketing', $lead->retentions_marketing) }}" placeholder="{{ __('e.g. Call after Eid offer') }}" class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Repeat Marketing') }}</label>
                            <input type="text" name="repeat_marketing" value="{{ old('repeat_marketing', $lead->repeat_marketing) }}" placeholder="{{ __('e.g. Next month website redesign pitch') }}" class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
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
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Assigned Staff') }}</label>
                            <select name="assigned_to" class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">{{ __('Unassigned') }}</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('assigned_to', $lead->assigned_to) == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }} ({{ $user->role->name ?? 'Staff' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Client Budget') }} ({{ __('BDT') }})</label>
                            <input type="number" step="0.01" name="budget" value="{{ old('budget', $lead->budget) }}" class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Discussed Amount') }} ({{ __('BDT') }})</label>
                            <input type="number" step="0.01" name="discussed_amount" value="{{ old('discussed_amount', $lead->discussed_amount) }}" class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Internal Notes') }}</label>
                        <textarea name="notes" rows="2" class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">{{ old('notes', $lead->notes) }}</textarea>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex items-center justify-end gap-3 pt-4">
                    <a href="{{ $returnTo ?? route('leads.index') }}" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-lg text-sm transition">
                        {{ __('Cancel') }}
                    </a>
                    <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg text-sm shadow-sm transition flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        {{ __('Update Lead') }}
                    </button>
                </div>
            </form>
        </div>
</x-app-layout>
