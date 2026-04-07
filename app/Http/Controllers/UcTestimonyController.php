<?php

namespace App\Http\Controllers;

use App\Models\UcAiAnalysis;
use App\Models\UcTestimony;
use App\Models\User;
use App\Services\AiModerationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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

        // ✅ CHANGED: Allow guests to submit, but block admins
        if ($user && $user->isAdmin()) {
            abort(403, 'Administrators cannot create testimonies. Only students, alumni, and guests can.');
        }

        try {
            $rules = [
                'content' => 'required|string|min:10',
                'rating' => 'required|integer|min:1|max:5',
            ];

            if (!$user) {
                $rules['customer_name'] = 'required|string|max:255';
            }

            $validated = $request->validate($rules);

            if ($user) {
                $validated['customer_name'] = $user->name;
                $validated['user_id'] = $user->id;
            }

            $validated['date'] = now()->toDateString();

            $testimony = UcTestimony::create($validated);

            $rawCustomerName = (string) ($validated['customer_name'] ?? '');
            $customerNameForAi = trim(preg_replace(
                [
                    '/\\S+@\\S+\\.\\S+/i',           // remove emails
                    '/\\+?\\d[\\d\\-\\s\\(\\)]{5,}\\d/', // remove phone-like sequences
                    "/[^\\p{L}\\s\\-'.]/u"          // keep letters/spaces/hyphen/apostrophe/dot
                ],
                ['', '', ''],
                $rawCustomerName
            ));

            if ($customerNameForAi === '') {
                // Provide a generic label so the model still receives a valid Customer field
                $customerNameForAi = 'Guest';
            }

            $aiService = app(AiModerationService::class);
            $result = $aiService->analyze(
                $validated['content'],
                (int) $validated['rating'],
                $customerNameForAi
            );

            // Helpful debug log so we can trace when guest names are being sanitized
            Log::info('UcTestimony AI analysis', [
                'raw_customer_name' => $rawCustomerName,
                'sanitized_customer_name' => $customerNameForAi,
                'rating' => $validated['rating'],
                'sentiment_score' => $result['sentiment_score'] ?? null,
                'is_approved' => $result['is_approved'] ?? null,
            ]);

            UcAiAnalysis::create([
                'uc_testimony_id' => $testimony->id,
                'sentiment_score' => $result['sentiment_score'],
                'rejection_reason' => $result['rejection_reason'],
                'is_approved' => $result['is_approved'],
            ]);

            // Always show a generic message to the submitter.
            // Non-approved items simply won't appear in the public list.
            return redirect()
                ->route('uc-testimonies.index')
                ->with('success', 'Your testimony has been submitted.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'An error occurred while submitting your testimony. Please try again.'])->withInput();
        }
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
