<?php

namespace App\Imports;

use App\Models\Business;
use App\Models\User;
use App\Models\BusinessType;
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
            if (empty($row['nama']) && empty($row['name'])) {
                $this->skippedCount++;
                return null;
            }

            // Get business name
            $businessName = $row['nama'] ?? $row['name'] ?? null;
            
            // Check if business already exists
            $existingBusiness = Business::where('name', $businessName)->first();
            if ($existingBusiness) {
                $this->skippedCount++;
                Log::info("Business '{$businessName}' already exists, skipping...");
                return null;
            }

            // Find owner by name (from Excel)
            $ownerName = $row['status_dan_major'] ?? $row['owner'] ?? null;
            $user = null;
            
            if ($ownerName) {
                // Try to find user by name
                $user = User::where('name', 'like', '%' . $ownerName . '%')->first();
                
                // If not found, try by email
                if (!$user && isset($row['email'])) {
                    $user = User::where('email', $row['email'])->first();
                }
            }
            
            // If no user found, skip or assign to first admin
            if (!$user) {
                $user = User::where('role', 'admin')->first();
                if (!$user) {
                    Log::warning("No owner found for business '{$businessName}', skipping...");
                    $this->skippedCount++;
                    return null;
                }
            }

            // Get or create business type
            $businessTypeName = $row['ptrbn_for_apakah_an_having_post_paste_saat_mengikat_ke_bankrapot_pendidikan_kategori'] 
                ?? $row['business_type'] 
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
                'description' => $description ?: 'No description provided',
                'business_mode' => $businessMode,
                
                // Additional fields
                'address' => $row['phone'] ?? $row['mobile'] ?? null,
                'established_date' => $this->parseDate($row['tiadak_sedik_1_belanciontors'] ?? null),
                'employee_count' => $this->parseEmployeeCount($row),
                'revenue_range' => $this->parseRevenueRange($row),
                'is_from_college_project' => $this->parseBoolean($row['ya'] ?? null),
                'is_continued_after_graduation' => $this->parseBoolean($row['tiadak_sedik_1_belanciontors'] ?? null),
                'business_challenges' => $challenges,
            ]);

            $business->save();
            
            $this->successCount++;
            Log::info("Business '{$businessName}' imported successfully for user '{$user->name}'");
            
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
