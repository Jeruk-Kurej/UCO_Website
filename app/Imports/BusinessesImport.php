<?php

namespace App\Imports;

use App\Models\Business;
use App\Models\User;
use App\Models\BusinessType;
use App\Models\BusinessContact;
use App\Models\ContactType;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Facades\Log;

class BusinessesImport implements ToModel, WithHeadingRow, WithValidation
{
    protected $errors = [];
    protected $successCount = 0;
    protected $skippedCount = 0;

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        try {
            // Skip if no business name
            if (empty($row['nama']) && empty($row['name']) && empty($row['business_name'])) {
                $this->skippedCount++;
                $this->errors[] = "Row skipped: No business name found. Available columns: " . implode(', ', array_keys($row));
                Log::warning("Business row skipped - no name. Columns: " . implode(', ', array_keys($row)));
                return null;
            }

            // Get business name
            $businessName = $row['nama'] ?? $row['name'] ?? $row['business_name'] ?? null;
            
            // Check if business already exists
            $existingBusiness = Business::where('name', $businessName)->first();
            if ($existingBusiness) {
                $this->skippedCount++;
                Log::info("Business '{$businessName}' already exists, skipping...");
                return null;
            }

            // Find owner by name OR email
            // Priority: 1) Email exact match, 2) Name partial match
            $user = null;
            
            // Try email first (most reliable)
            if (!empty($row['email'])) {
                $user = User::where('email', $row['email'])->first();
                if (!$user) {
                    Log::warning("Business '{$businessName}': User with email '{$row['email']}' not found");
                }
            }
            
            // If not found by email, try by name
            if (!$user && !empty($row['nama'])) {
                $user = User::where('name', 'like', '%' . $row['nama'] . '%')->first();
                if (!$user) {
                    Log::warning("Business '{$businessName}': User with name like '{$row['nama']}' not found");
                }
            }
            
            // Also try 'owner' field if exists
            if (!$user && !empty($row['owner'])) {
                $user = User::where('name', 'like', '%' . $row['owner'] . '%')
                    ->orWhere('email', $row['owner'])
                    ->first();
                if (!$user) {
                    Log::warning("Business '{$businessName}': User with owner field '{$row['owner']}' not found");
                }
            }
            
            // If still no user found, log warning and skip
            if (!$user) {
                $errorMsg = "Business '{$businessName}': No owner found. Email: " . ($row['email'] ?? 'N/A') . ", Nama: " . ($row['nama'] ?? 'N/A');
                Log::warning($errorMsg);
                $this->errors[] = $errorMsg . " - Please ensure users are imported first and emails match.";
                $this->skippedCount++;
                return null;
            }

            // Get or create business type
            $businessTypeName = $row['business_type'] 
                ?? $row['business_line'] 
                ?? $row['kategori'] 
                ?? $row['jenis_bisnis']
                ?? 'Other';
            
            $businessType = BusinessType::firstOrCreate(
                ['name' => $businessTypeName],
                ['description' => 'Auto-created from import']
            );

            // Determine business mode
            $businessMode = 'product'; // Default
            if (isset($row['business_mode'])) {
                $businessMode = strtolower($row['business_mode']);
            }

            // Parse description from various fields
            $description = $this->buildDescription($row);

            // Parse challenges
            $challenges = $this->parseChallenges($row);

            // Create business
            $business = new Business([
                'user_id' => $user->id,
                'business_type_id' => $businessType->id,
                'name' => $businessName,
                'description' => $description ?: ($row['deskripsi'] ?? 'No description provided'),
                'business_mode' => $businessMode,
                
                // Additional fields with proper column mapping
                'address' => $row['address'] ?? $row['alamat'] ?? null,
                'established_date' => $this->parseDate($row['established_date'] ?? $row['tanggal_berdiri'] ?? null),
                'employee_count' => $this->parseEmployeeCount($row),
                'revenue_range' => $this->parseRevenueRange($row),
                'is_from_college_project' => $this->parseBoolean($row['from_college_project'] ?? $row['dari_kuliah'] ?? null),
                'is_continued_after_graduation' => $this->parseBoolean($row['continued_after_grad'] ?? $row['lanjut_setelah_lulus'] ?? null),
                'business_challenges' => $challenges,
            ]);

            $business->save();
            
            // Import contacts (phone, email, whatsapp, etc.)
            $this->importContacts($business, $row);
            
            $this->successCount++;
            Log::info("Business '{$businessName}' imported successfully for user '{$user->name}' with contacts");
            
            return $business;

        } catch (\Exception $e) {
            $this->errors[] = "Row error: {$e->getMessage()} - Data: " . json_encode($row);
            Log::error("Error importing business: " . $e->getMessage());
            $this->skippedCount++;
            return null;
        }
    }

    /**
     * Build description from multiple fields
     */
    private function buildDescription(array $row): ?string
    {
        $parts = [];
        
        // Add various description fields
        if (!empty($row['pt_smk_jiteram_114_2024_10_pt_74_jk'])) {
            $parts[] = $row['pt_smk_jiteram_114_2024_10_pt_74_jk'];
        }
        
        if (!empty($row['capital_for_entrepreneur'])) {
            $parts[] = "Capital: " . $row['capital_for_entrepreneur'];
        }
        
        if (!empty($row['netserviceinya_others_pen_rumot_tunggo'])) {
            $parts[] = $row['netserviceinya_others_pen_rumot_tunggo'];
        }

        return !empty($parts) ? implode("\n\n", $parts) : null;
    }

    /**
     * Parse challenges from row
     */
    private function parseChallenges(array $row): ?array
    {
        $challenges = [];
        
        // Look for challenge-related fields
        $challengeFields = [
            'main_focus_entrepreneur',
            'capital_for_entrepreneur',
            'netserviceinya_others_tidak_bekerja',
            'passion_for_entrepreneur',
        ];

        foreach ($challengeFields as $field) {
            if (!empty($row[$field])) {
                $challenges[] = $row[$field];
            }
        }

        return !empty($challenges) ? $challenges : null;
    }

    /**
     * Import contacts for the business with auto-create ContactType
     */
    private function importContacts(Business $business, array $row): void
    {
        // Define contact mappings: Excel column => [ContactType platform_name, icon_class]
        $contactMappings = [
            // Phone contacts
            'phone' => ['Phone', 'fas fa-phone'],
            'mobile' => ['Mobile', 'fas fa-mobile-alt'],
            'telepon' => ['Phone', 'fas fa-phone'],
            'hp' => ['Mobile', 'fas fa-mobile-alt'],
            
            // Email
            'email' => ['Email', 'fas fa-envelope'],
            'email_bisnis' => ['Email', 'fas fa-envelope'],
            'business_email' => ['Email', 'fas fa-envelope'],
            
            // Social Media & Messaging
            'whatsapp' => ['WhatsApp', 'fab fa-whatsapp'],
            'wa' => ['WhatsApp', 'fab fa-whatsapp'],
            'line' => ['LINE', 'fab fa-line'],
            'telegram' => ['Telegram', 'fab fa-telegram'],
            
            // Social Media
            'facebook' => ['Facebook', 'fab fa-facebook'],
            'instagram' => ['Instagram', 'fab fa-instagram'],
            'twitter' => ['Twitter', 'fab fa-twitter'],
            'tiktok' => ['TikTok', 'fab fa-tiktok'],
            'linkedin' => ['LinkedIn', 'fab fa-linkedin'],
            
            // Website
            'website' => ['Website', 'fas fa-globe'],
            'web' => ['Website', 'fas fa-globe'],
        ];

        $isPrimary = true; // First contact will be primary

        foreach ($contactMappings as $excelColumn => $contactInfo) {
            $contactValue = $row[$excelColumn] ?? null;
            
            // Skip if no value
            if (empty($contactValue)) {
                continue;
            }

            [$platformName, $iconClass] = $contactInfo;

            // Get or create contact type (auto-create if not exists)
            $contactType = ContactType::firstOrCreate(
                ['platform_name' => $platformName],
                ['icon_class' => $iconClass]
            );

            // Create business contact
            BusinessContact::create([
                'business_id' => $business->id,
                'contact_type_id' => $contactType->id,
                'contact_value' => $contactValue,
                'is_primary' => $isPrimary,
            ]);

            // Only first contact is primary
            $isPrimary = false;

            Log::info("Contact '{$platformName}' added for business '{$business->name}': {$contactValue}");
        }
    }

    /**
     * Parse date from various formats
     */
    private function parseDate($value): ?string
    {
        if (empty($value)) {
            return null;
        }

        try {
            $date = \Carbon\Carbon::parse($value);
            return $date->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Parse employee count
     */
    private function parseEmployeeCount(array $row): ?int
    {
        $employeeField = $row['sberlitik_2023_02_2_sumatoini'] ?? null;
        
        if ($employeeField && is_numeric($employeeField)) {
            return (int) $employeeField;
        }

        return null;
    }

    /**
     * Parse revenue range
     */
    private function parseRevenueRange(array $row): ?string
    {
        $revenueField = $row['sberlitik_2023_02_2_sumatoini_1_yene_jmist'] ?? null;
        
        if ($revenueField) {
            return $revenueField;
        }

        return null;
    }

    /**
     * Parse boolean from various formats
     */
    private function parseBoolean($value): bool
    {
        if (is_bool($value)) {
            return $value;
        }

        $value = strtolower(trim($value ?? ''));
        return in_array($value, ['yes', 'ya', 'true', '1', 'y']);
    }

    /**
     * Validation rules
     */
    public function rules(): array
    {
        return [
            'nama' => 'nullable|string',
            'name' => 'nullable|string',
            'email' => 'nullable|email',
        ];
    }

    /**
     * Get import results
     */
    public function getResults(): array
    {
        return [
            'success' => $this->successCount,
            'skipped' => $this->skippedCount,
            'errors' => $this->errors,
        ];
    }
}
