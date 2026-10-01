@extends('layouts.app')

@section('title', 'Buttons & Actions')

@section('content')

      <div>
        <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">Buttons & Interactive Triggers</h1>
        <p class="text-xs sm:text-sm text-slate-500 dark:text-dark-400 mt-1">Multi-style buttons including primary brand, gradients, outlines, loading states, and icon variations.</p>
      </div>

      <!-- 1. Color Variants -->
      <div class="p-6 rounded-2xl bg-white dark:bg-dark-900 border border-slate-200/80 dark:border-dark-800 shadow-sm space-y-4">
        <h3 class="text-sm font-bold">Standard Color Variants</h3>
        <div class="flex flex-wrap items-center gap-3">
          <button class="px-4 py-2 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs shadow-md shadow-brand-500/20 transition-all">Primary Brand</button>
          <button class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-md shadow-emerald-500/20 transition-all">Success</button>
          <button class="px-4 py-2 rounded-xl bg-cyan-600 hover:bg-cyan-700 text-white font-bold text-xs shadow-md shadow-cyan-500/20 transition-all">Info</button>
          <button class="px-4 py-2 rounded-xl bg-amber-500 hover:bg-amber-600 text-white font-bold text-xs shadow-md shadow-amber-500/20 transition-all">Warning</button>
          <button class="px-4 py-2 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs shadow-md shadow-rose-500/20 transition-all">Danger</button>
          <button class="px-4 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 dark:bg-slate-100 dark:hover:bg-white text-white dark:text-slate-900 font-bold text-xs shadow-md transition-all">Dark / Contrast</button>
        </div>
      </div>

      <!-- 2. Soft & Ghost Buttons -->
      <div class="p-6 rounded-2xl bg-white dark:bg-dark-900 border border-slate-200/80 dark:border-dark-800 shadow-sm space-y-4">
        <h3 class="text-sm font-bold">Soft, Ghost & Outline Styles</h3>
        <div class="flex flex-wrap items-center gap-3">
          <button class="px-4 py-2 rounded-xl bg-brand-50 text-brand-700 dark:bg-brand-950/60 dark:text-brand-300 font-bold text-xs hover:bg-brand-100 transition-colors">Soft Brand</button>
          <button class="px-4 py-2 rounded-xl bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300 font-bold text-xs hover:bg-emerald-100 transition-colors">Soft Success</button>
          <button class="px-4 py-2 rounded-xl bg-rose-50 text-rose-700 dark:bg-rose-950/60 dark:text-rose-300 font-bold text-xs hover:bg-rose-100 transition-colors">Soft Danger</button>
          <button class="px-4 py-2 rounded-xl border border-slate-300 dark:border-dark-700 text-slate-700 dark:text-dark-200 font-bold text-xs hover:bg-slate-50 dark:hover:bg-dark-800 transition-colors">Outline Button</button>
          <button class="px-4 py-2 rounded-xl text-brand-600 dark:text-brand-400 font-bold text-xs hover:bg-brand-50 dark:hover:bg-brand-950/40 transition-colors">Ghost Button</button>
        </div>
      </div>

      <!-- 3. Icon Buttons & Loading State -->
      <div class="p-6 rounded-2xl bg-white dark:bg-dark-900 border border-slate-200/80 dark:border-dark-800 shadow-sm space-y-4">
        <h3 class="text-sm font-bold">Icon Buttons & Loading Animations</h3>
        <div class="flex flex-wrap items-center gap-3">
          <button class="flex items-center gap-2 px-4 py-2 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            <span>Create Item</span>
          </button>
          <button class="flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-100 dark:bg-dark-800 text-slate-700 dark:text-dark-200 font-bold text-xs">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
            <span>Download CSV</span>
          </button>
          <button class="flex items-center gap-2 px-4 py-2 rounded-xl bg-brand-600 text-white font-bold text-xs cursor-wait opacity-80" disabled>
            <svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
            <span>Processing...</span>
          </button>
          <!-- Circular Icon Button -->
          <button class="p-2.5 rounded-full bg-slate-100 dark:bg-dark-800 text-slate-600 dark:text-dark-300 hover:bg-brand-50 hover:text-brand-600 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
          </button>
        </div>
      </div>
    
@endsection
