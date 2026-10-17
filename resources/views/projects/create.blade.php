<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                    {{ __('New Project Setup') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">{{ __('Link with sales orders to initialize project team and track progress') }}</p>
            </div>
            <a href="{{ route('projects.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-200 transition">
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
                    <ul class="mt-2 list-disc list-inside text-xs space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('projects.store') }}" class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 sm:p-8 space-y-6">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

                    <!-- Order Selection -->
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('Select Sales Order') }} <span class="text-rose-500">*</span></label>
                        <select name="order_id" required class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">{{ __('Select Order') }}</option>
                            @if($preselectedOrder)
                                <option value="{{ $preselectedOrder->id }}" selected>
                                    {{ $preselectedOrder->order_code }} - {{ $preselectedOrder->title }} ({{ $preselectedOrder->customer?->name }})
                                </option>
                            @endif
                            @foreach($orders as $ord)
                                @if(!$preselectedOrder || $preselectedOrder->id !== $ord->id)
                                    <option value="{{ $ord->id }}" {{ old('order_id') == $ord->id ? 'selected' : '' }}>
                                        {{ $ord->order_code }} - {{ $ord->title }} ({{ $ord->customer?->name }})
                                    </option>
                                @endif
                            @endforeach
                        </select>
                        <p class="text-xs text-gray-500 mt-1">{{ __('Only confirmed orders without an active project are shown here.') }}</p>
                    </div>

                    <!-- Project Name -->
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('Project Name') }} <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" required value="{{ old('name', $preselectedOrder?->title) }}" placeholder="{{ __('e.g. E-Commerce Platform & Payment Integration') }}"
                            class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <!-- Category -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('Category') }} <span class="text-rose-500">*</span></label>
                        <select name="category" required class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            @foreach($serviceCategories as $cat)
                                <option value="{{ $cat }}" {{ old('category', $preselectedOrder?->service_category) == $cat ? 'selected' : '' }}>
                                    {{ __($cat) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Project Manager -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('Project Manager') }}</label>
                        <select name="project_manager_id" class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">{{ __('Select PM') }}</option>
                            @foreach($projectManagers as $pm)
                                <option value="{{ $pm->id }}" {{ old('project_manager_id', auth()->id()) == $pm->id ? 'selected' : '' }}>
                                    {{ $pm->name }} ({{ $pm->role->name ?? 'Staff' }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Start Date -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('Start Date') }}</label>
                        <input type="date" name="start_date" value="{{ old('start_date', date('Y-m-d')) }}"
                            class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <!-- Deadline -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('Deadline') }}</label>
                        <input type="date" name="deadline" value="{{ old('deadline', $preselectedOrder?->expected_delivery_date?->format('Y-m-d')) }}"
                            class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('Initial Status') }} <span class="text-rose-500">*</span></label>
                        <select name="status" required class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            @foreach(['Pending', 'Planning', 'In Development', 'In QA', 'Client Review', 'Completed', 'Delivered'] as $st)
                                <option value="{{ $st }}" {{ old('status', 'Planning') == $st ? 'selected' : '' }}>{{ __($st) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Git Repo -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('Git Repository URL (Optional)') }}</label>
                        <input type="url" name="git_repository" value="{{ old('git_repository') }}" placeholder="https://github.com/organization/project"
                            class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <!-- Live URL -->
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('Live Preview / Test URL (Optional)') }}</label>
                        <input type="url" name="live_url" value="{{ old('live_url') }}" placeholder="https://demo.example.com/client-project"
                            class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <!-- Description -->
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('Project Scope & Description') }}</label>
                        <textarea name="description" rows="3" class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">{{ old('description') }}</textarea>
                    </div>

                </div>

                <div class="pt-4 border-t border-gray-100 flex items-center justify-end gap-3">
                    <a href="{{ route('projects.index') }}" class="px-5 py-2.5 bg-gray-100 text-gray-700 rounded-lg text-sm font-semibold hover:bg-gray-200 transition">
                        {{ __('Cancel') }}
                    </a>
                    <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white rounded-lg text-sm font-semibold hover:bg-indigo-700 shadow-sm transition">
                        {{ __('Create Project') }}
                    </button>
                </div>
            </form>

        </div>
    </div>
</x-app-layout>
