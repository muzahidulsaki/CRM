<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight flex items-center gap-2">
                    <svg class="w-7 h-7 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                    {{ __('Video Editor Per-Second Billings') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">{{ __('Video length (seconds) x per-second rate = compensation ledger & billing') }}</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('payouts.create', ['payout_type' => 'video_billing']) }}" class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white rounded-lg font-semibold text-xs uppercase tracking-widest hover:bg-emerald-700 shadow-sm transition">
                    <svg class="w-4 h-4 me-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    {{ __('Pay Compensation') }}
                </a>
                <a href="{{ route('video-billings.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 shadow-sm transition">
                    <svg class="w-4 h-4 me-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    {{ __('New Video Billing Entry') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Video Production & Billing Metrics -->
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
                <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm text-center">
                    <div class="text-xs font-semibold text-gray-500 uppercase">{{ __('Total Videos') }}</div>
                    <div class="text-2xl font-bold text-gray-900 mt-1">{{ $metrics['total_count'] }}</div>
                </div>

                <div class="bg-white p-4 rounded-xl border border-indigo-200 bg-indigo-50/30 shadow-sm text-center">
                    <div class="text-xs font-semibold text-indigo-600 uppercase">{{ __('Approved Videos') }}</div>
                    <div class="text-2xl font-bold text-indigo-700 mt-1">{{ $metrics['approved_count'] }}</div>
                </div>

                <div class="bg-white p-4 rounded-xl border border-blue-200 bg-blue-50/30 shadow-sm text-center">
                    <div class="text-xs font-semibold text-blue-600 uppercase">{{ __('Total Duration') }}</div>
                    <div class="text-lg font-bold text-blue-900 mt-1">
                        {{ number_format($metrics['total_seconds']) }} <span class="text-xs font-normal text-gray-500">{{ __('sec') }}</span>
                    </div>
                </div>

                <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm text-center">
                    <div class="text-xs font-semibold text-gray-600 uppercase">{{ __('Total Payable') }}</div>
                    <div class="text-xl font-bold text-gray-900 mt-1">BDT {{ number_format($metrics['total_payable']) }}</div>
                </div>

                <div class="bg-white p-4 rounded-xl border border-emerald-200 bg-emerald-50/30 shadow-sm text-center">
                    <div class="text-xs font-semibold text-emerald-600 uppercase">{{ __('Total Paid') }}</div>
                    <div class="text-xl font-bold text-emerald-700 mt-1">BDT {{ number_format($metrics['total_paid']) }}</div>
                </div>

                <div class="bg-white p-4 rounded-xl border border-rose-200 bg-rose-50/30 shadow-sm text-center">
                    <div class="text-xs font-semibold text-rose-600 uppercase">{{ __('Due Balance') }}</div>
                    <div class="text-xl font-bold text-rose-700 mt-1">BDT {{ number_format($metrics['total_due']) }}</div>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white p-4 rounded-2xl border border-gray-200 shadow-sm">
                <form method="GET" action="{{ route('video-billings.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 items-end">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">{{ __('Search') }}</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('Billing code, video title, editor...') }}"
                            class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">{{ __('Video Editor') }}</label>
                        <select name="employee_id" class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">{{ __('All Editors') }}</option>
                            @foreach($employees as $emp)
                                <option value="{{ $emp->id }}" {{ request('employee_id') == $emp->id ? 'selected' : '' }}>
                                    {{ $emp->user->name }} ({{ $emp->designation }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">{{ __('Work Status') }}</label>
                        <select name="status" class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">{{ __('All Statuses') }}</option>
                            @foreach(['Submitted', 'Approved', 'Rejected', 'Draft', 'In Progress'] as $st)
                                <option value="{{ $st }}" {{ request('status') == $st ? 'selected' : '' }}>{{ __($st) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">{{ __('Payment Status') }}</label>
                        <select name="payment_status" class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">{{ __('All Payments') }}</option>
                            @foreach(['Unpaid', 'Partially Paid', 'Paid'] as $pst)
                                <option value="{{ $pst }}" {{ request('payment_status') == $pst ? 'selected' : '' }}>{{ __($pst) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex items-center gap-2">
                        <button type="submit" class="flex-1 py-2 px-4 bg-gray-900 text-white rounded-lg text-sm font-semibold hover:bg-gray-800 transition">
                            {{ __('Filter') }}
                        </button>
                        @if(request()->anyFilled(['search', 'employee_id', 'status', 'payment_status']))
                            <a href="{{ route('video-billings.index') }}" class="py-2 px-3 bg-gray-100 text-gray-600 rounded-lg text-sm font-medium hover:bg-gray-200 transition">
                                {{ __('Reset') }}
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Billings Table -->
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-left text-sm">
                        <thead class="bg-gray-50 text-gray-500 font-semibold uppercase text-xs">
                            <tr>
                                <th class="py-3.5 px-4">{{ __('Billing Code') }}</th>
                                <th class="py-3.5 px-4">{{ __('Video & Project') }}</th>
                                <th class="py-3.5 px-4">{{ __('Video Editor') }}</th>
                                <th class="py-3.5 px-4">{{ __('Duration & Rate') }}</th>
                                <th class="py-3.5 px-4">{{ __('Total Payable') }}</th>
                                <th class="py-3.5 px-4">{{ __('Approval') }}</th>
                                <th class="py-3.5 px-4">{{ __('Payment Status') }}</th>
                                <th class="py-3.5 px-4 text-right">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($billings as $bill)
                                <tr class="hover:bg-gray-50/60 transition">
                                    <td class="py-3.5 px-4">
                                        <a href="{{ route('video-billings.show', $bill) }}" class="font-bold text-indigo-600 hover:text-indigo-800 font-mono text-xs">
                                            {{ $bill->billing_code }}
                                        </a>
                                        <div class="text-xs text-gray-400 mt-0.5">{{ $bill->created_at->format('d M, Y') }}</div>
                                    </td>
                                    <td class="py-3.5 px-4">
                                        <div class="font-bold text-gray-900">{{ $bill->video_title }}</div>
                                        <div class="text-xs text-gray-500 flex items-center gap-1.5 mt-0.5">
                                            @if($bill->project)
                                                <span>📁 {{ $bill->project->name }}</span>
                                            @endif
                                            @if($bill->video_url)
                                                <a href="{{ $bill->video_url }}" target="_blank" class="text-indigo-600 hover:underline flex items-center gap-0.5">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                                    {{ __('Drive Link') }}
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="py-3.5 px-4">
                                        <div class="flex items-center gap-2">
                                            <div class="w-7 h-7 rounded-full bg-indigo-100 text-indigo-700 font-bold flex items-center justify-center text-xs">
                                                {{ mb_substr($bill->employee->user->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="font-medium text-gray-900">{{ $bill->employee->user->name }}</div>
                                                <div class="text-xs text-gray-400">{{ $bill->employee->designation }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3.5 px-4">
                                        <div class="font-bold text-gray-800">
                                            {{ $bill->duration_minutes }}m {{ $bill->duration_seconds }}s
                                        </div>
                                        <div class="text-xs text-indigo-600">
                                            {{ number_format($bill->total_seconds) }} {{ __('sec') }} &times; BDT {{ number_format($bill->rate_per_second, 2) }}
                                        </div>
                                    </td>
                                    <td class="py-3.5 px-4">
                                        <div class="font-black text-gray-900">BDT {{ number_format($bill->total_payable, 2) }}</div>
                                        @if($bill->due_amount > 0)
                                            <div class="text-xs text-rose-600 font-semibold">{{ __('Due') }}: BDT {{ number_format($bill->due_amount, 2) }}</div>
                                        @else
                                            <div class="text-xs text-emerald-600 font-medium">{{ __('Paid') }}</div>
                                        @endif
                                    </td>
                                    <td class="py-3.5 px-4">
                                        @php
                                            $stClass = match($bill->status) {
                                                'Approved' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                                'Submitted' => 'bg-amber-50 text-amber-700 border-amber-200',
                                                'Rejected' => 'bg-rose-50 text-rose-700 border-rose-200',
                                                default => 'bg-gray-50 text-gray-700 border-gray-200'
                                            };
                                        @endphp
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold border {{ $stClass }}">
                                            {{ __($bill->status) }}
                                        </span>
                                    </td>
                                    <td class="py-3.5 px-4">
                                        @php
                                            $pstClass = match($bill->payment_status) {
                                                'Paid' => 'bg-emerald-100 text-emerald-800',
                                                'Partially Paid' => 'bg-blue-100 text-blue-800',
                                                default => 'bg-rose-100 text-rose-800'
                                            };
                                        @endphp
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold {{ $pstClass }}">
                                            {{ __($bill->payment_status) }}
                                        </span>
                                    </td>
                                    <td class="py-3.5 px-4 text-right">
                                        <div class="flex items-center justify-end gap-1.5">
                                            @if($bill->status !== 'Approved')
                                                <form method="POST" action="{{ route('video-billings.approve', $bill) }}">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="px-2 py-1 bg-emerald-50 text-emerald-700 hover:bg-emerald-100 border border-emerald-200 rounded text-xs font-semibold transition" title="{{ __('Approve') }}">
                                                        {{ __('Approve') }}
                                                    </button>
                                                </form>
                                            @elseif($bill->due_amount > 0)
                                                <a href="{{ route('payouts.create', ['employee_id' => $bill->employee_id, 'video_billing_id' => $bill->id, 'payout_type' => 'video_billing']) }}"
                                                   class="px-2 py-1 bg-indigo-50 text-indigo-700 hover:bg-indigo-100 border border-indigo-200 rounded text-xs font-semibold transition">
                                                    {{ __('Pay') }}
                                                </a>
                                            @endif
                                            <a href="{{ route('video-billings.show', $bill) }}" class="p-1.5 text-gray-500 hover:text-indigo-600 rounded hover:bg-gray-100 transition" title="{{ __('Details') }}">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="py-12 text-center text-gray-400">
                                        <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                        <div class="text-base font-medium">{{ __('No video billings found') }}</div>
                                        <div class="text-sm mt-1">{{ __('Click the button above to add a new video billing entry.') }}</div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($billings->hasPages())
                    <div class="p-4 border-t border-gray-100">
                        {{ $billings->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
