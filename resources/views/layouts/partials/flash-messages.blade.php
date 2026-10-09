@if(session('success') || session('error') || session('warning') || session('status'))
    <div class="px-4 sm:px-6 lg:px-8 pt-4">
        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-transition class="p-4 bg-emerald-50/90 border border-emerald-200 text-emerald-900 rounded-2xl flex items-center justify-between shadow-xs">
                <div class="flex items-center gap-3">
                    <div class="p-1.5 bg-emerald-100 rounded-lg text-emerald-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <span class="text-xs sm:text-sm font-semibold">{{ session('success') }}</span>
                </div>
                <button type="button" @click="show = false" class="text-emerald-500 hover:text-emerald-800 p-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div x-data="{ show: true }" x-show="show" x-transition class="p-4 bg-rose-50/90 border border-rose-200 text-rose-900 rounded-2xl flex items-center justify-between shadow-xs">
                <div class="flex items-center gap-3">
                    <div class="p-1.5 bg-rose-100 rounded-lg text-rose-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>
                    <span class="text-xs sm:text-sm font-semibold">{{ session('error') }}</span>
                </div>
                <button type="button" @click="show = false" class="text-rose-500 hover:text-rose-800 p-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        @endif

        @if(session('status'))
            <div x-data="{ show: true }" x-show="show" x-transition class="p-4 bg-indigo-50/90 border border-indigo-200 text-indigo-900 rounded-2xl flex items-center justify-between shadow-xs">
                <div class="flex items-center gap-3">
                    <div class="p-1.5 bg-indigo-100 rounded-lg text-indigo-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <span class="text-xs sm:text-sm font-semibold">{{ session('status') }}</span>
                </div>
                <button type="button" @click="show = false" class="text-indigo-500 hover:text-indigo-800 p-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        @endif
    </div>
@endif
