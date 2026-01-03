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
     * ✅ CHANGED: Redirect to business show page with photos tab
     */
    public function index(Business $business)
    {
        return redirect()->route('businesses.show', $business)->with('activeTab', 'photos');
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

        try {
            $validated = $request->validate([
                'photo' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                'caption' => 'nullable|string|max:255',
            ]);

            // Handle file upload
            if ($request->hasFile('photo')) {
                $file = $request->file('photo');
                
                // Additional file size check
                if ($file->getSize() > 2048 * 1024) {
                    return back()->withErrors(['photo' => 'Photo must not be larger than 2MB.'])->withInput();
                }
                
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                
                $path = $file->storeAs(
                    "businesses/{$business->id}/photos",
                    $filename,
                    'public'
                );
                
                $validated['photo_url'] = $path;
            }

            $validated['business_id'] = $business->id;

            $photo = BusinessPhoto::create($validated);

            // ✅ FIXED: Redirect to business show page
            return redirect()
                ->route('businesses.show', $business)
                ->with('success', 'Photo uploaded successfully!')
                ->with('activeTab', 'photos');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'An error occurred while uploading the photo. Please try again.'])->withInput();
        }
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
            
            $path = $file->storeAs(
                "businesses/{$business->id}/photos",
                $filename,
                'public'
            );
            
            $validated['photo_url'] = $path;
        }

        $photo->update($validated);

        // ✅ FIXED: Redirect to business show page
        return redirect()
            ->route('businesses.show', $business)
            ->with('success', 'Photo updated successfully!')
            ->with('activeTab', 'photos');
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

        // ✅ FIXED: Redirect to business show page
        return redirect()
            ->route('businesses.show', $business)
            ->with('success', 'Photo deleted successfully!')
            ->with('activeTab', 'photos');
    }
}
