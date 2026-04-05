<?php

use App\Http\Controllers\AiAnalysisController;
use App\Http\Controllers\BusinessContactController;
use App\Http\Controllers\BusinessController;
use App\Http\Controllers\BusinessPhotoController;
use App\Http\Controllers\BusinessTypeController;
use App\Http\Controllers\ContactTypeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FeaturedController;
use App\Http\Controllers\ProductCategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductPhotoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UcTestimonyController;
use App\Models\Business;
use App\Models\BusinessType;
use App\Models\ContactType;
use App\Models\ProductCategory;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

// ============================================================
// PUBLIC ROUTES
// ============================================================

Route::get('/ping', function () {
    return response()->json(['status' => 'ok'], 200);
});

// Home route - redirect to appropriate page based on auth status
Route::get('/', function () {
    if (Auth::check()) {
        return redirect('/dashboard');
    }
    return redirect('/featured');  // Guests go to featured page instead of the full businesses listing
})->name('home');

// Public featured page for guests
Route::get('/featured', [FeaturedController::class, 'index'])->name('featured');

use Illuminate\Http\Request as HttpRequest;

// Businesses index: Everyone can see the full listing
Route::get('/businesses', [BusinessController::class, 'index'])->name('businesses.index');
Route::get('/business-types', [BusinessTypeController::class, 'index'])->name('business-types.index');
Route::get('/contact-types', [ContactTypeController::class, 'index'])->name('contact-types.index');

// ✅ PUBLIC: Everyone (guests + authenticated) can view and submit testimonies
Route::get('/uc-testimonies', [UcTestimonyController::class, 'index'])->name('uc-testimonies.index');
Route::post('/uc-testimonies', [UcTestimonyController::class, 'store'])->name('uc-testimonies.store');

// ============================================================
// AUTHENTICATED ROUTES (Student, Alumni, Admin)
// ============================================================

Route::middleware(['auth', 'verified'])->group(function () {
    
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Import progress tracking endpoint
    Route::get('/import-progress/{sessionId}', [DashboardController::class, 'importProgress'])->name('import.progress');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('businesses', BusinessController::class)->except(['index', 'show']);
    Route::get('/regions/regencies', [BusinessController::class, 'regenciesByProvince'])->name('regions.regencies');

    Route::resource('businesses.product-categories', ProductCategoryController::class)
        ->scoped(['business' => 'id', 'productCategory' => 'id']);

    Route::resource('businesses.products', ProductController::class)
        ->except(['index'])
        ->scoped(['business' => 'id', 'product' => 'id']);

    Route::resource('businesses.services', ServiceController::class)
        ->except(['index'])
        ->scoped(['business' => 'id', 'service' => 'id']);

    Route::resource('businesses.photos', BusinessPhotoController::class)
        ->scoped(['business' => 'id', 'photo' => 'id']);

    Route::resource('businesses.contacts', BusinessContactController::class)
        ->except(['index'])
        ->scoped(['business' => 'id', 'contact' => 'id']);

    Route::resource('products.photos', ProductPhotoController::class)
        ->scoped(['product' => 'id', 'photo' => 'id']);

});

// ============================================================
// ADMIN-ONLY ROUTES
// ============================================================

Route::middleware(['auth', 'verified', 'admin'])->group(function () {
    
    // Testimony Moderation
    Route::delete('/uc-testimonies/{ucTestimony}', [UcTestimonyController::class, 'destroy'])->name('uc-testimonies.destroy');
    Route::get('/ai-analyses', [AiAnalysisController::class, 'index'])->name('ai-analyses.index');
    Route::get('/uc-testimonies/{ucTestimony}/detail', [AiAnalysisController::class, 'showUc'])->name('uc-testimonies.detail');
    Route::post('/uc-testimonies/{ucTestimony}/approve', [AiAnalysisController::class, 'approve'])->name('uc-testimonies.approve');
    Route::post('/uc-testimonies/{ucTestimony}/reject', [AiAnalysisController::class, 'reject'])->name('uc-testimonies.reject');

    Route::resource('users', UserController::class);
    Route::post('/users/import', [UserController::class, 'import'])->name('users.import');
    Route::get('/users/template/download', [UserController::class, 'downloadTemplate'])->name('users.template');

    Route::resource('business-types', BusinessTypeController::class)->except(['index', 'show']);
    Route::resource('contact-types', ContactTypeController::class)->except(['index', 'show']);
    
    Route::post('/businesses/import', [BusinessController::class, 'import'])->name('businesses.import');
    Route::post('/businesses/{business}/toggle-featured', [BusinessController::class, 'toggleFeatured'])
        ->name('businesses.toggle-featured');
});

// ============================================================
// PUBLIC SHOW ROUTES (Must be after specific routes)
// ============================================================

Route::get('/businesses/{business}', [BusinessController::class, 'show'])->name('businesses.show');
Route::get('/business-types/{businessType}', [BusinessTypeController::class, 'show'])->name('business-types.show');
Route::get('/contact-types/{contactType}', [ContactTypeController::class, 'show'])->name('contact-types.show');

// ============================================================
// ============================================================
// DATABASE RESET ROUTES (TEMPORARY - DELETE AFTER USE)
// ============================================================

Route::middleware(['auth', 'verified', 'admin'])->group(function () {
    Route::get('/admin/reset-database-confirm', function () {
        return view('admin.reset-database');
    })->name('admin.reset-database');

    Route::post('/admin/reset-database-execute', function () {
        try {
            $businessCount = \App\Models\Business::count();
            \App\Models\Business::query()->delete();
            
            \App\Models\BusinessType::query()->delete();
            \App\Models\ProductCategory::query()->delete();
            \App\Models\ContactType::query()->delete();
            
            $userCount = \App\Models\User::count();
            \App\Models\User::query()->delete();
            
            \App\Models\User::create([
                'username' => 'admin',
                'name' => 'Admin UCO',
                'email' => 'admin@uco.com',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'role' => 'admin',
                'is_active' => true,
                'email_verified_at' => now(),
            ]);
            
            \Illuminate\Support\Facades\Auth::logout();
            request()->session()->invalidate();
            request()->session()->regenerateToken();
            
            return redirect('/login')->with('success', "✅ Database reset! Deleted {$businessCount} businesses and {$userCount} users. Login as admin@uco.com / password");
            
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    })->name('admin.reset-database.execute');
});

require __DIR__.'/auth.php';
