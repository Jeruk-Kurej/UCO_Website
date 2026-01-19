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
        $query = Business::with(['user', 'businessType', 'products', 'photos'])
            ->where('is_featured', true);

        $businesses = $query->latest()->paginate(10)->withQueryString();

        $myBusinesses = collect();
        $businessTypes = BusinessType::all();

        return view('businesses.index', compact('businesses', 'myBusinesses', 'businessTypes'));
    }
}
