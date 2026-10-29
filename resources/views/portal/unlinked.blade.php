<x-portal-layout>
    <x-slot name="title">{{ __('Account Linking Required') }}</x-slot>

    <div class="max-w-xl mx-auto py-12 text-center space-y-6">
        <div class="w-16 h-16 rounded-3xl bg-amber-50 text-amber-600 flex items-center justify-center text-3xl mx-auto shadow-sm">
            🔗
        </div>
        <div class="space-y-2">
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">{{ __('Client Profile Not Linked') }}</h1>
            <p class="text-xs sm:text-sm text-slate-500 leading-relaxed max-w-md mx-auto">
                {{ __('Your login account is active, but our system has not yet linked a company or customer profile to this email address. Please contact our administrative team to complete the link.') }}
            </p>
        </div>

        <div class="p-4 bg-white rounded-2xl border border-slate-200 shadow-2xs text-xs space-y-1 inline-block text-left">
            <div><span class="text-slate-400 font-medium">{{ __('Logged in as:') }}</span> <strong class="text-slate-800">{{ Auth::user()->email }}</strong></div>
            <div><span class="text-slate-400 font-medium">{{ __('Role:') }}</span> <span class="font-bold text-indigo-600">Client Portal</span></div>
        </div>

        <div class="pt-4 flex items-center justify-center gap-3">
            <a href="mailto:support@projuktisheba.com?subject=Link%20Client%20Profile%20for%20{{ urlencode(Auth::user()->email) }}" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl shadow-xs transition">
                {{ __('Contact Support') }} &rarr;
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="px-4 py-2.5 border border-slate-200 text-slate-600 hover:bg-slate-50 font-bold text-xs rounded-xl transition">
                    {{ __('Sign Out') }}
                </button>
            </form>
        </div>
    </div>
</x-portal-layout>

