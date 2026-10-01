@extends('layouts.app')

@section('title', 'Cards & Widgets')

@section('content')

      <div>
        <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">Cards & Dashboard Widgets</h1>
        <p class="text-xs sm:text-sm text-slate-500 dark:text-dark-400 mt-1">Modular content cards, metric KPIs, user profiles, and glassmorphic containers.</p>
      </div>

      <!-- KPI Stat Cards -->
      <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
        <div class="p-5 rounded-2xl bg-white dark:bg-dark-900 border border-slate-200/80 dark:border-dark-800 shadow-sm space-y-3">
          <div class="flex items-center justify-between">
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Sales</span>
            <span class="p-2 rounded-xl bg-emerald-50 text-emerald-600 dark:bg-emerald-950/60 dark:text-emerald-400 text-xs font-bold">+18.4%</span>
          </div>
          <p class="text-2xl font-extrabold text-slate-900 dark:text-white">$84,290.00</p>
          <p class="text-[11px] text-slate-400">vs. $71,200.00 previous month</p>
        </div>

        <div class="p-5 rounded-2xl bg-white dark:bg-dark-900 border border-slate-200/80 dark:border-dark-800 shadow-sm space-y-3">
          <div class="flex items-center justify-between">
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Active Tenants</span>
            <span class="p-2 rounded-xl bg-brand-50 text-brand-600 dark:bg-brand-950/60 dark:text-brand-400 text-xs font-bold">+6.2%</span>
          </div>
          <p class="text-2xl font-extrabold text-slate-900 dark:text-white">2,849</p>
          <p class="text-[11px] text-slate-400">99.4% retention health score</p>
        </div>

        <div class="p-5 rounded-2xl bg-white dark:bg-dark-900 border border-slate-200/80 dark:border-dark-800 shadow-sm space-y-3">
          <div class="flex items-center justify-between">
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Storage Utilized</span>
            <span class="p-2 rounded-xl bg-amber-50 text-amber-600 dark:bg-amber-950/60 dark:text-amber-400 text-xs font-bold">78%</span>
          </div>
          <p class="text-2xl font-extrabold text-slate-900 dark:text-white">384.2 GB</p>
          <p class="text-[11px] text-slate-400">500 GB pooled capacity</p>
        </div>
      </div>

      <!-- User Profile Card -->
      <div class="p-6 rounded-2xl bg-white dark:bg-dark-900 border border-slate-200/80 dark:border-dark-800 shadow-sm max-w-sm flex items-center gap-4">
        <img src="{{ asset('assets/images/avatars/avatar-1.svg') }}" class="w-12 h-12 rounded-full border-2 border-brand-500">
        <div>
          <h4 class="font-bold text-sm text-slate-900 dark:text-white">Alex Morgan</h4>
          <p class="text-xs text-brand-600 dark:text-brand-400 font-semibold">Chief Technology Officer</p>
          <p class="text-[11px] text-slate-400 mt-0.5">alex@nexus.io &bull; San Francisco</p>
        </div>
      </div>
    
@endsection
