<div class="space-y-6">
  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
      <h1 class="text-2xl font-extrabold tracking-tight">Security Audit &amp; Activity Logs</h1>
      <p class="text-xs text-slate-500 dark:text-dark-400 mt-1">Chronological record of sign-ins, account changes, and administrative actions.</p>
    </div>
    <div class="flex items-center gap-3">
      <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search events or actors..."
             class="text-xs px-3 py-2 rounded-xl bg-white dark:bg-dark-900 border border-slate-200 dark:border-dark-700 focus:outline-none w-56">
      <select wire:model.live="logFilter" class="text-xs font-semibold px-3 py-2 bg-white dark:bg-dark-900 border border-slate-200 dark:border-dark-700 rounded-xl focus:outline-none">
        <option value="all">All Categories</option>
        @foreach ($logNames as $name)
          <option value="{{ $name }}">{{ ucfirst($name) }}</option>
        @endforeach
      </select>
    </div>
  </div>

  <div class="rounded-2xl bg-white dark:bg-dark-900 border border-slate-200/80 dark:border-dark-800 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
      <table class="w-full text-left">
        <thead>
          <tr class="bg-slate-50/75 dark:bg-dark-800/50 text-[11px] font-bold uppercase tracking-wider text-slate-500 dark:text-dark-400 border-b border-slate-100 dark:border-dark-800">
            <th class="py-3.5 px-4">Event Type</th>
            <th class="py-3.5 px-4">Actor</th>
            <th class="py-3.5 px-4">IP Address</th>
            <th class="py-3.5 px-4">Status</th>
            <th class="py-3.5 px-4">Timestamp</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 dark:divide-dark-800 text-xs">
          @forelse ($activities as $activity)
          @php($props = $activity->properties ?? collect())
          <tr class="hover:bg-slate-50/50 dark:hover:bg-dark-800/50" wire:key="act-{{ $activity->id }}">
            <td class="py-3 px-4">
              <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold {{ $activity->log_name === 'auth' ? 'bg-brand-50 text-brand-700 dark:bg-brand-950/60 dark:text-brand-400' : 'bg-slate-100 text-slate-600 dark:bg-dark-800 dark:text-dark-300' }}">{{ ucfirst($activity->log_name ?? 'system') }}</span>
              <span class="ml-2 font-semibold text-slate-800 dark:text-white">{{ $activity->description }}</span>
            </td>
            <td class="py-3 px-4">
              @if ($activity->causer)
                <div class="flex items-center gap-2">
                  <img src="{{ $activity->causer->avatar_url }}" class="w-6 h-6 rounded-full" alt="">
                  <span class="font-semibold">{{ $activity->causer->name }}</span>
                </div>
              @else
                <span class="text-slate-400">{{ $props->get('email', 'System') }}</span>
              @endif
            </td>
            <td class="py-3 px-4 font-mono text-[11px] text-slate-500 dark:text-dark-400">{{ $props->get('ip', '—') }}</td>
            <td class="py-3 px-4">
              @if ($props->get('status') === 'failed')
                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 dark:bg-rose-950/60 dark:text-rose-400">Failed</span>
              @else
                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-400">Success</span>
              @endif
            </td>
            <td class="py-3 px-4 text-slate-500 dark:text-dark-400" title="{{ $activity->created_at }}">{{ $activity->created_at->diffForHumans() }}</td>
          </tr>
          @empty
          <tr><td colspan="5" class="py-10 text-center text-slate-400 text-xs">No activity recorded yet.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div class="p-4 border-t border-slate-100 dark:border-dark-800">{{ $activities->links() }}</div>
  </div>
</div>
