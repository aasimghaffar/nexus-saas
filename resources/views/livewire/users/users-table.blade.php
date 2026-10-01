<div class="space-y-6">
  {{-- Page header --}}
  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
      <h1 class="text-2xl font-extrabold tracking-tight">Team Members &amp; Access</h1>
      <p class="text-xs text-slate-500 dark:text-dark-400 mt-1">Manage workspace users, assign role-based access control (RBAC), and invite teammates.</p>
    </div>
    @can('create', App\Models\User::class)
    <button wire:click="$set('showInvite', true)" class="flex items-center gap-1.5 px-4 py-2 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-semibold shadow-md shadow-brand-500/20">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
      <span>Invite Member</span>
    </button>
    @endcan
  </div>

  {{-- Stats row --}}
  <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
    <div class="p-5 rounded-2xl bg-white dark:bg-dark-900 border border-slate-200/80 dark:border-dark-800 shadow-sm">
      <p class="text-xs font-bold text-slate-500 dark:text-dark-400 uppercase">Total Seats Occupied</p>
      <p class="text-2xl font-extrabold text-slate-900 dark:text-white mt-1">{{ $totalUsers }} / {{ $seatLimit }} Seats</p>
      <div class="w-full h-1.5 bg-slate-100 dark:bg-dark-800 rounded-full mt-3 overflow-hidden">
        <div class="h-full bg-brand-600 rounded-full" style="width: {{ min(100, round($totalUsers / max(1, $seatLimit) * 100)) }}%"></div>
      </div>
    </div>
    <div class="p-5 rounded-2xl bg-white dark:bg-dark-900 border border-slate-200/80 dark:border-dark-800 shadow-sm">
      <p class="text-xs font-bold text-slate-500 dark:text-dark-400 uppercase">Administrators</p>
      <p class="text-2xl font-extrabold text-slate-900 dark:text-white mt-1">{{ $adminCount }} Active {{ Str::plural('Admin', $adminCount) }}</p>
      <p class="text-xs text-slate-400 mt-1">Full system root access</p>
    </div>
    <div class="p-5 rounded-2xl bg-white dark:bg-dark-900 border border-slate-200/80 dark:border-dark-800 shadow-sm">
      <p class="text-xs font-bold text-slate-500 dark:text-dark-400 uppercase">Pending Invitations</p>
      <p class="text-2xl font-extrabold text-slate-900 dark:text-white mt-1">{{ $pending }} Pending</p>
      <p class="text-xs text-amber-500 mt-1">Invites sent via email</p>
    </div>
  </div>

  {{-- Users table --}}
  <div class="rounded-2xl bg-white dark:bg-dark-900 border border-slate-200/80 dark:border-dark-800 shadow-sm overflow-hidden">
    <div class="p-5 border-b border-slate-100 dark:border-dark-800 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
      <div class="flex items-center gap-3">
        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search users by name, email..."
               class="text-xs px-3 py-2 rounded-xl bg-slate-50 dark:bg-dark-800 border border-slate-200 dark:border-dark-700 text-slate-700 dark:text-dark-200 focus:outline-none w-56 sm:w-72">
        <select wire:model.live="roleFilter" class="text-xs font-semibold px-3 py-2 bg-slate-50 dark:bg-dark-800 border border-slate-200 dark:border-dark-700 rounded-xl text-slate-700 dark:text-dark-200 focus:outline-none">
          <option value="all">All Roles</option>
          @foreach ($roles as $key => $role)
            <option value="{{ $key }}">{{ $role['label'] }}</option>
          @endforeach
        </select>
      </div>
      <div wire:loading class="text-[11px] font-semibold text-brand-500">Refreshing…</div>
    </div>

    <div class="overflow-x-auto">
      <table class="w-full text-left">
        <thead>
          <tr class="bg-slate-50/75 dark:bg-dark-800/50 text-[11px] font-bold uppercase tracking-wider text-slate-500 dark:text-dark-400 border-b border-slate-100 dark:border-dark-800">
            <th class="py-3.5 px-4 cursor-pointer select-none" wire:click="sortBy('name')">
              User @if($sortField === 'name') <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span> @endif
            </th>
            <th class="py-3.5 px-4">Role</th>
            <th class="py-3.5 px-4">Status</th>
            <th class="py-3.5 px-4">2FA Status</th>
            <th class="py-3.5 px-4 cursor-pointer select-none" wire:click="sortBy('last_seen_at')">
              Last Active @if($sortField === 'last_seen_at') <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span> @endif
            </th>
            <th class="py-3.5 px-4 text-right">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 dark:divide-dark-800 text-xs">
          @forelse ($users as $user)
          <tr class="hover:bg-slate-50/50 dark:hover:bg-dark-800/50" wire:key="user-{{ $user->id }}">
            <td class="py-3 px-4">
              <div class="flex items-center gap-3">
                <img src="{{ $user->avatar_url }}" class="w-8 h-8 rounded-full" alt="">
                <div>
                  <p class="font-bold text-slate-900 dark:text-white">{{ $user->name }} @if($user->id === auth()->id())<span class="text-slate-400 font-medium">(you)</span>@endif</p>
                  <p class="text-[11px] text-slate-400">{{ $user->email }}</p>
                </div>
              </div>
            </td>
            <td class="py-3 px-4">
              @can('update', $user)
                <select wire:change="changeRole({{ $user->id }}, $event.target.value)"
                        class="text-[11px] font-bold px-2 py-1 rounded-lg bg-slate-50 dark:bg-dark-800 border border-slate-200 dark:border-dark-700 focus:outline-none">
                  @foreach (['admin', 'editor', 'viewer'] as $r)
                    <option value="{{ $r }}" @selected($user->hasRole($r))>{{ config("nexus.roles.$r.label") }}</option>
                  @endforeach
                </select>
              @else
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold {{ $user->role_badge }}">{{ $user->role_label }}</span>
              @endcan
            </td>
            <td class="py-3 px-4">
              @if ($user->isSuspended())
                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 dark:bg-rose-950/60 dark:text-rose-400">Suspended</span>
              @elseif (! $user->hasVerifiedEmail())
                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 dark:bg-amber-950/60 dark:text-amber-400">Invited</span>
              @else
                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-400">Active</span>
              @endif
            </td>
            <td class="py-3 px-4 font-semibold {{ $user->hasTwoFactorEnabled() ? 'text-emerald-600 dark:text-emerald-400' : 'text-slate-400' }}">
              {{ $user->hasTwoFactorEnabled() ? 'Enabled' : 'Disabled' }}
            </td>
            <td class="py-3 px-4 text-slate-500 dark:text-dark-400">
              {{ $user->last_seen_at?->diffForHumans() ?? 'Never' }}
            </td>
            <td class="py-3 px-4 text-right space-x-1 whitespace-nowrap">
              @can('suspend', $user)
                <button wire:click="toggleSuspend({{ $user->id }})"
                        class="px-2.5 py-1 rounded-lg {{ $user->isSuspended() ? 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100 dark:bg-emerald-950/50 dark:text-emerald-400' : 'bg-amber-50 text-amber-700 hover:bg-amber-100 dark:bg-amber-950/50 dark:text-amber-400' }} font-semibold text-[11px]">
                  {{ $user->isSuspended() ? 'Activate' : 'Suspend' }}
                </button>
              @endcan
              @can('delete', $user)
                <button wire:click="deleteUser({{ $user->id }})"
                        wire:confirm="Delete {{ $user->email }}? This cannot be undone."
                        class="px-2.5 py-1 rounded-lg bg-rose-50 text-rose-700 hover:bg-rose-100 dark:bg-rose-950/50 dark:text-rose-400 font-semibold text-[11px]">
                  Delete
                </button>
              @endcan
            </td>
          </tr>
          @empty
          <tr><td colspan="6" class="py-10 text-center text-slate-400 text-xs">No users match your search.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="p-4 border-t border-slate-100 dark:border-dark-800">
      {{ $users->links() }}
    </div>
  </div>

  {{-- Invite modal --}}
  @if ($showInvite)
  <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" wire:click="$set('showInvite', false)"></div>
    <div class="relative max-w-md w-full bg-white dark:bg-dark-900 rounded-3xl shadow-xl border border-slate-200/80 dark:border-dark-800 p-8 space-y-5">
      <div>
        <h2 class="text-lg font-extrabold text-slate-900 dark:text-white">Invite a team member</h2>
        <p class="text-xs text-slate-500 dark:text-dark-400 mt-1">They'll receive an email link to set their password and join your workspace.</p>
      </div>
      <form wire:submit="invite" class="space-y-4 text-xs">
        <div>
          <label class="block font-bold mb-1">Full Name</label>
          <input type="text" wire:model="inviteName" placeholder="Jordan Reeves"
                 class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 dark:bg-dark-800 border border-slate-200 dark:border-dark-700 focus:outline-none focus:ring-2 focus:ring-brand-500">
          @error('inviteName') <span class="text-rose-500 font-semibold mt-1 block">{{ $message }}</span> @enderror
        </div>
        <div>
          <label class="block font-bold mb-1">Work Email</label>
          <input type="email" wire:model="inviteEmail" placeholder="jordan@company.com"
                 class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 dark:bg-dark-800 border border-slate-200 dark:border-dark-700 focus:outline-none focus:ring-2 focus:ring-brand-500">
          @error('inviteEmail') <span class="text-rose-500 font-semibold mt-1 block">{{ $message }}</span> @enderror
        </div>
        <div>
          <label class="block font-bold mb-1">Role</label>
          <select wire:model="inviteRole" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 dark:bg-dark-800 border border-slate-200 dark:border-dark-700 focus:outline-none">
            <option value="admin">Admin</option>
            <option value="editor">Editor</option>
            <option value="viewer">Viewer</option>
          </select>
        </div>
        <div class="flex items-center justify-end gap-2 pt-2">
          <button type="button" wire:click="$set('showInvite', false)" class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-500 hover:bg-slate-100 dark:hover:bg-dark-800">Cancel</button>
          <button type="submit" class="px-4 py-2 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-semibold shadow-md shadow-brand-500/20">
            <span wire:loading.remove wire:target="invite">Send Invitation</span>
            <span wire:loading wire:target="invite">Sending…</span>
          </button>
        </div>
      </form>
    </div>
  </div>
  @endif
</div>
