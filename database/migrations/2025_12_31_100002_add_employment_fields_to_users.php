<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Current Employment Status
            if (!Schema::hasColumn('users', 'current_employment_status')) {
                $table->enum('current_employment_status', [
                    'employed_intrapreneur',  // Bekerja sebagai profesional
                    'entrepreneur',           // Memiliki bisnis sendiri
                    'job_seeking',           // Mencari pekerjaan
                    'preparing_business'     // Persiapan entrepreneur
                ])->nullable()->after('role');
            }
            
            // Side Business Flag
            if (!Schema::hasColumn('users', 'has_side_business')) {
                $table->boolean('has_side_business')->default(false)->after('current_employment_status');
            }
            
            // Profile Photo
            if (!Schema::hasColumn('users', 'profile_photo_url')) {
                $table->string('profile_photo_url')->nullable()->after('email');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $columns = [
                'current_employment_status',
                'has_side_business',
                'profile_photo_url',
            ];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
