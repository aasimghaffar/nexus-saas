@extends('layouts.app')

@section('title', 'Modals & Dialogs')

@section('content')

      <div>
        <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">Modals & Interactive Overlays</h1>
        <p class="text-xs sm:text-sm text-slate-500 dark:text-dark-400 mt-1">Accessible modal dialogs with focus trapping, backdrop blurring, and ESC key dismiss.</p>
      </div>

      <div class="p-6 rounded-2xl bg-white dark:bg-dark-900 border border-slate-200/80 dark:border-dark-800 shadow-sm space-y-4">
        <h3 class="text-sm font-bold">Interactive Modal Triggers</h3>
        <div class="flex flex-wrap items-center gap-4">
          <button data-modal-open="demo-standard-modal" class="px-4 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs shadow-md shadow-brand-500/20">
            Open Standard Modal
          </button>
          <button data-modal-open="demo-danger-modal" class="px-4 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs shadow-md shadow-rose-500/20">
            Open Danger Confirmation
          </button>
        </div>
      </div>
    
@endsection
