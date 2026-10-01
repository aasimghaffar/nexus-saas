@extends('layouts.app')

@section('title', 'Invoice #'.($invoice->number ?? $invoice->id))

@section('content')
  <div class="flex items-center justify-between no-print">
    <a href="{{ route('billing.invoices') }}" class="text-xs font-bold text-slate-500 hover:text-brand-600 dark:hover:text-brand-400">&larr; Back to Invoices</a>
    <div class="space-x-2">
      <button onclick="window.print()" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-dark-800 dark:hover:bg-dark-700 text-xs font-semibold">Print</button>
      <a href="{{ route('billing.invoices.download', $invoice->id) }}" class="px-4 py-2 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-semibold shadow-md shadow-brand-500/20">Download PDF</a>
    </div>
  </div>

  <div class="rounded-2xl bg-white dark:bg-dark-900 border border-slate-200/80 dark:border-dark-800 shadow-sm p-8 print-full-width">
    <div class="flex flex-col sm:flex-row justify-between gap-6 pb-6 border-b border-slate-100 dark:border-dark-800">
      <div>
        <img src="{{ asset('assets/images/logo-light.svg') }}" class="h-8 dark:hidden" alt="{{ config('app.name') }}">
        <img src="{{ asset('assets/images/logo-dark.svg') }}" class="h-8 hidden dark:block print-hidden" alt="">
        <p class="text-xs text-slate-400 mt-3">{{ config('app.name') }}<br>Subscription Services</p>
      </div>
      <div class="sm:text-right">
        <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Invoice</p>
        <h1 class="text-xl font-extrabold font-mono mt-2 text-slate-900 dark:text-white">#{{ $invoice->number ?? $invoice->id }}</h1>
        <p class="text-xs text-slate-400 mt-1">Issued {{ $invoice->date()->format('d M Y') }}</p>
        <span class="inline-block mt-2 px-2.5 py-0.5 rounded-full text-[10px] font-bold {{ $invoice->status === 'paid' ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700' }}">{{ ucfirst($invoice->status ?? 'draft') }}</span>
      </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 py-6 text-xs">
      <div>
        <p class="font-bold uppercase text-[10px] tracking-wider text-slate-400 mb-2">Billed To</p>
        <p class="font-bold text-slate-900 dark:text-white">{{ $user->company_name ?? $user->name }}</p>
        <p class="text-slate-500 dark:text-dark-400">{{ $user->name }}<br>{{ $user->email }}</p>
      </div>
      <div class="sm:text-right">
        <p class="font-bold uppercase text-[10px] tracking-wider text-slate-400 mb-2">Payment</p>
        <p class="text-slate-500 dark:text-dark-400">Currency: {{ strtoupper($invoice->currency) }}<br>Method: Card on file (Stripe)</p>
      </div>
    </div>

    <table class="w-full text-left text-xs">
      <thead>
        <tr class="bg-slate-50/75 dark:bg-dark-800/50 text-[11px] font-bold uppercase tracking-wider text-slate-500 dark:text-dark-400">
          <th class="py-3 px-4 rounded-l-xl">Description</th>
          <th class="py-3 px-4 text-right rounded-r-xl">Amount</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-slate-100 dark:divide-dark-800">
        @foreach ($invoice->invoiceLineItems() as $item)
        <tr>
          <td class="py-3.5 px-4 font-semibold text-slate-800 dark:text-white">{{ $item->description }}</td>
          <td class="py-3.5 px-4 text-right font-bold">{{ $item->total() }}</td>
        </tr>
        @endforeach
      </tbody>
    </table>

    <div class="flex justify-end mt-6">
      <div class="w-full sm:w-64 space-y-2 text-xs">
        <div class="flex justify-between text-slate-500 dark:text-dark-400"><span>Subtotal</span><span>{{ $invoice->subtotal() }}</span></div>
        @if ($invoice->hasTax())
          <div class="flex justify-between text-slate-500 dark:text-dark-400"><span>Tax</span><span>{{ $invoice->tax() }}</span></div>
        @endif
        <div class="flex justify-between font-extrabold text-sm text-slate-900 dark:text-white border-t border-slate-100 dark:border-dark-800 pt-2"><span>Total</span><span>{{ $invoice->total() }}</span></div>
      </div>
    </div>

    <p class="text-[11px] text-slate-400 mt-8 pt-6 border-t border-slate-100 dark:border-dark-800">Thank you for your business. Questions about this invoice? Reply to your billing emails or open a support ticket.</p>
  </div>
@endsection
