<?php

use App\Http\Controllers\AiAnalysisController;
use App\Http\Controllers\BusinessContactController;
use App\Http\Controllers\BusinessController;
use App\Http\Controllers\BusinessPhotoController;
use App\Http\Controllers\BusinessTypeController;
use App\Http\Controllers\ContactTypeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductCategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductPhotoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UcTestimonyController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

// Health check endpoint for Railway
Route::get('/ping', function () {
    return response()->json(['status' => 'ok'], 200);
});

// Welcome Page
Route::get('/', function () {
    return view('welcome');
})->name('home');

// ✅ Public Business Index
Route::get('/businesses', [BusinessController::class, 'index'])->name('businesses.index');

// ✅ Public Business Types Index (READ ONLY)
Route::get('/business-types', [BusinessTypeController::class, 'index'])->name('business-types.index');

// ✅ Public Contact Types Index (READ ONLY)
Route::get('/contact-types', [ContactTypeController::class, 'index'])->name('contact-types.index');

// ✅ UC-wide Testimonies (Public list)
Route::get('/uc-testimonies', [UcTestimonyController::class, 'index'])->name('uc-testimonies.index');

/*
|--------------------------------------------------------------------------
| Authenticated Routes (Student, Alumni, Admin) 🔐
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile Management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    /*
    |--------------------------------------------------------------------------
    | ✅ FIXED: Business Management (CRUD)
    | MUST come BEFORE public show route
    |--------------------------------------------------------------------------
    */
    Route::resource('businesses', BusinessController::class)->except(['index', 'show']);
    
    // Toggle featured status (Admin only)
    Route::post('/businesses/{business}/toggle-featured', [BusinessController::class, 'toggleFeatured'])
        ->name('businesses.toggle-featured');
    
    // Import businesses from Excel (Admin only)
    Route::post('/businesses/import', [BusinessController::class, 'import'])
        ->name('businesses.import');

    /*
    |--------------------------------------------------------------------------
    | ✅ MOVED HERE: Product Categories (All Authenticated Users)
    |--------------------------------------------------------------------------
    */
    Route::resource('business-types.product-categories', ProductCategoryController::class)
        ->scoped([
            'businessType' => 'id',
            'productCategory' => 'id'
        ]);

    /*
    |--------------------------------------------------------------------------
    | ✅ FIXED: Nested Resources with Explicit Scoping
    |--------------------------------------------------------------------------
    */
    
    // Products (nested under businesses)
    Route::resource('businesses.products', ProductController::class)
        ->except(['index'])
        ->scoped([
            'business' => 'id',
            'product' => 'id'
        ]);

    // Services (nested under businesses)
    Route::resource('businesses.services', ServiceController::class)
        ->except(['index'])
        ->scoped([
            'business' => 'id',
            'service' => 'id'
        ]);

    // Business Photos (nested under businesses)
    Route::resource('businesses.photos', BusinessPhotoController::class)
        ->except(['index'])
        ->scoped([
            'business' => 'id',
            'photo' => 'id'
        ]);

    // Business Contacts (nested under businesses)
    Route::resource('businesses.contacts', BusinessContactController::class)
        ->except(['index'])
        ->scoped([
            'business' => 'id',
            'contact' => 'id'
        ]);

    // Product Photos (nested under products)
    Route::resource('products.photos', ProductPhotoController::class)
        ->scoped([
            'product' => 'id',
            'photo' => 'id'
        ]);

    // ✅ UC-wide Testimonies (submission)
    Route::post('/uc-testimonies', [UcTestimonyController::class, 'store'])->name('uc-testimonies.store');

    // ✅ UC-wide Testimonies (admin reject/delete)
    Route::delete('/uc-testimonies/{ucTestimony}', [UcTestimonyController::class, 'destroy'])->name('uc-testimonies.destroy');

    /*
    |--------------------------------------------------------------------------
    | AI Analysis (Read-Only)
    |--------------------------------------------------------------------------
    */
    Route::get('/ai-analyses', [AiAnalysisController::class, 'index'])->name('ai-analyses.index');
    Route::get('/uc-testimonies/{ucTestimony}/ai-analysis', [AiAnalysisController::class, 'showUc'])->name('uc-ai-analyses.show');
});

/*
|--------------------------------------------------------------------------
| Admin-Only Routes 👮‍♂️
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified', 'admin'])->group(function () {
    
    // User Management
    Route::resource('users', UserController::class);
    
    // User Import Routes
    Route::post('users/import', [UserController::class, 'import'])->name('users.import');
    Route::get('users/template/download', [UserController::class, 'downloadTemplate'])->name('users.template');

    /*
    |--------------------------------------------------------------------------
    | ✅ FIXED: Business Type Management (Admin CRUD)
    | MUST come BEFORE public show route
    |--------------------------------------------------------------------------
    */
    Route::resource('business-types', BusinessTypeController::class)->except(['index', 'show']);

    /*
    |--------------------------------------------------------------------------
    | ✅ FIXED: Contact Type Management (Admin CRUD)
    | MUST come BEFORE public show route
    |--------------------------------------------------------------------------
    */
    Route::resource('contact-types', ContactTypeController::class)->except(['index', 'show']);

    // ❌ REMOVED FROM HERE: Product Categories (moved to authenticated group above)
});

/*
|--------------------------------------------------------------------------
| ✅ MOVED: Public Show Routes (AFTER all specific routes)
| These MUST come LAST to avoid catching /create, /edit, etc.
|--------------------------------------------------------------------------
*/
Route::get('/businesses/{business}', [BusinessController::class, 'show'])->name('businesses.show');
Route::get('/business-types/{businessType}', [BusinessTypeController::class, 'show'])->name('business-types.show');
Route::get('/contact-types/{contactType}', [ContactTypeController::class, 'show'])->name('contact-types.show');

/*
|--------------------------------------------------------------------------
| 🔥 TEMPORARY: Database Reset Route (DELETE AFTER USE!)
|--------------------------------------------------------------------------
*/
Route::get('/admin/reset-database-confirm', function () {
    // Only admin can access
    if (!auth()->check() || !auth()->user()->isAdmin()) {
        abort(403, 'Only administrators can reset database.');
    }
    
    return view('admin.reset-database');
})->middleware('auth')->name('admin.reset-database');

Route::post('/admin/reset-database-execute', function () {
    // Only admin can access
    if (!auth()->check() || !auth()->user()->isAdmin()) {
        abort(403, 'Only administrators can reset database.');
    }
    
    try {
        // Delete all businesses
        $businessCount = \App\Models\Business::count();
        \App\Models\Business::query()->delete();
        
        // Delete business types (clean HTML tags)
        \App\Models\BusinessType::query()->delete();
        
        // Delete product categories
        \App\Models\ProductCategory::query()->delete();
        
        // Delete contact types
        \App\Models\ContactType::query()->delete();
        
        // Delete ALL users
        $userCount = \App\Models\User::count();
        \App\Models\User::query()->delete();
        
        // Create ONE default admin
        $admin = \App\Models\User::create([
            'username' => 'admin',
            'name' => 'Admin UCO',
            'email' => 'admin@uco.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'role' => 'admin',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
        
        // Logout current user (will be deleted)
        auth()->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        
        return redirect('/login')->with('success', "✅ Database reset! Deleted {$businessCount} businesses and {$userCount} users. Login as admin@uco.com / password");
        
    } catch (\Exception $e) {
        return back()->with('error', 'Error: ' . $e->getMessage());
    }
})->middleware('auth')->name('admin.reset-database.execute');

require __DIR__.'/auth.php';