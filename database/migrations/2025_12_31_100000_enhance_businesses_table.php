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
        Schema::table('businesses', function (Blueprint $table) {
            // Logo
            if (!Schema::hasColumn('businesses', 'logo_url')) {
                $table->string('logo_url')->nullable()->after('description');
            }
            
            // Operational Details
            if (!Schema::hasColumn('businesses', 'established_date')) {
                $table->date('established_date')->nullable();
            }
            
            if (!Schema::hasColumn('businesses', 'address')) {
                $table->text('address')->nullable();
            }
            
            if (!Schema::hasColumn('businesses', 'employee_count')) {
                $table->integer('employee_count')->nullable();
            }
            
            // Revenue Range
            if (!Schema::hasColumn('businesses', 'revenue_range')) {
                $table->enum('revenue_range', [
                    'Mikro: <= Rp 300 Juta',
                    'Kecil: > Rp 300 Juta - Rp 2,5 Milyar',
                    'Menengah: > Rp 2,5 Milyar - Rp 50 Milyar',
                    'Besar: > Rp 50 Milyar'
                ])->nullable();
            }
            
            // Origin & Status
            if (!Schema::hasColumn('businesses', 'is_from_college_project')) {
                $table->boolean('is_from_college_project')->default(false);
            }
            
            if (!Schema::hasColumn('businesses', 'is_continued_after_graduation')) {
                $table->boolean('is_continued_after_graduation')->default(true);
            }
            
            // JSON Fields for Legal & Compliance
            if (!Schema::hasColumn('businesses', 'legal_documents')) {
                $table->json('legal_documents')->nullable()->comment('SIUP, NIB, TDP, dll');
            }
            
            if (!Schema::hasColumn('businesses', 'product_certifications')) {
                $table->json('product_certifications')->nullable()->comment('BPOM, Halal, SNI, dll');
            }
            
            if (!Schema::hasColumn('businesses', 'business_challenges')) {
                $table->json('business_challenges')->nullable()->comment('Array of challenges');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('businesses', function (Blueprint $table) {
            $columns = [
                'logo_url',
                'established_date',
                'address',
                'employee_count',
                'revenue_range',
                'is_from_college_project',
                'is_continued_after_graduation',
                'legal_documents',
                'product_certifications',
                'business_challenges',
            ];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('businesses', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
