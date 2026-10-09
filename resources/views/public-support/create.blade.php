<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Customer Support Portal | Projukti Sheba</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 font-sans text-slate-800 antialiased min-h-screen flex flex-col justify-between">
    <!-- Header -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-30">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 py-4 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-xl bg-blue-600 flex items-center justify-center text-white font-bold text-xl shadow-md">
                    PS
                </div>
                <div>
                    <h1 class="text-lg font-bold text-slate-900 leading-tight">Projukti Sheba</h1>
                    <p class="text-xs text-slate-500 font-medium">Client Support & Helpdesk</p>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('public-support.track') }}" class="inline-flex items-center px-4 py-2 border border-blue-600 text-sm font-semibold rounded-lg text-blue-600 hover:bg-blue-50 transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    Track Existing Ticket
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-4xl mx-auto px-4 sm:px-6 py-10 w-full">
        <!-- Hero Section -->
        <div class="text-center max-w-2xl mx-auto mb-10">
            <span class="inline-block px-3 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded-full uppercase tracking-wider mb-3">
                We're Here to Help
            </span>
            <h2 class="text-3xl font-extrabold text-slate-900 sm:text-4xl">
                Submit a Support Request
            </h2>
            <p class="mt-3 text-slate-600 text-sm sm:text-base">
                Have an issue with your website, software, or server? Fill out the details below and our dedicated technical engineers will investigate immediately.
            </p>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-50 border border-emerald-300 rounded-xl text-emerald-800 text-sm flex items-start">
                <svg class="w-5 h-5 mr-3 text-emerald-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <div>
                    <h4 class="font-bold">Request Submitted!</h4>
                    <p class="mt-1">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 p-4 bg-rose-50 border border-rose-300 rounded-xl text-rose-800 text-sm">
                <h4 class="font-bold flex items-center mb-2">
                    <svg class="w-5 h-5 mr-2 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    Please correct the following errors:
                </h4>
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Support Request Form -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <form action="{{ route('public-support.store') }}" method="POST" enctype="multipart/form-data" class="p-6 sm:p-8 space-y-6">
                @csrf

                <!-- Section 1: Contact Details -->
                <div>
                    <h3 class="text-base font-bold text-slate-900 border-b border-slate-100 pb-2 mb-4 flex items-center">
                        <span class="w-6 h-6 rounded-full bg-blue-100 text-blue-600 text-xs font-bold flex items-center justify-center mr-2">1</span>
                        Your Contact Information
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Full Name <span class="text-rose-500">*</span></label>
                            <input type="text" name="contact_name" value="{{ old('contact_name') }}" required
                                class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                                placeholder="e.g. Tanvir Ahmed">
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Company / Organization</label>
                            <input type="text" name="company_name" value="{{ old('company_name') }}"
                                class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                                placeholder="e.g. Apex Software Ltd">
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Phone Number <span class="text-rose-500">*</span></label>
                            <input type="text" name="phone" value="{{ old('phone') }}" required
                                class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                                placeholder="017XXXXXXXX">
                            <p class="text-[11px] text-slate-500 mt-1">Existing clients will be automatically identified.</p>
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Email Address</label>
                            <input type="email" name="email" value="{{ old('email') }}"
                                class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                                placeholder="tanvir@company.com">
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">WhatsApp Number (Optional)</label>
                            <input type="text" name="whatsapp" value="{{ old('whatsapp') }}"
                                class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                                placeholder="01XXXXXXXXX">
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Website or Software URL</label>
                            <input type="url" name="service_url" value="{{ old('service_url') }}"
                                class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                                placeholder="https://yourwebsite.com">
                        </div>
                    </div>
                </div>

                <!-- Section 2: Ticket Specifics -->
                <div>
                    <h3 class="text-base font-bold text-slate-900 border-b border-slate-100 pb-2 mb-4 flex items-center">
                        <span class="w-6 h-6 rounded-full bg-blue-100 text-blue-600 text-xs font-bold flex items-center justify-center mr-2">2</span>
                        Issue Details
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Issue Category <span class="text-rose-500">*</span></label>
                            <select name="issue_type" required class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                                @foreach($issueTypes as $type)
                                    <option value="{{ $type }}" {{ old('issue_type') === $type ? 'selected' : '' }}>{{ $type }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Priority Level <span class="text-rose-500">*</span></label>
                            <select name="priority" required class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                                @foreach($priorities as $pri)
                                    <option value="{{ $pri }}" {{ (old('priority', 'Medium') === $pri) ? 'selected' : '' }}>
                                        {{ $pri }} @if($pri === 'Critical') (System Down) @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Subject / Problem Title <span class="text-rose-500">*</span></label>
                        <input type="text" name="subject" value="{{ old('subject') }}" required
                            class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                            placeholder="e.g. Database connection failure on login page">
                    </div>

                    <div class="mb-4">
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Detailed Description of Problem <span class="text-rose-500">*</span></label>
                        <textarea name="description" rows="5" required
                            class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                            placeholder="Please provide steps to reproduce, exact error messages shown, and any other relevant context...">{{ old('description') }}</textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Attach Screenshot or Document (Optional)</label>
                        <input type="file" name="attachment"
                            class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                        <p class="text-[11px] text-slate-500 mt-1">Allowed formats: JPG, PNG, PDF, ZIP, DOCX (Max 10MB)</p>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                    <p class="text-xs text-slate-500">
                        Our technical support SLA response time is typically within 2-4 hours.
                    </p>
                    <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm rounded-lg shadow-md hover:shadow-lg transition">
                        Submit Support Ticket
                    </button>
                </div>
            </form>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 py-6 text-center text-xs text-slate-500">
        <p>&copy; {{ date('Y') }} Projukti Sheba. All rights reserved. Secure Support & Helpdesk Platform.</p>
    </footer>
</body>
</html>
