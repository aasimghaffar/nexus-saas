@extends('layouts.app')

@section('title', 'Blank Page')

@section('content')
  <div>
    <h1 class="text-2xl font-extrabold tracking-tight">Blank Starter</h1>
    <p class="text-xs text-slate-500 dark:text-dark-400 mt-1">A clean canvas inside the app shell — duplicate this view to prototype new screens fast.</p>
  </div>
  <div class="rounded-2xl bg-white dark:bg-dark-900 border border-dashed border-slate-300 dark:border-dark-700 p-16 text-center">
    <img src="{{ asset('assets/images/illustrations/empty.svg') }}" class="w-20 mx-auto opacity-70" alt="">
    <p class="text-xs text-slate-400 mt-4">Start building here.</p>
  </div>
@endsection
