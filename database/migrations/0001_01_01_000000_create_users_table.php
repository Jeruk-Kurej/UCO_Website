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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique();
            $table->string('name');
            $table->enum('gender', ['Male', 'Female', 'Other'])->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->text('profile_photo_url')->nullable();
            $table->enum('role', ['student', 'alumni', 'admin'])->default('student');
            $table->boolean('is_active')->default(true)->comment('True = Active Student, False = Alumni');
            
            // Personal & Address
            $table->date('birth_date')->nullable();
            $table->string('birth_city', 100)->nullable();
            $table->string('religion', 50)->nullable();
            $table->text('address')->nullable();
            $table->string('address_city', 100)->nullable();
            $table->string('province', 100)->nullable();
            $table->string('country', 100)->nullable();
            $table->string('zip_code', 20)->nullable();
            
            // Secondary Address
            $table->text('address2')->nullable();
            $table->string('address_city2', 100)->nullable();
            $table->string('province2', 100)->nullable();
            $table->string('country2', 100)->nullable();
            $table->string('zip_code2', 20)->nullable();
            
            // Contact
            $table->string('phone_number', 50)->nullable();
            $table->string('phone_number2', 50)->nullable();
            $table->string('mobile_number', 50)->nullable();
            $table->string('mobile_number2', 50)->nullable();
            
            // Social Media
            $table->string('line', 100)->nullable();
            $table->string('whatsapp', 50)->nullable();
            $table->string('facebook', 200)->nullable();
            $table->string('twitter', 200)->nullable();
            $table->string('instagram', 200)->nullable();
            
            // Student Info
            $table->string('NIS', 50)->nullable();
            $table->string('Student_Year', 10)->nullable();
            $table->string('Edu_Level', 50)->nullable();
            $table->string('Academic_Advisor', 150)->nullable();
            $table->string('Previous_School_Name', 200)->nullable();
            $table->string('Activity', 200)->nullable();
            $table->string('Position', 150)->nullable();
            
            // Academic Details
            $table->string('School_City', 100)->nullable();
            $table->string('Major', 150)->nullable();
            $table->string('Start_Year', 10)->nullable();
            $table->string('End_Year', 10)->nullable();
            $table->boolean('Is_Graduate')->default(false);
            $table->decimal('Score', 6, 2)->nullable();
            $table->string('Certificate_No_1', 100)->nullable();
            $table->date('Certificate_Date_1')->nullable();
            $table->string('Certificate_No_2', 100)->nullable();
            $table->date('Certificate_Date_2')->nullable();
            
            // Father Info
            $table->string('Father_Name', 200)->nullable();
            $table->string('Father_Birth_City', 100)->nullable();
            $table->date('Father_Birthday')->nullable();
            $table->string('Father_Citizenship', 100)->nullable();
            $table->string('Father_Citizenship_No', 100)->nullable();
            $table->string('Father_Passport_No', 100)->nullable();
            $table->string('Father_NPWP_No', 100)->nullable();
            $table->string('Father_Religion', 50)->nullable();
            $table->string('Father_BPJS_No', 100)->nullable();
            $table->text('Father_Address')->nullable();
            $table->string('Father_Address_City', 100)->nullable();
            $table->string('Father_Phone', 50)->nullable();
            $table->string('Father_Mobile', 50)->nullable();
            $table->string('Father_Email', 150)->nullable();
            $table->string('Father_BBM', 50)->nullable();
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
            
            // Mother Info
            $table->string('Mother_Name', 200)->nullable();
            $table->string('Mother_Birth_City', 100)->nullable();
            $table->date('Mother_Birthday')->nullable();
            $table->string('Mother_Citizenship', 100)->nullable();
            $table->string('Mother_Citizenship_No', 100)->nullable();
            $table->string('Mother_Passport_No', 100)->nullable();
            $table->string('Mother_NPWP_No', 100)->nullable();
            $table->string('Mother_Religion', 50)->nullable();
            $table->string('Mother_BPJS_No', 100)->nullable();
            $table->text('Mother_Address')->nullable();
            $table->string('Mother_Address_City', 100)->nullable();
            $table->string('Mother_Phone', 50)->nullable();
            $table->string('Mother_Mobile', 50)->nullable();
            $table->string('Mother_Email', 150)->nullable();
            $table->string('Mother_BBM', 50)->nullable();
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
            
            // Graduation & Status
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
            
            // Add JSON fields to handle MySQL row size limits if needed for future
            $table->json('personal_data')->nullable();
            $table->json('academic_data')->nullable();
            $table->json('father_data')->nullable();
            $table->json('mother_data')->nullable();
            $table->json('graduation_data')->nullable();
            $table->json('additional_data')->nullable()->comment('Extended user data');
            
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
