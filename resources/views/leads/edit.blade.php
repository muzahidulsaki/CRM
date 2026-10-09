<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight flex items-center gap-2">
                    <a href="{{ route('leads.show', $lead) }}" class="text-gray-400 hover:text-gray-600 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    </a>
                    লিড সম্পাদনা: {{ $lead->lead_code }}
                </h2>
                <p class="text-sm text-gray-500 mt-0.5">{{ $lead->name }} - {{ $lead->company_name ?? 'ব্যক্তিগত' }}</p>
            </div>
            <a href="{{ route('leads.show', $lead) }}" class="text-sm font-medium text-gray-600 hover:text-gray-900 bg-white px-3.5 py-2 border border-gray-300 rounded-lg shadow-sm">
                প্রোফাইলে ফিরে যান
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

            <form method="POST" action="{{ route('leads.update', $lead) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Section 1: Customer & Business Info -->
                <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm space-y-4">
                    <h3 class="text-base font-bold text-gray-900 flex items-center gap-2 border-b pb-3">
                        <span class="w-7 h-7 rounded-lg bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold text-sm">১</span>
                        ব্যক্তি ও প্রাতিষ্ঠানিক তথ্য
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">গ্রাহকের নাম <span class="text-rose-500">*</span></label>
                            <input type="text" name="name" value="{{ old('name', $lead->name) }}" required class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                            @error('name') <span class="text-rose-600 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">মোবাইল নম্বর <span class="text-rose-500">*</span></label>
                            <input type="text" name="phone" value="{{ old('phone', $lead->phone) }}" required class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                            @error('phone') <span class="text-rose-600 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">হোয়াটসঅ্যাপ</label>
                            <input type="text" name="whatsapp" value="{{ old('whatsapp', $lead->whatsapp) }}" class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">ইমেইল অ্যাড্রেস</label>
                            <input type="email" name="email" value="{{ old('email', $lead->email) }}" class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">প্রতিষ্ঠানের নাম</label>
                            <input type="text" name="company_name" value="{{ old('company_name', $lead->company_name) }}" class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">ব্যবসার ধরন</label>
                            <input type="text" name="business_type" value="{{ old('business_type', $lead->business_type) }}" class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-2">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">ঠিকানা</label>
                            <input type="text" name="address" value="{{ old('address', $lead->address) }}" class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">শহর / জেলা</label>
                            <input type="text" name="city" value="{{ old('city', $lead->city) }}" class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">ওয়েবসাইট / পেজ</label>
                            <input type="text" name="website" value="{{ old('website', $lead->website) }}" class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                    </div>
                </div>

                <!-- Section 2: Pipeline, Service & Budget -->
                <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm space-y-4">
                    <h3 class="text-base font-bold text-gray-900 flex items-center gap-2 border-b pb-3">
                        <span class="w-7 h-7 rounded-lg bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold text-sm">২</span>
                        পাইপলাইন স্ট্যাটাস ও সেলস প্যারামিটার
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <!-- Lead Status -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">লিড স্ট্যাটাস <span class="text-rose-500">*</span></label>
                            <select name="lead_status" required class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 font-semibold text-indigo-900 bg-indigo-50/30">
                                @foreach(['New', 'Contacted', 'Interested', 'Meeting/Demo', 'Proposal Sent', 'Negotiation', 'Won', 'Lost'] as $st)
                                    <option value="{{ $st }}" {{ old('lead_status', $lead->lead_status) == $st ? 'selected' : '' }}>{{ $st }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Lead Temperature -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">লিড টেম্পারেচার <span class="text-rose-500">*</span></label>
                            <select name="lead_temperature" required class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 font-semibold">
                                <option value="Hot" class="text-rose-600" {{ old('lead_temperature', $lead->lead_temperature) == 'Hot' ? 'selected' : '' }}>🔥 Hot (খুব আগ্রহী)</option>
                                <option value="Warm" class="text-amber-600" {{ old('lead_temperature', $lead->lead_temperature) == 'Warm' ? 'selected' : '' }}>⚡ Warm (মাঝারি)</option>
                                <option value="Cold" class="text-sky-600" {{ old('lead_temperature', $lead->lead_temperature) == 'Cold' ? 'selected' : '' }}>❄️ Cold (কম আগ্রহী)</option>
                            </select>
                        </div>

                        <!-- Interested Service -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">আগ্রহী সার্ভিস <span class="text-rose-500">*</span></label>
                            <select name="interested_service" required class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                                @foreach($serviceCategories as $cat)
                                    <option value="{{ $cat }}" {{ old('interested_service', $lead->interested_service) == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Lead Source -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">লিড সোর্স <span class="text-rose-500">*</span></label>
                            <select name="lead_source" required class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                                @foreach($leadSources as $src)
                                    <option value="{{ $src }}" {{ old('lead_source', $lead->lead_source) == $src ? 'selected' : '' }}>{{ $src }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Assigned To -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">দায়িত্বপ্রাপ্ত সেলস কর্মী</label>
                            <select name="assigned_to" class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">অনির্ধারিত (Unassigned)</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('assigned_to', $lead->assigned_to) == $user->id ? 'selected' : '' }}>{{ $user->name }} ({{ $user->role->name ?? 'User' }})</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Budget -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">ক্লায়েন্টের বাজেট (৳)</label>
                            <input type="number" step="0.01" name="budget" value="{{ old('budget', $lead->budget) }}" class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <!-- Discussed Amount -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">আলোচিত পরিমাণ (৳)</label>
                            <input type="number" step="0.01" name="discussed_amount" value="{{ old('discussed_amount', $lead->discussed_amount) }}" class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <!-- Next Follow-up Date -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">পরবর্তী ফলো-আপ</label>
                            <input type="datetime-local" name="next_followup_date" value="{{ old('next_followup_date', $lead->next_followup_date?->format('Y-m-d\TH:i')) }}" class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                    </div>

                    <!-- Lost Reason (if Lost) -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">লিড বাতিলের কারণ (যদি বাতিল বা Lost হয়)</label>
                        <input type="text" name="lost_reason" value="{{ old('lost_reason', $lead->lost_reason) }}" placeholder="উদা: বাজেট স্বল্পতা, অন্য এজেন্সি পছন্দ করেছে, যোগাযোগ বন্ধ" class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <!-- Notes -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">নোট ও বিশেষ বিবরণ</label>
                        <textarea name="notes" rows="3" class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">{{ old('notes', $lead->notes) }}</textarea>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex items-center justify-end gap-3">
                    <a href="{{ route('leads.show', $lead) }}" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-lg text-sm transition">
                        বাতিল
                    </a>
                    <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg text-sm shadow-sm transition flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        আপডেট সংরক্ষণ করুন
                    </button>
                </div>
            </form>

        </div>
    </div>
</x-app-layout>
