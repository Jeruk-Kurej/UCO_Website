<?php

namespace App\Http\Controllers;

use App\Models\Testimony;
use App\Models\AiAnalysis;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AiAnalysisController extends Controller
{
    /**
     * Get authenticated user as User instance
     */
    private function getAuthUser(): User
    {
        /** @var User $user */
        $user = Auth::user();
        
        if (!$user) {
            abort(401, 'Unauthenticated.');
        }
        
        return $user;
    }

    /**
     * Check if user can view AI analysis
     * Admin can view all, Business owner can view their own testimonies
     */
    private function authorizeAnalysisAccess(Testimony $testimony): void
    {
        $user = $this->getAuthUser();
        
        // Load business relationship
        $testimony->load('business');
        
        // Allow if admin OR business owner
        if (!$user->isAdmin() && $testimony->business->user_id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }
    }

    /**
     * Display the AI analysis for a testimony.
     * READ ONLY - No create/update because AI generates this automatically.
     */
    public function show(Testimony $testimony)
    {
        $this->authorizeAnalysisAccess($testimony);

        // Load AI analysis
        $analysis = $testimony->aiAnalysis;

        if (!$analysis) {
            abort(404, 'AI Analysis not found for this testimony.');
        }

        $testimony->load('business');

        return view('ai-analyses.show', compact('testimony', 'analysis'));
    }

    /**
     * Display a listing of AI analyses (Admin only - for monitoring)
     */
    public function index()
    {
        $user = $this->getAuthUser();

        if (!$user->isAdmin()) {
            abort(403, 'Only administrators can view all AI analyses.');
        }

        $analyses = AiAnalysis::with(['testimony.business'])
            ->latest()
            ->paginate(20);

        return view('ai-analyses.index', compact('analyses'));
    }
}
