<div class="space-y-6">
  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
      <h1 class="text-2xl font-extrabold tracking-tight">API Keys &amp; Programmatic Access</h1>
      <p class="text-xs text-slate-500 dark:text-dark-400 mt-1">Generate personal access tokens for the Nexus REST API. Base URL: <code class="font-mono font-bold">{{ url('/api/v1') }}</code></p>
    </div>
    <button wire:click="$set('showForm', true)" class="px-4 py-2 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-semibold shadow-md shadow-brand-500/20 whitespace-nowrap">Generate New Key</button>
  </div>

  @if ($plainTextToken)
  <div class="rounded-2xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-900 p-5">
    <p class="text-xs font-bold text-emerald-700 dark:text-emerald-400">Copy your token now — it will never be shown again.</p>
    <div class="flex items-center gap-2 mt-3">
      <code class="flex-1 px-3.5 py-2.5 rounded-xl bg-white dark:bg-dark-900 border border-emerald-200 dark:border-emerald-900 font-mono text-[11px] break-all select-all">{{ $plainTextToken }}</code>
      <button data-copy-text="{{ $plainTextToken }}" class="px-3 py-2.5 rounded-xl bg-emerald-600 text-white text-xs font-bold">Copy</button>
    </div>
    <button wire:click="dismissToken" class="mt-3 text-[11px] font-bold text-emerald-700 dark:text-emerald-400 hover:underline">I've stored it safely — dismiss</button>
  </div>
  @endif

  <div class="rounded-2xl bg-white dark:bg-dark-900 border border-slate-200/80 dark:border-dark-800 shadow-sm overflow-hidden">
    <table class="w-full text-left">
      <thead>
        <tr class="bg-slate-50/75 dark:bg-dark-800/50 text-[11px] font-bold uppercase tracking-wider text-slate-500 dark:text-dark-400 border-b border-slate-100 dark:border-dark-800">
          <th class="py-3.5 px-4">Token Name</th>
          <th class="py-3.5 px-4">Abilities</th>
          <th class="py-3.5 px-4">Last Used</th>
          <th class="py-3.5 px-4">Created</th>
          <th class="py-3.5 px-4 text-right">Actions</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-slate-100 dark:divide-dark-800 text-xs">
        @forelse ($tokens as $token)
        <tr class="hover:bg-slate-50/50 dark:hover:bg-dark-800/50" wire:key="token-{{ $token->id }}">
          <td class="py-3 px-4 font-bold text-slate-900 dark:text-white">{{ $token->name }} <span class="text-slate-400 font-mono font-normal">(…{{ substr($token->token, -6) }})</span></td>
          <td class="py-3 px-4 space-x-1">
            @foreach ($token->abilities as $ability)
              <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $ability === 'write' ? 'bg-amber-50 text-amber-700 dark:bg-amber-950/60 dark:text-amber-400' : 'bg-sky-50 text-sky-700 dark:bg-sky-950/60 dark:text-sky-400' }}">{{ $ability }}</span>
            @endforeach
          </td>
          <td class="py-3 px-4 text-slate-500 dark:text-dark-400">{{ $token->last_used_at?->diffForHumans() ?? 'Never' }}</td>
          <td class="py-3 px-4 text-slate-500 dark:text-dark-400">{{ $token->created_at->format('d M Y') }}</td>
          <td class="py-3 px-4 text-right">
            <button wire:click="revoke({{ $token->id }})" wire:confirm="Revoke this token? Apps using it will stop working immediately." class="px-2.5 py-1 rounded-lg bg-rose-50 text-rose-700 hover:bg-rose-100 dark:bg-rose-950/50 dark:text-rose-400 font-semibold text-[11px]">Revoke</button>
          </td>
        </tr>
        @empty
        <tr><td colspan="5" class="py-10 text-center text-slate-400 text-xs">No API keys yet — generate your first token.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div class="rounded-2xl bg-white dark:bg-dark-900 border border-slate-200/80 dark:border-dark-800 shadow-sm p-6 text-xs">
    <h3 class="text-base font-bold">Quick Start</h3>
    <p class="text-slate-400 mt-1 mb-3">Authenticate with a Bearer token. Endpoints: <code class="font-mono">GET /projects</code>, <code class="font-mono">GET /tasks</code>, <code class="font-mono">POST /tasks</code> (write ability), <code class="font-mono">GET /tickets</code>.</p>
    <pre class="p-4 rounded-xl bg-slate-900 text-slate-200 font-mono text-[11px] overflow-x-auto">curl {{ url('/api/v1/projects') }} \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Accept: application/json"</pre>
  </div>

  @if ($showForm)
  <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" wire:click="$set('showForm', false)"></div>
    <div class="relative max-w-md w-full bg-white dark:bg-dark-900 rounded-3xl shadow-xl border border-slate-200/80 dark:border-dark-800 p-8 space-y-4">
      <h2 class="text-lg font-extrabold">Generate API Key</h2>
      <form wire:submit="createToken" class="space-y-4 text-xs">
        <div>
          <label class="block font-bold mb-1">Token Name</label>
          <input type="text" wire:model="tokenName" placeholder="e.g. CI Pipeline, Zapier" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 dark:bg-dark-800 border border-slate-200 dark:border-dark-700 focus:outline-none focus:ring-2 focus:ring-brand-500">
          @error('tokenName') <span class="text-rose-500 font-semibold mt-1 block">{{ $message }}</span> @enderror
        </div>
        <div>
          <label class="block font-bold mb-2">Abilities</label>
          <div class="flex items-center gap-4">
            <label class="flex items-center gap-2 cursor-pointer font-semibold"><input type="checkbox" wire:model="abilities" value="read" class="rounded text-brand-600 focus:ring-brand-500"> Read</label>
            <label class="flex items-center gap-2 cursor-pointer font-semibold"><input type="checkbox" wire:model="abilities" value="write" class="rounded text-brand-600 focus:ring-brand-500"> Write</label>
          </div>
          @error('abilities') <span class="text-rose-500 font-semibold mt-1 block">{{ $message }}</span> @enderror
        </div>
        <div class="flex items-center justify-end gap-2 pt-2">
          <button type="button" wire:click="$set('showForm', false)" class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-500 hover:bg-slate-100 dark:hover:bg-dark-800">Cancel</button>
          <button type="submit" class="px-4 py-2 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-semibold">Generate Token</button>
        </div>
      </form>
    </div>
  </div>
  @endif
</div>
