<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-900 leading-tight flex items-center gap-2">
                    <svg class="w-7 h-7 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2 1.5 3 3.5 3h9c2 0 3.5-1 3.5-3V7M4 7c0-2 1.5-3 3.5-3h9c2 0 3.5 1 3.5 3M4 7h16m-8 4v6m-3-3l3 3 3-3"/>
                    </svg>
                    <span>{{ __('ডাটাবেজ ও ফাইল অটো-ব্যাকআপ') }} (SRS Section 53)</span>
                </h2>
                <p class="text-sm text-gray-500 mt-1">সিস্টেমের পূর্ণাঙ্গ ডাটাবেজ স্ন্যাপশট, রিটেনশন পলিসি ও ইনস্ট্যান্ট রিকভারি ফাইল ব্যবস্থাপনা</p>
            </div>
            
            <form action="{{ route('backups.create') }}" method="POST" class="flex flex-wrap items-center gap-3">
                @csrf
                <label class="flex items-center gap-2 text-xs text-gray-600 cursor-pointer bg-white px-3 py-2 rounded-lg border border-gray-200 hover:bg-gray-50">
                    <input type="checkbox" name="include_files" value="1" class="rounded text-indigo-600 focus:ring-indigo-500">
                    <span>সংযুক্ত ফাইলসমূহ ব্যাকআপ নিন</span>
                </label>
                <button type="submit" class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg shadow-sm transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>এখনই ব্যাকআপ তৈরি করুন</span>
                </button>
            </form>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl flex items-center gap-3 shadow-sm">
                    <svg class="w-6 h-6 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span class="text-sm font-medium">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl flex items-center gap-3 shadow-sm">
                    <svg class="w-6 h-6 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span class="text-sm font-medium">{{ session('error') }}</span>
                </div>
            @endif

            <!-- Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-5">
                <div class="bg-white rounded-xl p-5 border border-gray-200 shadow-sm flex items-center gap-4">
                    <div class="p-3 bg-indigo-50 text-indigo-600 rounded-xl">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2 1.5 3 3.5 3h9c2 0 3.5-1 3.5-3V7M4 7c0-2 1.5-3 3.5-3h9c2 0 3.5 1 3.5 3M4 7h16"/></svg>
                    </div>
                    <div>
                        <div class="text-xs font-semibold uppercase tracking-wider text-gray-500">মোট সংরক্ষিত ব্যাকআপ</div>
                        <div class="text-2xl font-black text-gray-900 mt-1">{{ count($backups) }} টি ফাইল</div>
                    </div>
                </div>

                <div class="bg-white rounded-xl p-5 border border-gray-200 shadow-sm flex items-center gap-4">
                    <div class="p-3 bg-emerald-50 text-emerald-600 rounded-xl">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <div class="text-xs font-semibold uppercase tracking-wider text-gray-500">অটো-শিডিউল ফ্রিকোয়েন্সি</div>
                        <div class="text-base font-bold text-gray-900 mt-1">প্রতিদিন রাত ১২:০০ টা</div>
                        <div class="text-xs text-emerald-600">Scheduler Active</div>
                    </div>
                </div>

                <div class="bg-white rounded-xl p-5 border border-gray-200 shadow-sm flex items-center gap-4">
                    <div class="p-3 bg-amber-50 text-amber-600 rounded-xl">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </div>
                    <div>
                        <div class="text-xs font-semibold uppercase tracking-wider text-gray-500">রিটেনশন লিমিট</div>
                        <div class="text-xl font-bold text-gray-900 mt-1">১৪ দিন</div>
                        <div class="text-xs text-gray-400">পুরোনো ফাইল স্বয়ংক্রিয় ক্লিনআপ</div>
                    </div>
                </div>

                <div class="bg-white rounded-xl p-5 border border-gray-200 shadow-sm flex items-center gap-4">
                    <div class="p-3 bg-purple-50 text-purple-600 rounded-xl">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <div>
                        <div class="text-xs font-semibold uppercase tracking-wider text-gray-500">CLI কমান্ড</div>
                        <div class="text-xs font-mono font-bold text-gray-800 bg-gray-100 px-2 py-1 rounded mt-1 select-all">php artisan crm:backup-db</div>
                    </div>
                </div>
            </div>

            <!-- Backups Table -->
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center bg-gray-50">
                    <h3 class="font-bold text-gray-800">সংরক্ষিত ব্যাকআপ ফাইলসমূহ (Latest First)</h3>
                    <span class="text-xs text-gray-500">পাথ: storage/app/backups</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-left text-sm">
                        <thead class="bg-gray-100/75 text-xs uppercase tracking-wider text-gray-600 font-semibold">
                            <tr>
                                <th class="px-6 py-3.5">ফাইল নাম</th>
                                <th class="px-6 py-3.5">ফরমেট</th>
                                <th class="px-6 py-3.5">সাইজ</th>
                                <th class="px-6 py-3.5">তৈরির তারিখ ও সময়</th>
                                <th class="px-6 py-3.5 text-right">অ্যাকশন</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($backups as $b)
                                <tr class="hover:bg-gray-50/80 transition">
                                    <td class="px-6 py-4 font-mono font-semibold text-gray-900 flex items-center gap-2">
                                        <svg class="w-5 h-5 text-indigo-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                        <span>{{ $b['filename'] }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-2.5 py-1 rounded text-xs font-semibold uppercase {{ $b['extension'] === 'zip' ? 'bg-indigo-50 text-indigo-700 border border-indigo-200' : 'bg-amber-50 text-amber-700 border border-amber-200' }}">
                                            {{ $b['extension'] }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 font-semibold text-gray-700">
                                        {{ $b['size'] }}
                                    </td>
                                    <td class="px-6 py-4 text-gray-600">
                                        {{ $b['created_at'] }}
                                    </td>
                                    <td class="px-6 py-4 text-right space-x-2">
                                        <a href="{{ route('backups.download', $b['filename']) }}" class="inline-flex items-center gap-1 px-3 py-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-300 rounded text-xs font-medium transition">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                            ডাউনলোড
                                        </a>

                                        <form action="{{ route('backups.destroy', $b['filename']) }}" method="POST" class="inline" onsubmit="return confirm('আপনি কি নিশ্চিতভাবে এই ব্যাকআপ ফাইলটি ডিলিট করতে চান?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 rounded text-xs font-medium transition">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                মুছুন
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                        এখনও কোনো ব্যাকআপ তৈরি করা হয়নি। উপরের বাটন ক্লিক করে প্রথম ব্যাকআপ তৈরি করুন।
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Recovery & Restore Instructions -->
            <div class="bg-gray-50 rounded-xl p-6 border border-gray-200">
                <h4 class="font-bold text-gray-900 flex items-center gap-2 mb-2">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>জরুরি রিকভারি ও রিস্টোর প্রসিডিউর (Disaster Recovery)</span>
                </h4>
                <div class="text-sm text-gray-600 space-y-2">
                    <p>সার্ভার ক্র্যাশ বা ডাটা রিস্টোরের প্রয়োজন হলে নিচের কমান্ডগুলোর সাহায্যে ডাটাবেজ রিস্টোর করা যাবে:</p>
                    <div class="bg-gray-900 text-emerald-400 p-3 rounded-lg font-mono text-xs overflow-x-auto select-all">
                        # ১. জিপ ফাইল আনজিপ করুন<br>
                        unzip ps_crm_backup_YYYY-MM-DD_HHMMSS.zip<br><br>
                        # ২. MySQL কমান্ডের মাধ্যমে ডাটা রিস্টোর করুন<br>
                        mysql -u username -p projukti_sheba_crm < ps_crm_backup_YYYY-MM-DD_HHMMSS.sql
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
