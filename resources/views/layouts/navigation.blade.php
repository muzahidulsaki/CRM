@php
    $unreadNotificationCount = \App\Models\CrmNotification::where('is_read', false)->count();
    $currentUser = Auth::user();
@endphp

<!-- Mobile Sidebar Drawer (Off-canvas) -->
<div x-show="sidebarOpen" 
     class="relative z-50 lg:hidden" 
     role="dialog" 
     aria-modal="true" 
     x-cloak>
    
    <!-- Backdrop Overlay -->
    <div x-show="sidebarOpen"
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-slate-900/80 backdrop-blur-xs"
         @click="sidebarOpen = false"></div>

    <div class="fixed inset-0 flex">
        <div x-show="sidebarOpen"
             x-transition:enter="transition ease-in-out duration-300 transform"
             x-transition:enter-start="-translate-x-full"
             x-transition:enter-end="translate-x-0"
             x-transition:leave="transition ease-in-out duration-300 transform"
             x-transition:leave-start="translate-x-0"
             x-transition:leave-end="-translate-x-full"
             class="relative mr-16 flex w-full max-w-xs flex-1">
            
            <!-- Close Button -->
            <div class="absolute left-full top-0 flex w-16 justify-center pt-5">
                <button type="button" @click="sidebarOpen = false" class="-m-2.5 p-2.5 text-white/80 hover:text-white transition">
                    <span class="sr-only">Close sidebar</span>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Mobile Drawer Content -->
            <div class="flex grow flex-col gap-y-5 overflow-y-auto bg-slate-900 px-5 pb-4 custom-scrollbar ring-1 ring-white/10">
                <!-- Brand Header -->
                <div class="flex h-16 shrink-0 items-center gap-3 border-b border-slate-800">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-indigo-600 via-indigo-500 to-cyan-400 flex items-center justify-center text-white font-black text-xl shadow-lg shadow-indigo-500/25">
                        PS
                    </div>
                    <div>
                        <div class="font-extrabold text-white text-base tracking-tight leading-none">প্রযুক্তি সেবা</div>
                        <div class="text-[11px] font-semibold text-indigo-400 tracking-wider uppercase mt-1">CRM & Business ERP</div>
                    </div>
                </div>

                <!-- Navigation Links -->
                @include('layouts.partials.sidebar-nav-links')

                <!-- User Profile at bottom -->
                @include('layouts.partials.sidebar-user-pill')
            </div>
        </div>
    </div>
</div>

<!-- Desktop Fixed Sidebar -->
<aside class="hidden lg:fixed lg:inset-y-0 lg:z-50 lg:flex lg:w-72 lg:flex-col">
    <div class="flex grow flex-col gap-y-5 overflow-y-auto bg-slate-900 border-r border-slate-800 px-5 pb-4 custom-scrollbar">
        <!-- Brand Header -->
        <div class="flex h-16 shrink-0 items-center justify-between border-b border-slate-800">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-indigo-600 via-indigo-500 to-cyan-400 flex items-center justify-center text-white font-black text-xl shadow-lg shadow-indigo-500/20 group-hover:scale-105 transition">
                    PS
                </div>
                <div>
                    <div class="font-extrabold text-white text-base tracking-tight leading-none group-hover:text-indigo-400 transition">প্রযুক্তি সেবা</div>
                    <div class="text-[10px] font-bold text-indigo-400 tracking-wider uppercase mt-1">CRM & ERP Platform</div>
                </div>
            </a>

            <div class="flex items-center gap-1">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-soft-pulse" title="System Live & Healthy"></span>
            </div>
        </div>

        <!-- Navigation Links -->
        @include('layouts.partials.sidebar-nav-links')

        <!-- User Profile Pill at bottom -->
        @include('layouts.partials.sidebar-user-pill')
    </div>
</aside>
