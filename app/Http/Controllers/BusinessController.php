<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\User;
use App\Models\BusinessType;
use App\Imports\BusinessesImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
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
        
        // Filter for "My Businesses" if query param present
        if ($request->get('my') && Auth::check()) {
            /** @var User $user */
            $user = Auth::user();
            $query->where('user_id', $user->id);
        }
        
        $businesses = $query->latest()->paginate(15);
        
        // Prepare my businesses for current user
        $myBusinesses = collect();
        if (Auth::check()) {
            $myBusinesses = $businesses->where('user_id', Auth::id());
        }
        
        return view('businesses.index', compact('businesses', 'myBusinesses'));
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

        // If admin, allow selecting user
        $users = null;
        if ($user->isAdmin()) {
            $users = User::whereIn('role', ['student', 'alumni', 'admin'])->get();
        }

        return view('businesses.create', compact('businessTypes', 'users'));
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
                'description' => 'required|string',
                'business_type_id' => 'required|exists:business_types,id',
                'business_mode' => 'required|in:product,service',
                'user_id' => 'nullable|exists:users,id',
                'position' => 'nullable|string|max:255',
                
                // Enhanced fields
                'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:10240',
                'established_date' => 'nullable|date',
                'address' => 'nullable|string',
                'employee_count' => 'nullable|integer|min:0',
                'revenue_range' => 'nullable|in:Mikro: <= Rp 300 Juta,Kecil: > Rp 300 Juta - Rp 2,5 Milyar,Menengah: > Rp 2,5 Milyar - Rp 50 Milyar,Besar: > Rp 50 Milyar',
                'is_from_college_project' => 'nullable|boolean',
                'is_continued_after_graduation' => 'nullable|boolean',
                'legal_documents.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
                'product_certifications.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
                'business_challenges' => 'nullable|array',
            ]);

            $user = $this->getAuthUser();

            // Automatically set user_id to current user unless admin specifies
            if (!isset($validated['user_id']) || !$user->isAdmin()) {
                $validated['user_id'] = $user->id;
            }

            // Handle logo upload
            if ($request->hasFile('logo')) {
                $logoPath = $request->file('logo')->store('businesses/logos', 'public');
                $validated['logo_url'] = $logoPath;
            }
            unset($validated['logo']);

            // Handle legal documents upload
            $legalDocs = [];
            if ($request->hasFile('legal_documents')) {
                foreach ($request->file('legal_documents') as $index => $file) {
                    if ($file->getSize() > 5120 * 1024) {
                        return back()->withErrors(['legal_documents' => 'Each legal document must not be larger than 5MB.'])->withInput();
                    }
                    $path = $file->store('businesses/legal-documents', 'public');
                    $legalDocs[] = [
                        'file_path' => $path,
                        'original_name' => $file->getClientOriginalName(),
                        'uploaded_at' => now()->toDateTimeString(),
                    ];
                }
            }
            $validated['legal_documents'] = !empty($legalDocs) ? $legalDocs : null;

            // Handle product certifications upload
            $certifications = [];
            if ($request->hasFile('product_certifications')) {
                foreach ($request->file('product_certifications') as $index => $file) {
                    if ($file->getSize() > 5120 * 1024) {
                        return back()->withErrors(['product_certifications' => 'Each certification file must not be larger than 5MB.'])->withInput();
                    }
                    $path = $file->store('businesses/certifications', 'public');
                    $certifications[] = [
                        'file_path' => $path,
                        'original_name' => $file->getClientOriginalName(),
                        'uploaded_at' => now()->toDateTimeString(),
                    ];
                }
            }
            $validated['product_certifications'] = !empty($certifications) ? $certifications : null;

            $business = Business::create($validated);

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
            'businessType.productCategories.products.photos',
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

        // If admin, allow changing owner
        $users = null;
        if ($user->isAdmin()) {
            $users = User::whereIn('role', ['student', 'alumni', 'admin'])->get();
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

        return view('businesses.edit', compact('business', 'businessTypes', 'users', 'legalDocs', 'certifications', 'challenges', 'hasProducts', 'hasServices', 'canChangeMode'));
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
                'description' => 'required|string',
                'business_type_id' => 'required|exists:business_types,id',
                'business_mode' => 'required|in:product,service,both',
                'user_id' => 'nullable|exists:users,id',
                'position' => 'nullable|string|max:255',
                
                // Enhanced fields
                'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:10240',
                'established_date' => 'nullable|date',
                'address' => 'nullable|string',
                'employee_count' => 'nullable|integer|min:0',
                'revenue_range' => 'nullable|in:Mikro: <= Rp 300 Juta,Kecil: > Rp 300 Juta - Rp 2,5 Milyar,Menengah: > Rp 2,5 Milyar - Rp 50 Milyar,Besar: > Rp 50 Milyar',
                'is_from_college_project' => 'nullable|boolean',
                'is_continued_after_graduation' => 'nullable|boolean',
                'legal_documents.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
                'product_certifications.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
                'business_challenges' => 'nullable|array',
                'remove_legal_docs' => 'nullable|array',
                'remove_certifications' => 'nullable|array',
            ]);

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

            // Handle logo upload
            if ($request->hasFile('logo')) {
                $logoFile = $request->file('logo');
                if ($logoFile->getSize() > 2048 * 1024) {
                    return back()->withErrors(['logo' => 'Logo must not be larger than 2MB.'])->withInput();
                }
                
                // Delete old logo if exists
                if ($business->logo_url && Storage::disk('public')->exists($business->logo_url)) {
                    Storage::disk('public')->delete($business->logo_url);
                }
                $logoPath = $logoFile->store('businesses/logos', 'public');
                $validated['logo_url'] = $logoPath;
            }
            unset($validated['logo']);

            // Handle legal documents
            $currentLegalDocs = $business->legal_documents ?? [];
            
            // Remove selected documents
            if ($request->has('remove_legal_docs')) {
                foreach ($request->remove_legal_docs as $index) {
                    if (isset($currentLegalDocs[$index]['file_path'])) {
                        Storage::disk('public')->delete($currentLegalDocs[$index]['file_path']);
                        unset($currentLegalDocs[$index]);
                    }
                }
                $currentLegalDocs = array_values($currentLegalDocs); // Re-index array
            }
            
            // Add new documents
            if ($request->hasFile('legal_documents')) {
                foreach ($request->file('legal_documents') as $file) {
                    if ($file->getSize() > 5120 * 1024) {
                        return back()->withErrors(['legal_documents' => 'Each legal document must not be larger than 5MB.'])->withInput();
                    }
                    $path = $file->store('businesses/legal-documents', 'public');
                    $currentLegalDocs[] = [
                        'file_path' => $path,
                        'original_name' => $file->getClientOriginalName(),
                        'uploaded_at' => now()->toDateTimeString(),
                    ];
                }
            }
            $validated['legal_documents'] = !empty($currentLegalDocs) ? $currentLegalDocs : null;

            // Handle product certifications
            $currentCertifications = $business->product_certifications ?? [];
            
            // Remove selected certifications
            if ($request->has('remove_certifications')) {
                foreach ($request->remove_certifications as $index) {
                    if (isset($currentCertifications[$index]['file_path'])) {
                        Storage::disk('public')->delete($currentCertifications[$index]['file_path']);
                        unset($currentCertifications[$index]);
                    }
                }
                $currentCertifications = array_values($currentCertifications); // Re-index array
            }
            
            // Add new certifications
            if ($request->hasFile('product_certifications')) {
                foreach ($request->file('product_certifications') as $file) {
                    if ($file->getSize() > 5120 * 1024) {
                        return back()->withErrors(['product_certifications' => 'Each certification file must not be larger than 5MB.'])->withInput();
                    }
                    $path = $file->store('businesses/certifications', 'public');
                    $currentCertifications[] = [
                        'file_path' => $path,
                        'original_name' => $file->getClientOriginalName(),
                        'uploaded_at' => now()->toDateTimeString(),
                    ];
                }
            }
            $validated['product_certifications'] = !empty($currentCertifications) ? $currentCertifications : null;

            // Remove these from validated array
            unset($validated['remove_legal_docs'], $validated['remove_certifications']);

            $business->update($validated);

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
}
