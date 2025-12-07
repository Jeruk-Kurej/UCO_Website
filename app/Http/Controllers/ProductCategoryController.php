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
     * Only admin can manage product categories
     */
    private function authorizeAdmin(): void
    {
        $user = $this->getAuthUser();
        
        if (!$user->isAdmin()) {
            abort(403, 'Only administrators can manage product categories.');
        }
    }

    /**
     * Display a listing of product categories for a business type.
     */
    public function index(BusinessType $businessType)
    {
        $categories = $businessType->productCategories()->with('products')->get();

        return view('product-categories.index', compact('businessType', 'categories'));
    }

    /**
     * Show the form for creating a new product category.
     */
    public function create(BusinessType $businessType)
    {
        $this->authorizeAdmin();

        return view('product-categories.create', compact('businessType'));
    }

    /**
     * Store a newly created product category in storage.
     */
    public function store(Request $request, BusinessType $businessType)
    {
        $this->authorizeAdmin();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $validated['business_type_id'] = $businessType->id;

        $category = ProductCategory::create($validated);

        return redirect()
            ->route('business-types.product-categories.index', $businessType)
            ->with('success', 'Product category created successfully!');
    }

    /**
     * Display the specified product category.
     */
    public function show(BusinessType $businessType, ProductCategory $productCategory)
    {
        if ($productCategory->business_type_id !== $businessType->id) {
            abort(404);
        }

        $productCategory->load('products.photos');

        return view('product-categories.show', compact('businessType', 'productCategory'));
    }

    /**
     * Show the form for editing the specified product category.
     */
    public function edit(BusinessType $businessType, ProductCategory $productCategory)
    {
        $this->authorizeAdmin();

        if ($productCategory->business_type_id !== $businessType->id) {
            abort(404);
        }

        return view('product-categories.edit', compact('businessType', 'productCategory'));
    }

    /**
     * Update the specified product category in storage.
     */
    public function update(Request $request, BusinessType $businessType, ProductCategory $productCategory)
    {
        $this->authorizeAdmin();

        if ($productCategory->business_type_id !== $businessType->id) {
            abort(404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $productCategory->update($validated);

        return redirect()
            ->route('business-types.product-categories.index', $businessType)
            ->with('success', 'Product category updated successfully!');
    }

    /**
     * Remove the specified product category from storage.
     */
    public function destroy(BusinessType $businessType, ProductCategory $productCategory)
    {
        $this->authorizeAdmin();

        if ($productCategory->business_type_id !== $businessType->id) {
            abort(404);
        }

        $productCategory->delete();

        return redirect()
            ->route('business-types.product-categories.index', $businessType)
            ->with('success', 'Product category deleted successfully!');
    }
}
