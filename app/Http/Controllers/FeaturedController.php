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
        // Prioritize featured businesses first
        $featuredBusinesses = Business::with(['businessType', 'photos', 'user'])
            ->where('is_featured', true)
            ->latest()
            ->take(6)
            ->get();

        // If less than 6 featured businesses, fill with latest non-featured
        if ($featuredBusinesses->count() < 6) {
            $remaining = 6 - $featuredBusinesses->count();
            $latestBusinesses = Business::with(['businessType', 'photos', 'user'])
                ->where('is_featured', false)
                ->latest()
                ->take($remaining)
                ->get();
            
            $featuredBusinesses = $featuredBusinesses->merge($latestBusinesses);
        }

        // Fetch approved testimonies for homepage
        $testimonies = \App\Models\UcTestimony::query()
            ->with('aiAnalysis')
            ->whereHas('aiAnalysis', function ($query) {
                $query->where('is_approved', true);
            })
            ->latest()
            ->take(6)
            ->get();

        return view('dashboard', [
            'featuredBusinesses' => $featuredBusinesses,
            'testimonies' => $testimonies,
        ]);
    }
}
