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
        Schema::dropIfExists('ai_analyses');
        Schema::dropIfExists('testimonies');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // We are not recreating the tables as this is a cleanup migration.
    }
};
