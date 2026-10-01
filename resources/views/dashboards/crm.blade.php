@extends('layouts.app')

@section('title', 'CRM & Pipeline')

@section('content')
  <div>
    <h1 class="text-2xl font-extrabold tracking-tight">CRM &amp; Sales Pipeline</h1>
    <p class="text-xs text-slate-500 dark:text-dark-400 mt-1">Delivery pipeline health, ticket load, and project ownership at a glance — all live data.</p>
  </div>

  <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
    @foreach ($columns as $column)
    <div class="p-5 rounded-2xl bg-white dark:bg-dark-900 border border-slate-200/80 dark:border-dark-800 shadow-sm">
      <p class="text-xs font-bold text-slate-500 dark:text-dark-400 uppercase">{{ $column->name }}</p>
      <p class="text-2xl font-extrabold mt-1">{{ $column->tasks_count }} <span class="text-xs font-semibold text-slate-400">tasks</span></p>
    </div>
    @endforeach
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
    <div class="p-6 rounded-2xl bg-white dark:bg-dark-900 border border-slate-200/80 dark:border-dark-800 shadow-sm">
      <h3 class="text-base font-bold">Pipeline Distribution</h3>
      <p class="text-xs text-slate-400 mt-1">Tasks per stage on the sprint board.</p>
      <div id="chart-crm-pipeline" class="mt-4"></div>
    </div>
    <div class="p-6 rounded-2xl bg-white dark:bg-dark-900 border border-slate-200/80 dark:border-dark-800 shadow-sm">
      <h3 class="text-base font-bold">Ticket Volume (7 days)</h3>
      <p class="text-xs text-slate-400 mt-1">New support tickets opened per day.</p>
      <div id="chart-deal-revenue" class="mt-4"></div>
    </div>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
    <div class="lg:col-span-2 rounded-2xl bg-white dark:bg-dark-900 border border-slate-200/80 dark:border-dark-800 shadow-sm overflow-hidden">
      <div class="p-5 border-b border-slate-100 dark:border-dark-800"><h3 class="text-base font-bold">Recent Projects</h3></div>
      <table class="w-full text-left text-xs">
        <tbody class="divide-y divide-slate-100 dark:divide-dark-800">
          @foreach ($projects as $project)
          <tr class="hover:bg-slate-50/50 dark:hover:bg-dark-800/50">
            <td class="py-3 px-5">
              <a href="{{ route('kanban', ['projectFilter' => $project->id]) }}" class="font-bold text-slate-900 dark:text-white hover:text-brand-600 dark:hover:text-brand-400">{{ $project->name }}</a>
              <p class="text-[11px] text-slate-400">{{ $project->tasks_count }} tasks &bull; {{ $project->owner->name }}</p>
            </td>
            <td class="py-3 px-5 w-40">
              <div class="w-full h-1.5 bg-slate-100 dark:bg-dark-800 rounded-full overflow-hidden"><div class="h-full bg-brand-600 rounded-full" style="width: {{ $project->progress }}%"></div></div>
            </td>
            <td class="py-3 px-5 text-right font-bold text-slate-500 dark:text-dark-400 w-16">{{ $project->progress }}%</td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    <div class="p-6 rounded-2xl bg-white dark:bg-dark-900 border border-slate-200/80 dark:border-dark-800 shadow-sm">
      <h3 class="text-base font-bold">Tickets by Priority</h3>
      <div class="mt-4 space-y-3 text-xs">
        @foreach (['urgent' => 'bg-rose-600', 'high' => 'bg-rose-400', 'medium' => 'bg-amber-400', 'low' => 'bg-slate-300'] as $priority => $bar)
        @php($count = $ticketsByPriority[$priority] ?? 0)
        <div>
          <div class="flex items-center justify-between font-bold"><span class="uppercase text-[10px]">{{ $priority }}</span><span class="text-slate-400">{{ $count }}</span></div>
          <div class="w-full h-1.5 bg-slate-100 dark:bg-dark-800 rounded-full mt-1 overflow-hidden">
            <div class="h-full {{ $bar }} rounded-full" style="width: {{ $ticketsByPriority->sum() ? round($count / $ticketsByPriority->sum() * 100) : 0 }}%"></div>
          </div>
        </div>
        @endforeach
      </div>
      <a href="{{ route('tickets') }}" class="inline-block mt-5 text-xs font-bold text-brand-600 dark:text-brand-400 hover:underline">Open Helpdesk &rarr;</a>
    </div>
  </div>
@endsection

@push('scripts')
<script>window.NexusChartData = @json($chartData);</script>
@endpush
