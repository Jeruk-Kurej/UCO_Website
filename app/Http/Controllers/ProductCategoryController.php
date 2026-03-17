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
     * Display a listing of product categories for a business.
     * ✅ CHANGED: Open to all authenticated users
     */
    public function index(Business $business)
    {
        $this->getAuthUser(); // ✅ Just verify authentication, no admin check

        $categories = $business->productCategories()
            ->withCount('products')
            ->get();

        return view('product-categories.index', compact('business', 'categories'));
    }

    /**
     * Show the form for creating a new product category.
     * ✅ CHANGED: Open to all authenticated users
     */
    public function create(Business $business)
    {
        $this->getAuthUser(); // ✅ Just verify authentication

        return view('product-categories.create', compact('business'));
    }

    /**
     * Store a newly created product category in storage.
     * ✅ CHANGED: Open to all authenticated users
     */
    public function store(Request $request, Business $business)
    {
        $this->getAuthUser(); // ✅ Just verify authentication

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:product_categories,name,NULL,id,business_id,' . $business->id,
        ], [
            'name.unique' => 'This category name already exists for this business.',
        ]);

        $validated['business_id'] = $business->id;

        $category = ProductCategory::create($validated);

        return redirect()
            ->route('businesses.product-categories.index', $business)
            ->with('success', 'Product category created successfully!');
    }

    /**
     * Display the specified product category.
     * ✅ CHANGED: Open to all authenticated users
     */
    public function show(Business $business, ProductCategory $productCategory)
    {
        $this->getAuthUser(); // ✅ Just verify authentication

        if ($productCategory->business_id !== $business->id) {
            abort(404);
        }

        $productCategory->load('products.photos');

        return view('product-categories.show', compact('business', 'productCategory'));
    }

    /**
     * Show the form for editing the specified product category.
     * ✅ CHANGED: Open to all authenticated users
     */
    public function edit(Business $business, ProductCategory $productCategory)
    {
        $this->getAuthUser(); // ✅ Just verify authentication

        if ($productCategory->business_id !== $business->id) {
            abort(404);
        }

        return view('product-categories.edit', compact('business', 'productCategory'));
    }

    /**
     * Update the specified product category in storage.
     * ✅ CHANGED: Open to all authenticated users
     */
    public function update(Request $request, Business $business, ProductCategory $productCategory)
    {
        $this->getAuthUser(); // ✅ Just verify authentication

        if ($productCategory->business_id !== $business->id) {
            abort(404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:product_categories,name,' . $productCategory->id . ',id,business_id,' . $business->id,
        ], [
            'name.unique' => 'This category name already exists for this business.',
        ]);

        $productCategory->update($validated);

        return redirect()
            ->route('businesses.product-categories.index', $business)
            ->with('success', 'Product category updated successfully!');
    }

    /**
     * Remove the specified product category from storage.
     * ✅ CHANGED: Open to all authenticated users (with safety check)
     */
    public function destroy(Business $business, ProductCategory $productCategory)
    {
        $this->getAuthUser(); // ✅ Just verify authentication

        if ($productCategory->business_id !== $business->id) {
            abort(404);
        }

        // ✅ SAFETY: Prevent deletion if products exist
        if ($productCategory->products()->count() > 0) {
            return redirect()
                ->route('businesses.product-categories.index', $business)
                ->with('error', 'Cannot delete category that has products. Please delete or reassign products first.');
        }

        $productCategory->delete();

        return redirect()
            ->route('businesses.product-categories.index', $business)
            ->with('success', 'Product category deleted successfully!');
    }
}
