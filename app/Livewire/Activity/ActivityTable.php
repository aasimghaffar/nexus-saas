<?php

namespace App\Livewire\Activity;

use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Activitylog\Models\Activity;

#[Layout('layouts.app')]
class ActivityTable extends Component
{
    use WithPagination;

    public string $search = '';
    public string $logFilter = 'all';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedLogFilter(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $activities = Activity::query()
            ->with('causer')
            ->when($this->logFilter !== 'all', fn ($q) => $q->where('log_name', $this->logFilter))
            ->when($this->search, fn ($q) => $q->where(fn ($q) => $q
                ->where('description', 'like', "%{$this->search}%")
                ->orWhereHas('causer', fn ($c) => $c->where('name', 'like', "%{$this->search}%")
                    ->orWhere('email', 'like', "%{$this->search}%"))))
            ->latest()
            ->paginate(12);

        return view('livewire.activity.activity-table', [
            'activities' => $activities,
            'logNames'   => Activity::query()->select('log_name')->distinct()->pluck('log_name'),
        ])->title('Security Audit & Activity Logs');
    }
}
