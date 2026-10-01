<div class="space-y-6 max-w-3xl mx-auto">
  <div class="text-center">
    <h1 class="text-2xl font-extrabold tracking-tight">Help Center &amp; FAQ</h1>
    <p class="text-xs text-slate-500 dark:text-dark-400 mt-2">Quick answers to the most common questions about the platform.</p>
    <div class="flex items-center justify-center gap-2 mt-4">
      <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search questions..."
             class="text-xs px-4 py-2.5 rounded-xl bg-white dark:bg-dark-900 border border-slate-200 dark:border-dark-700 focus:outline-none focus:ring-2 focus:ring-brand-500 w-72">
      @if ($canManage)
        <button wire:click="create" class="px-4 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-semibold shadow-md shadow-brand-500/20">Add FAQ</button>
      @endif
    </div>
  </div>

  @forelse ($groups as $category => $faqs)
  <div>
    <h2 class="text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-2 px-1">{{ $category }}</h2>
    <div class="space-y-2">
      @foreach ($faqs as $faq)
      <div class="rounded-2xl bg-white dark:bg-dark-900 border border-slate-200/80 dark:border-dark-800 shadow-sm overflow-hidden {{ ! $faq->published ? 'opacity-60' : '' }}"
           x-data="{ open: false }" wire:key="faq-{{ $faq->id }}">
        <button x-on:click="open = !open" class="w-full flex items-center justify-between gap-4 p-4 text-left">
          <span class="text-xs font-bold text-slate-900 dark:text-white">{{ $faq->question }} @if(! $faq->published)<span class="ml-1 text-[10px] text-amber-500">(draft)</span>@endif</span>
          <svg class="w-4 h-4 text-slate-400 flex-shrink-0 transition-transform" x-bind:class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
        </button>
        <div x-show="open" x-collapse.duration.200ms x-cloak class="px-4 pb-4">
          <p class="text-xs text-slate-500 dark:text-dark-400 leading-relaxed whitespace-pre-line">{{ $faq->answer }}</p>
          @if ($canManage)
          <div class="flex items-center gap-1.5 mt-3">
            <button wire:click="edit({{ $faq->id }})" class="px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 dark:bg-dark-800 dark:hover:bg-dark-700 font-semibold text-[11px]">Edit</button>
            <button wire:click="delete({{ $faq->id }})" wire:confirm="Delete this FAQ?" class="px-2.5 py-1 rounded-lg bg-rose-50 text-rose-700 hover:bg-rose-100 dark:bg-rose-950/50 dark:text-rose-400 font-semibold text-[11px]">Delete</button>
          </div>
          @endif
        </div>
      </div>
      @endforeach
    </div>
  </div>
  @empty
  <p class="text-center text-xs text-slate-400 py-10">No answers match your search.</p>
  @endforelse

  @if ($showForm)
  <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" wire:click="$set('showForm', false)"></div>
    <div class="relative max-w-lg w-full bg-white dark:bg-dark-900 rounded-3xl shadow-xl border border-slate-200/80 dark:border-dark-800 p-8 space-y-4">
      <h2 class="text-lg font-extrabold">{{ $editingId ? 'Edit FAQ' : 'New FAQ' }}</h2>
      <form wire:submit="save" class="space-y-4 text-xs">
        <div class="grid grid-cols-2 gap-3">
          <div>
            <label class="block font-bold mb-1">Category</label>
            <input type="text" wire:model="category" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 dark:bg-dark-800 border border-slate-200 dark:border-dark-700 focus:outline-none">
          </div>
          <label class="flex items-center gap-2 cursor-pointer font-semibold mt-6">
            <input type="checkbox" wire:model="published" class="rounded text-brand-600 focus:ring-brand-500"> Published
          </label>
        </div>
        <div>
          <label class="block font-bold mb-1">Question</label>
          <input type="text" wire:model="question" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 dark:bg-dark-800 border border-slate-200 dark:border-dark-700 focus:outline-none focus:ring-2 focus:ring-brand-500">
          @error('question') <span class="text-rose-500 font-semibold mt-1 block">{{ $message }}</span> @enderror
        </div>
        <div>
          <label class="block font-bold mb-1">Answer</label>
          <textarea wire:model="answer" rows="5" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 dark:bg-dark-800 border border-slate-200 dark:border-dark-700 focus:outline-none"></textarea>
          @error('answer') <span class="text-rose-500 font-semibold mt-1 block">{{ $message }}</span> @enderror
        </div>
        <div class="flex items-center justify-end gap-2 pt-2">
          <button type="button" wire:click="$set('showForm', false)" class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-500 hover:bg-slate-100 dark:hover:bg-dark-800">Cancel</button>
          <button type="submit" class="px-4 py-2 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-semibold">{{ $editingId ? 'Save' : 'Publish' }}</button>
        </div>
      </form>
    </div>
  </div>
  @endif
</div>
