<?php

namespace Database\Seeders;

use App\Models\Business;
use App\Models\BusinessPhoto;
use App\Models\BusinessType;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductPhoto;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class DummyBusinessSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🏢 Creating dummy businesses...');

        // Check if users exist, if not create them
        $this->command->info('👥 Checking users...');
        $existingUsers = User::whereIn('username', ['user1', 'user2', 'user3', 'user4', 'user5', 'user6', 'user7', 'user8', 'user9'])->get();
        
        if ($existingUsers->count() >= 9) {
            $this->command->line('  ✓ Using existing users');
            $users = $existingUsers->take(9)->values()->all();
        } else {
            $this->command->info('  Creating missing users...');
            $users = [];
            $roles = ['student', 'alumni'];
            
            for ($i = 1; $i <= 9; $i++) {
                $existing = User::where('username', "user{$i}")->first();
                if ($existing) {
                    $users[] = $existing;
                } else {
                    $users[] = User::create([
                        'username' => "user{$i}",
                        'name' => "User $i",
                        'email' => "user{$i}@uco.com",
                        'password' => bcrypt('password'),
                        'role' => $i === 1 ? 'admin' : ($i % 2 === 0 ? 'alumni' : 'student'),
                        'is_active' => true,
                    ]);
                }
            }
        }

        // Ensure we have business types
        $businessTypes = BusinessType::all();
        if ($businessTypes->isEmpty()) {
            $this->command->error('❌ No business types found. Please run BusinessTypeSeeder first.');
            return;
        }

        // Get a default product category
        $defaultCategory = ProductCategory::first();
        if (!$defaultCategory) {
            $this->command->error('❌ No product categories found. Please run ProductCategorySeeder first.');
            return;
        }

        // Business data templates
        $businessesData = [
            [
                'name' => 'Digital Solutions Pro',
                'type' => 'Technology',
                'description' => 'Perusahaan jasa pembuatan website, aplikasi mobile, dan digital marketing untuk UMKM. Membantu bisnis lokal go digital dengan solusi terjangkau.',
                'address' => 'Jl. Teknologi No. 123, Jakarta Selatan',
                'phone' => '081234567890',
                'products' => [
                    ['name' => 'Website Company Profile', 'price' => 5000000, 'description' => 'Website profesional untuk perusahaan'],
                    ['name' => 'Mobile App Android', 'price' => 15000000, 'description' => 'Aplikasi mobile untuk Android'],
                    ['name' => 'Digital Marketing Package', 'price' => 3000000, 'description' => 'Paket marketing sosial media'],
                ],
                'services' => ['Web Development', 'UI/UX Design', 'SEO Optimization', 'Digital Marketing'],
            ],
            [
                'name' => 'Warung Nasi Ibu Siti',
                'type' => 'Food & Beverage',
                'description' => 'Warung makan legendaris dengan menu nasi campur khas Jawa. Sudah berdiri sejak 1995 dan terkenal dengan sambal bajak nya yang pedas mantap!',
                'address' => 'Jl. Pahlawan No. 45, Surabaya',
                'phone' => '082345678901',
                'products' => [
                    ['name' => 'Nasi Campur Special', 'price' => 25000, 'description' => 'Nasi dengan lauk lengkap'],
                    ['name' => 'Ayam Goreng Kremes', 'price' => 20000, 'description' => 'Ayam goreng dengan kremesan'],
                    ['name' => 'Soto Ayam', 'price' => 18000, 'description' => 'Soto ayam kuah kuning'],
                    ['name' => 'Es Teh Manis', 'price' => 5000, 'description' => 'Minuman es teh segar'],
                ],
                'services' => ['Dine In', 'Take Away', 'Catering', 'Delivery'],
            ],
            [
                'name' => 'Klinik Sehat Bersama',
                'type' => 'Healthcare',
                'description' => 'Klinik kesehatan umum dengan dokter berpengalaman. Melayani pemeriksaan umum, gigi, dan laboratorium dengan harga terjangkau.',
                'address' => 'Jl. Kesehatan Raya No. 78, Bandung',
                'phone' => '083456789012',
                'products' => [
                    ['name' => 'Medical Check Up Basic', 'price' => 350000, 'description' => 'Pemeriksaan kesehatan dasar'],
                    ['name' => 'Dental Care Package', 'price' => 500000, 'description' => 'Perawatan gigi lengkap'],
                ],
                'services' => ['General Practice', 'Dental Care', 'Laboratory', 'Vaccination'],
            ],
            [
                'name' => 'Fashion House Elegan',
                'type' => 'Retail',
                'description' => 'Butik fashion modern dengan koleksi pakaian casual dan formal. Menyediakan baju pria, wanita, dan anak-anak dengan kualitas premium.',
                'address' => 'Jl. Mode No. 99, Yogyakarta',
                'phone' => '084567890123',
                'products' => [
                    ['name' => 'Dress Elegan', 'price' => 450000, 'description' => 'Dress untuk acara formal'],
                    ['name' => 'Kemeja Premium', 'price' => 350000, 'description' => 'Kemeja pria berkualitas'],
                    ['name' => 'Celana Jeans', 'price' => 275000, 'description' => 'Jeans casual'],
                    ['name' => 'Blazer Formal', 'price' => 650000, 'description' => 'Blazer untuk kantor'],
                ],
                'services' => ['Custom Tailoring', 'Personal Stylist', 'Alteration'],
            ],
            [
                'name' => 'AutoCare Workshop',
                'type' => 'Automotive',
                'description' => 'Bengkel mobil dan motor profesional. Spesialis servis berkala, ganti oli, tune up, dan perbaikan mesin dengan mekanik bersertifikat.',
                'address' => 'Jl. Otomotif No. 234, Semarang',
                'phone' => '085678901234',
                'products' => [
                    ['name' => 'Oli Mesin Synthetic', 'price' => 250000, 'description' => 'Oli mesin berkualitas tinggi'],
                    ['name' => 'Ban Michelin', 'price' => 1200000, 'description' => 'Ban mobil premium'],
                    ['name' => 'Aki GS Astra', 'price' => 850000, 'description' => 'Aki mobil original'],
                ],
                'services' => ['Oil Change', 'Tune Up', 'Brake Service', 'Engine Repair', 'Detailing'],
            ],
            [
                'name' => 'EduCenter Bimbel',
                'type' => 'Education',
                'description' => 'Lembaga bimbingan belajar untuk SD, SMP, dan SMA. Menggunakan metode pembelajaran interaktif dengan tutor berpengalaman.',
                'address' => 'Jl. Pendidikan No. 567, Malang',
                'phone' => '086789012345',
                'products' => [
                    ['name' => 'Paket SD (1 Semester)', 'price' => 1500000, 'description' => 'Bimbel untuk siswa SD'],
                    ['name' => 'Paket SMP (1 Semester)', 'price' => 2000000, 'description' => 'Bimbel untuk siswa SMP'],
                    ['name' => 'Paket SMA UTBK', 'price' => 3500000, 'description' => 'Persiapan UTBK'],
                ],
                'services' => ['Private Tutoring', 'Group Classes', 'Online Learning', 'Test Preparation'],
            ],
            [
                'name' => 'Rumah Kopi Nusantara',
                'type' => 'Food & Beverage',
                'description' => 'Coffee shop dengan suasana cozy dan instagramable. Menyajikan kopi specialty dari berbagai daerah di Indonesia dan menu makanan ringan.',
                'address' => 'Jl. Kopi Kenangan No. 88, Bali',
                'phone' => '087890123456',
                'products' => [
                    ['name' => 'Kopi Gayo Aceh', 'price' => 25000, 'description' => 'Kopi dari Aceh'],
                    ['name' => 'Cappuccino', 'price' => 30000, 'description' => 'Cappuccino klasik'],
                    ['name' => 'Croissant', 'price' => 20000, 'description' => 'Pastry Prancis'],
                    ['name' => 'Cheesecake', 'price' => 35000, 'description' => 'Kue keju lembut'],
                ],
                'services' => ['Dine In', 'Take Away', 'Free WiFi', 'Meeting Space'],
            ],
            [
                'name' => 'FitZone Gym & Fitness',
                'type' => 'Sports & Fitness',
                'description' => 'Pusat kebugaran modern dengan alat fitness terlengkap. Dilengkapi personal trainer, kelas zumba, yoga, dan sauna.',
                'address' => 'Jl. Sehat Bugar No. 321, Medan',
                'phone' => '088901234567',
                'products' => [
                    ['name' => 'Monthly Membership', 'price' => 350000, 'description' => 'Member 1 bulan'],
                    ['name' => 'Annual Membership', 'price' => 3000000, 'description' => 'Member 1 tahun (hemat!)'],
                    ['name' => 'Personal Training (10 Sessions)', 'price' => 1500000, 'description' => 'PT 10 sesi'],
                ],
                'services' => ['Gym Access', 'Personal Training', 'Group Classes', 'Sauna', 'Nutrition Consultation'],
            ],
            [
                'name' => 'Toko Bunga Cantik',
                'type' => 'Retail',
                'description' => 'Florist profesional untuk segala kebutuhan bunga. Menyediakan bunga potong, hand bouquet, standing flower, dan dekorasi acara.',
                'address' => 'Jl. Anggrek No. 12, Surakarta',
                'phone' => '089012345678',
                'products' => [
                    ['name' => 'Hand Bouquet Rose', 'price' => 250000, 'description' => 'Buket mawar segar'],
                    ['name' => 'Standing Flower', 'price' => 750000, 'description' => 'Bunga papan untuk acara'],
                    ['name' => 'Flower Box', 'price' => 350000, 'description' => 'Bunga dalam box cantik'],
                    ['name' => 'Wedding Decoration', 'price' => 5000000, 'description' => 'Dekorasi pernikahan'],
                ],
                'services' => ['Same Day Delivery', 'Custom Arrangement', 'Event Decoration', 'Subscription Service'],
            ],
            [
                'name' => 'Creative Print Studio',
                'type' => 'Technology',
                'description' => 'Jasa percetakan digital dan offset dengan kualitas terbaik. Melayani cetak banner, brosur, kartu nama, undangan, dan merchandise.',
                'address' => 'Jl. Printing No. 456, Depok',
                'phone' => '081122334455',
                'products' => [
                    ['name' => 'Business Card (1 Box)', 'price' => 150000, 'description' => 'Kartu nama profesional'],
                    ['name' => 'Banner 3x2 meter', 'price' => 200000, 'description' => 'Banner untuk promosi'],
                    ['name' => 'Flyer A5 (1000 pcs)', 'price' => 500000, 'description' => 'Flyer promosi'],
                    ['name' => 'Custom T-Shirt', 'price' => 75000, 'description' => 'Kaos sablon custom'],
                ],
                'services' => ['Digital Printing', 'Offset Printing', 'Custom Design', 'Fast Delivery'],
            ],
        ];

        // Create businesses with products and services
        $this->command->info('🏪 Creating 10 businesses with products and services...');
        
        foreach ($businessesData as $index => $data) {
            // User 1 gets 2 businesses (index 0 and 9), others get 1 each
            $userIndex = $index === 9 ? 0 : $index;
            $user = $users[$userIndex];

            // Find business type
            $businessType = $businessTypes->where('name', $data['type'])->first();
            if (!$businessType) {
                $businessType = $businessTypes->first();
            }

            // Create business
            $business = Business::create([
                'user_id' => $user->id,
                'business_type_id' => $businessType->id,
                'name' => $data['name'],
                'description' => $data['description'],
                'address' => $data['address'],
                'business_mode' => 'both', // Support both products and services
                'is_featured' => true, // All dummy businesses are featured
            ]);

            // Create products
            if (isset($data['products'])) {
                foreach ($data['products'] as $productData) {
                    Product::create([
                        'business_id' => $business->id,
                        'product_category_id' => $defaultCategory->id,
                        'name' => $productData['name'],
                        'description' => $productData['description'],
                        'price' => $productData['price'],
                    ]);
                }
            }

            // Create services
            if (isset($data['services'])) {
                foreach ($data['services'] as $serviceName) {
                    Service::create([
                        'business_id' => $business->id,
                        'name' => $serviceName,
                        'description' => "Professional $serviceName service",
                        'price' => 0,
                        'price_type' => 'contact', // Default to contact for pricing
                    ]);
                }
            }

            $this->command->line("  ✓ Created: {$data['name']} (Owner: {$user->name})");
        }

        $this->command->newLine();
        $this->command->info('✅ Successfully created:');
        $this->command->line('   - 9 users');
        $this->command->line('   - 10 businesses (User 1 has 2 businesses)');
        $this->command->line('   - ' . Product::count() . ' products');
        $this->command->line('   - ' . Service::count() . ' services');
        $this->command->newLine();
        $this->command->warn('📝 Note: Photos are not included in this seeder.');
        $this->command->line('   You can upload photos manually through the application.');
    }
}
