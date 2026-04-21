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
        Schema::create('image_mappings', function (Blueprint $table) {
            $table->id();
            $table->string('url_hash', 40)->index(); // MD5 is 32, SHA1 is 40. We'll use SHA1 for fewer collisions.
            $table->text('source_url');
            $table->text('stored_path');
            $table->string('disk')->default('cloudinary');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('image_mappings');
    }
};
