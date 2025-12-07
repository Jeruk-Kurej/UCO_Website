<?php

namespace App\Http\Controllers;

use App\Models\BusinessType;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BusinessTypeController extends Controller
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
     * Check if user is admin
     */
    private function authorizeAdmin(): void
    {
        $user = $this->getAuthUser();
        
        if (!$user->isAdmin()) {
            abort(403, 'Only administrators can manage business types.');
        }
    }

    /**
     * Display a listing of business types.
     * Public access.
     */
    public function index()
    {
        $businessTypes = BusinessType::withCount('businesses')->latest()->paginate(15);

        return view('business-types.index', compact('businessTypes'));
    }

    /**
     * Show the form for creating a new business type.
     * Admin only.
     */
    public function create()
    {
        $this->authorizeAdmin();

        return view('business-types.create');
    }

    /**
     * Store a newly created business type in storage.
     * Admin only.
     */
    public function store(Request $request)
    {
        $this->authorizeAdmin();

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:business_types',
            'description' => 'nullable|string',
        ]);

        $businessType = BusinessType::create($validated);

        return redirect()
            ->route('business-types.index')
            ->with('success', 'Business type created successfully!');
    }

    /**
     * Display the specified business type.
     */
    public function show(BusinessType $businessType)
    {
        $businessType->load('businesses.user');

        return view('business-types.show', compact('businessType'));
    }

    /**
     * Show the form for editing the specified business type.
     * Admin only.
     */
    public function edit(BusinessType $businessType)
    {
        $this->authorizeAdmin();

        return view('business-types.edit', compact('businessType'));
    }

    /**
     * Update the specified business type in storage.
     * Admin only.
     */
    public function update(Request $request, BusinessType $businessType)
    {
        $this->authorizeAdmin();

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:business_types,name,' . $businessType->id,
            'description' => 'nullable|string',
        ]);

        $businessType->update($validated);

        return redirect()
            ->route('business-types.index')
            ->with('success', 'Business type updated successfully!');
    }

    /**
     * Remove the specified business type from storage.
     * Admin only.
     */
    public function destroy(BusinessType $businessType)
    {
        $this->authorizeAdmin();

        // Check if any businesses are using this type
        if ($businessType->businesses()->count() > 0) {
            return redirect()
                ->route('business-types.index')
                ->with('error', 'Cannot delete business type that is in use by businesses.');
        }

        $businessType->delete();

        return redirect()
            ->route('business-types.index')
            ->with('success', 'Business type deleted successfully!');
    }
}
