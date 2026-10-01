<div class="space-y-6">
  <div>
    <h1 class="text-2xl font-extrabold tracking-tight">Connected Apps &amp; Marketplace</h1>
    <p class="text-xs text-slate-500 dark:text-dark-400 mt-1">Wire workspace events into the tools your team already uses.</p>
  </div>

  <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
    @foreach ($catalog as $slug => [$name, $description, $logo, $fieldLabel])
    @php($isConnected = in_array($slug, $connected))
    <div class="p-5 rounded-2xl bg-white dark:bg-dark-900 border border-slate-200/80 dark:border-dark-800 shadow-sm flex flex-col" wire:key="app-{{ $slug }}">
      <div class="flex items-start justify-between">
        <div class="w-10 h-10 rounded-xl bg-slate-50 dark:bg-dark-800 border border-slate-100 dark:border-dark-700 flex items-center justify-center p-2">
          <img src="{{ asset('assets/images/brands/'.$logo) }}" class="w-full h-full" alt="{{ $name }}">
        </div>
        @if ($isConnected)
          <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-400">Connected</span>
        @elseif ($slug === 'stripe')
          <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ config('cashier.secret') ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-400' : 'bg-slate-100 text-slate-500 dark:bg-dark-800 dark:text-dark-300' }}">{{ config('cashier.secret') ? 'Active' : 'Not configured' }}</span>
        @endif
      </div>
      <h3 class="text-sm font-bold mt-3">{{ $name }}</h3>
      <p class="text-[11px] text-slate-500 dark:text-dark-400 mt-1 flex-1">{{ $description }}</p>

      <div class="flex items-center gap-1.5 mt-4">
        @if ($fieldLabel === null)
          <a href="{{ route('billing') }}" class="px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 dark:bg-dark-800 dark:hover:bg-dark-700 font-semibold text-[11px]">Open Billing</a>
        @elseif ($isConnected)
          <button wire:click="sendTest('{{ $slug }}')" class="px-3 py-1.5 rounded-lg bg-brand-50 text-brand-700 hover:bg-brand-100 dark:bg-brand-950/50 dark:text-brand-400 font-semibold text-[11px]">Send Test</button>
          <button wire:click="configure('{{ $slug }}')" class="px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 dark:bg-dark-800 dark:hover:bg-dark-700 font-semibold text-[11px]">Edit</button>
          <button wire:click="disconnect('{{ $slug }}')" wire:confirm="Disconnect {{ $name }}?" class="px-3 py-1.5 rounded-lg bg-rose-50 text-rose-700 hover:bg-rose-100 dark:bg-rose-950/50 dark:text-rose-400 font-semibold text-[11px]">Disconnect</button>
        @else
          <button wire:click="configure('{{ $slug }}')" class="px-3 py-1.5 rounded-lg bg-brand-600 hover:bg-brand-700 text-white font-semibold text-[11px] shadow-sm shadow-brand-500/20">Connect</button>
        @endif
      </div>
    </div>
    @endforeach
  </div>

  @if ($configuring)
  <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" wire:click="$set('configuring', null)"></div>
    <div class="relative max-w-md w-full bg-white dark:bg-dark-900 rounded-3xl shadow-xl border border-slate-200/80 dark:border-dark-800 p-8 space-y-4">
      <h2 class="text-lg font-extrabold">Connect {{ $catalog[$configuring][0] }}</h2>
      <form wire:submit="save" class="space-y-4 text-xs">
        <div>
          <label class="block font-bold mb-1">{{ $catalog[$configuring][3] }}</label>
          <input type="url" wire:model="webhookUrl" placeholder="https://..."
                 class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 dark:bg-dark-800 border border-slate-200 dark:border-dark-700 font-mono text-[11px] focus:outline-none focus:ring-2 focus:ring-brand-500">
          @error('webhookUrl') <span class="text-rose-500 font-semibold mt-1 block">{{ $message }}</span> @enderror
        </div>
        <div class="flex items-center justify-end gap-2 pt-2">
          <button type="button" wire:click="$set('configuring', null)" class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-500 hover:bg-slate-100 dark:hover:bg-dark-800">Cancel</button>
          <button type="submit" class="px-4 py-2 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-semibold">Save Connection</button>
        </div>
      </form>
    </div>
  </div>
  @endif
</div>
