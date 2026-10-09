<!-- Sidebar Bottom User Profile Card -->
<div class="mt-auto pt-4 border-t border-slate-800">
    <div class="flex items-center justify-between p-2 rounded-xl bg-slate-800/60 border border-slate-800">
        <a href="{{ route('profile.edit') }}" class="flex items-center gap-2.5 min-w-0 flex-1 hover:opacity-90 transition">
            <div class="w-9 h-9 rounded-lg bg-gradient-to-tr from-indigo-500 to-purple-600 text-white font-bold text-xs flex items-center justify-center shrink-0 shadow-sm">
                {{ strtoupper(substr($currentUser->name, 0, 2)) }}
            </div>
            <div class="min-w-0 flex-1 text-left">
                <p class="text-xs font-bold text-white truncate leading-tight">{{ $currentUser->name }}</p>
                <div class="flex items-center gap-1.5 mt-0.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 shrink-0"></span>
                    <p class="text-[10px] font-medium text-slate-400 truncate capitalize">{{ $currentUser->role->name ?? 'User' }}</p>
                </div>
            </div>
        </a>

        <form method="POST" action="{{ route('logout') }}" class="shrink-0 ml-1">
            @csrf
            <button type="submit" 
                    title="লগআউট করুন"
                    class="p-2 text-slate-400 hover:text-rose-400 hover:bg-slate-700/50 rounded-lg transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
            </button>
        </form>
    </div>
</div>
