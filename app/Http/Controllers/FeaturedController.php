<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\BusinessType;
use App\Models\UcTestimony;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FeaturedController extends Controller
{
    /**
     * Show featured businesses to public guests.
     */
    public function index(Request $request)
    {
        // Keep admin-picked featured items strictly from is_featured=true
        $featuredBusinesses = Business::with(['businessType', 'photos', 'user'])
            ->where('is_featured', true)
            ->latest()
            ->get();

        // Fallback: IF no businesses are manually featured, show recent ones so the page isn't empty
        if ($featuredBusinesses->isEmpty()) {
            $featuredBusinesses = Business::with(['businessType', 'photos', 'user'])
                ->latest()
                ->take(6)
                ->get();
        }

        $featuredBusinesses = $featuredBusinesses
            ->sortByDesc(fn(Business $business) => $this->businessQualityScore($business))
            ->values()
            ->map(function (Business $business) {
                $business->display_description = $this->buildBusinessDisplayDescription($business);
                $business->display_category = Str::limit((string) ($business->businessType->name ?? 'Business'), 32);

                return $business;
            });

        // Hero spotlight comes from featured items only
        $spotlightBusinesses = $featuredBusinesses->take(4)->values();

        $businessTypes = BusinessType::query()
            ->withCount('businesses')
            ->orderByDesc('businesses_count')
            ->take(8)
            ->get();

        $testimonies = UcTestimony::query()
            ->with('aiAnalysis')
            ->whereHas('aiAnalysis', function ($query) {
                $query->where('is_approved', true);
            })
            ->latest()
            ->take(20)
            ->get()
            ->map(function (UcTestimony $testimony) {
                $testimony->display_content = $this->cleanTestimonyContent((string) $testimony->content);

                return $testimony;
            })
            ->filter(fn(UcTestimony $testimony) => $testimony->display_content !== '')
            ->take(6)
            ->values();

        $partners = [
            'UCO Incubator Network',
            'Student Founder Circle',
            'Alumni Mentor Guild',
            'Creative Product Lab',
            'Campus Market Access',
            'SME Growth Collective',
            'Digital Commerce Studio',
            'Community Business Hub',
        ];

        return view('featured.index', [
            'featuredBusinesses' => $featuredBusinesses,
            'spotlightBusinesses' => $spotlightBusinesses,
            'businessTypes' => $businessTypes,
            'testimonies' => $testimonies,
            'partners' => $partners,
        ]);
    }

    private function businessQualityScore(Business $business): int
    {
        $score = 0;

        if (!empty($business->photos->first())) {
            $score += 3;
        }

        if (!empty($business->logo_url)) {
            $score += 1;
        }

        $description = trim((string) ($business->description ?? ''));
        if (!$this->isWeakText($description, 12)) {
            $score += 2;
        }

        if (!empty($business->user?->profile_photo_url)) {
            $score += 1;
        }

        if (!empty($business->businessType?->name)) {
            $score += 1;
        }

        return $score;
    }

    private function buildBusinessDisplayDescription(Business $business): string
    {
        $raw = trim((string) ($business->description ?? ''));
        if (!$this->isWeakText($raw, 12)) {
            return Str::limit($this->normalizeSentence($raw), 140);
        }

        $ownerFirstName = trim((string) Str::before((string) ($business->user?->name ?? 'Founder'), ' '));
        if ($ownerFirstName === '') {
            $ownerFirstName = 'Founder';
        }

        $businessType = trim((string) ($business->businessType?->name ?? 'business venture'));
        $modeLabel = match ($business->business_mode) {
            'product' => 'product-focused',
            'service' => 'service-driven',
            default => 'hybrid product-service',
        };

        $templates = [
            "{$ownerFirstName}'s {$modeLabel} {$businessType} growing through the UCO student and alumni network.",
            "A {$modeLabel} {$businessType} built by {$ownerFirstName} to serve real market needs.",
            "Community-powered {$businessType} venture by {$ownerFirstName}, showcasing UCO entrepreneurial spirit.",
        ];

        $index = abs(crc32((string) $business->id . '|' . (string) $business->name)) % count($templates);

        return Str::limit($templates[$index], 140);
    }

    private function cleanTestimonyContent(string $content): string
    {
        $clean = trim(preg_replace('/\s+/', ' ', $content) ?? '');

        if ($clean === '') {
            return '';
        }

        if ($this->isWeakText($clean, 20)) {
            return '';
        }

        $wordCount = str_word_count($clean);
        if ($wordCount < 4) {
            return '';
        }

        return Str::limit($this->normalizeSentence($clean), 260);
    }

    private function normalizeSentence(string $value): string
    {
        $value = trim(preg_replace('/\s+/', ' ', $value) ?? '');
        if ($value === '') {
            return '';
        }

        return Str::ucfirst($value);
    }

    private function isWeakText(string $value, int $minimumLength = 10): bool
    {
        $normalized = strtolower(trim(preg_replace('/\s+/', ' ', $value) ?? ''));

        if ($normalized === '' || Str::length($normalized) < $minimumLength) {
            return true;
        }

        $weakValues = [
            'no description provided',
            'tidak ada deskripsi',
            'n/a',
            '-',
            '--',
            'test',
            'lorem ipsum',
            'placeholder',
        ];

        if (in_array($normalized, $weakValues, true)) {
            return true;
        }

        $lettersOnly = preg_replace('/[^a-z]/', '', $normalized) ?? '';
        if ($lettersOnly !== '' && Str::length($lettersOnly) <= 8) {
            $uniqueChars = count(array_unique(str_split($lettersOnly)));
            if ($uniqueChars <= 3) {
                return true;
            }
        }

        return false;
    }
}
