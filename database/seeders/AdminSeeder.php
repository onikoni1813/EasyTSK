<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@test.com'],
            [
                'name' => 'Demo Admin',
                'full_name' => 'System Administrator',
                'password' => Hash::make('password123'),
                'is_admin' => true,
                'is_moderator' => true,
                'email_verified_at' => now(),
            ]
        );
    }
}
