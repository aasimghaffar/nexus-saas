<div class="space-y-6">
  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
      <h1 class="text-2xl font-extrabold tracking-tight">Cloud Document Storage</h1>
      <p class="text-xs text-slate-500 dark:text-dark-400 mt-1">Your private files — organized in folders, downloadable anywhere.</p>
    </div>
    <div class="flex items-center gap-3">
      <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search files..."
             class="text-xs px-3 py-2 rounded-xl bg-white dark:bg-dark-900 border border-slate-200 dark:border-dark-700 focus:outline-none w-48">
      <label class="px-4 py-2 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-semibold shadow-md shadow-brand-500/20 cursor-pointer whitespace-nowrap">
        <input type="file" wire:model="uploads" multiple class="hidden">
        <span wire:loading.remove wire:target="uploads">Upload Files</span>
        <span wire:loading wire:target="uploads">Uploading…</span>
      </label>
    </div>
  </div>

  @error('uploads') <div class="rounded-xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-900 text-rose-700 dark:text-rose-400 text-xs font-semibold px-4 py-3">{{ $message }}</div> @enderror
  @error('uploads.*') <div class="rounded-xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-900 text-rose-700 dark:text-rose-400 text-xs font-semibold px-4 py-3">{{ $message }}</div> @enderror

  <div class="grid grid-cols-1 lg:grid-cols-4 gap-4">
    {{-- Sidebar: quota + folders --}}
    <div class="space-y-4">
      <div class="p-5 rounded-2xl bg-white dark:bg-dark-900 border border-slate-200/80 dark:border-dark-800 shadow-sm">
        <p class="text-xs font-bold text-slate-500 dark:text-dark-400 uppercase">Storage Used</p>
        <p class="text-xl font-extrabold mt-1">{{ number_format($usedBytes / 1073741824, 2) }} GB <span class="text-xs font-semibold text-slate-400">/ {{ $quotaGb }} GB</span></p>
        <div class="w-full h-1.5 bg-slate-100 dark:bg-dark-800 rounded-full mt-3 overflow-hidden">
          <div class="h-full {{ $usedPercent > 85 ? 'bg-rose-500' : 'bg-brand-600' }} rounded-full" style="width: {{ $usedPercent }}%"></div>
        </div>
      </div>

      <div class="p-4 rounded-2xl bg-white dark:bg-dark-900 border border-slate-200/80 dark:border-dark-800 shadow-sm">
        <div class="flex items-center justify-between px-1">
          <p class="text-xs font-bold text-slate-500 dark:text-dark-400 uppercase">Folders</p>
          <button wire:click="$toggle('showNewFolder')" class="text-brand-600 dark:text-brand-400 text-xs font-bold">+ New</button>
        </div>
        @if ($showNewFolder)
        <form wire:submit="createFolder" class="flex items-center gap-1.5 mt-2">
          <input type="text" wire:model="newFolderName" placeholder="Folder name" class="flex-1 text-xs px-2.5 py-2 rounded-lg bg-slate-50 dark:bg-dark-800 border border-slate-200 dark:border-dark-700 focus:outline-none">
          <button type="submit" class="px-2.5 py-2 rounded-lg bg-brand-600 text-white text-xs font-bold">OK</button>
        </form>
        @error('newFolderName') <span class="text-rose-500 font-semibold text-[11px] block mt-1">{{ $message }}</span> @enderror
        @endif
        <nav class="mt-2 space-y-0.5 text-xs font-semibold">
          <button wire:click="$set('folderId', null)" class="w-full text-left px-3 py-2 rounded-xl {{ $folderId === null ? 'bg-brand-50 text-brand-700 dark:bg-brand-950/40 dark:text-brand-400' : 'text-slate-600 dark:text-dark-300 hover:bg-slate-50 dark:hover:bg-dark-800' }}">All Files</button>
          @foreach ($folders as $folder)
          <div class="flex items-center group" wire:key="folder-{{ $folder->id }}">
            <button wire:click="$set('folderId', {{ $folder->id }})" class="flex-1 text-left px-3 py-2 rounded-xl {{ $folderId === $folder->id ? 'bg-brand-50 text-brand-700 dark:bg-brand-950/40 dark:text-brand-400' : 'text-slate-600 dark:text-dark-300 hover:bg-slate-50 dark:hover:bg-dark-800' }}">
              {{ $folder->name }} <span class="text-slate-400 font-normal">({{ $folder->files_count }})</span>
            </button>
            <button wire:click="deleteFolder({{ $folder->id }})" wire:confirm="Delete folder and all files inside?" class="opacity-0 group-hover:opacity-100 p-1.5 text-slate-300 hover:text-rose-500">
              <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
          </div>
          @endforeach
        </nav>
      </div>
    </div>

    {{-- File list --}}
    <div class="lg:col-span-3 rounded-2xl bg-white dark:bg-dark-900 border border-slate-200/80 dark:border-dark-800 shadow-sm overflow-hidden">
      <table class="w-full text-left">
        <thead>
          <tr class="bg-slate-50/75 dark:bg-dark-800/50 text-[11px] font-bold uppercase tracking-wider text-slate-500 dark:text-dark-400 border-b border-slate-100 dark:border-dark-800">
            <th class="py-3.5 px-4">Name</th>
            <th class="py-3.5 px-4">Type</th>
            <th class="py-3.5 px-4">Size</th>
            <th class="py-3.5 px-4">Uploaded</th>
            <th class="py-3.5 px-4 text-right">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 dark:divide-dark-800 text-xs">
          @forelse ($files as $file)
          <tr class="hover:bg-slate-50/50 dark:hover:bg-dark-800/50" wire:key="file-{{ $file->id }}">
            <td class="py-3 px-4">
              <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-brand-50 text-brand-600 dark:bg-brand-950/50 dark:text-brand-400 flex items-center justify-center">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
                <span class="font-bold text-slate-900 dark:text-white">{{ $file->name }}</span>
              </div>
            </td>
            <td class="py-3 px-4 text-slate-400 uppercase text-[10px] font-bold">{{ Str::afterLast($file->mime ?? 'file', '/') }}</td>
            <td class="py-3 px-4 text-slate-500 dark:text-dark-400">{{ $file->human_size }}</td>
            <td class="py-3 px-4 text-slate-500 dark:text-dark-400">{{ $file->created_at->diffForHumans() }}</td>
            <td class="py-3 px-4 text-right space-x-1 whitespace-nowrap">
              <button wire:click="download({{ $file->id }})" class="px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 dark:bg-dark-800 dark:hover:bg-dark-700 font-semibold text-[11px]">Download</button>
              <button wire:click="deleteFile({{ $file->id }})" wire:confirm="Delete {{ $file->name }}?" class="px-2.5 py-1 rounded-lg bg-rose-50 text-rose-700 hover:bg-rose-100 dark:bg-rose-950/50 dark:text-rose-400 font-semibold text-[11px]">Delete</button>
            </td>
          </tr>
          @empty
          <tr><td colspan="5" class="py-12 text-center">
            <img src="{{ asset('assets/images/illustrations/empty.svg') }}" class="w-20 mx-auto opacity-70" alt="">
            <p class="text-xs text-slate-400 mt-3">This folder is empty — upload your first file.</p>
          </td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
