<x-portal-layout>
    <x-slot name="title">{{ __('Submit Support Ticket') }}</x-slot>

    <div class="max-w-3xl mx-auto space-y-6">
        <!-- Back Link & Title -->
        <div class="flex items-center gap-4">
            <a href="{{ route('portal.tickets.index') }}" class="p-2 rounded-xl bg-white border border-slate-200 text-slate-500 hover:text-slate-900 shadow-2xs transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <div>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight">{{ __('Submit New Support Ticket') }}</h1>
                <p class="text-xs text-slate-500 mt-0.5">{{ __('Our technical developers and project managers will review and respond promptly.') }}</p>
            </div>
        </div>

        <!-- Form Card -->
        <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-2xs">
            <form action="{{ route('portal.tickets.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf

                <!-- Subject -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                        {{ __('Ticket Subject / Summary') }} <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" 
                           name="subject" 
                           value="{{ old('subject') }}" 
                           required 
                           placeholder="{{ __('e.g., Payment gateway checkout error on staging site') }}" 
                           class="w-full text-xs sm:text-sm rounded-xl border border-slate-200 px-3.5 py-2.5 text-slate-900 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    @error('subject')
                        <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- 2-Col Grid: Category & Priority -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            {{ __('Issue Category') }} <span class="text-rose-500">*</span>
                        </label>
                        <select name="issue_type" required class="w-full text-xs sm:text-sm rounded-xl border border-slate-200 px-3.5 py-2.5 text-slate-900 focus:ring-2 focus:ring-indigo-500">
                            <option value="Bug/Error" {{ old('issue_type') === 'Bug/Error' ? 'selected' : '' }}>{{ __('Bug / Technical Error') }}</option>
                            <option value="Website Issue" {{ old('issue_type') === 'Website Issue' ? 'selected' : '' }}>{{ __('Website / Server Down') }}</option>
                            <option value="Feature Request" {{ old('issue_type') === 'Feature Request' ? 'selected' : '' }}>{{ __('New Feature / Change Request') }}</option>
                            <option value="Technical Support" {{ old('issue_type') === 'Technical Support' ? 'selected' : '' }} selected>{{ __('General Technical Support') }}</option>
                            <option value="Billing Question" {{ old('issue_type') === 'Billing Question' ? 'selected' : '' }}>{{ __('Invoice / Billing Question') }}</option>
                            <option value="Other" {{ old('issue_type') === 'Other' ? 'selected' : '' }}>{{ __('Other Inquiries') }}</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            {{ __('Urgency / Priority') }} <span class="text-rose-500">*</span>
                        </label>
                        <select name="priority" required class="w-full text-xs sm:text-sm rounded-xl border border-slate-200 px-3.5 py-2.5 text-slate-900 focus:ring-2 focus:ring-indigo-500">
                            <option value="Low" {{ old('priority') === 'Low' ? 'selected' : '' }}>{{ __('Low — General question') }}</option>
                            <option value="Medium" {{ old('priority') === 'Medium' ? 'selected' : '' }} selected>{{ __('Medium — Normal operational request') }}</option>
                            <option value="High" {{ old('priority') === 'High' ? 'selected' : '' }}>{{ __('High — Important feature blocked') }}</option>
                            <option value="Critical" {{ old('priority') === 'Critical' ? 'selected' : '' }}>{{ __('Critical — Site is completely down/crashing') }}</option>
                        </select>
                    </div>
                </div>

                <!-- 2-Col Grid: Linked Project & URL -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            {{ __('Related Project (Optional)') }}
                        </label>
                        <select name="project_id" class="w-full text-xs sm:text-sm rounded-xl border border-slate-200 px-3.5 py-2.5 text-slate-900 focus:ring-2 focus:ring-indigo-500">
                            <option value="">{{ __('-- None / General Question --') }}</option>
                            @foreach($projects as $p)
                                <option value="{{ $p->id }}" {{ (old('project_id') == $p->id || request('project_id') == $p->id) ? 'selected' : '' }}>
                                    {{ $p->project_code }} - {{ $p->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            {{ __('Affected URL / Page Link (Optional)') }}
                        </label>
                        <input type="url" 
                               name="service_url" 
                               value="{{ old('service_url') }}" 
                               placeholder="https://example.com/checkout" 
                               class="w-full text-xs sm:text-sm rounded-xl border border-slate-200 px-3.5 py-2.5 text-slate-900 focus:ring-2 focus:ring-indigo-500">
                    </div>
                </div>

                <!-- Description -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                        {{ __('Detailed Description of Issue or Request') }} <span class="text-rose-500">*</span>
                    </label>
                    <textarea name="description" 
                              rows="6" 
                              required 
                              placeholder="{{ __('Please describe the steps to reproduce the issue, expected results, or exact instructions...') }}" 
                              class="w-full text-xs sm:text-sm rounded-xl border border-slate-200 p-3.5 text-slate-900 focus:ring-2 focus:ring-indigo-500">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- File Attachment -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                        {{ __('Attachment / Screenshot (Optional)') }}
                    </label>
                    <input type="file" 
                           name="attachment" 
                           class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition border border-slate-200 rounded-xl p-1.5">
                    <p class="text-[11px] text-slate-400 mt-1">{{ __('Supported formats: PNG, JPG, PDF, ZIP (Max: 10MB)') }}</p>
                </div>

                <!-- Submit Button -->
                <div class="pt-3 flex items-center justify-end gap-3 border-t border-slate-100">
                    <a href="{{ route('portal.tickets.index') }}" class="px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50 transition">
                        {{ __('Cancel') }}
                    </a>
                    <button type="submit" class="px-6 py-2.5 rounded-xl text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 shadow-md shadow-indigo-500/25 transition">
                        {{ __('Submit Ticket Now') }} &rarr;
                    </button>
                </div>

            </form>
        </div>
    </div>
</x-portal-layout>

