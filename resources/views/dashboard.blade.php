@extends('layouts.app')

@section('title', 'SaaS Overview')

@section('content')

      <!-- Page Header with Actions -->
      <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
          <h1 class="text-2xl font-extrabold tracking-tight">SaaS Performance Overview</h1>
          <p class="text-xs text-slate-500 dark:text-dark-400 mt-1">Real-time metrics, recurring revenue analytics and customer health.</p>
        </div>
        <div class="flex items-center gap-2.5">
          <div class="relative">
            <select class="text-xs font-semibold px-3 py-2 bg-white dark:bg-dark-900 border border-slate-200 dark:border-dark-700 rounded-xl text-slate-700 dark:text-dark-200 shadow-sm focus:outline-none focus:ring-2 focus:ring-brand-500">
              <option>Last 30 Days</option>
              <option>This Quarter</option>
              <option>Year to Date (2026)</option>
              <option>All Time</option>
            </select>
          </div>
          <button onclick="NexusApp.showToast({title:'Report Generated', message:'Monthly revenue summary exported to CSV', type:'success'})" class="flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-white dark:bg-dark-900 border border-slate-200 dark:border-dark-700 text-xs font-semibold hover:bg-slate-50 dark:hover:bg-dark-800 shadow-sm transition-colors">
            <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
            <span>Export</span>
          </button>
          <button data-modal-open="create-campaign-modal" class="flex items-center gap-1.5 px-4 py-2 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-semibold shadow-md shadow-brand-500/20 transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            <span>New Campaign</span>
          </button>
        </div>
      </div>

      <!-- KPI Metrics Row (4 Cards) -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
        <!-- Card 1: MRR -->
        <div class="p-5 rounded-2xl bg-white dark:bg-dark-900 border border-slate-200/80 dark:border-dark-800 shadow-sm relative overflow-hidden">
          <div class="flex items-center justify-between">
            <span class="text-xs font-bold text-slate-500 dark:text-dark-400 uppercase tracking-wider">Team Members</span>
            <span class="p-2 rounded-xl bg-brand-50 dark:bg-brand-950/60 text-brand-600 dark:text-brand-400">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </span>
          </div>
          <div class="mt-3 flex items-baseline justify-between">
            <div>
              <div class="text-2xl font-extrabold text-slate-900 dark:text-white">{{ number_format($metrics['users'] ?? 0) }}</div>
              <div class="flex items-center gap-1.5 text-xs text-emerald-600 dark:text-emerald-400 font-semibold mt-1">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                <span>+14.8% vs last month</span>
              </div>
            </div>
            <div class="sparkline-chart"></div>
          </div>
        </div>

        <!-- Card 2: ARR -->
        <div class="p-5 rounded-2xl bg-white dark:bg-dark-900 border border-slate-200/80 dark:border-dark-800 shadow-sm relative overflow-hidden">
          <div class="flex items-center justify-between">
            <span class="text-xs font-bold text-slate-500 dark:text-dark-400 uppercase tracking-wider">New This Month</span>
            <span class="p-2 rounded-xl bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
            </span>
          </div>
          <div class="mt-3 flex items-baseline justify-between">
            <div>
              <div class="text-2xl font-extrabold text-slate-900 dark:text-white">{{ number_format($metrics['usersThisMonth'] ?? 0) }}</div>
              <div class="flex items-center gap-1.5 text-xs text-emerald-600 dark:text-emerald-400 font-semibold mt-1">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                <span>+22.4% YoY</span>
              </div>
            </div>
            <div class="sparkline-chart"></div>
          </div>
        </div>

        <!-- Card 3: Active Subscriptions -->
        <div class="p-5 rounded-2xl bg-white dark:bg-dark-900 border border-slate-200/80 dark:border-dark-800 shadow-sm relative overflow-hidden">
          <div class="flex items-center justify-between">
            <span class="text-xs font-bold text-slate-500 dark:text-dark-400 uppercase tracking-wider">Active Projects</span>
            <span class="p-2 rounded-xl bg-cyan-50 dark:bg-cyan-950/60 text-cyan-600 dark:text-cyan-400">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
            </span>
          </div>
          <div class="mt-3 flex items-baseline justify-between">
            <div>
              <div class="text-2xl font-extrabold text-slate-900 dark:text-white">{{ number_format($metrics['projectsActive'] ?? 0) }}</div>
              <div class="flex items-center gap-1.5 text-xs text-emerald-600 dark:text-emerald-400 font-semibold mt-1">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                <span>+180 new this month</span>
              </div>
            </div>
            <div class="sparkline-chart"></div>
          </div>
        </div>

        <!-- Card 4: Churn Rate -->
        <div class="p-5 rounded-2xl bg-white dark:bg-dark-900 border border-slate-200/80 dark:border-dark-800 shadow-sm relative overflow-hidden">
          <div class="flex items-center justify-between">
            <span class="text-xs font-bold text-slate-500 dark:text-dark-400 uppercase tracking-wider">Open Tickets</span>
            <span class="p-2 rounded-xl bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path></svg>
            </span>
          </div>
          <div class="mt-3 flex items-baseline justify-between">
            <div>
              <div class="text-2xl font-extrabold text-slate-900 dark:text-white">{{ number_format($metrics['ticketsOpen'] ?? 0) }}</div>
              <div class="flex items-center gap-1.5 text-xs text-emerald-600 dark:text-emerald-400 font-semibold mt-1">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
                <span>-0.4% improvement</span>
              </div>
            </div>
            <div class="sparkline-chart"></div>
          </div>
        </div>
      </div>

      <!-- Charts Row (2 Columns: Revenue Area Chart & Plan Breakdown Donut) -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Revenue Growth Area Chart (2 Cols) -->
        <div class="lg:col-span-2 p-5 sm:p-6 rounded-2xl bg-white dark:bg-dark-900 border border-slate-200/80 dark:border-dark-800 shadow-sm">
          <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-4">
            <div>
              <h3 class="text-base font-bold">Growth & Activity</h3>
              <p class="text-xs text-slate-500 dark:text-dark-400">Gross MRR compared with Net ARR expansion</p>
            </div>
            <div class="flex items-center gap-2">
              <span class="inline-flex items-center gap-1.5 text-xs font-medium text-slate-500 dark:text-dark-400">
                <span class="w-2.5 h-2.5 rounded-full bg-indigo-600"></span> Gross MRR
              </span>
              <span class="inline-flex items-center gap-1.5 text-xs font-medium text-slate-500 dark:text-dark-400">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span> Net ARR
              </span>
            </div>
          </div>
          <div id="chart-revenue-mrr" class="w-full"></div>
        </div>

        <!-- Plan Breakdown Donut Chart (1 Col) -->
        <div class="p-5 sm:p-6 rounded-2xl bg-white dark:bg-dark-900 border border-slate-200/80 dark:border-dark-800 shadow-sm flex flex-col justify-between">
          <div>
            <div class="flex items-center justify-between mb-2">
              <h3 class="text-base font-bold">Workload Breakdown</h3>
              <button class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path></svg>
              </button>
            </div>
            <p class="text-xs text-slate-500 dark:text-dark-400">Active customer distribution across tier tiers</p>
          </div>
          <div id="chart-plan-distribution" class="my-auto"></div>
          <div class="pt-4 border-t border-slate-100 dark:border-dark-800 grid grid-cols-2 gap-3 text-center">
            <div class="p-2 rounded-xl bg-slate-50 dark:bg-dark-800/60">
              <p class="text-[10px] text-slate-400 uppercase font-bold">Top Plan</p>
              <p class="text-xs font-bold text-slate-800 dark:text-slate-200 mt-0.5">Enterprise Pro (45%)</p>
            </div>
            <div class="p-2 rounded-xl bg-slate-50 dark:bg-dark-800/60">
              <p class="text-[10px] text-slate-400 uppercase font-bold">Avg ARPU</p>
              <p class="text-xs font-bold text-slate-800 dark:text-slate-200 mt-0.5">$29.66 / mo</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Recent Subscriptions Table with Filter & Export -->
      <div class="rounded-2xl bg-white dark:bg-dark-900 border border-slate-200/80 dark:border-dark-800 shadow-sm overflow-hidden" data-table-container data-per-page="5">
        <div class="p-5 border-b border-slate-100 dark:border-dark-800 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
          <div>
            <h3 class="text-base font-bold">Newest Team Members</h3>
            <p class="text-xs text-slate-500 dark:text-dark-400">Live feed of new subscription signups and renewals</p>
          </div>
          <div class="flex items-center gap-3">
            <div class="relative">
              <input type="text" data-table-search placeholder="Filter members..." class="text-xs pl-8 pr-3 py-2 rounded-xl bg-slate-50 dark:bg-dark-800 border border-slate-200 dark:border-dark-700 text-slate-700 dark:text-dark-200 focus:outline-none focus:ring-2 focus:ring-brand-500 w-48 sm:w-60">
              <svg class="w-3.5 h-3.5 text-slate-400 absolute left-2.5 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <select data-table-filter class="text-xs font-semibold px-3 py-2 bg-slate-50 dark:bg-dark-800 border border-slate-200 dark:border-dark-700 rounded-xl text-slate-700 dark:text-dark-200 focus:outline-none">
              <option value="all">All Statuses</option>
              <option value="active">Active</option>
              <option value="suspended">Suspended</option>
            </select>
            <button data-table-export class="p-2 rounded-xl border border-slate-200 dark:border-dark-700 hover:bg-slate-50 dark:hover:bg-dark-800 text-slate-600 dark:text-dark-300" title="Export to CSV">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
            </button>
          </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
          <table class="w-full text-left border-collapse">
            <thead>
              <tr class="bg-slate-50/75 dark:bg-dark-800/50 text-[11px] font-bold uppercase tracking-wider text-slate-500 dark:text-dark-400 border-b border-slate-100 dark:border-dark-800">
                <th class="py-3.5 px-4 w-10">
                  <input type="checkbox" data-table-select-all class="rounded border-slate-300 dark:border-dark-700 text-brand-600 focus:ring-brand-500">
                </th>
                <th class="py-3.5 px-4">Member</th>
                <th class="py-3.5 px-4">Role</th>
                <th class="py-3.5 px-4">Company</th>
                <th class="py-3.5 px-4">Status</th>
                <th class="py-3.5 px-4">Joined</th>
                <th class="py-3.5 px-4 text-right">Actions</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-dark-800 text-xs">
              @forelse ($recentMembers ?? [] as $member)
              <tr class="hover:bg-slate-50/50 dark:hover:bg-dark-800/50 transition-colors" data-status="{{ $member->suspended_at ? 'suspended' : 'active' }}">
                <td class="py-3 px-4">
                  <input type="checkbox" class="rounded border-slate-300 dark:border-dark-700 text-brand-600">
                </td>
                <td class="py-3 px-4">
                  <div class="flex items-center gap-3">
                    <img src="{{ $member->avatar_url }}" alt="{{ $member->name }}" class="w-8 h-8 rounded-full">
                    <div>
                      <p class="font-bold text-slate-800 dark:text-slate-200">{{ $member->name }}</p>
                      <p class="text-[11px] text-slate-400">{{ $member->email }}</p>
                    </div>
                  </div>
                </td>
                <td class="py-3 px-4">
                  <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $member->role_badge }}">{{ $member->role_label }}</span>
                </td>
                <td class="py-3 px-4 text-slate-500 dark:text-dark-400">{{ $member->company_name ?? '—' }}</td>
                <td class="py-3 px-4">
                  @if ($member->suspended_at)
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 dark:bg-rose-950/60 dark:text-rose-400 border border-rose-200 dark:border-rose-800/40">Suspended</span>
                  @else
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800/40">Active</span>
                  @endif
                </td>
                <td class="py-3 px-4 text-slate-500 dark:text-dark-400">{{ $member->created_at->format('M d, Y') }}</td>
                <td class="py-3 px-4 text-right">
                  @can('users.view')
                  <a href="{{ route('users') }}" class="px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 dark:bg-dark-800 dark:hover:bg-dark-700 text-slate-700 dark:text-slate-300 font-semibold text-[11px] transition-colors">Manage</a>
                  @endcan
                </td>
              </tr>
              @empty
              <tr><td colspan="7" class="py-8 text-center text-slate-400 text-xs">No members yet.</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>

        <!-- Table Footer Pagination -->
        <div class="p-4 border-t border-slate-100 dark:border-dark-800 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-500 dark:text-dark-400">
          <span data-table-info>Showing 1 to 5 of 5 transactions</span>
          <div data-table-pagination class="flex items-center gap-1"></div>
        </div>
      </div>
@endsection

@push('scripts')
<script>
  // Live series computed in DashboardController@index; charts.js prefers these over demo data.
  window.NexusChartData = @json($chartData ?? []);
</script>
@endpush
