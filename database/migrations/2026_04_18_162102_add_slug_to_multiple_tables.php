<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $tables = [
            'businesses' => 'name',
            'business_types' => 'name',
            'product_categories' => 'name',
            'products' => 'name',
            'services' => 'name',
            'contact_types' => 'platform_name'
        ];

        foreach ($tables as $tableName => $sourceColumn) {
            if (!Schema::hasColumn($tableName, 'slug')) {
                Schema::table($tableName, function (Blueprint $table) use ($sourceColumn) {
                    $table->string('slug')->nullable()->after($sourceColumn)->index();
                });
            }

            // Populate existing records with slugs
            $records = DB::table($tableName)->get();
            foreach ($records as $record) {
                if (empty($record->slug)) {
                    $slug = Str::slug($record->{$sourceColumn});
                    
                    // Check for uniqueness
                    $originalSlug = $slug;
                    $counter = 1;
                    while (DB::table($tableName)->where('slug', $slug)->where('id', '!=', $record->id)->exists()) {
                        $slug = $originalSlug . '-' . $counter;
                        $counter++;
                    }

                    DB::table($tableName)->where('id', $record->id)->update(['slug' => $slug]);
                }
            }

            // Once populated, try to make it unique and non-nullable
            // Use try-catch or check if index already exists to prevent errors on retry
            try {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->string('slug')->unique()->nullable(false)->change();
                });
            } catch (\Exception $e) {
                // If the unique constraint fails (e.g., already exists), we catch it
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = [
            'businesses',
            'business_types',
            'product_categories',
            'products',
            'services',
            'contact_types'
        ];

        foreach ($tables as $tableName) {
            if (Schema::hasColumn($tableName, 'slug')) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->dropColumn('slug');
                });
            }
        }
    }
};
