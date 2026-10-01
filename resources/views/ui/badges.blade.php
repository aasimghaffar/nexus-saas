@extends('layouts.app')

@section('title', 'Badges & Chips')

@section('content')

      <div>
        <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">Badges, Tags & Status Chips</h1>
        <p class="text-xs sm:text-sm text-slate-500 dark:text-dark-400 mt-1">Lightweight badges with status dot indicators, pill shapes, and notification counts.</p>
      </div>

      <!-- 1. Soft Badges -->
      <div class="p-6 rounded-2xl bg-white dark:bg-dark-900 border border-slate-200/80 dark:border-dark-800 shadow-sm space-y-4">
        <h3 class="text-sm font-bold">Soft Pastel Badges</h3>
        <div class="flex flex-wrap items-center gap-3">
          <span class="px-3 py-1 rounded-full text-xs font-bold bg-brand-50 text-brand-700 dark:bg-brand-950/60 dark:text-brand-300">Indigo Brand</span>
          <span class="px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300">Completed</span>
          <span class="px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 dark:bg-amber-950/60 dark:text-amber-300">Pending Review</span>
          <span class="px-3 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-700 dark:bg-rose-950/60 dark:text-rose-300">Action Required</span>
          <span class="px-3 py-1 rounded-full text-xs font-bold bg-cyan-50 text-cyan-700 dark:bg-cyan-950/60 dark:text-cyan-300">Live Production</span>
        </div>
      </div>

      <!-- 2. Dot Indicator Badges -->
      <div class="p-6 rounded-2xl bg-white dark:bg-dark-900 border border-slate-200/80 dark:border-dark-800 shadow-sm space-y-4">
        <h3 class="text-sm font-bold">Status Dot Indicator Badges</h3>
        <div class="flex flex-wrap items-center gap-3">
          <span class="flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800/40">
            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-ping"></span>
            <span>Online</span>
          </span>
          <span class="flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 dark:bg-amber-950/60 dark:text-amber-300 border border-amber-200 dark:border-amber-800/40">
            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
            <span>Away</span>
          </span>
          <span class="flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 dark:bg-rose-950/60 dark:text-rose-300 border border-rose-200 dark:border-rose-800/40">
            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
            <span>Offline</span>
          </span>
        </div>
      </div>
    
@endsection
