<?php

namespace Database\Seeders;

use App\Models\Business;
use App\Models\BusinessType;
use App\Models\User;
use Illuminate\Database\Seeder;

class EnhancedBusinessSeeder extends Seeder
{
    /**
     * Seed enhanced business data for testing
     * Based on 42-column Excel requirements
     */
    public function run(): void
    {
        $this->command->info('🔄 Seeding Enhanced Business Data...');

        // Get or create a user
        $user = User::first();
        if (!$user) {
            $this->command->error('❌ No users found! Please seed users first.');
            return;
        }

        // Update user with employment status
        $user->update([
            'current_employment_status' => 'entrepreneur',
            'has_side_business' => false,
        ]);

        // Ensure we have business types
        $foodType = BusinessType::firstOrCreate(['name' => 'Food & Beverage']);
        $techType = BusinessType::firstOrCreate(['name' => 'Technology']);
        $fashionType = BusinessType::firstOrCreate(['name' => 'Fashion & Retail']);

        // Sample Business 1: From College Project, Continued After Graduation
        $business1 = Business::create([
            'user_id' => $user->id,
            'business_type_id' => $foodType->id,
            'business_mode' => 'product',
            'name' => 'Warung Kopi Mahasiswa',
            'description' => 'Coffee shop yang dimulai dari project entrepreneurship kampus. Sekarang sudah berkembang dengan 2 cabang dan melayani ratusan pelanggan setiap hari.',
            
            // Enhanced fields from 42-column Excel
            'logo_url' => 'businesses/logos/warung-kopi.png',
            'established_date' => now()->subYears(2)->subMonths(3),
            'address' => 'Jl. Kampus Raya No. 15, Kelurahan Kemanggisan, Jakarta Barat 11480',
            'employee_count' => 8,
            'revenue_range' => 'Kecil: > Rp 300 Juta - Rp 2,5 Milyar',
            'is_from_college_project' => true,
            'is_continued_after_graduation' => true,
            'legal_documents' => [
                'SIUP' => '12345/SIUP/2023',
                'NIB' => '9876543210123456',
                'TDP' => 'TDP-001-2023-JKT',
                'NPWP' => '12.345.678.9-012.000'
            ],
            'product_certifications' => [
                'Halal' => 'MUI-HALAL-12345-2023',
                'PIRT' => 'PIRT-2023-001-JKT',
                'BPOM' => 'MD-123456789'
            ],
            'business_challenges' => [
                'Pemasaran digital masih terbatas, perlu optimize social media',
                'Persaingan dengan coffee shop franchise besar',
                'Manajemen stok bahan baku yang efisien',
                'Mencari supplier kopi berkualitas dengan harga terjangkau'
            ],
        ]);

        $this->command->info("✅ Created: {$business1->name}");

        // Sample Business 2: Not from College Project, Professional Business
        $business2 = Business::create([
            'user_id' => $user->id,
            'business_type_id' => $techType->id,
            'business_mode' => 'service',
            'name' => 'Digital Solutions Pro',
            'description' => 'Perusahaan jasa pembuatan website, aplikasi mobile, dan digital marketing untuk UMKM. Membantu bisnis lokal go digital dengan solusi terjangkau.',
            
            // Enhanced fields
            'logo_url' => 'businesses/logos/digital-solutions.png',
            'established_date' => now()->subYears(5),
            'address' => 'Rukan Permata Hijau Blok A15-16, Jakarta Selatan 12210',
            'employee_count' => 15,
            'revenue_range' => 'Menengah: > Rp 2,5 Milyar - Rp 50 Milyar',
            'is_from_college_project' => false,
            'is_continued_after_graduation' => true,
            'legal_documents' => [
                'SIUP' => '54321/SIUP/2019',
                'NIB' => '1234567890987654',
                'TDP' => 'TDP-002-2019-JKT',
                'NPWP' => '98.765.432.1-043.000',
                'Akta Pendirian' => 'No. 123/2019'
            ],
            'product_certifications' => [],
            'business_challenges' => [
                'Menemukan talent developer yang qualified dan berpengalaman',
                'Kompetisi dengan agency digital besar dengan budget marketing tinggi',
                'Client retention dan mendapatkan repeat order',
                'Perkembangan teknologi yang cepat, harus terus belajar'
            ],
        ]);

        $this->command->info("✅ Created: {$business2->name}");

        // Sample Business 3: Startup from College, Recently Graduated
        $business3 = Business::create([
            'user_id' => $user->id,
            'business_type_id' => $fashionType->id,
            'business_mode' => 'product',
            'name' => 'EcoFashion Store',
            'description' => 'Toko online fashion sustainable dari bahan ramah lingkungan. Menjual pakaian dan aksesori yang eco-friendly dengan desain modern dan harga terjangkau.',
            
            // Enhanced fields - Early stage business
            'logo_url' => 'businesses/logos/ecofashion.png',
            'established_date' => now()->subMonths(8),
            'address' => 'Virtual Office - Marketplace: Tokopedia, Shopee, Instagram',
            'employee_count' => 3,
            'revenue_range' => 'Mikro: <= Rp 300 Juta',
            'is_from_college_project' => true,
            'is_continued_after_graduation' => true,
            'legal_documents' => [
                'NIB' => '5555555555444444',
                'NPWP' => '11.222.333.4-055.000'
            ],
            'product_certifications' => [
                'Eco Label' => 'ECO-2024-001',
                'SNI Tekstil' => 'SNI-7617-2013'
            ],
            'business_challenges' => [
                'Modal terbatas untuk inventory dan produksi massal',
                'Brand awareness masih rendah di market',
                'Logistik dan pengiriman yang reliable',
                'Scaling production sambil maintain kualitas sustainable',
                'Edukasi customer tentang pentingnya sustainable fashion'
            ],
        ]);

        $this->command->info("✅ Created: {$business3->name}");

        // Sample Business 4: Service Business, No Longer Operating
        $business4 = Business::create([
            'user_id' => $user->id,
            'business_type_id' => $techType->id,
            'business_mode' => 'service',
            'name' => 'Bimbel Kampus',
            'description' => 'Bimbingan belajar untuk mahasiswa, dimulai dari project entrepreneurship. Sudah tidak beroperasi setelah lulus.',
            
            // Enhanced fields
            'established_date' => now()->subYears(3),
            'address' => 'Jl. Universitas No. 45, Jakarta Barat',
            'employee_count' => 5,
            'revenue_range' => 'Mikro: <= Rp 300 Juta',
            'is_from_college_project' => true,
            'is_continued_after_graduation' => false, // Stopped after graduation
            'legal_documents' => [],
            'product_certifications' => [],
            'business_challenges' => [
                'Waktu terbatas karena masih kuliah',
                'Kompetisi dengan bimbel established',
                'Sulit maintain consistency jadwal'
            ],
        ]);

        $this->command->info("✅ Created: {$business4->name}");

        $this->command->newLine();
        $this->command->info('✅ Enhanced business data seeded successfully!');
        $this->command->info('📊 Created 4 businesses with comprehensive data:');
        $this->command->line('   - 3 Active businesses (continued after graduation)');
        $this->command->line('   - 1 Inactive business (stopped after graduation)');
        $this->command->line('   - 3 From college projects');
        $this->command->line('   - Various revenue ranges (Mikro to Menengah)');
        $this->command->line('   - Complete legal documents & certifications');
        $this->command->line('   - Business challenges documented');
    }
}
