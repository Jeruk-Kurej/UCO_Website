<?php

namespace App\Traits;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use App\Models\ImageMapping;

trait UcAuthTrait
{
    /**
     * Check if the image has already been downloaded and mapped.
     */
    protected function getExistingMapping(string $url): ?string
    {
        try {
            $hash = sha1($url);
            $mapping = ImageMapping::where('url_hash', $hash)->first();
            
            if ($mapping) {
                return $mapping->stored_path;
            }
        } catch (\Exception $e) {
            Log::warning("Deduplication lookup failed: " . $e->getMessage());
        }
        
        return null;
    }

    /**
     * Save a new image mapping.
     */
    protected function storeImageMapping(string $url, string $path, string $disk): void
    {
        try {
            ImageMapping::updateOrCreate(
                ['url_hash' => sha1($url)],
                [
                    'source_url' => $url,
                    'stored_path' => $path,
                    'disk' => $disk
                ]
            );
        } catch (\Exception $e) {
            Log::warning("Failed to store image mapping: " . $e->getMessage());
        }
    }

    /**
     * Authenticate into the UC portal to get a valid session cookie for downloading images.
     * Caches the session cookie for 10 minutes to avoid repeatedly logging in.
     */
    protected function getUcSessionCookie(): ?string
    {
        // 1. Check if we have a manual session cookie provided in .env
        $manualCookie = config('services.uc.session_cookie');
        if ($manualCookie && $manualCookie !== 'PASTE_YOUR_COPIED_VALUE_HERE') {
            return $manualCookie;
        }

        // 2. Fallback: Check the singleton cache to avoid multiple logins in one request
        $username = config('services.uc.username');
        $password = config('services.uc.password');

        if (!$username || !$password) {
            Log::warning('UC_USERNAME or UC_PASSWORD missing in .env. Cannot download secure UC images.');
            return null;
        }

        // Return cached session if we already logged in recently
        if (Cache::has('uc_session_cookie')) {
            return Cache::get('uc_session_cookie');
        }

        try {
            // Step 1: Open the home page and grab the stc CSRF token
            $response = Http::timeout(15)->withOptions(['verify' => true])->get('https://employee.uc.ac.id/');
            
            if (!$response->ok()) {
                Log::error('Failed to access employee.uc.ac.id homepage.');
                return null;
            }

            // Extract the 'ci_session' initial cookie
            $cookies = $response->cookies();
            $ciSessionCookie = $cookies->getCookieByName('ci_session');
            $sessionValue = $ciSessionCookie ? $ciSessionCookie->getValue() : null;

            // Extract the 'stc' CSRF token from the HTML form using RegEx
            preg_match('/name="stc"\s+value="([^"]+)"/', $response->body(), $matches);
            $stcToken = $matches[1] ?? null;

            if (!$stcToken || !$sessionValue) {
                Log::error('Failed to extract CSRF stc token or session cookie from UC homepage.');
                return null;
            }

            // Step 2: Post to the login exactly like the Vue form does
            $loginResponse = Http::asForm()->withCookies(
                ['ci_session' => $sessionValue],
                'employee.uc.ac.id'
            )->post('https://employee.uc.ac.id/front/authenticate', [
                'stc' => $stcToken,
                'username' => $username,
                'password' => $password,
            ]);

            Log::warning("UC Login Response Status: " . $loginResponse->status() . " Body length: " . strlen($loginResponse->body()));

            // Sometimes it sets a new ci_session cookie upon success, capture it
            $newCookies = $loginResponse->cookies();
            $newCiSessionCookie = $newCookies->getCookieByName('ci_session');
            $finalSessionValue = $newCiSessionCookie ? $newCiSessionCookie->getValue() : $sessionValue;

            // Cache it for 10 minutes (queue jobs might run longer so better cache it)
            Cache::put('uc_session_cookie', $finalSessionValue, now()->addMinutes(10));

            return $finalSessionValue;

        } catch (\Exception $e) {
            Log::error('Exception during UC authentication: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Replaces standard Http::get with a cookie-authenticated GET for UC URLs.
     */
    protected function fetchSecureUcFile(string $url)
    {
        // Only use the bot login for employee.uc.ac.id domains
        if (str_contains($url, 'employee.uc.ac.id')) {
            $rawCookie = config('services.uc.cookie_raw');
            $sessionCookie = $this->getUcSessionCookie();

            $pendingRequest = Http::retry(3, 200)->timeout(15)->withOptions(['verify' => true])
                ->withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/121.0.0.0 Safari/537.36',
                    'Accept' => 'image/avif,image/webp,image/apng,image/svg+xml,image/*,*/*;q=0.8',
                    'Referer' => 'https://employee.uc.ac.id/',
                ]);

            // If we have a raw cookie string (best for bypassing IP/multiple cookie issues)
            if ($rawCookie && $rawCookie !== 'PASTE_FULL_COOKIE_HERE') {
                $pendingRequest = $pendingRequest->withHeaders(['Cookie' => $rawCookie]);
            } 
            // Fallback to the single ci_session cookie
            else if ($sessionCookie) {
                $pendingRequest = $pendingRequest->withCookies(['ci_session' => $sessionCookie], 'employee.uc.ac.id');
            }

            $response = $pendingRequest->get($url);

            if ($response->header('Content-Type') && str_contains($response->header('Content-Type'), 'text/html')) {
                Log::warning("UC image request returned HTML (Likely blocked). Snippet: " . substr($response->body(), 0, 200));
            }

            return $response;
        }
        
        // Fallback for non-UC public URLs
        return Http::retry(3, 200)->timeout(15)->withOptions(['verify' => true])
            ->withHeaders([
                'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/121.0.0.0 Safari/537.36',
            ])
            ->get($url);
    }
}
