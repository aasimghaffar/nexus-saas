@extends('layouts.guest')

@section('title', 'Choose New Password')
@section('showcase-badge', 'Account Recovery')
@section('showcase-title', 'Choose a strong password to secure your workspace.')
@section('showcase-text', 'Use at least 8 characters with a mix of letters, numbers, and symbols.')

@section('form')
  <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white">Set a new password</h1>
  <p class="text-xs sm:text-sm text-slate-500 dark:text-dark-400 mt-2">Almost done — pick a new password for <strong>{{ request('email') }}</strong>.</p>

  <form method="POST" action="{{ route('password.update') }}" class="space-y-4 text-xs mt-6">
    @csrf
    <input type="hidden" name="token" value="{{ request()->route('token') ?? '' }}">
    <input type="hidden" name="email" value="{{ request('email') }}">

    <div>
      <label for="password" class="block font-bold mb-1">New Password</label>
      <input id="password" name="password" type="password" required autofocus autocomplete="new-password"
             class="w-full px-3.5 py-2.5 rounded-xl bg-white dark:bg-dark-900 border @error('password') border-rose-400 @else border-slate-200 dark:border-dark-700 @enderror focus:outline-none focus:ring-2 focus:ring-brand-500">
      @error('password') <span class="text-rose-500 font-semibold mt-1 block">{{ $message }}</span> @enderror
    </div>
    <div>
      <label for="password_confirmation" class="block font-bold mb-1">Confirm New Password</label>
      <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password"
             class="w-full px-3.5 py-2.5 rounded-xl bg-white dark:bg-dark-900 border border-slate-200 dark:border-dark-700 focus:outline-none focus:ring-2 focus:ring-brand-500">
    </div>

    <button type="submit" class="w-full py-3 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-sm shadow-md shadow-brand-500/25 transition-all mt-2">
      Update Password &rarr;
    </button>
  </form>
@endsection
