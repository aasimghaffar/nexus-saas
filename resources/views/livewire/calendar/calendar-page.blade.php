<div class="space-y-6">
  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
      <h1 class="text-2xl font-extrabold tracking-tight">Team Events &amp; Product Calendar</h1>
      <p class="text-xs text-slate-500 dark:text-dark-400 mt-1">Click any day to add an event; click an event to edit it.</p>
    </div>
    <div class="flex items-center gap-2">
      <button wire:click="previousMonth" class="p-2 rounded-xl bg-white dark:bg-dark-900 border border-slate-200 dark:border-dark-700 hover:bg-slate-50 dark:hover:bg-dark-800"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg></button>
      <h3 class="text-base font-bold text-slate-900 dark:text-white w-36 text-center">{{ $first->format('F Y') }}</h3>
      <button wire:click="nextMonth" class="p-2 rounded-xl bg-white dark:bg-dark-900 border border-slate-200 dark:border-dark-700 hover:bg-slate-50 dark:hover:bg-dark-800"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg></button>
      <button wire:click="today" class="px-3 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-dark-800 dark:hover:bg-dark-700 text-xs font-semibold">Today</button>
    </div>
  </div>

  <div class="rounded-2xl bg-white dark:bg-dark-900 border border-slate-200/80 dark:border-dark-800 shadow-sm overflow-hidden">
    <div class="grid grid-cols-7 text-[10px] font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100 dark:border-dark-800">
      @foreach (['Mon','Tue','Wed','Thu','Fri','Sat','Sun'] as $dow)
        <div class="py-2.5 text-center">{{ $dow }}</div>
      @endforeach
    </div>
    @foreach ($weeks as $week)
    <div class="grid grid-cols-7 divide-x divide-slate-50 dark:divide-dark-800/60 border-b border-slate-50 dark:border-dark-800/60 last:border-b-0">
      @foreach ($week as $day)
      @php($key = $day->format('Y-m-d'))
      <div wire:click="create('{{ $key }}')" wire:key="day-{{ $key }}"
           class="min-h-[96px] p-1.5 cursor-pointer hover:bg-slate-50/70 dark:hover:bg-dark-800/40 {{ $day->format('Y-m') !== $first->format('Y-m') ? 'bg-slate-50/40 dark:bg-dark-950/40 opacity-60' : '' }}">
        <span class="inline-flex w-6 h-6 items-center justify-center rounded-full text-[11px] font-bold {{ $day->isToday() ? 'bg-brand-600 text-white' : 'text-slate-500 dark:text-dark-400' }}">{{ $day->day }}</span>
        <div class="mt-1 space-y-1">
          @foreach ($events->get($key, collect())->take(3) as $event)
          <button wire:click.stop="edit({{ $event->id }})" wire:key="event-{{ $event->id }}"
                  class="w-full text-left px-1.5 py-1 rounded-md bg-{{ $event->color }}-50 text-{{ $event->color }}-700 dark:bg-{{ $event->color }}-950/60 dark:text-{{ $event->color }}-400 text-[10px] font-bold truncate">
            @unless($event->all_day){{ $event->starts_at->format('H:i') }} @endunless{{ $event->title }}
          </button>
          @endforeach
          @if ($events->get($key, collect())->count() > 3)
            <p class="text-[9px] font-bold text-slate-400 px-1.5">+{{ $events->get($key)->count() - 3 }} more</p>
          @endif
        </div>
      </div>
      @endforeach
    </div>
    @endforeach
  </div>

  {{-- Event modal --}}
  @if ($showForm)
  <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" wire:click="$set('showForm', false)"></div>
    <div class="relative max-w-md w-full bg-white dark:bg-dark-900 rounded-3xl shadow-xl border border-slate-200/80 dark:border-dark-800 p-8 space-y-4">
      <h2 class="text-lg font-extrabold">{{ $editingId ? 'Edit Event' : 'New Event' }}</h2>
      <form wire:submit="save" class="space-y-4 text-xs">
        <div>
          <label class="block font-bold mb-1">Title</label>
          <input type="text" wire:model="title" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 dark:bg-dark-800 border border-slate-200 dark:border-dark-700 focus:outline-none focus:ring-2 focus:ring-brand-500">
          @error('title') <span class="text-rose-500 font-semibold mt-1 block">{{ $message }}</span> @enderror
        </div>
        <div class="grid grid-cols-3 gap-3">
          <div>
            <label class="block font-bold mb-1">Date</label>
            <input type="date" wire:model="date" class="w-full px-2 py-2.5 rounded-xl bg-slate-50 dark:bg-dark-800 border border-slate-200 dark:border-dark-700 focus:outline-none">
          </div>
          <div>
            <label class="block font-bold mb-1">Start</label>
            <input type="time" wire:model="startTime" @disabled($allDay) class="w-full px-2 py-2.5 rounded-xl bg-slate-50 dark:bg-dark-800 border border-slate-200 dark:border-dark-700 focus:outline-none disabled:opacity-40">
          </div>
          <div>
            <label class="block font-bold mb-1">End</label>
            <input type="time" wire:model="endTime" @disabled($allDay) class="w-full px-2 py-2.5 rounded-xl bg-slate-50 dark:bg-dark-800 border border-slate-200 dark:border-dark-700 focus:outline-none disabled:opacity-40">
          </div>
        </div>
        <div class="flex items-center justify-between">
          <label class="flex items-center gap-2 cursor-pointer font-semibold">
            <input type="checkbox" wire:model.live="allDay" class="rounded text-brand-600 focus:ring-brand-500"> All-day event
          </label>
          <select wire:model="color" class="px-2 py-2 rounded-xl bg-slate-50 dark:bg-dark-800 border border-slate-200 dark:border-dark-700 focus:outline-none font-semibold">
            <option value="brand">Indigo</option><option value="emerald">Emerald</option><option value="amber">Amber</option><option value="rose">Rose</option><option value="sky">Sky</option><option value="violet">Violet</option>
          </select>
        </div>
        <div>
          <label class="block font-bold mb-1">Notes</label>
          <textarea wire:model="description" rows="2" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 dark:bg-dark-800 border border-slate-200 dark:border-dark-700 focus:outline-none"></textarea>
        </div>
        <div class="flex items-center justify-between pt-2">
          @if ($editingId)
            <button type="button" wire:click="deleteEvent({{ $editingId }})" wire:confirm="Delete this event?" class="px-3 py-2 rounded-xl bg-rose-50 text-rose-700 hover:bg-rose-100 dark:bg-rose-950/50 dark:text-rose-400 text-xs font-semibold">Delete</button>
          @else <span></span> @endif
          <div class="flex items-center gap-2">
            <button type="button" wire:click="$set('showForm', false)" class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-500 hover:bg-slate-100 dark:hover:bg-dark-800">Cancel</button>
            <button type="submit" class="px-4 py-2 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-semibold">{{ $editingId ? 'Save' : 'Add Event' }}</button>
          </div>
        </div>
      </form>
    </div>
  </div>
  @endif
</div>
