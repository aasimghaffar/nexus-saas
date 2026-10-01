<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoUsersSeeder extends Seeder
{
    /**
     * Demo teammates. Emails use the reserved example.com domain
     * (safe placeholder — never a real inbox).
     */
    public function run(): void
    {
        $demo = [
            ['Sarah Kline',  'sarah@example.com',  'admin'],
            ['Marcus Vance', 'marcus@example.com', 'editor'],
            ['Elena Ortiz',  'elena@example.com',  'editor'],
            ['Liam Doyle',   'liam@example.com',   'viewer'],
            ['Dana Chen',    'dana@example.com',   'viewer'],
        ];

        foreach ($demo as $i => [$name, $email, $role]) {
            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'name'              => $name,
                    'company_name'      => 'Demo Workspace',
                    'password'          => Hash::make('password'),
                    'email_verified_at' => now(),
                    'last_seen_at'      => now()->subHours($i + 1),
                ]
            );
            $user->syncRoles($role);
        }
    }
}
