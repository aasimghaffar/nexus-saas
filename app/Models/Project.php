<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    protected $fillable = ['name', 'description', 'status', 'color', 'due_date', 'owner_id'];

    protected $casts = ['due_date' => 'date'];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    public function getProgressAttribute(): int
    {
        $total = $this->tasks_count ?? $this->tasks()->count();
        if ($total === 0) {
            return 0;
        }

        $doneColumn = KanbanColumn::orderByDesc('position')->value('id');
        $done = $this->tasks()->where('kanban_column_id', $doneColumn)->count();

        return (int) round($done / $total * 100);
    }
}
