<?php

namespace Database\Seeders;

use App\Models\BusinessType;
use Illuminate\Database\Seeder;

class BusinessTypeSeeder extends Seeder
{
    /**
     * Seed the business_types table.
     */
    public function run(): void
    {
        $this->command->info('🔄 Seeding Business Types...');

        $businessTypes = [
            [
                'name' => 'Food & Beverage',
                'description' => 'Restaurants, cafes, food stalls, and catering services',
            ],
            [
                'name' => 'Technology',
                'description' => 'Software development, IT services, and tech consulting',
            ],
            [
                'name' => 'Retail',
                'description' => 'Online and offline retail stores',
            ],
            [
                'name' => 'Professional Services',
                'description' => 'Consulting, legal, accounting, and business services',
            ],
            [
                'name' => 'Creative & Design',
                'description' => 'Graphic design, photography, video production',
            ],
        ];

        foreach ($businessTypes as $type) {
            BusinessType::create($type);
        }

        $this->command->info('✅ Business Types seeded successfully!');
    }
}
