<?php

namespace App\Http\Controllers;

use App\Models\UcAiAnalysis;
use App\Models\UcTestimony;
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
     * Display the AI analysis for a UC-wide testimony.
     * Admin only.
     */
    public function showUc(UcTestimony $ucTestimony)
    {
        $user = $this->getAuthUser();

        if (!$user->isAdmin()) {
            abort(403, 'Only administrators can view UC testimony AI analyses.');
        }

        $analysis = $ucTestimony->aiAnalysis;

        if (!$analysis) {
            abort(404, 'AI Analysis not found for this UC testimony.');
        }

        return view('uc-ai-analyses.show', [
            'testimony' => $ucTestimony,
            'analysis' => $analysis,
        ]);
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

        $ucAnalyses = UcAiAnalysis::with(['ucTestimony'])
            ->latest()
            ->paginate(20, ['*'], 'uc_page');

        // Calculate stats
        $totalCount = $ucAnalyses->total();
        $approvedCount = UcAiAnalysis::where('is_approved', true)->count();
        $rejectedCount = UcAiAnalysis::where('is_approved', false)->count();
        $approvalRate = $totalCount > 0 ? round(($approvedCount / $totalCount) * 100, 1) : 0;

return view('ai-analyses.index', compact('ucAnalyses', 'totalCount', 'approvedCount', 'rejectedCount', 'approvalRate'));    }
}
