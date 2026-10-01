<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name'              => 'Alex Morgan',
                'company_name'      => 'Nexus Platform Inc',
                'designation'       => 'VP of Product & Engineering',
                'password'          => Hash::make('password'),
                'email_verified_at' => now(),
                'last_seen_at'      => now(),
            ]
        );

        $user->syncRoles('super-admin');
    }
}
