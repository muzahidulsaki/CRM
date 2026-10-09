<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight flex items-center gap-2">
                    <a href="{{ route('customers.show', $customer) }}" class="text-gray-400 hover:text-gray-600 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    </a>
                    গ্রাহক তথ্য সম্পাদনা: {{ $customer->customer_code }}
                </h2>
                <p class="text-sm text-gray-500 mt-0.5">{{ $customer->name }}</p>
            </div>
            <a href="{{ route('customers.show', $customer) }}" class="text-sm font-medium text-gray-600 hover:text-gray-900 bg-white px-3.5 py-2 border border-gray-300 rounded-lg shadow-sm">
                প্রোফাইলে ফিরে যান
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            <form method="POST" action="{{ route('customers.update', $customer) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm space-y-4">
                    <h3 class="text-base font-bold text-gray-900 border-b pb-3">গ্রাহকের মৌলিক তথ্য</h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">গ্রাহকের নাম <span class="text-rose-500">*</span></label>
                            <input type="text" name="name" value="{{ old('name', $customer->name) }}" required class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">মোবাইল নম্বর <span class="text-rose-500">*</span></label>
                            <input type="text" name="phone" value="{{ old('phone', $customer->phone) }}" required class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">হোয়াটসঅ্যাপ</label>
                            <input type="text" name="whatsapp" value="{{ old('whatsapp', $customer->whatsapp) }}" class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">ইমেইল অ্যাড্রেস</label>
                            <input type="email" name="email" value="{{ old('email', $customer->email) }}" class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">প্রতিষ্ঠানের নাম</label>
                            <input type="text" name="company_name" value="{{ old('company_name', $customer->company_name) }}" class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">ব্যবসার ধরন</label>
                            <input type="text" name="business_type" value="{{ old('business_type', $customer->business_type) }}" class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-2">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">ঠিকানা</label>
                            <input type="text" name="address" value="{{ old('address', $customer->address) }}" class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">শহর</label>
                            <input type="text" name="city" value="{{ old('city', $customer->city) }}" class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">স্ট্যাটাস</label>
                            <select name="status" class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="active" {{ $customer->status == 'active' ? 'selected' : '' }}>সক্রিয় (Active)</option>
                                <option value="inactive" {{ $customer->status == 'inactive' ? 'selected' : '' }}>নিষ্ক্রিয় (Inactive)</option>
                            </select>
                        </div>
                    </div>

                    <div class="pt-2">
                        <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">মন্তব্য / নোট</label>
                        <textarea name="notes" rows="2" class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">{{ old('notes', $customer->notes) }}</textarea>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3">
                    <a href="{{ route('customers.show', $customer) }}" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-lg text-sm transition">
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
