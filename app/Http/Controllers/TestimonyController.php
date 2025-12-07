<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Testimony;
use App\Models\AiAnalysis;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TestimonyController extends Controller
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
     * Display a listing of testimonies for a business.
     */
    public function index(Business $business)
    {
        $testimonies = $business->testimonies()
            ->with('aiAnalysis')
            ->whereHas('aiAnalysis', function ($query) {
                $query->where('is_approved', true);
            })
            ->latest()
            ->paginate(10);

        return view('testimonies.index', compact('business', 'testimonies'));
    }

    /**
     * Show the form for creating a new testimony.
     */
    public function create(Business $business)
    {
        return view('testimonies.create', compact('business'));
    }

    /**
     * Store a newly created testimony in storage.
     * Includes AI moderation logic.
     */
    public function store(Request $request, Business $business)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'content' => 'required|string|min:10',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        // Add business_id and current date
        $validated['business_id'] = $business->id;
        $validated['date'] = now()->toDateString();

        // Create testimony
        $testimony = Testimony::create($validated);

        // TODO: Integrate AI Service here to check score
        // Example: $aiResult = AiModerationService::analyze($testimony->content);
        
        // Placeholder AI logic - Replace with actual AI service
        $sentimentScore = $this->mockAiSentimentAnalysis($validated['content'], $validated['rating']);
        $isApproved = $sentimentScore >= 60; // Threshold: 60%
        $rejectionReason = null;

        if (!$isApproved) {
            $rejectionReason = 'Content flagged by AI moderation: Low sentiment score or inappropriate language detected.';
        }

        // Create AI Analysis record
        AiAnalysis::create([
            'testimony_id' => $testimony->id,
            'sentiment_score' => $sentimentScore,
            'rejection_reason' => $rejectionReason,
            'is_approved' => $isApproved,
        ]);

        // Return response based on approval
        if ($isApproved) {
            return redirect()
                ->route('businesses.show', $business)
                ->with('success', 'Thank you! Your testimony has been approved and published.');
        } else {
            return redirect()
                ->route('businesses.show', $business)
                ->with('warning', 'Your testimony has been received but is pending review. Reason: ' . $rejectionReason);
        }
    }

    /**
     * Display the specified testimony.
     */
    public function show(Business $business, Testimony $testimony)
    {
        $testimony->load('aiAnalysis');

        return view('testimonies.show', compact('business', 'testimony'));
    }

    /**
     * Remove the specified testimony from storage.
     * Only admin can delete.
     */
    public function destroy(Business $business, Testimony $testimony)
    {
        $user = $this->getAuthUser();

        // Only admin can delete testimonies
        if (!$user->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $testimony->delete(); // AI Analysis will cascade delete

        return redirect()
            ->route('businesses.show', $business)
            ->with('success', 'Testimony deleted successfully.');
    }

    /**
     * MOCK AI Sentiment Analysis (Replace with real AI service)
     * Returns a score between 0-100.
     */
    private function mockAiSentimentAnalysis(string $content, int $rating): float
    {
        // Simple mock logic based on rating and content length
        $baseScore = $rating * 20; // 1-5 rating → 20-100

        // Check for negative keywords (placeholder)
        $negativeKeywords = ['bad', 'terrible', 'worst', 'horrible', 'awful', 'hate'];
        $negativeCount = 0;
        
        foreach ($negativeKeywords as $keyword) {
            if (stripos($content, $keyword) !== false) {
                $negativeCount++;
            }
        }

        // Reduce score based on negative words
        $penalty = $negativeCount * 10;
        $finalScore = max(0, $baseScore - $penalty);

        return round($finalScore, 2);
    }
}
