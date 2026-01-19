<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        try {
            $user = $request->user();
            
            Log::info('Profile update started', [
                'user_id' => $user->id,
                'has_file' => $request->hasFile('profile_photo'),
                'all_files' => $request->allFiles()
            ]);
            
            // Fill basic validated fields (exclude password fields)
            $validatedData = $request->validated();
            $fillableData = collect($validatedData)->except([
                'profile_photo', 
                'current_password', 
                'password', 
                'password_confirmation'
            ])->toArray();
            
            $user->fill($fillableData);

            // Handle profile photo upload
            if ($request->hasFile('profile_photo')) {
                $file = $request->file('profile_photo');

                // clearer size check
                if ($file->getSize() > 10 * 1024 * 1024) { // 10MB in bytes
                    return Redirect::route('profile.edit')
                        ->withErrors(['profile_photo' => 'Profile photo must not be larger than 10MB.'])
                        ->withInput();
                }

                // Delete old photo if exists (from Cloudinary)
                if (!empty($user->profile_photo_url)) {
                    try {
                        if (Storage::exists((string)$user->profile_photo_url)) {
                            Storage::delete((string)$user->profile_photo_url);
                        }
                    } catch (\Throwable $e) {
                        // Log and continue - missing Cloudinary resource should not block profile update
                        Log::warning('Failed to delete old profile photo from storage: ' . $e->getMessage(), ['path' => $user->profile_photo_url]);
                    }
                }

                // Store new photo to default disk (Cloudinary or local depending on env)
                $path = $file->store('profile-photos');
                $user->profile_photo_url = $path;
            }

            // Handle password change
            if ($request->filled('current_password') && $request->filled('password')) {
                // Verify current password
                if (!Hash::check($request->current_password, $user->password)) {
                    return Redirect::route('profile.edit')
                        ->withErrors(['current_password' => 'The current password is incorrect.'])
                        ->withInput();
                }
                
                // Update to new password
                $user->password = Hash::make($request->password);
            }

            if ($user->isDirty('email')) {
                $user->email_verified_at = null;
            }

            $user->save();
            
            Log::info('Profile updated successfully', [
                'user_id' => $user->id,
                'profile_photo_url' => $user->profile_photo_url
            ]);
            
            // Refresh auth session to reflect updated data
            Auth::setUser($user->fresh());

            return Redirect::route('profile.edit')->with('status', 'profile-updated');
        } catch (\Exception $e) {
            Log::error('Profile update failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return Redirect::route('profile.edit')
                ->withErrors(['error' => 'An error occurred while updating your profile. Please try again: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
