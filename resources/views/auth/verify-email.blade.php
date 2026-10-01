@extends('layouts.card')

@section('title', 'Verify Email')

@section('card')
  <div class="max-w-md w-full bg-white dark:bg-dark-900 rounded-3xl shadow-xl border border-slate-200/80 dark:border-dark-800 p-8 sm:p-10 space-y-6 text-center">
    <div class="w-12 h-12 rounded-2xl bg-brand-50 text-brand-600 dark:bg-brand-950/60 dark:text-brand-400 flex items-center justify-center mx-auto">
      <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
    </div>
    <div>
      <h1 class="text-2xl font-extrabold text-slate-900 dark:text-white">Verify your email</h1>
      <p class="text-xs text-slate-500 dark:text-dark-400 mt-2">We've sent a verification link to <strong>{{ auth()->user()->email }}</strong>. Click it to activate your workspace.</p>
    </div>

    @if (session('status') == 'verification-link-sent')
      <p class="text-xs font-semibold text-emerald-600 dark:text-emerald-400">A fresh verification link has been sent.</p>
    @endif

    <form method="POST" action="{{ route('verification.send') }}">
      @csrf
      <button type="submit" class="w-full py-3 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs shadow-md shadow-brand-500/25 transition-all">
        Resend Verification Email
      </button>
    </form>

    <form method="POST" action="{{ route('logout') }}" class="pt-2 border-t border-slate-100 dark:border-dark-800">
      @csrf
      <button type="submit" class="text-xs text-slate-400 hover:text-brand-600 dark:hover:text-brand-400 font-bold">Sign out</button>
    </form>
  </div>
@endsection
