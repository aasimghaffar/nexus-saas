@extends('layouts.app')

@section('title', 'Tabs & Accordions')

@section('content')

      <div>
        <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">Tabs, Navigation & Accordions</h1>
        <p class="text-xs sm:text-sm text-slate-500 dark:text-dark-400 mt-1">Multi-variant tab switchers and expandable accordion containers.</p>
      </div>

      <!-- Underline Tabs -->
      <div class="p-6 rounded-2xl bg-white dark:bg-dark-900 border border-slate-200/80 dark:border-dark-800 shadow-sm space-y-4">
        <h3 class="text-sm font-bold">Underline Tab Bar</h3>
        <div class="border-b border-slate-200 dark:border-dark-800 flex gap-6 text-xs font-bold">
          <button class="pb-3 text-brand-600 border-b-2 border-brand-600">General Information</button>
          <button class="pb-3 text-slate-400 hover:text-slate-600">Security & 2FA</button>
          <button class="pb-3 text-slate-400 hover:text-slate-600">Billing History</button>
        </div>
      </div>

      <!-- Expandable Accordion -->
      <div class="p-6 rounded-2xl bg-white dark:bg-dark-900 border border-slate-200/80 dark:border-dark-800 shadow-sm space-y-3">
        <h3 class="text-sm font-bold">Collapsible Accordion Group</h3>
        <details class="p-4 rounded-xl bg-slate-50 dark:bg-dark-800 text-xs" open>
          <summary class="font-bold cursor-pointer list-none flex justify-between items-center text-slate-900 dark:text-white">
            <span>What is the refund policy for subscriptions?</span>
            <span>&darr;</span>
          </summary>
          <p class="text-slate-500 dark:text-dark-300 mt-2 leading-relaxed">We offer a 30-day money-back guarantee on all annual subscriptions. Monthly subscriptions can be canceled at any time.</p>
        </details>
      </div>
    
@endsection
