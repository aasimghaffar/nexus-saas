<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;

class ProjectPolicy
{
    public function create(User $user): bool
    {
        return $user->can('projects.manage');
    }

    public function update(User $user, Project $project): bool
    {
        return $user->id === $project->owner_id || $user->can('projects.manage');
    }

    public function delete(User $user, Project $project): bool
    {
        return $this->update($user, $project);
    }
}
