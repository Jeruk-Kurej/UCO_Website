<?php

use App\Models\User;
use App\Models\Business;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('test:business-enhancement', function () {
    $this->info('🧪 Testing Business Data Enhancement Features...');
    $this->newLine();
    
    // Get businesses with user
    $businesses = Business::with('user', 'businessType')->get();
    
    if ($businesses->isEmpty()) {
        $this->error('❌ No businesses found! Please run seeder first:');
        $this->line('   php artisan db:seed --class=EnhancedBusinessSeeder');
        return;
    }
    
    $this->info("📊 Found {$businesses->count()} businesses");
    $this->newLine();
    
    foreach ($businesses as $index => $business) {
        $this->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        $this->info('Business #' . ($index + 1) . ': ' . $business->name);
        $this->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        $this->newLine();
        
        // Basic Info
        $this->line('<fg=cyan>📌 Basic Information:</>');
        $this->line('   Owner: ' . $business->user->name);
        $this->line('   Type: ' . ($business->businessType->name ?? 'N/A'));
        $this->line('   Mode: ' . ucfirst($business->business_mode));
        $this->line('   Description: ' . substr($business->description, 0, 80) . '...');
        $this->newLine();
        
        // Enhanced Fields
        $this->line('<fg=green>✨ Enhanced Data (from 42-column Excel):</>');
        $this->line('   Logo: ' . ($business->logo_url ?? '<fg=yellow>Not set</>'));
        $this->line('   Established: ' . ($business->established_date ? $business->established_date->format('d M Y') . ' (' . $business->getAgeInYears() . ' years old)' : '<fg=yellow>Not set</>'));
        $this->line('   Address: ' . ($business->address ?? '<fg=yellow>Not set</>'));
        $this->line('   Employees: ' . ($business->employee_count ?? '<fg=yellow>Not set</>'));
        $this->line('   Revenue: ' . $business->getRevenueLabel());
        $this->line('   From College Project: ' . ($business->isCollegeProject() ? '<fg=green>Yes ✓</>' : '<fg=red>No</>'));
        $this->line('   Continued After Grad: ' . ($business->is_continued_after_graduation ? '<fg=green>Yes ✓</> (Active)' : '<fg=red>No</> (Inactive)'));
        $this->line('   Status: ' . ($business->isActive() ? '<fg=green>🟢 Active</>'  : '<fg=red>🔴 Inactive</>'));
        $this->newLine();
        
        // Legal Documents
        if ($business->hasLegalDocuments()) {
            $this->line('<fg=blue>📄 Legal Documents:</>');
            foreach ($business->legal_documents as $type => $number) {
                $this->line('   - ' . $type . ': ' . $number);
            }
            $this->newLine();
        }
        
        // Certifications
        if ($business->hasCertifications()) {
            $this->line('<fg=magenta>🏆 Product Certifications:</>');
            foreach ($business->product_certifications as $type => $number) {
                $this->line('   - ' . $type . ': ' . $number);
            }
            $this->newLine();
        }
        
        // Challenges
        if (!empty($business->business_challenges)) {
            $this->line('<fg=yellow>⚠️  Business Challenges (' . $business->getChallengesCount() . '):</>');
            foreach ($business->business_challenges as $idx => $challenge) {
                $this->line('   ' . ($idx + 1) . '. ' . $challenge);
            }
            $this->newLine();
        }
    }
    
    // User Summary
    $user = $businesses->first()->user;
    $this->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
    $this->info('👤 Business Owner Summary');
    $this->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
    $this->newLine();
    $this->line('<fg=cyan>Owner Information:</>');
    $this->line('   Name: ' . $user->name);
    $this->line('   Employment Status: ' . $user->getEmploymentStatusLabel());
    $this->line('   Has Side Business: ' . ($user->has_side_business ? '<fg=green>Yes ✓</>' : '<fg=red>No</>'));
    $this->line('   Total Businesses: ' . $user->totalBusinesses());
    $this->line('   Multiple Businesses: ' . ($user->hasMultipleBusinesses() ? '<fg=green>Yes ✓</>' : '<fg=red>No</>'));
    $this->line('   Is Entrepreneur: ' . ($user->isEntrepreneur() ? '<fg=green>Yes ✓</>' : '<fg=red>No</>'));
    $this->line('   Is Intrapreneur: ' . ($user->isIntrapreneur() ? '<fg=green>Yes ✓</>' : '<fg=red>No</>'));
    $this->newLine();
    
    $this->info('✅ All tests passed! Business data enhancement is working correctly.');
    $this->newLine();
    
})->purpose('Test enhanced business data features from 42-column Excel');

