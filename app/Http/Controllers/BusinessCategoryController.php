<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\BusinessCategory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BusinessCategoryController extends Controller
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
     * Display a listing of categories for a business.
     */
    public function index(Business $business)
    {
        $categories = $business->businessCategories()->with('products')->get();

        return view('business-categories.index', compact('business', 'categories'));
    }

    /**
     * Show the form for creating a new category.
     */
    public function create(Business $business)
    {
        $this->authorizeBusinessAccess($business);

        return view('business-categories.create', compact('business'));
    }

    /**
     * Store a newly created category in storage.
     */
    public function store(Request $request, Business $business)
    {
        $this->authorizeBusinessAccess($business);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $validated['business_id'] = $business->id;

        $category = BusinessCategory::create($validated);

        return redirect()
            ->route('businesses.categories.index', $business)
            ->with('success', 'Category created successfully!');
    }

    /**
     * Display the specified category.
     */
    public function show(Business $business, BusinessCategory $category)
    {
        // Ensure category belongs to this business
        if ($category->business_id !== $business->id) {
            abort(404);
        }

        $category->load('products.photos');

        return view('business-categories.show', compact('business', 'category'));
    }

    /**
     * Show the form for editing the specified category.
     */
    public function edit(Business $business, BusinessCategory $category)
    {
        $this->authorizeBusinessAccess($business);

        // Ensure category belongs to this business
        if ($category->business_id !== $business->id) {
            abort(404);
        }

        return view('business-categories.edit', compact('business', 'category'));
    }

    /**
     * Update the specified category in storage.
     */
    public function update(Request $request, Business $business, BusinessCategory $category)
    {
        $this->authorizeBusinessAccess($business);

        // Ensure category belongs to this business
        if ($category->business_id !== $business->id) {
            abort(404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $category->update($validated);

        return redirect()
            ->route('businesses.categories.index', $business)
            ->with('success', 'Category updated successfully!');
    }

    /**
     * Remove the specified category from storage.
     */
    public function destroy(Business $business, BusinessCategory $category)
    {
        $this->authorizeBusinessAccess($business);

        // Ensure category belongs to this business
        if ($category->business_id !== $business->id) {
            abort(404);
        }

        $category->delete();

        return redirect()
            ->route('businesses.categories.index', $business)
            ->with('success', 'Category deleted successfully!');
    }
}