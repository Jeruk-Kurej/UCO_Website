<?php

namespace App\Http\Controllers;

use App\Models\UcAiAnalysis;
use App\Models\UcTestimony;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AiAnalysisController extends Controller
{
    /**
     * Display the AI analysis for a UC-wide testimony.
     */
    public function showUc(UcTestimony $ucTestimony)
    {
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
        $ucAnalyses = UcAiAnalysis::with(['ucTestimony'])
            ->latest()
            ->paginate(20, ['*'], 'uc_page');

        // Calculate stats
        $totalCount = $ucAnalyses->total();
        $approvedCount = UcAiAnalysis::where('is_approved', true)->count();
        $rejectedCount = UcAiAnalysis::where('is_approved', false)->count();
        $approvalRate = $totalCount > 0 ? round(($approvedCount / $totalCount) * 100, 1) : 0;

return view('ai-analyses.index', compact('ucAnalyses', 'totalCount', 'approvedCount', 'rejectedCount', 'approvalRate'));    }

    /**
     * Manually approve a rejected testimony (Admin only)
     */
    public function approve(UcTestimony $ucTestimony)
    {
        $analysis = $ucTestimony->aiAnalysis;

        if (!$analysis) {
            return back()->with('error', "AI Analysis records could not be found for this testimony.");
        }

        // Update analysis to approved
        $analysis->update([
            'is_approved' => true,
            'rejection_reason' => null, // Clear rejection reason
        ]);

        $name = $ucTestimony->user->name ?? $ucTestimony->customer_name ?? 'this user';
        return back()->with('success', "Success! The testimony from '{$name}' has been manually approved and is now visible.");
    }

    /**
     * Manually reject (or re-reject) a testimony (Admin only)
     */
    public function reject(UcTestimony $ucTestimony, Request $request)
    {
        $analysis = $ucTestimony->aiAnalysis;

        if (!$analysis) {
            return back()->with('error', "AI Analysis records could not be found for this testimony.");
        }

        $reason = $request->input('rejection_reason', 'Rejected by administrator');

        $analysis->update([
            'is_approved' => false,
            'rejection_reason' => $reason,
        ]);

        $name = $ucTestimony->user->name ?? $ucTestimony->customer_name ?? 'this user';
        return back()->with('success', "The testimony from '{$name}' has been rejected.");
    }
}
