<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Exception;

class MigrateImagesToCloudinary extends Command
{
    protected $signature = 'migrate:images-to-cloudinary
                            {--tables= : Comma-separated table.column pairs (default: users.profile_photo_url,businesses.logo_url,companies.logo_url)}
                            {--limit=0 : Maximum rows per table to process (0 = no limit)}
                            {--dry-run : Do not write to disk or update database}
                            {--domain= : Only process URLs that contain this domain (optional)}
                            {--include-local : Also upload local extracted_images/ paths to Cloudinary}';

    protected $description = 'Download remote image URLs (with cookie auth for employee.uc.ac.id) and upload them to Cloudinary. Updates DB columns to new Cloudinary URL.';

    public function handle()
    {
        $disk = config('filesystems.default', env('FILESYSTEM_DISK', 'public'));
        $tablesOption = $this->option('tables') ?? 'users.profile_photo_url,businesses.logo_url,companies.logo_url';
        $tables = array_map('trim', explode(',', $tablesOption));
        $limit = (int) $this->option('limit');
        $dry = $this->option('dry-run');
        $domain = $this->option('domain');
        $includeLocal = $this->option('include-local');

        // Load UC cookies from .env for authenticated downloads
        $ucCookieRaw = env('UC_COOKIE_RAW', '');

        $this->info("Using disk: {$disk}");
        if ($ucCookieRaw) {
            $this->info("UC cookies loaded for employee.uc.ac.id auth.");
        }

        $stats = ['uploaded' => 0, 'skipped' => 0, 'failed' => 0];

        foreach ($tables as $pair) {
            if (!str_contains($pair, '.')) {
                $this->error("Invalid table.column format: {$pair}");
                continue;
            }

            [$table, $column] = explode('.', $pair, 2);

            // Build query: remote URLs + optionally local extracted_images paths
            $query = DB::table($table)->where(function ($q) use ($column, $includeLocal) {
                $q->where($column, 'like', 'http%');
                if ($includeLocal) {
                    $q->orWhere($column, 'like', 'extracted_images/%');
                }
            });

            if ($domain) {
                $query->where($column, 'like', "%{$domain}%");
            }
            if ($limit > 0) {
                $query->limit($limit);
            }

            $rows = $query->get();
            $count = $rows->count();
            $this->info("Found {$count} rows in {$table}.{$column}");

            foreach ($rows as $row) {
                $id = $row->id ?? null;
                $url = $row->{$column} ?? null;
                if (!$url) {
                    $stats['skipped']++;
                    continue;
                }

                // Skip already-cloudinary URLs
                if (str_contains($url, 'cloudinary.com') || str_contains($url, 'res.cloudinary.com')) {
                    $this->line("  ⏩ id={$id} already on Cloudinary");
                    $stats['skipped']++;
                    continue;
                }

                try {
                    $contents = null;
                    $basename = null;

                    // Case 1: Local file from extracted_images/
                    if (str_starts_with($url, 'extracted_images/')) {
                        $localPath = storage_path('app/public/' . $url);
                        if (!file_exists($localPath)) {
                            $this->error("  ❌ id={$id} local file missing: {$localPath}");
                            $stats['failed']++;
                            continue;
                        }
                        $contents = file_get_contents($localPath);
                        $basename = basename($url);
                        $this->line("  📁 id={$id} reading local: {$basename}");
                    }
                    // Case 2: employee.uc.ac.id URL (needs cookies)
                    elseif (str_contains($url, 'employee.uc.ac.id') && $ucCookieRaw) {
                        $this->line("  🔐 id={$id} downloading with cookies: {$url}");
                        $response = Http::timeout(30)
                            ->withHeaders(['Cookie' => $ucCookieRaw])
                            ->get($url);

                        if (!$response->ok()) {
                            $this->error("  ❌ id={$id} HTTP {$response->status()}");
                            $stats['failed']++;
                            continue;
                        }
                        $contents = $response->body();
                        $basename = basename(parse_url($url, PHP_URL_PATH) ?? 'image.jpg');
                    }
                    // Case 3: Regular public URL
                    else {
                        $this->line("  🌐 id={$id} downloading: {$url}");
                        $response = Http::timeout(30)->get($url);
                        if (!$response->ok()) {
                            $this->error("  ❌ id={$id} HTTP {$response->status()}");
                            $stats['failed']++;
                            continue;
                        }
                        $contents = $response->body();
                        $basename = basename(parse_url($url, PHP_URL_PATH) ?? 'image.jpg');
                    }

                    if (!$contents || strlen($contents) < 100) {
                        $this->error("  ❌ id={$id} empty or too small response");
                        $stats['failed']++;
                        continue;
                    }

                    // Sanitize filename
                    $sanitized = preg_replace('/[^A-Za-z0-9_.-]/', '_', $basename);
                    $targetPath = "uco/{$table}/{$id}/{$sanitized}";

                    if ($dry) {
                        $this->info("  [dry-run] would store → {$disk}:{$targetPath}");
                        continue;
                    }

                    // Upload to Cloudinary (or configured disk)
                    $stored = Storage::disk($disk)->put($targetPath, $contents);
                    if (!$stored) {
                        $this->error("  ❌ id={$id} failed to store on {$disk}");
                        $stats['failed']++;
                        continue;
                    }

                    // Get the new URL and update DB
                    $newUrl = Storage::disk($disk)->url($targetPath);
                    DB::table($table)->where('id', $id)->update([$column => $newUrl]);
                    $this->info("  ✅ id={$id} → {$newUrl}");
                    $stats['uploaded']++;

                } catch (Exception $e) {
                    $this->error("  ❌ id={$id}: " . $e->getMessage());
                    $stats['failed']++;
                }
            }
        }

        $this->newLine();
        $this->info("Done! Uploaded: {$stats['uploaded']} | Skipped: {$stats['skipped']} | Failed: {$stats['failed']}");
        return 0;
    }
}
