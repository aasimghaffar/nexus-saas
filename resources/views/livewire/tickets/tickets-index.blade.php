<div class="space-y-6">
  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
      <h1 class="text-2xl font-extrabold tracking-tight">{{ $isAgent ? 'Support Helpdesk' : 'My Support Tickets' }}</h1>
      <p class="text-xs text-slate-500 dark:text-dark-400 mt-1">{{ $isAgent ? 'All customer conversations across the workspace.' : 'Questions and issues you have raised with our team.' }}</p>
    </div>
    <button wire:click="$set('showForm', true)" class="px-4 py-2 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-semibold shadow-md shadow-brand-500/20 whitespace-nowrap">New Ticket</button>
  </div>

  <div class="grid grid-cols-3 gap-4">
    @foreach (['open' => ['Open', 'text-brand-600 dark:text-brand-400'], 'pending' => ['Pending Reply', 'text-amber-500'], 'resolved' => ['Resolved', 'text-emerald-500']] as $key => [$label, $tone])
      <div class="p-5 rounded-2xl bg-white dark:bg-dark-900 border border-slate-200/80 dark:border-dark-800 shadow-sm">
        <p class="text-xs font-bold text-slate-500 dark:text-dark-400 uppercase">{{ $label }}</p>
        <p class="text-2xl font-extrabold mt-1 {{ $tone }}">{{ $counts[$key] }}</p>
      </div>
    @endforeach
  </div>

  <div class="rounded-2xl bg-white dark:bg-dark-900 border border-slate-200/80 dark:border-dark-800 shadow-sm overflow-hidden">
    <div class="p-5 border-b border-slate-100 dark:border-dark-800 flex items-center gap-3">
      <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search subject or reference..."
             class="text-xs px-3 py-2 rounded-xl bg-slate-50 dark:bg-dark-800 border border-slate-200 dark:border-dark-700 focus:outline-none w-64">
      <select wire:model.live="statusFilter" class="text-xs font-semibold px-3 py-2 bg-slate-50 dark:bg-dark-800 border border-slate-200 dark:border-dark-700 rounded-xl focus:outline-none">
        <option value="all">All Statuses</option><option value="open">Open</option><option value="pending">Pending</option><option value="resolved">Resolved</option>
      </select>
    </div>
    <div class="overflow-x-auto">
      <table class="w-full text-left">
        <thead>
          <tr class="bg-slate-50/75 dark:bg-dark-800/50 text-[11px] font-bold uppercase tracking-wider text-slate-500 dark:text-dark-400 border-b border-slate-100 dark:border-dark-800">
            <th class="py-3.5 px-4">Ticket ID</th>
            <th class="py-3.5 px-4">Subject</th>
            @if ($isAgent)<th class="py-3.5 px-4">Requester</th>@endif
            <th class="py-3.5 px-4">Priority</th>
            <th class="py-3.5 px-4">Assigned Agent</th>
            <th class="py-3.5 px-4">Status</th>
            <th class="py-3.5 px-4 text-right">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 dark:divide-dark-800 text-xs">
          @forelse ($tickets as $ticket)
          <tr class="hover:bg-slate-50/50 dark:hover:bg-dark-800/50" wire:key="ticket-{{ $ticket->id }}">
            <td class="py-3 px-4 font-bold font-mono">{{ $ticket->reference }}</td>
            <td class="py-3 px-4">
              <p class="font-bold text-slate-900 dark:text-white">{{ $ticket->subject }}</p>
              <p class="text-[11px] text-slate-400">{{ $ticket->replies_count }} {{ Str::plural('reply', $ticket->replies_count) }} &bull; {{ $ticket->created_at->diffForHumans() }}</p>
            </td>
            @if ($isAgent)
            <td class="py-3 px-4">
              <div class="flex items-center gap-2"><img src="{{ $ticket->user->avatar_url }}" class="w-6 h-6 rounded-full" alt=""><span class="font-semibold">{{ $ticket->user->name }}</span></div>
            </td>
            @endif
            <td class="py-3 px-4">
              <span class="text-[10px] font-bold uppercase {{ ['low' => 'text-slate-400', 'medium' => 'text-amber-500', 'high' => 'text-rose-500', 'urgent' => 'text-rose-600'][$ticket->priority] }}">{{ $ticket->priority }}</span>
            </td>
            <td class="py-3 px-4 text-slate-500 dark:text-dark-400">{{ $ticket->agent?->name ?? 'Unassigned' }}</td>
            <td class="py-3 px-4">
              @if ($ticket->status === 'open')<span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-brand-50 text-brand-700 dark:bg-brand-950/60 dark:text-brand-400">Open</span>
              @elseif ($ticket->status === 'pending')<span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 dark:bg-amber-950/60 dark:text-amber-400">Pending</span>
              @else<span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-400">Resolved</span>@endif
            </td>
            <td class="py-3 px-4 text-right">
              <a href="{{ route('tickets.show', $ticket) }}" class="px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 dark:bg-dark-800 dark:hover:bg-dark-700 font-semibold text-[11px]">Open Thread</a>
            </td>
          </tr>
          @empty
          <tr><td colspan="7" class="py-10 text-center text-slate-400 text-xs">No tickets found.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div class="p-4 border-t border-slate-100 dark:border-dark-800">{{ $tickets->links() }}</div>
  </div>

  {{-- New ticket modal --}}
  @if ($showForm)
  <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" wire:click="$set('showForm', false)"></div>
    <div class="relative max-w-md w-full bg-white dark:bg-dark-900 rounded-3xl shadow-xl border border-slate-200/80 dark:border-dark-800 p-8 space-y-4">
      <h2 class="text-lg font-extrabold">Open a Support Ticket</h2>
      <form wire:submit="save" class="space-y-4 text-xs">
        <div>
          <label class="block font-bold mb-1">Subject</label>
          <input type="text" wire:model="subject" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 dark:bg-dark-800 border border-slate-200 dark:border-dark-700 focus:outline-none focus:ring-2 focus:ring-brand-500">
          @error('subject') <span class="text-rose-500 font-semibold mt-1 block">{{ $message }}</span> @enderror
        </div>
        <div>
          <label class="block font-bold mb-1">Priority</label>
          <select wire:model="priority" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 dark:bg-dark-800 border border-slate-200 dark:border-dark-700 focus:outline-none">
            <option value="low">Low</option><option value="medium">Medium</option><option value="high">High</option><option value="urgent">Urgent</option>
          </select>
        </div>
        <div>
          <label class="block font-bold mb-1">Describe the issue</label>
          <textarea wire:model="body" rows="5" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 dark:bg-dark-800 border border-slate-200 dark:border-dark-700 focus:outline-none"></textarea>
          @error('body') <span class="text-rose-500 font-semibold mt-1 block">{{ $message }}</span> @enderror
        </div>
        <div class="flex items-center justify-end gap-2 pt-2">
          <button type="button" wire:click="$set('showForm', false)" class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-500 hover:bg-slate-100 dark:hover:bg-dark-800">Cancel</button>
          <button type="submit" class="px-4 py-2 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-semibold">Submit Ticket</button>
        </div>
      </form>
    </div>
  </div>
  @endif
</div>
