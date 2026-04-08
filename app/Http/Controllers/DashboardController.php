<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

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
    public function importProgress($importId)
    {
        $prefix = "import_{$importId}";
        
        // Fetch status and errors from the shared cache (Database)
        $progress = Cache::get($prefix);

        if (!$progress) {
             // Fallback to ensure UI has something to work with initially
             $progress = [
                'status' => 'processing',
                'errors' => []
            ];
        }

        // Fetch atomic counts pushed by the background worker
        $total = (int) Cache::get("{$prefix}_total", 0);
        $current = (int) Cache::get("{$prefix}_current", 0);
        $success = (int) Cache::get("{$prefix}_success", 0);
        $skipped = (int) Cache::get("{$prefix}_skipped", 0);

        $progress['total'] = $total;
        $progress['current'] = $current;
        $progress['success'] = $success;
        $progress['skipped'] = $skipped;

        return response()->json($progress);
    }

    /**
     * Clear the active import session after completion or manual dismiss.
     */
    public function clearActiveImport(Request $request)
    {
        $type = $request->get('type', 'user');
        if ($type === 'business') {
            session()->forget('active_business_import_id');
        } else {
            session()->forget('active_user_import_id');
        }
        return response()->json(['status' => 'cleared']);
    }
}
