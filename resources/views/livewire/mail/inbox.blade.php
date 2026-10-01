<div class="grid grid-cols-1 lg:grid-cols-4 gap-4">
  {{-- Folders --}}
  <div class="rounded-2xl bg-white dark:bg-dark-900 border border-slate-200/80 dark:border-dark-800 shadow-sm p-4 h-fit">
    <button wire:click="$set('showCompose', true)" class="w-full py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold shadow-md shadow-brand-500/20">Compose</button>
    <nav class="mt-4 space-y-1 text-xs font-semibold">
      @foreach (['inbox' => 'Inbox', 'sent' => 'Sent', 'starred' => 'Starred', 'trash' => 'Trash'] as $key => $label)
        <button wire:click="setFolder('{{ $key }}')"
                class="w-full flex items-center justify-between px-3 py-2 rounded-xl {{ $folder === $key ? 'bg-brand-50 text-brand-700 dark:bg-brand-950/40 dark:text-brand-400' : 'text-slate-600 dark:text-dark-300 hover:bg-slate-50 dark:hover:bg-dark-800' }}">
          <span>{{ $label }}</span>
          @if ($key === 'inbox' && $unreadCount)
            <span class="px-1.5 py-0.5 rounded-md bg-brand-600 text-white text-[10px] font-bold">{{ $unreadCount }}</span>
          @endif
        </button>
      @endforeach
    </nav>
  </div>

  {{-- List + reading pane --}}
  <div class="lg:col-span-3 rounded-2xl bg-white dark:bg-dark-900 border border-slate-200/80 dark:border-dark-800 shadow-sm overflow-hidden">
    @if ($openMail)
      {{-- Reading pane --}}
      <div class="p-6">
        <div class="flex items-start justify-between gap-4 pb-4 border-b border-slate-100 dark:border-dark-800">
          <div>
            <button wire:click="$set('openId', null)" class="text-xs font-bold text-slate-500 hover:text-brand-600 dark:hover:text-brand-400">&larr; Back to {{ ucfirst($folder) }}</button>
            <h2 class="text-base font-extrabold mt-2">{{ $openMail->subject }}</h2>
            <div class="flex items-center gap-2 mt-2">
              <img src="{{ $openMail->from->avatar_url }}" class="w-7 h-7 rounded-full" alt="">
              <p class="text-xs"><span class="font-bold">{{ $openMail->from->name }}</span> <span class="text-slate-400">to {{ $openMail->to->id === auth()->id() ? 'me' : $openMail->to->name }} &bull; {{ $openMail->created_at->format('d M Y, H:i') }}</span></p>
            </div>
          </div>
          <div class="flex items-center gap-1">
            @if ($openMail->to_user_id === auth()->id())
              <button wire:click="toggleStar({{ $openMail->id }})" class="p-2 rounded-lg hover:bg-slate-100 dark:hover:bg-dark-800 {{ $openMail->starred_by_recipient ? 'text-amber-400' : 'text-slate-300' }}">
                <svg class="w-4.5 h-4.5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
              </button>
            @endif
            <button wire:click="moveToTrash({{ $openMail->id }})" class="p-2 rounded-lg text-slate-400 hover:text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-950/40">
              <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
            </button>
          </div>
        </div>
        <p class="text-xs text-slate-600 dark:text-dark-300 mt-5 whitespace-pre-line leading-relaxed">{{ $openMail->body }}</p>
      </div>
    @else
      <div class="p-4 border-b border-slate-100 dark:border-dark-800">
        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search subjects..."
               class="text-xs px-3 py-2 rounded-xl bg-slate-50 dark:bg-dark-800 border border-slate-200 dark:border-dark-700 focus:outline-none w-64">
      </div>
      <div class="divide-y divide-slate-50 dark:divide-dark-800/60">
        @forelse ($messages as $mail)
        @php($mine = $mail->to_user_id === auth()->id())
        <button wire:click="open({{ $mail->id }})" wire:key="mail-{{ $mail->id }}"
                class="w-full flex items-center gap-3 p-4 text-left hover:bg-slate-50 dark:hover:bg-dark-800/60 {{ $mine && ! $mail->read_at ? 'bg-brand-50/40 dark:bg-brand-950/20' : '' }}">
          <img src="{{ ($folder === 'sent' ? $mail->to : $mail->from)->avatar_url }}" class="w-8 h-8 rounded-full" alt="">
          <div class="flex-1 min-w-0">
            <div class="flex items-center justify-between">
              <p class="text-xs {{ $mine && ! $mail->read_at ? 'font-extrabold' : 'font-semibold' }} text-slate-900 dark:text-white truncate">
                {{ $folder === 'sent' ? 'To: '.$mail->to->name : $mail->from->name }}
              </p>
              <span class="text-[10px] text-slate-400 whitespace-nowrap ml-2">{{ $mail->created_at->format('d M') }}</span>
            </div>
            <p class="text-[11px] {{ $mine && ! $mail->read_at ? 'font-bold text-slate-700 dark:text-dark-200' : 'text-slate-400' }} truncate">{{ $mail->subject }}</p>
          </div>
          @if ($mail->starred_by_recipient && $mine)
            <svg class="w-3.5 h-3.5 text-amber-400 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
          @endif
        </button>
        @empty
        <p class="p-10 text-center text-xs text-slate-400">Nothing in {{ ucfirst($folder) }}.</p>
        @endforelse
      </div>
      <div class="p-4 border-t border-slate-100 dark:border-dark-800">{{ $messages->links() }}</div>
    @endif
  </div>

  {{-- Compose modal --}}
  @if ($showCompose)
  <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" wire:click="$set('showCompose', false)"></div>
    <div class="relative max-w-lg w-full bg-white dark:bg-dark-900 rounded-3xl shadow-xl border border-slate-200/80 dark:border-dark-800 p-8 space-y-4">
      <h2 class="text-lg font-extrabold">New Message</h2>
      <form wire:submit="send" class="space-y-4 text-xs">
        <div>
          <label class="block font-bold mb-1">To</label>
          <select wire:model="composeTo" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 dark:bg-dark-800 border border-slate-200 dark:border-dark-700 focus:outline-none">
            <option value="">Choose a teammate…</option>
            @foreach ($members as $member)<option value="{{ $member->id }}">{{ $member->name }}</option>@endforeach
          </select>
          @error('composeTo') <span class="text-rose-500 font-semibold mt-1 block">{{ $message }}</span> @enderror
        </div>
        <div>
          <label class="block font-bold mb-1">Subject</label>
          <input type="text" wire:model="composeSubject" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 dark:bg-dark-800 border border-slate-200 dark:border-dark-700 focus:outline-none focus:ring-2 focus:ring-brand-500">
          @error('composeSubject') <span class="text-rose-500 font-semibold mt-1 block">{{ $message }}</span> @enderror
        </div>
        <div>
          <label class="block font-bold mb-1">Message</label>
          <textarea wire:model="composeBody" rows="6" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 dark:bg-dark-800 border border-slate-200 dark:border-dark-700 focus:outline-none"></textarea>
          @error('composeBody') <span class="text-rose-500 font-semibold mt-1 block">{{ $message }}</span> @enderror
        </div>
        <div class="flex items-center justify-end gap-2 pt-2">
          <button type="button" wire:click="$set('showCompose', false)" class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-500 hover:bg-slate-100 dark:hover:bg-dark-800">Discard</button>
          <button type="submit" class="px-4 py-2 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-semibold">Send Message</button>
        </div>
      </form>
    </div>
  </div>
  @endif
</div>
