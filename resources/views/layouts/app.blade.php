<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Projukti Sheba CRM') }}</title>

        <!-- Fonts (Plus Jakarta Sans & Hind Siliguri for Bengali) -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Hind+Siliguri:wght@400;500;600;700&display=swap" rel="stylesheet">

        <style>
            [x-cloak] { display: none !important; }
        </style>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased text-slate-800 bg-slate-50 min-h-screen" x-data="{ sidebarOpen: false }">
        <div class="min-h-screen flex">
            <!-- Sidebar Navigation (Desktop Fixed + Mobile Off-canvas Drawer) -->
            @include('layouts.navigation')

            <!-- Main Shell alongside Sidebar -->
            <div class="flex-1 flex flex-col min-w-0 lg:pl-72 min-h-screen">
                <!-- Sticky Top Header -->
                @include('layouts.partials.topbar')

                <!-- Page Header (if any) -->
                @isset($header)
                    <div class="bg-white border-b border-slate-200/80 shadow-2xs">
                        <div class="px-4 sm:px-6 lg:px-8 py-4 sm:py-5">
                            {{ $header }}
                        </div>
                    </div>
                @endisset

                <!-- Flash Alerts -->
                @include('layouts.partials.flash-messages')

                <!-- Page Content -->
                <main class="flex-1 py-6 sm:py-8">
                    <div class="px-4 sm:px-6 lg:px-8">
                        {{ $slot }}
                    </div>
                </main>

                <!-- Footer -->
                <footer class="mt-auto py-4 px-4 sm:px-6 lg:px-8 border-t border-slate-200/60 bg-white/50 text-center text-xs text-slate-500 flex flex-col sm:flex-row justify-between items-center gap-2">
                    <div>&copy; {{ date('Y') }} <strong>{{ __('Projukti Sheba') }}</strong> — {{ __('Centralized CRM & ERP Business Platform') }}</div>
                    <div class="flex items-center gap-2 sm:gap-3 text-xs text-slate-500 flex-wrap justify-center">
                        <span class="inline-flex items-center gap-1.5 text-[11px] text-emerald-600 font-semibold">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                            {{ __('System Online & Secure') }} (v1.0)
                        </span>
                        <span class="text-slate-300">|</span>
                        <a href="#" class="hover:text-slate-800 transition">{{ __('Help') }}</a>
                        <span class="text-slate-300">|</span>
                        <a href="{{ route('public-support.create') }}" target="_blank" class="hover:text-slate-800 transition">{{ __('Support') }}</a>
                        <span class="text-slate-300">|</span>
                        <a href="#" class="hover:text-slate-800 transition">{{ __('Privacy') }}</a>
                        <span class="text-slate-300">|</span>
                        <a href="#" class="hover:text-slate-800 transition">{{ __('Terms') }}</a>
                    </div>
                </footer>
            </div>
        </div>
    </body>
</html>
