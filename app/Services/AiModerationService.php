<?php

namespace App\Services;

use Gemini\Laravel\Facades\Gemini;
use Illuminate\Support\Facades\Log;

class AiModerationService
{
    /**
     * Analyze testimony content using Google Gemini AI
     * 
     * @param string $content Testimony text
     * @param int $rating Star rating (1-5)
     * @param string $customerName Customer name
     * @return array ['sentiment_score' => float, 'is_approved' => bool, 'rejection_reason' => string|null]
     */
    public function analyze(string $content, int $rating = 0, string $customerName = ''): array
    {
        try {
            // Build analysis prompt
            $prompt = $this->buildPrompt($content, $rating, $customerName);

            $model = env('GEMINI_MODEL', 'gemini-2.5-flash');

            // Call Gemini API with an explicit model name
            $response = Gemini::generativeModel(model: $model)->generateContent($prompt);

            // Raw model output (often wrapped in ```json)
            $rawText = $response->text();

            // DEBUG: print to terminal (artisan serve) + log file when enabled
            if (env('GEMINI_DEBUG', false) || config('app.debug')) {
                $snippet = mb_substr($rawText, 0, 2000);
                error_log("[Gemini debug] model={$model} rating={$rating} response_snippet=" . $snippet);
                Log::debug('Gemini raw response', [
                    'model' => $model,
                    'rating' => $rating,
                    'response' => $rawText,
                ]);
            }

            $result = $this->parseGeminiResponse($rawText, $rating);

            Log::info('Gemini AI Analysis', [
                'model' => $model,
                'content' => substr($content, 0, 100),
                'rating' => $rating,
                'result' => $result,
            ]);
            
            return $result;
            
        } catch (\Exception $e) {
            Log::error('Gemini AI Error', [
                'error' => $e->getMessage(),
                'content' => substr($content, 0, 100)
            ]);
            
            // Fallback to simple heuristic if AI fails
            return $this->fallbackAnalysis($content, $rating);
        }
    }

    /**
     * Build prompt for Gemini AI
     */
    private function buildPrompt(string $content, int $rating, string $customerName): string
    {
        return <<<PROMPT
You are an AI content moderator for Universitas Ciputra's student & alumni business platform.

Analyze this testimony and respond with ONLY a JSON object (no markdown, no explanation):

{
  "sentiment_score": 0-100,
  "is_approved": true/false,
  "rejection_reason": "string or null"
}

**APPROVAL RULES:**
1. ✅ APPROVE (sentiment ≥ 60%) if: Positive/neutral sentiment, constructive feedback, genuine experience
2. ❌ REJECT if: Hate speech, profanity, spam, fake reviews, irrelevant content, personal attacks, suspicious names
3. Consider both content quality and rating coherence (5 stars should have positive content, 1 star can be negative if constructive)

**Testimony Details:**
- Customer: {$customerName}
- Rating: {$rating}/5 stars
- Content: "{$content}"

**Context:**
- Language: Indonesian & English both accepted
- Platform: UCO student/alumni business testimonies
- Threshold: Approve if sentiment_score >= 60

Respond with valid JSON only:
PROMPT;
    }

    /**
     * Parse Gemini response into structured array
     */
    private function parseGeminiResponse(string $text, int $rating): array
    {
        // Remove markdown code blocks if present
        $text = str_replace('```json', '', $text);
        $text = str_replace('```', '', $text);
        $text = trim($text);
        
        // Try to decode JSON
        $json = json_decode($text, true);
        
        if (!is_array($json)) {
            Log::warning('Failed to parse Gemini JSON', ['response' => $text]);
            return $this->fallbackAnalysis('', $rating);
        }
        
        // Validate and structure response
        $sentimentScore = isset($json['sentiment_score']) 
            ? (float) max(0, min(100, $json['sentiment_score'])) 
            : 0.0;
            
        $isApproved = isset($json['is_approved']) 
            ? (bool) $json['is_approved'] 
            : false;
            
        $rejectionReason = $isApproved 
            ? null 
            : ($json['rejection_reason'] ?? 'Content requires review by administrators');
        
        return [
            'sentiment_score' => $sentimentScore,
            'is_approved' => $isApproved,
            'rejection_reason' => $rejectionReason,
        ];
    }

    /**
     * Fallback analysis using simple heuristics
     */
    private function fallbackAnalysis(string $content, int $rating): array
    {
        // Simple keyword-based sentiment
        $negativeKeywords = ['buruk', 'jelek', 'mengecewakan', 'bad', 'terrible', 'worst', 'horrible', 'scam', 'palsu'];
        $positiveKeywords = ['bagus', 'baik', 'memuaskan', 'recommended', 'good', 'great', 'excellent', 'amazing'];
        
        $lowerContent = strtolower($content);
        $negativeCount = 0;
        $positiveCount = 0;
        
        foreach ($negativeKeywords as $word) {
            if (stripos($lowerContent, $word) !== false) $negativeCount++;
        }
        
        foreach ($positiveKeywords as $word) {
            if (stripos($lowerContent, $word) !== false) $positiveCount++;
        }
        
        // Calculate sentiment score
        $baseScore = $rating * 20; // Convert 1-5 to 0-100
        $keywordAdjustment = ($positiveCount - $negativeCount) * 10;
        $sentimentScore = max(0, min(100, $baseScore + $keywordAdjustment));
        
        // Auto-approve if sentiment >= 60%
        $isApproved = $sentimentScore >= 60;
        
        return [
            'sentiment_score' => round($sentimentScore, 2),
            'is_approved' => $isApproved,
            'rejection_reason' => $isApproved ? null : 'AI service unavailable - requires manual review',
        ];
    }
}
