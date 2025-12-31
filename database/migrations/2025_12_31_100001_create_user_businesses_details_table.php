<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Create table for existing User_Businesses_Detail model
     */
    public function up(): void
    {
        if (!Schema::hasTable('user_businesses_details')) {
            Schema::create('user_businesses_details', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->foreignId('business_id')->constrained()->onDelete('cascade');
                
                // Role Type
                $table->enum('role_type', [
                    'owner',        // Pemilik/Founder
                    'co_founder',   // Co-Founder
                    'employee',     // Karyawan
                    'partner',      // Business Partner
                    'freelance'     // Freelancer/Consultant
                ])->default('owner');
                
                // Original field names (keep for compatibility)
                $table->string('Position_name')->nullable();
                $table->date('Working_Date')->nullable();
                $table->text('Company_Description')->nullable();
                
                // Income/Salary Range
                $table->enum('Income', [
                    '< Rp 5 Juta',
                    'Rp 5 Juta - Rp 10 Juta',
                    'Rp 10 Juta - Rp 15 Juta',
                    '> Rp 15 Juta'
                ])->nullable();
                
                // Enhanced Fields
                $table->date('end_date')->nullable();
                $table->boolean('is_current')->default(true);
                
                $table->timestamps();
                
                // Indexes for performance
                $table->index(['user_id', 'business_id']);
                $table->index(['business_id', 'role_type']);
                $table->index('is_current');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_businesses_details');
    }
};
