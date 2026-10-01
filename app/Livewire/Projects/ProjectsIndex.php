<?php

namespace App\Livewire\Projects;

use App\Models\Project;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class ProjectsIndex extends Component
{
    use WithPagination;

    public string $search = '';
    public string $statusFilter = 'all';

    public bool $showForm = false;
    public ?int $editingId = null;
    public string $name = '';
    public string $description = '';
    public string $status = 'active';
    public string $color = 'brand';
    public ?string $due_date = null;

    protected function rules(): array
    {
        return [
            'name'        => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string', 'max:2000'],
            'status'      => ['required', 'in:active,on-hold,completed'],
            'color'       => ['required', 'in:brand,emerald,amber,rose,sky,violet'],
            'due_date'    => ['nullable', 'date'],
        ];
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function create(): void
    {
        $this->authorize('create', Project::class);
        $this->reset('editingId', 'name', 'description', 'status', 'color', 'due_date');
        $this->showForm = true;
    }

    public function edit(int $id): void
    {
        $project = Project::findOrFail($id);
        $this->authorize('update', $project);

        $this->editingId = $project->id;
        $this->name = $project->name;
        $this->description = (string) $project->description;
        $this->status = $project->status;
        $this->color = $project->color;
        $this->due_date = $project->due_date?->format('Y-m-d');
        $this->showForm = true;
    }

    public function save(): void
    {
        $data = $this->validate();

        if ($this->editingId) {
            $project = Project::findOrFail($this->editingId);
            $this->authorize('update', $project);
            $project->update($data);
            activity('projects')->causedBy(auth()->user())->performedOn($project)->log('Project updated');
        } else {
            $this->authorize('create', Project::class);
            $project = Project::create($data + ['owner_id' => auth()->id()]);
            activity('projects')->causedBy(auth()->user())->performedOn($project)->log('Project created');
        }

        $this->reset('showForm', 'editingId');
    }

    public function delete(int $id): void
    {
        $project = Project::findOrFail($id);
        $this->authorize('delete', $project);
        $project->delete();
    }

    public function render()
    {
        $projects = Project::query()
            ->with('owner')
            ->withCount('tasks')
            ->when($this->search, fn ($q) => $q->where('name', 'like', "%{$this->search}%"))
            ->when($this->statusFilter !== 'all', fn ($q) => $q->where('status', $this->statusFilter))
            ->latest()
            ->paginate(9);

        return view('livewire.projects.projects-index', ['projects' => $projects])
            ->title('Projects');
    }
}
