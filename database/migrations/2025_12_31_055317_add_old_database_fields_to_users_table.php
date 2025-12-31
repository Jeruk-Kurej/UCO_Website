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
        // Split into multiple schema alterations to avoid MySQL row size limit
        
        // Part 1: Personal & Contact Info
        if (!Schema::hasColumn('users', 'birth_date')) {
            Schema::table('users', function (Blueprint $table) {
                $table->date('birth_date')->nullable();
                $table->string('birth_city', 100)->nullable();
                $table->string('religion', 50)->nullable();
                $table->text('address')->nullable();
                $table->string('address_city', 100)->nullable();
                $table->string('province', 100)->nullable();
                $table->string('country', 100)->nullable();
                $table->string('zip_code', 20)->nullable();
                $table->text('address2')->nullable();
                $table->string('address_city2', 100)->nullable();
                $table->string('province2', 100)->nullable();
                $table->string('country2', 100)->nullable();
                $table->string('zip_code2', 20)->nullable();
                $table->string('phone_number', 50)->nullable();
                $table->string('phone_number2', 50)->nullable();
                $table->string('mobile_number', 50)->nullable();
                $table->string('mobile_number2', 50)->nullable();
                $table->string('line', 100)->nullable();
                $table->string('whatsapp', 50)->nullable();
                $table->string('facebook', 150)->nullable();
                $table->string('twitter', 150)->nullable();
                $table->string('instagram', 150)->nullable();
            });
        }
        
        // Part 2: Academic Info
        if (!Schema::hasColumn('users', 'NIS')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('NIS', 50)->nullable();
                $table->string('Student_Year', 50)->nullable();
                $table->string('Edu_Level', 100)->nullable();
                $table->string('Academic_Advisor', 150)->nullable();
                $table->string('Previous_School_Name', 200)->nullable();
                $table->text('Activity')->nullable();
                $table->string('Position', 150)->nullable();
                $table->string('School_City', 100)->nullable();
                $table->string('Major', 150)->nullable();
                $table->string('Start_Year', 10)->nullable();
                $table->string('End_Year', 10)->nullable();
                $table->boolean('Is_Graduate')->default(false);
                $table->decimal('Score', 5, 2)->nullable();
                $table->string('Certificate_No_1', 100)->nullable();
                $table->date('Certificate_Date_1')->nullable();
                $table->string('Certificate_No_2', 100)->nullable();
                $table->date('Certificate_Date_2')->nullable();
            });
        }
        
        // Part 3: Father Info
        if (!Schema::hasColumn('users', 'Father_Name')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('Father_Name', 150)->nullable();
                $table->string('Father_Birth_City', 100)->nullable();
                $table->date('Father_Birthday')->nullable();
                $table->string('Father_Citizenship', 100)->nullable();
                $table->string('Father_Citizenship_No', 100)->nullable();
                $table->string('Father_Passport_No', 50)->nullable();
                $table->string('Father_NPWP_No', 50)->nullable();
                $table->string('Father_Religion', 50)->nullable();
                $table->string('Father_BPJS_No', 50)->nullable();
                $table->text('Father_Address')->nullable();
                $table->string('Father_Address_City', 100)->nullable();
                $table->string('Father_Phone', 50)->nullable();
                $table->string('Father_Mobile', 50)->nullable();
                $table->string('Father_Email', 150)->nullable();
                $table->string('Father_BBM', 50)->nullable();
            });
        }
        
        // Part 4: Father Business Info
        if (!Schema::hasColumn('users', 'Father_Education_Highest')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('Father_Education_Highest', 100)->nullable();
                $table->string('Father_Education_Major', 150)->nullable();
                $table->string('Father_Profession', 150)->nullable();
                $table->string('Father_Business_Name', 200)->nullable();
                $table->text('Father_Business_Address')->nullable();
                $table->string('Father_Business_Phone', 50)->nullable();
                $table->string('Father_Business_Line', 150)->nullable();
                $table->string('Father_Business_Title', 150)->nullable();
                $table->decimal('Father_Business_Revenue', 15, 2)->nullable();
                $table->text('Father_Special_Need')->nullable();
            });
        }
        
        // Part 5: Mother Info
        if (!Schema::hasColumn('users', 'Mother_Name')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('Mother_Name', 150)->nullable();
                $table->string('Mother_Birth_City', 100)->nullable();
                $table->date('Mother_Birthday')->nullable();
                $table->string('Mother_Citizenship', 100)->nullable();
                $table->string('Mother_Citizenship_No', 100)->nullable();
                $table->string('Mother_Passport_No', 50)->nullable();
                $table->string('Mother_NPWP_No', 50)->nullable();
                $table->string('Mother_Religion', 50)->nullable();
                $table->string('Mother_BPJS_No', 50)->nullable();
                $table->text('Mother_Address')->nullable();
                $table->string('Mother_Address_City', 100)->nullable();
                $table->string('Mother_Phone', 50)->nullable();
                $table->string('Mother_Mobile', 50)->nullable();
                $table->string('Mother_Email', 150)->nullable();
                $table->string('Mother_BBM', 50)->nullable();
            });
        }
        
        // Part 6: Mother Business Info
        if (!Schema::hasColumn('users', 'Mother_Education_Highest')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('Mother_Education_Highest', 100)->nullable();
                $table->string('Mother_Education_Major', 150)->nullable();
                $table->string('Mother_Profession', 150)->nullable();
                $table->string('Mother_Business_Name', 200)->nullable();
                $table->text('Mother_Business_Address')->nullable();
                $table->string('Mother_Business_Phone', 50)->nullable();
                $table->string('Mother_Business_Line', 150)->nullable();
                $table->string('Mother_Business_Title', 150)->nullable();
                $table->decimal('Mother_Business_Revenue', 15, 2)->nullable();
                $table->text('Mother_Special_Need')->nullable();
            });
        }
        
        // Part 7: Graduation & Status Info
        if (!Schema::hasColumn('users', 'Final_Project_Indonesia')) {
            Schema::table('users', function (Blueprint $table) {
                $table->text('Final_Project_Indonesia')->nullable();
                $table->text('Final_Project_English')->nullable();
                $table->decimal('CGPA', 4, 2)->nullable();
                $table->integer('Cum_Credits')->nullable();
                $table->string('Predicate', 100)->nullable();
                $table->date('Judicium_Date')->nullable();
                $table->string('Document_No', 100)->nullable();
                $table->date('Document_Date')->nullable();
                $table->string('Graduate_Period', 50)->nullable();
                $table->string('Class_Semester', 50)->nullable();
                $table->string('Form_No', 100)->nullable();
                $table->string('Official_Email', 150)->nullable();
                $table->string('Current_Status', 100)->nullable();
                $table->date('Start_Date')->nullable();
                $table->date('End_Date')->nullable();
                $table->string('Business_Name', 200)->nullable();
                $table->string('Business_Line', 150)->nullable();
                $table->string('Business_Title', 150)->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $columns = [
                // Personal
                'birth_date', 'birth_city', 'religion',
                // Primary Address
                'address', 'address_city', 'province', 'country', 'zip_code',
                // Secondary Address
                'address2', 'address_city2', 'province2', 'country2', 'zip_code2',
                // Contact
                'phone_number', 'phone_number2', 'mobile_number', 'mobile_number2',
                // Social Media
                'line', 'whatsapp', 'facebook', 'twitter', 'instagram',
                // Student Info
                'NIS', 'Student_Year', 'Edu_Level', 'Academic_Advisor', 'Previous_School_Name',
                'Activity', 'Position',
                // Academic Details
                'School_City', 'Major', 'Start_Year', 'End_Year', 'Is_Graduate', 'Score',
                'Certificate_No_1', 'Certificate_Date_1', 'Certificate_No_2', 'Certificate_Date_2',
                // Father Info
                'Father_Name', 'Father_Birth_City', 'Father_Birthday', 'Father_Citizenship',
                'Father_Citizenship_No', 'Father_Passport_No', 'Father_NPWP_No', 'Father_Religion',
                'Father_BPJS_No', 'Father_Address', 'Father_Address_City', 'Father_Phone',
                'Father_Mobile', 'Father_Email', 'Father_BBM', 'Father_Education_Highest',
                'Father_Education_Major', 'Father_Profession', 'Father_Business_Name',
                'Father_Business_Address', 'Father_Business_Phone', 'Father_Business_Line',
                'Father_Business_Title', 'Father_Business_Revenue', 'Father_Special_Need',
                // Mother Info
                'Mother_Name', 'Mother_Birth_City', 'Mother_Birthday', 'Mother_Citizenship',
                'Mother_Citizenship_No', 'Mother_Passport_No', 'Mother_NPWP_No', 'Mother_Religion',
                'Mother_BPJS_No', 'Mother_Address', 'Mother_Address_City', 'Mother_Phone',
                'Mother_Mobile', 'Mother_Email', 'Mother_BBM', 'Mother_Education_Highest',
                'Mother_Education_Major', 'Mother_Profession', 'Mother_Business_Name',
                'Mother_Business_Address', 'Mother_Business_Phone', 'Mother_Business_Line',
                'Mother_Business_Title', 'Mother_Business_Revenue', 'Mother_Special_Need',
                // Final Project & Graduation
                'Final_Project_Indonesia', 'Final_Project_English', 'CGPA', 'Cum_Credits',
                'Predicate', 'Judicium_Date', 'Document_No', 'Document_Date', 'Graduate_Period',
                // Current Status
                'Class_Semester', 'Form_No', 'Official_Email', 'Current_Status',
                'Start_Date', 'End_Date',
                // Business
                'Business_Name', 'Business_Line', 'Business_Title',
            ];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
