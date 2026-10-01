@extends('layouts.guest')

@section('title', 'Create Account')
@section('showcase-badge', 'Zero Friction Onboarding')
@section('showcase-title', 'Everything you need to ship world-class SaaS software.')
@section('showcase-text', 'Join thousands of high-velocity developers launching profitable products on the Nexus framework.')

@section('form')
  <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white">Create your workspace</h1>
  <p class="text-xs sm:text-sm text-slate-500 dark:text-dark-400 mt-2">Get started with a 14-day free trial on the Enterprise Pro tier.</p>

  <form method="POST" action="{{ route('register') }}" class="space-y-4 text-xs mt-6"
        x-data="passwordStrength()">
    @csrf
    <div class="grid grid-cols-2 gap-3">
      <div>
        <label for="first_name" class="block font-bold mb-1">First Name</label>
        <input id="first_name" name="first_name" type="text" value="{{ old('first_name') }}" placeholder="Alex" required
               class="w-full px-3.5 py-2.5 rounded-xl bg-white dark:bg-dark-900 border border-slate-200 dark:border-dark-700 focus:outline-none focus:ring-2 focus:ring-brand-500">
        @error('first_name') <span class="text-rose-500 font-semibold mt-1 block">{{ $message }}</span> @enderror
      </div>
      <div>
        <label for="last_name" class="block font-bold mb-1">Last Name</label>
        <input id="last_name" name="last_name" type="text" value="{{ old('last_name') }}" placeholder="Morgan" required
               class="w-full px-3.5 py-2.5 rounded-xl bg-white dark:bg-dark-900 border border-slate-200 dark:border-dark-700 focus:outline-none focus:ring-2 focus:ring-brand-500">
        @error('last_name') <span class="text-rose-500 font-semibold mt-1 block">{{ $message }}</span> @enderror
      </div>
    </div>

    <div>
      <label for="company_name" class="block font-bold mb-1">Organization / Company Name</label>
      <input id="company_name" name="company_name" type="text" value="{{ old('company_name') }}" placeholder="Acme Global Inc" required
             class="w-full px-3.5 py-2.5 rounded-xl bg-white dark:bg-dark-900 border border-slate-200 dark:border-dark-700 focus:outline-none focus:ring-2 focus:ring-brand-500">
      @error('company_name') <span class="text-rose-500 font-semibold mt-1 block">{{ $message }}</span> @enderror
    </div>

    <div>
      <label for="email" class="block font-bold mb-1">Work Email</label>
      <input id="email" name="email" type="email" value="{{ old('email') }}" placeholder="alex@company.com" required autocomplete="username"
             class="w-full px-3.5 py-2.5 rounded-xl bg-white dark:bg-dark-900 border border-slate-200 dark:border-dark-700 focus:outline-none focus:ring-2 focus:ring-brand-500">
      @error('email') <span class="text-rose-500 font-semibold mt-1 block">{{ $message }}</span> @enderror
    </div>

    <div>
      <label for="password" class="block font-bold mb-1">Password</label>
      <input id="password" name="password" type="password" placeholder="Create strong password" required autocomplete="new-password"
             x-on:input="score($event.target.value)"
             class="w-full px-3.5 py-2.5 rounded-xl bg-white dark:bg-dark-900 border border-slate-200 dark:border-dark-700 focus:outline-none focus:ring-2 focus:ring-brand-500">
      <div class="grid grid-cols-4 gap-1.5 mt-2">
        <template x-for="i in 4" :key="i">
          <div class="h-1 rounded-full" :class="i <= strength ? barColor : 'bg-slate-200 dark:bg-dark-700'"></div>
        </template>
      </div>
      <span class="text-[10px] font-semibold mt-1 block" :class="textColor" x-text="label"></span>
      @error('password') <span class="text-rose-500 font-semibold mt-1 block">{{ $message }}</span> @enderror
    </div>

    <div>
      <label for="password_confirmation" class="block font-bold mb-1">Confirm Password</label>
      <input id="password_confirmation" name="password_confirmation" type="password" placeholder="Repeat password" required autocomplete="new-password"
             class="w-full px-3.5 py-2.5 rounded-xl bg-white dark:bg-dark-900 border border-slate-200 dark:border-dark-700 focus:outline-none focus:ring-2 focus:ring-brand-500">
    </div>

    <div class="pt-1">
      <label class="flex items-start gap-2 cursor-pointer">
        <input type="checkbox" name="terms" required class="rounded text-brand-600 focus:ring-brand-500 mt-0.5">
        <span class="text-slate-600 dark:text-dark-300">I agree to the <a href="#" class="text-brand-600 dark:text-brand-400 font-bold hover:underline">Terms of Service</a> and Privacy Policy.</span>
      </label>
      @error('terms') <span class="text-rose-500 font-semibold mt-1 block">{{ $message }}</span> @enderror
    </div>

    <button type="submit" class="w-full py-3 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-sm shadow-md shadow-brand-500/25 transition-all mt-2">
      Create Organization &rarr;
    </button>
  </form>

  <p class="text-center text-xs text-slate-500 dark:text-dark-400 mt-6">
    Already have an account? <a href="{{ route('login') }}" class="text-brand-600 dark:text-brand-400 font-bold hover:underline">Sign In</a>
  </p>
@endsection
