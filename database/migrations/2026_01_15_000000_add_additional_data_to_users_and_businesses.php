<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'additional_data')) {
                $table->json('additional_data')->nullable()->after('graduation_data');
            }
        });

        Schema::table('businesses', function (Blueprint $table) {
            if (!Schema::hasColumn('businesses', 'additional_data')) {
                $table->json('additional_data')->nullable()->after('business_challenges');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'additional_data')) {
                $table->dropColumn('additional_data');
            }
        });

        Schema::table('businesses', function (Blueprint $table) {
            if (Schema::hasColumn('businesses', 'additional_data')) {
                $table->dropColumn('additional_data');
            }
        });
    }
};
