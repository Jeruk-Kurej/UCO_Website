<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Seed the users table with 3 specific users.
     */
    public function run(): void
    {
        $this->command->info('🔄 Seeding Users...');

        // Admin User
        User::updateOrCreate(
            ['email' => 'admin@uco.com'],
            [
                'username' => 'admin',
                'name' => 'Admin UCO',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        // Student User
        User::updateOrCreate(
            ['email' => 'student@uco.com'],
            [
                'username' => 'student',
                'name' => 'Student UCO',
                'password' => Hash::make('password'),
                'role' => 'student',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        // Alumni User
        User::updateOrCreate(
            ['email' => 'alumni@uco.com'],
            [
                'username' => 'alumni',
                'name' => 'Alumni UCO',
                'password' => Hash::make('password'),
                'role' => 'alumni',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('✅ Users seeded successfully!');
    }
}
