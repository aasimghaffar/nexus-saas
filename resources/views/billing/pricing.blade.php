@extends('layouts.app')

@section('title', 'Pricing')

@section('content')
  <div class="text-center max-w-xl mx-auto">
    <h1 class="text-2xl font-extrabold tracking-tight">Simple, transparent pricing</h1>
    <p class="text-xs text-slate-500 dark:text-dark-400 mt-2">Start free, upgrade when your team grows. All plans include the core platform.</p>
  </div>

  <div class="grid grid-cols-1 md:grid-cols-3 gap-4 max-w-4xl mx-auto">
    @foreach (config('nexus.plans') as $key => $plan)
      <div class="p-6 rounded-2xl bg-white dark:bg-dark-900 border {{ !empty($plan['featured']) ? 'border-brand-500 ring-2 ring-brand-500/20 relative' : 'border-slate-200/80 dark:border-dark-800' }} shadow-sm flex flex-col">
        @if (!empty($plan['featured']))
          <span class="absolute -top-2.5 left-1/2 -translate-x-1/2 px-3 py-0.5 rounded-full bg-brand-600 text-white text-[10px] font-bold uppercase">Most Popular</span>
        @endif
        <h3 class="text-lg font-bold">{{ $plan['name'] }}</h3>
        <p class="text-3xl font-extrabold mt-2">${{ $plan['price'] }}<span class="text-xs font-semibold text-slate-400">/{{ $plan['interval'] }}</span></p>
        <ul class="text-xs text-slate-500 dark:text-dark-400 space-y-2 mt-5 mb-6">
          @foreach ($plan['features'] as $feature)
            <li class="flex items-center gap-2"><svg class="w-4 h-4 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>{{ $feature }}</li>
          @endforeach
        </ul>
        <form method="POST" action="{{ route('billing.subscribe') }}" class="mt-auto">
          @csrf
          <input type="hidden" name="plan" value="{{ $key }}">
          <button type="submit" class="w-full py-3 rounded-xl {{ !empty($plan['featured']) ? 'bg-brand-600 hover:bg-brand-700 text-white shadow-md shadow-brand-500/25' : 'bg-slate-100 hover:bg-slate-200 dark:bg-dark-800 dark:hover:bg-dark-700' }} text-xs font-bold">
            Get {{ $plan['name'] }}
          </button>
        </form>
      </div>
    @endforeach
  </div>

  <p class="text-center text-[11px] text-slate-400">Prices in USD. Cancel anytime — you keep access until the end of the billing period.</p>
@endsection
