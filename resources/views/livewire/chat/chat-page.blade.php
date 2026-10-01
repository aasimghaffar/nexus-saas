<div class="grid grid-cols-1 lg:grid-cols-3 gap-4 h-[calc(100vh-11rem)]" wire:poll.5s>
  {{-- Contacts --}}
  <div class="rounded-2xl bg-white dark:bg-dark-900 border border-slate-200/80 dark:border-dark-800 shadow-sm flex flex-col overflow-hidden">
    <div class="p-4 border-b border-slate-100 dark:border-dark-800">
      <h2 class="text-sm font-extrabold">Team Chat</h2>
      <input type="text" wire:model.live.debounce.300ms="contactSearch" placeholder="Search teammates..."
             class="mt-3 w-full text-xs px-3 py-2 rounded-xl bg-slate-50 dark:bg-dark-800 border border-slate-200 dark:border-dark-700 focus:outline-none">
    </div>
    <div class="flex-1 overflow-y-auto divide-y divide-slate-50 dark:divide-dark-800/60">
      @foreach ($contacts as $contact)
      <button wire:click="openConversation({{ $contact->id }})" wire:key="contact-{{ $contact->id }}"
              class="w-full flex items-center gap-3 p-3.5 text-left hover:bg-slate-50 dark:hover:bg-dark-800/60 {{ $with === $contact->id ? 'bg-brand-50/60 dark:bg-brand-950/30' : '' }}">
        <img src="{{ $contact->avatar_url }}" class="w-9 h-9 rounded-full" alt="">
        <div class="flex-1 min-w-0">
          <div class="flex items-center justify-between">
            <h3 class="font-bold text-xs text-slate-900 dark:text-white truncate">{{ $contact->name }}</h3>
            <span class="text-[10px] text-slate-400">{{ $contact->lastAt?->shortAbsoluteDiffForHumans() }}</span>
          </div>
          <p class="text-[11px] text-slate-400 truncate">{{ $contact->lastMessage ?? 'Start a conversation' }}</p>
        </div>
        @if ($contact->unread)
          <span class="w-5 h-5 rounded-full bg-brand-600 text-white text-[10px] font-bold flex items-center justify-center">{{ $contact->unread }}</span>
        @endif
      </button>
      @endforeach
    </div>
  </div>

  {{-- Thread --}}
  <div class="lg:col-span-2 rounded-2xl bg-white dark:bg-dark-900 border border-slate-200/80 dark:border-dark-800 shadow-sm flex flex-col overflow-hidden">
    @if ($partner)
    <div class="p-4 border-b border-slate-100 dark:border-dark-800 flex items-center gap-3">
      <img src="{{ $partner->avatar_url }}" class="w-9 h-9 rounded-full" alt="">
      <div>
        <h3 class="font-bold text-xs text-slate-900 dark:text-white">{{ $partner->name }}</h3>
        <p class="text-[10px] {{ $partner->last_seen_at?->gt(now()->subMinutes(6)) ? 'text-emerald-500' : 'text-slate-400' }}">
          {{ $partner->last_seen_at?->gt(now()->subMinutes(6)) ? 'Online now' : 'Last seen '.($partner->last_seen_at?->diffForHumans() ?? 'a while ago') }}
        </p>
      </div>
    </div>

    <div class="flex-1 overflow-y-auto p-4 space-y-3" id="chat-scroll"
         x-data x-init="$el.scrollTop = $el.scrollHeight"
         x-on:chat-scroll-bottom.window="$nextTick(() => $el.scrollTop = $el.scrollHeight)">
      @foreach ($thread as $msg)
      <div class="flex {{ $msg->sender_id === auth()->id() ? 'justify-end' : 'justify-start' }}" wire:key="msg-{{ $msg->id }}">
        <div class="max-w-[75%]">
          <div class="px-3.5 py-2.5 rounded-2xl text-xs {{ $msg->sender_id === auth()->id() ? 'bg-brand-600 text-white rounded-br-md' : 'bg-slate-100 dark:bg-dark-800 text-slate-800 dark:text-dark-100 rounded-bl-md' }}">
            {{ $msg->body }}
          </div>
          <p class="text-[10px] text-slate-400 mt-1 {{ $msg->sender_id === auth()->id() ? 'text-right' : '' }}">{{ $msg->created_at->format('H:i') }}</p>
        </div>
      </div>
      @endforeach
    </div>

    <form wire:submit="send" class="p-4 border-t border-slate-100 dark:border-dark-800 flex items-center gap-2">
      <input type="text" wire:model="message" placeholder="Type a message..." autocomplete="off"
             class="flex-1 text-xs px-3.5 py-2.5 rounded-xl bg-slate-50 dark:bg-dark-800 border border-slate-200 dark:border-dark-700 focus:outline-none focus:ring-2 focus:ring-brand-500">
      <button type="submit" class="px-4 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold shadow-md shadow-brand-500/20">Send</button>
    </form>
    @else
    <div class="flex-1 flex items-center justify-center text-xs text-slate-400">Select a teammate to start chatting.</div>
    @endif
  </div>
</div>
