<?php

namespace Database\Seeders;

use App\Models\KanbanColumn;
use App\Models\Project;
use App\Models\Task;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Seeder;

class WorkspaceSeeder extends Seeder
{
    public function run(): void
    {
        // Kanban columns (matching the template board)
        $columns = collect([
            ['name' => 'Backlog',     'position' => 0, 'accent' => 'slate'],
            ['name' => 'In Progress', 'position' => 1, 'accent' => 'amber'],
            ['name' => 'Code Review', 'position' => 2, 'accent' => 'cyan'],
            ['name' => 'Done',        'position' => 3, 'accent' => 'emerald'],
        ])->map(fn ($c) => KanbanColumn::firstOrCreate(['name' => $c['name']], $c));

        // The workspace owner: the Phase-1 demo admin when present, otherwise
        // the first account (the super-admin created by the web installer).
        $admin = User::where('email', 'admin@example.com')->first()
            ?? User::orderBy('id')->first();

        if (! $admin) {
            $this->command?->warn('WorkspaceSeeder skipped: no users exist yet.');

            return;
        }

        $team = User::where('id', '!=', $admin->id)->pluck('id');

        // Demo projects
        $projects = collect([
            ['name' => 'Nexus Platform v2', 'description' => 'Core platform rebuild with multi-region support.', 'status' => 'active', 'color' => 'brand', 'due_date' => now()->addMonth()],
            ['name' => 'Mobile Companion App', 'description' => 'iOS/Android client for on-the-go workspace access.', 'status' => 'active', 'color' => 'emerald', 'due_date' => now()->addMonths(2)],
            ['name' => 'Marketing Site Refresh', 'description' => 'New landing pages and pricing experiments.', 'status' => 'on-hold', 'color' => 'amber', 'due_date' => null],
        ])->map(fn ($p) => Project::firstOrCreate(['name' => $p['name']], $p + ['owner_id' => $admin->id]));

        // Demo tasks (matching template card titles)
        $demoTasks = [
            ['Implement Stripe Webhook Retry Logic', 0, 'high', 0],
            ['Update API Documentation Swagger Spec', 0, 'low', 0],
            ['Multi-region Database Read Replicas', 1, 'high', 0],
            ['Tailwind Dark Mode Contrast Refactor', 2, 'medium', 1],
            ['OAuth2 Google SSO Provider Support', 3, 'medium', 0],
        ];

        foreach ($demoTasks as $i => [$title, $col, $priority, $projectIdx]) {
            Task::firstOrCreate(['title' => $title], [
                'kanban_column_id' => $columns[$col]->id,
                'project_id'       => $projects[$projectIdx]->id,
                'assignee_id'      => $team->isNotEmpty() ? $team[$i % $team->count()] : $admin->id,
                'priority'         => $priority,
                'position'         => $i,
                'due_date'         => now()->addDays(3 + $i * 2),
            ]);
        }

        // Demo tickets
        $requester = User::where('email', 'liam@example.com')->first() ?? $admin;
        $agent = User::where('email', 'sarah@example.com')->first();

        $ticket = Ticket::firstOrCreate(
            ['subject' => 'Custom Domain SSL Certificate Renewal Failing'],
            ['body' => "Our custom domain's SSL certificate failed to auto-renew last night. The dashboard shows 'renewal pending' but it's been stuck for 12 hours.\n\nDomain: app.example.com", 'status' => 'pending', 'priority' => 'high', 'user_id' => $requester->id, 'assigned_to' => $agent?->id]
        );

        if ($ticket->replies()->count() === 0 && $agent) {
            $ticket->replies()->create(['user_id' => $agent->id, 'body' => "Thanks for the report — I can see the renewal job stalled on a DNS validation step. I've re-queued it manually; propagation usually completes within the hour. I'll keep this ticket open until the certificate shows as active on your end."]);
        }

        Ticket::firstOrCreate(
            ['subject' => 'Invoice VAT number missing on May invoice'],
            ['body' => 'Our finance team needs the VAT number printed on invoice #INV-2026-051. Can this be regenerated?', 'status' => 'open', 'priority' => 'medium', 'user_id' => $requester->id]
        );
    }
}
