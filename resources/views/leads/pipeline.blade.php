<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight flex items-center gap-2">
                    <svg class="w-7 h-7 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/></svg>
                    {{ __('Sales Pipeline (Kanban)') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">{{ __('Stage-by-stage lead conversion and visual sales funnel') }}</p>
            </div>
            <div class="flex items-center gap-2.5">
                <a href="{{ route('leads.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 transition">
                    <svg class="w-4 h-4 me-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                    {{ __('List View') }}
                </a>
                <a href="{{ route('leads.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 shadow-sm transition">
                    <svg class="w-4 h-4 me-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    {{ __('New Lead') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-[1700px] mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Horizontal Kanban Board with Horizontal Scroll -->
            <div class="flex gap-4 overflow-x-auto pb-6">
                @foreach($stages as $stageKey => $stageTitle)
                    @php
                        $stageLeads = $leadsByStage[$stageKey] ?? collect();
                        $borderClass = match($stageKey) {
                            'New' => 'border-t-indigo-500',
                            'Contacted' => 'border-t-blue-500',
                            'Interested' => 'border-t-cyan-500',
                            'Meeting/Demo' => 'border-t-purple-500',
                            'Proposal Sent' => 'border-t-amber-500',
                            'Negotiation' => 'border-t-orange-500',
                            'Won' => 'border-t-emerald-500',
                            'Lost' => 'border-t-rose-500',
                            default => 'border-t-gray-400',
                        };
                    @endphp

                    <div class="w-80 shrink-0 bg-gray-100/80 rounded-2xl border-t-4 {{ $borderClass }} border border-gray-200 flex flex-col max-h-[calc(100vh-210px)] shadow-sm">
                        <!-- Column Header -->
                        <div class="p-3.5 border-b border-gray-200 bg-white rounded-t-xl flex items-center justify-between">
                            <div class="font-bold text-xs text-gray-800 tracking-tight">
                                {{ __($stageKey) }}
                            </div>
                            <span class="w-5 h-5 rounded-full bg-gray-100 text-gray-700 font-bold text-xs flex items-center justify-center">
                                {{ $stageLeads->count() }}
                            </span>
                        </div>

                        <!-- Card Items Container -->
                        <div class="p-2.5 overflow-y-auto space-y-2.5 flex-1">
                            @forelse($stageLeads as $lead)
                                <div class="bg-white p-3.5 rounded-xl border border-gray-200 shadow-xs hover:shadow-md transition space-y-2.5">
                                    <!-- Lead Code & Temp -->
                                    <div class="flex items-center justify-between">
                                        <a href="{{ route('leads.show', $lead) }}" class="font-bold text-xs text-indigo-600 hover:text-indigo-800 hover:underline">
                                            {{ $lead->lead_code }}
                                        </a>
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-semibold border {{ $lead->temperature_badge_class }}">
                                            @if($lead->lead_temperature === 'Hot') 🔥
                                            @elseif($lead->lead_temperature === 'Warm') ⚡
                                            @else ❄️
                                            @endif
                                            {{ $lead->lead_temperature }}
                                        </span>
                                    </div>

                                    <!-- Name & Company -->
                                    <div>
                                        <a href="{{ route('leads.show', $lead) }}" class="font-semibold text-gray-900 text-xs hover:text-indigo-600 block line-clamp-1">
                                            {{ $lead->name }}
                                        </a>
                                        @if($lead->company_name)
                                            <div class="text-[11px] text-gray-500 line-clamp-1">{{ $lead->company_name }}</div>
                                        @endif
                                    </div>

                                    <!-- Service & Budget -->
                                    <div class="bg-gray-50 p-2 rounded-lg text-[11px] flex justify-between items-center">
                                        <span class="text-gray-600 truncate max-w-[120px]">{{ __($lead->interested_service ?? 'Service') }}</span>
                                        <span class="font-bold text-gray-900">
                                            {{ $lead->budget ? __('BDT') . ' ' . number_format($lead->budget) : '-' }}
                                        </span>
                                    </div>

                                    <!-- Contact row -->
                                    <div class="flex items-center justify-between pt-1 text-[11px] text-gray-500 border-t border-gray-100">
                                        <span>{{ $lead->phone }}</span>
                                        <a href="https://wa.me/{{ $lead->normalized_whatsapp ? '88' . $lead->normalized_whatsapp : '88' . $lead->normalized_phone }}" target="_blank" class="text-emerald-600 hover:text-emerald-800 font-semibold" title="WhatsApp">
                                            WA 💬
                                        </a>
                                    </div>

                                    <!-- Quick Move Dropdown -->
                                    <div class="pt-1">
                                        <form method="POST" action="{{ route('leads.update-status', $lead) }}">
                                            @csrf
                                            @method('PATCH')
                                            <select name="lead_status" onchange="this.form.submit()" class="w-full text-[10px] py-1 border-gray-200 rounded-md bg-gray-50 text-gray-600 focus:ring-indigo-500 focus:border-indigo-500">
                                                <option value="" disabled selected>{{ __('Move to stage...') }}</option>
                                                @foreach($stages as $moveKey => $moveTitle)
                                                    @if($moveKey !== $stageKey)
                                                        <option value="{{ $moveKey }}">&rarr; {{ __($moveKey) }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </form>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-6 text-gray-400 text-xs">
                                    {{ __('No leads') }}
                                </div>
                            @endforelse
                        </div>
                    </div>
                @endforeach
            </div>

        </div>
    </div>
</x-app-layout>
