@extends('layouts.guest')

@section('title', 'Forgot Password')
@section('showcase-badge', 'Account Recovery')
@section('showcase-title', 'Locked out? We will get you back in safely.')
@section('showcase-text', 'Password reset links are single-use and expire automatically to keep your workspace secure.')

@section('form')
  <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white">Reset your password</h1>
  <p class="text-xs sm:text-sm text-slate-500 dark:text-dark-400 mt-2">Enter the work email tied to your account and we'll send a secure reset link.</p>

  <form method="POST" action="{{ route('password.email') }}" class="space-y-4 text-xs mt-6">
    @csrf
    <div>
      <label for="email" class="block font-bold mb-1">Work Email</label>
      <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus
             class="w-full px-3.5 py-2.5 rounded-xl bg-white dark:bg-dark-900 border @error('email') border-rose-400 @else border-slate-200 dark:border-dark-700 @enderror focus:outline-none focus:ring-2 focus:ring-brand-500">
      @error('email') <span class="text-rose-500 font-semibold mt-1 block">{{ $message }}</span> @enderror
    </div>

    <button type="submit" class="w-full py-3 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-sm shadow-md shadow-brand-500/25 transition-all mt-2">
      Send Reset Link
    </button>
  </form>

  <p class="text-center text-xs text-slate-500 dark:text-dark-400 mt-6">
    Remembered it after all? <a href="{{ route('login') }}" class="text-brand-600 dark:text-brand-400 font-bold hover:underline">&larr; Back to Sign In</a>
  </p>
@endsection
