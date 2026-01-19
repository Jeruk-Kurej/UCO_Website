<?php

namespace App\Providers;

use App\Models\Business;
use App\Models\User;
use App\Policies\BusinessPolicy;
use App\Policies\UserPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Load custom helpers
        foreach (glob(app_path('Helpers') . '/*.php') as $filename) {
            require_once $filename;
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // ✅ Register policies
        Gate::policy(Business::class, BusinessPolicy::class);
        Gate::policy(User::class, UserPolicy::class); // ✅ ADDED
    }
}
