<?php

namespace App\Http\Controllers;

use App\Models\UcAiAnalysis;
use App\Models\UcTestimony;
use App\Models\User;
use App\Services\AiModerationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UcTestimonyController extends Controller
{
    private function getAuthUserOrNull(): ?User
    {
        /** @var User|null $user */
        $user = Auth::user();
        return $user;
    }

    public function index()
    {
        $testimonies = UcTestimony::query()
            ->with('aiAnalysis')
            ->whereHas('aiAnalysis', function ($query) {
                $query->where('is_approved', true);
            })
            ->latest()
            ->paginate(10);

        return view('uc-testimonies.index', compact('testimonies'));
    }

    public function store(Request $request)
    {
        $user = $this->getAuthUserOrNull();

        if (!$user) {
            if ($request->wantsJson()) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }
            abort(401, 'Unauthenticated.');
        }

        if ($user->isAdmin()) {
            if ($request->wantsJson()) {
                return response()->json(['message' => 'Administrators cannot create testimonies. Only students and alumni can.'], 403);
            }
            abort(403, 'Administrators cannot create testimonies. Only students and alumni can.');
        }

        try {
            $validated = $request->validate([
                'customer_name' => 'required|string|max:255',
                'content' => 'required|string|min:10',
                'rating' => 'required|integer|min:1|max:5',
            ]);

            $validated['date'] = now()->toDateString();

            $testimony = UcTestimony::create($validated);

            $aiService = app(AiModerationService::class);
            $result = $aiService->analyze(
                $validated['content'],
                (int) $validated['rating'],
                $validated['customer_name']
            );

            UcAiAnalysis::create([
                'uc_testimony_id' => $testimony->id,
                'sentiment_score' => $result['sentiment_score'],
                'rejection_reason' => $result['rejection_reason'],
                'is_approved' => $result['is_approved'],
            ]);

        // Always show a generic message to the submitter.
        // Non-approved items simply won't appear in the public list.
        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Your testimony has been submitted successfully!',
                'testimony' => $testimony,
            ], 201);
        }

        return redirect()
            ->route('uc-testimonies.index')
            ->with('success', 'Your testimony has been submitted.');
    }

    public function destroy(UcTestimony $ucTestimony)
    {
        /** @var User|null $user */
        $user = Auth::user();

        if (!$user) {
            abort(401, 'Unauthenticated.');
        }

        if (!$user->isAdmin()) {
            abort(403, 'Only administrators can delete testimonies.');
        }

        $ucTestimony->delete();

        return redirect()
            ->route('ai-analyses.index')
            ->with('success', 'UC testimony rejected and deleted.');
    }
}
