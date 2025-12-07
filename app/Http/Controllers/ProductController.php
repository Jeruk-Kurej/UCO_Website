<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
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
     * Display a listing of products for a business.
     */
    public function index(Business $business)
    {
        $products = $business->products()
            ->with(['productCategory', 'photos'])  // ✅ FIXED
            ->latest()
            ->paginate(12);

        return view('products.index', compact('business', 'products'));
    }

    /**
     * Show the form for creating a new product.
     */
    public function create(Business $business)
    {
        $this->authorizeBusinessAccess($business);

        // Fetch product categories for this BUSINESS TYPE (not business)
        $categories = $business->businessType->productCategories;

        return view('products.create', compact('business', 'categories'));
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(Request $request, Business $business)
    {
        $this->authorizeBusinessAccess($business);

        $validated = $request->validate([
            'product_category_id' => 'required|exists:product_categories,id',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
        ]);

        // Ensure the category belongs to this BUSINESS TYPE
        $category = $business->businessType->productCategories()->find($validated['product_category_id']);
        
        if (!$category) {
            return back()->withErrors(['product_category_id' => 'The selected category does not belong to this business type.']);
        }

        $validated['business_id'] = $business->id;

        $product = Product::create($validated);

        return redirect()
            ->route('businesses.products.index', $business)
            ->with('success', 'Product created successfully!');
    }

    /**
     * Display the specified product.
     */
    public function show(Business $business, Product $product)
    {
        if ($product->business_id !== $business->id) {
            abort(404);
        }

        $product->load(['productCategory', 'photos']);  // ✅ FIXED

        return view('products.show', compact('business', 'product'));
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit(Business $business, Product $product)
    {
        $this->authorizeBusinessAccess($business);

        // Ensure product belongs to this business
        if ($product->business_id !== $business->id) {
            abort(404);
        }

        // Fetch product categories for this BUSINESS TYPE (not business)
        $categories = $business->businessType->productCategories;

        return view('products.edit', compact('business', 'product', 'categories'));
    }

    /**
     * Update the specified product in storage.
     */
    public function update(Request $request, Business $business, Product $product)
    {
        $this->authorizeBusinessAccess($business);

        // Ensure product belongs to this business
        if ($product->business_id !== $business->id) {
            abort(404);
        }

        $validated = $request->validate([
            'product_category_id' => 'required|exists:product_categories,id',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
        ]);

        // Ensure the category belongs to this BUSINESS TYPE
        $category = $business->businessType->productCategories()->find($validated['product_category_id']);
        
        if (!$category) {
            return back()->withErrors(['product_category_id' => 'The selected category does not belong to this business type.']);
        }

        $product->update($validated);

        return redirect()
            ->route('businesses.products.index', $business)
            ->with('success', 'Product updated successfully!');
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy(Business $business, Product $product)
    {
        $this->authorizeBusinessAccess($business);

        // Ensure product belongs to this business
        if ($product->business_id !== $business->id) {
            abort(404);
        }

        $product->delete();

        return redirect()
            ->route('businesses.products.index', $business)
            ->with('success', 'Product deleted successfully!');
    }
}
