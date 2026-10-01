<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Models\Project;
use App\Models\Task;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function __invoke(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        $user = $request->user();
        $results = [];

        if (mb_strlen($q) >= 2) {
            $like = '%'.$q.'%';

            $results['Projects'] = Project::where('name', 'like', $like)
                ->orWhere('description', 'like', $like)
                ->limit(8)->get()
                ->map(fn ($p) => ['title' => $p->name, 'subtitle' => ucfirst($p->status).' project', 'url' => route('kanban', ['projectFilter' => $p->id])]);

            $results['Tasks'] = Task::with('column:id,name')
                ->where('title', 'like', $like)
                ->limit(8)->get()
                ->map(fn ($t) => ['title' => $t->title, 'subtitle' => 'Board · '.($t->column->name ?? ''), 'url' => route('kanban')]);

            $results['Tickets'] = Ticket::where(function ($query) use ($like) {
                    $query->where('subject', 'like', $like)->orWhere('reference', 'like', $like);
                })
                ->when(! $user->can('tickets.manage'), fn ($query) => $query->where('user_id', $user->id))
                ->limit(8)->get()
                ->map(fn ($t) => ['title' => $t->subject, 'subtitle' => $t->reference.' · '.ucfirst($t->status), 'url' => route('tickets.show', $t)]);

            if ($user->can('users.view')) {
                $results['Members'] = User::where('name', 'like', $like)
                    ->orWhere('email', 'like', $like)
                    ->limit(8)->get()
                    ->map(fn ($u) => ['title' => $u->name, 'subtitle' => $u->email, 'url' => route('users')]);
            }

            $results['Help Articles'] = Faq::where('published', true)
                ->where(fn ($query) => $query->where('question', 'like', $like)->orWhere('answer', 'like', $like))
                ->limit(5)->get()
                ->map(fn ($f) => ['title' => $f->question, 'subtitle' => 'FAQ · '.$f->category, 'url' => route('faq')]);

            $results = array_filter($results, fn ($group) => $group->isNotEmpty());
        }

        return view('search-results', ['q' => $q, 'results' => $results]);
    }
}
