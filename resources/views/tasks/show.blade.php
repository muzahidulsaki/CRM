<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <div class="flex items-center gap-2">
                    <span class="text-xs font-mono font-bold bg-indigo-100 text-indigo-700 px-2.5 py-1 rounded-lg">
                        {{ $task->task_code }}
                    </span>
                    <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                        {{ $task->title }}
                    </h2>
                </div>
                <div class="flex items-center gap-2 text-xs text-gray-500 mt-1">
                    <span>প্রজেক্ট: <a href="{{ route('projects.show', $task->project) }}" class="font-bold text-indigo-600 hover:underline">{{ $task->project->name }}</a></span>
                    <span>•</span>
                    <span>ক্লায়েন্ট: <strong class="text-gray-700">{{ $task->project->customer?->name }}</strong></span>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('projects.show', $task->project) }}" class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm font-semibold hover:bg-gray-200 transition">
                    প্রজেক্টে ফিরে যান
                </a>
                <a href="{{ route('tasks.kanban', ['project_id' => $task->project_id]) }}" class="inline-flex items-center px-4 py-2 bg-indigo-50 text-indigo-700 border border-indigo-200 rounded-lg text-sm font-semibold hover:bg-indigo-100 transition">
                    কানবান বোর্ড
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Alerts -->
            @if(session('success'))
                <div class="p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 rounded-r-lg shadow-sm flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        <span class="font-medium text-sm">{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            @if ($errors->any())
                <div class="p-4 bg-rose-50 border-l-4 border-rose-500 text-rose-800 rounded-r-lg shadow-sm">
                    <div class="font-bold text-sm">ত্রুটি সংশোধন করুন:</div>
                    <ul class="mt-1 list-disc list-inside text-xs">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- 6-Stage Visual Stepper Lifecycle -->
            @php
                $stages = ['Pending', 'Working', 'Completed', 'Checked', 'Delivered'];
                $isReworking = ($task->status === 'Reworking');
                $currentIndex = array_search($task->status, $stages);
                if ($currentIndex === false && $isReworking) {
                    $currentIndex = 1; // Between working and completed
                }
            @endphp
            <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm">
                <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-4">টাস্ক লাইফসাইকেল ট্র্যাকার (Task Lifecycle)</div>
                <div class="flex items-center justify-between flex-wrap gap-2">
                    @foreach(['Pending' => 'অপেক্ষমাণ', 'Working' => 'চলমান কাজ', 'Completed' => 'সাবমিশন / QA', 'Checked' => 'QA অনুমোদিত', 'Delivered' => 'ডেলিভার্ড'] as $stgKey => $stgName)
                        @php
                            $stepIdx = array_search($stgKey, $stages);
                            $isDone = ($currentIndex !== false && $stepIdx <= $currentIndex && !$isReworking);
                            $isCurrent = ($task->status === $stgKey);
                        @endphp
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs transition {{ $isCurrent ? 'bg-indigo-600 text-white ring-4 ring-indigo-100' : ($isDone ? 'bg-emerald-500 text-white' : 'bg-gray-100 text-gray-400') }}">
                                @if($isDone && !$isCurrent)
                                    ✓
                                @else
                                    {{ $loop->iteration }}
                                @endif
                            </div>
                            <span class="text-xs font-semibold {{ $isCurrent ? 'text-indigo-700' : ($isDone ? 'text-gray-800' : 'text-gray-400') }}">
                                {{ $stgName }}
                            </span>
                            @if(!$loop->last)
                                <span class="hidden sm:inline-block w-8 h-0.5 {{ $isDone ? 'bg-emerald-400' : 'bg-gray-200' }} mx-1"></span>
                            @endif
                        </div>
                    @endforeach
                </div>

                @if($isReworking)
                    <div class="mt-4 p-3 bg-rose-50 border border-rose-200 rounded-xl flex items-center gap-3 text-rose-800 text-xs">
                        <span class="p-1.5 bg-rose-600 text-white rounded-lg">⚠️</span>
                        <div>
                            <span class="font-bold">রি-ওয়ার্কিং স্ট্যাটাস (Reworking):</span>
                            <span>QA টিম কাজের মাঝে ত্রুটি সনাক্ত করেছে। অনুগ্রহ করে ত্রুটি সমাধান করে আবার সাবমিট করুন।</span>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Two-Column Task Content -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- Left Column: Submission and QA Review -->
                <div class="lg:col-span-2 space-y-6">

                    <!-- Task Scope & Description -->
                    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 space-y-3">
                        <h3 class="font-bold text-gray-900 text-base border-b border-gray-100 pb-2">কাজের বিবরণ ও নির্দেশিকা</h3>
                        <div class="text-sm text-gray-700 whitespace-pre-line leading-relaxed">
                            {{ $task->description ?: 'কোনো বিস্তারিত বিবরণ প্রদান করা হয়নি।' }}
                        </div>
                    </div>

                    <!-- WORK SUBMISSION SECTION -->
                    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 space-y-5">
                        <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                            <div>
                                <h3 class="font-bold text-gray-900 text-base flex items-center gap-2">
                                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                    কাজের সাবমিশন (Work Submission)
                                </h3>
                                <p class="text-xs text-gray-500 mt-0.5">ডেভেলপার বা ডিজাইনার কাজ শেষ করে পর্যালোচনার জন্য জমা দিন</p>
                            </div>
                            @if($task->submitted_at)
                                <span class="px-2.5 py-1 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-lg text-xs font-semibold">
                                    জমা দেওয়া হয়েছে: {{ $task->submitted_at->format('d M, Y h:i A') }}
                                </span>
                            @endif
                        </div>

                        <!-- Current Submission Info if exists -->
                        @if($task->submission_notes || $task->submission_url)
                            <div class="p-4 bg-gray-50 rounded-xl border border-gray-200 space-y-3">
                                <div class="text-xs font-bold text-gray-700 uppercase tracking-wider">সর্বশেষ সাবমিশন নোট:</div>
                                <p class="text-sm text-gray-800 whitespace-pre-line">{{ $task->submission_notes }}</p>

                                @if($task->submission_url)
                                    <div class="pt-2 border-t border-gray-200 flex items-center gap-2">
                                        <span class="text-xs font-semibold text-gray-600">ডেলিভারেবল লিংক:</span>
                                        <a href="{{ $task->submission_url }}" target="_blank" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 underline truncate max-w-md">
                                            {{ $task->submission_url }} &rarr;
                                        </a>
                                    </div>
                                @endif

                                @if($task->actual_hours)
                                    <div class="text-xs text-gray-600">
                                        ব্যয়িত সময়: <strong class="text-gray-900">{{ $task->actual_hours }} ঘণ্টা</strong>
                                    </div>
                                @endif
                            </div>
                        @endif

                        <!-- Submit / Re-submit Form -->
                        <form method="POST" action="{{ route('tasks.submit-work', $task) }}" class="space-y-4 pt-2">
                            @csrf
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">সাবমিশন বিবরণ ও চেকলিস্ট <span class="text-rose-500">*</span></label>
                                <textarea name="submission_notes" rows="3" required placeholder="কী কী কাজ সম্পন্ন হয়েছে এবং কীভাবে পরীক্ষা করতে হবে তা লিখুন..."
                                    class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">{{ old('submission_notes', $task->submission_notes) }}</textarea>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-1">ডেলিভারেবল লিংক (GitHub PR / Figma / Drive / Live Preview)</label>
                                    <input type="url" name="submission_url" value="{{ old('submission_url', $task->submission_url) }}" placeholder="https://..."
                                        class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-1">প্রকৃত ব্যয়িত সময় (ঘণ্টা)</label>
                                    <input type="number" step="0.5" name="actual_hours" value="{{ old('actual_hours', $task->actual_hours) }}" placeholder="যেমন: 6.5"
                                        class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                            </div>

                            <div class="flex justify-end">
                                <button type="submit" class="inline-flex items-center px-5 py-2.5 bg-indigo-600 text-white rounded-lg text-xs font-semibold hover:bg-indigo-700 shadow-sm transition">
                                    <svg class="w-4 h-4 me-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    {{ $task->submission_notes ? 'পুনরায় সাবমিট করুন' : 'QA ও পর্যালোচনার জন্য জমা দিন' }}
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- QA REVIEW ENGINE (SRS Section 11.1) -->
                    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 space-y-5">
                        <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                            <div>
                                <h3 class="font-bold text-gray-900 text-base flex items-center gap-2">
                                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                    কোয়ালিটি অ্যাসুরেন্স ও রিভিউ ইঞ্জিন (QA Engine)
                                </h3>
                                <p class="text-xs text-gray-500 mt-0.5">SRS সেকশন ১১.১: Passed হলে Checked স্ট্যাটাস, ত্রুটি থাকলে Reworking এ পাঠানো হবে</p>
                            </div>

                            @if($task->qa_status)
                                @php
                                    $qaBadge = match($task->qa_status) {
                                        'Passed' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                                        'Failed' => 'bg-rose-100 text-rose-800 border-rose-200',
                                        default => 'bg-amber-100 text-amber-800 border-amber-200'
                                    };
                                @endphp
                                <span class="px-2.5 py-1 rounded-lg text-xs font-bold border {{ $qaBadge }}">
                                    QA: {{ $task->qa_status }}
                                </span>
                            @endif
                        </div>

                        <!-- Previous QA Review Details -->
                        @if($task->checked_at)
                            <div class="p-4 rounded-xl border {{ $task->qa_status === 'Passed' ? 'bg-emerald-50/50 border-emerald-200' : 'bg-rose-50/50 border-rose-200' }} space-y-2">
                                <div class="flex items-center justify-between text-xs">
                                    <span class="font-bold {{ $task->qa_status === 'Passed' ? 'text-emerald-900' : 'text-rose-900' }}">
                                        রিভিউকারী: {{ $task->qaUser?->name ?? 'QA Officer' }}
                                    </span>
                                    <span class="text-gray-500">{{ $task->checked_at->format('d M, Y h:i A') }}</span>
                                </div>
                                @if($task->qa_notes)
                                    <div class="text-xs text-gray-700 pt-1">
                                        <strong>মন্তব্য / ইস্যু তালিকা:</strong>
                                        <p class="mt-1 whitespace-pre-line bg-white/70 p-2.5 rounded border border-gray-200">{{ $task->qa_notes }}</p>
                                    </div>
                                @endif
                            </div>
                        @endif

                        <!-- QA Review Action Form -->
                        <form method="POST" action="{{ route('tasks.qa-review', $task) }}" class="space-y-4 pt-1">
                            @csrf

                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-2">QA সিদ্ধান্তের ফলাফল <span class="text-rose-500">*</span></label>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <label class="flex items-center gap-3 p-3.5 border rounded-xl cursor-pointer hover:bg-emerald-50/40 border-gray-200 has-[:checked]:border-emerald-500 has-[:checked]:bg-emerald-50/60 transition">
                                        <input type="radio" name="qa_status" value="Passed" {{ old('qa_status', $task->qa_status) === 'Passed' ? 'checked' : '' }} required
                                            class="text-emerald-600 focus:ring-emerald-500">
                                        <div>
                                            <div class="text-xs font-bold text-gray-900">অনুমোদিত (Passed)</div>
                                            <div class="text-[11px] text-gray-500">কাজ মানসম্মত হয়েছে, 'Checked' স্ট্যাটাসে উত্তীর্ণ করুন।</div>
                                        </div>
                                    </label>

                                    <label class="flex items-center gap-3 p-3.5 border rounded-xl cursor-pointer hover:bg-rose-50/40 border-gray-200 has-[:checked]:border-rose-500 has-[:checked]:bg-rose-50/60 transition">
                                        <input type="radio" name="qa_status" value="Failed" {{ old('qa_status', $task->qa_status) === 'Failed' ? 'checked' : '' }} required
                                            class="text-rose-600 focus:ring-rose-500">
                                        <div>
                                            <div class="text-xs font-bold text-gray-900">ত্রুটি সনাক্ত (Failed / Reworking)</div>
                                            <div class="text-[11px] text-gray-500">বাগ বা পরিবর্তন প্রয়োজন, রি-ওয়ার্কিংয়ে পাঠান।</div>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">QA রিভিউ নোট / বাগের তালিকা</label>
                                <textarea name="qa_notes" rows="3" placeholder="যদি কোনো ইস্যু থাকে তা স্পষ্টভাবে উল্লেখ করুন যেন কর্মী দ্রুত ঠিক করতে পারেন..."
                                    class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">{{ old('qa_notes', $task->qa_notes) }}</textarea>
                            </div>

                            <div class="flex justify-end">
                                <button type="submit" class="px-5 py-2.5 bg-purple-600 text-white rounded-lg text-xs font-semibold hover:bg-purple-700 shadow-sm transition">
                                    QA ফলাফল সংরক্ষণ করুন
                                </button>
                            </div>
                        </form>
                    </div>

                </div>

                <!-- Right Column: Meta Details & Quick Management -->
                <div class="space-y-6">

                    <!-- Task Metadata Card -->
                    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 space-y-4">
                        <h3 class="font-bold text-gray-900 border-b border-gray-100 pb-2">টাস্ক বিবরণ</h3>

                        <dl class="space-y-3 text-xs">
                            <div class="flex items-center justify-between">
                                <dt class="text-gray-500">টাস্ক টাইপ:</dt>
                                <dd class="font-bold text-gray-900">{{ $task->task_type }}</dd>
                            </div>

                            <div class="flex items-center justify-between">
                                <dt class="text-gray-500">প্রাধান্য (Priority):</dt>
                                <dd>
                                    @php
                                        $pClass = match($task->priority) {
                                            'Urgent' => 'bg-rose-100 text-rose-700',
                                            'High' => 'bg-amber-100 text-amber-700',
                                            'Medium' => 'bg-blue-100 text-blue-700',
                                            default => 'bg-gray-100 text-gray-700'
                                        };
                                    @endphp
                                    <span class="px-2 py-0.5 rounded text-[11px] font-semibold {{ $pClass }}">
                                        {{ $task->priority }}
                                    </span>
                                </dd>
                            </div>

                            <div class="flex items-center justify-between">
                                <dt class="text-gray-500">বর্তমান স্ট্যাটাস:</dt>
                                <dd>
                                    <span class="px-2 py-0.5 rounded-full text-xs font-semibold border bg-indigo-50 text-indigo-700 border-indigo-200">
                                        {{ $task->status }}
                                    </span>
                                </dd>
                            </div>

                            <div class="flex items-center justify-between">
                                <dt class="text-gray-500">অ্যাসাইন্ড কর্মী:</dt>
                                <dd class="font-bold text-gray-900">{{ $task->assignedUser?->name ?? 'অ্যাসাইন করা হয়নি' }}</dd>
                            </div>

                            <div class="flex items-center justify-between">
                                <dt class="text-gray-500">অ্যাসাইন করেছেন:</dt>
                                <dd class="text-gray-700">{{ $task->assignedBy?->name ?? 'সিস্টেম' }}</dd>
                            </div>

                            <div class="flex items-center justify-between">
                                <dt class="text-gray-500">ডেডলাইন:</dt>
                                <dd class="font-semibold text-gray-900">{{ $task->deadline ? $task->deadline->format('d M, Y') : 'নির্দিষ্ট নেই' }}</dd>
                            </div>

                            <div class="flex items-center justify-between">
                                <dt class="text-gray-500">আনুমানিক সময়:</dt>
                                <dd class="text-gray-900">{{ $task->estimated_hours ? $task->estimated_hours . ' ঘণ্টা' : '-' }}</dd>
                            </div>

                            <div class="flex items-center justify-between">
                                <dt class="text-gray-500">প্রকৃত সময়:</dt>
                                <dd class="text-gray-900 font-bold">{{ $task->actual_hours ? $task->actual_hours . ' ঘণ্টা' : '-' }}</dd>
                            </div>
                        </dl>
                    </div>

                    <!-- Quick Status / Reassign Form -->
                    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 space-y-4">
                        <h3 class="font-bold text-gray-900 text-sm border-b border-gray-100 pb-2">ম্যানেজমেন্ট আপডেট</h3>

                        <form method="POST" action="{{ route('tasks.update', $task) }}" class="space-y-3">
                            @csrf
                            @method('PUT')

                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">স্ট্যাটাস পরিবর্তন</label>
                                <select name="status" class="w-full text-xs rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                    @foreach(['Pending', 'Working', 'Reworking', 'Completed', 'Checked', 'Delivered'] as $st)
                                        <option value="{{ $st }}" {{ $task->status === $st ? 'selected' : '' }}>{{ $st }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">টিম মেম্বার পরিবর্তন</label>
                                <select name="assigned_to" class="w-full text-xs rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">নির্বাচন করুন</option>
                                    @foreach($teamMembers as $tm)
                                        <option value="{{ $tm->id }}" {{ $task->assigned_to == $tm->id ? 'selected' : '' }}>
                                            {{ $tm->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">প্রাধান্য</label>
                                <select name="priority" class="w-full text-xs rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                    @foreach(['Low', 'Medium', 'High', 'Urgent'] as $pr)
                                        <option value="{{ $pr }}" {{ $task->priority === $pr ? 'selected' : '' }}>{{ $pr }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <input type="hidden" name="title" value="{{ $task->title }}">

                            <button type="submit" class="w-full py-2 bg-gray-900 text-white rounded-lg text-xs font-semibold hover:bg-gray-800 transition">
                                আপডেট সংরক্ষণ করুন
                            </button>
                        </form>
                    </div>

                    <!-- Linked Project Details -->
                    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 space-y-3">
                        <div class="flex items-center justify-between">
                            <h3 class="font-bold text-gray-900 text-sm">প্রজেক্ট ওভারভিউ</h3>
                            <a href="{{ route('projects.show', $task->project) }}" class="text-xs font-bold text-indigo-600 hover:underline">
                                ড্যাশবোর্ড &rarr;
                            </a>
                        </div>
                        <div class="p-3 bg-gray-50 rounded-xl space-y-2">
                            <div class="text-xs font-bold text-gray-800">{{ $task->project->name }}</div>
                            <div class="text-[11px] text-gray-500">{{ $task->project->category }}</div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-indigo-600 h-2 rounded-full" style="width: {{ $task->project->progress_percent }}%"></div>
                            </div>
                            <div class="text-[11px] text-gray-600 text-right">{{ $task->project->progress_percent }}% সম্পন্ন</div>
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </div>
</x-app-layout>
