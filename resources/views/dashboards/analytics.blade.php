@extends('layouts.app')

@section('title', 'Analytics')

@section('content')
  <div>
    <h1 class="text-2xl font-extrabold tracking-tight">Workspace Analytics</h1>
    <p class="text-xs text-slate-500 dark:text-dark-400 mt-1">Activity telemetry across the last 14 days — computed from the audit log.</p>
  </div>

  <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
    <div class="p-5 rounded-2xl bg-white dark:bg-dark-900 border border-slate-200/80 dark:border-dark-800 shadow-sm">
      <p class="text-xs font-bold text-slate-500 dark:text-dark-400 uppercase">Events (14 days)</p>
      <p class="text-2xl font-extrabold mt-1">{{ number_format($totals['events14d']) }}</p>
    </div>
    <div class="p-5 rounded-2xl bg-white dark:bg-dark-900 border border-slate-200/80 dark:border-dark-800 shadow-sm">
      <p class="text-xs font-bold text-slate-500 dark:text-dark-400 uppercase">Total Sign-ins</p>
      <p class="text-2xl font-extrabold mt-1 text-emerald-500">{{ number_format($totals['signins']) }}</p>
    </div>
    <div class="p-5 rounded-2xl bg-white dark:bg-dark-900 border border-slate-200/80 dark:border-dark-800 shadow-sm">
      <p class="text-xs font-bold text-slate-500 dark:text-dark-400 uppercase">Failed Attempts</p>
      <p class="text-2xl font-extrabold mt-1 {{ $totals['failed'] ? 'text-rose-500' : '' }}">{{ number_format($totals['failed']) }}</p>
    </div>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
    <div class="lg:col-span-2 p-6 rounded-2xl bg-white dark:bg-dark-900 border border-slate-200/80 dark:border-dark-800 shadow-sm">
      <h3 class="text-base font-bold">Activity Velocity</h3>
      <p class="text-xs text-slate-400 mt-1">Logged events per day, last 14 days.</p>
      <div id="chart-traffic-overview" class="mt-4"></div>
    </div>
    <div class="p-6 rounded-2xl bg-white dark:bg-dark-900 border border-slate-200/80 dark:border-dark-800 shadow-sm">
      <h3 class="text-base font-bold">Event Breakdown</h3>
      <div id="chart-device-breakdown" class="mt-4"></div>
    </div>
  </div>

  <div class="rounded-2xl bg-white dark:bg-dark-900 border border-slate-200/80 dark:border-dark-800 shadow-sm overflow-hidden">
    <div class="p-5 border-b border-slate-100 dark:border-dark-800"><h3 class="text-base font-bold">Most Active Members</h3></div>
    <table class="w-full text-left text-xs">
      <tbody class="divide-y divide-slate-100 dark:divide-dark-800">
        @foreach ($topActors as $row)
        @continue(! $row->causer)
        <tr class="hover:bg-slate-50/50 dark:hover:bg-dark-800/50">
          <td class="py-3 px-5">
            <div class="flex items-center gap-3"><img src="{{ $row->causer->avatar_url }}" class="w-7 h-7 rounded-full" alt=""><span class="font-bold text-slate-900 dark:text-white">{{ $row->causer->name }}</span></div>
          </td>
          <td class="py-3 px-5 text-right font-bold text-slate-500 dark:text-dark-400">{{ $row->total }} events</td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
@endsection

@push('scripts')
<script>window.NexusChartData = @json($chartData);</script>
@endpush
