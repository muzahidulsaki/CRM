<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-extrabold text-2xl text-slate-900 tracking-tight leading-tight flex items-center gap-2">
                    <span class="p-2 bg-indigo-50 text-indigo-600 rounded-xl">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/></svg>
                    </span>
                    Our Ads Reports & Campaign Tracking
                </h2>
                <p class="text-xs text-slate-500 mt-1">
                    Excel parity campaign ledger: track daily dollar burn, Facebook/Google post links, daily costing, today status checks, and client WhatsApp links.
                </p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('ads-reports.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl transition shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    + New Campaign Entry
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6 space-y-6">
        <!-- 6 KPI Summary Cards -->
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
            <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-2xs">
                <span class="text-[11px] font-bold uppercase text-slate-400">Total Campaigns</span>
                <div class="text-2xl font-black text-slate-900 mt-1">{{ $metrics['total_campaigns'] }}</div>
                <div class="text-[11px] text-emerald-600 font-semibold mt-0.5">{{ $metrics['active_campaigns'] }} Active Now</div>
            </div>

            <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-2xs">
                <span class="text-[11px] font-bold uppercase text-slate-400">Total BDT Value</span>
                <div class="text-xl font-black text-slate-900 mt-1">৳{{ number_format($metrics['total_bdt'], 0) }}</div>
                <div class="text-[11px] text-slate-400 mt-0.5">Client Invoiced</div>
            </div>

            <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-2xs">
                <span class="text-[11px] font-bold uppercase text-slate-400">Total Dollar Budget</span>
                <div class="text-2xl font-black text-blue-600 mt-1">${{ number_format($metrics['total_dollar'], 2) }}</div>
                <div class="text-[11px] text-slate-400 mt-0.5">Allocated Budget</div>
            </div>

            <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-2xs">
                <span class="text-[11px] font-bold uppercase text-slate-400">Running Dollar Spent</span>
                <div class="text-2xl font-black text-indigo-600 mt-1">${{ number_format($metrics['running_dollar'], 2) }}</div>
                <div class="text-[11px] text-slate-400 mt-0.5">Live Burned</div>
            </div>

            <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-2xs">
                <span class="text-[11px] font-bold uppercase text-slate-400">Dollar Due</span>
                <div class="text-2xl font-black text-rose-600 mt-1">${{ number_format($metrics['dollar_due'], 2) }}</div>
                <div class="text-[11px] text-slate-400 mt-0.5">Remaining Dollar</div>
            </div>

            <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-2xs">
                <span class="text-[11px] font-bold uppercase text-slate-400">Today Check</span>
                <div class="text-2xl font-black text-emerald-600 mt-1">{{ $metrics['checked_today'] }}</div>
                <div class="text-[11px] text-slate-400 mt-0.5">Verified Today</div>
            </div>
        </div>

        <!-- Filter & Search Toolbar -->
        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-2xs">
            <form action="{{ route('ads-reports.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-6 gap-3">
                <!-- Search input -->
                <div class="md:col-span-2">
                    <label class="block text-[11px] font-bold text-slate-500 uppercase mb-1">Search Campaign / Client</label>
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Business, WhatsApp, Post Link, Lead..." class="w-full text-xs pl-8 pr-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none">
                        <svg class="w-4 h-4 text-slate-400 absolute left-2.5 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                </div>

                <!-- Month Filter -->
                <div>
                    <label class="block text-[11px] font-bold text-slate-500 uppercase mb-1">Order Month</label>
                    <select name="month" class="w-full text-xs px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none">
                        <option value="">All Months</option>
                        @foreach($months as $m)
                            <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>{{ $m }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Category Filter -->
                <div>
                    <label class="block text-[11px] font-bold text-slate-500 uppercase mb-1">Category</label>
                    <select name="category" class="w-full text-xs px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none">
                        <option value="">All Categories</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Activity Filter -->
                <div>
                    <label class="block text-[11px] font-bold text-slate-500 uppercase mb-1">Activity</label>
                    <select name="activity" class="w-full text-xs px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none">
                        <option value="">All Statuses</option>
                        <option value="Active" {{ request('activity') == 'Active' ? 'selected' : '' }}>Active</option>
                        <option value="Paused" {{ request('activity') == 'Paused' ? 'selected' : '' }}>Paused</option>
                        <option value="Completed" {{ request('activity') == 'Completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                </div>

                <!-- Today Check Filter & Actions -->
                <div class="flex items-end gap-2">
                    <div class="flex-1">
                        <label class="block text-[11px] font-bold text-slate-500 uppercase mb-1">Today Check</label>
                        <select name="today_check" class="w-full text-xs px-2.5 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none">
                            <option value="">All</option>
                            <option value="Checked" {{ request('today_check') == 'Checked' ? 'selected' : '' }}>Checked</option>
                            <option value="Unchecked" {{ request('today_check') == 'Unchecked' ? 'selected' : '' }}>Unchecked</option>
                        </select>
                    </div>
                    <button type="submit" class="px-3.5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl transition shadow-xs">
                        Filter
                    </button>
                    @if(request()->anyFilled(['search', 'month', 'category', 'activity', 'today_check']))
                        <a href="{{ route('ads-reports.index') }}" class="px-2.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-bold rounded-xl transition" title="Clear Filters">
                            ✕
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Excel Spreadsheet-Parity Table -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-2xs overflow-hidden">
            <div class="p-4 bg-slate-50 border-b border-slate-100 flex items-center justify-between">
                <span class="text-xs font-bold text-slate-700">
                    Showing {{ $reports->firstItem() ?? 0 }}-{{ $reports->lastItem() ?? 0 }} of {{ $reports->total() }} Ads Campaigns
                </span>
                <span class="text-[11px] text-slate-500 italic">21-Column Spreadsheet View matching CRM Format.xlsx</span>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-100 text-xs whitespace-nowrap">
                    <thead class="bg-slate-800 text-white font-bold text-[11px] uppercase tracking-wider">
                        <tr>
                            <th class="px-3 py-3 text-left">Lead ID</th>
                            <th class="px-3 py-3 text-left">Order Source</th>
                            <th class="px-3 py-3 text-center">Activity</th>
                            <th class="px-3 py-3 text-right">Total Amount (৳)</th>
                            <th class="px-3 py-3 text-right">Total Dollar ($)</th>
                            <th class="px-3 py-3 text-left">Ads Post Link</th>
                            <th class="px-3 py-3 text-left">Business Category</th>
                            <th class="px-3 py-3 text-left">Business Name</th>
                            <th class="px-3 py-3 text-left">Whatsapp</th>
                            <th class="px-3 py-3 text-left">Order Month</th>
                            <th class="px-3 py-3 text-left">Order Date</th>
                            <th class="px-3 py-3 text-left">End Date</th>
                            <th class="px-3 py-3 text-left">Starting Date</th>
                            <th class="px-3 py-3 text-center">Total Days</th>
                            <th class="px-3 py-3 text-right">Daily Cost ($)</th>
                            <th class="px-3 py-3 text-right">Previous $</th>
                            <th class="px-3 py-3 text-right">Running $</th>
                            <th class="px-3 py-3 text-right">Total $ Cost</th>
                            <th class="px-3 py-3 text-right text-rose-300">$ Due</th>
                            <th class="px-3 py-3 text-center">Today Check</th>
                            <th class="px-3 py-3 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($reports as $row)
                            @php
                                $phoneClean = preg_replace('/[^0-9]/', '', $row->whatsapp ?? '');
                                $isChecked = ($row->today_check === 'Checked');
                            @endphp
                            <tr class="hover:bg-slate-50/80 transition {{ $isChecked ? 'bg-emerald-50/30' : '' }}">
                                <!-- 1. Lead ID -->
                                <td class="px-3 py-2.5 font-bold text-indigo-600">
                                    {{ $row->lead_code ?? ($row->lead ? $row->lead->lead_code : '—') }}
                                </td>

                                <!-- 2. Order Source -->
                                <td class="px-3 py-2.5 text-slate-700">
                                    {{ $row->order_source ?? 'N/A' }}
                                </td>

                                <!-- 3. Activity -->
                                <td class="px-3 py-2.5 text-center">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-black uppercase {{ $row->activity === 'Active' ? 'bg-emerald-100 text-emerald-800' : ($row->activity === 'Paused' ? 'bg-amber-100 text-amber-800' : 'bg-slate-100 text-slate-700') }}">
                                        {{ $row->activity }}
                                    </span>
                                </td>

                                <!-- 4. Total Amount (BDT) -->
                                <td class="px-3 py-2.5 text-right font-semibold text-slate-900">
                                    ৳{{ number_format($row->total_amount, 2) }}
                                </td>

                                <!-- 5. Total Dollar ($) -->
                                <td class="px-3 py-2.5 text-right font-black text-blue-600">
                                    ${{ number_format($row->total_dollar, 2) }}
                                </td>

                                <!-- 6. Ads Post Link -->
                                <td class="px-3 py-2.5 max-w-[150px] truncate">
                                    @if($row->ads_post_link)
                                        <a href="{{ $row->ads_post_link }}" target="_blank" class="inline-flex items-center gap-1 text-blue-600 hover:underline">
                                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                            <span class="truncate">View Ad</span>
                                        </a>
                                    @else
                                        <span class="text-slate-400 italic">—</span>
                                    @endif
                                </td>

                                <!-- 7. Business Category -->
                                <td class="px-3 py-2.5 text-slate-600">
                                    {{ $row->business_category ?? '—' }}
                                </td>

                                <!-- 8. Business Name -->
                                <td class="px-3 py-2.5 font-bold text-slate-900">
                                    {{ $row->business_name }}
                                </td>

                                <!-- 9. Whatsapp -->
                                <td class="px-3 py-2.5">
                                    @if($phoneClean)
                                        <a href="https://wa.me/{{ $phoneClean }}" target="_blank" class="inline-flex items-center gap-1 text-emerald-700 hover:underline font-mono font-bold">
                                            <span>💬 {{ $row->whatsapp }}</span>
                                        </a>
                                    @else
                                        <span class="text-slate-400 italic">—</span>
                                    @endif
                                </td>

                                <!-- 10. Order Month -->
                                <td class="px-3 py-2.5 text-slate-600">
                                    {{ $row->order_month ?? '—' }}
                                </td>

                                <!-- 11. Order Date -->
                                <td class="px-3 py-2.5 text-slate-600">
                                    {{ $row->order_date ? $row->order_date->format('d M, Y') : '—' }}
                                </td>

                                <!-- 12. End Date -->
                                <td class="px-3 py-2.5 text-slate-600">
                                    {{ $row->end_date ? $row->end_date->format('d M, Y') : '—' }}
                                </td>

                                <!-- 14. Starting Date -->
                                <td class="px-3 py-2.5 text-slate-600">
                                    {{ $row->starting_date ? $row->starting_date->format('d M, Y') : '—' }}
                                </td>

                                <!-- 15. Total Days -->
                                <td class="px-3 py-2.5 text-center font-semibold text-slate-800">
                                    {{ $row->total_days }}
                                </td>

                                <!-- 16. Daily Cost -->
                                <td class="px-3 py-2.5 text-right font-medium text-slate-700">
                                    ${{ number_format($row->daily_cost, 2) }}
                                </td>

                                <!-- 17. Previous Dollar -->
                                <td class="px-3 py-2.5 text-right text-slate-500">
                                    ${{ number_format($row->previous_dollar, 2) }}
                                </td>

                                <!-- 18. Running Dollar -->
                                <td class="px-3 py-2.5 text-right font-bold text-indigo-700">
                                    ${{ number_format($row->running_dollar, 2) }}
                                </td>

                                <!-- 19. Total $ Cost -->
                                <td class="px-3 py-2.5 text-right font-bold text-slate-900">
                                    ${{ number_format($row->total_dollar_cost, 2) }}
                                </td>

                                <!-- 20. $ Due -->
                                <td class="px-3 py-2.5 text-right font-black {{ $row->dollar_due > 0 ? 'text-rose-600' : 'text-slate-400' }}">
                                    ${{ number_format($row->dollar_due, 2) }}
                                </td>

                                <!-- 21. Today Check (Toggleable) -->
                                <td class="px-3 py-2.5 text-center">
                                    <form action="{{ route('ads-reports.toggle-check', $row->id) }}" method="POST" class="inline-block">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[10px] font-black uppercase transition {{ $isChecked ? 'bg-emerald-600 hover:bg-emerald-700 text-white shadow-xs' : 'bg-slate-200 hover:bg-slate-300 text-slate-700' }}" title="Click to toggle status">
                                            @if($isChecked)
                                                ✓ Checked
                                            @else
                                                ⏳ Pending
                                            @endif
                                        </button>
                                    </form>
                                </td>

                                <!-- Actions -->
                                <td class="px-3 py-2.5 text-center">
                                    <div class="flex items-center justify-center gap-1">
                                        <a href="{{ route('ads-reports.edit', $row->id) }}" class="p-1 hover:bg-slate-200 rounded text-slate-600" title="Edit Entry">
                                            ✏️
                                        </a>
                                        <form action="{{ route('ads-reports.destroy', $row->id) }}" method="POST" onsubmit="return confirm('Delete this ads campaign entry?');" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1 hover:bg-rose-100 rounded text-rose-600" title="Delete Entry">
                                                🗑️
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="21" class="px-4 py-12 text-center text-slate-400 italic">
                                    No ads campaign reports found. Click "+ New Campaign Entry" to log an ad campaign!
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="p-4 border-t border-slate-100">
                {{ $reports->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
