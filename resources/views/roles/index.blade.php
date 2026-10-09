<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Roles & Permissions Management') }}
            </h2>
            <span class="text-sm text-gray-500">Configure role-based access for Projukti Sheba CRM</span>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-lg flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <!-- Role List Column -->
                <div class="md:col-span-1 space-y-2">
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider px-2 mb-2">System Roles</h3>
                    @foreach($roles as $index => $r)
                        <div class="p-3 bg-white rounded-lg border border-gray-200 shadow-sm flex justify-between items-center hover:border-indigo-300 transition">
                            <div>
                                <h4 class="font-semibold text-gray-800 text-sm">{{ $r->name }}</h4>
                                <span class="text-xs text-gray-500">{{ $r->users->count() }} active users</span>
                            </div>
                            <span class="px-2 py-0.5 text-xs rounded bg-gray-100 text-gray-600 font-mono">{{ $r->slug }}</span>
                        </div>
                    @endforeach
                </div>

                <!-- Role Permissions Configurations -->
                <div class="md:col-span-3 space-y-6">
                    @foreach($roles as $role)
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                            <form method="POST" action="{{ route('roles.update', $role) }}">
                                @csrf
                                @method('PUT')

                                <div class="flex flex-col sm:flex-row justify-between sm:items-center pb-4 mb-4 border-b border-gray-100 gap-2">
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <h3 class="text-lg font-bold text-gray-900">{{ $role->name }}</h3>
                                            <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-50 text-indigo-700 font-mono">{{ $role->slug }}</span>
                                        </div>
                                        <p class="text-xs text-gray-500 mt-0.5">{{ $role->description }}</p>
                                    </div>

                                    @if($role->slug === 'admin')
                                        <span class="text-xs font-semibold px-3 py-1 rounded bg-amber-50 text-amber-700 border border-amber-200">
                                            Admin has unrestricted full access
                                        </span>
                                    @else
                                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-lg shadow-sm transition">
                                            Save Changes
                                        </button>
                                    @endif
                                </div>

                                <div class="space-y-4">
                                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                        @foreach($permissions as $module => $modulePerms)
                                            <div class="bg-gray-50 rounded-lg p-3 border border-gray-100">
                                                <h4 class="text-xs font-bold text-indigo-700 uppercase tracking-wider mb-2 border-b border-gray-200 pb-1">
                                                    {{ strtoupper($module) }} Module
                                                </h4>
                                                <div class="space-y-1.5">
                                                    @foreach($modulePerms as $perm)
                                                        <label class="flex items-center text-xs text-gray-700 hover:text-gray-900 cursor-pointer">
                                                            <input type="checkbox" 
                                                                   name="permissions[]" 
                                                                   value="{{ $perm->id }}"
                                                                   {{ $role->slug === 'admin' || $role->permissions->contains('id', $perm->id) ? 'checked' : '' }}
                                                                   {{ $role->slug === 'admin' ? 'disabled' : '' }}
                                                                   class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 w-3.5 h-3.5 me-2">
                                                            <span>{{ $perm->name }}</span>
                                                        </label>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </form>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
