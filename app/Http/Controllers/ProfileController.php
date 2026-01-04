<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
            $user->fill($request->validated());

            // Handle profile photo upload
            if ($request->hasFile('profile_photo')) {
                // Additional validation for file size
                $file = $request->file('profile_photo');
                
                if ($file->getSize() > 2048 * 1024) { // 2MB in bytes
                    return Redirect::route('profile.edit')
                        ->withErrors(['profile_photo' => 'Profile photo must not be larger than 2MB.'])
                        ->withInput();
                }
                
                // Delete old photo if exists
                if ($user->profile_photo_url && Storage::disk('public')->exists($user->profile_photo_url)) {
                    Storage::disk('public')->delete($user->profile_photo_url);
                }
                
                // Store new photo
                $path = $file->store('profile-photos', 'public');
                $user->profile_photo_url = $path;
            }

            if ($user->isDirty('email')) {
                $user->email_verified_at = null;
            }

            $user->save();
            
            // Refresh auth session to reflect updated data
            Auth::setUser($user->fresh());

            return Redirect::route('profile.edit')->with('status', 'profile-updated');
        } catch (\Exception $e) {
            return Redirect::route('profile.edit')
                ->withErrors(['error' => 'An error occurred while updating your profile. Please try again.'])
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
