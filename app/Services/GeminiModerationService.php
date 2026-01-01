<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiModerationService
{
    /**
     * Gemini API endpoint
     */
    private const API_ENDPOINT = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent';

    /**
     * Analyze testimony content using Gemini API
     *
     * @param string $text The testimony content to analyze
     * @return array Returns ['score' => int, 'reason' => string, 'sentiment_label' => string]
     */
    public function analyze(string $text): array
    {
        try {
            $apiKey = config('services.gemini.api_key');

            // Validate API key
            if (empty($apiKey)) {
                Log::error('Gemini API key is not configured');
                return $this->defaultSafeResponse('API key not configured');
            }

            // Construct the prompt
            $prompt = $this->buildPrompt($text);

            // ✅ Added more detailed logging
            Log::info('Sending request to Gemini API', [
                'endpoint' => self::API_ENDPOINT,
                'content_length' => strlen($text)
            ]);

            // Make API request
            $response = Http::timeout(30)
                ->withoutVerifying() // ✅ Disable SSL verification (development only!)
                ->retry(3, 1000) // ✅ Retry 3 times with 1 second delay
                ->withHeaders([
                    'Content-Type' => 'application/json',
                ])
                ->post(self::API_ENDPOINT . '?key=' . $apiKey, [
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => $prompt]
                            ]
                        ]
                    ],
                    'generationConfig' => [
                        'temperature' => 0.1,
                        'maxOutputTokens' => 200,
                    ]
                ]);

            // Check if request was successful
            if (!$response->successful()) {
                Log::error('Gemini API request failed', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                return $this->defaultSafeResponse('API request failed: ' . $response->status());
            }

            // Parse the response
            $data = $response->json();
            
            // Extract the generated text
            $generatedText = $data['candidates'][0]['content']['parts'][0]['text'] ?? null;

            if (!$generatedText) {
                Log::error('Gemini API returned empty response');
                return $this->defaultSafeResponse('Empty API response');
            }

            // Clean and parse JSON (remove markdown formatting if present)
            $cleanedText = $this->cleanJsonResponse($generatedText);
            
            Log::info('Cleaned Gemini response', ['text' => $cleanedText]);
            
            $result = json_decode($cleanedText, true);

            // Validate the response structure
            if (!$this->isValidResponse($result)) {
                Log::warning('Invalid Gemini API response structure', ['response' => $generatedText]);
                return $this->defaultSafeResponse('Invalid response structure');
            }

            // Ensure score is within bounds
            $score = max(0, min(100, (int) $result['score']));
            $reason = trim($result['reason']);

            // Determine sentiment label
            $sentimentLabel = $this->getSentimentLabel($score);

            Log::info('Gemini analysis completed', [
                'score' => $score,
                'reason' => $reason,
                'sentiment' => $sentimentLabel
            ]);

            return [
                'score' => $score,
                'reason' => $reason,
                'sentiment_label' => $sentimentLabel,
            ];

        } catch (\Exception $e) {
            Log::error('Gemini moderation service error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return $this->defaultSafeResponse('Service error: ' . $e->getMessage());
        }
    }

    /**
     * Build the analysis prompt
     *
     * @param string $text
     * @return string
     */
    private function buildPrompt(string $text): string
    {
        return <<<PROMPT
You are a professional content moderator for a business review platform. Analyze the following customer testimony and assess its quality and appropriateness.

TESTIMONY:
"{$text}"

INSTRUCTIONS:
1. Evaluate the testimony based on:
   - Authenticity (does it seem genuine?)
   - Constructiveness (is it helpful feedback?)
   - Appropriateness (no spam, hate speech, or offensive content)
   - Relevance (related to business/service quality)

2. Assign a score from 0-100:
   - 90-100: Excellent, detailed, helpful review
   - 70-89: Good, constructive feedback
   - 50-69: Acceptable but needs review (vague, short, or slightly concerning)
   - 30-49: Questionable content (spam-like, inappropriate language)
   - 0-29: Toxic, hateful, or clearly spam

3. Provide a brief reason (max 10 words) explaining the score.

IMPORTANT: Return ONLY valid JSON without any markdown formatting. Do not include ```json or ``` in your response.

Format:
{
  "score": 85,
  "reason": "Detailed positive feedback about service quality"
}

Your response:
PROMPT;
    }

    /**
     * Clean JSON response from potential markdown formatting
     *
     * @param string $text
     * @return string
     */
    private function cleanJsonResponse(string $text): string
    {
        // Remove markdown code block formatting
        $text = preg_replace('/```json\s*/', '', $text);
        $text = preg_replace('/```\s*/', '', $text);
        
        // Trim whitespace
        $text = trim($text);

        return $text;
    }

    /**
     * Validate the API response structure
     *
     * @param mixed $result
     * @return bool
     */
    private function isValidResponse($result): bool
    {
        return is_array($result) 
            && isset($result['score']) 
            && isset($result['reason'])
            && is_numeric($result['score']);
    }

    /**
     * Get sentiment label based on score
     *
     * @param int $score
     * @return string
     */
    private function getSentimentLabel(int $score): string
    {
        if ($score >= 80) {
            return 'positive';
        } elseif ($score >= 60) {
            return 'neutral';
        } else {
            return 'negative';
        }
    }

    /**
     * Return a default safe response when API fails
     *
     * @param string $reason
     * @return array
     */
    private function defaultSafeResponse(string $reason): array
    {
        return [
            'score' => 50, // Neutral score - requires manual review
            'reason' => 'Pending manual review: ' . $reason,
            'sentiment_label' => 'neutral',
        ];
    }
}
