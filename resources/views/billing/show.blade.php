@extends('layouts.app')

@section('title', 'Subscription & Billing')

@section('content')
  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
      <h1 class="text-2xl font-extrabold tracking-tight">Subscription &amp; Billing Management</h1>
      <p class="text-xs text-slate-500 dark:text-dark-400 mt-1">Manage your plan, payment methods, and billing history.</p>
    </div>
    @if ($stripeReady && $user->hasStripeId())
      <a href="{{ route('billing.portal') }}" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-dark-800 dark:hover:bg-dark-700 text-xs font-semibold">Open Stripe Billing Portal</a>
    @endif
  </div>

  @unless ($stripeReady)
    <div class="rounded-xl bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-900 text-amber-700 dark:text-amber-400 text-xs font-semibold px-4 py-3">
      Stripe is not configured yet. Add STRIPE_KEY, STRIPE_SECRET, and the STRIPE_PRICE_* IDs to your .env to activate live billing. The page below runs in preview mode.
    </div>
  @endunless

  @if (request('checkout') === 'success')
    <div class="rounded-xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-900 text-emerald-700 dark:text-emerald-400 text-xs font-semibold px-4 py-3">
      Subscription activated — welcome aboard! Your invoice will appear in Billing History shortly.
    </div>
  @endif

  {{-- Current plan + meters --}}
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
    <div class="lg:col-span-2 p-6 rounded-2xl bg-gradient-to-br from-brand-900 via-indigo-950 to-dark-950 text-white shadow-md relative overflow-hidden">
      <p class="text-[11px] font-bold uppercase tracking-wider text-brand-300">Current Plan</p>
      <h2 class="text-2xl font-extrabold mt-2">{{ $currentPlan['name'] ?? 'Free Preview' }}</h2>
      <p class="text-xs text-slate-300 mt-1">
        @if ($subscription?->onGracePeriod())
          Cancelled — access until {{ $subscription->ends_at->format('d M Y') }}.
        @elseif ($currentPlan)
          ${{ $currentPlan['price'] }}/{{ $currentPlan['interval'] }} &bull; renews {{ $upcoming?->date()?->format('d M Y') ?? 'monthly' }}
        @else
          No active subscription — choose a plan below to unlock full capacity.
        @endif
      </p>
      <div class="flex items-center gap-2 mt-5">
        @if ($subscription?->onGracePeriod())
          <form method="POST" action="{{ route('billing.resume') }}">@csrf
            <button class="px-4 py-2 rounded-xl bg-white text-brand-800 text-xs font-bold">Resume Subscription</button>
          </form>
        @elseif ($subscription?->active())
          <form method="POST" action="{{ route('billing.cancel') }}">@csrf
            <button class="px-4 py-2 rounded-xl bg-white/10 border border-white/20 text-xs font-bold hover:bg-white/20">Cancel Plan</button>
          </form>
        @endif
      </div>
    </div>

    <div class="p-6 rounded-2xl bg-white dark:bg-dark-900 border border-slate-200/80 dark:border-dark-800 shadow-sm space-y-4">
      <div>
        <div class="flex items-center justify-between text-xs font-bold"><span>Team Seats</span><span class="text-slate-400">{{ $seatsUsed }} / {{ $seatLimit }}</span></div>
        <div class="w-full h-1.5 bg-slate-100 dark:bg-dark-800 rounded-full mt-2 overflow-hidden">
          <div class="h-full {{ $seatsUsed >= $seatLimit ? 'bg-rose-500' : 'bg-brand-600' }} rounded-full" style="width: {{ min(100, round($seatsUsed / max(1, $seatLimit) * 100)) }}%"></div>
        </div>
      </div>
      <div>
        <div class="flex items-center justify-between text-xs font-bold"><span>Plan Tier</span><span class="text-slate-400">{{ $currentPlan['name'] ?? '—' }}</span></div>
      </div>
      <div>
        <div class="flex items-center justify-between text-xs font-bold"><span>Next Invoice</span>
          <span class="text-slate-400">{{ $upcoming ? '$'.number_format($upcoming->rawTotal() / 100, 2) : '—' }}</span>
        </div>
      </div>
    </div>
  </div>

  {{-- Plan switcher --}}
  <div class="rounded-2xl bg-white dark:bg-dark-900 border border-slate-200/80 dark:border-dark-800 shadow-sm p-6">
    <h3 class="text-base font-bold">{{ $subscription?->active() ? 'Change Plan' : 'Choose a Plan' }}</h3>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-5">
      @foreach ($plans as $key => $plan)
        <div class="p-5 rounded-2xl border {{ $currentPlanKey === $key ? 'border-brand-500 ring-2 ring-brand-500/30' : 'border-slate-200 dark:border-dark-700' }} flex flex-col">
          <div class="flex items-center justify-between">
            <h4 class="text-sm font-bold">{{ $plan['name'] }}</h4>
            @if ($currentPlanKey === $key)
              <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-brand-50 text-brand-700 dark:bg-brand-950/60 dark:text-brand-400">Current</span>
            @elseif (!empty($plan['featured']))
              <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-400">Popular</span>
            @endif
          </div>
          <p class="text-2xl font-extrabold mt-2">${{ $plan['price'] }}<span class="text-xs font-semibold text-slate-400">/{{ $plan['interval'] }}</span></p>
          <ul class="text-[11px] text-slate-500 dark:text-dark-400 space-y-1.5 mt-3 mb-5">
            @foreach ($plan['features'] as $feature)
              <li class="flex items-center gap-1.5"><svg class="w-3.5 h-3.5 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>{{ $feature }}</li>
            @endforeach
          </ul>
          @if ($currentPlanKey !== $key)
            <form method="POST" action="{{ route('billing.subscribe') }}" class="mt-auto">
              @csrf
              <input type="hidden" name="plan" value="{{ $key }}">
              <button type="submit" @disabled(!$stripeReady) class="w-full py-2.5 rounded-xl {{ !empty($plan['featured']) ? 'bg-brand-600 hover:bg-brand-700 text-white' : 'bg-slate-100 hover:bg-slate-200 dark:bg-dark-800 dark:hover:bg-dark-700' }} text-xs font-bold disabled:opacity-40">
                {{ $subscription?->active() ? 'Switch to '.$plan['name'] : 'Subscribe' }}
              </button>
            </form>
          @endif
        </div>
      @endforeach
    </div>
  </div>

  {{-- Payment methods + billing history --}}
  <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
    <div class="rounded-2xl bg-white dark:bg-dark-900 border border-slate-200/80 dark:border-dark-800 shadow-sm p-6">
      <h3 class="text-base font-bold">Payment Methods</h3>
      <p class="text-xs text-slate-400 mt-1 mb-4">Cards are managed securely by Stripe — add or remove via the Billing Portal.</p>
      <div class="space-y-3 text-xs">
        @forelse ($paymentMethods as $pm)
          <div class="flex items-center justify-between p-3.5 rounded-xl border border-slate-100 dark:border-dark-800">
            <div class="flex items-center gap-3">
              <span class="px-2 py-1 rounded-lg bg-slate-100 dark:bg-dark-800 font-bold uppercase text-[10px]">{{ $pm->card->brand }}</span>
              <span class="font-bold">•••• {{ $pm->card->last4 }}</span>
            </div>
            <span class="text-slate-400">Expires {{ $pm->card->exp_month }}/{{ $pm->card->exp_year }}</span>
          </div>
        @empty
          <p class="text-slate-400">No saved payment methods yet.</p>
        @endforelse
      </div>
    </div>

    <div class="rounded-2xl bg-white dark:bg-dark-900 border border-slate-200/80 dark:border-dark-800 shadow-sm p-6">
      <h3 class="text-base font-bold">Billing History</h3>
      <p class="text-xs text-slate-400 mt-1 mb-4">Your most recent invoices.</p>
      <div class="space-y-2 text-xs">
        @if ($stripeReady && $user->hasStripeId())
          @forelse ($user->invoices()->take(5) as $invoice)
            <a href="{{ route('billing.invoices.show', $invoice->id) }}" class="flex items-center justify-between p-3 rounded-xl hover:bg-slate-50 dark:hover:bg-dark-800">
              <span class="font-bold font-mono">#{{ $invoice->number ?? $invoice->id }}</span>
              <span class="text-slate-400">{{ $invoice->date()->format('d M Y') }}</span>
              <span class="font-bold">{{ $invoice->total() }}</span>
            </a>
          @empty
            <p class="text-slate-400">No invoices yet.</p>
          @endforelse
        @else
          <p class="text-slate-400">Invoices appear here once billing is active.</p>
        @endif
      </div>
      <a href="{{ route('billing.invoices') }}" class="inline-block mt-4 text-xs font-bold text-brand-600 dark:text-brand-400 hover:underline">View all invoices &rarr;</a>
    </div>
  </div>
@endsection
