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
        // Add essential fields to users table + JSON columns for extended data
        Schema::table('users', function (Blueprint $table) {
            // Core personal info
            if (!Schema::hasColumn('users', 'birth_date')) {
                $table->date('birth_date')->nullable();
                $table->string('birth_city', 100)->nullable();
                $table->string('religion', 50)->nullable();
            }
            
            // Contact
            if (!Schema::hasColumn('users', 'phone_number')) {
                $table->string('phone_number', 50)->nullable();
                $table->string('mobile_number', 50)->nullable();
                $table->string('whatsapp', 50)->nullable();
            }
            
            // Student info
            if (!Schema::hasColumn('users', 'NIS')) {
                $table->string('NIS', 50)->nullable();
                $table->string('Student_Year', 50)->nullable();
                $table->string('Major', 150)->nullable();
                $table->boolean('Is_Graduate')->default(false);
                $table->decimal('CGPA', 4, 2)->nullable();
            }
            
            // Store all other data as JSON to avoid MySQL row size limit
            if (!Schema::hasColumn('users', 'personal_data')) {
                $table->json('personal_data')->nullable()->comment('Address, secondary contact, social media');
                $table->json('academic_data')->nullable()->comment('Academic advisor, certificates, final project');
                $table->json('father_data')->nullable()->comment('Father complete information');
                $table->json('mother_data')->nullable()->comment('Mother complete information');
                $table->json('graduation_data')->nullable()->comment('Graduation and current status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'birth_date', 'birth_city', 'religion',
                'phone_number', 'mobile_number', 'whatsapp',
                'NIS', 'Student_Year', 'Major', 'Is_Graduate', 'CGPA',
                'personal_data', 'academic_data', 'father_data', 'mother_data', 'graduation_data',
            ]);
        });
    }
};
