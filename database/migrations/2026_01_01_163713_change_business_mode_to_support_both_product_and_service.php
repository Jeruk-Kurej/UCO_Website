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
            // Change business_mode enum to support product, service, or both
            $table->enum('business_mode', ['product', 'service', 'both'])->default('product')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('businesses', function (Blueprint $table) {
            // Revert back to only product or service
            $table->enum('business_mode', ['product', 'service'])->default('product')->change();
        });
    }
};
