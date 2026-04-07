<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBusinessRequest;
use App\Http\Requests\UpdateBusinessRequest;
use App\Models\Business;
use App\Models\User;
use App\Models\BusinessType;
use App\Models\Province;
use App\Models\Regency;
use App\Imports\BusinessesImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class BusinessController extends Controller
{
    /**
     * Get authenticated user as User instance
     */
    private function getAuthUser(): User
    {
        /** @var User $user */
        $user = Auth::user();
        
        if (!$user) {
            abort(401, 'Unauthenticated.');
        }
        
        return $user;
    }

    /**
     * Display a listing of the businesses.
     * Public access.
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        $query = Business::with(['user', 'businessType', 'products', 'photos']);
        
        // Optimize search filtering via JOIN instead of slow correlated Subqueries (orWhereHas)
        if ($search) {
            $query->select('businesses.*')
                  ->leftJoin('users', 'businesses.user_id', '=', 'users.id')
                  ->leftJoin('business_types', 'businesses.business_type_id', '=', 'business_types.id')
                  ->where(function($q) use ($search) {
                      $q->where('businesses.name', 'LIKE', "%{$search}%")
                        ->orWhere('businesses.description', 'LIKE', "%{$search}%")
                        ->orWhere('users.name', 'LIKE', "%{$search}%")
                        ->orWhere('business_types.name', 'LIKE', "%{$search}%");
                  });
        }
        
        // Filter by business type id if provided
        $type = $request->get('type');
        if ($type) {
            $query->where('businesses.business_type_id', $type);
        }
        
        // Filter for "My Businesses" if query param present
        if (($request->get('tab') === 'my' || $request->get('my')) && Auth::check()) {
            /** @var User $user */
            $user = Auth::user();
            $query->where(function ($ownerQuery) use ($user) {
                $ownerQuery->where('businesses.user_id', $user->id)
                    ->orWhereHas('owners', function ($pivotOwnerQuery) use ($user) {
                        $pivotOwnerQuery->where('users.id', $user->id);
                    });
            });
        }
        
        $businesses = $query->orderBy('businesses.is_featured', 'desc')->orderBy('businesses.created_at', 'desc')->paginate(12);
        
        // Prepare my businesses for current user - independent of pagination
        $myBusinesses = collect();
        if (Auth::check()) {
            /** @var User $user */
            $user = Auth::user();
            $myBusinesses = Business::with(['businessType', 'photos'])
                ->where(function ($ownerQuery) use ($user) {
                    $ownerQuery->where('user_id', $user->id)
                        ->orWhereHas('owners', function ($pivotOwnerQuery) use ($user) {
                            $pivotOwnerQuery->where('users.id', $user->id);
                        });
                })
                ->latest()
                ->get();
        }
        
        // Also load business types for the filter bar
        $businessTypes = BusinessType::all();

        return view('businesses.index', compact('businesses', 'myBusinesses', 'businessTypes'));
    }

    /**
     * Show the form for creating a new business.
     * Requires authentication.
     */
    public function create()
    {
        $this->authorize('create', Business::class);

        $user = $this->getAuthUser();

        // Fetch all business types
        $businessTypes = BusinessType::all();
        $provinces = Province::orderBy('name')->get(['id', 'name']);

        // If admin, allow selecting user
        $users = null;
        if ($user->isAdmin()) {
            $users = User::whereIn('role', ['student', 'alumni'])->get();
        }

        return view('businesses.create', compact('businessTypes', 'users', 'provinces'));
    }

    /**
     * Store a newly created business in storage.
     */
    public function store(StoreBusinessRequest $request)
    {
        try {
            $validated = $request->validated();
            $user = $this->getAuthUser();

            // Automatically set user_id to current user unless admin specifies
            if (!isset($validated['user_id']) || !$user->isAdmin()) {
                $validated['user_id'] = $user->id;
            }

            $selectedOwnerIds = [];
            if ($user->isAdmin()) {
                $selectedOwnerIds = collect($request->input('owner_ids', []))
                    ->map(fn ($id) => (int) $id)
                    ->filter(fn ($id) => $id > 0)
                    ->unique()
                    ->values()
                    ->all();
            }

            unset($validated['owner_ids']);

            // Handle logo upload
            if ($request->hasFile('logo')) {
                $logoFile = $request->file('logo');
                $businessSlug = Str::slug($validated['name'], '_');
                $logoFilename = $businessSlug . '_logo_' . time() . '.' . $logoFile->getClientOriginalExtension();
                $logoPath = $logoFile->storeAs('businesses/logos', $logoFilename, config('filesystems.default'));
                $validated['logo_url'] = $logoPath;
            }
            unset($validated['logo']);

            // Handle legal documents (array) upload
            $legalDocs = [];
            if ($request->hasFile('legal_documents')) {
                $businessSlug = $businessSlug ?? Str::slug($validated['name'], '_');
                foreach ($request->file('legal_documents') as $index => $file) {
                    $docNumber = $index + 1;
                    $docFilename = $businessSlug . '_legal_' . $docNumber . '_' . time() . '.' . $file->getClientOriginalExtension();
                    $path = $file->storeAs('businesses/legal-documents', $docFilename, config('filesystems.default'));
                    $legalDocs[] = [
                        'file_path' => $path,
                        'original_name' => $file->getClientOriginalName(),
                        'uploaded_at' => now()->toDateTimeString(),
                    ];
                }
            }
            $validated['legal_documents'] = !empty($legalDocs) ? $legalDocs : null;

            // Handle legal document path (single PDF)
            if ($request->hasFile('legal_document_path')) {
                $docPath = $request->file('legal_document_path')->store('businesses/documents', 'public');
                $validated['legal_document_path'] = $docPath;
            }

            // Handle product certifications (array) upload
            $certifications = [];
            if ($request->hasFile('product_certifications')) {
                $businessSlug = $businessSlug ?? Str::slug($validated['name'], '_');
                foreach ($request->file('product_certifications') as $index => $file) {
                    $certNumber = $index + 1;
                    $certFilename = $businessSlug . '_cert_' . $certNumber . '_' . time() . '.' . $file->getClientOriginalExtension();
                    $path = $file->storeAs('businesses/certifications', $certFilename, config('filesystems.default'));
                    $certifications[] = [
                        'file_path' => $path,
                        'original_name' => $file->getClientOriginalName(),
                        'uploaded_at' => now()->toDateTimeString(),
                    ];
                }
            }
            $validated['product_certifications'] = !empty($certifications) ? $certifications : null;

            // Handle certification path (single PDF)
            if ($request->hasFile('certification_path')) {
                $certPath = $request->file('certification_path')->store('businesses/documents', 'public');
                $validated['certification_path'] = $certPath;
            }

            $productRows = $validated['products'] ?? [];
            $serviceRows = $validated['services'] ?? [];
            unset($validated['products'], $validated['services']);

            $business = Business::create($validated);

            $this->syncOwnerUsers($business, $selectedOwnerIds, false);

            if (in_array($validated['business_mode'], ['product', 'both'], true)) {
                $this->syncInlineProducts($business, $productRows, false);
            }

            if (in_array($validated['business_mode'], ['service', 'both'], true)) {
                $this->syncInlineServices($business, $serviceRows, false);
            }

            return redirect()
                ->route('businesses.show', $business)
                ->with('success', 'Business created successfully!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'An error occurred while creating the business. Please try again.'])->withInput();
        }
    }

    /**
     * Display the specified business.
     * Public access.
     */
    public function show(Business $business)
    {
        $this->authorize('view', $business);

        $business->load([
            'user',
            'businessType',
            'products.productCategory',
            'products.photos',
            'services',
            'photos',
            'contacts.contactType'
        ]);

        return view('businesses.show', compact('business'));
    }

    /**
     * Show the form for editing the specified business.
     */
    public function edit(Business $business)
    {
        $this->authorize('update', $business);

        $user = $this->getAuthUser();

        // Fetch all business types
        $businessTypes = BusinessType::all();
        $provinces = Province::orderBy('name')->get(['id', 'name']);

        // If admin, allow changing owner
        $users = null;
        if ($user->isAdmin()) {
            $users = User::whereIn('role', ['student', 'alumni'])->get();
        }

        // Decode JSON fields
        $legalDocs = $business->legal_documents;
        if (is_string($legalDocs)) {
            $legalDocs = json_decode($legalDocs, true) ?? [];
        }
        $legalDocs = $legalDocs ?? [];

        $certifications = $business->product_certifications;
        if (is_string($certifications)) {
            $certifications = json_decode($certifications, true) ?? [];
        }
        $certifications = $certifications ?? [];

        $challenges = $business->business_challenges;
        if (is_string($challenges)) {
            $challenges = json_decode($challenges, true) ?? [];
        }
        $challenges = $challenges ?? [];

        $hasProducts = $business->products()->count() > 0;
        $hasServices = $business->services()->count() > 0;
        $canChangeMode = !($hasProducts || $hasServices);

        return view('businesses.edit', compact('business', 'businessTypes', 'users', 'provinces', 'legalDocs', 'certifications', 'challenges', 'hasProducts', 'hasServices', 'canChangeMode'));
    }

    /**
     * Update the specified business in storage.
     */
    public function update(UpdateBusinessRequest $request, Business $business)
    {
        try {
            $validated = $request->validated();
            $user = $this->getAuthUser();

            // Only admin can change user_id
            if (!$user->isAdmin()) {
                unset($validated['user_id']);
            }

            $selectedOwnerIds = [];
            if ($user->isAdmin()) {
                $selectedOwnerIds = collect($request->input('owner_ids', []))
                    ->map(fn ($id) => (int) $id)
                    ->filter(fn ($id) => $id > 0)
                    ->unique()
                    ->values()
                    ->all();
            }

            unset($validated['owner_ids']);

            // Handle logo upload
            if ($request->hasFile('logo')) {
                $logoFile = $request->file('logo');
                // Delete old logo if exists
                if ($business->logo_url && Storage::disk(config('filesystems.default'))->exists($business->logo_url)) {
                    Storage::disk(config('filesystems.default'))->delete($business->logo_url);
                }
                $businessSlug = Str::slug($business->name, '_');
                $logoFilename = $businessSlug . '_logo_' . time() . '.' . $logoFile->getClientOriginalExtension();
                $logoPath = $logoFile->storeAs('businesses/logos', $logoFilename, config('filesystems.default'));
                $validated['logo_url'] = $logoPath;
            }
            unset($validated['logo']);

            // Handle legal documents (array)
            $currentLegalDocs = $business->legal_documents ?? [];
            
            // Remove selected documents
            if ($request->has('remove_legal_docs')) {
                foreach ($request->remove_legal_docs as $index) {
                    if (isset($currentLegalDocs[$index]['file_path'])) {
                        Storage::disk(config('filesystems.default'))->delete($currentLegalDocs[$index]['file_path']);
                        unset($currentLegalDocs[$index]);
                    }
                }
                $currentLegalDocs = array_values($currentLegalDocs); // Re-index array
            }
            
            // Add new documents
            if ($request->hasFile('legal_documents')) {
                $businessSlug = Str::slug($business->name, '_');
                foreach ($request->file('legal_documents') as $file) {
                    $docNumber = count($currentLegalDocs) + 1;
                    $docFilename = $businessSlug . '_legal_' . $docNumber . '_' . time() . '.' . $file->getClientOriginalExtension();
                    $path = $file->storeAs('businesses/legal-documents', $docFilename, config('filesystems.default'));
                    $currentLegalDocs[] = [
                        'file_path' => $path,
                        'original_name' => $file->getClientOriginalName(),
                        'uploaded_at' => now()->toDateTimeString(),
                    ];
                }
            }
            $validated['legal_documents'] = !empty($currentLegalDocs) ? $currentLegalDocs : null;

            // Handle legal_document_path (single PDF)
            if ($request->hasFile('legal_document_path')) {
                // Delete old document if exists
                if ($business->legal_document_path && Storage::disk('public')->exists($business->legal_document_path)) {
                    Storage::disk('public')->delete($business->legal_document_path);
                }
                $docPath = $request->file('legal_document_path')->store('businesses/documents', 'public');
                $validated['legal_document_path'] = $docPath;
            } elseif ($request->boolean('remove_legal_document')) {
                if ($business->legal_document_path && Storage::disk('public')->exists($business->legal_document_path)) {
                    Storage::disk('public')->delete($business->legal_document_path);
                }
                $validated['legal_document_path'] = null;
            }

            // Handle product certifications (array)
            $currentCertifications = $business->product_certifications ?? [];
            
            // Remove selected certifications
            if ($request->has('remove_certifications')) {
                foreach ($request->remove_certifications as $index) {
                    if (isset($currentCertifications[$index]['file_path'])) {
                        Storage::disk(config('filesystems.default'))->delete($currentCertifications[$index]['file_path']);
                        unset($currentCertifications[$index]);
                    }
                }
                $currentCertifications = array_values($currentCertifications); // Re-index array
            }
            
            // Add new certifications
            if ($request->hasFile('product_certifications')) {
                $businessSlug = $businessSlug ?? Str::slug($business->name, '_');
                foreach ($request->file('product_certifications') as $file) {
                    $certNumber = count($currentCertifications) + 1;
                    $certFilename = $businessSlug . '_cert_' . $certNumber . '_' . time() . '.' . $file->getClientOriginalExtension();
                    $path = $file->storeAs('businesses/certifications', $certFilename, config('filesystems.default'));
                    $currentCertifications[] = [
                        'file_path' => $path,
                        'original_name' => $file->getClientOriginalName(),
                        'uploaded_at' => now()->toDateTimeString(),
                    ];
                }
            }
            $validated['product_certifications'] = !empty($currentCertifications) ? $currentCertifications : null;

            // Handle certification_path (single PDF)
            if ($request->hasFile('certification_path')) {
                // Delete old certification if exists
                if ($business->certification_path && Storage::disk('public')->exists($business->certification_path)) {
                    Storage::disk('public')->delete($business->certification_path);
                }
                $certPath = $request->file('certification_path')->store('businesses/documents', 'public');
                $validated['certification_path'] = $certPath;
            } elseif ($request->boolean('remove_certification')) {
                if ($business->certification_path && Storage::disk('public')->exists($business->certification_path)) {
                    Storage::disk('public')->delete($business->certification_path);
                }
                $validated['certification_path'] = null;
            }

            // Remove these from validated array
            unset($validated['remove_legal_docs'], $validated['remove_certifications'], $validated['remove_legal_document'], $validated['remove_certification']);

            $productRows = $validated['products'] ?? [];
            $serviceRows = $validated['services'] ?? [];
            unset($validated['products'], $validated['services']);

            // Move extra fields into additional_data JSON (merge with existing)
            $additionalDataKeys = ['phone', 'email', 'website', 'instagram_handle', 'whatsapp_number',
                'product_name', 'product_description', 'unique_value_proposition', 'target_market',
                'customer_base_size', 'establishment_date', 'operational_status'];
            $additionalData = $business->additional_data ?? [];
            foreach ($additionalDataKeys as $key) {
                if (array_key_exists($key, $validated)) {
                    $additionalData[$key] = $validated[$key];
                    unset($validated[$key]);
                }
            }
            $validated['additional_data'] = $additionalData;

            $business->update($validated);

            $this->syncOwnerUsers($business, $selectedOwnerIds, true);

            if (in_array($validated['business_mode'], ['product', 'both'], true)) {
                $this->syncInlineProducts($business, $productRows, true);
            }

            if (in_array($validated['business_mode'], ['service', 'both'], true)) {
                $this->syncInlineServices($business, $serviceRows, true);
            }

            return redirect()
                ->route('businesses.show', $business)
                ->with('success', 'Business updated successfully!');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Update error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return back()->withErrors(['error' => 'An error occurred while updating the business. Please try again.'])->withInput();
        }
    }

    /**
     * Toggle featured status for a business (Admin only).
     */
    public function toggleFeatured(Business $business)
    {
        $user = $this->getAuthUser();
        
        if (!$user->isAdmin()) {
            abort(403, 'Only administrators can feature businesses.');
        }

        $business->is_featured = !$business->is_featured;
        $business->save();

        $status = $business->is_featured ? 'featured' : 'unfeatured';
        
        return response()->json([
            'success' => true,
            'is_featured' => $business->is_featured,
            'message' => "Business successfully {$status}!"
        ]);
    }

    /**
     * Remove the specified business from storage.
     */
    public function destroy(Business $business)
    {
        $this->authorize('delete', $business);

        $business->delete();

        return redirect()
            ->route('businesses.index')
            ->with('success', 'Business deleted successfully!');
    }

    /**
     * Download Excel template for business import
     */
    public function downloadTemplate(Request $request)
    {
        $user = $this->getAuthUser();
        if (!$user->isAdmin()) {
            abort(403, 'Only administrators can download templates.');
        }

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set Headers
        $headers = [
            'A1' => 'owner_email',
            'B1' => 'business_name',
            'C1' => 'business_type',
            'D1' => 'business_mode',
            'E1' => 'description',
            'F1' => 'established_date',
            'G1' => 'employee_count',
            'H1' => 'revenue_range',
            'I1' => 'address',
            'J1' => 'province',
            'K1' => 'city',
            'L1' => 'is_featured',
            'M1' => 'is_active',
        ];

        foreach ($headers as $cell => $value) {
            $sheet->setCellValue($cell, $value);
            $sheet->getStyle($cell)->getFont()->setBold(true);
            $sheet->getColumnDimension(substr($cell, 0, 1))->setAutoSize(true);
        }

        // Set Sample Data
        $sheet->setCellValue('A2', 'student@example.com');
        $sheet->setCellValue('B2', 'My Cool Business');
        $sheet->setCellValue('C2', 'Technology');
        $sheet->setCellValue('D2', 'product');
        $sheet->setCellValue('E2', 'A tech startup focusing on cool software products.');
        $sheet->setCellValue('F2', '2025-01-01');
        $sheet->setCellValue('G2', '5');
        $sheet->setCellValue('H2', 'Mikro: <= Rp 300 Juta');
        $sheet->setCellValue('I2', 'Jl. Contoh 123');
        $sheet->setCellValue('J2', 'Jawa Timur');
        $sheet->setCellValue('K2', 'Surabaya');
        $sheet->setCellValue('L2', '1');
        $sheet->setCellValue('M2', '1');

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $fileName = 'businesses_import_template_' . date('Y-m-d') . '.xlsx';
        $tempFile = tempnam(sys_get_temp_dir(), 'excel');
        $writer->save($tempFile);

        return response()->download($tempFile, $fileName)->deleteFileAfterSend(true);
    }

    /**
     * Import businesses from Excel file
     */
    public function import(Request $request)
    {
        $user = $this->getAuthUser();

        // Only admins can import businesses
        if (!$user->isAdmin()) {
            return back()->withErrors(['error' => 'Only administrators can import businesses.']);
        }

        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:10240', // Max 10MB
        ]);

        // Increase execution time and memory limit for large imports
        set_time_limit(300); // 5 minutes
        ini_set('memory_limit', '512M');

        try {
            $import = new BusinessesImport();
            Excel::import($import, $request->file('file'));

            $results = $import->getResults();

            $message = "Import completed! Success: {$results['success']}, Skipped: {$results['skipped']}";
            
            if (!empty($results['errors'])) {
                $message .= ". Errors: " . count($results['errors']);
                
                // Log detailed errors for debugging
                foreach ($results['errors'] as $error) {
                    Log::error("Business import error: " . $error);
                }
                
                // Show first few errors to user
                $errorMessages = array_slice($results['errors'], 0, 5);
                return back()->with('success', $message)
                    ->with('import_errors', $errorMessages);
            }

            return back()->with('success', $message);

        } catch (\Exception $e) {
            Log::error('Business import exception: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Error importing file: ' . $e->getMessage()]);
        }
    }

    /**
     * Return regencies by province for dependent selects.
     */
    public function regenciesByProvince(Request $request)
    {
        $request->validate([
            'province_id' => 'required|exists:provinces,id',
        ]);

        $regencies = Regency::where('province_id', $request->integer('province_id'))
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json($regencies);
    }



    /**
     * @param array<int, array<string, mixed>> $rows
     */
    private function syncInlineProducts(Business $business, array $rows, bool $isUpdate): void
    {
        $defaultCategory = $business->productCategories()->firstOrCreate([
            'name' => 'Umum',
        ]);

        $keptIds = [];

        foreach ($rows as $row) {
            $existing = null;
            if ($isUpdate && !empty($row['id'])) {
                $existing = $business->products()->where('id', (int) $row['id'])->first();
            }

            if ($existing) {
                $existing->update([
                    'name' => (string) $row['name'],
                    'description' => (string) $row['description'],
                    'price' => $row['price'],
                ]);
                $keptIds[] = $existing->id;
            } else {
                $product = $business->products()->create([
                    'product_category_id' => $defaultCategory->id,
                    'name' => (string) $row['name'],
                    'description' => (string) $row['description'],
                    'price' => $row['price'],
                ]);
                $keptIds[] = $product->id;
            }
        }

        if ($isUpdate) {
            if (!empty($keptIds)) {
                $business->products()->whereNotIn('id', $keptIds)->delete();
            } else {
                $business->products()->delete();
            }
        }
    }

    /**
     * @param array<int, array<string, mixed>> $rows
     */
    private function syncInlineServices(Business $business, array $rows, bool $isUpdate): void
    {
        $keptIds = [];

        foreach ($rows as $row) {
            $existing = null;
            if ($isUpdate && !empty($row['id'])) {
                $existing = $business->services()->where('id', (int) $row['id'])->first();
            }

            if ($existing) {
                $existing->update([
                    'name' => (string) $row['name'],
                    'description' => (string) $row['description'],
                    'price_type' => (string) $row['price_type'],
                    'price' => $row['price'],
                ]);
                $keptIds[] = $existing->id;
            } else {
                $service = $business->services()->create([
                    'name' => (string) $row['name'],
                    'description' => (string) $row['description'],
                    'price_type' => (string) $row['price_type'],
                    'price' => $row['price'],
                ]);
                $keptIds[] = $service->id;
            }
        }

        if ($isUpdate) {
            if (!empty($keptIds)) {
                $business->services()->whereNotIn('id', $keptIds)->delete();
            } else {
                $business->services()->delete();
            }
        }
    }

    /**
     * Sync owners in user_businesses_details while keeping user_id as primary owner.
     *
     * @param array<int, int> $selectedOwnerIds
     */
    private function syncOwnerUsers(Business $business, array $selectedOwnerIds, bool $isUpdate): void
    {
        $primaryOwnerId = (int) $business->user_id;

        $ownerIds = collect($selectedOwnerIds)
            ->push($primaryOwnerId)
            ->filter(fn ($id) => (int) $id > 0)
            ->unique()
            ->values();

        $syncPayload = [];
        foreach ($ownerIds as $ownerId) {
            $syncPayload[(int) $ownerId] = [
                'role_type' => 'owner',
                'is_current' => true,
            ];
        }

        if ($isUpdate) {
            $business->teamMembers()->wherePivot('role_type', 'owner')->detach();
        }

        if (!empty($syncPayload)) {
            $business->teamMembers()->syncWithoutDetaching($syncPayload);
        }
    }
}
