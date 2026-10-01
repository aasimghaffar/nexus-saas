<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Super Admin bypasses all checks via Gate::before (see FortifyServiceProvider).
     */
    public function viewAny(User $user): bool
    {
        return $user->can('users.view');
    }

    public function create(User $user): bool
    {
        return $user->can('users.manage');
    }

    public function update(User $user, User $target): bool
    {
        // Admins manage everyone except Super Admins.
        return $user->can('users.manage') && ! $target->hasRole('super-admin');
    }

    public function suspend(User $user, User $target): bool
    {
        return $user->id !== $target->id && $this->update($user, $target);
    }

    public function delete(User $user, User $target): bool
    {
        return $user->id !== $target->id
            && $user->can('users.manage')
            && ! $target->hasRole('super-admin');
    }
}
