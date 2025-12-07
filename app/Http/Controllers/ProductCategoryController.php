<?php

namespace App\Http\Controllers;

use App\Models\BusinessType;
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
     * Display a listing of product categories for a business type.
     * ✅ CHANGED: Open to all authenticated users
     */
    public function index(BusinessType $businessType)
    {
        $this->getAuthUser(); // ✅ Just verify authentication, no admin check

        $categories = $businessType->productCategories()
            ->withCount('products')
            ->get();

        return view('product-categories.index', compact('businessType', 'categories'));
    }

    /**
     * Show the form for creating a new product category.
     * ✅ CHANGED: Open to all authenticated users
     */
    public function create(BusinessType $businessType)
    {
        $this->getAuthUser(); // ✅ Just verify authentication

        return view('product-categories.create', compact('businessType'));
    }

    /**
     * Store a newly created product category in storage.
     * ✅ CHANGED: Open to all authenticated users
     */
    public function store(Request $request, BusinessType $businessType)
    {
        $this->getAuthUser(); // ✅ Just verify authentication

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:product_categories,name,NULL,id,business_type_id,' . $businessType->id,
        ], [
            'name.unique' => 'This category name already exists for this business type.',
        ]);

        $validated['business_type_id'] = $businessType->id;

        $category = ProductCategory::create($validated);

        return redirect()
            ->route('business-types.product-categories.index', $businessType)
            ->with('success', 'Product category created successfully!');
    }

    /**
     * Display the specified product category.
     * ✅ CHANGED: Open to all authenticated users
     */
    public function show(BusinessType $businessType, ProductCategory $productCategory)
    {
        $this->getAuthUser(); // ✅ Just verify authentication

        if ($productCategory->business_type_id !== $businessType->id) {
            abort(404);
        }

        $productCategory->load('products.photos');

        return view('product-categories.show', compact('businessType', 'productCategory'));
    }

    /**
     * Show the form for editing the specified product category.
     * ✅ CHANGED: Open to all authenticated users
     */
    public function edit(BusinessType $businessType, ProductCategory $productCategory)
    {
        $this->getAuthUser(); // ✅ Just verify authentication

        if ($productCategory->business_type_id !== $businessType->id) {
            abort(404);
        }

        return view('product-categories.edit', compact('businessType', 'productCategory'));
    }

    /**
     * Update the specified product category in storage.
     * ✅ CHANGED: Open to all authenticated users
     */
    public function update(Request $request, BusinessType $businessType, ProductCategory $productCategory)
    {
        $this->getAuthUser(); // ✅ Just verify authentication

        if ($productCategory->business_type_id !== $businessType->id) {
            abort(404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:product_categories,name,' . $productCategory->id . ',id,business_type_id,' . $businessType->id,
        ], [
            'name.unique' => 'This category name already exists for this business type.',
        ]);

        $productCategory->update($validated);

        return redirect()
            ->route('business-types.product-categories.index', $businessType)
            ->with('success', 'Product category updated successfully!');
    }

    /**
     * Remove the specified product category from storage.
     * ✅ CHANGED: Open to all authenticated users (with safety check)
     */
    public function destroy(BusinessType $businessType, ProductCategory $productCategory)
    {
        $this->getAuthUser(); // ✅ Just verify authentication

        if ($productCategory->business_type_id !== $businessType->id) {
            abort(404);
        }

        // ✅ SAFETY: Prevent deletion if products exist
        if ($productCategory->products()->count() > 0) {
            return redirect()
                ->route('business-types.product-categories.index', $businessType)
                ->with('error', 'Cannot delete category that has products. Please delete or reassign products first.');
        }

        $productCategory->delete();

        return redirect()
            ->route('business-types.product-categories.index', $businessType)
            ->with('success', 'Product category deleted successfully!');
    }
}
