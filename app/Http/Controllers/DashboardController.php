<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the dashboard (now unified with Featured page).
     */
    public function index(Request $request)
    {
        return app(FeaturedController::class)->index($request);
    }

    /**
     * Track import progress by session ID.
     */
    public function importProgress($sessionId)
    {
        $progress = session("import_progress_{$sessionId}", ['current' => 0, 'total' => 0, 'status' => 'unknown']);
        return response()->json($progress);
    }
}
