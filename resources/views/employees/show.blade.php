<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-full bg-indigo-600 text-white font-bold flex items-center justify-center text-xl shrink-0 shadow-sm">
                    {{ mb_substr($employee->user->name, 0, 1) }}
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                            {{ $employee->user->name }}
                        </h2>
                        <span class="text-xs font-mono bg-gray-100 text-gray-700 px-2 py-0.5 rounded">
                            {{ $employee->employee_code }}
                        </span>
                    </div>
                    <p class="text-xs text-gray-500 mt-0.5">
                        {{ $employee->designation }} • {{ $employee->department }} ({{ $employee->user->role->name ?? 'Staff' }})
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('payouts.create', ['employee_id' => $employee->id, 'payout_type' => ($employee->payment_type === 'per_second_video' ? 'video_billing' : 'monthly_salary')]) }}"
                   class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white rounded-lg text-sm font-semibold hover:bg-emerald-700 shadow-sm transition">
                    <svg class="w-4 h-4 me-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    {{ __('Pay Remuneration') }}
                </a>
                <a href="{{ route('employees.edit', $employee) }}" class="inline-flex items-center px-3.5 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm font-semibold hover:bg-gray-200 transition">
                    {{ __('Edit') }}
                </a>
                <a href="{{ route('employees.index') }}" class="inline-flex items-center px-3.5 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm font-semibold hover:bg-gray-200 transition">
                    {{ __('Back to List') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Financial Ledger 4-Card Banner -->
            <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm">
                    <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('Remuneration Model & Rate') }}</div>
                    <div class="text-xl font-bold text-gray-900 mt-1">
                        @if($employee->payment_type === 'per_second_video')
                            {{ __('BDT') }} {{ number_format($employee->salary_or_rate, 2) }} <span class="text-xs font-normal text-gray-500">/sec</span>
                        @elseif($employee->payment_type === 'hourly')
                            {{ __('BDT') }} {{ number_format($employee->salary_or_rate, 2) }} <span class="text-xs font-normal text-gray-500">/hr</span>
                        @else
                            {{ __('BDT') }} {{ number_format($employee->salary_or_rate) }} <span class="text-xs font-normal text-gray-500">/mo</span>
                        @endif
                    </div>
                    <div class="text-xs text-indigo-600 mt-0.5 capitalize">{{ str_replace('_', ' ', $employee->payment_type) }}</div>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-indigo-200 bg-indigo-50/20 shadow-sm">
                    <div class="text-xs font-semibold text-indigo-700 uppercase tracking-wider">{{ __('Total Earned') }}</div>
                    <div class="text-2xl font-black text-indigo-900 mt-1">
                        {{ __('BDT') }} {{ number_format($financials['total_earned'], 2) }}
                    </div>
                    <div class="text-xs text-indigo-600 mt-0.5">{{ $financials['total_videos'] }} {{ __('videos') }} ({{ number_format($financials['total_seconds']) }} sec)</div>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-emerald-200 bg-emerald-50/20 shadow-sm">
                    <div class="text-xs font-semibold text-emerald-700 uppercase tracking-wider">{{ __('Total Paid') }}</div>
                    <div class="text-2xl font-black text-emerald-800 mt-1">
                        {{ __('BDT') }} {{ number_format($financials['total_paid'], 2) }}
                    </div>
                    <div class="text-xs text-emerald-600 mt-0.5">{{ $employee->payouts->count() }} {{ __('vouchers') }}</div>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-rose-200 bg-rose-50/20 shadow-sm">
                    <div class="text-xs font-semibold text-rose-700 uppercase tracking-wider">{{ __('Current Due') }}</div>
                    <div class="text-2xl font-black text-rose-700 mt-1">
                        {{ __('BDT') }} {{ number_format($financials['current_due'], 2) }}
                    </div>
                    <div class="text-xs text-rose-500 mt-0.5">{{ __('Pending Payout') }}</div>
                </div>
            </div>

            <!-- Two Columns: Profile Details + Financial Ledgers -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- Left Column: Personal & Employment Profile -->
                <div class="space-y-6">
                    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 space-y-4">
                        <h3 class="font-bold text-gray-900 border-b border-gray-100 pb-2">{{ __('Staff Profile & Information') }}</h3>

                        <dl class="space-y-2.5 text-xs">
                            <div class="flex items-center justify-between">
                                <dt class="text-gray-500">{{ __('Employee Code') }}:</dt>
                                <dd class="font-mono font-bold text-indigo-700">{{ $employee->employee_code }}</dd>
                            </div>
                            <div class="flex items-center justify-between">
                                <dt class="text-gray-500">{{ __('Contract Type') }}:</dt>
                                <dd class="font-semibold text-gray-900 capitalize">{{ __($employee->employment_type) }}</dd>
                            </div>
                            <div class="flex items-center justify-between">
                                <dt class="text-gray-500">{{ __('Department') }}:</dt>
                                <dd class="font-medium text-gray-900">{{ $employee->department }}</dd>
                            </div>
                            <div class="flex items-center justify-between">
                                <dt class="text-gray-500">{{ __('Email') }}:</dt>
                                <dd class="text-gray-800">{{ $employee->user->email }}</dd>
                            </div>
                            <div class="flex items-center justify-between">
                                <dt class="text-gray-500">{{ __('Phone') }}:</dt>
                                <dd class="font-medium text-gray-900">{{ $employee->user->phone ?? '-' }}</dd>
                            </div>
                            <div class="flex items-center justify-between">
                                <dt class="text-gray-500">{{ __('Emergency Contact') }}:</dt>
                                <dd class="text-gray-800">{{ $employee->emergency_contact ?: '-' }}</dd>
                            </div>
                            <div class="flex items-center justify-between">
                                <dt class="text-gray-500">{{ __('Joining Date') }}:</dt>
                                <dd class="text-gray-800">{{ $employee->joining_date ? $employee->joining_date->format('d M, Y') : '-' }}</dd>
                            </div>
                            <div class="flex items-center justify-between">
                                <dt class="text-gray-500">{{ __('Payment Schedule') }}:</dt>
                                <dd class="text-gray-800 capitalize">{{ str_replace('_', ' ', $employee->payment_schedule) }}</dd>
                            </div>
                            <div class="flex items-center justify-between">
                                <dt class="text-gray-500">{{ __('Status') }}:</dt>
                                <dd>
                                    <span class="px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        {{ __($employee->status) }}
                                    </span>
                                </dd>
                            </div>
                        </dl>

                        @if($employee->address)
                            <div class="pt-3 border-t border-gray-100 text-xs text-gray-600">
                                <span class="font-semibold text-gray-500 block mb-0.5">{{ __('Address') }}:</span>
                                <p>{{ $employee->address }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Right Column: Tables (Video Billings, Payouts) -->
                <div class="lg:col-span-2 space-y-6">

                    <!-- Recent Video Billings Table -->
                    @if($employee->payment_type === 'per_second_video' || $employee->videoBillings->isNotEmpty())
                        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                            <div class="p-4 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
                                <h3 class="font-bold text-gray-900 text-sm flex items-center gap-2">
                                    <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                    {{ __('Video Billing History') }}
                                </h3>
                                <a href="{{ route('video-billings.create', ['employee_id' => $employee->id]) }}" class="text-xs font-bold text-indigo-600 hover:underline">
                                    {{ __('+ New Video Bill') }}
                                </a>
                            </div>

                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 text-left text-xs">
                                    <thead class="bg-gray-50 text-gray-500 font-semibold uppercase">
                                        <tr>
                                            <th class="py-2.5 px-3">{{ __('Billing Code') }}</th>
                                            <th class="py-2.5 px-3">{{ __('Video Title') }}</th>
                                            <th class="py-2.5 px-3">{{ __('Duration') }}</th>
                                            <th class="py-2.5 px-3">{{ __('Total Bill') }} ({{ __('BDT') }})</th>
                                            <th class="py-2.5 px-3">{{ __('Due') }}</th>
                                            <th class="py-2.5 px-3">{{ __('Payment') }}</th>
                                            <th class="py-2.5 px-3 text-right">{{ __('Action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100">
                                        @forelse($employee->videoBillings as $vbill)
                                            <tr class="hover:bg-gray-50/50">
                                                <td class="py-2.5 px-3 font-mono font-bold text-indigo-600">
                                                    {{ $vbill->billing_code }}
                                                </td>
                                                <td class="py-2.5 px-3">
                                                    <a href="{{ route('video-billings.show', $vbill) }}" class="font-bold text-gray-900 hover:text-indigo-600">
                                                        {{ $vbill->video_title }}
                                                    </a>
                                                </td>
                                                <td class="py-2.5 px-3">
                                                    {{ $vbill->duration_minutes }}m {{ $vbill->duration_seconds }}s ({{ number_format($vbill->total_seconds) }}s)
                                                </td>
                                                <td class="py-2.5 px-3 font-bold text-gray-900">
                                                    {{ __('BDT') }} {{ number_format($vbill->total_payable, 2) }}
                                                </td>
                                                <td class="py-2.5 px-3 font-bold text-rose-600">
                                                    {{ __('BDT') }} {{ number_format($vbill->due_amount, 2) }}
                                                </td>
                                                <td class="py-2.5 px-3">
                                                    <span class="px-1.5 py-0.5 rounded text-[10px] font-semibold {{ $vbill->payment_status === 'Paid' ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                                        {{ __($vbill->payment_status) }}
                                                    </span>
                                                </td>
                                                <td class="py-2.5 px-3 text-right">
                                                    <a href="{{ route('video-billings.show', $vbill) }}" class="text-indigo-600 hover:underline font-semibold">
                                                        {{ __('View') }} &rarr;
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="py-6 text-center text-gray-400">{{ __('No video billing records') }}</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif

                    <!-- Recent Payout Vouchers Table -->
                    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                        <div class="p-4 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
                            <h3 class="font-bold text-gray-900 text-sm flex items-center gap-2">
                                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                {{ __('Payout Vouchers & Money Receipts') }}
                            </h3>
                            <a href="{{ route('payouts.create', ['employee_id' => $employee->id]) }}" class="text-xs font-bold text-emerald-600 hover:underline">
                                {{ __('+ New Payout Voucher') }}
                            </a>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 text-left text-xs">
                                <thead class="bg-gray-50 text-gray-500 font-semibold uppercase">
                                    <tr>
                                        <th class="py-2.5 px-3">{{ __('Voucher No') }}</th>
                                        <th class="py-2.5 px-3">{{ __('Date') }}</th>
                                        <th class="py-2.5 px-3">{{ __('Category') }}</th>
                                        <th class="py-2.5 px-3">{{ __('Method') }}</th>
                                        <th class="py-2.5 px-3">{{ __('Paid') }} ({{ __('BDT') }})</th>
                                        <th class="py-2.5 px-3 text-right">{{ __('Receipt') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @forelse($employee->payouts as $payout)
                                        <tr class="hover:bg-gray-50/50">
                                            <td class="py-2.5 px-3 font-mono font-bold text-emerald-700">
                                                {{ $payout->voucher_no }}
                                            </td>
                                            <td class="py-2.5 px-3 text-gray-600">
                                                {{ $payout->payment_date->format('d M, Y') }}
                                            </td>
                                            <td class="py-2.5 px-3 capitalize">
                                                {{ str_replace('_', ' ', $payout->payout_type) }}
                                            </td>
                                            <td class="py-2.5 px-3">
                                                <span class="px-2 py-0.5 rounded font-semibold bg-gray-100 text-gray-800">
                                                    {{ $payout->payment_method }}
                                                </span>
                                            </td>
                                            <td class="py-2.5 px-3 font-bold text-gray-900">
                                                {{ __('BDT') }} {{ number_format($payout->paid_amount, 2) }}
                                            </td>
                                            <td class="py-2.5 px-3 text-right">
                                                <a href="{{ route('payouts.show', $payout) }}" class="text-emerald-700 font-semibold hover:underline">
                                                    {{ __('Money Receipt') }} &rarr;
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="py-6 text-center text-gray-400">{{ __('No payout vouchers found') }}</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </div>
</x-app-layout>
