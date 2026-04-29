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
                'name' => 'Admin UCO',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'is_visible' => true,
                'email_verified_at' => now(),
            ]
        );

        // Student User
        User::updateOrCreate(
            ['email' => 'student@uco.com'],
            [
                'name' => 'Student UCO',
                'password' => Hash::make('password'),
                'role' => 'user',
                'student_status' => 'active',
                'is_visible' => true,
                'email_verified_at' => now(),
            ]
        );

        // Alumni User
        User::updateOrCreate(
            ['email' => 'alumni@uco.com'],
            [
                'name' => 'Alumni UCO',
                'password' => Hash::make('password'),
                'role' => 'user',
                'student_status' => 'alumni',
                'is_visible' => true,
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('✅ Users seeded successfully!');
    }
}
