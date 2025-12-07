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
            ->with(['businessCategory', 'photos'])
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

        // Fetch business categories for this business
        $categories = $business->businessCategories;

        return view('products.create', compact('business', 'categories'));
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(Request $request, Business $business)
    {
        $this->authorizeBusinessAccess($business);

        $validated = $request->validate([
            'business_category_id' => 'required|exists:business_categories,id',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
        ]);

        // Ensure the category belongs to this business
        $category = $business->businessCategories()->find($validated['business_category_id']);
        
        if (!$category) {
            return back()->withErrors(['business_category_id' => 'The selected category does not belong to this business.']);
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
        // Ensure product belongs to this business
        if ($product->business_id !== $business->id) {
            abort(404);
        }

        $product->load(['businessCategory', 'photos']);

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

        // Fetch business categories for this business
        $categories = $business->businessCategories;

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
            'business_category_id' => 'required|exists:business_categories,id',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
        ]);

        // Ensure the category belongs to this business
        $category = $business->businessCategories()->find($validated['business_category_id']);
        
        if (!$category) {
            return back()->withErrors(['business_category_id' => 'The selected category does not belong to this business.']);
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
