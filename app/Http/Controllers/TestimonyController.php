<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Testimony;
use App\Models\AiAnalysis;
use App\Services\AiModerationService;
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
        // ✅ ADDED: Block admin from creating testimonies
        $user = $this->getAuthUser();
        
        if ($user->isAdmin()) {
            abort(403, 'Administrators cannot create testimonies. Only students and alumni can.');
        }

        return view('testimonies.create', compact('business'));
    }

    /**
     * Store a newly created testimony in storage.
     * Includes AI moderation logic.
     */
    public function store(Request $request, Business $business)
    {
        // ✅ ADDED: Block admin from creating testimonies
        $user = $this->getAuthUser();
        
        if ($user->isAdmin()) {
            abort(403, 'Administrators cannot create testimonies. Only students and alumni can.');
        }

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

        // Use AI moderation service with all available data
        $aiService = app(AiModerationService::class);
        $result = $aiService->analyze(
            $validated['content'],
            (int)$validated['rating'],
            $validated['customer_name']
        );

        AiAnalysis::create([
            'testimony_id' => $testimony->id,
            'sentiment_score' => $result['sentiment_score'],
            'rejection_reason' => $result['rejection_reason'],
            'is_approved' => $result['is_approved'],
        ]);

        // Return response based on approval
        if ($result['is_approved']) {
            return redirect()
                ->route('businesses.show', $business)
                ->with('success', 'Thank you! Your testimony has been approved and published.');
        } else {
            return redirect()
                ->route('businesses.show', $business)
                ->with('warning', 'Your testimony has been received but is pending review. Reason: ' . $result['rejection_reason']);
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
    // Keep the mock helper for local testing if needed
    private function mockAiSentimentAnalysis(string $content, int $rating): float
    {
        $baseScore = $rating * 20;
        $negativeKeywords = ['bad', 'terrible', 'worst', 'horrible', 'awful', 'hate'];
        $negativeCount = 0;
        foreach ($negativeKeywords as $keyword) {
            if (stripos($content, $keyword) !== false) $negativeCount++;
        }
        $penalty = $negativeCount * 10;
        return round(max(0, $baseScore - $penalty), 2);
    }
}
