<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('businesses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('category_id')->nullable()->constrained('categories')->onDelete('set null')->comment('CSV: Category');

            // CSV: Core info
            $table->string('name')->comment('CSV: Business Name');
            $table->string('slug')->unique();
            $table->string('position')->nullable()->comment('CSV: Entrepreneur Position');
            $table->date('established_date')->nullable()->comment('CSV: Established Date');
            $table->text('description')->nullable()->comment('CSV: Description');

            // CSV: Location
            $table->string('province')->nullable()->comment('CSV: Province');
            $table->string('city')->nullable()->comment('CSV: City / Regency');
            $table->text('address')->nullable()->comment('CSV: Full Address');

            // CSV: Contacts
            $table->string('phone_number')->nullable()->comment('CSV: Business Phone Number');
            $table->string('whatsapp')->nullable()->comment('CSV: Business WhatsApp');
            $table->string('email')->nullable()->comment('CSV: Business Email Address');
            $table->string('website')->nullable()->comment('CSV: Website');
            $table->string('instagram')->nullable()->comment('CSV: Instagram');

            // CSV: Operations
            $table->string('operational_status')->nullable()->comment('CSV: Operational Status');
            $table->string('offering_type')->nullable()->comment('CSV: Offering Type');

            // CSV: Market & Performance
            $table->text('unique_value_proposition')->nullable()->comment('CSV: Unique Value Proposition');
            $table->string('target_market')->nullable()->comment('CSV: Target Market');
            $table->string('customer_base_size')->nullable()->comment('CSV: Customer Base Size');
            $table->string('employee_count')->nullable()->comment('CSV: Employee Count');
            $table->string('revenue_range')->nullable()->comment('CSV: Revenue Range (per year)');

            // CSV: Heritage & Docs
            $table->string('academic_heritage')->nullable()->comment('CSV: Academic Heritage');
            $table->text('company_profile_url')->nullable()->comment('CSV: Company Profile');
            $table->text('logo_url')->nullable()->comment('CSV: Business/Company Logo');

            // CSV: Scale & Legality
            $table->string('business_scale')->nullable()->comment('CSV: Business Scale');
            $table->string('business_legality')->nullable()->comment('CSV: Business Legality');
            $table->string('product_legality')->nullable()->comment('CSV: Product Legality');

            // Platform management
            $table->boolean('is_visible')->default(true)->comment('Hide/show business on public pages');
            $table->enum('type', ['entrepreneur', 'intrapreneur'])->default('entrepreneur')->comment('Business type for directory separation');

            $table->timestamps();

            $table->index('user_id');
            $table->index('category_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('businesses');
    }
};
