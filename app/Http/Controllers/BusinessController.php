<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\User;
use App\Models\BusinessType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'business_type_id' => 'required|exists:business_types,id',
            'business_mode' => 'required|in:product,service',
            'user_id' => 'nullable|exists:users,id', // Only if admin wants to assign
        ]);

        $user = $this->getAuthUser();

        // Automatically set user_id to current user unless admin specifies
        if (!isset($validated['user_id']) || !$user->isAdmin()) {
            $validated['user_id'] = $user->id;
        }

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
            'contacts.contactType',
            'testimonies.aiAnalysis'
        ]);

        // Only show approved testimonies to public
        $approvedTestimonies = $business->testimonies()
            ->whereHas('aiAnalysis', function ($query) {
                $query->where('is_approved', true);
            })
            ->latest()
            ->get();

        return view('businesses.show', compact('business', 'approvedTestimonies'));
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
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'business_type_id' => 'required|exists:business_types,id',
            'business_mode' => 'required|in:product,service',
            'user_id' => 'nullable|exists:users,id', // Only admin can change owner
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
