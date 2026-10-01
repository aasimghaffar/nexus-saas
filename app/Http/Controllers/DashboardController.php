<?php

namespace App\Http\Controllers;

use App\Models\KanbanColumn;
use App\Models\Project;
use App\Models\Task;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Support\Carbon;
use Spatie\Activitylog\Models\Activity;

class DashboardController extends Controller
{
    /**
     * Main SaaS overview — feeds the template charts with live series.
     */
    public function index()
    {
        $months = collect(range(11, 0))->map(fn ($i) => now()->subMonths($i)->startOfMonth());

        $signups = $months->map(fn (Carbon $m) => User::whereBetween('created_at', [$m, $m->copy()->endOfMonth()])->count());

        $chartData = [
            'mrr' => [
                ['name' => 'New Members', 'data' => $signups->all()],
                ['name' => 'Tickets Opened', 'data' => $months->map(fn (Carbon $m) => Ticket::whereBetween('created_at', [$m, $m->copy()->endOfMonth()])->count())->all()],
            ],
            'mrrCategories' => $months->map(fn (Carbon $m) => $m->format('M'))->all(),
            'plans'      => [
                Ticket::where('status', 'open')->count(),
                Ticket::where('status', 'pending')->count(),
                Ticket::where('status', 'resolved')->count(),
                max(0, Task::count() - Task::whereNotNull('assignee_id')->count()),
            ],
            'planLabels' => ['Open Tickets', 'Pending Tickets', 'Resolved Tickets', 'Unassigned Tasks'],
        ];

        return view('dashboard', [
            'metrics' => [
                'users'          => User::count(),
                'usersThisMonth' => User::where('created_at', '>=', now()->startOfMonth())->count(),
                'projectsActive' => Project::where('status', 'active')->count(),
                'ticketsOpen'    => Ticket::whereIn('status', ['open', 'pending'])->count(),
                'tasksDone'      => Task::where('kanban_column_id', KanbanColumn::orderByDesc('position')->value('id'))->count(),
                'tasksTotal'     => Task::count(),
            ],
            'chartData'     => $chartData,
            'recentMembers' => User::latest()->take(5)->get(),
        ]);
    }

    /**
     * CRM / pipeline view built from projects & tickets.
     */
    public function crm()
    {
        $columns = KanbanColumn::orderBy('position')->withCount('tasks')->get();

        $chartData = [
            'pipeline' => [['name' => 'Tasks', 'data' => $columns->pluck('tasks_count')->all()]],
            'deals'    => [['name' => 'Tickets', 'data' => collect(range(6, 0))->map(fn ($i) => Ticket::whereBetween('created_at', [now()->subDays($i)->startOfDay(), now()->subDays($i)->endOfDay()])->count())->all()]],
        ];

        return view('dashboards.crm', [
            'columns'      => $columns,
            'projects'     => Project::withCount('tasks')->with('owner')->latest()->take(6)->get(),
            'ticketsByPriority' => Ticket::selectRaw('priority, count(*) as total')->groupBy('priority')->pluck('total', 'priority'),
            'chartData'    => $chartData,
        ]);
    }

    /**
     * Analytics view built from activity log & usage.
     */
    public function analytics()
    {
        $days = collect(range(13, 0))->map(fn ($i) => now()->subDays($i)->startOfDay());

        $chartData = [
            'traffic' => [
                ['name' => 'Events', 'data' => $days->map(fn (Carbon $d) => Activity::whereBetween('created_at', [$d, $d->copy()->endOfDay()])->count())->all()],
            ],
            'devices'      => [
                Activity::where('log_name', 'auth')->where('description', 'Signed in')->count(),
                Activity::where('log_name', 'auth')->where('description', 'Failed sign-in attempt')->count(),
                Activity::whereNotIn('log_name', ['auth'])->count(),
            ],
            'deviceLabels' => ['Sign-ins', 'Failed attempts', 'Other events'],
        ];

        return view('dashboards.analytics', [
            'days'       => $days,
            'topActors'  => Activity::selectRaw('causer_id, count(*) as total')->whereNotNull('causer_id')->groupBy('causer_id')->orderByDesc('total')->take(5)->with('causer')->get(),
            'chartData'  => $chartData,
            'totals'     => [
                'events14d' => Activity::where('created_at', '>=', now()->subDays(14))->count(),
                'signins'   => Activity::where('log_name', 'auth')->where('description', 'Signed in')->count(),
                'failed'    => Activity::where('log_name', 'auth')->where('description', 'Failed sign-in attempt')->count(),
            ],
        ]);
    }
}
