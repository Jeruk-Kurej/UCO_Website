<?php

namespace Database\Seeders;

use App\Models\ContactType;
use Illuminate\Database\Seeder;

class ContactTypeSeeder extends Seeder
{
    /**
     * Seed the contact_types table.
     */
    public function run(): void
    {
        $this->command->info('🔄 Seeding Contact Types...');

        $contactTypes = [
            ['platform_name' => 'WhatsApp', 'icon_class' => 'bi-whatsapp'],
            ['platform_name' => 'Instagram', 'icon_class' => 'bi-instagram'],
            ['platform_name' => 'Facebook', 'icon_class' => 'bi-facebook'],
            ['platform_name' => 'Email', 'icon_class' => 'bi-envelope'],
            ['platform_name' => 'Phone', 'icon_class' => 'bi-telephone'],
            ['platform_name' => 'Website', 'icon_class' => 'bi-globe'],
            ['platform_name' => 'Twitter/X', 'icon_class' => 'bi-twitter-x'],
            ['platform_name' => 'LinkedIn', 'icon_class' => 'bi-linkedin'],
            ['platform_name' => 'TikTok', 'icon_class' => 'bi-tiktok'],
            ['platform_name' => 'YouTube', 'icon_class' => 'bi-youtube'],
        ];

        foreach ($contactTypes as $type) {
            ContactType::create($type);
        }

        $this->command->info('✅ Contact Types seeded successfully!');
    }
}
