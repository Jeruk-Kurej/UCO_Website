<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\BusinessType;
use Illuminate\Http\Request;

class FeaturedController extends Controller
{
    /**
     * Show featured businesses to public guests.
     */
    public function index(Request $request)
    {
        // Use the same curated featured selection as the dashboard so guests
        // see the same card layout as authenticated users on the dashboard.
        $featuredBusinesses = Business::with(['businessType', 'photos', 'user'])
            ->where('is_featured', true)
            ->latest()
            ->take(6)
            ->get();

        if ($featuredBusinesses->count() < 6) {
            $remaining = 6 - $featuredBusinesses->count();
            $latestBusinesses = Business::with(['businessType', 'photos', 'user'])
                ->where('is_featured', false)
                ->latest()
                ->take($remaining)
                ->get();

            $featuredBusinesses = $featuredBusinesses->merge($latestBusinesses);
        }

        return view('dashboard', [
            'featuredBusinesses' => $featuredBusinesses
        ]);
    }
}
