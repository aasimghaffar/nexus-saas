@extends('layouts.app')

@section('title', 'Invoices')

@section('content')
  <div>
    <h1 class="text-2xl font-extrabold tracking-tight">Invoices</h1>
    <p class="text-xs text-slate-500 dark:text-dark-400 mt-1">All invoices issued for your subscription, straight from Stripe.</p>
  </div>

  <div class="rounded-2xl bg-white dark:bg-dark-900 border border-slate-200/80 dark:border-dark-800 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
      <table class="w-full text-left">
        <thead>
          <tr class="bg-slate-50/75 dark:bg-dark-800/50 text-[11px] font-bold uppercase tracking-wider text-slate-500 dark:text-dark-400 border-b border-slate-100 dark:border-dark-800">
            <th class="py-3.5 px-4">Invoice Number</th>
            <th class="py-3.5 px-4">Organization</th>
            <th class="py-3.5 px-4">Issue Date</th>
            <th class="py-3.5 px-4">Amount</th>
            <th class="py-3.5 px-4">Status</th>
            <th class="py-3.5 px-4 text-right">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 dark:divide-dark-800 text-xs">
          @forelse ($invoices as $invoice)
          <tr class="hover:bg-slate-50/50 dark:hover:bg-dark-800/50">
            <td class="py-3 px-4 font-bold font-mono">#{{ $invoice->number ?? $invoice->id }}</td>
            <td class="py-3 px-4">{{ $user->company_name ?? $user->name }}</td>
            <td class="py-3 px-4 text-slate-500 dark:text-dark-400">{{ $invoice->date()->format('d M Y') }}</td>
            <td class="py-3 px-4 font-bold">{{ $invoice->total() }}</td>
            <td class="py-3 px-4">
              @if ($invoice->status === 'paid')
                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-400">Paid</span>
              @elseif ($invoice->status === 'open')
                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 dark:bg-amber-950/60 dark:text-amber-400">Open</span>
              @else
                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600 dark:bg-dark-800 dark:text-dark-300">{{ ucfirst($invoice->status ?? 'draft') }}</span>
              @endif
            </td>
            <td class="py-3 px-4 text-right space-x-1 whitespace-nowrap">
              <a href="{{ route('billing.invoices.show', $invoice->id) }}" class="px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 dark:bg-dark-800 dark:hover:bg-dark-700 font-semibold text-[11px]">View</a>
              <a href="{{ route('billing.invoices.download', $invoice->id) }}" class="px-2.5 py-1 rounded-lg bg-brand-50 text-brand-700 hover:bg-brand-100 dark:bg-brand-950/50 dark:text-brand-400 font-semibold text-[11px]">PDF</a>
            </td>
          </tr>
          @empty
          <tr><td colspan="6" class="py-10 text-center text-slate-400 text-xs">No invoices yet — they'll appear after your first subscription payment.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
@endsection
