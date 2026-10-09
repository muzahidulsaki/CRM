<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('automation.index') }}" class="text-xs font-semibold text-indigo-600 hover:underline">&larr; Automation Center</a>
                    <span class="text-gray-300">/</span>
                    <span class="text-xs font-semibold text-gray-500">Notifications</span>
                </div>
                <h2 class="font-bold text-2xl text-gray-900 leading-tight mt-1 flex items-center gap-2">
                    <svg class="w-6 h-6 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"/></svg>
                    {{ __('সিস্টেম নোটিফিকেশন ও অ্যালার্ট ইনবক্স') }}
                </h2>
                <p class="text-sm text-gray-500 mt-0.5">
                    রিনিউয়াল অ্যালার্ট, বকেয়া ইনভয়েস, নতুন লিড ও হোয়াটসঅ্যাপ নোটিফিকেশন
                </p>
            </div>
            <div class="flex items-center gap-3">
                @if($unreadCount > 0)
                    <form action="{{ route('notifications.read-all') }}" method="POST">
                        @csrf
                        <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 rounded-xl shadow-sm transition">
                            সবগুলো পঠিত করুন ({{ $unreadCount }} টি অপঠিত)
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-4">

            <!-- Filter tabs -->
            <div class="flex gap-2 pb-2">
                <a href="{{ route('notifications.index') }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold {{ !request('filter') ? 'bg-indigo-600 text-white' : 'bg-white border text-gray-700 hover:bg-gray-50' }}">
                    সব নোটিফিকেশন
                </a>
                <a href="{{ route('notifications.index', ['filter' => 'unread']) }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold {{ request('filter') === 'unread' ? 'bg-indigo-600 text-white' : 'bg-white border text-gray-700 hover:bg-gray-50' }}">
                    অপঠিত ({{ $unreadCount }})
                </a>
            </div>

            <!-- Notifications List -->
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm divide-y divide-gray-100 overflow-hidden">
                @forelse($notifications as $n)
                    <div class="p-4 flex justify-between items-start gap-4 transition hover:bg-gray-50/80 {{ !$n->is_read ? 'bg-indigo-50/20' : '' }}">
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0 mt-0.5
                                {{ $n->type === 'renewal_alert' ? 'bg-amber-100 text-amber-700' : '' }}
                                {{ $n->type === 'invoice_overdue' ? 'bg-rose-100 text-rose-700' : '' }}
                                {{ $n->type === 'new_lead' ? 'bg-emerald-100 text-emerald-700' : '' }}
                                {{ $n->type === 'whatsapp_msg' ? 'bg-green-100 text-green-700' : '' }}
                                {{ $n->type === 'follow_up' ? 'bg-indigo-100 text-indigo-700' : '' }}
                                {{ !in_array($n->type, ['renewal_alert', 'invoice_overdue', 'new_lead', 'whatsapp_msg', 'follow_up']) ? 'bg-gray-100 text-gray-700' : '' }}
                            ">
                                @if($n->type === 'renewal_alert') 🔄
                                @elseif($n->type === 'invoice_overdue') ⚠️
                                @elseif($n->type === 'new_lead') 👤
                                @elseif($n->type === 'whatsapp_msg') 💬
                                @elseif($n->type === 'follow_up') 📅
                                @else 🔔
                                @endif
                            </div>
                            <div>
                                <div class="font-bold text-sm text-gray-900 flex items-center gap-2">
                                    {{ $n->title }}
                                    @if(!$n->is_read)
                                        <span class="px-2 py-0.2 rounded-full text-[10px] font-bold bg-indigo-600 text-white">নতুন</span>
                                    @endif
                                </div>
                                <p class="text-xs text-gray-600 mt-1">{{ $n->message }}</p>
                                <span class="text-[11px] text-gray-400 mt-1 block">{{ $n->created_at->format('d M, Y h:i A') }} ({{ $n->created_at->diffForHumans() }})</span>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 shrink-0">
                            @if($n->link_url)
                                <a href="{{ $n->link_url }}" class="px-3 py-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-semibold text-xs rounded-lg transition">
                                    রেকর্ড দেখুন &rarr;
                                </a>
                            @endif

                            @if(!$n->is_read)
                                <form action="{{ route('notifications.read', $n->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="px-2.5 py-1.5 hover:bg-gray-100 text-gray-500 text-xs rounded-lg font-semibold" title="পঠিত চিহ্নিত করুন">
                                        ✓ পঠিত
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="p-12 text-center text-gray-400">
                        <div class="text-3xl mb-2">📭</div>
                        <p class="font-semibold text-sm">কোনো নোটিফিকেশন নেই।</p>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $notifications->links() }}
            </div>

        </div>
    </div>
</x-app-layout>
