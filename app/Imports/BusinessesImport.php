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
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use App\Models\BusinessPhoto;

class BusinessesImport implements ToModel, WithHeadingRow, WithValidation, WithChunkReading
{
    protected $errors = [];
    protected $successCount = 0;
    protected $skippedCount = 0;

    /**
     * Chunk size for reading (memory efficient)
     */
    public function chunkSize(): int
    {
        return 100;
    }

    /**
     * Detect if the Excel data is student/user data instead of business data
     */
    private function isStudentData(array $row): bool
    {
        // Check for student-specific columns
        $studentColumns = ['nis', 'nisn', 'prodi', 'angkatan', 'student_year', 'major', 'jurusan', 'ipk', 'cgpa'];
        
        $studentColumnCount = 0;
        foreach ($studentColumns as $column) {
            if (array_key_exists($column, $row)) {
                $studentColumnCount++;
            }
        }
        
        // If 3 or more student columns exist, this is likely student data
        if ($studentColumnCount >= 3) {
            return true;
        }
        
        return false;
    }

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        try {
            // CRITICAL: Remove 'id' column if exists to prevent duplicate key errors
            // ID should be auto-incremented by database, not set from Excel
            unset($row['id']);
            
            // CRITICAL: Detect if this is student data instead of business data
            if ($this->isStudentData($row)) {
                $this->skippedCount++;
                $errorMsg = "Row skipped: This appears to be STUDENT/USER data, not BUSINESS data. Found student columns: " . implode(', ', array_keys($row)) . ". Please use User Import instead.";
                $this->errors[] = $errorMsg;
                Log::error("Business import: " . $errorMsg);
                return null;
            }

            // Skip if no business name
            // Business name from: "Nama bisnis/ventura" OR "Nama Perusahaan Tempat Bekerja (jika intraprenuer)"
            if (empty($row['nama_bisnisventura']) && 
                empty($row['nama_bisnis_ventura']) && 
                empty($row['nama_perusahaan_tempat_bekerja_jika_intraprenuer']) && 
                empty($row['nama_perusahaan_tempat_bekerja']) && 
                empty($row['business_name']) && 
                empty($row['nama_bisnis'])) {
                $this->skippedCount++;
                $this->errors[] = "❌ Missing business name - row skipped";
                return null;
            }

            // Get business name from Excel
            // TWO TYPES OF BUSINESSES:
            // 1. ENTREPRENEUR: "Nama bisnis/ventura" → nama_bisnisventura
            // 2. INTRAPRENEUR: "Nama Perusahaan Tempat Bekerja (jika intraprenuer)" → nama_perusahaan_tempat_bekerja_jika_intraprenuer
            //
            // Priority: entrepreneur column first, then intrapreneur column, then generic fallbacks
            // IMPORTANT: Do NOT use 'name' column as it contains OWNER name, not business name!
            $businessName = $row['nama_bisnisventura'] 
                ?? $row['nama_bisnis_ventura'] 
                ?? $row['nama_perusahaan_tempat_bekerja_jika_intraprenuer'] 
                ?? $row['nama_perusahaan_tempat_bekerja'] 
                ?? $row['business_name'] 
                ?? $row['nama_bisnis'] 
                ?? null;
            
            // Check if business already exists
            $existingBusiness = Business::where('name', $businessName)->first();
            if ($existingBusiness) {
                $this->skippedCount++;
                $this->errors[] = "⚠️ Duplicate: '{$businessName}' already exists, skipped";
                return null;
            }

            // Find owner by name OR email
            // Priority: 1) Email exact match, 2) Owner name field, 3) Nama field (student name)
            $user = null;
            
            // Try email first (most reliable)
            if (!empty($row['email']) || !empty($row['owner_email']) || !empty($row['email_owner'])) {
                $email = $row['email'] ?? $row['owner_email'] ?? $row['email_owner'];
                $user = User::where('email', $email)->first();
                if (!$user) {
                    Log::warning("Business '{$businessName}': User with email '{$email}' not found");
                }
            }
            
            // Try 'owner' or 'owner_name' field (business owner name)
            if (!$user && (!empty($row['owner']) || !empty($row['owner_name']) || !empty($row['nama_owner']))) {
                $ownerName = $row['owner'] ?? $row['owner_name'] ?? $row['nama_owner'];
                $user = User::where('name', 'like', '%' . $ownerName . '%')->first();
                if (!$user) {
                    Log::warning("Business '{$businessName}': User with owner name like '{$ownerName}' not found");
                }
            }
            
            // Last resort: try 'nama' field (student name)
            if (!$user && !empty($row['nama'])) {
                $user = User::where('name', 'like', '%' . $row['nama'] . '%')->first();
                if (!$user) {
                    Log::warning("Business '{$businessName}': User with name like '{$row['nama']}' not found");
                }
            }
            
            // If still no user found, log warning and skip
            if (!$user) {
                $emailAttempt = $row['email'] ?? $row['owner_email'] ?? $row['email_owner'] ?? 'N/A';
                $nameAttempt = $row['owner'] ?? $row['owner_name'] ?? $row['nama_owner'] ?? $row['nama'] ?? 'N/A';
                $errorMsg = "❌ Owner not found for '{$businessName}' - Tried email: '{$emailAttempt}', name: '{$nameAttempt}' - Import users first!";
                $this->errors[] = $errorMsg;
                $this->skippedCount++;
                return null;
            }

            // Get or create business type (category)
            // TWO SEPARATE EXCEL HEADERS:
            // 1. INTRAPRENEUR: "Jenis usaha dari perusahaan tempat Anda bekerja saat ini (jika intraprenuer)"
            //    Converts to: jenis_usaha_dari_perusahaan_tempat_anda_bekerja_saat_ini_jika_intraprenuer
            // 2. ENTREPRENEUR: "Jenis bisnis/ventura yang Anda jalankan saat ini"
            //    Converts to: jenis_bisnisventura_yang_anda_jalankan_saat_ini
            $businessTypeName = $row['jenis_usaha_dari_perusahaan_tempat_anda_bekerja_saat_ini_jika_intraprenuer'] 
                ?? $row['jenis_bisnisventura_yang_anda_jalankan_saat_ini'] 
                ?? $row['jenis_usaha_dari_perusahaan_tempat_anda_bekerja_saat_ini_jika_intraprenuer_atau_jenis_bisnisventura_yang_anda_jalankan_saat_ini'] 
                ?? $row['jenis_bisnisventura'] 
                ?? $row['jenis_bisnis_ventura'] 
                ?? $row['kategori_bisnis'] 
                ?? $row['jenis_bisnis'] 
                ?? $row['business_type'] 
                ?? $row['business_line'] 
                ?? $row['kategori'] 
                ?? $row['category']
                ?? $row['sektor']
                ?? $row['bidang']
                ?? $row['industri']
                ?? null;
            
            // Log for debugging if no business type found
            if (!$businessTypeName) {
                Log::warning("No business type found for '{$businessName}'. Available columns: " . implode(', ', array_keys($row)));
                $businessTypeName = 'Other';
            }
            
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

            // Get user position in this business (jabatan user di perusahaan)
            // Excel headers: "Posisi saat ini (jika intraprenuer)" or "Posisi saat ini"
            $position = $row['posisi_saat_ini_jika_intraprenuer'] 
                ?? $row['posisi_saat_ini'] 
                ?? $row['position'] 
                ?? $row['posisi'] 
                ?? $row['jabatan']
                ?? null;

            // Create business
            $business = new Business([
                'user_id' => $user->id,
                'business_type_id' => $businessType->id,
                'name' => $businessName,
                'position' => $position,
                'description' => $description ?: (
                    $row['deskripsi_bisnis'] ?? 
                    $row['deskripsi'] ?? 
                    $row['description'] ?? 
                    $row['business_description'] ?? 
                    'No description provided'
                ),
                'business_mode' => $businessMode,
                
                // Additional fields with proper column mapping
                'address' => $row['address'] ?? $row['alamat'] ?? null,
                'established_date' => $this->parseDate($row['established_date'] ?? $row['tanggal_berdiri'] ?? null),
                'employee_count' => $this->parseEmployeeCount($row),
                'revenue_range' => $this->parseRevenueRange($row),
                'is_from_college_project' => $this->parseBoolean($row['from_college_project'] ?? $row['dari_kuliah'] ?? null),
                'is_continued_after_graduation' => $this->parseBoolean($row['continued_after_grad'] ?? $row['lanjut_setelah_lulus'] ?? null),
                'business_challenges' => $challenges,
                // capture any remaining unmapped columns for audit/reference
                'additional_data' => $this->buildAdditionalData($row),
            ]);

            $business->save();
            
            // Import contacts (phone, email, whatsapp, etc.)
            $this->importContacts($business, $row);

            // Synchronously download any image URLs found in the row and upload to storage
            // This will set business->logo_url and create BusinessPhoto records for other photos
            try {
                $this->handleImages($business, $row);
            } catch (\Exception $e) {
                Log::warning("Image handling failed for business '{$businessName}': " . $e->getMessage());
            }
            
            $this->successCount++;
            
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
        
        // Priority 1: Explicit description fields
        if (!empty($row['description'])) {
            $parts[] = $row['description'];
        }
        if (!empty($row['deskripsi'])) {
            $parts[] = $row['deskripsi'];
        }
        if (!empty($row['business_description'])) {
            $parts[] = $row['business_description'];
        }
        
        // Priority 2: Long text fields that might contain descriptions
        if (!empty($row['pt_smk_jiteram_114_2024_10_pt_74_jk'])) {
            $parts[] = $row['pt_smk_jiteram_114_2024_10_pt_74_jk'];
        }
        
        if (!empty($row['capital_for_entrepreneur'])) {
            $parts[] = "Capital: " . $row['capital_for_entrepreneur'];
        }
        
        if (!empty($row['netserviceinya_others_pen_rumot_tunggo'])) {
            $parts[] = $row['netserviceinya_others_pen_rumot_tunggo'];
        }
        
        // Priority 3: Business details
        if (!empty($row['about'])) {
            $parts[] = $row['about'];
        }
        if (!empty($row['tentang'])) {
            $parts[] = $row['tentang'];
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
     * Scan row for image URLs, download synchronously and upload to configured storage.
     * Sets business->logo_url for logo-like columns and creates BusinessPhoto for other images.
     */
    private function handleImages(Business $business, array $row): void
    {
        $disk = config('filesystems.default', 'local');

        foreach ($row as $col => $val) {
            if (empty($val)) continue;

            // Split potential multiple URLs in a single cell (comma or whitespace separated)
            $candidates = preg_split('/[\s,;]+/', trim($val));

            foreach ($candidates as $candidate) {
                $candidate = trim($candidate);
                if (empty($candidate)) continue;

                // Quick URL validation
                if (!filter_var($candidate, FILTER_VALIDATE_URL)) continue;

                try {
                    $storedPath = $this->downloadAndStoreImage($candidate, $business->id, $disk);
                    if (!$storedPath) continue;

                    // Resolve public URL if available
                    try {
                        $publicUrl = Storage::disk($disk)->url($storedPath);
                    } catch (\Exception $e) {
                        // Fallback to stored path
                        $publicUrl = $storedPath;
                    }

                    // Determine if this column is likely a logo
                    if (preg_match('/logo|logo_usaha|logo_kantor|logo_perusahaan/i', $col)) {
                        $business->logo_url = $publicUrl;
                        $business->save();
                    } else {
                        BusinessPhoto::create([
                            'business_id' => $business->id,
                            'photo_url' => $publicUrl,
                            'caption' => $col,
                        ]);
                    }

                } catch (\Exception $e) {
                    Log::warning("Failed downloading/uploading image '{$candidate}' for business ID {$business->id}: " . $e->getMessage());
                    continue;
                }
            }
        }
    }

    /**
     * Download remote image and store it to the configured disk.
     * Returns the stored path on success or null on failure.
     */
    private function downloadAndStoreImage(string $url, int $businessId, string $disk): ?string
    {
        // Only allow http/https
        $scheme = parse_url($url, PHP_URL_SCHEME);
        if (!in_array(strtolower($scheme), ['http', 'https'], true)) {
            return null;
        }

        // Prevent SSRF / local network access
        if ($this->isUrlPrivate($url)) {
            return null;
        }

        $response = Http::retry(3, 200)->timeout(15)->withOptions(['verify' => true])->get($url);
        if (!$response->ok()) {
            return null;
        }

        $contentType = $response->header('Content-Type', '');
        if (stripos($contentType, 'image/') !== 0) {
            return null;
        }

        // Limit size to 5MB
        $maxBytes = 5 * 1024 * 1024;
        $contentLength = $response->header('Content-Length');
        if ($contentLength !== null && is_numeric($contentLength) && (int)$contentLength > $maxBytes) {
            return null;
        }

        $body = $response->body();
        if (strlen($body) > $maxBytes) {
            return null;
        }

        $basename = basename(parse_url($url, PHP_URL_PATH) ?: 'image');
        $basename = preg_replace('/[^A-Za-z0-9_\-\.]/', '_', $basename);
        if (empty($basename)) {
            $basename = 'image_' . uniqid();
        }

        $path = "imports/businesses/{$businessId}/" . uniqid() . '_' . $basename;

        Storage::disk($disk)->put($path, $body);

        return $path;
    }

    /**
     * Basic check to prevent downloading from private IPs or localhost.
     */
    private function isUrlPrivate(string $url): bool
    {
        $host = parse_url($url, PHP_URL_HOST);
        if (empty($host)) return true;

        if (in_array(strtolower($host), ['localhost', '127.0.0.1', '::1'])) return true;

        $ips = @gethostbynamel($host) ?: [];
        foreach ($ips as $ip) {
            if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false) {
                return true;
            }
        }

        return false;
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
     * Store additional (unmapped) columns from the Excel row
     */
    private function buildAdditionalData(array $row): ?array
    {
        // Remove known mapped fields to avoid duplication
        $known = [
            'id','nama_bisnisventura','nama_bisnis_ventura','nama_perusahaan_tempat_bekerja_jika_intraprenuer',
            'nama_perusahaan_tempat_bekerja','business_name','nama_bisnis','owner','owner_name','nama_owner','nama',
            'email','owner_email','email_owner','jenis_bisnisventura','jenis_bisnis_ventura','business_type',
            'business_mode','description','deskripsi','business_description','alamat','address','established_date',
            'tanggal_berdiri','employee_count','revenue_range','from_college_project','dari_kuliah','continued_after_grad',
            'lanjut_setelah_lulus','posisi_saat_ini_jika_intraprenuer','posisi_saat_ini','position','posisi','jabatan',
        ];

        $data = [];
        foreach ($row as $k => $v) {
            if (in_array($k, $known, true)) continue;
            if ($v === null || $v === '') continue;
            $data[$k] = $v;
        }

        return !empty($data) ? $data : null;
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
