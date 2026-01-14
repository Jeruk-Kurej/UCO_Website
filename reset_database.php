<?php

/**
 * Script to reset database - keep only 1 admin user
 * Run with: php artisan tinker < reset_database.php
 */

use App\Models\User;
use App\Models\Business;
use App\Models\Product;
use App\Models\Service;
use Illuminate\Support\Facades\DB;

echo "🔥 Starting database reset...\n";

// Delete all businesses (this will cascade delete products, services, etc.)
echo "Deleting all businesses...\n";
Business::query()->delete();

// Delete all users except admin
echo "Deleting all users except admin...\n";
$adminEmail = 'admin@uco.com';
User::where('email', '!=', $adminEmail)->delete();

// Check if admin exists, if not create one
$admin = User::where('email', $adminEmail)->first();
if (!$admin) {
    echo "Admin not found, creating new admin...\n";
    $admin = User::create([
        'username' => 'admin',
        'name' => 'Admin UCO',
        'email' => 'admin@uco.com',
        'password' => Hash::make('password'),
        'role' => 'admin',
        'is_active' => true,
        'email_verified_at' => now(),
    ]);
}

echo "✅ Database reset complete!\n";
echo "Admin credentials:\n";
echo "  Email: {$admin->email}\n";
echo "  Password: password\n";
