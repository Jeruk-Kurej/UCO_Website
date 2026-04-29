<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained('businesses')->onDelete('cascade');

            $table->string('name')->comment('CSV: Product/Service Name');
            $table->text('description')->nullable()->comment('CSV: Product/Service Description');
            $table->string('price')->nullable()->comment('CSV: Product/Service Price');
            $table->text('photo_url')->nullable()->comment('CSV: Product/Service Photo');
            $table->unsignedTinyInteger('sort_order')->default(0)->comment('1, 2, or 3 from CSV');

            $table->timestamps();

            $table->index('business_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
