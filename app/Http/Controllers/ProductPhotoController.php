<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductPhoto;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductPhotoController extends Controller
{
    /**
     * Display a listing of photos for a product.
     * ✅ CHANGED: Show photos directly, no redirect
     */
    public function index(Product $product)
    {
        $this->authorizeProductAccess($product);

        $photos = $product->photos()->latest()->get();
        $product->load('business');

        return view('product-photos.index', compact('product', 'photos'));
    }

    /**
     * Show the form for creating a new photo.
     */
    public function create(Product $product)
    {
        $this->authorizeProductAccess($product);

        return view('product-photos.create', compact('product'));
    }

    /**
     * Store a newly created photo in storage.
     */
    public function store(Request $request, Product $product)
    {
        $this->authorizeProductAccess($product);

        try {
            $validated = $request->validate([
                'photos' => 'required|array|min:1|max:10',
                'photos.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:10240',
            ]);

            $uploadedCount = 0;
            if ($request->hasFile('photos')) {
                $product->load('business');
                $productSlug = Str::slug($product->name, '_');
                $baseNumber = $product->photos()->count();

                foreach ($request->file('photos') as $index => $file) {
                    // Additional file size check just in case
                    if ($file->getSize() > 10240 * 1024) continue;

                    $nextNumber = $baseNumber + $index + 1;
                    $filename = $productSlug . '_photo_' . $nextNumber . '_' . time() . '_' . $index . '.' . $file->getClientOriginalExtension();
                    
                    // Store to Cloudinary (default disk)
                    $path = $file->storeAs(
                        "businesses/{$product->business_id}/products/{$product->id}/photos",
                        $filename,
                        config('filesystems.default')
                    );
                    
                    ProductPhoto::create([
                        'product_id' => $product->id,
                        'photo_url' => $path,
                    ]);

                    $uploadedCount++;
                }
            }

            if ($uploadedCount === 0) {
                return back()->withErrors(['photos' => 'No valid photos were uploaded.'])->withInput();
            }

            $message = $uploadedCount === 1 
                ? "Success! Your photo for '{$product->name}' has been uploaded."
                : "Success! {$uploadedCount} photos for '{$product->name}' have been uploaded.";

            return redirect()
                ->route('products.photos.index', $product)
                ->with('success', $message);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Product photo upload failed: ' . $e->getMessage());
            return back()->withErrors(['error' => 'An error occurred while uploading. Please try again.'])->withInput();
        }
    }

    /**
     * Display the specified photo.
     */
    public function show(Product $product, ProductPhoto $photo)
    {
        // Ensure photo belongs to this product
        if ($photo->product_id !== $product->id) {
            abort(404);
        }

        $product->load('business');

        return view('product-photos.show', compact('product', 'photo'));
    }

    /**
     * Show the form for editing the specified photo.
     */
    public function edit(Product $product, ProductPhoto $photo)
    {
        $this->authorizeProductAccess($product);

        // Ensure photo belongs to this product
        if ($photo->product_id !== $product->id) {
            abort(404);
        }

        return view('product-photos.edit', compact('product', 'photo'));
    }

    /**
     * Update the specified photo in storage.
     */
    public function update(Request $request, Product $product, ProductPhoto $photo)
    {
        $this->authorizeProductAccess($product);

        // Ensure photo belongs to this product
        if ($photo->product_id !== $product->id) {
            abort(404);
        }

        $validated = $request->validate([
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
            'caption' => 'nullable|string|max:255',
        ]);

        // Handle file upload (if new photo is provided)
        if ($request->hasFile('photo')) {
            // Delete old photo
            if ($photo->photo_url && Storage::disk(config('filesystems.default'))->exists($photo->photo_url)) {
                Storage::disk(config('filesystems.default'))->delete($photo->photo_url);
            }

            $file = $request->file('photo');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            
            $product->load('business');
            $path = $file->storeAs(
                "businesses/{$product->business_id}/products/{$product->id}/photos",
                $filename,
                config('filesystems.default')
            );
            
            $validated['photo_url'] = $path;
        }

        $photo->update($validated);

        // ✅ FIXED: Redirect back to photo index
        return redirect()
            ->route('products.photos.index', $product)
            ->with('success', "Success! The photo for '{$product->name}' has been updated.");
    }

    /**
     * Remove the specified photo from storage.
     */
    public function destroy(Product $product, ProductPhoto $photo)
    {
        $this->authorizeProductAccess($product);

        // Ensure photo belongs to this product
        if ($photo->product_id !== $product->id) {
            abort(404);
        }

        // Delete file from storage
        if ($photo->photo_url && Storage::disk(config('filesystems.default'))->exists($photo->photo_url)) {
            Storage::disk(config('filesystems.default'))->delete($photo->photo_url);
        }

        $photo->delete();

        // ✅ FIXED: Redirect back to previous page (likely the edit form)
        return back()
            ->with('success', "The photo for '{$product->name}' has been removed successfully.");
    }

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
     * Check if user can manage product (via business ownership)
     */
    private function authorizeProductAccess(Product $product): void
    {
        $user = $this->getAuthUser();
        
        // Load business relationship to check ownership
        $product->load('business');
        
        if (!$product->business->canBeManagedBy($user)) {
            abort(403, 'Unauthorized action.');
        }
    }
}
