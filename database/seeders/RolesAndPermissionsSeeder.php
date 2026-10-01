<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $permissions = [
            'users.view', 'users.manage',
            'activity.view',
            'billing.manage',
            'projects.manage',
            'tickets.manage',
            'settings.manage',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Role => permissions. Super Admin bypasses checks via Gate::before.
        $matrix = [
            'super-admin' => [],
            'admin'       => ['users.view', 'users.manage', 'activity.view', 'billing.manage', 'projects.manage', 'tickets.manage', 'settings.manage'],
            'editor'      => ['users.view', 'projects.manage', 'tickets.manage'],
            'viewer'      => [],
        ];

        foreach ($matrix as $role => $perms) {
            Role::firstOrCreate(['name' => $role])->syncPermissions($perms);
        }
    }
}
