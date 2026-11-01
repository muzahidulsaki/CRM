<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-extrabold text-2xl text-slate-900 tracking-tight leading-tight">
                    Edit Ads Campaign: {{ $adsReport->business_name }}
                </h2>
                <p class="text-xs text-slate-500 mt-1">Lead ID: {{ $adsReport->lead_code ?? 'N/A' }} &bull; Update live dollar costing, post links, or checks.</p>
            </div>
            <a href="{{ route('ads-reports.index') }}" class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition">
                &larr; Back to Ads Reports
            </a>
        </div>
    </x-slot>

    <div class="py-6 max-w-5xl mx-auto" x-data="{
        dailyCost: {{ $adsReport->daily_cost ?? 0 }},
        totalDollar: {{ $adsReport->total_dollar ?? 0 }},
        runningDollar: {{ $adsReport->running_dollar ?? 0 }},
        startDate: '{{ $adsReport->starting_date ? $adsReport->starting_date->format('Y-m-d') : '' }}',
        endDate: '{{ $adsReport->end_date ? $adsReport->end_date->format('Y-m-d') : '' }}',
        totalDays: {{ $adsReport->total_days ?? 0 }},
        totalDollarCost: {{ $adsReport->total_dollar_cost ?? 0 }},
        dollarDue: {{ $adsReport->dollar_due ?? 0 }},

        calculateMetrics() {
            if (this.startDate && this.endDate) {
                let start = new Date(this.startDate);
                let end = new Date(this.endDate);
                if (end >= start) {
                    let diffTime = Math.abs(end - start);
                    this.totalDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
                }
            }
            this.totalDollarCost = (parseFloat(this.dailyCost || 0) * parseInt(this.totalDays || 0)).toFixed(2);
            let tot$ = parseFloat(this.totalDollar || 0);
            let run$ = parseFloat(this.runningDollar || 0);
            this.dollarDue = Math.max(0, tot$ - run$).toFixed(2);
        }
    }">
        <form action="{{ route('ads-reports.update', $adsReport->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Section 1: Client & Lead Association -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-2xs space-y-4">
                <h3 class="text-sm font-black text-slate-900 border-b border-slate-100 pb-2 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-indigo-600"></span>
                    1. Client & Lead Information
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Lead ID</label>
                        <input type="text" name="lead_code" value="{{ old('lead_code', $adsReport->lead_code) }}" class="w-full text-xs px-3 py-2 border border-slate-300 rounded-xl outline-none font-bold">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Business Name <span class="text-rose-500">*</span></label>
                        <input type="text" name="business_name" value="{{ old('business_name', $adsReport->business_name) }}" required class="w-full text-xs px-3 py-2 border border-slate-300 rounded-xl outline-none font-semibold">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Business Category</label>
                        <input type="text" name="business_category" value="{{ old('business_category', $adsReport->business_category) }}" class="w-full text-xs px-3 py-2 border border-slate-300 rounded-xl outline-none">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">WhatsApp Number</label>
                        <input type="text" name="whatsapp" value="{{ old('whatsapp', $adsReport->whatsapp) }}" class="w-full text-xs px-3 py-2 border border-slate-300 rounded-xl outline-none font-mono">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Order Source</label>
                        <input type="text" name="order_source" value="{{ old('order_source', $adsReport->order_source) }}" class="w-full text-xs px-3 py-2 border border-slate-300 rounded-xl outline-none">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Campaign Activity Status <span class="text-rose-500">*</span></label>
                        <select name="activity" required class="w-full text-xs px-3 py-2 border border-slate-300 rounded-xl outline-none font-bold">
                            <option value="Active" {{ $adsReport->activity === 'Active' ? 'selected' : '' }}>Active</option>
                            <option value="Paused" {{ $adsReport->activity === 'Paused' ? 'selected' : '' }}>Paused</option>
                            <option value="Completed" {{ $adsReport->activity === 'Completed' ? 'selected' : '' }}>Completed</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Section 2: Ad Creative & Post Details -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-2xs space-y-4">
                <h3 class="text-sm font-black text-slate-900 border-b border-slate-100 pb-2 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-blue-600"></span>
                    2. Ads Post Link & Creative Details
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Ads Post Link</label>
                        <input type="url" name="ads_post_link" value="{{ old('ads_post_link', $adsReport->ads_post_link) }}" class="w-full text-xs px-3 py-2 border border-slate-300 rounded-xl outline-none font-mono">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Order Month</label>
                        <input type="text" name="order_month" value="{{ old('order_month', $adsReport->order_month) }}" class="w-full text-xs px-3 py-2 border border-slate-300 rounded-xl outline-none">
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block font-bold text-slate-700 mb-1">Order Details</label>
                        <textarea name="order_details" rows="2" class="w-full text-xs px-3 py-2 border border-slate-300 rounded-xl outline-none">{{ old('order_details', $adsReport->order_details) }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Section 3: Schedule & Duration -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-2xs space-y-4">
                <h3 class="text-sm font-black text-slate-900 border-b border-slate-100 pb-2 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-600"></span>
                    3. Schedule & Duration
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 text-xs">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Order Date</label>
                        <input type="date" name="order_date" value="{{ old('order_date', $adsReport->order_date ? $adsReport->order_date->format('Y-m-d') : '') }}" class="w-full text-xs px-3 py-2 border border-slate-300 rounded-xl outline-none">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Starting Date</label>
                        <input type="date" name="starting_date" x-model="startDate" @change="calculateMetrics()" class="w-full text-xs px-3 py-2 border border-slate-300 rounded-xl outline-none">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">End Date</label>
                        <input type="date" name="end_date" x-model="endDate" @change="calculateMetrics()" class="w-full text-xs px-3 py-2 border border-slate-300 rounded-xl outline-none">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Total Days</label>
                        <input type="number" name="total_days" x-model="totalDays" min="0" class="w-full text-xs px-3 py-2 border border-slate-300 rounded-xl outline-none bg-slate-50 font-bold">
                    </div>
                </div>
            </div>

            <!-- Section 4: Budget, Dollar Costing & Calculations -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-2xs space-y-4">
                <h3 class="text-sm font-black text-slate-900 border-b border-slate-100 pb-2 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                    4. Budget & Live Dollar Costing
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 text-xs">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Total Amount (BDT ৳)</label>
                        <input type="number" step="0.01" name="total_amount" value="{{ old('total_amount', $adsReport->total_amount) }}" class="w-full text-xs px-3 py-2 border border-slate-300 rounded-xl outline-none font-bold">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Total Dollar ($)</label>
                        <input type="number" step="0.01" name="total_dollar" x-model="totalDollar" @input="calculateMetrics()" class="w-full text-xs px-3 py-2 border border-slate-300 rounded-xl outline-none font-black text-blue-600">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Lifetime Budget ($)</label>
                        <input type="number" step="0.01" name="lifetime_budget" value="{{ old('lifetime_budget', $adsReport->lifetime_budget) }}" class="w-full text-xs px-3 py-2 border border-slate-300 rounded-xl outline-none font-semibold text-slate-800">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Daily Cost ($)</label>
                        <input type="number" step="0.01" name="daily_cost" x-model="dailyCost" @input="calculateMetrics()" class="w-full text-xs px-3 py-2 border border-slate-300 rounded-xl outline-none font-bold">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Previous Dollar ($)</label>
                        <input type="number" step="0.01" name="previous_dollar" value="{{ old('previous_dollar', $adsReport->previous_dollar) }}" class="w-full text-xs px-3 py-2 border border-slate-300 rounded-xl outline-none">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Running Dollar ($)</label>
                        <input type="number" step="0.01" name="running_dollar" x-model="runningDollar" @input="calculateMetrics()" class="w-full text-xs px-3 py-2 border border-slate-300 rounded-xl outline-none font-bold text-indigo-600">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Total $ Cost</label>
                        <input type="number" step="0.01" name="total_dollar_cost" x-model="totalDollarCost" class="w-full text-xs px-3 py-2 border border-slate-300 rounded-xl outline-none bg-slate-50 font-bold">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">$ Due</label>
                        <input type="number" step="0.01" name="dollar_due" x-model="dollarDue" class="w-full text-xs px-3 py-2 border border-slate-300 rounded-xl outline-none bg-slate-50 font-black text-rose-600">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Today Check Status</label>
                        <select name="today_check" class="w-full text-xs px-3 py-2 border border-slate-300 rounded-xl outline-none font-bold">
                            <option value="Pending" {{ $adsReport->today_check === 'Pending' ? 'selected' : '' }}>Pending Check</option>
                            <option value="Checked" {{ $adsReport->today_check === 'Checked' ? 'selected' : '' }}>Checked</option>
                            <option value="Needs Attention" {{ $adsReport->today_check === 'Needs Attention' ? 'selected' : '' }}>Needs Attention</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex items-center justify-end gap-3 pt-2">
                <a href="{{ route('ads-reports.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl transition shadow-sm">
                    Update Ads Campaign
                </button>
            </div>
        </form>
    </div>
</x-app-layout>

