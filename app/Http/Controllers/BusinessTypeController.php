<?php

namespace App\Http\Controllers;

use App\Models\BusinessType;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BusinessTypeController extends Controller
{
    private function getAuthUser(): ?User // ✅ CHANGED: Allow null for public access
    {
        /** @var User|null $user */
        $user = Auth::user();
        return $user;
    }

    private function authorizeAdmin(): void
    {
        $user = $this->getAuthUser();
        
        if (!$user || !$user->isAdmin()) { // ✅ ADDED: Check if user exists
            abort(403, 'Only administrators can manage business types.');
        }
    }

    /**
     * Display a listing of business types.
     * ✅ PUBLIC ACCESS - Everyone can read
     */
    public function index(Request $request)
    {
        // ✅ NO AUTHORIZATION - Public access for reading
        $search = $request->get('search');
        $query = BusinessType::withCount('businesses');
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }
        
        $businessTypes = $query->latest()->paginate(15);

        return view('business-types.index', compact('businessTypes'));
    }

    public function create()
    {
        $this->authorizeAdmin();

        return view('business-types.create');
    }

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
     * ✅ PUBLIC ACCESS - Everyone can read
     */
    public function show(BusinessType $businessType)
    {
        // ✅ NO AUTHORIZATION - Public access for reading
        $businessType->load('businesses.user');

        return view('business-types.show', compact('businessType'));
    }

    public function edit(BusinessType $businessType)
    {
        $this->authorizeAdmin();

        return view('business-types.edit', compact('businessType'));
    }

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

    public function destroy(BusinessType $businessType)
    {
        $this->authorizeAdmin();

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
