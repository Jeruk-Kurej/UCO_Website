<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('🚀 Seeding Admin Account...');

        // Create Default Admin
        User::updateOrCreate(
            ['email' => 'admin@uco.com'],
            [
                'name' => 'UCO Administrator',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('✅ Admin seeded: admin@uco.com / password');
    }
}
