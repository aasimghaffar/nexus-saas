@extends('layouts.card')

@section('title', 'Confirm Password')

@section('card')
  <div class="max-w-md w-full bg-white dark:bg-dark-900 rounded-3xl shadow-xl border border-slate-200/80 dark:border-dark-800 p-8 sm:p-10 space-y-6 text-center">
    <div class="w-12 h-12 rounded-2xl bg-brand-50 text-brand-600 dark:bg-brand-950/60 dark:text-brand-400 flex items-center justify-center mx-auto">
      <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
    </div>
    <div>
      <h1 class="text-2xl font-extrabold text-slate-900 dark:text-white">Confirm your password</h1>
      <p class="text-xs text-slate-500 dark:text-dark-400 mt-2">This is a secure area of your workspace. Please confirm your password before continuing.</p>
    </div>

    <form method="POST" action="{{ route('password.confirm') }}" class="space-y-4 text-xs">
      @csrf
      <div>
        <input type="password" name="password" placeholder="Current password" autofocus required
               class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 dark:bg-dark-800 border @error('password') border-rose-400 @else border-slate-200 dark:border-dark-700 @enderror text-center focus:outline-none focus:ring-2 focus:ring-brand-500">
        @error('password') <span class="text-rose-500 font-semibold mt-1 block">{{ $message }}</span> @enderror
      </div>
      <button type="submit" class="w-full py-3 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs shadow-md shadow-brand-500/25 transition-all">
        Confirm &amp; Continue
      </button>
    </form>
  </div>
@endsection
