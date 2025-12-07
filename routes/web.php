<?php

use App\Http\Controllers\AiAnalysisController;
use App\Http\Controllers\BusinessController;
use App\Http\Controllers\BusinessContactController;
use App\Http\Controllers\BusinessPhotoController;
use App\Http\Controllers\BusinessTypeController;
use App\Http\Controllers\ContactTypeController;
use App\Http\Controllers\ProductCategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductPhotoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\TestimonyController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes 🌍
|--------------------------------------------------------------------------
*/

// Welcome Page
Route::get('/', function () {
    return view('welcome');
})->name('home');

// ✅ Public Business Index (Read-Only)
Route::get('/businesses', [BusinessController::class, 'index'])->name('businesses.index');

// ✅ Public Business Types & Contact Types (Read Access for All)
Route::get('/business-types', [BusinessTypeController::class, 'index'])->name('business-types.index');
Route::get('/business-types/{businessType}', [BusinessTypeController::class, 'show'])->name('business-types.show');

Route::get('/contact-types', [ContactTypeController::class, 'index'])->name('contact-types.index');
Route::get('/contact-types/{contactType}', [ContactTypeController::class, 'show'])->name('contact-types.show');

/*
|--------------------------------------------------------------------------
| Authenticated Routes (Student, Alumni, Admin) 🔐
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])->group(function () {
    
    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Profile Management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    /*
    |--------------------------------------------------------------------------
    | ✅ FIXED: Business Management (Create, Edit, Update, Delete)
    | MUST come BEFORE public show route to avoid route conflict
    |--------------------------------------------------------------------------
    */
    Route::resource('businesses', BusinessController::class)->except(['index', 'show']);

    /*
    |--------------------------------------------------------------------------
    | Nested Resources: Business Child Entities
    |--------------------------------------------------------------------------
    */

    // Business Products
    Route::resource('businesses.products', ProductController::class)
        ->scoped(['product' => 'business']);

    // Business Services
    Route::resource('businesses.services', ServiceController::class)
        ->scoped(['service' => 'business']);

    // Business Photos
    Route::resource('businesses.photos', BusinessPhotoController::class)
        ->scoped(['photo' => 'business']);

    // Business Contacts
    Route::resource('businesses.contacts', BusinessContactController::class)
        ->scoped(['contact' => 'business']);

    /*
    |--------------------------------------------------------------------------
    | Product Photos (Nested under Products)
    |--------------------------------------------------------------------------
    */
    Route::resource('products.photos', ProductPhotoController::class)
        ->scoped(['photo' => 'product']);

    /*
    |--------------------------------------------------------------------------
    | Testimonies Management
    |--------------------------------------------------------------------------
    */
    Route::resource('businesses.testimonies', TestimonyController::class)
        ->scoped(['testimony' => 'business']);

    /*
    |--------------------------------------------------------------------------
    | AI Analysis (Read-Only) 🤖
    |--------------------------------------------------------------------------
    */
    Route::get('/ai-analyses', [AiAnalysisController::class, 'index'])->name('ai-analyses.index');
    Route::get('/testimonies/{testimony}/ai-analysis', [AiAnalysisController::class, 'show'])->name('ai-analyses.show');
});

/*
|--------------------------------------------------------------------------
| ✅ MOVED: Public Business Show Route (AFTER authenticated routes)
| This MUST come AFTER Route::resource to avoid catching /businesses/create
|--------------------------------------------------------------------------
*/
Route::get('/businesses/{business}', [BusinessController::class, 'show'])->name('businesses.show');

/*
|--------------------------------------------------------------------------
| Admin-Only Routes 👮‍♂️
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified', 'admin'])->group(function () {
    
    // User Management
    Route::resource('users', UserController::class);

    // ✅ Business Type Management (Admin CRUD)
    Route::resource('business-types', BusinessTypeController::class)->except(['index', 'show']);

    // ✅ Contact Type Management (Admin CRUD)
    Route::resource('contact-types', ContactTypeController::class)->except(['index', 'show']);

    // ✅ Product Categories nested under Business Types
    Route::resource('business-types.product-categories', ProductCategoryController::class)
        ->scoped(['productCategory' => 'businessType']);
});

require __DIR__.'/auth.php';