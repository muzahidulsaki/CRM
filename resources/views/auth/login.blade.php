<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ __('Sign In') }} — {{ config('app.name', 'Projukti Sheba CRM') }}</title>

    <!-- Google Fonts: Plus Jakarta Sans & Hind Siliguri for Bengali -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&family=Caveat:wght@600;700&family=Hind+Siliguri:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Styles & Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] { display: none !important; }
        .font-script { font-family: 'Caveat', cursive; }
        .perspective-tablet {
            transform: perspective(1400px) rotateX(10deg) rotateY(-8deg) rotateZ(2deg) scale(0.96);
            transform-origin: top left;
            transition: transform 0.5s ease;
        }
        .perspective-tablet:hover {
            transform: perspective(1400px) rotateX(6deg) rotateY(-4deg) rotateZ(1deg) scale(0.98);
        }
    </style>
</head>
<body class="font-sans antialiased bg-slate-900 text-slate-800 min-h-screen selection:bg-indigo-500 selection:text-white overflow-x-hidden">

    <div class="min-h-screen w-full flex flex-col lg:flex-row">
        
        <!-- ========================================== -->
        <!-- LEFT PANEL: Hero & Dashboard Preview (58%) -->
        <!-- ========================================== -->
        <div class="lg:w-[56%] xl:w-[58%] relative flex flex-col justify-between p-8 sm:p-12 lg:p-16 overflow-hidden text-white" 
             style="background: radial-gradient(circle at 15% 15%, rgba(59, 130, 246, 0.28) 0%, transparent 45%), radial-gradient(circle at 85% 75%, rgba(99, 102, 241, 0.35) 0%, transparent 55%), #0c1322;">
            
            <!-- Background Decorative Glows & Leaves Accent -->
            <div class="absolute -top-32 -left-32 w-96 h-96 bg-blue-600/20 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-32 -right-32 w-96 h-96 bg-indigo-600/25 rounded-full blur-3xl pointer-events-none"></div>
            
            <!-- Left Soft Foliage Silhouette -->
            <div class="absolute -left-12 top-1/4 w-36 h-96 opacity-20 pointer-events-none">
                <svg viewBox="0 0 100 300" fill="currentColor" class="text-emerald-400 w-full h-full">
                    <path d="M 0 50 Q 50 40, 80 10 C 60 50, 40 80, 0 90 Z"/>
                    <path d="M 0 120 Q 60 110, 90 70 C 70 120, 50 150, 0 160 Z"/>
                    <path d="M 0 190 Q 70 180, 100 130 C 80 190, 60 220, 0 230 Z"/>
                </svg>
            </div>

            <!-- Top Left Brand & Top Right Script Badge -->
            <div class="relative z-10 flex items-start justify-between gap-4">
                <!-- Brand Header -->
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-blue-600 via-indigo-600 to-violet-500 flex items-center justify-center text-white font-black text-2xl shadow-xl shadow-indigo-500/30 ring-2 ring-white/10">
                        PS
                    </div>
                    <div>
                        <div class="text-xl sm:text-2xl font-black text-white tracking-tight leading-none">
                            {{ __('Projukti Sheba') }}
                        </div>
                        <div class="text-[10px] sm:text-[11px] font-bold text-slate-400 tracking-[0.25em] uppercase mt-1">
                            CRM & ERP PLATFORM
                        </div>
                    </div>
                </div>

                <!-- Artistic Script Tag "Grow Manage Succeed" -->
                <div class="relative transform rotate-[-8deg] select-none text-right pr-2 pt-1 hidden sm:block">
                    <div class="font-script text-2xl sm:text-3xl text-white/90 font-bold tracking-wide leading-tight drop-shadow-md">
                        Grow<br>Manage<br>Succeed
                    </div>
                    <svg class="w-24 h-3 text-white/80 ml-auto mt-0.5" viewBox="0 0 100 14" fill="none">
                        <path d="M 2 8 C 30 2, 70 3, 98 10" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/>
                    </svg>
                </div>
            </div>

            <!-- Hero Section Content -->
            <div class="relative z-10 mt-10 lg:mt-14 max-w-xl">
                <h1 class="text-3xl sm:text-4xl xl:text-5xl font-black text-white tracking-tight leading-[1.15]">
                    {{ __('All Your Business') }}<br>
                    {{ __('Operations in') }} 
                    <span class="bg-gradient-to-r from-blue-400 via-indigo-300 to-cyan-300 bg-clip-text text-transparent">
                        {{ __('One Place') }}
                    </span>
                </h1>

                <p class="text-sm sm:text-base text-slate-300/90 mt-4 leading-relaxed font-normal">
                    {{ __('A complete CRM & ERP platform to manage leads, projects, team, billing, support and more — designed for modern businesses.') }}
                </p>

                <!-- 4 Feature Badges -->
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mt-8 pt-2">
                    <!-- 1. CRM & Sales -->
                    <div class="flex flex-col items-center text-center group">
                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-indigo-600 to-purple-500 flex items-center justify-center text-white shadow-lg shadow-indigo-600/30 group-hover:scale-105 transition duration-200">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                        <span class="text-xs font-semibold text-slate-200 mt-2.5">{{ __('CRM & Sales') }}</span>
                    </div>

                    <!-- 2. Projects -->
                    <div class="flex flex-col items-center text-center group">
                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-amber-500 to-orange-400 flex items-center justify-center text-white shadow-lg shadow-amber-500/30 group-hover:scale-105 transition duration-200">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <span class="text-xs font-semibold text-slate-200 mt-2.5">{{ __('Projects') }}</span>
                    </div>

                    <!-- 3. Team & Billing -->
                    <div class="flex flex-col items-center text-center group">
                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-emerald-500 to-teal-400 flex items-center justify-center text-white shadow-lg shadow-emerald-500/30 group-hover:scale-105 transition duration-200">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                            </svg>
                        </div>
                        <span class="text-xs font-semibold text-slate-200 mt-2.5">{{ __('Team & Billing') }}</span>
                    </div>

                    <!-- 4. Support Tickets -->
                    <div class="flex flex-col items-center text-center group">
                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-sky-500 to-blue-500 flex items-center justify-center text-white shadow-lg shadow-blue-500/30 group-hover:scale-105 transition duration-200">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                        </div>
                        <span class="text-xs font-semibold text-slate-200 mt-2.5">{{ __('Support Tickets') }}</span>
                    </div>
                </div>
            </div>

            <!-- Lower Angled Dashboard Tablet Mockup -->
            <div class="relative z-10 mt-10 -mb-16 -mr-16 hidden md:block">
                <div class="perspective-tablet w-[110%] max-w-3xl bg-slate-900/90 rounded-2xl p-2.5 shadow-2xl border border-white/15 backdrop-blur-md">
                    <!-- Browser / App Header bar inside tablet -->
                    <div class="bg-slate-800/80 rounded-xl px-4 py-2 flex items-center justify-between border border-slate-700/50 mb-2">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-rose-500 inline-block"></span>
                            <span class="w-2.5 h-2.5 rounded-full bg-amber-500 inline-block"></span>
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 inline-block"></span>
                            <span class="text-[11px] text-slate-400 font-mono ml-2">crm.softps.site/dashboard</span>
                        </div>
                        <div class="flex items-center gap-2 text-[10px] text-slate-300">
                            <span class="px-2 py-0.5 rounded-md bg-indigo-600/40 border border-indigo-400/30">Admin View</span>
                        </div>
                    </div>

                    <!-- Inner Mockup Body -->
                    <div class="bg-slate-50 text-slate-800 rounded-xl p-4 overflow-hidden shadow-inner flex gap-3 text-[11px]">
                        <!-- Mockup Mini Sidebar -->
                        <div class="w-36 bg-slate-900 text-slate-300 rounded-lg p-2.5 hidden sm:flex flex-col justify-between shrink-0">
                            <div>
                                <div class="flex items-center gap-1.5 pb-2 border-b border-slate-800 mb-2">
                                    <div class="w-5 h-5 rounded-md bg-indigo-600 flex items-center justify-center text-white text-[9px] font-black">PS</div>
                                    <span class="text-[10px] font-bold text-white truncate">Projukti Sheba</span>
                                </div>
                                <div class="space-y-1">
                                    <div class="px-2 py-1 rounded bg-indigo-600 text-white font-bold flex items-center gap-1.5 text-[10px]">
                                        <span>📊</span> Dashboard
                                    </div>
                                    <div class="px-2 py-1 rounded hover:bg-slate-800 font-medium flex items-center gap-1.5 text-[10px] text-slate-400">
                                        <span>👥</span> Leads
                                    </div>
                                    <div class="px-2 py-1 rounded hover:bg-slate-800 font-medium flex items-center gap-1.5 text-[10px] text-slate-400">
                                        <span>📁</span> Projects
                                    </div>
                                    <div class="px-2 py-1 rounded hover:bg-slate-800 font-medium flex items-center gap-1.5 text-[10px] text-slate-400">
                                        <span>💳</span> Video Billing
                                    </div>
                                </div>
                            </div>
                            <div class="p-1.5 bg-slate-800 rounded text-[9px] text-slate-400 text-center">
                                v1.0 Live
                            </div>
                        </div>

                        <!-- Mockup Main Content Area -->
                        <div class="flex-1 min-w-0 space-y-3">
                            <div class="flex justify-between items-center">
                                <div>
                                    <h4 class="font-extrabold text-slate-900 text-xs">Management Dashboard</h4>
                                    <p class="text-[9px] text-slate-400">Welcome back, Admin</p>
                                </div>
                                <span class="px-2 py-0.5 rounded-full text-[9px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    ● System Active
                                </span>
                            </div>

                            <!-- 4 Mini Metric Cards -->
                            <div class="grid grid-cols-4 gap-2">
                                <div class="bg-white p-2 rounded-lg border border-slate-200/80 shadow-2xs">
                                    <span class="text-[8px] text-slate-400 font-semibold block">Total Leads</span>
                                    <span class="text-xs font-black text-slate-900">1,240</span>
                                    <span class="text-[8px] text-emerald-600 font-bold block">↑ 12%</span>
                                </div>
                                <div class="bg-white p-2 rounded-lg border border-slate-200/80 shadow-2xs">
                                    <span class="text-[8px] text-slate-400 font-semibold block">Running Projects</span>
                                    <span class="text-xs font-black text-slate-900">28</span>
                                    <span class="text-[8px] text-emerald-600 font-bold block">↑ 6%</span>
                                </div>
                                <div class="bg-white p-2 rounded-lg border border-slate-200/80 shadow-2xs">
                                    <span class="text-[8px] text-slate-400 font-semibold block">Monthly Revenue</span>
                                    <span class="text-xs font-black text-slate-900 truncate block">BDT 2,45,000</span>
                                    <span class="text-[8px] text-emerald-600 font-bold block">↑ 18%</span>
                                </div>
                                <div class="bg-white p-2 rounded-lg border border-slate-200/80 shadow-2xs">
                                    <span class="text-[8px] text-slate-400 font-semibold block">Open Tickets</span>
                                    <span class="text-xs font-black text-slate-900">12</span>
                                    <span class="text-[8px] text-rose-500 font-bold block">↓ -5%</span>
                                </div>
                            </div>

                            <!-- Mini Chart & Status Row -->
                            <div class="grid grid-cols-5 gap-2">
                                <div class="col-span-3 bg-white p-2.5 rounded-lg border border-slate-200/80 shadow-2xs">
                                    <div class="flex justify-between text-[9px] font-bold text-slate-700 mb-1">
                                        <span>Lead Growth Overview</span>
                                        <span class="text-indigo-600">Last 6 Months</span>
                                    </div>
                                    <svg viewBox="0 0 100 25" class="w-full h-8" fill="none">
                                        <path d="M 0 20 Q 20 8, 40 15 T 75 6 T 100 12 L 100 25 L 0 25 Z" fill="rgba(79, 70, 229, 0.12)"/>
                                        <path d="M 0 20 Q 20 8, 40 15 T 75 6 T 100 12" stroke="#4f46e5" stroke-width="2" stroke-linecap="round"/>
                                    </svg>
                                </div>
                                <div class="col-span-2 bg-white p-2.5 rounded-lg border border-slate-200/80 shadow-2xs flex items-center justify-between">
                                    <div>
                                        <span class="text-[8px] text-slate-400 block font-bold">Project Status</span>
                                        <span class="text-xs font-black text-slate-800">28 Active</span>
                                        <span class="text-[8px] text-emerald-600 block">● 92% on time</span>
                                    </div>
                                    <div class="w-8 h-8 rounded-full border-4 border-indigo-500 border-t-amber-400 shrink-0"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Left Bottom Spacer -->
            <div class="pt-8 block md:hidden"></div>
        </div>

        <!-- ========================================== -->
        <!-- RIGHT PANEL: Login Form (42%)              -->
        <!-- ========================================== -->
        <div class="lg:w-[44%] xl:w-[42%] bg-white flex flex-col justify-between p-6 sm:p-10 lg:p-12 xl:p-16 min-h-screen relative shadow-2xl">
            
            <!-- Top Right: Language Switcher Dropdown -->
            <div class="flex justify-end w-full relative z-20">
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" 
                            type="button" 
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold text-slate-700 bg-slate-50 hover:bg-slate-100 border border-slate-200/80 transition shadow-2xs">
                        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>{{ app()->getLocale() === 'bn' ? 'বাংলা' : 'English' }}</span>
                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <!-- Language Menu -->
                    <div x-show="open" 
                         @click.away="open = false" 
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         class="absolute right-0 mt-2 w-36 bg-white rounded-xl shadow-lg border border-slate-100 py-1 z-50 text-xs font-medium" 
                         x-cloak>
                        <a href="{{ route('locale.switch', 'en') }}" class="flex items-center justify-between px-3.5 py-2 hover:bg-slate-50 text-slate-700 {{ app()->getLocale() === 'en' ? 'font-bold text-indigo-600 bg-indigo-50/50' : '' }}">
                            <span>🇬🇧 English</span>
                            @if(app()->getLocale() === 'en')
                                <svg class="w-3.5 h-3.5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            @endif
                        </a>
                        <a href="{{ route('locale.switch', 'bn') }}" class="flex items-center justify-between px-3.5 py-2 hover:bg-slate-50 text-slate-700 {{ app()->getLocale() === 'bn' ? 'font-bold text-indigo-600 bg-indigo-50/50' : '' }}">
                            <span>🇧🇩 বাংলা</span>
                            @if(app()->getLocale() === 'bn')
                                <svg class="w-3.5 h-3.5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            @endif
                        </a>
                    </div>
                </div>
            </div>

            <!-- Form Wrapper (Vertically Centered) -->
            <div class="my-auto py-8 w-full max-w-sm mx-auto">
                
                <!-- Central Logo Badge & Brand -->
                <div class="text-center">
                    <div class="inline-flex w-14 h-14 rounded-2xl bg-gradient-to-tr from-blue-600 via-indigo-600 to-violet-500 items-center justify-center text-white font-black text-2xl shadow-xl shadow-indigo-500/25 ring-4 ring-indigo-50">
                        PS
                    </div>
                    <h2 class="text-2xl font-black text-slate-900 tracking-tight mt-3">
                        {{ __('Projukti Sheba') }}
                    </h2>
                    <p class="text-[10px] font-bold text-slate-400 tracking-[0.25em] uppercase mt-0.5">
                        CRM & ERP PLATFORM
                    </p>
                </div>

                <!-- Form Heading -->
                <div class="text-center mt-6">
                    <h3 class="text-2xl font-bold text-slate-900 tracking-tight">
                        {{ __('Welcome Back') }}
                    </h3>
                    <p class="text-xs text-slate-500 mt-1">
                        {{ __('Sign in to access your account') }}
                    </p>
                </div>

                <!-- Session Status / Errors -->
                @if (session('status'))
                    <div class="mt-4 p-3 bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs rounded-xl flex items-center gap-2">
                        <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>{{ session('status') }}</span>
                    </div>
                @endif

                @if (isset($errors) && $errors->any())
                    <div class="mt-4 p-3 bg-rose-50 border border-rose-200 text-rose-700 text-xs rounded-xl space-y-1">
                        @foreach ($errors->all() as $error)
                            <div class="flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-rose-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span>{{ $error }}</span>
                            </div>
                        @endforeach
                    </div>
                @endif

                <!-- Login Form -->
                <form method="POST" action="{{ route('login') }}" class="mt-6 space-y-4">
                    @csrf

                    <!-- Email Field -->
                    <div>
                        <label for="email" class="block text-xs font-bold text-slate-700 mb-1.5">
                            {{ __('Email Address') }}
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206"/>
                                </svg>
                            </div>
                            <input id="email" 
                                   type="email" 
                                   name="email" 
                                   value="{{ old('email') }}" 
                                   required 
                                   autofocus 
                                   autocomplete="username"
                                   placeholder="Enter your email" 
                                   class="w-full pl-10 pr-4 py-3 rounded-xl border border-slate-200 text-sm text-slate-900 placeholder:text-slate-400 focus:border-indigo-600 focus:ring-4 focus:ring-indigo-50 outline-none transition duration-150">
                        </div>
                    </div>

                    <!-- Password Field with Show/Hide Toggle -->
                    <div x-data="{ show: false }">
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="password" class="block text-xs font-bold text-slate-700">
                                {{ __('Password') }}
                            </label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 transition">
                                    {{ __('Forgot Password?') }}
                                </a>
                            @endif
                        </div>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                            </div>
                            <input id="password" 
                                   :type="show ? 'text' : 'password'" 
                                   name="password" 
                                   required 
                                   autocomplete="current-password"
                                   placeholder="Enter your password" 
                                   class="w-full pl-10 pr-10 py-3 rounded-xl border border-slate-200 text-sm text-slate-900 placeholder:text-slate-400 focus:border-indigo-600 focus:ring-4 focus:ring-indigo-50 outline-none transition duration-150">
                            
                            <!-- Toggle Button -->
                            <button type="button" 
                                    @click="show = !show" 
                                    class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 transition" 
                                    title="Toggle Password Visibility">
                                <svg x-show="!show" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                <svg x-show="show" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-cloak>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Remember Me Checkbox -->
                    <div class="flex items-center pt-1">
                        <label for="remember_me" class="inline-flex items-center gap-2 cursor-pointer select-none">
                            <input id="remember_me" 
                                   type="checkbox" 
                                   name="remember" 
                                   checked 
                                   class="w-4 h-4 rounded text-indigo-600 border-slate-300 focus:ring-indigo-500 transition">
                            <span class="text-xs font-semibold text-slate-700">{{ __('Keep me signed in') }}</span>
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-2">
                        <button type="submit" 
                                class="w-full py-3.5 px-6 rounded-xl text-white font-bold text-sm bg-gradient-to-r from-indigo-600 via-indigo-600 to-blue-600 hover:from-indigo-700 hover:to-blue-700 shadow-md shadow-indigo-500/25 hover:shadow-lg hover:shadow-indigo-500/40 transition duration-200 flex items-center justify-center gap-2">
                            <span>{{ __('Sign In') }}</span>
                            <span class="text-base font-bold">&rarr;</span>
                        </button>
                    </div>

                    <!-- Or Continue With Divider -->
                    <div class="relative my-6 text-center">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-slate-200"></div>
                        </div>
                        <span class="relative bg-white px-3 text-[11px] font-semibold text-slate-400 uppercase tracking-wider">
                            {{ __('Or continue with') }}
                        </span>
                    </div>

                    <!-- SSO Buttons: Google & Microsoft -->
                    <div class="grid grid-cols-2 gap-3">
                        <!-- Google -->
                        <button type="button" 
                                onclick="alert('{{ __('Google SSO will be available with your G-Suite account.') }}')" 
                                class="flex items-center justify-center gap-2 py-2.5 px-4 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-xs font-bold text-slate-700 shadow-2xs transition">
                            <svg class="w-4 h-4" viewBox="0 0 24 24">
                                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
                            </svg>
                            <span>Google</span>
                        </button>

                        <!-- Microsoft -->
                        <button type="button" 
                                onclick="alert('{{ __('Microsoft 365 SSO integration will be activated.') }}')" 
                                class="flex items-center justify-center gap-2 py-2.5 px-4 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-xs font-bold text-slate-700 shadow-2xs transition">
                            <svg class="w-4 h-4" viewBox="0 0 21 21">
                                <rect x="1" y="1" width="9" height="9" fill="#f25022"/>
                                <rect x="11" y="1" width="9" height="9" fill="#7fba00"/>
                                <rect x="1" y="11" width="9" height="9" fill="#00a4ef"/>
                                <rect x="11" y="11" width="9" height="9" fill="#ffb900"/>
                            </svg>
                            <span>Microsoft</span>
                        </button>
                    </div>

                    <!-- Don't have an account -->
                    <div class="pt-4 text-center">
                        <p class="text-xs text-slate-500">
                            {{ __("Don't have an account?") }} 
                            <a href="mailto:support@projuktisheba.com?subject=CRM%20Account%20Request" class="font-bold text-indigo-600 hover:text-indigo-800 transition">
                                {{ __('Contact Administrator') }}
                            </a>
                        </p>
                    </div>
                </form>
            </div>

            <!-- Bottom Footer -->
            <div class="pt-6 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-3 text-[11px] text-slate-400">
                <div>
                    &copy; {{ date('Y') }} {{ __('Projukti Sheba. All rights reserved.') }}
                </div>
                <div class="flex items-center gap-3">
                    <a href="#" class="hover:text-slate-600 transition">{{ __('Help') }}</a>
                    <span>&bull;</span>
                    <a href="#" class="hover:text-slate-600 transition">{{ __('Privacy') }}</a>
                    <span>&bull;</span>
                    <a href="#" class="hover:text-slate-600 transition">{{ __('Terms') }}</a>
                </div>
            </div>

        </div>

    </div>

</body>
</html>
