<!-- Navigation Menu Groups -->
<nav class="flex flex-1 flex-col gap-y-6">
    <ul role="list" class="flex flex-1 flex-col gap-y-6">
        
        <!-- Group 1: Core -->
        <li>
            <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400 px-3 mb-2 flex items-center justify-between">
                <span>মেইন ড্যাশবোর্ড</span>
                <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span>
            </div>
            <ul role="list" class="space-y-1">
                <li>
                    <a href="{{ route('dashboard') }}" 
                       class="group flex items-center gap-x-3 rounded-xl px-3 py-2 text-xs font-semibold transition {{ request()->routeIs('dashboard') ? 'bg-indigo-600 text-white shadow-sm shadow-indigo-600/30' : 'text-slate-300 hover:bg-slate-800/70 hover:text-white' }}">
                        <svg class="h-4 w-4 shrink-0 {{ request()->routeIs('dashboard') ? 'text-white' : 'text-slate-400 group-hover:text-indigo-400' }}" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        <span>সেন্ট্রাল ড্যাশবোর্ড</span>
                    </a>
                </li>
            </ul>
        </li>

        <!-- Group 2: CRM & Sales -->
        <li>
            <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400 px-3 mb-2 flex items-center justify-between">
                <span>সিআরএম ও সেলস</span>
                <span class="text-[10px] text-indigo-400 font-mono">CRM</span>
            </div>
            <ul role="list" class="space-y-1">
                <li>
                    <a href="{{ route('leads.index') }}" 
                       class="group flex items-center justify-between rounded-xl px-3 py-2 text-xs font-medium transition {{ request()->routeIs('leads.index') || request()->routeIs('leads.create') || request()->routeIs('leads.show') || request()->routeIs('leads.edit') ? 'bg-indigo-600 text-white shadow-sm font-semibold' : 'text-slate-300 hover:bg-slate-800/70 hover:text-white' }}">
                        <div class="flex items-center gap-x-3">
                            <svg class="h-4 w-4 shrink-0 text-slate-400 group-hover:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                            </svg>
                            <span>লিড ম্যানেজমেন্ট</span>
                        </div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('leads.pipeline') }}" 
                       class="group flex items-center gap-x-3 rounded-xl px-3 py-2 text-xs font-medium transition {{ request()->routeIs('leads.pipeline') ? 'bg-indigo-600 text-white shadow-sm font-semibold' : 'text-slate-300 hover:bg-slate-800/70 hover:text-white' }}">
                        <svg class="h-4 w-4 shrink-0 text-slate-400 group-hover:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2" />
                        </svg>
                        <span>সেলস পাইপলাইন (কানবান)</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('quotations.index') }}" 
                       class="group flex items-center gap-x-3 rounded-xl px-3 py-2 text-xs font-medium transition {{ request()->routeIs('quotations.*') ? 'bg-indigo-600 text-white shadow-sm font-semibold' : 'text-slate-300 hover:bg-slate-800/70 hover:text-white' }}">
                        <svg class="h-4 w-4 shrink-0 text-slate-400 group-hover:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                        </svg>
                        <span>কোটেশন সমূহ</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('customers.index') }}" 
                       class="group flex items-center gap-x-3 rounded-xl px-3 py-2 text-xs font-medium transition {{ request()->routeIs('customers.*') ? 'bg-indigo-600 text-white shadow-sm font-semibold' : 'text-slate-300 hover:bg-slate-800/70 hover:text-white' }}">
                        <svg class="h-4 w-4 shrink-0 text-slate-400 group-hover:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008z" />
                        </svg>
                        <span>কাস্টমার ৩৬০° প্রোফাইল</span>
                    </a>
                </li>
            </ul>
        </li>

        <!-- Group 3: Operations & Projects -->
        <li>
            <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400 px-3 mb-2 flex items-center justify-between">
                <span>অপারেশনস ও প্রজেক্টস</span>
                <span class="text-[10px] text-cyan-400 font-mono">DEV</span>
            </div>
            <ul role="list" class="space-y-1">
                <li>
                    <a href="{{ route('orders.index') }}" 
                       class="group flex items-center gap-x-3 rounded-xl px-3 py-2 text-xs font-medium transition {{ request()->routeIs('orders.*') ? 'bg-indigo-600 text-white shadow-sm font-semibold' : 'text-slate-300 hover:bg-slate-800/70 hover:text-white' }}">
                        <svg class="h-4 w-4 shrink-0 text-slate-400 group-hover:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                        </svg>
                        <span>ক্লায়েন্ট অর্ডারসমূহ</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('projects.index') }}" 
                       class="group flex items-center gap-x-3 rounded-xl px-3 py-2 text-xs font-medium transition {{ request()->routeIs('projects.*') ? 'bg-indigo-600 text-white shadow-sm font-semibold' : 'text-slate-300 hover:bg-slate-800/70 hover:text-white' }}">
                        <svg class="h-4 w-4 shrink-0 text-slate-400 group-hover:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75V12A2.25 2.25 0 014.5 9.75h15A2.25 2.25 0 0121.75 12v.75m-8.69-6.44l-2.12-2.12a1.5 1.5 0 00-1.061-.44H4.5A2.25 2.25 0 002.25 6v12a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9a2.25 2.25 0 00-2.25-2.25h-5.379a1.5 1.5 0 01-1.06-.44z" />
                        </svg>
                        <span>চলমান প্রজেক্টস</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('tasks.index') }}" 
                       class="group flex items-center gap-x-3 rounded-xl px-3 py-2 text-xs font-medium transition {{ request()->routeIs('tasks.index') || request()->routeIs('tasks.show') ? 'bg-indigo-600 text-white shadow-sm font-semibold' : 'text-slate-300 hover:bg-slate-800/70 hover:text-white' }}">
                        <svg class="h-4 w-4 shrink-0 text-slate-400 group-hover:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>টাস্ক ও অ্যাসাইনমেন্ট</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('tasks.kanban') }}" 
                       class="group flex items-center gap-x-3 rounded-xl px-3 py-2 text-xs font-medium transition {{ request()->routeIs('tasks.kanban') ? 'bg-indigo-600 text-white shadow-sm font-semibold' : 'text-slate-300 hover:bg-slate-800/70 hover:text-white' }}">
                        <svg class="h-4 w-4 shrink-0 text-slate-400 group-hover:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v11.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v6A2.25 2.25 0 0118 14.25h-2.25A2.25 2.25 0 0113.5 12V6z" />
                        </svg>
                        <span>কিউএ কানবান বোর্ড</span>
                    </a>
                </li>
            </ul>
        </li>

        <!-- Group 4: Creative, Team & Worklogs -->
        <li>
            <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400 px-3 mb-2 flex items-center justify-between">
                <span>ক্রিয়েটিভ ও টিম ম্যানেজমেন্ট</span>
                <span class="text-[10px] text-amber-400 font-mono">PRO</span>
            </div>
            <ul role="list" class="space-y-1">
                <li>
                    <a href="{{ route('video-billings.index') }}" 
                       class="group flex items-center gap-x-3 rounded-xl px-3 py-2 text-xs font-medium transition {{ request()->routeIs('video-billings.*') ? 'bg-indigo-600 text-white shadow-sm font-semibold' : 'text-slate-300 hover:bg-slate-800/70 hover:text-white' }}">
                        <svg class="h-4 w-4 shrink-0 text-slate-400 group-hover:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5l4.72-4.72a.75.75 0 011.28.53v11.38a.75.75 0 01-1.28.53l-4.72-4.72M4.5 18.75h9a2.25 2.25 0 002.25-2.25v-9a2.25 2.25 0 00-2.25-2.25h-9A2.25 2.25 0 002.25 7.5v9a2.25 2.25 0 002.25 2.25z" />
                        </svg>
                        <span>ভিডিও বিলিং (পার-সেকেন্ড)</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('employees.index') }}" 
                       class="group flex items-center gap-x-3 rounded-xl px-3 py-2 text-xs font-medium transition {{ request()->routeIs('employees.*') ? 'bg-indigo-600 text-white shadow-sm font-semibold' : 'text-slate-300 hover:bg-slate-800/70 hover:text-white' }}">
                        <svg class="h-4 w-4 shrink-0 text-slate-400 group-hover:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                        </svg>
                        <span>টিম ডিরেক্টরি</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('worklogs.index') }}" 
                       class="group flex items-center gap-x-3 rounded-xl px-3 py-2 text-xs font-medium transition {{ request()->routeIs('worklogs.*') ? 'bg-indigo-600 text-white shadow-sm font-semibold' : 'text-slate-300 hover:bg-slate-800/70 hover:text-white' }}">
                        <svg class="h-4 w-4 shrink-0 text-slate-400 group-hover:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>কর্মঘণ্টা ও ওয়ার্কলগ</span>
                    </a>
                </li>
                @if(Auth::user()->isAdmin() || Auth::user()->hasRole('project_manager'))
                    <li>
                        <a href="{{ route('payouts.index') }}" 
                           class="group flex items-center gap-x-3 rounded-xl px-3 py-2 text-xs font-medium transition {{ request()->routeIs('payouts.*') ? 'bg-indigo-600 text-white shadow-sm font-semibold' : 'text-slate-300 hover:bg-slate-800/70 hover:text-white' }}">
                            <svg class="h-4 w-4 shrink-0 text-slate-400 group-hover:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z" />
                            </svg>
                            <span>টিম পে-আউট ভাউচার</span>
                        </a>
                    </li>
                @endif
            </ul>
        </li>

        <!-- Group 5: Billing, Finance & Renewals -->
        <li>
            <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400 px-3 mb-2 flex items-center justify-between">
                <span>একাউন্টিং ও ফাইন্যান্স</span>
                <span class="text-[10px] text-emerald-400 font-mono">BDT</span>
            </div>
            <ul role="list" class="space-y-1">
                <li>
                    <a href="{{ route('invoices.index') }}" 
                       class="group flex items-center gap-x-3 rounded-xl px-3 py-2 text-xs font-medium transition {{ request()->routeIs('invoices.*') ? 'bg-indigo-600 text-white shadow-sm font-semibold' : 'text-slate-300 hover:bg-slate-800/70 hover:text-white' }}">
                        <svg class="h-4 w-4 shrink-0 text-slate-400 group-hover:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m3.75 9v6m3-3H9m1.5-12H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                        </svg>
                        <span>ক্লায়েন্ট ইনভয়েস</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('client-payments.index') }}" 
                       class="group flex items-center gap-x-3 rounded-xl px-3 py-2 text-xs font-medium transition {{ request()->routeIs('client-payments.*') ? 'bg-indigo-600 text-white shadow-sm font-semibold' : 'text-slate-300 hover:bg-slate-800/70 hover:text-white' }}">
                        <svg class="h-4 w-4 shrink-0 text-slate-400 group-hover:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 14.25l6-6m4.5-3.493V21.75l-3.75-1.5-3.75 1.5-3.75-1.5-3.75 1.5V5.257c0-1.108.806-2.057 1.907-2.185a48.507 48.507 0 0111.186 0c1.1.128 1.907 1.077 1.907 2.185zM9.75 9h.008v.008H9.75V9zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm4.125 4.5h.008v.008h-.008V13.5zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                        </svg>
                        <span>কালেকশন ও মানি রিসিট</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('renewals.index') }}" 
                       class="group flex items-center gap-x-3 rounded-xl px-3 py-2 text-xs font-medium transition {{ request()->routeIs('renewals.*') ? 'bg-indigo-600 text-white shadow-sm font-semibold' : 'text-slate-300 hover:bg-slate-800/70 hover:text-white' }}">
                        <svg class="h-4 w-4 shrink-0 text-slate-400 group-hover:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                        </svg>
                        <span>হোস্টিং ও সার্ভিস রিনিউয়াল</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('expenses.index') }}" 
                       class="group flex items-center gap-x-3 rounded-xl px-3 py-2 text-xs font-medium transition {{ request()->routeIs('expenses.*') ? 'bg-indigo-600 text-white shadow-sm font-semibold' : 'text-slate-300 hover:bg-slate-800/70 hover:text-white' }}">
                        <svg class="h-4 w-4 shrink-0 text-slate-400 group-hover:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6H2.25m0 0v8.25m0-8.25h16.5m0 0h1.5A2.25 2.25 0 0122.5 8.25v10.5A2.25 2.25 0 0120.25 21H3.75m16.5-16.5V3a1.5 1.5 0 00-1.5-1.5h-13.5A1.5 1.5 0 003.75 3v1.5m16.5 0h-16.5" />
                        </svg>
                        <span>অফিস এক্সপেন্সেস</span>
                    </a>
                </li>
                @if(Auth::user()->isAdmin() || Auth::user()->hasRole(['project_manager', 'sales_manager']))
                    <li>
                        <a href="{{ route('finance.index') }}" 
                           class="group flex items-center gap-x-3 rounded-xl px-3 py-2 text-xs font-medium transition {{ request()->routeIs('finance.*') ? 'bg-indigo-600 text-white shadow-sm font-semibold' : 'text-slate-300 hover:bg-slate-800/70 hover:text-white' }}">
                            <svg class="h-4 w-4 shrink-0 text-slate-400 group-hover:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                            </svg>
                            <span>ফাইন্যান্সিয়াল ড্যাশবোর্ড (P&L)</span>
                        </a>
                    </li>
                @endif
            </ul>
        </li>

        <!-- Group 6: Support & Helpdesk -->
        <li>
            <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400 px-3 mb-2 flex items-center justify-between">
                <span>সাপোর্ট ও হেল্পডেস্ক</span>
                <span class="text-[10px] text-purple-400 font-mono">SLA</span>
            </div>
            <ul role="list" class="space-y-1">
                <li>
                    <a href="{{ route('support-tickets.index') }}" 
                       class="group flex items-center gap-x-3 rounded-xl px-3 py-2 text-xs font-medium transition {{ request()->routeIs('support-tickets.*') ? 'bg-indigo-600 text-white shadow-sm font-semibold' : 'text-slate-300 hover:bg-slate-800/70 hover:text-white' }}">
                        <svg class="h-4 w-4 shrink-0 text-slate-400 group-hover:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <span>সাপোর্ট টিকেটস</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('public-support.create') }}" target="_blank"
                       class="group flex items-center justify-between rounded-xl px-3 py-2 text-xs font-medium text-slate-400 hover:bg-slate-800/70 hover:text-white transition">
                        <div class="flex items-center gap-x-3">
                            <svg class="h-4 w-4 shrink-0 text-slate-500 group-hover:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418" />
                            </svg>
                            <span>পাবলিক সাপোর্ট পোর্টাল</span>
                        </div>
                        <svg class="w-3.5 h-3.5 text-slate-500 opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    </a>
                </li>
            </ul>
        </li>

        <!-- Group 7: Intelligence & Analytics -->
        <li>
            <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400 px-3 mb-2 flex items-center justify-between">
                <span>রিপোর্টস ও অ্যানালিটিক্স</span>
                <span class="text-[10px] text-sky-400 font-mono">BI</span>
            </div>
            <ul role="list" class="space-y-1">
                <li>
                    <a href="{{ route('reports.index') }}" 
                       class="group flex items-center gap-x-3 rounded-xl px-3 py-2 text-xs font-medium transition {{ request()->routeIs('reports.index') ? 'bg-indigo-600 text-white shadow-sm font-semibold' : 'text-slate-300 hover:bg-slate-800/70 hover:text-white' }}">
                        <svg class="h-4 w-4 shrink-0 text-slate-400 group-hover:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v11.25A2.25 2.25 0 006 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0118 16.5h-2.25m-7.5 0h7.5m-7.5 0l-1 3m8.5-3l1 3m0 0l.5 1.5m-.5-1.5h-9.5m0 0l-.5 1.5m.75-9l3-3 2.143 2.143L15.75 6" />
                        </svg>
                        <span>সেন্ট্রাল বিজনেস রিপোর্টস</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('reports.sales') }}" 
                       class="group flex items-center gap-x-3 rounded-xl px-3 py-2 text-xs font-medium transition {{ request()->routeIs('reports.sales') ? 'bg-indigo-600 text-white shadow-sm font-semibold' : 'text-slate-300 hover:bg-slate-800/70 hover:text-white' }}">
                        <svg class="h-4 w-4 shrink-0 text-slate-400 group-hover:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6a7.5 7.5 0 107.5 7.5h-7.5V6z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5H21A7.5 7.5 0 0013.5 3v7.5z" />
                        </svg>
                        <span>সেলস পারফরম্যান্স</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('reports.production') }}" 
                       class="group flex items-center gap-x-3 rounded-xl px-3 py-2 text-xs font-medium transition {{ request()->routeIs('reports.production') ? 'bg-indigo-600 text-white shadow-sm font-semibold' : 'text-slate-300 hover:bg-slate-800/70 hover:text-white' }}">
                        <svg class="h-4 w-4 shrink-0 text-slate-400 group-hover:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.375 19.5h17.25m-17.25 0a1.125 1.125 0 01-1.125-1.125M3.375 19.5h7.5c.621 0 1.125-.504 1.125-1.125m-9.75 0V5.625m0 12.75v-1.5c0-.621.504-1.125 1.125-1.125m18.375 2.625V5.625m0 12.75c0 .621-.504 1.125-1.125 1.125m1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125m0 3.75h-7.5A1.125 1.125 0 0112 18.375m9.75-12.75c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125m19.5 0v1.5c0 .621-.504 1.125-1.125 1.125M2.25 5.625v1.5c0 .621.504 1.125 1.125 1.125m0 0h17.25m-17.25 0h7.5c.621 0 1.125.504 1.125 1.125M3.375 8.25c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125m17.25-3.75h-7.5c-.621 0-1.125.504-1.125 1.125m8.625-1.125c.621 0 1.125.504 1.125 1.125v1.5c0 .621-.504 1.125-1.125 1.125m-17.25 0h7.5c.621 0 1.125.504 1.125 1.125M3.375 12c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125m17.25-3.75h-7.5c-.621 0-1.125.504-1.125 1.125m8.625-1.125c.621 0 1.125.504 1.125 1.125v1.5c0 .621-.504 1.125-1.125 1.125" />
                        </svg>
                        <span>ভিডিও ও টিম প্রডাকশন</span>
                    </a>
                </li>
            </ul>
        </li>

        <!-- Group 8: System Administration (Admin Only) -->
        @if(Auth::user()->isAdmin())
            <li>
                <div class="text-[10px] font-bold uppercase tracking-wider text-rose-400 px-3 mb-2 flex items-center justify-between">
                    <span>সিস্টেম অ্যাডমিনিস্ট্রেশন</span>
                    <span class="text-[9px] bg-rose-500/20 text-rose-300 px-1.5 py-0.5 rounded font-bold">ADMIN</span>
                </div>
                <ul role="list" class="space-y-1">
                    <li>
                        <a href="{{ route('automation.index') }}" 
                           class="group flex items-center gap-x-3 rounded-xl px-3 py-2 text-xs font-medium transition {{ request()->routeIs('automation.*') ? 'bg-indigo-600 text-white shadow-sm font-semibold' : 'text-slate-300 hover:bg-slate-800/70 hover:text-white' }}">
                            <svg class="h-4 w-4 shrink-0 text-slate-400 group-hover:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" />
                            </svg>
                            <span>অটোমেশন ও এপিআই টোকেন</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('backups.index') }}" 
                           class="group flex items-center gap-x-3 rounded-xl px-3 py-2 text-xs font-medium transition {{ request()->routeIs('backups.*') ? 'bg-indigo-600 text-white shadow-sm font-semibold' : 'text-slate-300 hover:bg-slate-800/70 hover:text-white' }}">
                            <svg class="h-4 w-4 shrink-0 text-slate-400 group-hover:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 5.625c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125" />
                            </svg>
                            <span>ডাটাবেজ অটো-ব্যাকআপ</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('users.index') }}" 
                           class="group flex items-center gap-x-3 rounded-xl px-3 py-2 text-xs font-medium transition {{ request()->routeIs('users.*') ? 'bg-indigo-600 text-white shadow-sm font-semibold' : 'text-slate-300 hover:bg-slate-800/70 hover:text-white' }}">
                            <svg class="h-4 w-4 shrink-0 text-slate-400 group-hover:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                            </svg>
                            <span>ইউজার অ্যাকাউন্টস</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('roles.index') }}" 
                           class="group flex items-center gap-x-3 rounded-xl px-3 py-2 text-xs font-medium transition {{ request()->routeIs('roles.*') ? 'bg-indigo-600 text-white shadow-sm font-semibold' : 'text-slate-300 hover:bg-slate-800/70 hover:text-white' }}">
                            <svg class="h-4 w-4 shrink-0 text-slate-400 group-hover:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                            </svg>
                            <span>রোলস ও পারমিশন (RBAC)</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('settings.index') }}" 
                           class="group flex items-center gap-x-3 rounded-xl px-3 py-2 text-xs font-medium transition {{ request()->routeIs('settings.*') ? 'bg-indigo-600 text-white shadow-sm font-semibold' : 'text-slate-300 hover:bg-slate-800/70 hover:text-white' }}">
                            <svg class="h-4 w-4 shrink-0 text-slate-400 group-hover:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span>সিস্টেম কনফিগারেশন</span>
                        </a>
                    </li>
                </ul>
            </li>
        @endif
        
    </ul>
</nav>
