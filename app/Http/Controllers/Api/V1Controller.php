<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Task;
use App\Models\Ticket;
use Illuminate\Http\Request;

class V1Controller extends Controller
{
    public function projects(Request $request)
    {
        return Project::withCount('tasks')->latest()->paginate(25);
    }

    public function tasks(Request $request)
    {
        return Task::with(['column:id,name', 'assignee:id,name', 'project:id,name'])->latest()->paginate(50);
    }

    public function storeTask(Request $request)
    {
        abort_unless($request->user()->tokenCan('write'), 403, 'Token lacks the write ability.');

        $data = $request->validate([
            'title'            => ['required', 'string', 'max:150'],
            'description'      => ['nullable', 'string', 'max:2000'],
            'kanban_column_id' => ['required', 'exists:kanban_columns,id'],
            'project_id'       => ['nullable', 'exists:projects,id'],
            'priority'         => ['nullable', 'in:low,medium,high'],
        ]);

        $data['position'] = (Task::where('kanban_column_id', $data['kanban_column_id'])->max('position') ?? -1) + 1;

        return response()->json(Task::create($data), 201);
    }

    public function tickets(Request $request)
    {
        return Ticket::query()
            ->when(! $request->user()->can('tickets.manage'), fn ($q) => $q->where('user_id', $request->user()->id))
            ->withCount('replies')
            ->latest()
            ->paginate(25);
    }
}
