<?php

namespace App\Livewire\Kanban;

use App\Models\KanbanColumn;
use App\Models\Project;
use App\Models\Task;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Layout('layouts.app')]
class Board extends Component
{
    #[Url]
    public ?int $projectFilter = null;

    public bool $showForm = false;
    public ?int $editingId = null;
    public string $title = '';
    public string $description = '';
    public string $priority = 'medium';
    public ?int $columnId = null;
    public ?int $projectId = null;
    public ?int $assigneeId = null;
    public ?string $due_date = null;

    protected function rules(): array
    {
        return [
            'title'       => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string', 'max:2000'],
            'priority'    => ['required', 'in:low,medium,high'],
            'columnId'    => ['required', 'exists:kanban_columns,id'],
            'projectId'   => ['nullable', 'exists:projects,id'],
            'assigneeId'  => ['nullable', 'exists:users,id'],
            'due_date'    => ['nullable', 'date'],
        ];
    }

    /**
     * Fired from resources/js/kanban-livewire.js after a native HTML5 drop.
     * $ordered = task ids in their new order within the target column.
     */
    #[On('taskMoved')]
    public function taskMoved(int $taskId, int $columnId, array $ordered = []): void
    {
        $task = Task::findOrFail($taskId);
        $task->update(['kanban_column_id' => $columnId]);

        foreach (array_values($ordered) as $position => $id) {
            Task::where('id', $id)->update(['position' => $position, 'kanban_column_id' => $columnId]);
        }
    }

    public function create(int $columnId): void
    {
        $this->reset('editingId', 'title', 'description', 'priority', 'projectId', 'assigneeId', 'due_date');
        $this->columnId = $columnId;
        $this->showForm = true;
    }

    public function edit(int $taskId): void
    {
        $task = Task::findOrFail($taskId);
        $this->editingId = $task->id;
        $this->title = $task->title;
        $this->description = (string) $task->description;
        $this->priority = $task->priority;
        $this->columnId = $task->kanban_column_id;
        $this->projectId = $task->project_id;
        $this->assigneeId = $task->assignee_id;
        $this->due_date = $task->due_date?->format('Y-m-d');
        $this->showForm = true;
    }

    public function save(): void
    {
        $data = $this->validate();
        $payload = [
            'title'            => $data['title'],
            'description'      => $data['description'],
            'priority'         => $data['priority'],
            'kanban_column_id' => $data['columnId'],
            'project_id'       => $data['projectId'],
            'assignee_id'      => $data['assigneeId'],
            'due_date'         => $data['due_date'],
        ];

        if ($this->editingId) {
            Task::findOrFail($this->editingId)->update($payload);
        } else {
            $payload['position'] = (Task::where('kanban_column_id', $data['columnId'])->max('position') ?? -1) + 1;
            Task::create($payload);
        }

        $this->reset('showForm', 'editingId');
    }

    public function deleteTask(int $taskId): void
    {
        Task::findOrFail($taskId)->delete();
        $this->reset('showForm', 'editingId');
    }

    public function render()
    {
        $columns = KanbanColumn::orderBy('position')
            ->with(['tasks' => function ($q) {
                $q->with(['assignee', 'project'])
                  ->when($this->projectFilter, fn ($q) => $q->where('project_id', $this->projectFilter));
            }])
            ->get();

        return view('livewire.kanban.board', [
            'columns'  => $columns,
            'projects' => Project::orderBy('name')->get(['id', 'name']),
            'members'  => \App\Models\User::orderBy('name')->get(['id', 'name']),
        ])->title('Sprint Board');
    }
}
