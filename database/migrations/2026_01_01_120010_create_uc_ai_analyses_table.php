<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('uc_ai_analyses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('uc_testimony_id')->constrained('uc_testimonies')->onDelete('cascade');
            $table->decimal('sentiment_score', 8, 2);
            $table->text('rejection_reason')->nullable();
            $table->boolean('is_approved');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('uc_ai_analyses');
    }
};
