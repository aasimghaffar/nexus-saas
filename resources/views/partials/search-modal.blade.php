{{-- Global search (Cmd/Ctrl+K) — quick links filter live; Enter runs a full workspace search --}}
<div id="search-modal" class="modal fixed inset-0 z-50 hidden items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
    <div class="modal-content w-full max-w-xl bg-white dark:bg-dark-900 rounded-2xl shadow-2xl border border-slate-200 dark:border-dark-800 overflow-hidden">
      <div class="p-4 border-b border-slate-100 dark:border-dark-800 flex items-center gap-3">
        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
        <form method="GET" action="{{ route('search') }}" class="flex-1"><input id="global-search-input" name="q" type="text" placeholder="Search projects, tickets, members... (press Enter)" autocomplete="off" class="w-full bg-transparent text-sm text-slate-800 dark:text-slate-100 focus:outline-none"></form>
        <button data-modal-close="search-modal" class="p-1 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
          <kbd class="px-1.5 py-0.5 rounded bg-slate-100 dark:bg-dark-800 text-[10px] font-mono border border-slate-200 dark:border-dark-700">ESC</kbd>
        </button>
      </div>
      <div class="max-h-80 overflow-y-auto p-2 space-y-1">
        <div class="text-[10px] uppercase font-bold text-slate-400 px-3 py-1.5">Quick Navigation</div>
        <a href="{{ route('dashboard') }}" class="search-result-item flex items-center gap-3 p-2.5 rounded-xl hover:bg-slate-50 dark:hover:bg-dark-800 text-xs font-medium">
          <span class="p-2 rounded-lg bg-brand-50 text-brand-600 dark:bg-brand-950 dark:text-brand-400"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg></span>
          <div>
            <p class="font-bold text-slate-800 dark:text-slate-200">SaaS Overview Dashboard</p>
            <p class="text-[11px] text-slate-400">MRR, ARR, and subscriber metrics</p>
          </div>
        </a>
        <a href="{{ route('dashboard.crm') }}" class="search-result-item flex items-center gap-3 p-2.5 rounded-xl hover:bg-slate-50 dark:hover:bg-dark-800 text-xs font-medium">
          <span class="p-2 rounded-lg bg-emerald-50 text-emerald-600 dark:bg-emerald-950 dark:text-emerald-400"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path></svg></span>
          <div>
            <p class="font-bold text-slate-800 dark:text-slate-200">CRM & Sales Pipeline</p>
            <p class="text-[11px] text-slate-400">Deals, leads, and conversion stages</p>
          </div>
        </a>
        <a href="{{ route('users') }}" class="search-result-item flex items-center gap-3 p-2.5 rounded-xl hover:bg-slate-50 dark:hover:bg-dark-800 text-xs font-medium">
          <span class="p-2 rounded-lg bg-cyan-50 text-cyan-600 dark:bg-cyan-950 dark:text-cyan-400"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg></span>
          <div>
            <p class="font-bold text-slate-800 dark:text-slate-200">User Management</p>
            <p class="text-[11px] text-slate-400">Manage team roles and permissions</p>
          </div>
        </a>
        <a href="{{ route('billing') }}" class="search-result-item flex items-center gap-3 p-2.5 rounded-xl hover:bg-slate-50 dark:hover:bg-dark-800 text-xs font-medium">
          <span class="p-2 rounded-lg bg-amber-50 text-amber-600 dark:bg-amber-950 dark:text-amber-400"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg></span>
          <div>
            <p class="font-bold text-slate-800 dark:text-slate-200">Billing & Subscriptions</p>
            <p class="text-[11px] text-slate-400">Payment methods and usage quotas</p>
          </div>
        </a>
      </div>
      <div class="p-3 border-t border-slate-100 dark:border-dark-800 bg-slate-50/50 dark:bg-dark-800/50 flex items-center justify-between text-[11px] text-slate-400">
        <span>Use <kbd class="px-1 py-0.5 bg-white dark:bg-dark-700 rounded border border-slate-200 dark:border-dark-600">↑</kbd> <kbd class="px-1 py-0.5 bg-white dark:bg-dark-700 rounded border border-slate-200 dark:border-dark-600">↓</kbd> to navigate</span>
        <span>Press <kbd class="px-1 py-0.5 bg-white dark:bg-dark-700 rounded border border-slate-200 dark:border-dark-600">ESC</kbd> to close</span>
      </div>
    </div>
  </div>
