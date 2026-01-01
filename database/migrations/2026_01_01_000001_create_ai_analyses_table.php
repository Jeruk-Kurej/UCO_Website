<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('ai_analyses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('testimony_id')->index();
            $table->decimal('sentiment_score', 5, 2)->default(0);
            $table->text('rejection_reason')->nullable();
            $table->boolean('is_approved')->default(false);
            $table->timestamps();

            $table->foreign('testimony_id')
                ->references('id')
                ->on('testimonies')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('ai_analyses');
    }
};
