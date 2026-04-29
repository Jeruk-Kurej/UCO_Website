<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait HasImage
{
    /**
     * Resolve image URL handling Google Drive, Local Storage, and External URLs.
     * Fallback to UI-Avatars if no valid image found.
     */
    protected function resolveImage(?string $path, string $type = 'business'): string
    {
        $name = $this->name ?? 'UCO';
        $fallback = "https://ui-avatars.com/api/?name=" . urlencode($name) . "&color=FFFFFF&background=F97316";

        if (!$path) {
            return $fallback;
        }

        // Clean path (remove <br>, strip tags, trim)
        $path = preg_replace('/<br\s*\/?>/i', ' ', $path);
        $path = trim(strip_tags($path));

        if (!$path) {
            return $fallback;
        }

        // 1. Handle Google Drive Links
        if (Str::contains($path, ['drive.google.com', 'docs.google.com'])) {
            return $this->convertGoogleDriveLink($path);
        }

        // 2. Handle Absolute URLs (External)
        if (filter_var($path, FILTER_VALIDATE_URL)) {
            return $path;
        }

        // 3. Handle Local Storage Paths
        // If it exists in storage, return URL. Else fallback.
        if (Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->url($path);
        }

        return $fallback;
    }

    /**
     * Convert Google Drive sharing link to a direct image link.
     */
    private function convertGoogleDriveLink(string $url): string
    {
        $id = '';
        if (preg_match('/\/d\/([a-zA-Z0-9_-]+)/', $url, $matches)) {
            $id = $matches[1];
        } elseif (preg_match('/id=([a-zA-Z0-9_-]+)/', $url, $matches)) {
            $id = $matches[1];
        }

        if ($id) {
            return "https://docs.google.com/uc?export=view&id={$id}";
        }

        return $url;
    }
}
