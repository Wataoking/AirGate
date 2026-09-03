<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Super administrateur',
                'email' => 'superadmin@example.com',
                'phone number' => '+2250700000001',
                'role' => 'super_admin',
            ],
            [
                'name' => 'Administrateur',
                'email' => 'admin@example.com',
                'phone number' => '+2250700000002',
                'role' => 'admin',
            ],
            [
                'name' => 'Utilisateur',
                'email' => 'user@example.com',
                'phone number' => '+2250700000003',
                'role' => 'user',
            ],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(
                ['email' => $user['email']],
                [
                    ...$user,
                    'password' => Hash::make('Mbole237'),
                    'email_verified_at' => now(),
                ],
            );
        }
    }
}
