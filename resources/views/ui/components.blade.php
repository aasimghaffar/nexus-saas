@extends('layouts.app')

@section('title', 'UI Components Kit')

@section('content')

      <div>
        <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">Design System & UI Components</h1>
        <p class="text-xs sm:text-sm text-slate-500 dark:text-dark-400 mt-1">A comprehensive suite of accessible, production-ready Tailwind CSS widgets and controls.</p>
      </div>

      <!-- Section 1: Buttons -->
      <div class="space-y-4">
        <h2 class="text-base font-bold pb-2 border-b border-slate-200 dark:border-dark-800">1. Buttons & Action Triggers</h2>
        <div class="p-6 rounded-2xl bg-white dark:bg-dark-900 border border-slate-200/80 dark:border-dark-800 shadow-sm flex flex-wrap items-center gap-3">
          <button class="px-4 py-2 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs shadow-md shadow-brand-500/20">Primary Brand</button>
          <button class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-dark-800 dark:hover:bg-dark-700 text-slate-800 dark:text-white font-bold text-xs">Secondary</button>
          <button class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs">Success</button>
          <button class="px-4 py-2 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs">Danger</button>
          <button class="px-4 py-2 rounded-xl border border-slate-200 dark:border-dark-700 font-bold text-xs hover:bg-slate-50 dark:hover:bg-dark-800">Outline</button>
          <button class="px-4 py-2 rounded-xl text-brand-600 dark:text-brand-400 font-bold text-xs hover:bg-brand-50 dark:hover:bg-brand-950/40">Ghost</button>
          <button class="px-4 py-2 rounded-xl bg-brand-600 text-white font-bold text-xs opacity-50 cursor-not-allowed" disabled>Disabled</button>
        </div>
      </div>

      <!-- Section 2: Badges & Tags -->
      <div class="space-y-4">
        <h2 class="text-base font-bold pb-2 border-b border-slate-200 dark:border-dark-800">2. Badges, Chips & Status Indicators</h2>
        <div class="p-6 rounded-2xl bg-white dark:bg-dark-900 border border-slate-200/80 dark:border-dark-800 shadow-sm flex flex-wrap items-center gap-3">
          <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-brand-50 text-brand-700 dark:bg-brand-950/60 dark:text-brand-400 border border-brand-200 dark:border-brand-800/40">Brand Indigo</span>
          <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800/40">Active / Paid</span>
          <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 dark:bg-amber-950/60 dark:text-amber-400 border border-amber-200 dark:border-amber-800/40">Pending</span>
          <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-700 dark:bg-rose-950/60 dark:text-rose-400 border border-rose-200 dark:border-rose-800/40">Failed</span>
          <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-cyan-50 text-cyan-700 dark:bg-cyan-950/60 dark:text-cyan-400 border border-cyan-200 dark:border-cyan-800/40">In Review</span>
        </div>
      </div>

      <!-- Section 3: Alerts -->
      <div class="space-y-4">
        <h2 class="text-base font-bold pb-2 border-b border-slate-200 dark:border-dark-800">3. Contextual Alerts</h2>
        <div class="space-y-3 text-xs">
          <div class="p-4 rounded-2xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800/40 flex items-start gap-3">
            <svg class="w-5 h-5 text-emerald-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            <div>
              <h4 class="font-bold text-emerald-900 dark:text-emerald-200">Success Notification</h4>
              <p class="text-emerald-700 dark:text-emerald-300 mt-0.5">Your changes have been automatically deployed to all global edge regions.</p>
            </div>
          </div>
          <div class="p-4 rounded-2xl bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-800/40 flex items-start gap-3">
            <svg class="w-5 h-5 text-amber-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            <div>
              <h4 class="font-bold text-amber-900 dark:text-amber-200">Warning Alert</h4>
              <p class="text-amber-700 dark:text-amber-300 mt-0.5">Your monthly storage quota is nearing its capacity (88% utilized).</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Section 4: Interactive Toasts -->
      <div class="space-y-4">
        <h2 class="text-base font-bold pb-2 border-b border-slate-200 dark:border-dark-800">4. Dynamic Toast Notifications</h2>
        <div class="p-6 rounded-2xl bg-white dark:bg-dark-900 border border-slate-200/80 dark:border-dark-800 shadow-sm flex flex-wrap items-center gap-3">
          <button onclick="NexusApp.showToast({title:'Success!', message:'Record saved successfully.', type:'success'})" class="px-4 py-2 rounded-xl bg-emerald-600 text-white font-bold text-xs">Trigger Success Toast</button>
          <button onclick="NexusApp.showToast({title:'Error Encountered', message:'Database connection timed out.', type:'error'})" class="px-4 py-2 rounded-xl bg-rose-600 text-white font-bold text-xs">Trigger Error Toast</button>
          <button onclick="NexusApp.showToast({title:'Rate Limit', message:'You have 5 requests remaining.', type:'warning'})" class="px-4 py-2 rounded-xl bg-amber-600 text-white font-bold text-xs">Trigger Warning Toast</button>
          <button onclick="NexusApp.showToast({title:'New Feature', message:'Check out our new Kanban board.', type:'info'})" class="px-4 py-2 rounded-xl bg-brand-600 text-white font-bold text-xs">Trigger Info Toast</button>
        </div>
      </div>
    
@endsection
