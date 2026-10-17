<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight flex items-center gap-2">
                    <svg class="w-7 h-7 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    {{ __('Quotation Management') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">{{ __('Proposed pricing list and invoice tracking for clients') }}</p>
            </div>
            <a href="{{ route('quotations.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 shadow-sm transition">
                <svg class="w-4 h-4 me-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                {{ __('New Quotation') }}
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Quotation Metrics -->
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3">
                <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm text-center">
                    <div class="text-xs font-semibold text-gray-500 uppercase">{{ __('Total Quotations') }}</div>
                    <div class="text-2xl font-bold text-gray-900 mt-1">{{ $metrics['total'] }}</div>
                </div>

                <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm text-center">
                    <div class="text-xs font-semibold text-indigo-600 uppercase">{{ __('Draft') }}</div>
                    <div class="text-2xl font-bold text-indigo-700 mt-1">{{ $metrics['draft'] }}</div>
                </div>

                <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm text-center">
                    <div class="text-xs font-semibold text-amber-600 uppercase">{{ __('Sent') }}</div>
                    <div class="text-2xl font-bold text-amber-700 mt-1">{{ $metrics['sent'] }}</div>
                </div>

                <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm text-center">
                    <div class="text-xs font-semibold text-emerald-600 uppercase">{{ __('Accepted') }}</div>
                    <div class="text-2xl font-bold text-emerald-700 mt-1">{{ $metrics['accepted'] }}</div>
                </div>

                <div class="bg-emerald-50/70 p-4 rounded-xl border border-emerald-200 shadow-sm text-center col-span-2 sm:col-span-1">
                    <div class="text-xs font-semibold text-emerald-800 uppercase">{{ __('Accepted Value') }}</div>
                    <div class="text-xl font-bold text-emerald-900 mt-1">{{ __('BDT') }} {{ number_format($metrics['total_value']) }}</div>
                </div>
            </div>

            <!-- Quotations Table -->
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-left text-sm">
                        <thead class="bg-gray-50 text-gray-500 font-semibold uppercase text-xs">
                            <tr>
                                <th class="py-3.5 px-4">{{ __('Quotation Code') }}</th>
                                <th class="py-3.5 px-4">{{ __('Client / Lead') }}</th>
                                <th class="py-3.5 px-4">{{ __('Date') }}</th>
                                <th class="py-3.5 px-4">{{ __('Valid Until') }}</th>
                                <th class="py-3.5 px-4">{{ __('Total Amount') }} ({{ __('BDT') }})</th>
                                <th class="py-3.5 px-4">{{ __('Status') }}</th>
                                <th class="py-3.5 px-4 text-right">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($quotations as $quotation)
                                <tr class="hover:bg-gray-50/60 transition">
                                    <td class="py-3.5 px-4">
                                        <a href="{{ route('quotations.show', $quotation) }}" class="font-bold text-indigo-600 hover:text-indigo-800">
                                            {{ $quotation->quotation_code }}
                                        </a>
                                    </td>
                                    <td class="py-3.5 px-4">
                                        @if($quotation->customer)
                                            <div class="font-medium text-gray-900">{{ $quotation->customer->name }}</div>
                                            <div class="text-xs text-emerald-700 font-semibold">{{ $quotation->customer->customer_code }}</div>
                                        @elseif($quotation->lead)
                                            <div class="font-medium text-gray-900">{{ $quotation->lead->name }}</div>
                                            <div class="text-xs text-indigo-600">{{ $quotation->lead->lead_code }}</div>
                                        @else
                                            <span class="text-gray-400 italic">{{ __('Unassigned') }}</span>
                                        @endif
                                    </td>
                                    <td class="py-3.5 px-4 text-xs text-gray-600">
                                        {{ $quotation->quotation_date->format('d M, Y') }}
                                    </td>
                                    <td class="py-3.5 px-4 text-xs text-gray-600">
                                        {{ $quotation->valid_until ? $quotation->valid_until->format('d M, Y') : __('No Expiry') }}
                                    </td>
                                    <td class="py-3.5 px-4 font-bold text-gray-900">
                                        {{ __('BDT') }} {{ number_format($quotation->total_amount, 2) }}
                                    </td>
                                    <td class="py-3.5 px-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $quotation->status === 'Accepted' ? 'bg-emerald-100 text-emerald-800' : ($quotation->status === 'Rejected' ? 'bg-rose-100 text-rose-800' : 'bg-gray-100 text-gray-800') }}">
                                            {{ __($quotation->status) }}
                                        </span>
                                    </td>
                                    <td class="py-3.5 px-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('quotations.show', $quotation) }}" class="text-indigo-600 hover:text-indigo-800 font-semibold text-xs bg-indigo-50 px-2.5 py-1 rounded hover:bg-indigo-100 transition">
                                                {{ __('Print / View') }}
                                            </a>
                                            <a href="{{ route('quotations.edit', $quotation) }}" class="text-gray-500 hover:text-amber-600 p-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-10 text-gray-400 text-xs">
                                        {{ __('No quotations found.') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($quotations->hasPages())
                    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                        {{ $quotations->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
