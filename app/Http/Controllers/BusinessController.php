<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\User;
use App\Models\BusinessType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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
        $query = Business::with(['user', 'businessType', 'products', 'photos']);
        
        // Filter for "My Businesses" if query param present
        if ($request->get('my') && Auth::check()) {
            /** @var User $user */
            $user = Auth::user();
            $query->where('user_id', $user->id);
        }
        
        $businesses = $query->latest()->paginate(15);
        return view('businesses.index', compact('businesses'));
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

        $validated = $request->validate([
            // Basic fields
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'business_type_id' => 'required|exists:business_types,id',
            'business_mode' => 'required|in:product,service',
            'user_id' => 'nullable|exists:users,id',
            
            // Enhanced fields
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
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

        return view('businesses.edit', compact('business', 'businessTypes', 'users'));
    }

    /**
     * Update the specified business in storage.
     */
    public function update(Request $request, Business $business)
    {
        $this->authorize('update', $business);

        $validated = $request->validate([
            // Basic fields
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'business_type_id' => 'required|exists:business_types,id',
            'business_mode' => 'required|in:product,service',
            'user_id' => 'nullable|exists:users,id',
            
            // Enhanced fields
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
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

        // Validate business mode change
        $hasProducts = $business->products()->count() > 0;
        $hasServices = $business->services()->count() > 0;
        
        if ($validated['business_mode'] !== $business->business_mode) {
            if ($hasProducts || $hasServices) {
                return back()->withErrors([
                    'business_mode' => 'Cannot change business mode while products or services exist. Delete them first.'
                ])->withInput();
            }
        }

        // Only admin can change user_id
        if (!$user->isAdmin()) {
            unset($validated['user_id']);
        }

        // Handle logo upload
        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($business->logo_url && Storage::disk('public')->exists($business->logo_url)) {
                Storage::disk('public')->delete($business->logo_url);
            }
            $logoPath = $request->file('logo')->store('businesses/logos', 'public');
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
}
