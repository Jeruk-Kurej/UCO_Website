<?php

namespace App\Http\Controllers;

use App\Models\Business;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the dashboard with featured businesses.
     */
    public function index(): View
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

        return view('dashboard', [
            'featuredBusinesses' => $featuredBusinesses
        ]);
    }
}
