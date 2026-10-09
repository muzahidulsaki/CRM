<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('System Settings & Configurations') }}
            </h2>
            <span class="text-sm text-gray-500">Configure business defaults, currencies, and operational parameters</span>
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

            <form method="POST" action="{{ route('settings.update') }}">
                @csrf

                <div class="space-y-6">
                    @foreach($settings as $group => $groupSettings)
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                                <h3 class="text-base font-bold text-gray-900 uppercase tracking-wide">
                                    {{ ucfirst($group) }} Configuration
                                </h3>
                                <p class="text-xs text-gray-500">Settings and defaults for {{ $group }} module</p>
                            </div>

                            <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-6">
                                @foreach($groupSettings as $setting)
                                    <div>
                                        <x-input-label for="{{ $setting->key }}" :value="ucwords(str_replace('_', ' ', $setting->key))" />
                                        
                                        @if($setting->type === 'json')
                                            @php
                                                $decoded = json_decode($setting->value, true);
                                                $textValue = is_array($decoded) ? implode("\n", $decoded) : $setting->value;
                                            @endphp
                                            <textarea id="{{ $setting->key }}" 
                                                      name="{{ $setting->key }}" 
                                                      rows="4" 
                                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm font-mono"
                                                      placeholder="One item per line">{{ $textValue }}</textarea>
                                            <span class="text-xs text-gray-400">Enter each item on a separate line</span>
                                        @elseif($setting->type === 'decimal' || $setting->type === 'integer')
                                            <x-text-input id="{{ $setting->key }}" 
                                                          name="{{ $setting->key }}" 
                                                          type="number" 
                                                          step="any" 
                                                          class="mt-1 block w-full" 
                                                          value="{{ $setting->value }}" />
                                        @else
                                            <x-text-input id="{{ $setting->key }}" 
                                                          name="{{ $setting->key }}" 
                                                          type="text" 
                                                          class="mt-1 block w-full" 
                                                          value="{{ $setting->value }}" />
                                        @endif

                                        @if($setting->description)
                                            <p class="text-xs text-gray-500 mt-1">{{ $setting->description }}</p>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach

                    <div class="flex justify-end">
                        <button type="submit" class="inline-flex items-center px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg shadow-sm transition">
                            Save System Settings
                        </button>
                    </div>
                </div>
            </form>

        </div>
    </div>
</x-app-layout>
