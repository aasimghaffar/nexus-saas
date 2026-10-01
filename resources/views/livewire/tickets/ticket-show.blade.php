<div class="space-y-6">
  <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4">
    <div>
      <a href="{{ route('tickets') }}" class="text-xs font-bold text-slate-500 hover:text-brand-600 dark:hover:text-brand-400">&larr; All Tickets</a>
      <h1 class="text-lg font-bold mt-1 text-slate-900 dark:text-white">{{ $ticket->subject }}</h1>
      <p class="text-[11px] text-slate-400 mt-0.5 font-mono">{{ $ticket->reference }} &bull; opened {{ $ticket->created_at->diffForHumans() }}</p>
    </div>
    @can('manage', $ticket)
    <div class="flex items-center gap-2">
      <select wire:change="assignTo($event.target.value)" class="text-xs font-semibold px-3 py-2 bg-white dark:bg-dark-900 border border-slate-200 dark:border-dark-700 rounded-xl focus:outline-none">
        <option value="">Unassigned</option>
        @foreach ($agents as $agent)<option value="{{ $agent->id }}" @selected($ticket->assigned_to === $agent->id)>{{ $agent->name }}</option>@endforeach
      </select>
      @foreach (['open' => 'Reopen', 'pending' => 'Mark Pending', 'resolved' => 'Resolve'] as $status => $label)
        @if ($ticket->status !== $status)
          <button wire:click="setStatus('{{ $status }}')" class="px-3 py-2 rounded-xl {{ $status === 'resolved' ? 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100 dark:bg-emerald-950/50 dark:text-emerald-400' : 'bg-slate-100 hover:bg-slate-200 dark:bg-dark-800 dark:hover:bg-dark-700' }} text-xs font-semibold">{{ $label }}</button>
        @endif
      @endforeach
    </div>
    @endcan
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
    {{-- Thread --}}
    <div class="lg:col-span-2 space-y-4">
      {{-- Original message --}}
      <div class="rounded-2xl bg-white dark:bg-dark-900 border border-slate-200/80 dark:border-dark-800 shadow-sm p-5">
        <div class="flex items-center gap-3">
          <img src="{{ $ticket->user->avatar_url }}" class="w-8 h-8 rounded-full" alt="">
          <div>
            <p class="text-xs font-bold text-slate-900 dark:text-white">{{ $ticket->user->name }}</p>
            <p class="text-[10px] text-slate-400">{{ $ticket->created_at->format('d M Y, H:i') }}</p>
          </div>
        </div>
        <p class="text-xs text-slate-600 dark:text-dark-300 mt-3 whitespace-pre-line">{{ $ticket->body }}</p>
      </div>

      @foreach ($ticket->replies as $reply)
      <div class="rounded-2xl bg-white dark:bg-dark-900 border {{ $reply->user_id !== $ticket->user_id ? 'border-brand-200 dark:border-brand-900/50' : 'border-slate-200/80 dark:border-dark-800' }} shadow-sm p-5" wire:key="reply-{{ $reply->id }}">
        <div class="flex items-center justify-between">
          <div class="flex items-center gap-3">
            <img src="{{ $reply->user->avatar_url }}" class="w-8 h-8 rounded-full" alt="">
            <div>
              <p class="text-xs font-bold text-slate-900 dark:text-white">{{ $reply->user->name }}
                @if ($reply->user_id !== $ticket->user_id)<span class="ml-1 px-1.5 py-0.5 rounded text-[9px] font-bold bg-brand-50 text-brand-700 dark:bg-brand-950/60 dark:text-brand-400 uppercase">Agent</span>@endif
              </p>
              <p class="text-[10px] text-slate-400">{{ $reply->created_at->format('d M Y, H:i') }}</p>
            </div>
          </div>
        </div>
        <p class="text-xs text-slate-600 dark:text-dark-300 mt-3 whitespace-pre-line">{{ $reply->body }}</p>
      </div>
      @endforeach

      {{-- Reply box --}}
      @if ($ticket->status !== 'resolved' || auth()->user()->can('manage', $ticket))
      <form wire:submit="sendReply" class="rounded-2xl bg-white dark:bg-dark-900 border border-slate-200/80 dark:border-dark-800 shadow-sm p-5 space-y-3">
        <label class="text-xs font-bold">Reply to this conversation</label>
        <textarea wire:model="reply" rows="4" placeholder="Type your reply..."
                  class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 dark:bg-dark-800 border border-slate-200 dark:border-dark-700 text-xs focus:outline-none focus:ring-2 focus:ring-brand-500"></textarea>
        @error('reply') <span class="text-rose-500 font-semibold text-xs">{{ $message }}</span> @enderror
        <div class="flex justify-end">
          <button type="submit" class="px-4 py-2 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-semibold shadow-md shadow-brand-500/20">
            <span wire:loading.remove wire:target="sendReply">Send Reply</span>
            <span wire:loading wire:target="sendReply">Sending…</span>
          </button>
        </div>
      </form>
      @else
      <p class="text-center text-xs text-slate-400 py-3">This ticket is resolved. Open a new ticket if you need more help.</p>
      @endif
    </div>

    {{-- Customer profile sidebar --}}
    <div class="rounded-2xl bg-white dark:bg-dark-900 border border-slate-200/80 dark:border-dark-800 shadow-sm p-5 h-fit">
      <h3 class="font-bold text-sm">Customer Profile</h3>
      <div class="flex items-center gap-3 mt-4">
        <img src="{{ $ticket->user->avatar_url }}" class="w-10 h-10 rounded-full" alt="">
        <div>
          <p class="text-xs font-bold text-slate-900 dark:text-white">{{ $ticket->user->name }}</p>
          <p class="text-[11px] text-slate-400">{{ $ticket->user->email }}</p>
        </div>
      </div>
      <dl class="mt-5 space-y-3 text-xs">
        <div class="flex justify-between"><dt class="text-slate-400 font-semibold">Company</dt><dd class="font-bold">{{ $ticket->user->company_name ?? '—' }}</dd></div>
        <div class="flex justify-between"><dt class="text-slate-400 font-semibold">Role</dt><dd><span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $ticket->user->role_badge }}">{{ $ticket->user->role_label }}</span></dd></div>
        <div class="flex justify-between"><dt class="text-slate-400 font-semibold">Priority</dt><dd class="font-bold uppercase text-[10px] {{ ['low' => 'text-slate-400', 'medium' => 'text-amber-500', 'high' => 'text-rose-500', 'urgent' => 'text-rose-600'][$ticket->priority] }}">{{ $ticket->priority }}</dd></div>
        <div class="flex justify-between"><dt class="text-slate-400 font-semibold">Member since</dt><dd class="font-bold">{{ $ticket->user->created_at->format('M Y') }}</dd></div>
      </dl>
    </div>
  </div>
</div>
