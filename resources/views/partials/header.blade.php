{{-- Nexus top header (extracted from template) --}}
    <header class="h-16 sticky top-0 z-30 glass-header border-b border-slate-200/80 dark:border-dark-800 flex items-center justify-between px-4 sm:px-6">
      <div class="flex items-center gap-3">
        <button id="mobile-menu-btn" class="lg:hidden p-2 rounded-xl text-slate-500 hover:bg-slate-100 dark:hover:bg-dark-800 transition-colors">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
        </button>
        <button id="desktop-collapse-btn" class="hidden lg:flex p-2 rounded-xl text-slate-500 hover:bg-slate-100 dark:hover:bg-dark-800 transition-colors" title="Toggle Sidebar">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h8m-8 6h16"></path></svg>
        </button>

        <!-- Quick Search Bar (Cmd+K) -->
        <button data-trigger="search" class="hidden sm:flex items-center gap-3 px-3.5 py-1.5 rounded-xl bg-slate-100 dark:bg-dark-800/80 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 text-xs border border-transparent hover:border-slate-200 dark:hover:border-dark-700 transition-all w-52 md:w-64">
          <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
          <span class="flex-1 text-left">Search anything...</span>
          <kbd class="px-1.5 py-0.5 rounded bg-white dark:bg-dark-700 shadow-sm border border-slate-200 dark:border-dark-600 text-[10px] font-mono">⌘K</kbd>
        </button>
      </div>

      <!-- Header Actions Right -->
      <div class="flex items-center gap-2 sm:gap-3">
        <!-- Live System Status Badge -->
        <div class="hidden md:flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 text-xs font-medium border border-emerald-200 dark:border-emerald-800/50">
          <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
          <span>99.98% Uptime</span>
        </div>

        <!-- Theme Toggle Button -->
        <button data-toggle="theme" class="p-2 rounded-xl text-slate-500 hover:bg-slate-100 dark:hover:bg-dark-800 hover:text-slate-900 dark:hover:text-white transition-colors" title="Switch Theme">
          <svg class="w-5 h-5 theme-moon-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
          <svg class="w-5 h-5 theme-sun-icon hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
        </button>

        <!-- Notifications Dropdown -->
        <div class="relative">
          <button data-dropdown-toggle="notification-dropdown" class="p-2 rounded-xl text-slate-500 hover:bg-slate-100 dark:hover:bg-dark-800 relative transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
            <span id="notif-unread-badge" class="absolute top-1.5 right-1.5 w-2 h-2 rounded-full bg-brand-600 ring-2 ring-white dark:ring-dark-900"></span>
          </button>

          <!-- Notification Menu -->
          <div id="notification-dropdown" data-dropdown-menu class="hidden absolute right-0 mt-2 w-80 sm:w-96 bg-white dark:bg-dark-900 border border-slate-200 dark:border-dark-800 rounded-2xl shadow-2xl z-50 overflow-hidden">
            <div class="p-4 border-b border-slate-100 dark:border-dark-800 flex items-center justify-between">
              <div class="flex items-center gap-2">
                <h3 class="text-sm font-bold">Notifications</h3>
                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-brand-50 text-brand-600 dark:bg-brand-950 dark:text-brand-400">3 new</span>
              </div>
              <button id="mark-all-read-btn" class="text-xs text-brand-600 hover:text-brand-700 dark:text-brand-400 font-semibold">Mark all read</button>
            </div>
            <div class="divide-y divide-slate-100 dark:divide-dark-800 max-h-80 overflow-y-auto">
              <!-- Item 1 -->
              <div class="notification-item unread p-3.5 flex items-start gap-3 bg-brand-50/40 dark:bg-brand-950/20 hover:bg-slate-50 dark:hover:bg-dark-800 transition-colors">
                <span class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-600 dark:bg-emerald-950 dark:text-emerald-400 flex items-center justify-center flex-shrink-0 text-xs">💰</span>
                <div class="flex-1 min-w-0">
                  <p class="text-xs font-semibold text-slate-800 dark:text-slate-200">New Subscription Plan</p>
                  <p class="text-[11px] text-slate-500 dark:text-dark-400 truncate">Acme Inc upgraded to Enterprise Pro ($990/yr)</p>
                  <span class="text-[10px] text-slate-400 mt-1 block">5 mins ago</span>
                </div>
                <span class="unread-dot w-2 h-2 rounded-full bg-brand-600 mt-1"></span>
              </div>
              <!-- Item 2 -->
              <div class="notification-item unread p-3.5 flex items-start gap-3 bg-brand-50/40 dark:bg-brand-950/20 hover:bg-slate-50 dark:hover:bg-dark-800 transition-colors">
                <span class="w-8 h-8 rounded-full bg-brand-100 text-brand-600 dark:bg-brand-950 dark:text-brand-400 flex items-center justify-center flex-shrink-0 text-xs">🚀</span>
                <div class="flex-1 min-w-0">
                  <p class="text-xs font-semibold text-slate-800 dark:text-slate-200">Deployment Succeeded</p>
                  <p class="text-[11px] text-slate-500 dark:text-dark-400 truncate">Production cluster v2.4.1 live on US-East</p>
                  <span class="text-[10px] text-slate-400 mt-1 block">42 mins ago</span>
                </div>
                <span class="unread-dot w-2 h-2 rounded-full bg-brand-600 mt-1"></span>
              </div>
              <!-- Item 3 -->
              <div class="notification-item p-3.5 flex items-start gap-3 hover:bg-slate-50 dark:hover:bg-dark-800 transition-colors">
                <span class="w-8 h-8 rounded-full bg-amber-100 text-amber-600 dark:bg-amber-950 dark:text-amber-400 flex items-center justify-center flex-shrink-0 text-xs">⚠️</span>
                <div class="flex-1 min-w-0">
                  <p class="text-xs font-semibold text-slate-800 dark:text-slate-200">API Rate Limit Warning</p>
                  <p class="text-[11px] text-slate-500 dark:text-dark-400 truncate">Workspace reached 85% of monthly limit</p>
                  <span class="text-[10px] text-slate-400 mt-1 block">2 hours ago</span>
                </div>
              </div>
            </div>
            <div class="p-2.5 border-t border-slate-100 dark:border-dark-800 text-center bg-slate-50/50 dark:bg-dark-800/50">
              <a href="{{ route('activity-logs') }}" class="text-xs font-semibold text-brand-600 dark:text-brand-400 hover:underline">View all activity logs &rarr;</a>
            </div>
          </div>
        </div>

        <!-- User Menu Dropdown -->
        <div class="relative" data-dropdown-hover="user-dropdown">
          <button data-dropdown-toggle="user-dropdown" class="flex items-center gap-2 p-1 rounded-full hover:ring-2 hover:ring-brand-500 transition-all">
            <img src="{{ auth()->user()?->avatar_url ?? asset('assets/images/avatars/avatar-1.svg') }}" alt="{{ auth()->user()->name ?? 'Guest' }}" class="w-8 h-8 rounded-full border border-brand-500/40">
          </button>

          <div id="user-dropdown" data-dropdown-menu class="hidden absolute right-0 mt-2 w-56 bg-white dark:bg-dark-900 border border-slate-200 dark:border-dark-800 rounded-2xl shadow-2xl z-50 p-2">
            <div class="p-2 border-b border-slate-100 dark:border-dark-800">
              <p class="text-xs font-bold">{{ auth()->user()->name ?? 'Guest' }}</p>
              <p class="text-[11px] text-slate-400">Owner &bull; Super Admin</p>
            </div>
            <div class="py-1 space-y-0.5 text-xs">
              <a href="{{ route('profile') }}" class="flex items-center gap-2.5 px-2.5 py-2 rounded-xl text-slate-600 dark:text-dark-300 hover:bg-slate-50 dark:hover:bg-dark-800 hover:text-slate-900 dark:hover:text-white">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                <span>Profile Settings</span>
              </a>
              <a href="{{ route('billing') }}" class="flex items-center gap-2.5 px-2.5 py-2 rounded-xl text-slate-600 dark:text-dark-300 hover:bg-slate-50 dark:hover:bg-dark-800 hover:text-slate-900 dark:hover:text-white">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                <span>Billing & Usage</span>
              </a>
              <a href="{{ route('api-keys') }}" class="flex items-center gap-2.5 px-2.5 py-2 rounded-xl text-slate-600 dark:text-dark-300 hover:bg-slate-50 dark:hover:bg-dark-800 hover:text-slate-900 dark:hover:text-white">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
                <span>API Tokens</span>
              </a>
            </div>
            <div class="border-t border-slate-100 dark:border-dark-800 pt-1">
              <form method="POST" action="{{ route('lock.engage') }}">
                @csrf
                <button type="submit" class="w-full flex items-center gap-2.5 px-2.5 py-2 rounded-xl text-slate-600 dark:text-dark-300 hover:bg-slate-50 dark:hover:bg-dark-800 text-xs font-semibold">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                  <span>Lock Screen</span>
                </button>
              </form>
              <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center gap-2.5 px-2.5 py-2 rounded-xl text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-950/40 text-xs font-semibold">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                  <span>Sign Out</span>
                </button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </header>