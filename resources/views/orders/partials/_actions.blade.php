<td class="py-3 px-3 whitespace-nowrap text-center">
    <div class="relative inline-block text-left" x-data="{ open: false }" @click.outside="open = false">
        <button @click="open = !open" type="button" class="p-1.5 text-gray-500 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition focus:outline-none" title="{{ __('Actions') }}">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                <circle cx="12" cy="5" r="1.75" />
                <circle cx="12" cy="12" r="1.75" />
                <circle cx="12" cy="19" r="1.75" />
            </svg>
        </button>

        <!-- Dropdown Menu -->
        <div x-show="open"
             x-cloak
             x-transition:enter="transition ease-out duration-100"
             x-transition:enter-start="transform opacity-0 scale-95"
             x-transition:enter-end="transform opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-75"
             x-transition:leave-start="transform opacity-100 scale-100"
             x-transition:leave-end="transform opacity-0 scale-95"
             class="absolute left-0 z-50 mt-1 w-44 origin-top-left rounded-xl bg-white p-1.5 shadow-xl ring-1 ring-slate-900/10 focus:outline-none text-left"
             style="display: none;">

            <a href="{{ route('orders.show', $order) }}" class="flex items-center gap-2 px-3 py-2 text-xs font-semibold text-gray-700 hover:bg-indigo-50 hover:text-indigo-700 rounded-lg transition">
                <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                <span>{{ __('View Details') }}</span>
            </a>

            <a href="{{ route('orders.edit', $order) }}" class="flex items-center gap-2 px-3 py-2 text-xs font-semibold text-gray-700 hover:bg-amber-50 hover:text-amber-700 rounded-lg transition">
                <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                <span>{{ __('Edit Order') }}</span>
            </a>

            @if($order->project)
                <a href="{{ route('projects.show', $order->project) }}" class="flex items-center gap-2 px-3 py-2 text-xs font-semibold text-indigo-700 hover:bg-indigo-50 rounded-lg transition">
                    <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    <span>{{ __('Project Workspace') }}</span>
                </a>
            @endif

            <div class="my-1 border-t border-gray-100"></div>

            <form method="POST" action="{{ route('orders.destroy', $order) }}" onsubmit="return confirm('{{ __('Are you sure you want to delete this order?') }}');">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-full flex items-center gap-2 px-3 py-2 text-xs font-semibold text-rose-600 hover:bg-rose-50 rounded-lg transition text-left">
                    <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    <span>{{ __('Delete') }}</span>
                </button>
            </form>
        </div>
    </div>
</td>

