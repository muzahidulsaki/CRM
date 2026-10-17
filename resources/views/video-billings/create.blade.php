<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                    {{ __('New Video Billing Entry') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">{{ __('Per-second video editor tracking and automated compensation calculation') }}</p>
            </div>
            <a href="{{ route('video-billings.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-200 transition">
                <svg class="w-4 h-4 me-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                {{ __('Back to List') }}
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            @if($preselectedTask)
                <div class="mb-6 p-4 bg-indigo-50 border border-indigo-200 rounded-xl flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <span class="p-2 bg-indigo-600 text-white rounded-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        </span>
                        <div>
                            <div class="text-sm font-bold text-indigo-900">{{ __('Creating billing from task') }}: {{ $preselectedTask->task_code }} ({{ $preselectedTask->title }})</div>
                            <div class="text-xs text-indigo-700">{{ __('Project') }}: {{ $preselectedTask->project?->name }} | {{ __('Staff') }}: {{ $preselectedTask->assignedUser?->name }}</div>
                        </div>
                    </div>
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 p-4 bg-rose-50 border-l-4 border-rose-500 text-rose-800 rounded-r-lg shadow-sm">
                    <div class="font-bold text-sm">{{ __('Please fix the following errors:') }}</div>
                    <ul class="mt-1 list-disc list-inside text-xs">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('video-billings.store') }}" class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 sm:p-8 space-y-6">
                @csrf

                @if($preselectedTask)
                    <input type="hidden" name="task_id" value="{{ $preselectedTask->id }}">
                    <input type="hidden" name="project_id" value="{{ $preselectedTask->project_id }}">
                    <input type="hidden" name="customer_id" value="{{ $preselectedTask->project?->customer_id }}">
                @endif

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

                    <!-- Employee Select -->
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('Select Video Editor') }} <span class="text-rose-500">*</span></label>
                        <select name="employee_id" id="employee_id" required class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">{{ __('Select Editor') }}</option>
                            @foreach($employees as $emp)
                                <option value="{{ $emp->id }}"
                                    data-rate="{{ $emp->salary_or_rate > 0 ? $emp->salary_or_rate : $defaultRate }}"
                                    {{ (old('employee_id') == $emp->id || ($preselectedTask && $preselectedTask->assignedUser?->employee?->id == $emp->id)) ? 'selected' : '' }}>
                                    {{ $emp->user->name }} ({{ $emp->designation }}) - {{ __('Rate') }}: BDT {{ number_format($emp->salary_or_rate > 0 ? $emp->salary_or_rate : $defaultRate, 2) }}/{{ __('sec') }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Video Title -->
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('Video Title') }} <span class="text-rose-500">*</span></label>
                        <input type="text" name="video_title" required value="{{ old('video_title', $preselectedTask?->title) }}" placeholder="{{ __('e.g. Facebook Promo Reels - Batch 1') }}"
                            class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <!-- Video URL -->
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('Deliverable URL (Google Drive / YouTube / Frame.io)') }}</label>
                        <input type="url" name="video_url" value="{{ old('video_url') }}" placeholder="https://drive.google.com/..."
                            class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <!-- Duration: Minutes -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('Duration: Minutes') }} <span class="text-rose-500">*</span></label>
                        <input type="number" name="duration_minutes" id="duration_minutes" required min="0" value="{{ old('duration_minutes', 0) }}"
                            class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <!-- Duration: Seconds -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('Duration: Seconds') }} <span class="text-rose-500">*</span></label>
                        <input type="number" name="duration_seconds" id="duration_seconds" required min="0" max="59" value="{{ old('duration_seconds', 0) }}"
                            class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <!-- Rate Per Second -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('Rate per Second (BDT)') }} <span class="text-rose-500">*</span></label>
                        <input type="number" step="0.01" name="rate_per_second" id="rate_per_second" required value="{{ old('rate_per_second', $defaultRate) }}"
                            class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                        <p class="text-xs text-gray-400 mt-1">{{ __('Default rate') }}: BDT {{ number_format($defaultRate, 2) }}/{{ __('sec') }}</p>
                    </div>

                    <!-- Number of Videos -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('Number of Videos (Quantity)') }} <span class="text-rose-500">*</span></label>
                        <input type="number" name="number_of_videos" id="number_of_videos" required min="1" value="{{ old('number_of_videos', 1) }}"
                            class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <!-- LIVE CALCULATION DISPLAY CARD -->
                    <div class="sm:col-span-2 p-5 bg-gradient-to-br from-indigo-50 to-blue-50 border border-indigo-200 rounded-2xl shadow-sm space-y-3">
                        <div class="flex items-center justify-between text-xs font-bold text-indigo-900 uppercase tracking-wider">
                            <span>{{ __('Live Billing Calculator') }}</span>
                            <span class="text-indigo-600 bg-white px-2 py-0.5 rounded shadow-sm">{{ __('Auto Computed') }}</span>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 pt-1">
                            <div class="bg-white p-3 rounded-xl border border-indigo-100 text-center">
                                <div class="text-xs text-gray-500">{{ __('Total Seconds') }}</div>
                                <div id="display_total_seconds" class="text-xl font-bold text-indigo-900 mt-1">0 {{ __('sec') }}</div>
                            </div>
                            <div class="bg-white p-3 rounded-xl border border-indigo-100 text-center">
                                <div class="text-xs text-gray-500">{{ __('Calculation Formula') }}</div>
                                <div id="display_formula" class="text-xs font-mono font-medium text-gray-700 mt-2">0 {{ __('sec') }} &times; BDT 0.00</div>
                            </div>
                            <div class="bg-white p-3 rounded-xl border border-indigo-100 text-center bg-emerald-50/50">
                                <div class="text-xs font-semibold text-emerald-700">{{ __('Total Payable') }}</div>
                                <div id="display_total_payable" class="text-2xl font-black text-emerald-800 mt-0.5">BDT 0.00</div>
                            </div>
                        </div>
                    </div>

                    <!-- Project Selection -->
                    @if(!$preselectedTask)
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('Project (Optional)') }}</label>
                            <select name="project_id" class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">{{ __('Select Project') }}</option>
                                @foreach($projects as $proj)
                                    <option value="{{ $proj->id }}" {{ old('project_id') == $proj->id ? 'selected' : '' }}>
                                        {{ $proj->project_code }} - {{ $proj->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Customer Selection -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('Client (Optional)') }}</label>
                            <select name="customer_id" class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">{{ __('Select Customer') }}</option>
                                @foreach($customers as $cust)
                                    <option value="{{ $cust->id }}" {{ old('customer_id') == $cust->id ? 'selected' : '' }}>
                                        {{ $cust->name }} ({{ $cust->company_name ?? $cust->phone }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <!-- Work Dates -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('Work Start Date') }}</label>
                        <input type="date" name="work_start_date" value="{{ old('work_start_date') }}"
                            class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('Work End Date') }}</label>
                        <input type="date" name="work_end_date" value="{{ old('work_end_date', date('Y-m-d')) }}"
                            class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <!-- Notes -->
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('Notes') }}</label>
                        <textarea name="notes" rows="2" placeholder="{{ __('Video editing notes or instructions...') }}"
                            class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">{{ old('notes') }}</textarea>
                    </div>

                </div>

                <div class="pt-4 border-t border-gray-100 flex items-center justify-end gap-3">
                    <a href="{{ route('video-billings.index') }}" class="px-5 py-2.5 bg-gray-100 text-gray-700 rounded-lg text-sm font-semibold hover:bg-gray-200 transition">
                        {{ __('Cancel') }}
                    </a>
                    <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white rounded-lg text-sm font-semibold hover:bg-indigo-700 shadow-sm transition">
                        {{ __('Confirm Billing Entry') }}
                    </button>
                </div>
            </form>

        </div>
    </div>

    <!-- Live Calculator Script -->
    <script>
        function recalculate() {
            const mins = parseInt(document.getElementById('duration_minutes').value) || 0;
            const secs = parseInt(document.getElementById('duration_seconds').value) || 0;
            const rate = parseFloat(document.getElementById('rate_per_second').value) || 0;
            const qty = parseInt(document.getElementById('number_of_videos').value) || 1;

            const totalSeconds = (mins * 60) + secs;
            const totalPayable = (totalSeconds * rate * qty);

            document.getElementById('display_total_seconds').innerText = totalSeconds.toLocaleString() + ' {{ __("sec") }}';
            document.getElementById('display_formula').innerText = `${totalSeconds} {{ __("sec") }} × BDT ${rate.toFixed(2)}` + (qty > 1 ? ` × ${qty}` : '');
            document.getElementById('display_total_payable').innerText = 'BDT ' + totalPayable.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
        }

        // Auto update rate when employee selection changes
        document.getElementById('employee_id').addEventListener('change', function() {
            const selected = this.options[this.selectedIndex];
            const rate = selected.getAttribute('data-rate');
            if (rate) {
                document.getElementById('rate_per_second').value = parseFloat(rate).toFixed(2);
                recalculate();
            }
        });

        document.getElementById('duration_minutes').addEventListener('input', recalculate);
        document.getElementById('duration_seconds').addEventListener('input', recalculate);
        document.getElementById('rate_per_second').addEventListener('input', recalculate);
        document.getElementById('number_of_videos').addEventListener('input', recalculate);

        // Run once on initial load
        recalculate();
    </script>
</x-app-layout>
