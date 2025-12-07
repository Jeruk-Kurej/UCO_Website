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
        User::create([
            'username' => 'admin',
            'name' => 'Admin UCO',
            'email' => 'admin@uco.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // Student User
        User::create([
            'username' => 'student',
            'name' => 'Student UCO',
            'email' => 'student@uco.com',
            'password' => Hash::make('password'),
            'role' => 'student',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // Alumni User
        User::create([
            'username' => 'alumni',
            'name' => 'Alumni UCO',
            'email' => 'alumni@uco.com',
            'password' => Hash::make('password'),
            'role' => 'alumni',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        $this->command->info('✅ Users seeded successfully!');
    }
}
