<x-portal-layout>
    <x-slot name="title">{{ __('Profile & Security') }}</x-slot>

    <div class="max-w-2xl mx-auto space-y-6">
        <!-- Header -->
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">{{ __('Profile & Security Settings') }}</h1>
            <p class="text-xs text-slate-500 mt-0.5">{{ __('Update your account profile details and manage your portal password') }}</p>
        </div>

        <!-- Form Card -->
        <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-2xs space-y-6">
            <form action="{{ route('portal.profile.update') }}" method="POST" class="space-y-5">
                @csrf

                <!-- Basic Profile Details -->
                <div class="space-y-4">
                    <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider pb-2 border-b border-slate-100 flex items-center gap-2">
                        <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        <span>{{ __('Account Information') }}</span>
                    </h3>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">{{ __('Full Name') }} <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full text-xs sm:text-sm rounded-xl border border-slate-200 px-3.5 py-2.5 text-slate-900 focus:ring-2 focus:ring-indigo-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">{{ __('Login Email (Read-only)') }}</label>
                        <input type="email" value="{{ $user->email }}" disabled class="w-full text-xs sm:text-sm rounded-xl border border-slate-200 bg-slate-50 text-slate-500 px-3.5 py-2.5 cursor-not-allowed">
                        <p class="text-[10px] text-slate-400 mt-1">{{ __('To change your official login email, please contact support.') }}</p>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">{{ __('Contact Phone Number') }}</label>
                        <input type="text" name="phone" value="{{ old('phone', $user->phone ?? ($customer->phone ?? '')) }}" class="w-full text-xs sm:text-sm rounded-xl border border-slate-200 px-3.5 py-2.5 text-slate-900 focus:ring-2 focus:ring-indigo-500">
                    </div>
                </div>

                <!-- Password Change Section -->
                <div class="space-y-4 pt-4 border-t border-slate-100">
                    <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider pb-2 border-b border-slate-100 flex items-center gap-2">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        <span>{{ __('Change Password') }}</span>
                    </h3>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">{{ __('Current Password (If changing password)') }}</label>
                        <input type="password" name="current_password" class="w-full text-xs sm:text-sm rounded-xl border border-slate-200 px-3.5 py-2.5 text-slate-900 focus:ring-2 focus:ring-indigo-500">
                        @error('current_password')
                            <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">{{ __('New Password') }}</label>
                            <input type="password" name="new_password" class="w-full text-xs sm:text-sm rounded-xl border border-slate-200 px-3.5 py-2.5 text-slate-900 focus:ring-2 focus:ring-indigo-500">
                            @error('new_password')
                                <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">{{ __('Confirm New Password') }}</label>
                            <input type="password" name="new_password_confirmation" class="w-full text-xs sm:text-sm rounded-xl border border-slate-200 px-3.5 py-2.5 text-slate-900 focus:ring-2 focus:ring-indigo-500">
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="pt-4 flex items-center justify-end">
                    <button type="submit" class="px-6 py-2.5 rounded-xl text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 shadow-md shadow-indigo-500/25 transition">
                        {{ __('Save Changes') }} &rarr;
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-portal-layout>

