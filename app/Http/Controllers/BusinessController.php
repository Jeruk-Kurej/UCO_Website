<?php

namespace App\Http\Controllers;

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
        
        // Apply search filter
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'LIKE', "%{$search}%");
                  })
                  ->orWhereHas('businessType', function($typeQuery) use ($search) {
                      $typeQuery->where('name', 'LIKE', "%{$search}%");
                  });
            });
        }
        
        // Filter by business type id if provided
        $type = $request->get('type');
        if ($type) {
            $query->where('business_type_id', $type);
        }
        
        // Filter for "My Businesses" if query param present
        if ($request->get('my') && Auth::check()) {
            /** @var User $user */
            $user = Auth::user();
            $query->where(function ($ownerQuery) use ($user) {
                $ownerQuery->where('user_id', $user->id)
                    ->orWhereHas('owners', function ($pivotOwnerQuery) use ($user) {
                        $pivotOwnerQuery->where('users.id', $user->id);
                    });
            });
        }
        
        $businesses = $query->latest()->paginate(12);
        
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
    public function store(Request $request)
    {
        $this->authorize('create', Business::class);

        try {
            $validated = $request->validate([
                // Basic fields
                'name' => 'required|string|max:255',
                'description' => 'required|string|max:1000',
                'business_type_id' => 'required|exists:business_types,id',
                'business_mode' => 'required|in:product,service,both',
                'user_id' => 'nullable|exists:users,id',
                'owner_ids' => 'nullable|array',
                'owner_ids.*' => 'integer|exists:users,id',
                'position' => 'nullable|string|max:255',

                // Location
                'city' => 'nullable|string|max:255',
                'province' => 'nullable|string|max:255|exists:provinces,name',
                'address' => 'nullable|string',

                // Enhanced fields
                'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:10240',
                'established_date' => 'nullable|date',
                'employee_count' => 'nullable|integer|min:0',
                'revenue_range' => 'nullable|in:Mikro: <= Rp 300 Juta,Kecil: > Rp 300 Juta - Rp 2,5 Milyar,Menengah: > Rp 2,5 Milyar - Rp 50 Milyar,Besar: > Rp 50 Milyar',
                'is_from_college_project' => 'nullable|boolean',
                'is_continued_after_graduation' => 'nullable|boolean',
                'legal_document_path' => 'nullable|file|mimes:pdf|max:5120',
                'certification_path' => 'nullable|file|mimes:pdf|max:5120',
                'legal_documents.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
                'product_certifications.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
                'business_challenges' => 'nullable|array',

                // Additional data fields (stored in additional_data JSON)
                'phone' => 'nullable|string|max:50',
                'email' => 'nullable|email|max:255',
                'website' => 'nullable|url|max:255',
                'instagram_handle' => 'nullable|string|max:100',
                'whatsapp_number' => 'nullable|string|max:50',
                'product_name' => 'nullable|string|max:255',
                'product_description' => 'nullable|string|max:2000',
                'unique_value_proposition' => 'nullable|string|max:1000',
                'target_market' => 'nullable|string|max:255',
                'customer_base_size' => 'nullable|integer|min:0',
                'establishment_date' => 'nullable|date',
                'operational_status' => 'nullable|in:active,inactive,seasonal',

                // Inline products/services
                'products' => 'nullable|array',
                'products.*.id' => 'nullable|integer',
                'products.*.name' => 'nullable|string|max:255',
                'products.*.description' => 'nullable|string|max:2000',
                'products.*.price' => 'nullable|numeric|min:0',
                'services' => 'nullable|array',
                'services.*.id' => 'nullable|integer',
                'services.*.name' => 'nullable|string|max:255',
                'services.*.description' => 'nullable|string|max:2000',
                'services.*.price_type' => 'nullable|string|max:255',
                'services.*.price' => 'nullable|numeric|min:0',
            ]);

            if (!empty($validated['city']) && !empty($validated['province'])) {
                $provinceId = Province::where('name', $validated['province'])->value('id');
                $isValidCity = $provinceId
                    ? Regency::where('province_id', $provinceId)->where('name', $validated['city'])->exists()
                    : false;

                if (!$isValidCity) {
                    return back()->withErrors([
                        'city' => 'Selected city does not belong to the selected province.'
                    ])->withInput();
                }
            }

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

                if (!empty($selectedOwnerIds)) {
                    $adminOwnerExists = User::whereIn('id', $selectedOwnerIds)
                        ->where('role', 'admin')
                        ->exists();

                    if ($adminOwnerExists) {
                        return back()->withErrors([
                            'owner_ids' => 'Admin UCO tidak boleh menjadi owner business.'
                        ])->withInput();
                    }
                }

                if (isset($validated['user_id'])) {
                    $ownerUser = User::find($validated['user_id']);
                    if ($ownerUser?->role === 'admin') {
                        return back()->withErrors([
                            'user_id' => 'Admin UCO tidak boleh menjadi owner business.'
                        ])->withInput();
                    }
                }
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
                    if ($file->getSize() > 5120 * 1024) {
                        return back()->withErrors(['legal_documents' => 'Each legal document must not be larger than 5MB.'])->withInput();
                    }
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
                    if ($file->getSize() > 5120 * 1024) {
                        return back()->withErrors(['product_certifications' => 'Each certification file must not be larger than 5MB.'])->withInput();
                    }
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

            $productRows = $this->normalizeInlineRows($request->input('products', []), 'product');
            $serviceRows = $this->normalizeInlineRows($request->input('services', []), 'service');

            $inlineErrors = array_merge(
                $this->validateInlineRows($productRows, 'product'),
                $this->validateInlineRows($serviceRows, 'service')
            );

            if ($validated['business_mode'] === 'service' && !empty($productRows)) {
                $inlineErrors['products'] = 'Business mode Service Only tidak boleh memiliki produk.';
            }
            if ($validated['business_mode'] === 'product' && !empty($serviceRows)) {
                $inlineErrors['services'] = 'Business mode Product Only tidak boleh memiliki layanan.';
            }

            if (!empty($inlineErrors)) {
                return back()->withErrors($inlineErrors)->withInput();
            }

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
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
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
    public function update(Request $request, Business $business)
    {
        $this->authorize('update', $business);

        try {
            $validated = $request->validate([
                // Basic fields
                'name' => 'required|string|max:255',
                'description' => 'required|string|max:1000',
                'business_type_id' => 'required|exists:business_types,id',
                'business_mode' => 'required|in:product,service,both',
                'user_id' => 'nullable|exists:users,id',
                'owner_ids' => 'nullable|array',
                'owner_ids.*' => 'integer|exists:users,id',
                'position' => 'nullable|string|max:255',

                // Location
                'city' => 'nullable|string|max:255',
                'province' => 'nullable|string|max:255|exists:provinces,name',
                'address' => 'nullable|string',

                // Enhanced fields
                'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:10240',
                'established_date' => 'nullable|date',
                'employee_count' => 'nullable|integer|min:0',
                'revenue_range' => 'nullable|in:Mikro: <= Rp 300 Juta,Kecil: > Rp 300 Juta - Rp 2,5 Milyar,Menengah: > Rp 2,5 Milyar - Rp 50 Milyar,Besar: > Rp 50 Milyar',
                'is_from_college_project' => 'nullable|boolean',
                'is_continued_after_graduation' => 'nullable|boolean',
                'legal_document_path' => 'nullable|file|mimes:pdf|max:5120',
                'certification_path' => 'nullable|file|mimes:pdf|max:5120',
                'legal_documents.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
                'product_certifications.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
                'business_challenges' => 'nullable|array',
                'remove_legal_docs' => 'nullable|array',
                'remove_certifications' => 'nullable|array',

                // Additional data fields (stored in additional_data JSON)
                'phone' => 'nullable|string|max:50',
                'email' => 'nullable|email|max:255',
                'website' => 'nullable|url|max:255',
                'instagram_handle' => 'nullable|string|max:100',
                'whatsapp_number' => 'nullable|string|max:50',
                'product_name' => 'nullable|string|max:255',
                'product_description' => 'nullable|string|max:2000',
                'unique_value_proposition' => 'nullable|string|max:1000',
                'target_market' => 'nullable|string|max:255',
                'customer_base_size' => 'nullable|integer|min:0',
                'establishment_date' => 'nullable|date',
                'operational_status' => 'nullable|in:active,inactive,seasonal',

                // Inline products/services
                'products' => 'nullable|array',
                'products.*.id' => 'nullable|integer',
                'products.*.name' => 'nullable|string|max:255',
                'products.*.description' => 'nullable|string|max:2000',
                'products.*.price' => 'nullable|numeric|min:0',
                'services' => 'nullable|array',
                'services.*.id' => 'nullable|integer',
                'services.*.name' => 'nullable|string|max:255',
                'services.*.description' => 'nullable|string|max:2000',
                'services.*.price_type' => 'nullable|string|max:255',
                'services.*.price' => 'nullable|numeric|min:0',
            ]);

            if (!empty($validated['city']) && !empty($validated['province'])) {
                $provinceId = Province::where('name', $validated['province'])->value('id');
                $isValidCity = $provinceId
                    ? Regency::where('province_id', $provinceId)->where('name', $validated['city'])->exists()
                    : false;

                if (!$isValidCity) {
                    return back()->withErrors([
                        'city' => 'Selected city does not belong to the selected province.'
                    ])->withInput();
                }
            }

            $user = $this->getAuthUser();

            // Validate business mode change - prevent breaking changes
            $hasProducts = $business->products()->count() > 0;
            $hasServices = $business->services()->count() > 0;
            
            if ($validated['business_mode'] !== $business->business_mode) {
                // Prevent changing to "service only" if products exist
                if ($validated['business_mode'] === 'service' && $hasProducts) {
                    return back()->withErrors([
                        'business_mode' => 'Cannot change to Service Only while products exist. Delete products first or choose "Product & Service".'
                    ])->withInput();
                }
                
                // Prevent changing to "product only" if services exist
                if ($validated['business_mode'] === 'product' && $hasServices) {
                    return back()->withErrors([
                        'business_mode' => 'Cannot change to Product Only while services exist. Delete services first or choose "Product & Service".'
                    ])->withInput();
                }
            }

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

                if (!empty($selectedOwnerIds)) {
                    $adminOwnerExists = User::whereIn('id', $selectedOwnerIds)
                        ->where('role', 'admin')
                        ->exists();

                    if ($adminOwnerExists) {
                        return back()->withErrors([
                            'owner_ids' => 'Admin UCO tidak boleh menjadi owner business.'
                        ])->withInput();
                    }
                }

                if (isset($validated['user_id'])) {
                    $ownerUser = User::find($validated['user_id']);
                    if ($ownerUser?->role === 'admin') {
                        return back()->withErrors([
                            'user_id' => 'Admin UCO tidak boleh menjadi owner business.'
                        ])->withInput();
                    }
                }
            }

            unset($validated['owner_ids']);

            // Handle logo upload
            if ($request->hasFile('logo')) {
                $logoFile = $request->file('logo');
                if ($logoFile->getSize() > 2048 * 1024) {
                    return back()->withErrors(['logo' => 'Logo must not be larger than 2MB.'])->withInput();
                }
                
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
                    if ($file->getSize() > 5120 * 1024) {
                        return back()->withErrors(['legal_documents' => 'Each legal document must not be larger than 5MB.'])->withInput();
                    }
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
                    if ($file->getSize() > 5120 * 1024) {
                        return back()->withErrors(['product_certifications' => 'Each certification file must not be larger than 5MB.'])->withInput();
                    }
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
            unset($validated['remove_legal_docs'], $validated['remove_certifications']);

            $productRows = $this->normalizeInlineRows($request->input('products', []), 'product');
            $serviceRows = $this->normalizeInlineRows($request->input('services', []), 'service');

            $inlineErrors = array_merge(
                $this->validateInlineRows($productRows, 'product'),
                $this->validateInlineRows($serviceRows, 'service')
            );

            if ($validated['business_mode'] === 'service' && !empty($productRows)) {
                $inlineErrors['products'] = 'Business mode Service Only tidak boleh memiliki produk.';
            }
            if ($validated['business_mode'] === 'product' && !empty($serviceRows)) {
                $inlineErrors['services'] = 'Business mode Product Only tidak boleh memiliki layanan.';
            }

            if (!empty($inlineErrors)) {
                return back()->withErrors($inlineErrors)->withInput();
            }

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
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
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
     * Normalize rows from inline product/service form arrays.
     *
     * @param array<int, array<string, mixed>>|mixed $rows
     * @return array<int, array<string, mixed>>
     */
    private function normalizeInlineRows($rows, string $type): array
    {
        if (!is_array($rows)) {
            return [];
        }

        $normalized = [];

        foreach ($rows as $row) {
            if (!is_array($row)) {
                continue;
            }

            $name = isset($row['name']) ? trim((string) $row['name']) : '';
            $description = isset($row['description']) ? trim((string) $row['description']) : '';
            $priceRaw = $row['price'] ?? null;
            $price = ($priceRaw === '' || $priceRaw === null) ? null : $priceRaw;
            $id = isset($row['id']) && is_numeric($row['id']) ? (int) $row['id'] : null;

            $isFilled = $name !== '' || $description !== '' || $price !== null;
            if ($type === 'service') {
                $priceType = isset($row['price_type']) ? trim((string) $row['price_type']) : '';
                $isFilled = $isFilled || $priceType !== '';
            }

            if (!$isFilled) {
                continue;
            }

            $payload = [
                'id' => $id,
                'name' => $name,
                'description' => $description,
                'price' => $price,
            ];

            if ($type === 'service') {
                $payload['price_type'] = isset($row['price_type']) ? trim((string) $row['price_type']) : '';
            }

            $normalized[] = $payload;
        }

        return $normalized;
    }

    /**
     * @param array<int, array<string, mixed>> $rows
     * @return array<string, string>
     */
    private function validateInlineRows(array $rows, string $type): array
    {
        $errors = [];

        foreach ($rows as $index => $row) {
            if (empty($row['name'])) {
                $errors["{$type}s.{$index}.name"] = ucfirst($type) . ' #' . ($index + 1) . ': nama wajib diisi.';
            }

            if (empty($row['description'])) {
                $errors["{$type}s.{$index}.description"] = ucfirst($type) . ' #' . ($index + 1) . ': deskripsi wajib diisi.';
            }

            if ($row['price'] === null || $row['price'] === '') {
                $errors["{$type}s.{$index}.price"] = ucfirst($type) . ' #' . ($index + 1) . ': harga wajib diisi.';
            }

            if ($type === 'service' && empty($row['price_type'])) {
                $errors["services.{$index}.price_type"] = 'Service #' . ($index + 1) . ': tipe harga wajib diisi.';
            }
        }

        return $errors;
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
