<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithColumnLimit;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Events\BeforeImport;
use Maatwebsite\Excel\Events\AfterImport;
use Maatwebsite\Excel\Events\ImportFailed;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class UsersImport implements ToModel, WithHeadingRow, WithValidation, WithChunkReading, WithEvents, WithColumnLimit, SkipsEmptyRows
{
    use \App\Traits\UcAuthTrait;

    public $importId;
    public $timeout = 3600; // Allow up to 1 hour per chunk if images are slow
    protected $errors = [];
    protected $successCount = 0;
    protected $skippedCount = 0;

    public function __construct($importId = null)
    {
        $this->importId = $importId;
        // Boost memory for image processing
        @ini_set('memory_limit', '512M');
    }

    /**
     * Chunk size for reading (memory efficient)
     */
    public function chunkSize(): int
    {
        return 5; // Reduced to 5 for maximum safety with large images
    }

    /**
     * Column limit to save memory (Ignore columns after Z)
     */
    public function endColumn(): string
    {
        return 'DZ'; // Support the massive ~114 columns provided by the user
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
            // CRITICAL: Remove 'id' column if exists to prevent duplicate key errors
            // ID should be auto-incremented by database, not set from Excel
            unset($row['id']);

            // CRITICAL: Detect if this is business data instead of user data
            if ($this->isBusinessData($row)) {
                $this->skippedCount++;
                $this->errors[] = "Wrong file: This row appears to be Business data. Use Business Import instead.";
                $this->updateWorkerProgress('skipped');
                return null;
            }

            // Check required fields
            if (empty($row['name'])) {
                $this->skippedCount++;
                $this->errors[] = "Skipped: Missing name field";
                $this->updateWorkerProgress('skipped');
                return null;
            }

            if (empty($row['email'])) {
                $this->skippedCount++;
                $this->errors[] = "Skipped: Missing email for row with name '{$row['name']}'";
                $this->updateWorkerProgress('skipped');
                return null;
            }

            // Check if user already exists — if so, merge non-empty fields from import
            $existingUser = User::where('email', $row['email'])->first();
            if ($existingUser) {
                $updated = false;

                $mergeFields = [
                    'username' => $row['username'] ?? null,
                    'name' => $row['name'] ?? null,
                    'role' => $row['role'] ?? null,
                    'is_active' => isset($row['is_active']) ? (bool)$row['is_active'] : null,
                    'birth_date' => $row['birth_date'] ?? $row['tanggal_lahir'] ?? $row['birthday'] ?? null,
                    'birth_city' => $row['birth_city'] ?? $row['tempat_lahir'] ?? null,
                    'religion' => $row['religion'] ?? $row['agama'] ?? null,
                    'phone_number' => $row['phone_number'] ?? $row['phone'] ?? $row['telp'] ?? $row['phone_1'] ?? null,
                    'mobile_number' => $row['mobile_number'] ?? $row['mobile'] ?? $row['hp'] ?? $row['mobile_1'] ?? null,
                    'whatsapp' => $row['whatsapp'] ?? $row['wa'] ?? null,
                    'NIS' => $row['nis'] ?? null,
                    'Student_Year' => $row['student_year'] ?? $row['angkatan'] ?? $row['academic_year'] ?? null,
                    'Major' => $row['major'] ?? $row['prodi'] ?? $row['jurusan'] ?? null,
                    'Is_Graduate' => isset($row['is_graduate']) ? (bool)$row['is_graduate'] : (isset($row['is_graduated']) ? (bool)$row['is_graduated'] : null),
                    'CGPA' => $row['cgpa'] ?? $row['ipk'] ?? null,
                ];

                foreach ($mergeFields as $k => $v) {
                    if ($v === null || $v === '') continue;
                    
                    $currentValue = $existingUser->{$k};
                    // Only merge if the current DB field is truly empty (null or empty string) 
                    // and the incoming value is actually a change.
                    if (($currentValue === null || $currentValue === '') && $v !== $currentValue) {
                        $existingUser->{$k} = $v;
                        $updated = true;
                    }
                }

                // Merge JSON fields
                foreach (['personal_data', 'academic_data', 'father_data', 'mother_data', 'graduation_data'] as $jsonField) {
                    $incoming = $this->{"build" . ucfirst(str_replace('_', '', $jsonField))}($row) ?? ($row[$jsonField] ?? null);
                    if ($incoming && is_array($incoming)) {
                        $existingVal = $existingUser->{$jsonField} ?? [];
                        $merged = array_merge((array)$existingVal, $incoming);
                        if ($merged !== (array)$existingVal) {
                            $existingUser->{$jsonField} = $merged;
                            $updated = true;
                        }
                    }
                }

                // Merge additional_data
                $incomingAdditional = $this->buildAdditionalData($row) ?? [];
                if (!empty($incomingAdditional)) {
                    $existingAdditional = (array)($existingUser->additional_data ?? []);
                    $mergedAdditional = array_merge($existingAdditional, $incomingAdditional);
                    if ($mergedAdditional !== $existingAdditional) {
                        $existingUser->additional_data = $mergedAdditional;
                        $updated = true;
                    }
                }

                if ($updated) {
                    $existingUser->save();
                    $this->successCount++;
                    $this->updateWorkerProgress('success');
                    try {
                        $this->handleUserImages($existingUser, $row);
                    } catch (\Exception $e) {
                        Log::warning('User image handling failed for existing user ' . ($existingUser->email ?? $existingUser->name) . ': ' . $e->getMessage());
                    }
                    gc_collect_cycles();
                    return $existingUser;
                }

                // Truly duplicate — no new data to add
                $this->skippedCount++;
                $this->errors[] = "Duplicate: '{$row['name']}' ({$row['email']}) — already exists, no new data";
                $this->updateWorkerProgress('skipped');
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
                'birth_date' => $row['birth_date'] ?? $row['tanggal_lahir'] ?? $row['birthday'] ?? null,
                'birth_city' => $row['birth_city'] ?? $row['tempat_lahir'] ?? null,
                'religion' => $row['religion'] ?? $row['agama'] ?? null,

                // Contact Information
                'phone_number' => $row['phone_number'] ?? $row['phone'] ?? $row['telp'] ?? $row['phone_1'] ?? null,
                'mobile_number' => $row['mobile_number'] ?? $row['mobile'] ?? $row['hp'] ?? $row['mobile_1'] ?? null,
                'whatsapp' => $row['whatsapp'] ?? $row['wa'] ?? null,

                // Student/Academic Information - MATCH DATABASE FIELD NAMES (PascalCase)
                'NIS' => $row['nis'] ?? null,
                'Student_Year' => $row['student_year'] ?? $row['angkatan'] ?? $row['academic_year'] ?? null,
                'Major' => $row['major'] ?? $row['prodi'] ?? $row['jurusan'] ?? null,
                'Is_Graduate' => isset($row['is_graduate']) ? (bool)$row['is_graduate'] : (isset($row['is_graduated']) ? (bool)$row['is_graduated'] : false),
                'CGPA' => $row['cgpa'] ?? $row['ipk'] ?? null,

                // JSON fields - store additional data
                'personal_data' => $this->buildPersonalData($row),
                'academic_data' => $this->buildAcademicData($row),
                'father_data' => $this->buildFatherData($row),
                'mother_data' => $this->buildMotherData($row),
                'graduation_data' => $this->buildGraduationData($row),
                // Store all remaining columns for future reference
                'additional_data' => $this->buildAdditionalData($row),
            ]);

            // Persist user so we have an id for image paths and further updates
            $user->save();

            // Handle synchronous image downloads for user (profile photo, other photos)
            try {
                $this->handleUserImages($user, $row);
            } catch (\Exception $e) {
                Log::warning('User image handling failed for ' . ($user->email ?? $user->name) . ': ' . $e->getMessage());
            }

            $this->successCount++;
            $this->updateWorkerProgress('success');

            // Explicitly clear memory after processing each row/chunk
            gc_collect_cycles();

            return $user;
        } catch (\Exception $e) {
            $this->errors[] = "Error importing user: {$e->getMessage()} - Data: " . json_encode($row);
            Log::error("User import error: " . $e->getMessage());
            $this->skippedCount++;
            $this->updateWorkerProgress('skipped');
            return null;
        }
    }

    /**
     * Update worker progress in Cache (Atomic)
     */
    protected function updateWorkerProgress($status = 'success')
    {
        if ($this->importId) {
            $prefix = "import_{$this->importId}";

            Cache::increment("{$prefix}_current");

            if ($status === 'success') {
                Cache::increment("{$prefix}_success");
            } else {
                Cache::increment("{$prefix}_skipped");
            }

            if (!empty($this->errors)) {
                $progress = Cache::get($prefix, ['status' => 'processing', 'errors' => []]);
                $progress['errors'] = array_slice(array_merge($progress['errors'] ?? [], $this->errors), -10);
                Cache::put($prefix, $progress, now()->addMinutes(30));
                $this->errors = [];
            }

            $total = (int) Cache::get("{$prefix}_total", 0);
            $current = (int) Cache::get("{$prefix}_current", 0);

            if ($total > 0 && $current >= $total) {
                $progress = Cache::get($prefix, ['status' => 'processing', 'errors' => []]);
                $progress['status'] = 'completed';
                Cache::put($prefix, $progress, now()->addMinutes(30));
            }
        }
    }

    /**
     * Maatwebsite Excel Events
     */
    public function registerEvents(): array
    {
        return [
            BeforeImport::class => function (BeforeImport $event) {
                if ($this->importId) {
                    $totalRows = $event->getReader()->getTotalRows();
                    $totalCount = 0;
                    foreach ($totalRows as $rows) {
                        if ($rows > $totalCount) $totalCount = $rows;
                    }
                    $totalCount = max(0, $totalCount - 1);

                    $prefix = "import_{$this->importId}";

                    Cache::put($prefix, [
                        'status' => 'processing',
                        'errors' => []
                    ], now()->addMinutes(60));

                    Cache::forever("{$prefix}_total", $totalCount);
                    Cache::forever("{$prefix}_current", 0);
                    Cache::forever("{$prefix}_success", 0);
                    Cache::forever("{$prefix}_skipped", 0);

                    Log::info("[UserImport-Queue] ID: {$this->importId} | Started with {$totalCount} rows");
                }
            },
            AfterImport::class => function (AfterImport $event) {
                if ($this->importId) {
                    $prefix = "import_{$this->importId}";
                    $progress = Cache::get($prefix, ['status' => 'processing', 'errors' => []]);
                    $progress['status'] = 'completed';
                    Cache::put($prefix, $progress, now()->addMinutes(60));
                    Log::info("[UserImport-Queue] ID: {$this->importId} | Finished Successfully");
                }
            },
            ImportFailed::class => function (ImportFailed $event) {
                if ($this->importId) {
                    $prefix = "import_{$this->importId}";
                    $progress = Cache::get($prefix, ['status' => 'processing', 'errors' => []]);
                    $progress['status'] = 'failed';
                    $progress['errors'][] = $event->getException()->getMessage();
                    Cache::put($prefix, $progress, now()->addMinutes(60));
                    Log::error("[UserImport-Queue] ID: {$this->importId} | FAILED: " . $event->getException()->getMessage());
                }
            },
        ];
    }

    /**
     * Store additional (unmapped) columns from the Excel row
     */
    private function buildAdditionalData(array $row): ?array
    {
        // Remove known mapped fields to avoid duplication
        $known = [
            'id',
            'username',
            'name',
            'email',
            'password',
            'role',
            'is_active',
            'birth_date',
            'birth_city',
            'religion',
            'phone_number',
            'mobile_number',
            'whatsapp',
            'nis',
            'student_year',
            'major',
            'is_graduate',
            'cgpa',
            'personal_data',
            'academic_data',
            'father_data',
            'mother_data',
            'graduation_data'
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
            'address' => $row['address'] ?? $row['address_1'] ?? $row['alamat'] ?? null,
            'address_city' => $row['address_city'] ?? null,
            'province' => $row['province'] ?? null,
            'country' => $row['country'] ?? null,
            'zip_code' => $row['zip_code'] ?? $row['postal_code_1'] ?? null,

            // Secondary Address
            'address2' => $row['address2'] ?? $row['address_2'] ?? null,
            'address_city2' => $row['address_city_2'] ?? null,
            'province2' => $row['province_2'] ?? null,
            'country2' => $row['country_2'] ?? null,
            'zip_code2' => $row['zip_code2'] ?? $row['postal_code_2'] ?? null,

            // Additional Contacts
            'phone_number2' => $row['phone_number2'] ?? $row['phone_2'] ?? null,
            'mobile_number2' => $row['mobile_number2'] ?? $row['mobile_2'] ?? null,
            'bbm' => $row['bbm'] ?? null,
            'line' => $row['line'] ?? null,
            'facebook' => $row['facebook'] ?? null,
            'twitter' => $row['twitter'] ?? null,
            'instagram' => $row['instagram'] ?? null,

            // Identity extras
            'passport_no' => $row['passport_no'] ?? $row['nomor_paspor'] ?? null,
            'special_need' => $row['special_need'] ?? $row['kebutuhan_khusus'] ?? null,
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
            'previous_school_name' => $row['previous_school_name'] ?? null,
            'school_city' => $row['school_city'] ?? null,
            'previous_edu_level' => $row['previous_edu_level'] ?? null,
            'start_year' => $row['start_year'] ?? null,
            'end_year' => $row['end_year'] ?? null,
            'score' => $row['score'] ?? null,

            // Certificates
            'academic_advisor' => $row['academic_advisor'] ?? null,
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
     * Handle image URLs in user import row: download and upload synchronously.
     * Sets `profile_photo_url` for profile-like columns and stores others in additional_data.imported_images
     */
    private function handleUserImages(User $user, array $row): void
    {
        $disk = config('filesystems.default', 'local');

        foreach ($row as $col => $val) {
            if (empty($val)) continue;

            $candidates = preg_split('/[\s,;]+/', trim($val));
            foreach ($candidates as $candidate) {
                $candidate = trim($candidate);
                if (empty($candidate)) continue;
                if (!filter_var($candidate, FILTER_VALIDATE_URL)) continue;

                try {
                    $storedPath = $this->downloadAndStoreImageUser($candidate, $user->id, $disk);
                    if (!$storedPath) continue;

                    try {
                        $publicUrl = Storage::url($storedPath);
                    } catch (\Exception $e) {
                        $publicUrl = $storedPath;
                    }

                    if (preg_match('/foto_pribadi|foto_diri|foto_profil|profile_photo|photo_profile|foto/i', $col)) {
                        $user->profile_photo_url = $publicUrl;
                        $user->save();
                    } else {
                        $additional = $user->additional_data ?? [];
                        $additional['imported_images'][] = $publicUrl;
                        $user->additional_data = $additional;
                        $user->save();
                    }
                } catch (\Exception $e) {
                    Log::warning("Failed to import image for user ({$user->email}): " . $e->getMessage());
                    continue;
                }
            }
        }
    }

    /**
     * Download remote image and store for a user import. Returns stored path or null.
     */
    private function downloadAndStoreImageUser(string $url, int $userId, string $disk): ?string
    {
        $scheme = parse_url($url, PHP_URL_SCHEME);
        if (!in_array(strtolower($scheme), ['http', 'https'], true)) return null;

        // Prevent SSRF / local network access
        if ($this->isUrlPrivate($url)) {
            return null;
        }

        // Use the UC bot trait to automatically download even protected images
        $response = $this->fetchSecureUcFile($url);
        if (!$response || !$response->ok()) return null;

        $contentType = $response->header('Content-Type') ?? '';
        if (stripos($contentType, 'image/') !== 0) return null;

        $maxBytes = 5 * 1024 * 1024;
        $contentLength = $response->header('Content-Length');
        if ($contentLength !== null && is_numeric($contentLength) && (int)$contentLength > $maxBytes) return null;

        $body = $response->body();
        if (strlen($body) > $maxBytes) return null;

        $basename = basename(parse_url($url, PHP_URL_PATH) ?: 'image');
        $basename = preg_replace('/[^A-Za-z0-9_\-\.]/', '_', $basename);
        if (empty($basename)) $basename = 'image_' . uniqid();

        $path = "imports/users/{$userId}/" . uniqid() . '_' . $basename;
        
        // Use Base64 Data URI for Cloudinary to avoid path/URL parsing issues
        if ($disk === 'cloudinary') {
            $base64 = base64_encode($body);
            $dataUri = "data:{$contentType};base64,{$base64}";
            Storage::disk($disk)->put($path, $dataUri);
        } else {
            Storage::disk($disk)->put($path, $body);
        }
        
        // Save mapping for deduplication
        $this->storeImageMapping($url, $path, $disk);

        // Clean up memory
        unset($body, $base64, $dataUri);
        if (rand(1, 10) === 1) gc_collect_cycles();
        
        return $path;
    }

    /**
     * Basic check to prevent downloading from private IPs or localhost.
     */
    private function isUrlPrivate(string $url): bool
    {
        $host = parse_url($url, PHP_URL_HOST);
        if (empty($host)) return true;

        // quick rejects
        if (in_array(strtolower($host), ['localhost', '127.0.0.1', '::1'])) return true;

        $ips = @gethostbynamel($host) ?: [];
        foreach ($ips as $ip) {
            if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false) {
                // If it's not a public IP, consider it private/reserved
                return true;
            }
        }

        return false;
    }

    /**
     * Validation rules for each row
     */
    public function rules(): array
    {
        return [
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|email', // Duplicate and required checks are handled in model() to gracefully skip
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
