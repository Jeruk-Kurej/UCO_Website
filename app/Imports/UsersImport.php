<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Support\Str;

class UsersImport implements ToModel, WithHeadingRow, WithValidation, WithBatchInserts, WithChunkReading
{
    protected $errors = [];
    protected $successCount = 0;
    protected $skippedCount = 0;

    /**
     * Batch size for bulk inserts
     */
    public function batchSize(): int
    {
        return 100;
    }

    /**
     * Chunk size for reading
     */
    public function chunkSize(): int
    {
        return 100;
    }

    /**
     * Detect if the Excel data is business data instead of user data
     */
    private function isBusinessData(array $row): bool
    {
        // Only reject if it's CLEARLY business data (has business columns but NO user columns)
        $businessColumns = ['business_name', 'business_type', 'business_mode', 'established_date', 'employee_count', 'revenue_range'];
        $userColumns = ['name', 'email', 'username', 'nis', 'student_year', 'major'];
        
        $hasUserData = false;
        foreach ($userColumns as $column) {
            if (array_key_exists($column, $row) && !empty($row[$column])) {
                $hasUserData = true;
                break;
            }
        }
        
        // If we have user data (name or email), this is USER data, not business data
        if ($hasUserData) {
            return false;
        }
        
        // Only mark as business data if NO user columns exist but business columns do
        $businessColumnCount = 0;
        foreach ($businessColumns as $column) {
            if (array_key_exists($column, $row) && !empty($row[$column])) {
                $businessColumnCount++;
            }
        }
        
        // If 3 or more business columns exist AND no user data, this is business data
        return $businessColumnCount >= 3;
    }

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        try {
            // CRITICAL: Detect if this is business data instead of user data
            if ($this->isBusinessData($row)) {
                $this->skippedCount++;
                $errorMsg = "Row skipped: This appears to be BUSINESS data, not USER data. Found business columns: " . implode(', ', array_keys($row)) . ". Please use Business Import instead.";
                $this->errors[] = $errorMsg;
                Log::error("User import: " . $errorMsg);
                return null;
            }

            // Check required fields
            if (empty($row['name'])) {
                $this->skippedCount++;
                $this->errors[] = "❌ Missing name - row skipped";
                return null;
            }

            if (empty($row['email'])) {
                $this->skippedCount++;
                $this->errors[] = "❌ Missing email for '{$row['name']}' - row skipped";
                return null;
            }

            // Check if user already exists
            $existingUser = User::where('email', $row['email'])->first();
            
            if ($existingUser) {
                $this->skippedCount++;
                $this->errors[] = "⚠️ Duplicate: '{$row['name']}' ({$row['email']}) already exists, skipped";
                return null;
            }

            $user = new User([
                'username' => $row['username'] ?? strtolower(str_replace(' ', '_', $row['name'])),
                'name' => $row['name'],
                'email' => $row['email'],
                'password' => isset($row['password']) ? Hash::make($row['password']) : Hash::make('password123'),
                'role' => $row['role'] ?? 'student',
                'is_active' => isset($row['is_active']) ? (bool)$row['is_active'] : true,
                'email_verified_at' => now(),
                
                // Personal Information
                'birth_date' => $row['birth_date'] ?? $row['tanggal_lahir'] ?? null,
                'birth_city' => $row['birth_city'] ?? $row['tempat_lahir'] ?? null,
                'religion' => $row['religion'] ?? $row['agama'] ?? null,
                
                // Contact Information
                'phone_number' => $row['phone_number'] ?? $row['phone'] ?? $row['telp'] ?? null,
                'mobile_number' => $row['mobile_number'] ?? $row['mobile'] ?? $row['hp'] ?? null,
                'whatsapp' => $row['whatsapp'] ?? $row['wa'] ?? null,
                
                // Student/Academic Information - MATCH DATABASE FIELD NAMES (PascalCase)
                'NIS' => $row['nis'] ?? null,
                'Student_Year' => $row['student_year'] ?? $row['angkatan'] ?? null,
                'Major' => $row['major'] ?? $row['prodi'] ?? $row['jurusan'] ?? null,
                'Is_Graduate' => isset($row['is_graduate']) ? (bool)$row['is_graduate'] : false,
                'CGPA' => $row['cgpa'] ?? $row['ipk'] ?? null,
                
                // JSON fields - store additional data
                'personal_data' => $this->buildPersonalData($row),
                'academic_data' => $this->buildAcademicData($row),
                'father_data' => $this->buildFatherData($row),
                'mother_data' => $this->buildMotherData($row),
                'graduation_data' => $this->buildGraduationData($row),
            ]);

            $this->successCount++;
            
            return $user;

        } catch (\Exception $e) {
            $this->errors[] = "Error importing user: {$e->getMessage()} - Data: " . json_encode($row);
            Log::error("User import error: " . $e->getMessage());
            $this->skippedCount++;
            return null;
        }
    }

    /**
     * Build personal data JSON
     */
    private function buildPersonalData(array $row): ?array
    {
        $data = array_filter([
            // Basic Personal
            'gender' => $row['gender'] ?? null,
            'citizenship' => $row['citizenship'] ?? null,
            'citizenship_no' => $row['citizenship_no'] ?? null,
            
            // Primary Address
            'address' => $row['address'] ?? null,
            'address_city' => $row['address_city'] ?? null,
            'province' => $row['province'] ?? null,
            'country' => $row['country'] ?? null,
            'zip_code' => $row['zip_code'] ?? null,
            
            // Secondary Address
            'address2' => $row['address2'] ?? null,
            'address_city2' => $row['address_city2'] ?? null,
            'province2' => $row['province2'] ?? null,
            'country2' => $row['country2'] ?? null,
            'zip_code2' => $row['zip_code2'] ?? null,
            
            // Additional Contacts
            'phone_number2' => $row['phone_number2'] ?? null,
            'mobile_number2' => $row['mobile_number2'] ?? null,
            'bbm' => $row['bbm'] ?? null,
            'line' => $row['line'] ?? null,
            'facebook' => $row['facebook'] ?? null,
            'twitter' => $row['twitter'] ?? null,
            'instagram' => $row['instagram'] ?? null,
        ]);
        
        return !empty($data) ? $data : null;
    }

    /**
     * Build academic data JSON
     */
    private function buildAcademicData(array $row): ?array
    {
        $data = array_filter([
            // Student IDs
            'nisn' => $row['nisn'] ?? null,
            'prodi' => $row['prodi'] ?? null,
            'sub_prodi' => $row['sub_prodi'] ?? null,
            
            // Education History
            'edu_level' => $row['edu_level'] ?? null,
            'academic_advisor' => $row['academic_advisor'] ?? null,
            'previous_school_name' => $row['previous_school_name'] ?? null,
            'school_city' => $row['school_city'] ?? null,
            'previous_edu_level' => $row['previous_edu_level'] ?? null,
            'start_year' => $row['start_year'] ?? null,
            'end_year' => $row['end_year'] ?? null,
            'score' => $row['score'] ?? null,
            
            // Certificates
            'certificate_no_1' => $row['certificate_no_1'] ?? null,
            'certificate_date_1' => $row['certificate_date_1'] ?? null,
            'certificate_no_2' => $row['certificate_no_2'] ?? null,
            'certificate_date_2' => $row['certificate_date_2'] ?? null,
        ]);
        
        return !empty($data) ? $data : null;
    }

    /**
     * Build father data JSON
     */
    private function buildFatherData(array $row): ?array
    {
        $data = array_filter([
            // Basic Info
            'name' => $row['father_name'] ?? null,
            'birth_city' => $row['father_birth_city'] ?? null,
            'birthday' => $row['father_birthday'] ?? null,
            'citizenship' => $row['father_citizenship'] ?? null,
            'citizenship_no' => $row['father_citizenship_no'] ?? null,
            'passport_no' => $row['father_passport_no'] ?? null,
            'npwp_no' => $row['father_npwp_no'] ?? null,
            'religion' => $row['father_religion'] ?? null,
            'bpjs_no' => $row['father_bpjs_no'] ?? null,
            
            // Contact
            'address' => $row['father_address'] ?? null,
            'address_city' => $row['father_address_city'] ?? null,
            'phone' => $row['father_phone'] ?? null,
            'mobile' => $row['father_mobile'] ?? null,
            'email' => $row['father_email'] ?? null,
            'bbm' => $row['father_bbm'] ?? null,
            
            // Education & Work
            'education' => $row['father_education'] ?? null,
            'education_major' => $row['father_education_major'] ?? null,
            'profession' => $row['father_profession'] ?? null,
            'business_name' => $row['father_business_name'] ?? null,
            'business_address' => $row['father_business_address'] ?? null,
            'business_phone' => $row['father_business_phone'] ?? null,
            'business_line' => $row['father_business_line'] ?? null,
            'business_title' => $row['father_business_title'] ?? null,
            'business_revenue' => $row['father_business_revenue'] ?? null,
            'special_need' => $row['father_special_need'] ?? null,
        ]);
        
        return !empty($data) ? $data : null;
    }

    /**
     * Build mother data JSON
     */
    private function buildMotherData(array $row): ?array
    {
        $data = array_filter([
            // Basic Info
            'name' => $row['mother_name'] ?? null,
            'birth_city' => $row['mother_birth_city'] ?? null,
            'birthday' => $row['mother_birthday'] ?? null,
            'citizenship' => $row['mother_citizenship'] ?? null,
            'citizenship_no' => $row['mother_citizenship_no'] ?? null,
            'passport_no' => $row['mother_passport_no'] ?? null,
            'npwp_no' => $row['mother_npwp_no'] ?? null,
            'religion' => $row['mother_religion'] ?? null,
            'bpjs_no' => $row['mother_bpjs_no'] ?? null,
            
            // Contact
            'address' => $row['mother_address'] ?? null,
            'address_city' => $row['mother_address_city'] ?? null,
            'phone' => $row['mother_phone'] ?? null,
            'mobile' => $row['mother_mobile'] ?? null,
            'email' => $row['mother_email'] ?? null,
            'bbm' => $row['mother_bbm'] ?? null,
            
            // Education & Work
            'education' => $row['mother_education'] ?? null,
            'education_major' => $row['mother_education_major'] ?? null,
            'profession' => $row['mother_profession'] ?? null,
            'business_name' => $row['mother_business_name'] ?? null,
            'business_address' => $row['mother_business_address'] ?? null,
            'business_phone' => $row['mother_business_phone'] ?? null,
            'business_line' => $row['mother_business_line'] ?? null,
            'business_title' => $row['mother_business_title'] ?? null,
            'business_revenue' => $row['mother_business_revenue'] ?? null,
            'special_need' => $row['mother_special_need'] ?? null,
        ]);
        
        return !empty($data) ? $data : null;
    }

    /**
     * Build graduation data JSON
     */
    private function buildGraduationData(array $row): ?array
    {
        $data = array_filter([
            // Official Info
            'official_email' => $row['official_email'] ?? null,
            'current_status' => $row['current_status'] ?? null,
            'class_semester' => $row['class_semester'] ?? null,
            'form_no' => $row['form_no'] ?? null,
            'start_date' => $row['start_date'] ?? null,
            'end_date' => $row['end_date'] ?? null,
            
            // Final Projects
            'final_project_indonesia' => $row['final_project_indonesia'] ?? null,
            'final_project_english' => $row['final_project_english'] ?? null,
            
            // Academic Results
            'cum_credits' => $row['cum_credits'] ?? null,
            'predicate' => $row['predicate'] ?? null,
            
            // Graduation Docs
            'judicium_date' => $row['judicium_date'] ?? null,
            'document_no' => $row['document_no'] ?? null,
            'document_date' => $row['document_date'] ?? null,
            'graduate_period' => $row['graduate_period'] ?? null,
            
            // Business Info
            'business_name' => $row['business_name'] ?? null,
            'business_line' => $row['business_line'] ?? null,
            'business_title' => $row['business_title'] ?? null,
        ]);
        
        return !empty($data) ? $data : null;
    }

    /**
     * Validation rules for each row
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role' => 'nullable|in:student,alumni,admin',
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
