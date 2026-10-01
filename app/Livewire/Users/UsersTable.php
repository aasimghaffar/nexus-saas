<?php

namespace App\Livewire\Users;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class UsersTable extends Component
{
    use WithPagination;

    public string $search = '';
    public string $roleFilter = 'all';
    public string $sortField = 'name';
    public string $sortDirection = 'asc';

    public bool $showInvite = false;
    public string $inviteName = '';
    public string $inviteEmail = '';
    public string $inviteRole = 'viewer';

    protected $queryString = ['search' => ['except' => ''], 'roleFilter' => ['except' => 'all']];

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedRoleFilter(): void
    {
        $this->resetPage();
    }

    public function sortBy(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    /* ------------------------------------------------------------ invite */

    public function invite(): void
    {
        $this->authorize('create', User::class);

        $this->validate([
            'inviteName'  => ['required', 'string', 'max:255'],
            'inviteEmail' => ['required', 'email', 'max:255', 'unique:users,email'],
            'inviteRole'  => ['required', 'in:admin,editor,viewer'],
        ], [], ['inviteName' => 'name', 'inviteEmail' => 'email', 'inviteRole' => 'role']);

        $user = User::create([
            'name'         => $this->inviteName,
            'email'        => $this->inviteEmail,
            'company_name' => auth()->user()->company_name,
            'password'     => Hash::make(Str::random(40)),
        ]);
        $user->syncRoles($this->inviteRole);

        // The invitation is a password-reset link: the invitee sets their own password.
        Password::sendResetLink(['email' => $user->email]);

        activity('users')->causedBy(auth()->user())->performedOn($user)->log('Invited team member');

        $this->reset('showInvite', 'inviteName', 'inviteEmail', 'inviteRole');
        session()->flash('status', 'Invitation sent to '.$user->email);
    }

    /* ----------------------------------------------------------- actions */

    public function changeRole(int $userId, string $role): void
    {
        $target = User::findOrFail($userId);
        $this->authorize('update', $target);

        if (! in_array($role, ['admin', 'editor', 'viewer'], true)) {
            return;
        }

        $target->syncRoles($role);
        activity('users')->causedBy(auth()->user())->performedOn($target)->log("Role changed to {$role}");
    }

    public function toggleSuspend(int $userId): void
    {
        $target = User::findOrFail($userId);
        $this->authorize('suspend', $target);

        $target->forceFill(['suspended_at' => $target->isSuspended() ? null : now()])->save();
    }

    public function deleteUser(int $userId): void
    {
        $target = User::findOrFail($userId);
        $this->authorize('delete', $target);

        activity('users')->causedBy(auth()->user())->log("Deleted account {$target->email}");
        $target->delete();
    }

    private function seatLimit(): int
    {
        $owner = auth()->user();

        if (config('cashier.secret') && $owner->subscribed('default')) {
            $stripePrice = $owner->subscription('default')->stripe_price;
            foreach (config('nexus.plans') as $plan) {
                if (($plan['stripe_price'] ?? null) === $stripePrice) {
                    return (int) $plan['seats'];
                }
            }
        }

        return (int) config('nexus.seats');
    }

    /* ------------------------------------------------------------ render */

    public function render()
    {
        $users = User::query()
            ->with('roles')
            ->when($this->search, fn ($q) => $q->where(fn ($q) => $q
                ->where('name', 'like', "%{$this->search}%")
                ->orWhere('email', 'like', "%{$this->search}%")))
            ->when($this->roleFilter !== 'all', fn ($q) => $q->role($this->roleFilter))
            ->orderBy(in_array($this->sortField, ['name', 'email', 'last_seen_at']) ? $this->sortField : 'name', $this->sortDirection)
            ->paginate(8);

        return view('livewire.users.users-table', [
            'users'      => $users,
            'totalUsers' => User::count(),
            'seatLimit'  => $this->seatLimit(),
            'adminCount' => User::role(['super-admin', 'admin'])->count(),
            'pending'    => User::whereNull('email_verified_at')->count(),
            'roles'      => config('nexus.roles'),
        ])->title('Team Members & Access');
    }
}
