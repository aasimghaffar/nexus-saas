@extends('layouts.app')

@section('title', 'Alerts & Notifications')

@section('content')

      <div>
        <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">Alerts & System Notifications</h1>
        <p class="text-xs sm:text-sm text-slate-500 dark:text-dark-400 mt-1">Contextual callouts, dismissible banners, and animated dynamic toast notifications.</p>
      </div>

      <!-- Dismissible Callout Alerts -->
      <div class="space-y-4">
        <h3 class="text-sm font-bold">Contextual Callout Alerts</h3>
        <div class="space-y-3 text-xs">
          <!-- Success -->
          <div class="p-4 rounded-2xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800/40 flex items-start justify-between gap-3">
            <div class="flex items-start gap-3">
              <svg class="w-5 h-5 text-emerald-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
              <div>
                <h4 class="font-bold text-emerald-900 dark:text-emerald-200">Deployment Successful</h4>
                <p class="text-emerald-700 dark:text-emerald-300 mt-0.5">Your application release v2.4.1 has been successfully routed across 14 edge regions.</p>
              </div>
            </div>
            <button onclick="this.closest('div').remove()" class="text-emerald-600 hover:text-emerald-800">&times;</button>
          </div>

          <!-- Info -->
          <div class="p-4 rounded-2xl bg-brand-50 dark:bg-brand-950/40 border border-brand-200 dark:border-brand-800/40 flex items-start justify-between gap-3">
            <div class="flex items-start gap-3">
              <svg class="w-5 h-5 text-brand-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
              <div>
                <h4 class="font-bold text-brand-900 dark:text-brand-200">New Feature Available</h4>
                <p class="text-brand-700 dark:text-brand-300 mt-0.5">Explore our newly released Kanban board with real-time drag and drop synchronization.</p>
              </div>
            </div>
            <button onclick="this.closest('div').remove()" class="text-brand-600 hover:text-brand-800">&times;</button>
          </div>

          <!-- Warning -->
          <div class="p-4 rounded-2xl bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-800/40 flex items-start justify-between gap-3">
            <div class="flex items-start gap-3">
              <svg class="w-5 h-5 text-amber-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
              <div>
                <h4 class="font-bold text-amber-900 dark:text-amber-200">Rate Limit Quota Nearing</h4>
                <p class="text-amber-700 dark:text-amber-300 mt-0.5">Your API requests have reached 85% of monthly capacity. Upgrade your tier to avoid throttling.</p>
              </div>
            </div>
            <button onclick="this.closest('div').remove()" class="text-amber-600 hover:text-amber-800">&times;</button>
          </div>

          <!-- Danger -->
          <div class="p-4 rounded-2xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800/40 flex items-start justify-between gap-3">
            <div class="flex items-start gap-3">
              <svg class="w-5 h-5 text-rose-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
              <div>
                <h4 class="font-bold text-rose-900 dark:text-rose-200">Payment Authorization Failed</h4>
                <p class="text-rose-700 dark:text-rose-300 mt-0.5">We were unable to charge your primary Visa card ending in 4242. Please update billing details.</p>
              </div>
            </div>
            <button onclick="this.closest('div').remove()" class="text-rose-600 hover:text-rose-800">&times;</button>
          </div>
        </div>
      </div>
    
@endsection
