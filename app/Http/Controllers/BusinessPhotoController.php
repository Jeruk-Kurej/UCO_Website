<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\BusinessPhoto;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BusinessPhotoController extends Controller
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
     * Check if user can manage business
     */
    private function authorizeBusinessAccess(Business $business): void
    {
        $user = $this->getAuthUser();
        
        if ($business->user_id !== $user->id && !$user->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }
    }

    /**
     * Display a listing of photos for a business.
     */
    public function index(Business $business)
    {
        $photos = $business->photos()->latest()->get();

        return view('business-photos.index', compact('business', 'photos'));
    }

    /**
     * Show the form for creating a new photo.
     */
    public function create(Business $business)
    {
        $this->authorizeBusinessAccess($business);

        return view('business-photos.create', compact('business'));
    }

    /**
     * Store a newly created photo in storage.
     */
    public function store(Request $request, Business $business)
    {
        $this->authorizeBusinessAccess($business);

        $validated = $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'caption' => 'nullable|string|max:255',
        ]);

        // Handle file upload
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            
            // ✅ FIXED: Hierarchical folder structure
            $path = $file->storeAs(
                "businesses/{$business->id}/photos",
                $filename,
                'public'
            );
            
            $validated['photo_url'] = $path;
        }

        $validated['business_id'] = $business->id;

        $photo = BusinessPhoto::create($validated);

        return redirect()
            ->route('businesses.photos.index', $business)
            ->with('success', 'Photo uploaded successfully!');
    }

    /**
     * Display the specified photo.
     */
    public function show(Business $business, BusinessPhoto $photo)
    {
        // Ensure photo belongs to this business
        if ($photo->business_id !== $business->id) {
            abort(404);
        }

        return view('business-photos.show', compact('business', 'photo'));
    }

    /**
     * Show the form for editing the specified photo.
     */
    public function edit(Business $business, BusinessPhoto $photo)
    {
        $this->authorizeBusinessAccess($business);

        // Ensure photo belongs to this business
        if ($photo->business_id !== $business->id) {
            abort(404);
        }

        return view('business-photos.edit', compact('business', 'photo'));
    }

    /**
     * Update the specified photo in storage.
     */
    public function update(Request $request, Business $business, BusinessPhoto $photo)
    {
        $this->authorizeBusinessAccess($business);

        // Ensure photo belongs to this business
        if ($photo->business_id !== $business->id) {
            abort(404);
        }

        $validated = $request->validate([
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'caption' => 'nullable|string|max:255',
        ]);

        // Handle file upload (if new photo is provided)
        if ($request->hasFile('photo')) {
            // Delete old photo
            if ($photo->photo_url && Storage::disk('public')->exists($photo->photo_url)) {
                Storage::disk('public')->delete($photo->photo_url);
            }

            $file = $request->file('photo');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            
            // ✅ FIXED: Hierarchical folder structure
            $path = $file->storeAs(
                "businesses/{$business->id}/photos",
                $filename,
                'public'
            );
            
            $validated['photo_url'] = $path;
        }

        $photo->update($validated);

        return redirect()
            ->route('businesses.photos.index', $business)
            ->with('success', 'Photo updated successfully!');
    }

    /**
     * Remove the specified photo from storage.
     */
    public function destroy(Business $business, BusinessPhoto $photo)
    {
        $this->authorizeBusinessAccess($business);

        // Ensure photo belongs to this business
        if ($photo->business_id !== $business->id) {
            abort(404);
        }

        // Delete file from storage
        if ($photo->photo_url && Storage::disk('public')->exists($photo->photo_url)) {
            Storage::disk('public')->delete($photo->photo_url);
        }

        $photo->delete();

        return redirect()
            ->route('businesses.photos.index', $business)
            ->with('success', 'Photo deleted successfully!');
    }
}
