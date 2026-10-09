<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight flex items-center gap-2">
                    <a href="{{ route('leads.index') }}" class="text-gray-400 hover:text-gray-600 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    </a>
                    নতুন লিড এন্ট্রি (New Lead)
                </h2>
                <p class="text-sm text-gray-500 mt-0.5">নতুন গ্রাহক বা সম্ভাব্য প্রজেক্টের বিস্তারিত তথ্য সংগ্রহ করুন</p>
            </div>
            <a href="{{ route('leads.index') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900 bg-white px-3.5 py-2 border border-gray-300 rounded-lg shadow-sm">
                তালিকায় ফিরে যান
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
                            <h3 class="text-sm font-bold text-amber-800">ডুপ্লিকেট লিড সনাক্ত হয়েছে!</h3>
                            <p class="text-sm text-amber-700 mt-1">{{ $dup['message'] }}</p>
                            <div class="mt-3 flex items-center gap-3">
                                @if($dup['type'] === 'lead')
                                    <a href="{{ route('leads.show', $dup['record']->id) }}" target="_blank" class="inline-flex items-center text-xs font-semibold px-3 py-1.5 bg-amber-600 text-white rounded-md hover:bg-amber-700 transition">
                                        বিদ্যমান লিড দেখুন ({{ $dup['record']->lead_code }}) &rarr;
                                    </a>
                                @else
                                    <a href="{{ route('customers.show', $dup['record']->id) }}" target="_blank" class="inline-flex items-center text-xs font-semibold px-3 py-1.5 bg-emerald-600 text-white rounded-md hover:bg-emerald-700 transition">
                                        গ্রাহকের প্রোফাইল দেখুন ({{ $dup['record']->customer_code }}) &rarr;
                                    </a>
                                @endif
                                <label class="inline-flex items-center gap-1.5 text-xs text-amber-900 font-medium cursor-pointer">
                                    <input type="checkbox" name="allow_duplicate" value="1" form="lead-form" class="rounded border-amber-300 text-amber-600 focus:ring-amber-500">
                                    <span>তবুও নতুন লিড তৈরি করতে বাধ্য করুন</span>
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
                        <h3 class="text-sm font-bold text-rose-800">সতর্কতা: ডুপ্লিকেট কন্ট্যাক্ট নম্বর!</h3>
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
                        <span class="w-7 h-7 rounded-lg bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold text-sm">১</span>
                        ব্যক্তি ও প্রাতিষ্ঠানিক তথ্য
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        <!-- Name -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">গ্রাহকের নাম <span class="text-rose-500">*</span></label>
                            <input type="text" name="name" value="{{ old('name') }}" required placeholder="উদা: মোঃ আরিফুল ইসলাম" class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                            @error('name') <span class="text-rose-600 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Phone -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">মোবাইল নম্বর <span class="text-rose-500">*</span></label>
                            <input type="text" id="phone" name="phone" value="{{ old('phone') }}" required placeholder="উদা: 017XXXXXXXX" class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                            <span class="text-[11px] text-gray-400">ডুপ্লিকেট চেক সক্রিয় (+88 স্বয়ংক্রিয় নরম্যালাইজ হবে)</span>
                            @error('phone') <span class="text-rose-600 text-xs block">{{ $message }}</span> @enderror
                        </div>

                        <!-- WhatsApp -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">হোয়াটসঅ্যাপ (যদি থাকে)</label>
                            <input type="text" id="whatsapp" name="whatsapp" value="{{ old('whatsapp') }}" placeholder="উদা: 018XXXXXXXX" class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <!-- Email -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">ইমেইল অ্যাড্রেস</label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="client@example.com" class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <!-- Company Name -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">প্রতিষ্ঠানের নাম</label>
                            <input type="text" name="company_name" value="{{ old('company_name') }}" placeholder="উদা: টেকনোলজি বিডি লিমিটেড" class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <!-- Business Type -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">ব্যবসার ধরন</label>
                            <input type="text" name="business_type" value="{{ old('business_type') }}" placeholder="উদা: রেস্টুরেন্ট, ই-কমার্স, এনজিও" class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-2">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">ঠিকানা</label>
                            <input type="text" name="address" value="{{ old('address') }}" placeholder="বাড়ি/রোড/এরিয়া" class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">শহর / জেলা</label>
                            <input type="text" name="city" value="{{ old('city', 'Dhaka') }}" placeholder="Dhaka, Chittagong, etc." class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">ওয়েবসাইট / ফেসবুক পেজ</label>
                            <input type="text" name="website" value="{{ old('website') }}" placeholder="https://..." class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                    </div>
                </div>

                <!-- Section 2: Service, Budget & Source -->
                <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm space-y-4">
                    <h3 class="text-base font-bold text-gray-900 flex items-center gap-2 border-b pb-3">
                        <span class="w-7 h-7 rounded-lg bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold text-sm">২</span>
                        সার্ভিস রিকোয়ারমেন্ট ও সেলস প্যারামিটার
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <!-- Lead Source -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">লিড সোর্স (Lead Source) <span class="text-rose-500">*</span></label>
                            <select name="lead_source" required class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                                @foreach($leadSources as $src)
                                    <option value="{{ $src }}" {{ old('lead_source') == $src ? 'selected' : '' }}>{{ $src }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Interested Service (8 categories) -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">আগ্রহী সার্ভিস <span class="text-rose-500">*</span></label>
                            <select name="interested_service" required class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                                @foreach($serviceCategories as $cat)
                                    <option value="{{ $cat }}" {{ old('interested_service') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Lead Temperature -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">লিড টেম্পারেচার <span class="text-rose-500">*</span></label>
                            <select name="lead_temperature" required class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 font-semibold">
                                <option value="Hot" class="text-rose-600" {{ old('lead_temperature') == 'Hot' ? 'selected' : '' }}>🔥 Hot (খুব আগ্রহী ও দ্রুত কাজ শুরু)</option>
                                <option value="Warm" class="text-amber-600" {{ old('lead_temperature', 'Warm') == 'Warm' ? 'selected' : '' }}>⚡ Warm (আগ্রহী কিন্তু সময় নিচ্ছে)</option>
                                <option value="Cold" class="text-sky-600" {{ old('lead_temperature') == 'Cold' ? 'selected' : '' }}>❄️ Cold (কম আগ্রহী / প্রাইস যাচাই)</option>
                            </select>
                        </div>

                        <!-- Assigned To -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">দায়িত্বপ্রাপ্ত সেলস কর্মী</label>
                            <select name="assigned_to" class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">অনির্ধারিত (Unassigned)</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('assigned_to', auth()->id()) == $user->id ? 'selected' : '' }}>{{ $user->name }} ({{ $user->role->name ?? 'User' }})</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Budget -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">ক্লায়েন্টের সম্ভাব্য বাজেট (৳)</label>
                            <input type="number" step="0.01" name="budget" value="{{ old('budget') }}" placeholder="উদা: 25000" class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <!-- Discussed Amount -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">আলোচিত টাকার পরিমাণ (৳)</label>
                            <input type="number" step="0.01" name="discussed_amount" value="{{ old('discussed_amount') }}" placeholder="উদা: 22000" class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <!-- Next Follow-up Date -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">পরবর্তী যোগাযোগের সময়</label>
                            <input type="datetime-local" name="next_followup_date" value="{{ old('next_followup_date') }}" class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <!-- Expected Work Date -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">কবে কাজ শুরু হতে পারে</label>
                            <input type="date" name="expected_work_date" value="{{ old('expected_work_date') }}" class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                    </div>

                    <!-- Notes -->
                    <div class="pt-2">
                        <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">প্রাথমিক আলোচনার বিবরণ ও নোট</label>
                        <textarea name="notes" rows="3" placeholder="ক্লায়েন্টের চাহিদা, সফটওয়্যার বা ভিডিওর স্পেসিফিকেশন..." class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">{{ old('notes') }}</textarea>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex items-center justify-end gap-3">
                    <a href="{{ route('leads.index') }}" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-lg text-sm transition">
                        বাতিল
                    </a>
                    <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg text-sm shadow-sm transition flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        লিড সংরক্ষণ করুন
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
                            linkContainer.innerHTML = `<a href="/leads/${data.record.id}" target="_blank" class="inline-flex items-center text-xs font-semibold px-2.5 py-1 bg-rose-600 text-white rounded hover:bg-rose-700 transition">পূর্বের লিড দেখুন (${data.record.lead_code}) &rarr;</a>`;
                        } else if (data.type === 'customer') {
                            linkContainer.innerHTML = `<a href="/customers/${data.record.id}" target="_blank" class="inline-flex items-center text-xs font-semibold px-2.5 py-1 bg-emerald-600 text-white rounded hover:bg-emerald-700 transition">পূর্বের গ্রাহক প্রোফাইল দেখুন (${data.record.customer_code}) &rarr;</a>`;
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
