<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-900 leading-tight flex items-center gap-2">
                    <svg class="w-7 h-7 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    {{ __('API & Workflow Automation Control Center') }}
                </h2>
                <p class="text-sm text-gray-500 mt-0.5">
                    {{ __('Website Form REST API, WhatsApp Webhook & Automated Notification Reminders') }}
                </p>
            </div>
            <div class="flex items-center gap-3">
                <form action="{{ route('automation.reminders.run') }}" method="POST">
                    @csrf
                    <button type="submit" class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-semibold bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl shadow-sm transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ __('Run Reminder Engine Manually') }}
                    </button>
                </form>
                <a href="{{ route('notifications.index') }}" class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-semibold bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 rounded-xl shadow-sm transition">
                    <svg class="w-4 h-4 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"/></svg>
                    {{ __('Notification Inbox') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Flash Alerts -->
            @if(session('success'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-xl text-xs font-semibold text-emerald-800 flex items-center gap-2">
                    <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    {{ session('success') }}
                </div>
            @endif

            <!-- Top Grid: API Tokens & WhatsApp Test Simulator -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- Column 1 & 2: API Tokens for Website Integration -->
                <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-200 shadow-sm p-6 space-y-5">
                    <div class="flex justify-between items-center pb-3 border-b">
                        <div>
                            <h3 class="font-bold text-base text-gray-900 flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-indigo-600"></span>
                                {{ __('Website Integration API Tokens (Website REST API)') }}
                            </h3>
                            <p class="text-xs text-gray-500 mt-0.5">{{ __('Secure tokens for sending data from Projukti Sheba main website or landing page forms') }}</p>
                        </div>
                    </div>

                    <!-- Create New Token Form -->
                    <form action="{{ route('automation.tokens.store') }}" method="POST" class="bg-gray-50 p-4 rounded-xl border border-gray-200 flex flex-col sm:flex-row gap-3 items-end">
                        @csrf
                        <div class="w-full sm:w-1/2">
                            <label class="block text-xs font-bold text-gray-700 mb-1">{{ __('Token Name / Website Name') }} *</label>
                            <input type="text" name="name" placeholder="e.g. Projukti Sheba Main Website" required class="w-full text-xs rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div class="w-full sm:w-1/3">
                            <label class="block text-xs font-bold text-gray-700 mb-1">{{ __('Allowed Domain (Optional)') }}</label>
                            <input type="text" name="allowed_domain" placeholder="projuktisheba.com" class="w-full text-xs rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div class="w-full sm:w-auto">
                            <button type="submit" class="w-full px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-lg shadow-sm">
                                + {{ __('Create Token') }}
                            </button>
                        </div>
                    </form>

                    <!-- Tokens Table -->
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs text-gray-700">
                            <thead class="bg-gray-50 text-gray-500 uppercase font-semibold">
                                <tr>
                                    <th class="p-3">{{ __('Name & Domain') }}</th>
                                    <th class="p-3">{{ __('API Token (Bearer)') }}</th>
                                    <th class="p-3">{{ __('Last Used') }}</th>
                                    <th class="p-3 text-center">{{ __('Status') }}</th>
                                    <th class="p-3 text-right">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($tokens as $t)
                                    <tr>
                                        <td class="p-3">
                                            <div class="font-bold text-gray-900">{{ $t->name }}</div>
                                            <div class="text-[11px] text-gray-400">{{ $t->allowed_domain ?: 'Any Domain' }}</div>
                                        </td>
                                        <td class="p-3 font-mono">
                                            <div class="flex items-center gap-1.5">
                                                <input type="text" readonly value="{{ $t->token }}" class="text-[11px] bg-gray-50 border-gray-200 rounded px-2 py-1 w-48 font-mono select-all">
                                                <button onclick="navigator.clipboard.writeText('{{ $t->token }}'); alert('Token copied!');" class="p-1 hover:bg-gray-200 rounded text-gray-500" title="Copy Token">
                                                    📋
                                                </button>
                                            </div>
                                        </td>
                                        <td class="p-3 text-gray-500">{{ $t->last_used_at ? $t->last_used_at->diffForHumans() : __('Never') }}</td>
                                        <td class="p-3 text-center">
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold {{ $t->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-100 text-gray-500' }}">
                                                {{ $t->is_active ? __('Active') : __('Inactive') }}
                                            </span>
                                        </td>
                                        <td class="p-3 text-right space-x-1">
                                            <form action="{{ route('automation.tokens.toggle', $t->id) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="text-xs text-indigo-600 hover:underline font-semibold">
                                                    {{ $t->is_active ? __('Deactivate') : __('Activate') }}
                                                </button>
                                            </form>
                                            <span class="text-gray-300">|</span>
                                            <form action="{{ route('automation.tokens.destroy', $t->id) }}" method="POST" class="inline" onsubmit="return confirm('{{ __('Are you sure you want to delete this token?') }}');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-xs text-rose-600 hover:underline font-semibold">{{ __('Delete') }}</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="p-6 text-center text-gray-400 italic">
                                            {{ __('No API Token created yet. Generate a token for the website using the form above.') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- API Endpoint Documentation Box -->
                    <div class="p-4 bg-slate-900 rounded-xl text-slate-300 text-xs font-mono space-y-2">
                        <div class="text-indigo-400 font-bold">{{ __('API Endpoint Reference (Website Integration Guide):') }}</div>
                        <div><strong class="text-emerald-400">POST</strong> /api/v1/leads — {{ __('Submit leads from website form') }}</div>
                        <div><strong class="text-emerald-400">POST</strong> /api/v1/orders — {{ __('Direct order submission from website') }}</div>
                        <div><strong class="text-emerald-400">POST</strong> /api/v1/support — {{ __('Create support ticket from website') }}</div>
                        <div class="text-slate-400 text-[11px] pt-1">
                            * {{ __('Pass') }} <code>Authorization: Bearer YOUR_TOKEN</code> {{ __('or') }} <code>X-API-TOKEN: YOUR_TOKEN</code> {{ __('in request headers.') }}
                        </div>
                    </div>
                </div>

                <!-- Column 3: WhatsApp Webhook & Test Simulator -->
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 space-y-5">
                    <div>
                        <h3 class="font-bold text-base text-gray-900 flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-600"></span>
                            {{ __('WhatsApp Webhook Integration') }}
                        </h3>
                        <p class="text-xs text-gray-500 mt-0.5">{{ __('Meta WhatsApp Business Cloud API Webhook') }}</p>
                    </div>

                    <div class="p-3.5 bg-emerald-50/60 rounded-xl border border-emerald-100 text-xs space-y-1.5">
                        <div class="font-bold text-emerald-900">{{ __('Webhook URL:') }}</div>
                        <div class="font-mono text-[11px] bg-white p-2 rounded border border-emerald-200 break-all select-all">
                            {{ url('/api/v1/webhook/whatsapp') }}
                        </div>
                        <div class="text-[11px] text-emerald-700">{{ __('Verify Token:') }} <code>projukti_sheba_crm_verify_token</code></div>
                    </div>

                    <!-- Simulator Form -->
                    <div class="pt-2 border-t">
                        <h4 class="font-bold text-xs text-gray-700 uppercase tracking-wider mb-2">{{ __('WhatsApp Message Simulator (Test Ingestion):') }}</h4>
                        <form action="{{ route('automation.whatsapp.test') }}" method="POST" class="space-y-3">
                            @csrf
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-0.5">{{ __('Sender Name') }}</label>
                                <input type="text" name="sender_name" value="Kazi Rafiq" required class="w-full text-xs rounded-lg border-gray-300">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-0.5">{{ __('WhatsApp Mobile Number') }}</label>
                                <input type="text" name="sender_phone" value="8801712345678" required class="w-full text-xs rounded-lg border-gray-300">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-0.5">{{ __('Message Body') }}</label>
                                <textarea name="message" rows="2" required class="w-full text-xs rounded-lg border-gray-300">Need software for my restaurant. What would be the price?</textarea>
                            </div>
                            <button type="submit" class="w-full py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-lg shadow-sm">
                                📩 {{ __('Send Test Message') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Bottom Grid: Recent Webhook Logs & Live Notifications -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                <!-- Webhook Ingestion Logs -->
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 space-y-4">
                    <h3 class="font-bold text-base text-gray-900 flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-blue-600"></span>
                        {{ __('Recent Webhook Ingest Logs') }} (Recent Webhooks: {{ count($webhookLogs) }})
                    </h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs text-gray-700">
                            <thead class="bg-gray-50 text-gray-500 uppercase font-semibold">
                                <tr>
                                    <th class="p-2.5">{{ __('Provider') }}</th>
                                    <th class="p-2.5">{{ __('Sender & Phone') }}</th>
                                    <th class="p-2.5">{{ __('Message / Event') }}</th>
                                    <th class="p-2.5 text-center">{{ __('Status') }}</th>
                                    <th class="p-2.5 text-right">{{ __('Time') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($webhookLogs as $log)
                                    <tr>
                                        <td class="p-2.5 font-bold uppercase text-[11px] text-emerald-700">{{ $log->provider }}</td>
                                        <td class="p-2.5">
                                            <div class="font-semibold text-gray-900">{{ $log->sender_name }}</div>
                                            <div class="text-[11px] text-gray-400 font-mono">{{ $log->sender_phone }}</div>
                                        </td>
                                        <td class="p-2.5 text-gray-600">{{ \Illuminate\Support\Str::limit($log->message_body, 35) }}</td>
                                        <td class="p-2.5 text-center">
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold {{ $log->status === 'processed' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                                {{ ucfirst($log->status) }}
                                            </span>
                                        </td>
                                        <td class="p-2.5 text-right text-gray-400">{{ $log->created_at->diffForHumans() }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="p-6 text-center text-gray-400 italic">{{ __('No webhook events received yet.') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Recent Notifications Preview -->
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 space-y-4">
                    <div class="flex justify-between items-center">
                        <h3 class="font-bold text-base text-gray-900 flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>
                            {{ __('Automated System Alerts & Notifications') }}
                        </h3>
                        <a href="{{ route('notifications.index') }}" class="text-xs font-semibold text-indigo-600 hover:underline">
                            {{ __('View All') }} &rarr;
                        </a>
                    </div>
                    <div class="divide-y divide-gray-100">
                        @forelse($recentNotifications as $notif)
                            <div class="py-2.5 flex justify-between items-start text-xs gap-3">
                                <div>
                                    <div class="font-bold text-gray-900 {{ !$notif->is_read ? 'text-indigo-900' : '' }} flex items-center gap-1.5">
                                        @if(!$notif->is_read)
                                            <span class="w-1.5 h-1.5 rounded-full bg-indigo-600"></span>
                                        @endif
                                        {{ $notif->title }}
                                    </div>
                                    <p class="text-gray-500 text-[11px] mt-0.5">{{ $notif->message }}</p>
                                </div>
                                <div class="text-right shrink-0">
                                    <span class="text-[10px] text-gray-400 block">{{ $notif->created_at->diffForHumans() }}</span>
                                    @if($notif->link_url)
                                        <a href="{{ $notif->link_url }}" class="text-indigo-600 hover:underline font-semibold text-[11px]">{{ __('View') }} &rarr;</a>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <p class="p-6 text-center text-gray-400 italic">{{ __('No notifications found.') }}</p>
                        @endforelse
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>
