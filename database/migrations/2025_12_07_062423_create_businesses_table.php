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
        Schema::create('businesses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('business_type_id')->nullable()->constrained('business_types')->onDelete('cascade');
            
            $table->string('name');
            $table->string('position', 150)->nullable();
            $table->enum('business_mode', ['product', 'service', 'both'])->default('product');
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false)->index();
            
            $table->text('description')->nullable();
            $table->string('legal_document_path')->nullable();
            $table->string('certification_path')->nullable();
            
            $table->string('logo_url')->nullable();
            $table->date('established_date')->nullable();
            $table->text('address')->nullable();
            $table->string('city', 100)->nullable();
            $table->string('province', 100)->nullable();
            
            $table->integer('employee_count')->nullable();
            $table->enum('revenue_range', [
                '< 10jt',
                '10jt - 50jt',
                '50jt - 100jt',
                '> 100jt'
            ])->nullable();
            
            $table->boolean('is_from_college_project')->default(false);
            $table->boolean('is_continued_after_graduation')->default(true);
            
            // JSON Fields
            $table->json('legal_documents')->nullable()->comment('SIUP, NIB, TDP, dll');
            $table->json('product_certifications')->nullable()->comment('BPOM, Halal, SNI, dll');
            $table->json('business_challenges')->nullable()->comment('Array of challenges');
            $table->json('additional_data')->nullable()->comment('Extended business data');

            $table->string('status')->default('pending')->index();
            $table->text('rejection_reason')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('businesses');
    }
};
