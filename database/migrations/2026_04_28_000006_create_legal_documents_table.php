<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('legal_documents', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });

        Schema::create('business_legal_document', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained('businesses')->onDelete('cascade');
            $table->foreignId('legal_document_id')->constrained('legal_documents')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['business_id', 'legal_document_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('business_legal_document');
        Schema::dropIfExists('legal_documents');
    }
};
