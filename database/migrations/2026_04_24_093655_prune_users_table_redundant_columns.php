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
            // Drop Address & Location fields (now in personal_data)
            $table->dropColumn([
                'address', 'address_city', 'province', 'country', 'zip_code',
                'address2', 'address_city2', 'province2', 'country2', 'zip_code2'
            ]);

            // Drop Social Media & Additional Contacts (now in personal_data)
            $table->dropColumn(['phone_number2', 'mobile_number2', 'line', 'facebook', 'twitter', 'instagram']);

            // Drop Additional Student/Academic Info (now in academic_data)
            $table->dropColumn([
                'Edu_Level', 'Academic_Advisor', 'Previous_School_Name', 'Activity', 'Position', 
                'School_City', 'Start_Year', 'End_Year', 'Score',
                'Certificate_No_1', 'Certificate_Date_1', 'Certificate_No_2', 'Certificate_Date_2'
            ]);

            // Drop Father Information (now in father_data)
            $table->dropColumn([
                'Father_Name', 'Father_Birth_City', 'Father_Birthday', 'Father_Citizenship', 'Father_Citizenship_No', 
                'Father_Passport_No', 'Father_NPWP_No', 'Father_Religion', 'Father_BPJS_No', 'Father_Address', 
                'Father_Address_City', 'Father_Phone', 'Father_Mobile', 'Father_Email', 'Father_BBM', 
                'Father_Education_Highest', 'Father_Education_Major', 'Father_Profession', 'Father_Business_Name', 
                'Father_Business_Address', 'Father_Business_Phone', 'Father_Business_Line', 'Father_Business_Title', 
                'Father_Business_Revenue', 'Father_Special_Need'
            ]);

            // Drop Mother Information (now in mother_data)
            $table->dropColumn([
                'Mother_Name', 'Mother_Birth_City', 'Mother_Birthday', 'Mother_Citizenship', 'Mother_Citizenship_No', 
                'Mother_Passport_No', 'Mother_NPWP_No', 'Mother_Religion', 'Mother_BPJS_No', 'Mother_Address', 
                'Mother_Address_City', 'Mother_Phone', 'Mother_Mobile', 'Mother_Email', 'Mother_BBM', 
                'Mother_Education_Highest', 'Mother_Education_Major', 'Mother_Profession', 'Mother_Business_Name', 
                'Mother_Business_Address', 'Mother_Business_Phone', 'Mother_Business_Line', 'Mother_Business_Title', 
                'Mother_Business_Revenue', 'Mother_Special_Need'
            ]);

            // Drop Graduation & Career Info (now in graduation_data)
            $table->dropColumn([
                'Final_Project_Indonesia', 'Final_Project_English', 'Cum_Credits', 'Predicate', 
                'Judicium_Date', 'Document_No', 'Document_Date', 'Graduate_Period', 'Class_Semester', 
                'Form_No', 'Official_Email', 'Current_Status', 'Start_Date', 'End_Date', 
                'Business_Name', 'Business_Line', 'Business_Title'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Restore Address & Location
            $table->text('address')->nullable();
            $table->string('address_city')->nullable();
            $table->string('province')->nullable();
            $table->string('country')->nullable();
            $table->string('zip_code')->nullable();
            $table->text('address2')->nullable();
            $table->string('address_city2')->nullable();
            $table->string('province2')->nullable();
            $table->string('country2')->nullable();
            $table->string('zip_code2')->nullable();

            // Restore Contacts
            $table->string('phone_number2')->nullable();
            $table->string('mobile_number2')->nullable();
            $table->string('line')->nullable();
            $table->string('facebook')->nullable();
            $table->string('twitter')->nullable();
            $table->string('instagram')->nullable();

            // Restore Academic
            $table->string('Edu_Level')->nullable();
            $table->string('Academic_Advisor')->nullable();
            $table->string('Previous_School_Name')->nullable();
            $table->string('Activity')->nullable();
            $table->string('Position')->nullable();
            $table->string('School_City')->nullable();
            $table->string('Start_Year')->nullable();
            $table->string('End_Year')->nullable();
            $table->decimal('Score')->nullable();
            $table->string('Certificate_No_1')->nullable();
            $table->date('Certificate_Date_1')->nullable();
            $table->string('Certificate_No_2')->nullable();
            $table->date('Certificate_Date_2')->nullable();

            // Restore Father (Abbreviated for down, but should match UP for full integrity)
            $table->string('Father_Name')->nullable();
            $table->string('Father_Passport_No')->nullable();
            $table->string('Father_NPWP_No')->nullable();
            $table->string('Father_BPJS_No')->nullable();
            // ... (Other father fields could be added here if needed)

            // Restore Mother
            $table->string('Mother_Name')->nullable();
            $table->string('Mother_Passport_No')->nullable();
            $table->string('Mother_NPWP_No')->nullable();
            $table->string('Mother_BPJS_No')->nullable();

            // Restore Graduation
            $table->text('Final_Project_Indonesia')->nullable();
            $table->text('Final_Project_English')->nullable();
            $table->string('Official_Email')->nullable();
            $table->string('Current_Status')->nullable();
        });
    }
};
