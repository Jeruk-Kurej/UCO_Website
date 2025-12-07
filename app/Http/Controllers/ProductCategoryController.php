<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\ProductCategory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductCategoryController extends Controller
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
     * Display a listing of product categories for a business.
     */
    public function index(Business $business)
    {
        $categories = $business->productCategories()->with('products')->get();

        return view('product-categories.index', compact('business', 'categories'));
    }

    /**
     * Show the form for creating a new product category.
     */
    public function create(Business $business)
    {
        $this->authorizeBusinessAccess($business);

        return view('product-categories.create', compact('business'));
    }

    /**
     * Store a newly created product category in storage.
     */
    public function store(Request $request, Business $business)
    {
        $this->authorizeBusinessAccess($business);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $validated['business_id'] = $business->id;

        $category = ProductCategory::create($validated);

        return redirect()
            ->route('businesses.product-categories.index', $business)
            ->with('success', 'Product category created successfully!');
    }

    /**
     * Display the specified product category.
     */
    public function show(Business $business, ProductCategory $productCategory)
    {
        // Ensure category belongs to this business
        if ($productCategory->business_id !== $business->id) {
            abort(404);
        }

        $productCategory->load('products.photos');

        return view('product-categories.show', compact('business', 'productCategory'));
    }

    /**
     * Show the form for editing the specified product category.
     */
    public function edit(Business $business, ProductCategory $productCategory)
    {
        $this->authorizeBusinessAccess($business);

        // Ensure category belongs to this business
        if ($productCategory->business_id !== $business->id) {
            abort(404);
        }

        return view('product-categories.edit', compact('business', 'productCategory'));
    }

    /**
     * Update the specified product category in storage.
     */
    public function update(Request $request, Business $business, ProductCategory $productCategory)
    {
        $this->authorizeBusinessAccess($business);

        // Ensure category belongs to this business
        if ($productCategory->business_id !== $business->id) {
            abort(404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $productCategory->update($validated);

        return redirect()
            ->route('businesses.product-categories.index', $business)
            ->with('success', 'Product category updated successfully!');
    }

    /**
     * Remove the specified product category from storage.
     */
    public function destroy(Business $business, ProductCategory $productCategory)
    {
        $this->authorizeBusinessAccess($business);

        // Ensure category belongs to this business
        if ($productCategory->business_id !== $business->id) {
            abort(404);
        }

        $productCategory->delete();

        return redirect()
            ->route('businesses.product-categories.index', $business)
            ->with('success', 'Product category deleted successfully!');
    }
}
