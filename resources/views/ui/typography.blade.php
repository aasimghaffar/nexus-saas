@extends('layouts.app')

@section('title', 'Typography & Content')

@section('content')

      <div>
        <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">Typography, Headings & Code Elements</h1>
        <p class="text-xs sm:text-sm text-slate-500 dark:text-dark-400 mt-1">Hierarchical headings, body copy, inline code snippets, and keyboard shortcut tokens.</p>
      </div>

      <div class="p-6 rounded-2xl bg-white dark:bg-dark-900 border border-slate-200/80 dark:border-dark-800 shadow-sm space-y-4">
        <h1 class="text-3xl font-extrabold">Heading 1 (30px Bold)</h1>
        <h2 class="text-2xl font-bold">Heading 2 (24px Bold)</h2>
        <h3 class="text-xl font-bold">Heading 3 (20px SemiBold)</h3>
        <p class="text-sm text-slate-600 dark:text-dark-300 leading-relaxed">
          Regular paragraph body copy using <strong class="text-slate-900 dark:text-white">Plus Jakarta Sans</strong> paired with Inter for numerals. Clean, crisp anti-aliased rendering.
        </p>
        <div class="pt-2 flex items-center gap-3">
          <span class="text-xs text-slate-400">Keyboard shortcuts:</span>
          <kbd class="px-2 py-1 bg-slate-100 dark:bg-dark-800 border border-slate-200 dark:border-dark-700 rounded-lg text-xs font-mono font-bold">⌘ K</kbd>
          <kbd class="px-2 py-1 bg-slate-100 dark:bg-dark-800 border border-slate-200 dark:border-dark-700 rounded-lg text-xs font-mono font-bold">ESC</kbd>
        </div>
      </div>
    
@endsection
