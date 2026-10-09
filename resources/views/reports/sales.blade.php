<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('reports.index') }}" class="text-xs font-semibold text-indigo-600 hover:underline">&larr; Reports Hub</a>
                    <span class="text-gray-300">/</span>
                    <span class="text-xs font-semibold text-gray-500">Sales & CRM Analytics</span>
                </div>
                <h2 class="font-bold text-2xl text-gray-900 leading-tight mt-1 flex items-center gap-2">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                    {{ __('Sales & Lead Conversion Report') }}
                </h2>
                <p class="text-sm text-gray-500 mt-0.5">
                    সময়কাল: <strong class="text-gray-800">{{ $startDate->format('d M, Y') }}</strong> থেকে <strong class="text-gray-800">{{ $endDate->format('d M, Y') }}</strong>
                </p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('reports.export', ['type' => 'sales', 'range' => $filter]) }}" class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-semibold bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl shadow-sm transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    এক্সেল CSV ডাউনলোড
                </a>
                <button onclick="window.print()" class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-semibold bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 rounded-xl shadow-sm transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    প্রিন্ট
                </button>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Filter Range Bar -->
            <div class="bg-white p-4 rounded-2xl border border-gray-200 shadow-sm flex flex-wrap items-center gap-2">
                <span class="text-xs font-bold text-gray-500 uppercase tracking-wider me-2">ফিল্টার:</span>
                <a href="{{ route('reports.sales', ['range' => 'today']) }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold {{ $filter === 'today' ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">আজকে</a>
                <a href="{{ route('reports.sales', ['range' => 'yesterday']) }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold {{ $filter === 'yesterday' ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">গতকাল</a>
                <a href="{{ route('reports.sales', ['range' => 'last_7_days']) }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold {{ $filter === 'last_7_days' ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">গত ৭ দিন</a>
                <a href="{{ route('reports.sales', ['range' => 'this_month']) }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold {{ $filter === 'this_month' ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">চলতি মাস</a>
                <a href="{{ route('reports.sales', ['range' => 'last_month']) }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold {{ $filter === 'last_month' ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">গত মাস</a>
                <a href="{{ route('reports.sales', ['range' => 'this_year']) }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold {{ $filter === 'this_year' ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">চলতি বছর</a>
            </div>

            <!-- Key Metric Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm">
                    <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">মোট লিড (Leads)</span>
                    <div class="mt-2 text-3xl font-black text-gray-900">{{ $data['total_leads'] }}</div>
                    <p class="text-xs text-gray-500 mt-1">নির্বাচিত সময়কালে নতুন লিড</p>
                </div>
                <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm">
                    <span class="text-xs font-bold text-emerald-600 uppercase tracking-wider">সফল ডিল (Won Leads)</span>
                    <div class="mt-2 text-3xl font-black text-emerald-600">{{ $data['won_leads'] }}</div>
                    <p class="text-xs text-emerald-700 font-medium mt-1">কনভার্সন রেট: <strong>{{ $data['conversion_rate'] }}%</strong></p>
                </div>
                <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm">
                    <span class="text-xs font-bold text-indigo-600 uppercase tracking-wider">মোট বিক্রয় মূল্য (Orders)</span>
                    <div class="mt-2 text-3xl font-black text-indigo-600">৳{{ number_format($data['total_sales_value'], 2) }}</div>
                    <p class="text-xs text-gray-500 mt-1">{{ count($data['orders']) }} টি সফল অর্ডার</p>
                </div>
                <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm">
                    <span class="text-xs font-bold text-amber-600 uppercase tracking-wider">সংগ্রহ বনাম বকেয়া</span>
                    <div class="mt-2 text-xl font-black text-gray-900">
                        <span class="text-emerald-600">৳{{ number_format($data['total_collected'], 0) }}</span>
                        <span class="text-gray-400 font-normal">/</span>
                        <span class="text-rose-600">৳{{ number_format($data['total_due'], 0) }}</span>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">কালেক্টেড / ডিউ ব্যালেন্স</p>
                </div>
            </div>

            <!-- Breakdown Grids -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Source Performance (Where do leads come from?) -->
                <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
                    <h3 class="font-bold text-base text-gray-900 mb-4 flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-indigo-600"></span>
                        লিড সোর্স পারফরম্যান্স (Lead Acquisition Channels)
                    </h3>
                    @if(count($data['source_counts']) > 0)
                        <div class="space-y-3">
                            @foreach($data['source_counts'] as $source => $count)
                                @php
                                    $pct = $data['total_leads'] > 0 ? round(($count / $data['total_leads']) * 100, 1) : 0;
                                @endphp
                                <div>
                                    <div class="flex justify-between items-center text-xs font-semibold mb-1">
                                        <span class="text-gray-800">{{ ucfirst(str_replace('_', ' ', $source ?: 'Direct / Unknown')) }}</span>
                                        <span class="text-gray-600">{{ $count }} টি ({{ $pct }}%)</span>
                                    </div>
                                    <div class="w-full bg-gray-100 rounded-full h-2 overflow-hidden">
                                        <div class="bg-indigo-600 h-2 rounded-full" style="width: {{ $pct }}%"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-xs text-gray-400 italic py-4 text-center">এই সময়কালে কোনো লিড রেকর্ড নেই।</p>
                    @endif
                </div>

                <!-- Service Category Performance -->
                <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
                    <h3 class="font-bold text-base text-gray-900 mb-4 flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-600"></span>
                        সার্ভিস ক্যাটাগরি চাহিদা (Requested Services)
                    </h3>
                    @if(count($data['category_counts']) > 0)
                        <div class="space-y-3">
                            @foreach($data['category_counts'] as $cat => $count)
                                @php
                                    $pct = $data['total_leads'] > 0 ? round(($count / $data['total_leads']) * 100, 1) : 0;
                                @endphp
                                <div>
                                    <div class="flex justify-between items-center text-xs font-semibold mb-1">
                                        <span class="text-gray-800">{{ $cat ?: 'Uncategorized' }}</span>
                                        <span class="text-gray-600">{{ $count }} টি ({{ $pct }}%)</span>
                                    </div>
                                    <div class="w-full bg-gray-100 rounded-full h-2 overflow-hidden">
                                        <div class="bg-emerald-600 h-2 rounded-full" style="width: {{ $pct }}%"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-xs text-gray-400 italic py-4 text-center">এই সময়কালে কোনো ক্যাটাগরি ডাটা নেই।</p>
                    @endif
                </div>
            </div>

            <!-- Orders Table in Date Range -->
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="p-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                    <h3 class="font-bold text-base text-gray-900">
                        সময়কালের সম্পন্ন হওয়া বিক্রয় ও অর্ডারসমূহ (Orders: {{ count($data['orders']) }})
                    </h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-gray-700">
                        <thead class="bg-gray-50 text-gray-500 uppercase tracking-wider font-semibold border-b">
                            <tr>
                                <th class="p-3.5">অর্ডার নং</th>
                                <th class="p-3.5">কাস্টমার</th>
                                <th class="p-3.5">সার্ভিস ক্যাটাগরি</th>
                                <th class="p-3.5">তারিখ</th>
                                <th class="p-3.5 text-right">মোট বিক্রয় মূল্য</th>
                                <th class="p-3.5 text-right">কালেকশন</th>
                                <th class="p-3.5 text-right">বকেয়া</th>
                                <th class="p-3.5 text-center">স্ট্যাটাস</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($data['orders'] as $ord)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="p-3.5 font-bold text-indigo-600">
                                        <a href="{{ route('orders.show', $ord->id) }}">{{ $ord->order_code }}</a>
                                    </td>
                                    <td class="p-3.5 font-medium text-gray-900">{{ $ord->customer->name ?? 'N/A' }}</td>
                                    <td class="p-3.5 text-gray-600">{{ $ord->service_category }}</td>
                                    <td class="p-3.5 text-gray-500">{{ $ord->created_at->format('d M, Y') }}</td>
                                    <td class="p-3.5 font-bold text-gray-900 text-right">৳{{ number_format($ord->total_amount, 2) }}</td>
                                    <td class="p-3.5 text-emerald-600 font-semibold text-right">৳{{ number_format($ord->paid_amount, 2) }}</td>
                                    <td class="p-3.5 text-rose-600 font-semibold text-right">৳{{ number_format($ord->due_amount, 2) }}</td>
                                    <td class="p-3.5 text-center">
                                        <span class="px-2 py-0.5 rounded-full text-[11px] font-semibold bg-gray-100 text-gray-800">
                                            {{ ucfirst($ord->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="p-8 text-center text-gray-400 italic">
                                        এই সময়কালে কোনো অর্ডার রেকর্ড নেই।
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
