<div class="space-y-6">
  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
      <h1 class="text-2xl font-extrabold tracking-tight">Sprint Board</h1>
      <p class="text-xs text-slate-500 dark:text-dark-400 mt-1">Drag cards between columns — every move is saved instantly.</p>
    </div>
    <select wire:model.live="projectFilter" class="text-xs font-semibold px-3 py-2 bg-white dark:bg-dark-900 border border-slate-200 dark:border-dark-700 rounded-xl focus:outline-none">
      <option value="">All Projects</option>
      @foreach ($projects as $project)
        <option value="{{ $project->id }}">{{ $project->name }}</option>
      @endforeach
    </select>
  </div>

  <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4 items-start" data-kanban-board wire:key="board-{{ $projectFilter ?? 'all' }}">
    @foreach ($columns as $column)
    <div class="kanban-column rounded-2xl bg-slate-100/70 dark:bg-dark-900/70 border border-slate-200/60 dark:border-dark-800 p-3"
         data-column-id="{{ $column->id }}" wire:key="col-{{ $column->id }}">
      <div class="flex items-center justify-between px-1.5 py-1">
        <h3 class="column-title text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-dark-200">{{ $column->name }}</h3>
        <span class="px-1.5 py-0.5 rounded-md bg-white dark:bg-dark-800 text-[10px] font-bold text-slate-400">{{ $column->tasks->count() }}</span>
      </div>

      <div class="space-y-2.5 mt-2 min-h-[60px]" data-task-list>
        @foreach ($column->tasks as $task)
        <div class="kanban-card p-3.5 rounded-xl bg-white dark:bg-dark-800 border border-slate-200/80 dark:border-dark-700 shadow-sm cursor-grab active:cursor-grabbing"
             data-task-id="{{ $task->id }}" wire:key="task-{{ $task->id }}" wire:click="edit({{ $task->id }})">
          <div class="flex items-center justify-between gap-2">
            @if ($task->project)
              <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-{{ $task->project->color }}-50 text-{{ $task->project->color }}-700 dark:bg-{{ $task->project->color }}-950/60 dark:text-{{ $task->project->color }}-400">{{ $task->project->name }}</span>
            @else
              <span></span>
            @endif
            <span class="text-[10px] font-bold uppercase {{ ['low' => 'text-slate-400', 'medium' => 'text-amber-500', 'high' => 'text-rose-500'][$task->priority] }}">{{ $task->priority }}</span>
          </div>
          <h4 class="text-xs font-bold text-slate-800 dark:text-slate-100 mt-2 {{ $loop->parent->last ? 'line-through opacity-70' : '' }}">{{ $task->title }}</h4>
          <div class="flex items-center justify-between mt-3">
            @if ($task->assignee)
              <img src="{{ $task->assignee->avatar_url }}" class="w-6 h-6 rounded-full" title="{{ $task->assignee->name }}" alt="">
            @else
              <span class="w-6 h-6 rounded-full border border-dashed border-slate-300 dark:border-dark-600"></span>
            @endif
            <span class="text-[10px] text-slate-400">{{ $task->due_date?->format('d M') }}</span>
          </div>
        </div>
        @endforeach
      </div>

      <button wire:click="create({{ $column->id }})" class="w-full mt-2.5 py-2 rounded-xl border border-dashed border-slate-300 dark:border-dark-700 text-[11px] font-bold text-slate-400 hover:text-brand-600 hover:border-brand-400 dark:hover:text-brand-400 transition-colors">
        + Add Task
      </button>
    </div>
    @endforeach
  </div>

  {{-- Task modal --}}
  @if ($showForm)
  <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" wire:click="$set('showForm', false)"></div>
    <div class="relative max-w-md w-full bg-white dark:bg-dark-900 rounded-3xl shadow-xl border border-slate-200/80 dark:border-dark-800 p-8 space-y-4">
      <h2 class="text-lg font-extrabold">{{ $editingId ? 'Edit Task' : 'New Task' }}</h2>
      <form wire:submit="save" class="space-y-4 text-xs">
        <div>
          <label class="block font-bold mb-1">Title</label>
          <input type="text" wire:model="title" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 dark:bg-dark-800 border border-slate-200 dark:border-dark-700 focus:outline-none focus:ring-2 focus:ring-brand-500">
          @error('title') <span class="text-rose-500 font-semibold mt-1 block">{{ $message }}</span> @enderror
        </div>
        <div>
          <label class="block font-bold mb-1">Description</label>
          <textarea wire:model="description" rows="3" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 dark:bg-dark-800 border border-slate-200 dark:border-dark-700 focus:outline-none"></textarea>
        </div>
        <div class="grid grid-cols-2 gap-3">
          <div>
            <label class="block font-bold mb-1">Column</label>
            <select wire:model="columnId" class="w-full px-2 py-2.5 rounded-xl bg-slate-50 dark:bg-dark-800 border border-slate-200 dark:border-dark-700 focus:outline-none">
              @foreach ($columns as $column)<option value="{{ $column->id }}">{{ $column->name }}</option>@endforeach
            </select>
          </div>
          <div>
            <label class="block font-bold mb-1">Priority</label>
            <select wire:model="priority" class="w-full px-2 py-2.5 rounded-xl bg-slate-50 dark:bg-dark-800 border border-slate-200 dark:border-dark-700 focus:outline-none">
              <option value="low">Low</option><option value="medium">Medium</option><option value="high">High</option>
            </select>
          </div>
          <div>
            <label class="block font-bold mb-1">Project</label>
            <select wire:model="projectId" class="w-full px-2 py-2.5 rounded-xl bg-slate-50 dark:bg-dark-800 border border-slate-200 dark:border-dark-700 focus:outline-none">
              <option value="">None</option>
              @foreach ($projects as $project)<option value="{{ $project->id }}">{{ $project->name }}</option>@endforeach
            </select>
          </div>
          <div>
            <label class="block font-bold mb-1">Assignee</label>
            <select wire:model="assigneeId" class="w-full px-2 py-2.5 rounded-xl bg-slate-50 dark:bg-dark-800 border border-slate-200 dark:border-dark-700 focus:outline-none">
              <option value="">Unassigned</option>
              @foreach ($members as $member)<option value="{{ $member->id }}">{{ $member->name }}</option>@endforeach
            </select>
          </div>
        </div>
        <div>
          <label class="block font-bold mb-1">Due Date</label>
          <input type="date" wire:model="due_date" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 dark:bg-dark-800 border border-slate-200 dark:border-dark-700 focus:outline-none">
        </div>
        <div class="flex items-center justify-between pt-2">
          @if ($editingId)
            <button type="button" wire:click="deleteTask({{ $editingId }})" wire:confirm="Delete this task?" class="px-3 py-2 rounded-xl bg-rose-50 text-rose-700 hover:bg-rose-100 dark:bg-rose-950/50 dark:text-rose-400 text-xs font-semibold">Delete</button>
          @else <span></span> @endif
          <div class="flex items-center gap-2">
            <button type="button" wire:click="$set('showForm', false)" class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-500 hover:bg-slate-100 dark:hover:bg-dark-800">Cancel</button>
            <button type="submit" class="px-4 py-2 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-semibold">{{ $editingId ? 'Save' : 'Create Task' }}</button>
          </div>
        </div>
      </form>
    </div>
  </div>
  @endif
</div>
