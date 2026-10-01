@extends('layouts.app')

@section('title', 'Tables & Data Grids')

@section('content')

      <div>
        <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">Tables & Data Grids</h1>
        <p class="text-xs sm:text-sm text-slate-500 dark:text-dark-400 mt-1">Responsive tables with sort headers, status tags, and avatar cells.</p>
      </div>

      <div class="rounded-2xl bg-white dark:bg-dark-900 border border-slate-200/80 dark:border-dark-800 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
          <table class="w-full text-left border-collapse text-xs">
            <thead>
              <tr class="bg-slate-50 dark:bg-dark-800 text-slate-500 font-bold uppercase text-[11px] border-b border-slate-100 dark:border-dark-800">
                <th class="py-3 px-4">Customer Name</th>
                <th class="py-3 px-4">Plan Tier</th>
                <th class="py-3 px-4">Monthly Spend</th>
                <th class="py-3 px-4">Account Status</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-dark-800">
              <tr class="hover:bg-slate-50/50 dark:hover:bg-dark-800/50">
                <td class="py-3 px-4 font-bold">Stripe Integration Inc</td>
                <td class="py-3 px-4">Enterprise</td>
                <td class="py-3 px-4 font-bold text-emerald-600">$499/mo</td>
                <td class="py-3 px-4"><span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700">Active</span></td>
              </tr>
              <tr class="hover:bg-slate-50/50 dark:hover:bg-dark-800/50">
                <td class="py-3 px-4 font-bold">Vercel Edge Cloud</td>
                <td class="py-3 px-4">Growth Team</td>
                <td class="py-3 px-4 font-bold text-emerald-600">$199/mo</td>
                <td class="py-3 px-4"><span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700">Active</span></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    
@endsection
