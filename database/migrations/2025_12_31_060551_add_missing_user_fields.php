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
            // Add gender (from your data example)
            if (!Schema::hasColumn('users', 'gender')) {
                $table->enum('gender', ['Male', 'Female', 'Other'])->nullable()->after('name');
            }
            
            // Add social media (Line already added, adding others)
            if (!Schema::hasColumn('users', 'line')) {
                $table->string('line', 100)->nullable();
            }
            if (!Schema::hasColumn('users', 'facebook')) {
                $table->string('facebook', 150)->nullable();
            }
            if (!Schema::hasColumn('users', 'twitter')) {
                $table->string('twitter', 150)->nullable();
            }
            if (!Schema::hasColumn('users', 'instagram')) {
                $table->string('instagram', 150)->nullable();
            }
            
            // Add current status fields (from your data example)
            if (!Schema::hasColumn('users', 'Official_Email')) {
                $table->string('Official_Email', 150)->nullable()->comment('UC student email');
                $table->string('Current_Status', 100)->nullable()->comment('Aktif/Non-Aktif/Lulus');
                $table->string('Class_Semester', 50)->nullable()->comment('e.g., Semester 7');
                $table->string('Form_No', 100)->nullable()->comment('Student form number');
                $table->date('Start_Date')->nullable()->comment('Start date at UC');
                $table->date('End_Date')->nullable()->comment('End/graduation date');
            }
            
            // Add education level and previous school info
            if (!Schema::hasColumn('users', 'Edu_Level')) {
                $table->string('Edu_Level', 50)->nullable()->comment('S1, S2, etc.');
                $table->string('Previous_School_Name', 200)->nullable();
                $table->string('School_City', 100)->nullable();
                $table->string('Previous_Edu_Level', 50)->nullable()->comment('SMK, SMA, etc.');
                $table->string('Start_Year', 10)->nullable()->comment('Previous school start year');
                $table->string('End_Year', 10)->nullable()->comment('Previous school end year');
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
                'gender', 'line', 'facebook', 'twitter', 'instagram',
                'Official_Email', 'Current_Status', 'Class_Semester', 'Form_No', 
                'Start_Date', 'End_Date', 'Edu_Level', 'Previous_School_Name',
                'School_City', 'Previous_Edu_Level', 'Start_Year', 'End_Year'
            ];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
