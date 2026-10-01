@extends('layouts.card')

@section('title', 'Session Locked')

@section('card')
  <div class="max-w-md w-full bg-white dark:bg-dark-900 rounded-3xl shadow-xl border border-slate-200/80 dark:border-dark-800 p-8 sm:p-10 space-y-6 text-center">
    <div>
      <img src="{{ auth()->user()->avatar_url }}" class="w-20 h-20 rounded-full mx-auto border-4 border-brand-500 shadow-md" alt="{{ auth()->user()->name }}">
      <h1 class="text-xl font-extrabold text-slate-900 dark:text-white mt-4">{{ auth()->user()->name }}</h1>
      <p class="text-xs text-slate-400">{{ auth()->user()->email }} &bull; Session Locked</p>
    </div>

    <form method="POST" action="{{ route('lock.release') }}" class="space-y-4 text-xs">
      @csrf
      <div>
        <input type="password" name="password" placeholder="Enter password to unlock" autofocus required
               class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 dark:bg-dark-800 border @error('password') border-rose-400 @else border-slate-200 dark:border-dark-700 @enderror text-center focus:outline-none focus:ring-2 focus:ring-brand-500">
        @error('password') <span class="text-rose-500 font-semibold mt-1 block">{{ $message }}</span> @enderror
      </div>
      <button type="submit" class="w-full py-3 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs shadow-md shadow-brand-500/25 transition-all">
        Unlock Session
      </button>
    </form>

    <div class="pt-2 border-t border-slate-100 dark:border-dark-800 text-xs">
      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="text-slate-400 hover:text-brand-600 dark:hover:text-brand-400 font-bold">Sign in with a different account &rarr;</button>
      </form>
    </div>
  </div>
@endsection
