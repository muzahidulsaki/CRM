<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight flex items-center gap-2">
                    <svg class="w-7 h-7 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    অর্ডার ম্যানেজমেন্ট (Orders)
                </h2>
                <p class="text-sm text-gray-500 mt-1">ক্লায়েন্টের সকল কনফার্মড সেলস অর্ডার, বকেয়া এবং প্রজেক্ট ট্র্যাকিং</p>
            </div>
            <a href="{{ route('orders.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 shadow-sm transition">
                <svg class="w-4 h-4 me-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                নতুন অর্ডার তৈরি
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Flash Message -->
            @if(session('success'))
                <div class="p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 rounded-r-lg shadow-sm flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        <span class="font-medium text-sm">{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            <!-- Order Metrics -->
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3">
                <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm text-center">
                    <div class="text-xs font-semibold text-gray-500 uppercase">মোট অর্ডার</div>
                    <div class="text-2xl font-bold text-gray-900 mt-1">{{ $metrics['total'] }}</div>
                </div>

                <div class="bg-white p-4 rounded-xl border border-blue-200 bg-blue-50/40 shadow-sm text-center">
                    <div class="text-xs font-semibold text-blue-600 uppercase">চলমান (Active)</div>
                    <div class="text-2xl font-bold text-blue-700 mt-1">{{ $metrics['active'] }}</div>
                </div>

                <div class="bg-white p-4 rounded-xl border border-emerald-200 bg-emerald-50/40 shadow-sm text-center">
                    <div class="text-xs font-semibold text-emerald-600 uppercase">ডেলিভার্ড / সম্পন্ন</div>
                    <div class="text-2xl font-bold text-emerald-700 mt-1">{{ $metrics['completed'] }}</div>
                </div>

                <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm text-center">
                    <div class="text-xs font-semibold text-indigo-700 uppercase">মোট সেলস ভ্যালু</div>
                    <div class="text-xl font-bold text-indigo-800 mt-1">৳{{ number_format($metrics['total_sales']) }}</div>
                </div>

                <div class="bg-white p-4 rounded-xl border border-rose-200 bg-rose-50/40 shadow-sm text-center col-span-2 sm:col-span-1">
                    <div class="text-xs font-semibold text-rose-600 uppercase">মোট বকেয়া (Due)</div>
                    <div class="text-xl font-bold text-rose-700 mt-1">৳{{ number_format($metrics['total_due']) }}</div>
                </div>
            </div>

            <!-- Filters & Search -->
            <div class="bg-white p-4 rounded-2xl border border-gray-200 shadow-sm">
                <form method="GET" action="{{ route('orders.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 items-end">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">অনুসন্ধান (অর্ডার / ক্লায়েন্ট)</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="অর্ডার কোড, ক্লায়েন্ট, ফোন..."
                            class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">সার্ভিস ক্যাটাগরি</label>
                        <select name="category" class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">সকল ক্যাটাগরি</option>
                            @foreach($serviceCategories as $cat)
                                <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">স্ট্যাটাস</label>
                        <select name="status" class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">সকল স্ট্যাটাস</option>
                            @foreach(['Pending', 'Confirmed', 'Project Started', 'Working', 'Under Review', 'Completed', 'Delivered', 'Cancelled'] as $st)
                                <option value="{{ $st }}" {{ request('status') == $st ? 'selected' : '' }}>{{ $st }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex items-center gap-2">
                        <button type="submit" class="flex-1 py-2 px-4 bg-gray-900 text-white rounded-lg text-sm font-semibold hover:bg-gray-800 transition">
                            ফিল্টার করুন
                        </button>
                        @if(request()->anyFilled(['search', 'category', 'status']))
                            <a href="{{ route('orders.index') }}" class="py-2 px-3 bg-gray-100 text-gray-600 rounded-lg text-sm font-medium hover:bg-gray-200 transition">
                                রিসেট
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Orders Table -->
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-left text-sm">
                        <thead class="bg-gray-50 text-gray-500 font-semibold uppercase text-xs">
                            <tr>
                                <th class="py-3.5 px-4">অর্ডার কোড</th>
                                <th class="py-3.5 px-4">অর্ডারের নাম ও ক্যাটাগরি</th>
                                <th class="py-3.5 px-4">ক্লায়েন্ট</th>
                                <th class="py-3.5 px-4">মূল্য ও পেমেন্ট (৳)</th>
                                <th class="py-3.5 px-4">প্রজেক্ট লিঙ্ক</th>
                                <th class="py-3.5 px-4">স্ট্যাটাস</th>
                                <th class="py-3.5 px-4 text-right">অ্যাকশন</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($orders as $order)
                                <tr class="hover:bg-gray-50/60 transition">
                                    <td class="py-3.5 px-4">
                                        <a href="{{ route('orders.show', $order) }}" class="font-bold text-indigo-600 hover:text-indigo-800">
                                            {{ $order->order_code }}
                                        </a>
                                        <div class="text-xs text-gray-400">{{ $order->order_date ? $order->order_date->format('d M, Y') : '' }}</div>
                                    </td>
                                    <td class="py-3.5 px-4">
                                        <div class="font-semibold text-gray-800">{{ $order->title }}</div>
                                        <span class="inline-block mt-0.5 px-2 py-0.5 bg-gray-100 text-gray-600 text-xs rounded-md">
                                            {{ $order->service_category }}
                                        </span>
                                    </td>
                                    <td class="py-3.5 px-4">
                                        @if($order->customer)
                                            <a href="{{ route('customers.show', $order->customer) }}" class="font-medium text-gray-900 hover:text-indigo-600">
                                                {{ $order->customer->name }}
                                            </a>
                                            <div class="text-xs text-gray-500">{{ $order->customer->company_name ?? $order->customer->phone }}</div>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="py-3.5 px-4">
                                        <div class="font-bold text-gray-900">৳{{ number_format($order->total_amount, 2) }}</div>
                                        <div class="text-xs text-emerald-600">পরিশোধ: ৳{{ number_format($order->paid_amount, 2) }}</div>
                                        @if($order->due_amount > 0)
                                            <div class="text-xs text-rose-600 font-semibold">বকেয়া: ৳{{ number_format($order->due_amount, 2) }}</div>
                                        @else
                                            <div class="text-xs text-emerald-500">পরিশোধিত</div>
                                        @endif
                                    </td>
                                    <td class="py-3.5 px-4">
                                        @if($order->project)
                                            <a href="{{ route('projects.show', $order->project) }}" class="inline-flex items-center gap-1 text-xs font-semibold text-indigo-600 hover:text-indigo-800 bg-indigo-50 px-2.5 py-1 rounded-lg border border-indigo-200">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                                {{ $order->project->project_code }} ({{ $order->project->progress_percent }}%)
                                            </a>
                                        @else
                                            <form method="POST" action="{{ route('orders.initialize-project', $order) }}">
                                                @csrf
                                                <button type="submit" class="text-xs font-medium text-emerald-700 bg-emerald-50 border border-emerald-300 hover:bg-emerald-100 px-2 py-1 rounded-md transition flex items-center gap-1">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                                    প্রজেক্ট চালু
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                    <td class="py-3.5 px-4">
                                        @php
                                            $stClass = match($order->status) {
                                                'Confirmed' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                                'Project Started', 'Working' => 'bg-blue-50 text-blue-700 border-blue-200',
                                                'Under Review' => 'bg-purple-50 text-purple-700 border-purple-200',
                                                'Completed', 'Delivered' => 'bg-green-50 text-green-700 border-green-200',
                                                'Cancelled' => 'bg-rose-50 text-rose-700 border-rose-200',
                                                default => 'bg-amber-50 text-amber-700 border-amber-200'
                                            };
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold border {{ $stClass }}">
                                            {{ $order->status }}
                                        </span>
                                    </td>
                                    <td class="py-3.5 px-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('orders.show', $order) }}" class="p-1.5 text-gray-500 hover:text-indigo-600 hover:bg-gray-100 rounded-lg transition" title="বিস্তারিত">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            </a>
                                            <a href="{{ route('orders.edit', $order) }}" class="p-1.5 text-gray-500 hover:text-blue-600 hover:bg-gray-100 rounded-lg transition" title="সম্পাদনা">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-12 text-center text-gray-400">
                                        <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                        <div class="text-base font-medium">কোনো অর্ডার পাওয়া যায়নি</div>
                                        <div class="text-sm mt-1">নতুন সেলস অর্ডার তৈরির জন্য উপরের বাটনে ক্লিক করুন।</div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($orders->hasPages())
                    <div class="p-4 border-t border-gray-100">
                        {{ $orders->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
