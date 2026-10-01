<div class="space-y-6">
  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
      <h1 class="text-2xl font-extrabold tracking-tight">Active Projects Portfolio</h1>
      <p class="text-xs text-slate-500 dark:text-dark-400 mt-1">Track delivery status, ownership, and sprint progress across your workspace.</p>
    </div>
    <div class="flex items-center gap-3">
      <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search projects..."
             class="text-xs px-3 py-2 rounded-xl bg-white dark:bg-dark-900 border border-slate-200 dark:border-dark-700 focus:outline-none w-48">
      <select wire:model.live="statusFilter" class="text-xs font-semibold px-3 py-2 bg-white dark:bg-dark-900 border border-slate-200 dark:border-dark-700 rounded-xl focus:outline-none">
        <option value="all">All Statuses</option>
        <option value="active">Active</option>
        <option value="on-hold">On Hold</option>
        <option value="completed">Completed</option>
      </select>
      @can('create', App\Models\Project::class)
      <button wire:click="create" class="px-4 py-2 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-semibold shadow-md shadow-brand-500/20 whitespace-nowrap">New Project</button>
      @endcan
    </div>
  </div>

  <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
    @forelse ($projects as $project)
    <div class="p-5 rounded-2xl bg-white dark:bg-dark-900 border border-slate-200/80 dark:border-dark-800 shadow-sm flex flex-col" wire:key="project-{{ $project->id }}">
      <div class="flex items-start justify-between gap-3">
        <div class="w-10 h-10 rounded-xl bg-{{ $project->color }}-50 text-{{ $project->color }}-600 dark:bg-{{ $project->color }}-950/50 dark:text-{{ $project->color }}-400 flex items-center justify-center font-extrabold text-sm">
          {{ Str::of($project->name)->substr(0, 2)->upper() }}
        </div>
        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold
          @if($project->status === 'active') bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-400
          @elseif($project->status === 'on-hold') bg-amber-50 text-amber-700 dark:bg-amber-950/60 dark:text-amber-400
          @else bg-slate-100 text-slate-600 dark:bg-dark-800 dark:text-dark-300 @endif">
          {{ Str::of($project->status)->replace('-', ' ')->title() }}
        </span>
      </div>
      <h3 class="text-sm font-bold mt-3">{{ $project->name }}</h3>
      <p class="text-[11px] text-slate-500 dark:text-dark-400 mt-1 line-clamp-2">{{ $project->description }}</p>

      <div class="mt-4">
        <div class="flex items-center justify-between text-[11px] font-bold"><span>Progress</span><span class="text-slate-400">{{ $project->progress }}%</span></div>
        <div class="w-full h-1.5 bg-slate-100 dark:bg-dark-800 rounded-full mt-1.5 overflow-hidden">
          <div class="h-full bg-brand-600 rounded-full" style="width: {{ $project->progress }}%"></div>
        </div>
      </div>

      <div class="flex items-center justify-between mt-4 pt-4 border-t border-slate-100 dark:border-dark-800 text-[11px]">
        <div class="flex items-center gap-2">
          <img src="{{ $project->owner->avatar_url }}" class="w-6 h-6 rounded-full" alt="">
          <span class="font-semibold text-slate-600 dark:text-dark-300">{{ $project->owner->name }}</span>
        </div>
        <span class="text-slate-400">{{ $project->tasks_count }} tasks @if($project->due_date) &bull; due {{ $project->due_date->format('d M') }} @endif</span>
      </div>

      @can('update', $project)
      <div class="flex items-center gap-1 mt-3">
        <a href="{{ route('kanban', ['projectFilter' => $project->id]) }}" class="px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 dark:bg-dark-800 dark:hover:bg-dark-700 font-semibold text-[11px]">Board</a>
        <button wire:click="edit({{ $project->id }})" class="px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 dark:bg-dark-800 dark:hover:bg-dark-700 font-semibold text-[11px]">Edit</button>
        <button wire:click="delete({{ $project->id }})" wire:confirm="Delete this project? Its tasks stay on the board, unassigned." class="px-2.5 py-1 rounded-lg bg-rose-50 text-rose-700 hover:bg-rose-100 dark:bg-rose-950/50 dark:text-rose-400 font-semibold text-[11px]">Delete</button>
      </div>
      @endcan
    </div>
    @empty
    <div class="md:col-span-2 xl:col-span-3 py-14 text-center">
      <img src="{{ asset('assets/images/illustrations/empty.svg') }}" class="w-24 mx-auto opacity-70" alt="">
      <p class="text-xs text-slate-400 mt-3">No projects yet — create your first one.</p>
    </div>
    @endforelse
  </div>

  <div>{{ $projects->links() }}</div>

  {{-- Create / edit modal --}}
  @if ($showForm)
  <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" wire:click="$set('showForm', false)"></div>
    <div class="relative max-w-md w-full bg-white dark:bg-dark-900 rounded-3xl shadow-xl border border-slate-200/80 dark:border-dark-800 p-8 space-y-4">
      <h2 class="text-lg font-extrabold">{{ $editingId ? 'Edit Project' : 'New Project' }}</h2>
      <form wire:submit="save" class="space-y-4 text-xs">
        <div>
          <label class="block font-bold mb-1">Project Name</label>
          <input type="text" wire:model="name" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 dark:bg-dark-800 border border-slate-200 dark:border-dark-700 focus:outline-none focus:ring-2 focus:ring-brand-500">
          @error('name') <span class="text-rose-500 font-semibold mt-1 block">{{ $message }}</span> @enderror
        </div>
        <div>
          <label class="block font-bold mb-1">Description</label>
          <textarea wire:model="description" rows="3" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 dark:bg-dark-800 border border-slate-200 dark:border-dark-700 focus:outline-none focus:ring-2 focus:ring-brand-500"></textarea>
        </div>
        <div class="grid grid-cols-3 gap-3">
          <div>
            <label class="block font-bold mb-1">Status</label>
            <select wire:model="status" class="w-full px-2 py-2.5 rounded-xl bg-slate-50 dark:bg-dark-800 border border-slate-200 dark:border-dark-700 focus:outline-none">
              <option value="active">Active</option><option value="on-hold">On Hold</option><option value="completed">Completed</option>
            </select>
          </div>
          <div>
            <label class="block font-bold mb-1">Accent</label>
            <select wire:model="color" class="w-full px-2 py-2.5 rounded-xl bg-slate-50 dark:bg-dark-800 border border-slate-200 dark:border-dark-700 focus:outline-none">
              <option value="brand">Indigo</option><option value="emerald">Emerald</option><option value="amber">Amber</option><option value="rose">Rose</option><option value="sky">Sky</option><option value="violet">Violet</option>
            </select>
          </div>
          <div>
            <label class="block font-bold mb-1">Due Date</label>
            <input type="date" wire:model="due_date" class="w-full px-2 py-2.5 rounded-xl bg-slate-50 dark:bg-dark-800 border border-slate-200 dark:border-dark-700 focus:outline-none">
          </div>
        </div>
        <div class="flex items-center justify-end gap-2 pt-2">
          <button type="button" wire:click="$set('showForm', false)" class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-500 hover:bg-slate-100 dark:hover:bg-dark-800">Cancel</button>
          <button type="submit" class="px-4 py-2 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-semibold">{{ $editingId ? 'Save Changes' : 'Create Project' }}</button>
        </div>
      </form>
    </div>
  </div>
  @endif
</div>
