<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * 
     * This seeder orchestrates all other seeders in the correct order.
     */
    public function run(): void
    {
        $this->command->info('🚀 Starting database seeding...');
        $this->command->newLine();

        // Call seeders in dependency order
        $this->call([
            IndoRegionSeeder::class,        // 1. Seed geographical data (Provinces, Regencies, etc.)
            UserSeeder::class,              // 2. Create users
            BusinessTypeSeeder::class,      // 3. Create business types
            ContactTypeSeeder::class,       // 4. Create contact types
            DummyBusinessSeeder::class,     // 5. Create dummy businesses
        ]);

        $this->command->newLine();
        $this->command->info('✅ Database seeded successfully!');
        $this->command->newLine();
        
        // Display login credentials
        $this->displayLoginCredentials();
    }

    /**
     * Display login credentials for testing.
     */
    private function displayLoginCredentials(): void
    {
        $this->command->line('┌─────────────────────────────────────────────┐');
        $this->command->line('│         🔐 LOGIN CREDENTIALS                │');
        $this->command->line('├─────────────────────────────────────────────┤');
        $this->command->line('│ 👨‍💼 Admin:                                 │');
        $this->command->line('│    Email: admin@uco.com                     │');
        $this->command->line('│    Password: password                       │');
        $this->command->line('├─────────────────────────────────────────────┤');
        $this->command->line('│ 🎓 Student:                                 │');
        $this->command->line('│    Email: student@uco.com                   │');
        $this->command->line('│    Password: password                       │');
        $this->command->line('├─────────────────────────────────────────────┤');
        $this->command->line('│ 🎓 Alumni:                                  │');
        $this->command->line('│    Email: alumni@uco.com                    │');
        $this->command->line('│    Password: password                       │');
        $this->command->line('└─────────────────────────────────────────────┘');
        $this->command->newLine();
    }
}
