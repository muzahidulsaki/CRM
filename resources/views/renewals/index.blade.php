<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-slate-800 leading-tight">
                    Service Renewals & Subscriptions
                </h2>
                <p class="text-xs text-slate-500 mt-1">Track recurring domain, hosting, software licenses, and automated payment alerts.</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('renewals.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-lg shadow-sm transition">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    New Renewal Tracker
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="p-4 bg-emerald-50 border border-emerald-300 rounded-xl text-emerald-800 text-sm flex items-center">
                    <svg class="w-5 h-5 mr-3 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <!-- Renewal Alert Cards (SRS Section 18.1) -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <a href="{{ route('renewals.index', ['alert' => 'overdue']) }}" class="p-5 rounded-2xl border transition {{ $currentAlert === 'overdue' ? 'bg-rose-50 border-rose-400 ring-2 ring-rose-400' : 'bg-white border-slate-200 shadow-xs hover:border-rose-300' }}">
                    <div class="flex items-center justify-between text-rose-600 mb-1">
                        <span class="text-xs font-bold uppercase tracking-wider">Overdue Alerts</span>
                        <span class="w-2.5 h-2.5 rounded-full bg-rose-500 animate-ping"></span>
                    </div>
                    <div class="text-2xl font-black text-rose-600">{{ $metrics['overdue'] }}</div>
                    <p class="text-[11px] text-slate-400 mt-1">Requires immediate client call</p>
                </a>

                <a href="{{ route('renewals.index', ['alert' => 'due_today']) }}" class="p-5 rounded-2xl border transition {{ $currentAlert === 'due_today' ? 'bg-red-50 border-red-400 ring-2 ring-red-400' : 'bg-white border-slate-200 shadow-xs hover:border-red-300' }}">
                    <div class="flex items-center justify-between text-red-600 mb-1">
                        <span class="text-xs font-bold uppercase tracking-wider">Due Today</span>
                        <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div class="text-2xl font-black text-red-600">{{ $metrics['due_today'] }}</div>
                    <p class="text-[11px] text-slate-400 mt-1">Renew today to avoid outage</p>
                </a>

                <a href="{{ route('renewals.index', ['alert' => 'upcoming']) }}" class="p-5 rounded-2xl border transition {{ $currentAlert === 'upcoming' ? 'bg-amber-50 border-amber-400 ring-2 ring-amber-400' : 'bg-white border-slate-200 shadow-xs hover:border-amber-300' }}">
                    <div class="flex items-center justify-between text-amber-600 mb-1">
                        <span class="text-xs font-bold uppercase tracking-wider">Upcoming (15 Days)</span>
                        <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                    </div>
                    <div class="text-2xl font-black text-amber-600">{{ $metrics['upcoming'] }}</div>
                    <p class="text-[11px] text-slate-400 mt-1">Send invoice reminder</p>
                </a>

                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs">
                    <div class="flex items-center justify-between text-slate-500 mb-1">
                        <span class="text-xs font-semibold">Annual ARR Value</span>
                        <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div class="text-2xl font-black text-slate-900">{{ __('BDT') }} {{ number_format($metrics['total_annual_value']) }}</div>
                    <p class="text-[11px] text-slate-400 mt-1">{{ $metrics['total_active'] }} Total subscriptions</p>
                </div>
            </div>

            <!-- Filter Toolbar -->
            <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-xs">
                <form action="{{ route('renewals.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div>
                        <input type="text" name="search" value="{{ request('search') }}"
                            class="w-full text-xs px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none"
                            placeholder="Search Service, Domain, Client...">
                    </div>

                    <div>
                        <select name="category" class="w-full text-xs px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                            <option value="">-- All Categories --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat }}" {{ request('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex items-center space-x-2">
                        <button type="submit" class="flex-1 py-2 bg-slate-800 hover:bg-slate-900 text-white text-xs font-bold rounded-lg transition">
                            Filter
                        </button>
                        @if(request()->anyFilled(['search', 'category', 'alert']))
                            <a href="{{ route('renewals.index') }}" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-bold rounded-lg transition">
                                Reset
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Renewals Table -->
            <div class="bg-white rounded-xl border border-slate-200 shadow-xs overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 text-left text-xs">
                        <thead class="bg-slate-50 text-slate-500 font-bold uppercase tracking-wider">
                            <tr>
                                <th class="px-5 py-3">Renewal ID</th>
                                <th class="px-5 py-3">Service & Category</th>
                                <th class="px-5 py-3">Client</th>
                                <th class="px-5 py-3">Cycle</th>
                                <th class="px-5 py-3">Next Due Date</th>
                                <th class="px-5 py-3 text-right">Fee ({{ __('BDT') }})</th>
                                <th class="px-5 py-3 text-center">Alert Status</th>
                                <th class="px-5 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                            @forelse($renewals as $ren)
                                <tr class="hover:bg-slate-50/80 transition">
                                    <td class="px-5 py-3.5 whitespace-nowrap">
                                        <a href="{{ route('renewals.show', $ren) }}" class="font-mono font-bold text-blue-600 hover:underline">
                                            {{ $ren->renewal_no }}
                                        </a>
                                    </td>
                                    <td class="px-5 py-3.5">
                                        <div class="font-bold text-slate-900">{{ $ren->service_name }}</div>
                                        <span class="inline-block px-1.5 py-0.5 bg-slate-100 text-slate-600 text-[10px] rounded mt-0.5">
                                            {{ $ren->category }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-3.5">
                                        <div class="font-bold text-slate-900">{{ $ren->customer->name }}</div>
                                        <div class="text-[11px] text-slate-400">{{ $ren->customer->phone }}</div>
                                    </td>
                                    <td class="px-5 py-3.5 whitespace-nowrap text-slate-600">
                                        {{ $ren->billing_cycle }}
                                    </td>
                                    <td class="px-5 py-3.5 whitespace-nowrap">
                                        <span class="font-bold {{ $ren->computed_status === 'Overdue' ? 'text-rose-600 font-mono' : ($ren->computed_status === 'Due Today' ? 'text-red-600 font-mono' : 'text-slate-800') }}">
                                            {{ $ren->next_due_date->format('d M, Y') }}
                                        </span>
                                        <div class="text-[10px] text-slate-400">
                                            {{ $ren->next_due_date->diffForHumans() }}
                                        </div>
                                    </td>
                                    <td class="px-5 py-3.5 whitespace-nowrap text-right font-black text-slate-900">
                                        {{ __('BDT') }} {{ number_format($ren->renewal_fee, 2) }}
                                    </td>
                                    <td class="px-5 py-3.5 whitespace-nowrap text-center">
                                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold border {{ $ren->status_badge_color }}">
                                            {{ $ren->computed_status }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-3.5 whitespace-nowrap text-right space-x-1.5">
                                        <a href="{{ route('renewals.show', $ren) }}" class="inline-flex items-center px-2.5 py-1 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 font-bold text-xs rounded border border-emerald-200 transition">
                                            Renew / Pay
                                        </a>
                                        <a href="{{ route('renewals.edit', $ren) }}" class="inline-flex items-center px-2 py-1 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold text-xs rounded transition">
                                            Edit
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-5 py-8 text-center text-slate-400">
                                        No renewal subscriptions matching this filter.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($renewals->hasPages())
                    <div class="p-4 border-t border-slate-200">
                        {{ $renewals->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
