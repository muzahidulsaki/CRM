<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                    {{ __('New Employee Profile') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">{{ __('Set employee role, designation, contract, and remuneration model') }}</p>
            </div>
            <a href="{{ route('employees.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-200 transition">
                <svg class="w-4 h-4 me-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                {{ __('Back to List') }}
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            @if ($errors->any())
                <div class="mb-6 p-4 bg-rose-50 border-l-4 border-rose-500 text-rose-800 rounded-r-lg shadow-sm">
                    <div class="font-bold text-sm">{{ __('Please correct the errors:') }}</div>
                    <ul class="mt-1 list-disc list-inside text-xs">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('employees.store') }}" class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 sm:p-8 space-y-6">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

                    <!-- User Account -->
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('Select User Account') }} <span class="text-rose-500">*</span></label>
                        <select name="user_id" required class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">{{ __('Select User') }}</option>
                            @foreach($availableUsers as $usr)
                                <option value="{{ $usr->id }}" {{ old('user_id') == $usr->id ? 'selected' : '' }}>
                                    {{ $usr->name }} ({{ $usr->email }}) - {{ __('Role') }}: {{ $usr->role->name ?? 'User' }}
                                </option>
                            @endforeach
                        </select>
                        <p class="text-xs text-gray-500 mt-1">{{ __('Only users who do not already have an employee profile are listed here.') }}</p>
                    </div>

                    <!-- Department -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('Department') }} <span class="text-rose-500">*</span></label>
                        <input type="text" name="department" required value="{{ old('department') }}" placeholder="{{ __('e.g. Creative & Video, Web Development') }}"
                            class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <!-- Designation -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('Designation') }} <span class="text-rose-500">*</span></label>
                        <input type="text" name="designation" required value="{{ old('designation') }}" placeholder="{{ __('e.g. Senior Video Editor, Full-Stack Developer') }}"
                            class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <!-- Employment Type -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('Employment Type') }} <span class="text-rose-500">*</span></label>
                        <select name="employment_type" required class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="permanent" {{ old('employment_type') == 'permanent' ? 'selected' : '' }}>{{ __('Permanent (Full-Time)') }}</option>
                            <option value="contractual" {{ old('employment_type', 'contractual') == 'contractual' ? 'selected' : '' }}>{{ __('Contractual') }}</option>
                            <option value="freelance" {{ old('employment_type') == 'freelance' ? 'selected' : '' }}>{{ __('Freelance') }}</option>
                        </select>
                    </div>

                    <!-- Payment Type -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('Payment Model') }} <span class="text-rose-500">*</span></label>
                        <select name="payment_type" id="payment_type" required class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="per_second_video" {{ old('payment_type', 'per_second_video') == 'per_second_video' ? 'selected' : '' }}>{{ __('Per-Second Video Rate') }}</option>
                            <option value="monthly_salary" {{ old('payment_type') == 'monthly_salary' ? 'selected' : '' }}>{{ __('Monthly Salary') }}</option>
                            <option value="hourly" {{ old('payment_type') == 'hourly' ? 'selected' : '' }}>{{ __('Hourly Rate') }}</option>
                            <option value="per_task" {{ old('payment_type') == 'per_task' ? 'selected' : '' }}>{{ __('Per Task') }}</option>
                        </select>
                    </div>

                    <!-- Salary or Rate -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1" id="rate_label">{{ __('Remuneration Rate / Salary') }} ({{ __('BDT') }}) <span class="text-rose-500">*</span></label>
                        <input type="number" step="0.01" name="salary_or_rate" required value="{{ old('salary_or_rate', 8.00) }}" placeholder="e.g. 8.00 or 30000"
                            class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <!-- Payment Schedule -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('Payment Schedule') }} <span class="text-rose-500">*</span></label>
                        <select name="payment_schedule" required class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="monthly" {{ old('payment_schedule') == 'monthly' ? 'selected' : '' }}>{{ __('Monthly') }}</option>
                            <option value="bi_weekly" {{ old('payment_schedule') == 'bi_weekly' ? 'selected' : '' }}>{{ __('Bi-Weekly') }}</option>
                            <option value="per_work" {{ old('payment_schedule', 'per_work') == 'per_work' ? 'selected' : '' }}>{{ __('Per Delivery') }}</option>
                        </select>
                    </div>

                    <!-- Joining Date -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('Joining Date') }}</label>
                        <input type="date" name="joining_date" value="{{ old('joining_date', date('Y-m-d')) }}"
                            class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <!-- Emergency Contact -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('Emergency Contact') }}</label>
                        <input type="text" name="emergency_contact" value="{{ old('emergency_contact') }}" placeholder="01XXXXXXXXX"
                            class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <!-- Address -->
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('Address') }}</label>
                        <textarea name="address" rows="2" class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">{{ old('address') }}</textarea>
                    </div>

                </div>

                <div class="pt-4 border-t border-gray-100 flex items-center justify-end gap-3">
                    <a href="{{ route('employees.index') }}" class="px-5 py-2.5 bg-gray-100 text-gray-700 rounded-lg text-sm font-semibold hover:bg-gray-200 transition">
                        {{ __('Cancel') }}
                    </a>
                    <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white rounded-lg text-sm font-semibold hover:bg-indigo-700 shadow-sm transition">
                        {{ __('Create Profile') }}
                    </button>
                </div>
            </form>

        </div>
    </div>
</x-app-layout>
