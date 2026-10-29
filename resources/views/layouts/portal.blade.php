<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? __('Client Portal') }} — {{ config('app.name', 'Projukti Sheba') }}</title>

    <!-- Google Fonts: Plus Jakarta Sans & Hind Siliguri for Bengali -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&family=Hind+Siliguri:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Styles & Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="font-sans antialiased bg-slate-50 text-slate-800 min-h-screen flex flex-col selection:bg-indigo-500 selection:text-white" x-data="{ mobileMenuOpen: false }">

    <!-- Portal Top Navbar -->
    <header class="bg-slate-900 border-b border-slate-800 text-white sticky top-0 z-40 shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                
                <!-- Left: Brand Logo & Title -->
                <div class="flex items-center gap-6">
                    <a href="{{ route('portal.dashboard') }}" class="flex items-center gap-3 group">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-blue-600 via-indigo-600 to-violet-500 flex items-center justify-center text-white font-black text-xl shadow-lg shadow-indigo-500/25 ring-2 ring-white/10 group-hover:scale-105 transition">
                            PS
                        </div>
                        <div>
                            <div class="text-base font-extrabold text-white tracking-tight leading-none">
                                {{ __('Projukti Sheba') }}
                            </div>
                            <div class="text-[10px] font-bold text-indigo-400 tracking-wider uppercase mt-1">
                                {{ __('Client Self-Service Portal') }}
                            </div>
                        </div>
                    </a>

                    <!-- Desktop Nav Links -->
                    <nav class="hidden md:flex items-center gap-1 pl-4">
                        <a href="{{ route('portal.dashboard') }}" 
                           class="px-3 py-2 rounded-lg text-xs font-bold transition flex items-center gap-1.5 {{ request()->routeIs('portal.dashboard') ? 'bg-indigo-600 text-white shadow-xs' : 'text-slate-300 hover:text-white hover:bg-slate-800/80' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                            <span>{{ __('Dashboard') }}</span>
                        </a>

                        <a href="{{ route('portal.projects.index') }}" 
                           class="px-3 py-2 rounded-lg text-xs font-bold transition flex items-center gap-1.5 {{ request()->routeIs('portal.projects.*') ? 'bg-indigo-600 text-white shadow-xs' : 'text-slate-300 hover:text-white hover:bg-slate-800/80' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            <span>{{ __('My Projects') }}</span>
                        </a>

                        <a href="{{ route('portal.invoices.index') }}" 
                           class="px-3 py-2 rounded-lg text-xs font-bold transition flex items-center gap-1.5 {{ request()->routeIs('portal.invoices.*') ? 'bg-indigo-600 text-white shadow-xs' : 'text-slate-300 hover:text-white hover:bg-slate-800/80' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/></svg>
                            <span>{{ __('My Invoices') }}</span>
                        </a>

                        <a href="{{ route('portal.tickets.index') }}" 
                           class="px-3 py-2 rounded-lg text-xs font-bold transition flex items-center gap-1.5 {{ request()->routeIs('portal.tickets.*') ? 'bg-indigo-600 text-white shadow-xs' : 'text-slate-300 hover:text-white hover:bg-slate-800/80' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                            <span>{{ __('Support Tickets') }}</span>
                        </a>
                    </nav>
                </div>

                <!-- Right: Language & User Menu -->
                <div class="flex items-center gap-3">
                    
                    <!-- Language Switcher Pill -->
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" 
                                type="button" 
                                class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-xs font-semibold text-slate-300 bg-slate-800 hover:bg-slate-700/80 border border-slate-700/60 transition">
                            <span>{{ app()->getLocale() === 'bn' ? '🇧🇩 বাংলা' : '🇬🇧 EN' }}</span>
                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="open" 
                             @click.away="open = false" 
                             x-transition 
                             class="absolute right-0 mt-2 w-32 bg-slate-800 rounded-xl shadow-lg border border-slate-700 py-1 z-50 text-xs font-medium" 
                             x-cloak>
                            <a href="{{ route('locale.switch', 'en') }}" class="flex items-center justify-between px-3 py-1.5 hover:bg-slate-700 text-slate-200 {{ app()->getLocale() === 'en' ? 'font-bold text-indigo-400' : '' }}">
                                <span>🇬🇧 English</span>
                            </a>
                            <a href="{{ route('locale.switch', 'bn') }}" class="flex items-center justify-between px-3 py-1.5 hover:bg-slate-700 text-slate-200 {{ app()->getLocale() === 'bn' ? 'font-bold text-indigo-400' : '' }}">
                                <span>🇧🇩 বাংলা</span>
                            </a>
                        </div>
                    </div>

                    <!-- Client User Profile & Logout -->
                    <div x-data="{ userMenuOpen: false }" class="relative">
                        <button @click="userMenuOpen = !userMenuOpen" 
                                class="flex items-center gap-2 p-1.5 rounded-xl bg-slate-800 hover:bg-slate-700/80 border border-slate-700/70 transition">
                            <div class="w-7 h-7 rounded-lg bg-gradient-to-tr from-emerald-500 to-teal-400 text-white font-black text-xs flex items-center justify-center">
                                {{ strtoupper(substr(Auth::user()->name ?? 'C', 0, 1)) }}
                            </div>
                            <div class="text-left hidden sm:block pr-1">
                                <div class="text-xs font-bold text-white leading-none truncate max-w-[120px]">
                                    {{ Auth::user()->name }}
                                </div>
                                <div class="text-[10px] text-emerald-400 font-medium mt-0.5">
                                    {{ __('Client Account') }}
                                </div>
                            </div>
                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>

                        <div x-show="userMenuOpen" 
                             @click.away="userMenuOpen = false" 
                             x-transition 
                             class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-xl border border-slate-200 py-1.5 z-50 text-xs text-slate-700 font-medium" 
                             x-cloak>
                            <div class="px-4 py-2 border-b border-slate-100">
                                <p class="text-xs font-bold text-slate-900 truncate">{{ Auth::user()->name }}</p>
                                <p class="text-[11px] text-slate-500 truncate">{{ Auth::user()->email }}</p>
                            </div>

                            <a href="{{ route('portal.profile') }}" class="flex items-center gap-2 px-4 py-2 hover:bg-slate-50 text-slate-700">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                <span>{{ __('Profile & Security') }}</span>
                            </a>

                            <!-- Logout Form -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left flex items-center gap-2 px-4 py-2 text-rose-600 hover:bg-rose-50 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                    <span>{{ __('Sign Out') }}</span>
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Mobile Menu Hamburger Button -->
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden p-2 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            <path x-show="mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" x-cloak/>
                        </svg>
                    </button>

                </div>
            </div>
        </div>

        <!-- Mobile Drawer Navigation Links -->
        <div x-show="mobileMenuOpen" class="md:hidden border-t border-slate-800 bg-slate-900 px-4 pt-2 pb-4 space-y-1" x-cloak>
            <a href="{{ route('portal.dashboard') }}" class="block px-3 py-2 rounded-lg text-xs font-bold {{ request()->routeIs('portal.dashboard') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800' }}">
                📊 {{ __('Dashboard') }}
            </a>
            <a href="{{ route('portal.projects.index') }}" class="block px-3 py-2 rounded-lg text-xs font-bold {{ request()->routeIs('portal.projects.*') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800' }}">
                📁 {{ __('My Projects') }}
            </a>
            <a href="{{ route('portal.invoices.index') }}" class="block px-3 py-2 rounded-lg text-xs font-bold {{ request()->routeIs('portal.invoices.*') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800' }}">
                🧾 {{ __('My Invoices') }}
            </a>
            <a href="{{ route('portal.tickets.index') }}" class="block px-3 py-2 rounded-lg text-xs font-bold {{ request()->routeIs('portal.tickets.*') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800' }}">
                🎫 {{ __('Support Tickets') }}
            </a>
            <a href="{{ route('portal.profile') }}" class="block px-3 py-2 rounded-lg text-xs font-bold {{ request()->routeIs('portal.profile') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800' }}">
                ⚙️ {{ __('Profile & Security') }}
            </a>
        </div>
    </header>

    <!-- Main Content Body -->
    <main class="flex-1 py-8 px-4 sm:px-6 lg:px-8 max-w-7xl w-full mx-auto">
        <!-- Flash Alerts -->
        @if(session('success'))
            <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs sm:text-sm font-medium flex items-center gap-2.5 shadow-2xs">
                <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs sm:text-sm font-medium flex items-center gap-2.5 shadow-2xs">
                <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        {{ $slot }}
    </main>

    <!-- Portal Footer -->
    <footer class="bg-white border-t border-slate-200/80 py-6 text-center text-xs text-slate-500 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-3">
            <div>
                &copy; {{ date('Y') }} {{ __('Projukti Sheba') }}. {{ __('All rights reserved.') }}
            </div>
            <div class="flex items-center gap-4 text-xs font-semibold text-slate-600">
                <a href="{{ route('public-support.create') }}" target="_blank" class="hover:text-indigo-600 transition">{{ __('Help Center') }}</a>
                <span>&bull;</span>
                <a href="#" class="hover:text-indigo-600 transition">{{ __('Privacy Policy') }}</a>
                <span>&bull;</span>
                <a href="#" class="hover:text-indigo-600 transition">{{ __('Terms of Service') }}</a>
            </div>
        </div>
    </footer>

</body>
</html>

