@extends('layouts.app')

@section('title', 'Search')

@section('content')
  <div class="max-w-3xl mx-auto space-y-6">
    <div>
      <h1 class="text-2xl font-extrabold tracking-tight">Search</h1>
      <form method="GET" action="{{ route('search') }}" class="mt-4 flex items-center gap-2">
        <input type="text" name="q" value="{{ $q }}" autofocus placeholder="Search projects, tasks, tickets, members, help..."
               class="flex-1 text-sm px-4 py-3 rounded-2xl bg-white dark:bg-dark-900 border border-slate-200 dark:border-dark-700 focus:outline-none focus:ring-2 focus:ring-brand-500">
        <button type="submit" class="px-5 py-3 rounded-2xl bg-brand-600 hover:bg-brand-700 text-white text-sm font-bold shadow-md shadow-brand-500/20">Search</button>
      </form>
    </div>

    @if (mb_strlen($q) >= 2)
      @forelse ($results as $group => $items)
      <div>
        <h2 class="text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-2 px-1">{{ $group }}</h2>
        <div class="rounded-2xl bg-white dark:bg-dark-900 border border-slate-200/80 dark:border-dark-800 shadow-sm divide-y divide-slate-50 dark:divide-dark-800/60 overflow-hidden">
          @foreach ($items as $item)
          <a href="{{ $item['url'] }}" class="flex items-center justify-between gap-4 p-4 hover:bg-slate-50 dark:hover:bg-dark-800/60">
            <div>
              <p class="text-xs font-bold text-slate-900 dark:text-white">{{ $item['title'] }}</p>
              <p class="text-[11px] text-slate-400 mt-0.5">{{ $item['subtitle'] }}</p>
            </div>
            <svg class="w-4 h-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
          </a>
          @endforeach
        </div>
      </div>
      @empty
      <div class="text-center py-14">
        <img src="{{ asset('assets/images/illustrations/empty.svg') }}" class="w-24 mx-auto opacity-70" alt="">
        <p class="text-xs text-slate-400 mt-4">No results for "{{ $q }}" — try different keywords.</p>
      </div>
      @endforelse
    @else
      <p class="text-center text-xs text-slate-400 py-10">Type at least two characters to search across the workspace.</p>
    @endif
  </div>
@endsection
