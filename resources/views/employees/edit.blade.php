<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                    {{ __('Edit Employee Profile') }}: {{ $employee->user->name }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">{{ $employee->employee_code }} - {{ __('Details, designation, and rate updates') }}</p>
            </div>
            <a href="{{ route('employees.show', $employee) }}" class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-200 transition">
                <svg class="w-4 h-4 me-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                {{ __('Back to Profile') }}
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

            <form method="POST" action="{{ route('employees.update', $employee) }}" class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 sm:p-8 space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

                    <!-- Department -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('Department') }} <span class="text-rose-500">*</span></label>
                        <input type="text" name="department" required value="{{ old('department', $employee->department) }}"
                            class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <!-- Designation -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('Designation') }} <span class="text-rose-500">*</span></label>
                        <input type="text" name="designation" required value="{{ old('designation', $employee->designation) }}"
                            class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <!-- Employment Type -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('Employment Type') }} <span class="text-rose-500">*</span></label>
                        <select name="employment_type" required class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="permanent" {{ old('employment_type', $employee->employment_type) === 'permanent' ? 'selected' : '' }}>{{ __('Permanent (Full-Time)') }}</option>
                            <option value="contractual" {{ old('employment_type', $employee->employment_type) === 'contractual' ? 'selected' : '' }}>{{ __('Contractual') }}</option>
                            <option value="freelance" {{ old('employment_type', $employee->employment_type) === 'freelance' ? 'selected' : '' }}>{{ __('Freelance') }}</option>
                        </select>
                    </div>

                    <!-- Payment Type -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('Payment Model') }} <span class="text-rose-500">*</span></label>
                        <select name="payment_type" required class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="per_second_video" {{ old('payment_type', $employee->payment_type) === 'per_second_video' ? 'selected' : '' }}>{{ __('Per-Second Video Rate') }}</option>
                            <option value="monthly_salary" {{ old('payment_type', $employee->payment_type) === 'monthly_salary' ? 'selected' : '' }}>{{ __('Monthly Salary') }}</option>
                            <option value="hourly" {{ old('payment_type', $employee->payment_type) === 'hourly' ? 'selected' : '' }}>{{ __('Hourly Rate') }}</option>
                            <option value="per_task" {{ old('payment_type', $employee->payment_type) === 'per_task' ? 'selected' : '' }}>{{ __('Per Task') }}</option>
                        </select>
                    </div>

                    <!-- Salary or Rate -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('Remuneration Rate / Salary') }} ({{ __('BDT') }}) <span class="text-rose-500">*</span></label>
                        <input type="number" step="0.01" name="salary_or_rate" required value="{{ old('salary_or_rate', $employee->salary_or_rate) }}"
                            class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <!-- Payment Schedule -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('Payment Schedule') }} <span class="text-rose-500">*</span></label>
                        <select name="payment_schedule" required class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="monthly" {{ old('payment_schedule', $employee->payment_schedule) === 'monthly' ? 'selected' : '' }}>{{ __('Monthly') }}</option>
                            <option value="bi_weekly" {{ old('payment_schedule', $employee->payment_schedule) === 'bi_weekly' ? 'selected' : '' }}>{{ __('Bi-Weekly') }}</option>
                            <option value="per_work" {{ old('payment_schedule', $employee->payment_schedule) === 'per_work' ? 'selected' : '' }}>{{ __('Per Delivery') }}</option>
                        </select>
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('Staff Status') }} <span class="text-rose-500">*</span></label>
                        <select name="status" required class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="active" {{ old('status', $employee->status) === 'active' ? 'selected' : '' }}>{{ __('Active') }}</option>
                            <option value="inactive" {{ old('status', $employee->status) === 'inactive' ? 'selected' : '' }}>{{ __('Inactive') }}</option>
                            <option value="on_leave" {{ old('status', $employee->status) === 'on_leave' ? 'selected' : '' }}>{{ __('On Leave') }}</option>
                        </select>
                    </div>

                    <!-- Emergency Contact -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('Emergency Contact') }}</label>
                        <input type="text" name="emergency_contact" value="{{ old('emergency_contact', $employee->emergency_contact) }}"
                            class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <!-- Address -->
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('Address') }}</label>
                        <textarea name="address" rows="2" class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">{{ old('address', $employee->address) }}</textarea>
                    </div>

                </div>

                <div class="pt-4 border-t border-gray-100 flex items-center justify-end gap-3">
                    <a href="{{ route('employees.show', $employee) }}" class="px-5 py-2.5 bg-gray-100 text-gray-700 rounded-lg text-sm font-semibold hover:bg-gray-200 transition">
                        {{ __('Cancel') }}
                    </a>
                    <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white rounded-lg text-sm font-semibold hover:bg-indigo-700 shadow-sm transition">
                        {{ __('Save Changes') }}
                    </button>
                </div>
            </form>

        </div>
    </div>
</x-app-layout>
