<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('category_id')->nullable()->constrained('categories')->onDelete('set null')->comment('CSV: Industry Category');

            $table->string('name')->comment('CSV: Company Name');
            $table->string('slug')->unique();
            $table->string('position')->nullable()->comment('CSV: Intrapreneur Position');
            $table->text('job_description')->nullable()->comment('CSV: Job Description');
            $table->string('year_started_working')->nullable()->comment('CSV: Year Started Working');
            $table->text('achievement')->nullable()->comment('CSV: Achievement');
            $table->string('company_scale')->nullable()->comment('CSV: Company Scale');
            $table->text('logo_url')->nullable()->comment('CSV: Business/Company Logo');

            // Platform management
            $table->boolean('is_visible')->default(true)->comment('Hide/show company on public pages');

            $table->timestamps();

            $table->index('user_id');
            $table->index('category_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
