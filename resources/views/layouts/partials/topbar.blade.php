@php
    $unreadNotificationCount = \App\Models\CrmNotification::where('is_read', false)->count();
    $recentNotifications = \App\Models\CrmNotification::where('is_read', false)->latest()->take(5)->get();
    $currentUser = Auth::user();
    $currentLocale = app()->getLocale();
@endphp

<!-- Sticky Top Header Bar -->
<header class="sticky top-0 z-40 flex h-16 shrink-0 items-center justify-between border-b border-slate-200/80 bg-white/95 backdrop-blur px-4 sm:px-6 lg:px-8 shadow-xs">
    <!-- Left: Mobile Menu Toggle & Title -->
    <div class="flex items-center gap-3 sm:gap-4">
        <button type="button" 
                @click="sidebarOpen = true" 
                class="-m-2.5 p-2.5 text-slate-700 hover:text-indigo-600 hover:bg-slate-100 rounded-xl lg:hidden transition"
                aria-label="Open sidebar">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
            </svg>
        </button>

        <!-- Company / Workspace Indicator -->
        <div class="flex items-center gap-2 text-xs font-medium text-slate-500">
            <a href="{{ route('dashboard') }}" class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-100/80 hover:bg-indigo-100 transition">
                {{ __('Projukti Sheba') }}
            </a>
            <span class="text-slate-300">/</span>
            <span class="text-slate-800 font-semibold truncate max-w-[150px] sm:max-w-none">
                {{ __($title ?? 'CRM & Business Management') }}
            </span>
        </div>
    </div>

    <!-- Right: Language Switcher, Quick Actions, Notifications & User Dropdown -->
    <div class="flex items-center gap-2 sm:gap-3">
        
        <!-- Language Switcher Dropdown (BN / EN) -->
        <div class="relative" x-data="{ openLang: false }" @click.outside="openLang = false">
            <button @click="openLang = !openLang" 
                    type="button" 
                    class="inline-flex items-center gap-1.5 px-2.5 py-1.5 bg-slate-100/80 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition border border-slate-200/80 focus:outline-none"
                    title="{{ __('Language') }}">
                @if($currentLocale === 'en')
                    <span class="text-xs font-bold text-slate-500">GB</span>
                    <span class="hidden sm:inline text-xs font-bold text-slate-800">EN</span>
                @else
                    <span class="text-xs font-bold text-slate-500">BD</span>
                    <span class="hidden sm:inline text-xs font-bold text-slate-800">বাংলা</span>
                @endif
                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </button>

            <!-- Language Dropdown Menu -->
            <div x-show="openLang" 
                 x-cloak
                 x-transition:enter="transition ease-out duration-100"
                 x-transition:enter-start="transform opacity-0 scale-95"
                 x-transition:enter-end="transform opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-75"
                 x-transition:leave-start="transform opacity-100 scale-100"
                 x-transition:leave-end="transform opacity-0 scale-95"
                 class="absolute right-0 z-50 mt-2 w-36 origin-top-right rounded-2xl bg-white p-1.5 shadow-xl ring-1 ring-slate-900/10 focus:outline-none">
                
                <div class="px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider text-slate-400">{{ __('Language') }}</div>
                
                <a href="{{ route('locale.switch', 'bn') }}" 
                   class="flex items-center justify-between px-2.5 py-1.5 text-xs font-semibold rounded-xl transition {{ $currentLocale === 'bn' ? 'bg-indigo-50 text-indigo-700 font-bold' : 'text-slate-700 hover:bg-slate-50' }}">
                    <div class="flex items-center gap-2">
                        <span class="text-base leading-none">🇧🇩</span>
                        <span>বাংলা</span>
                    </div>
                    @if($currentLocale === 'bn')
                        <svg class="w-3.5 h-3.5 text-indigo-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                    @endif
                </a>

                <a href="{{ route('locale.switch', 'en') }}" 
                   class="flex items-center justify-between px-2.5 py-1.5 text-xs font-semibold rounded-xl transition {{ $currentLocale === 'en' ? 'bg-indigo-50 text-indigo-700 font-bold' : 'text-slate-700 hover:bg-slate-50' }}">
                    <div class="flex items-center gap-2">
                        <span class="text-base leading-none">🇬🇧</span>
                        <span>English</span>
                    </div>
                    @if($currentLocale === 'en')
                        <svg class="w-3.5 h-3.5 text-indigo-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                    @endif
                </a>
            </div>
        </div>

        <!-- Fast "+ Create" Action Dropdown -->
        <div class="relative" x-data="{ open: false }" @click.outside="open = false">
            <button @click="open = !open" 
                    type="button" 
                    class="inline-flex items-center gap-1.5 px-3 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs sm:text-sm font-semibold rounded-xl shadow-xs transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                <span class="hidden sm:inline">{{ __('Create New') }}</span>
                <svg class="w-3.5 h-3.5 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </button>

            <!-- Dropdown Menu -->
            <div x-show="open" 
                 x-cloak
                 x-transition:enter="transition ease-out duration-100"
                 x-transition:enter-start="transform opacity-0 scale-95"
                 x-transition:enter-end="transform opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-75"
                 x-transition:leave-start="transform opacity-100 scale-100"
                 x-transition:leave-end="transform opacity-0 scale-95"
                 class="absolute right-0 z-50 mt-2 w-56 origin-top-right rounded-2xl bg-white p-1.5 shadow-xl ring-1 ring-slate-900/10 focus:outline-none">
                
                <div class="px-3 py-1.5 text-[10px] font-bold uppercase tracking-wider text-slate-400">{{ __('Sales & Clients') }}</div>
                <a href="{{ route('leads.create') }}" class="flex items-center gap-2 px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-indigo-50 hover:text-indigo-700 rounded-xl transition">
                    <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                    <span>{{ __('New Lead Entry') }}</span>
                </a>
                <a href="{{ route('quotations.create') }}" class="flex items-center gap-2 px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-indigo-50 hover:text-indigo-700 rounded-xl transition">
                    <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span>{{ __('Create Quotation') }}</span>
                </a>
                <a href="{{ route('orders.create') }}" class="flex items-center gap-2 px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-indigo-50 hover:text-indigo-700 rounded-xl transition">
                    <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    <span>{{ __('Confirm New Order') }}</span>
                </a>

                <div class="my-1 border-t border-slate-100"></div>
                <div class="px-3 py-1.5 text-[10px] font-bold uppercase tracking-wider text-slate-400">{{ __('Billing & Service') }}</div>
                <a href="{{ route('invoices.create') }}" class="flex items-center gap-2 px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-indigo-50 hover:text-indigo-700 rounded-xl transition">
                    <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>{{ __('Generate Invoice') }}</span>
                </a>
                <a href="{{ route('tasks.create') }}" class="flex items-center gap-2 px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-indigo-50 hover:text-indigo-700 rounded-xl transition">
                    <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    <span>{{ __('Assign New Task') }}</span>
                </a>
                <a href="{{ route('support-tickets.create') }}" class="flex items-center gap-2 px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-indigo-50 hover:text-indigo-700 rounded-xl transition">
                    <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    <span>{{ __('Open Support Ticket') }}</span>
                </a>
            </div>
        </div>

        <!-- Notification Bell Dropdown -->
        <div class="relative" x-data="{ open: false }" @click.outside="open = false">
            <button @click="open = !open" 
                    type="button" 
                    class="relative p-2 rounded-xl text-slate-500 hover:text-indigo-600 hover:bg-slate-100 transition focus:outline-none"
                    title="{{ __('Notifications') }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                @if($unreadNotificationCount > 0)
                    <span class="absolute -top-0.5 -right-0.5 flex h-4 min-w-[16px] px-1 items-center justify-center rounded-full bg-rose-500 text-[10px] font-black text-white ring-2 ring-white animate-pulse">
                        {{ $unreadNotificationCount > 99 ? '99+' : $unreadNotificationCount }}
                    </span>
                @endif
            </button>

            <!-- Notification Drawer -->
            <div x-show="open" 
                 x-cloak
                 x-transition
                 class="absolute right-0 z-50 mt-2 w-80 sm:w-96 origin-top-right rounded-2xl bg-white shadow-xl ring-1 ring-slate-900/10 focus:outline-none overflow-hidden">
                <div class="px-4 py-3 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="font-bold text-xs text-slate-900">{{ __('Notification Inbox') }}</span>
                        @if($unreadNotificationCount > 0)
                            <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-rose-100 text-rose-700">{{ $unreadNotificationCount }} {{ __('New') }}</span>
                        @endif
                    </div>
                    <a href="{{ route('notifications.index') }}" class="text-[11px] font-semibold text-indigo-600 hover:text-indigo-800">{{ __('View All') }} &rarr;</a>
                </div>

                <div class="divide-y divide-slate-100 max-h-80 overflow-y-auto custom-scrollbar">
                    @forelse($recentNotifications as $notif)
                        <div class="p-3 hover:bg-slate-50 transition flex items-start gap-2.5">
                            <span class="w-2 h-2 mt-1.5 rounded-full shrink-0 {{ $notif->priority === 'urgent' ? 'bg-rose-500' : 'bg-indigo-500' }}"></span>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-semibold text-slate-900 truncate">{{ $notif->title }}</p>
                                <p class="text-[11px] text-slate-500 line-clamp-2 mt-0.5">{{ $notif->message }}</p>
                                <span class="text-[10px] text-slate-400 mt-1 block">{{ $notif->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="py-8 text-center text-xs text-slate-400">
                            {{ __('No new notifications') }}
                        </div>
                    @endforelse
                </div>

                @if($unreadNotificationCount > 0)
                    <form action="{{ route('notifications.read-all') }}" method="POST" class="border-t border-slate-100">
                        @csrf
                        <button type="submit" class="w-full py-2 bg-slate-50 hover:bg-slate-100 text-center text-[11px] font-semibold text-slate-600 transition">
                            {{ __('Mark all as read') }}
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <div class="h-6 w-px bg-slate-200"></div>

        <!-- User Profile Dropdown -->
        <div class="relative" x-data="{ open: false }" @click.outside="open = false">
            <button @click="open = !open" 
                    type="button" 
                    class="flex items-center gap-2 p-1.5 rounded-xl hover:bg-slate-100 transition focus:outline-none">
                <div class="w-8 h-8 rounded-lg bg-gradient-to-tr from-indigo-600 to-purple-600 text-white font-bold text-xs flex items-center justify-center shadow-xs">
                    {{ strtoupper(substr($currentUser->name, 0, 2)) }}
                </div>
                <div class="hidden md:block text-left">
                    <div class="text-xs font-bold text-slate-800 leading-tight truncate max-w-[120px]">{{ $currentUser->name }}</div>
                    <div class="text-[10px] font-medium text-slate-400 capitalize">{{ $currentUser->role->name ?? 'Team Member' }}</div>
                </div>
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </button>

            <!-- Dropdown -->
            <div x-show="open" 
                 x-cloak
                 x-transition
                 class="absolute right-0 z-50 mt-2 w-56 origin-top-right rounded-2xl bg-white p-1.5 shadow-xl ring-1 ring-slate-900/10 focus:outline-none">
                <div class="px-3 py-2 border-b border-slate-100">
                    <p class="text-xs font-bold text-slate-900">{{ $currentUser->name }}</p>
                    <p class="text-[11px] text-slate-500 truncate">{{ $currentUser->email }}</p>
                    <span class="inline-flex mt-1 px-2 py-0.5 text-[10px] font-bold rounded-md bg-indigo-50 text-indigo-700 border border-indigo-100">
                        {{ $currentUser->role->name ?? 'User' }}
                    </span>
                </div>

                <div class="py-1">
                    <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-3 py-2 text-xs font-medium text-slate-700 hover:bg-slate-50 hover:text-indigo-600 rounded-xl transition">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        <span>{{ __('Edit Profile') }}</span>
                    </a>
                    @if($currentUser->isAdmin())
                        <a href="{{ route('settings.index') }}" class="flex items-center gap-2 px-3 py-2 text-xs font-medium text-slate-700 hover:bg-slate-50 hover:text-indigo-600 rounded-xl transition">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <span>{{ __('System Settings') }}</span>
                        </a>
                    @endif
                </div>

                <div class="border-t border-slate-100 pt-1">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-2 px-3 py-2 text-xs font-semibold text-rose-600 hover:bg-rose-50 rounded-xl transition text-left">
                            <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                            <span>{{ __('Logout') }}</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</header>
