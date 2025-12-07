<?php

namespace Database\Seeders;

use App\Models\BusinessType;
use App\Models\ProductCategory;
use Illuminate\Database\Seeder;

class ProductCategorySeeder extends Seeder
{
    /**
     * Seed the product_categories table.
     * Categories are linked to specific BusinessTypes.
     */
    public function run(): void
    {
        $this->command->info('🔄 Seeding Product Categories...');

        // ✅ Query BusinessType IDs dynamically
        $fnb = BusinessType::where('name', 'Food & Beverage')->first();
        $tech = BusinessType::where('name', 'Technology')->first();
        $retail = BusinessType::where('name', 'Retail')->first();
        $services = BusinessType::where('name', 'Professional Services')->first();
        $creative = BusinessType::where('name', 'Creative & Design')->first();

        // Check if all business types exist
        if (!$fnb || !$tech || !$retail || !$services || !$creative) {
            $this->command->error('❌ Business Types not found! Please run BusinessTypeSeeder first.');
            return;
        }

        // Food & Beverage Categories
        $categories = [
            // FnB Categories
            ['business_type_id' => $fnb->id, 'name' => 'Heavy Meals'],
            ['business_type_id' => $fnb->id, 'name' => 'Light Snacks'],
            ['business_type_id' => $fnb->id, 'name' => 'Beverages'],
            ['business_type_id' => $fnb->id, 'name' => 'Desserts'],

            // Technology Categories
            ['business_type_id' => $tech->id, 'name' => 'Software Products'],
            ['business_type_id' => $tech->id, 'name' => 'Mobile Apps'],
            ['business_type_id' => $tech->id, 'name' => 'Web Applications'],
            ['business_type_id' => $tech->id, 'name' => 'SaaS Solutions'],

            // Retail Categories
            ['business_type_id' => $retail->id, 'name' => 'Clothing'],
            ['business_type_id' => $retail->id, 'name' => 'Electronics'],
            ['business_type_id' => $retail->id, 'name' => 'Home & Living'],
            ['business_type_id' => $retail->id, 'name' => 'Beauty & Health'],

            // Professional Services Categories
            ['business_type_id' => $services->id, 'name' => 'Consulting Packages'],
            ['business_type_id' => $services->id, 'name' => 'Training Programs'],
            ['business_type_id' => $services->id, 'name' => 'Legal Services'],

            // Creative & Design Categories
            ['business_type_id' => $creative->id, 'name' => 'Design Packages'],
            ['business_type_id' => $creative->id, 'name' => 'Photography Services'],
            ['business_type_id' => $creative->id, 'name' => 'Video Production'],
        ];

        foreach ($categories as $category) {
            ProductCategory::create($category);
        }

        $this->command->info('✅ Product Categories seeded successfully!');
    }
}
