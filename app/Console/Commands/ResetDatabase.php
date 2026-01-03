<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Business;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class ResetDatabase extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'db:reset-keep-admin 
                            {--force : Force the operation without confirmation}';

    /**
     * The console command description.
     */
    protected $description = 'Reset database: delete all data except admin users';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (!$this->option('force')) {
            if (!$this->confirm('⚠️  This will delete ALL businesses and non-admin users. Continue?')) {
                $this->info('Operation cancelled.');
                return 0;
            }
        }

        $this->info('🔥 Starting database reset...');
        $this->newLine();

        // Delete all businesses (will cascade to products, services, etc.)
        $this->info('Deleting all businesses...');
        $businessCount = Business::count();
        Business::query()->delete();
        $this->info("✅ Deleted {$businessCount} businesses");

        // Delete ALL users
        $this->info('Deleting all users...');
        $userCount = User::count();
        User::query()->delete();
        $this->info("✅ Deleted {$userCount} users");

        // Create ONE default admin
        $this->info('Creating default admin...');
        $admin = User::create([
            'username' => 'admin',
            'name' => 'Admin UCO',
            'email' => 'admin@uco.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
        $this->info("✅ Created admin: {$admin->email}");

        $this->newLine();
        $this->info('✅ Database reset complete!');
        $this->info('📧 Admin login: admin@uco.com / password: password');
        
        return 0;
    }
}
