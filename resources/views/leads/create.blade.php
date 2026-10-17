<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight flex items-center gap-2">
                    <a href="{{ route('leads.index') }}" class="text-gray-400 hover:text-gray-600 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    </a>
                    {{ __('New Lead') }}
                </h2>
                <p class="text-sm text-gray-500 mt-0.5">{{ __('Capture prospective client or project inquiry details') }}</p>
            </div>
            <a href="{{ route('leads.index') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900 bg-white px-3.5 py-2 border border-gray-300 rounded-lg shadow-sm">
                {{ __('Back to List') }}
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Duplicate Warning Card from Session -->
            @if(session('duplicate_warning'))
                @php $dup = session('duplicate_warning'); @endphp
                <div class="mb-6 p-4 bg-amber-50 border-l-4 border-amber-500 rounded-r-xl shadow-sm">
                    <div class="flex items-start gap-3">
                        <svg class="w-6 h-6 text-amber-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        <div class="flex-1">
                            <h3 class="text-sm font-bold text-amber-800">{{ __('Duplicate Lead Detected!') }}</h3>
                            <p class="text-sm text-amber-700 mt-1">{{ $dup['message'] }}</p>
                            <div class="mt-3 flex items-center gap-3">
                                @if($dup['type'] === 'lead')
                                    <a href="{{ route('leads.show', $dup['record']->id) }}" target="_blank" class="inline-flex items-center text-xs font-semibold px-3 py-1.5 bg-amber-600 text-white rounded-md hover:bg-amber-700 transition">
                                        {{ __('View Existing Lead') }} ({{ $dup['record']->lead_code }}) &rarr;
                                    </a>
                                @else
                                    <a href="{{ route('customers.show', $dup['record']->id) }}" target="_blank" class="inline-flex items-center text-xs font-semibold px-3 py-1.5 bg-emerald-600 text-white rounded-md hover:bg-emerald-700 transition">
                                        {{ __('View Customer Profile') }} ({{ $dup['record']->customer_code }}) &rarr;
                                    </a>
                                @endif
                                <label class="inline-flex items-center gap-1.5 text-xs text-amber-900 font-medium cursor-pointer">
                                    <input type="checkbox" name="allow_duplicate" value="1" form="lead-form" class="rounded border-amber-300 text-amber-600 focus:ring-amber-500">
                                    <span>{{ __('Force create duplicate lead anyway') }}</span>
                                </label>
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

                <!-- Section 1: Customer & Business Info -->
                <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm space-y-4">
                    <h3 class="text-base font-bold text-gray-900 flex items-center gap-2 border-b pb-3">
                        <span class="w-7 h-7 rounded-lg bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold text-sm">1</span>
                        {{ __('Personal & Organization Info') }}
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        <!-- Name -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Customer Name') }} <span class="text-rose-500">*</span></label>
                            <input type="text" name="name" value="{{ old('name') }}" required placeholder="e.g. John Doe" class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                            @error('name') <span class="text-rose-600 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Phone -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Mobile Number') }} <span class="text-rose-500">*</span></label>
                            <input type="text" id="phone" name="phone" value="{{ old('phone') }}" required placeholder="017XXXXXXXX" class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                            <span class="text-[11px] text-gray-400">{{ __('Duplicate check enabled (+88 auto-normalized)') }}</span>
                            @error('phone') <span class="text-rose-600 text-xs block">{{ $message }}</span> @enderror
                        </div>

                        <!-- WhatsApp -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('WhatsApp (If any)') }}</label>
                            <input type="text" id="whatsapp" name="whatsapp" value="{{ old('whatsapp') }}" placeholder="018XXXXXXXX" class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <!-- Email -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Email Address') }}</label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="client@example.com" class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <!-- Company Name -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Company Name') }}</label>
                            <input type="text" name="company_name" value="{{ old('company_name') }}" placeholder="e.g. Tech Solutions Ltd." class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <!-- Business Type -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Business Type') }}</label>
                            <input type="text" name="business_type" value="{{ old('business_type') }}" placeholder="e.g. Restaurant, E-commerce, NGO" class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-2">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Address') }}</label>
                            <input type="text" name="address" value="{{ old('address') }}" placeholder="Street / Area" class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('City / District') }}</label>
                            <input type="text" name="city" value="{{ old('city', 'Dhaka') }}" placeholder="Dhaka, Chittagong, etc." class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Website / Social Page') }}</label>
                            <input type="text" name="website" value="{{ old('website') }}" placeholder="https://..." class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                    </div>
                </div>

                <!-- Section 2: Service, Budget & Source -->
                <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm space-y-4">
                    <h3 class="text-base font-bold text-gray-900 flex items-center gap-2 border-b pb-3">
                        <span class="w-7 h-7 rounded-lg bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold text-sm">2</span>
                        {{ __('Service Requirements & Sales Parameters') }}
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <!-- Lead Source -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Lead Source') }} <span class="text-rose-500">*</span></label>
                            <select name="lead_source" required class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                                @foreach($leadSources as $src)
                                    <option value="{{ $src }}" {{ old('lead_source') == $src ? 'selected' : '' }}>{{ __($src) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Interested Service -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Interested Service') }} <span class="text-rose-500">*</span></label>
                            <select name="interested_service" required class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                                @foreach($serviceCategories as $cat)
                                    <option value="{{ $cat }}" {{ old('interested_service') == $cat ? 'selected' : '' }}>{{ __($cat) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Lead Temperature -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Lead Temperature') }} <span class="text-rose-500">*</span></label>
                            <select name="lead_temperature" required class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 font-semibold">
                                <option value="Hot" class="text-rose-600" {{ old('lead_temperature') == 'Hot' ? 'selected' : '' }}>🔥 {{ __('Hot (High interest & ready to start)') }}</option>
                                <option value="Warm" class="text-amber-600" {{ old('lead_temperature', 'Warm') == 'Warm' ? 'selected' : '' }}>⚡ {{ __('Warm (Interested, taking time)') }}</option>
                                <option value="Cold" class="text-sky-600" {{ old('lead_temperature') == 'Cold' ? 'selected' : '' }}>❄️ {{ __('Cold (Price checking / low interest)') }}</option>
                            </select>
                        </div>

                        <!-- Assigned To -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Assigned Sales Agent') }}</label>
                            <select name="assigned_to" class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">{{ __('Unassigned') }}</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('assigned_to', auth()->id()) == $user->id ? 'selected' : '' }}>{{ $user->name }} ({{ $user->role->name ?? 'User' }})</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Budget -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Client Budget') }} ({{ __('BDT') }})</label>
                            <input type="number" step="0.01" name="budget" value="{{ old('budget') }}" placeholder="e.g. 25000" class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <!-- Discussed Amount -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Discussed Amount') }} ({{ __('BDT') }})</label>
                            <input type="number" step="0.01" name="discussed_amount" value="{{ old('discussed_amount') }}" placeholder="e.g. 22000" class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <!-- Next Follow-up Date -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Next Follow-up Date') }}</label>
                            <input type="datetime-local" name="next_followup_date" value="{{ old('next_followup_date') }}" class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <!-- Expected Work Date -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Expected Work Date') }}</label>
                            <input type="date" name="expected_work_date" value="{{ old('expected_work_date') }}" class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                    </div>

                    <!-- Notes -->
                    <div class="pt-2">
                        <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">{{ __('Initial Notes & Requirements') }}</label>
                        <textarea name="notes" rows="3" placeholder="{{ __('Client requirements, software or video specifications...') }}" class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">{{ old('notes') }}</textarea>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex items-center justify-end gap-3">
                    <a href="{{ route('leads.index') }}" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-lg text-sm transition">
                        {{ __('Cancel') }}
                    </a>
                    <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg text-sm shadow-sm transition flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        {{ __('Save Lead') }}
                    </button>
                </div>
            </form>

        </div>
    </div>

    <!-- Live Duplicate Checking Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const phoneInput = document.getElementById('phone');
            const whatsappInput = document.getElementById('whatsapp');
            const emailInput = document.getElementById('email');
            const alertBox = document.getElementById('live-duplicate-alert');
            const msgBox = document.getElementById('live-duplicate-msg');
            const linkContainer = document.getElementById('live-duplicate-link-container');

            let timer = null;

            function checkDuplicates() {
                const phone = phoneInput.value.trim();
                const whatsapp = whatsappInput.value.trim();
                const email = emailInput.value.trim();

                if (phone.length < 5 && whatsapp.length < 5 && email.length < 5) {
                    alertBox.classList.add('hidden');
                    return;
                }

                fetch('{{ route('leads.check-duplicate') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ phone, whatsapp, email })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.is_duplicate) {
                        msgBox.innerText = data.message;
                        if (data.type === 'lead') {
                            linkContainer.innerHTML = `<a href="/leads/${data.record.id}" target="_blank" class="inline-flex items-center text-xs font-semibold px-2.5 py-1 bg-rose-600 text-white rounded hover:bg-rose-700 transition">{{ __('View Existing Lead') }} (${data.record.lead_code}) &rarr;</a>`;
                        } else if (data.type === 'customer') {
                            linkContainer.innerHTML = `<a href="/customers/${data.record.id}" target="_blank" class="inline-flex items-center text-xs font-semibold px-2.5 py-1 bg-emerald-600 text-white rounded hover:bg-emerald-700 transition">{{ __('View Customer Profile') }} (${data.record.customer_code}) &rarr;</a>`;
                        }
                        alertBox.classList.remove('hidden');
                    } else {
                        alertBox.classList.add('hidden');
                    }
                })
                .catch(err => console.error('Duplicate check error:', err));
            }

            [phoneInput, whatsappInput, emailInput].forEach(el => {
                el.addEventListener('input', () => {
                    clearTimeout(timer);
                    timer = setTimeout(checkDuplicates, 500);
                });
            });
        });
    </script>
</x-app-layout>
