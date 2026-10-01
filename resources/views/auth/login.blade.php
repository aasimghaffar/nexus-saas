@extends('layouts.guest')

@section('title', 'Sign In')

@section('form')
  <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white">Welcome back</h1>
  <p class="text-xs sm:text-sm text-slate-500 dark:text-dark-400 mt-2">Enter your work credentials to access your administrative dashboard.</p>

  {{-- Social login placeholders — wire to Socialite in a later phase or remove --}}
  <div class="grid grid-cols-2 gap-3 mt-6">
    <button type="button" class="flex items-center justify-center gap-2.5 py-2.5 px-4 rounded-xl border border-slate-200 dark:border-dark-700 bg-white dark:bg-dark-900 text-xs font-bold shadow-sm hover:bg-slate-50 dark:hover:bg-dark-800 transition-colors">
      <img src="{{ asset('assets/images/brands/google.svg') }}" class="w-4 h-4" alt=""><span>Google Workspace</span>
    </button>
    <button type="button" class="flex items-center justify-center gap-2.5 py-2.5 px-4 rounded-xl border border-slate-200 dark:border-dark-700 bg-white dark:bg-dark-900 text-xs font-bold shadow-sm hover:bg-slate-50 dark:hover:bg-dark-800 transition-colors">
      <img src="{{ asset('assets/images/brands/github.svg') }}" class="w-4 h-4" alt=""><span>GitHub SSO</span>
    </button>
  </div>

  <div class="relative flex items-center justify-center my-6">
    <div class="border-t border-slate-200 dark:border-dark-800 w-full"></div>
    <span class="bg-slate-50 dark:bg-dark-950 px-3 text-[11px] uppercase font-bold text-slate-400 absolute">or with email</span>
  </div>

  <form method="POST" action="{{ route('login') }}" class="space-y-4 text-xs">
    @csrf
    <div>
      <label for="email" class="block font-bold mb-1">Work Email</label>
      <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus autocomplete="username"
             class="w-full px-3.5 py-2.5 rounded-xl bg-white dark:bg-dark-900 border @error('email') border-rose-400 @else border-slate-200 dark:border-dark-700 @enderror focus:outline-none focus:ring-2 focus:ring-brand-500">
      @error('email') <span class="text-rose-500 font-semibold mt-1 block">{{ $message }}</span> @enderror
    </div>
    <div>
      <div class="flex items-center justify-between mb-1">
        <label for="password" class="font-bold">Password</label>
        <a href="{{ route('password.request') }}" class="text-brand-600 dark:text-brand-400 font-bold hover:underline">Forgot password?</a>
      </div>
      <input id="password" name="password" type="password" required autocomplete="current-password"
             class="w-full px-3.5 py-2.5 rounded-xl bg-white dark:bg-dark-900 border @error('password') border-rose-400 @else border-slate-200 dark:border-dark-700 @enderror focus:outline-none focus:ring-2 focus:ring-brand-500">
      @error('password') <span class="text-rose-500 font-semibold mt-1 block">{{ $message }}</span> @enderror
    </div>

    <div class="flex items-center justify-between pt-1">
      <label class="flex items-center gap-2 cursor-pointer">
        <input type="checkbox" name="remember" class="rounded text-brand-600 focus:ring-brand-500">
        <span class="text-slate-600 dark:text-dark-300 font-medium">Remember for 30 days</span>
      </label>
    </div>

    <button type="submit" class="w-full py-3 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-sm shadow-md shadow-brand-500/25 transition-all mt-2">
      Sign In to Dashboard
    </button>
  </form>

  <p class="text-center text-xs text-slate-500 dark:text-dark-400 mt-6">
    Don't have an organization account? <a href="{{ route('register') }}" class="text-brand-600 dark:text-brand-400 font-bold hover:underline">Start free trial &rarr;</a>
  </p>
@endsection
