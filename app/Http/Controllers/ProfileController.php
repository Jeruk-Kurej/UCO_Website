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
use Illuminate\Support\Str;
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

            // Handle personal_data (Identity/PII) - Only admins can update sensitive fields
            if ($request->has('personal_data')) {
                $newPersonalData = $request->input('personal_data');
                $existingPersonalData = $user->personal_data ?? [];
                
                // Fields that only admins can change
                $restrictedFields = ['citizenship', 'citizenship_no', 'passport_no', 'npwp_no', 'bpjs_no'];
                
                foreach ($newPersonalData as $key => $value) {
                    if (in_array($key, $restrictedFields)) {
                        if ($user->isAdmin()) {
                            $existingPersonalData[$key] = $value;
                        }
                        // Skip if not admin (don't overwrite)
                    } else {
                        // Regular fields in personal_data (if any)
                        $existingPersonalData[$key] = $value;
                    }
                }
                
                $user->personal_data = $existingPersonalData;
            }

            $user->save();

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
                        if (Storage::disk(config('filesystems.default'))->exists((string)$user->profile_photo_url)) {
                            Storage::disk(config('filesystems.default'))->delete((string)$user->profile_photo_url);
                        }
                    } catch (\Throwable $e) {
                        // Log and continue - missing Cloudinary resource should not block profile update
                        Log::warning('Failed to delete old profile photo from storage: ' . $e->getMessage(), ['path' => $user->profile_photo_url]);
                    }
                }

                // Store new photo to default disk (Cloudinary or local depending on env)
                $slug = Str::slug($user->username ?? $user->name, '_');
                $filename = $slug . '_' . time() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('profile-photos', $filename, config('filesystems.default'));
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

            return Redirect::route('profile.edit')->with('success', 'Your profile information has been successfully updated.');
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
