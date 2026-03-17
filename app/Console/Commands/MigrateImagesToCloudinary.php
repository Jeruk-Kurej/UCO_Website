<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Exception;

class MigrateImagesToCloudinary extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:images-to-cloudinary
                            {--tables= : Comma-separated table.column pairs (default: businesses.logo_url,business_photos.photo_url,product_photos.photo_url,users.profile_photo_url)}
                            {--limit=0 : Maximum rows per table to process (0 = no limit)}
                            {--dry-run : Do not write to disk or update database}
                            {--domain= : Only process URLs that contain this domain (optional)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Download remote image URLs and upload them to the configured filesystem (e.g. Cloudinary). Updates DB columns to the new stored URL.';

    public function handle()
    {
        $disk = config('filesystems.default', env('FILESYSTEM_DISK', 'public'));
        $tablesOption = $this->option('tables') ?? 'businesses.logo_url,business_photos.photo_url,product_photos.photo_url,users.profile_photo_url';
        $tables = array_map('trim', explode(',', $tablesOption));
        $limit = (int) $this->option('limit');
        $dry = $this->option('dry-run');
        $domain = $this->option('domain');

        $this->info("Using disk: {$disk}");

        foreach ($tables as $pair) {
            if (!str_contains($pair, '.')) {
                $this->error("Invalid table.column format: {$pair}");
                continue;
            }

            [$table, $column] = explode('.', $pair, 2);

            $query = DB::table($table)->where($column, 'like', 'http%');
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
                    $this->line("Skipping id={$id} (empty)");
                    continue;
                }

                // Skip already-cloudinary URLs
                if (str_contains($url, 'cloudinary.com') || str_contains($url, 'res.cloudinary.com')) {
                    $this->line("Skipping already-cloudinary id={$id} -> {$url}");
                    continue;
                }

                try {
                    $this->line("Downloading id={$id} -> {$url}");
                    $response = Http::timeout(30)->get($url);
                    if (!$response->ok()) {
                        $this->error("Failed to download id={$id}: HTTP {$response->status()}");
                        continue;
                    }

                    $contents = $response->body();
                    $pathInfo = pathinfo(parse_url($url, PHP_URL_PATH) ?? '');
                    $basename = $pathInfo['basename'] ?? 'image.jpg';
                    $sanitized = preg_replace('/[^A-Za-z0-9_.-]/', '_', $basename);
                    $ext = $pathInfo['extension'] ?? null;
                    if (!$ext) {
                        // try to guess from content-type
                        $ct = $response->header('Content-Type', 'image/jpeg');
                        if (str_contains($ct, 'png')) $ext = 'png';
                        else if (str_contains($ct, 'gif')) $ext = 'gif';
                        else $ext = 'jpg';
                    }

                    $filename = time() . '_' . $sanitized;
                    if (!str_ends_with($filename, ".{$ext}")) {
                        $filename .= ".{$ext}";
                    }

                    $targetPath = "migrated/{$table}/{$id}/{$filename}";

                    if ($dry) {
                        $this->info("[dry-run] would store {$url} -> {$disk}:{$targetPath}");
                        continue;
                    }

                    $stored = Storage::disk($disk)->put($targetPath, $contents);
                    if (!$stored) {
                        $this->error("Failed storing id={$id} to disk");
                        continue;
                    }

                    $newUrl = Storage::disk($disk)->url($targetPath);
                    DB::table($table)->where('id', $id)->update([$column => $newUrl]);
                    $this->info("Stored and updated id={$id} -> {$newUrl}");

                } catch (Exception $e) {
                    $this->error("Error processing id={$id}: " . $e->getMessage());
                }
            }
        }

        $this->info('Done.');
        return 0;
    }
}
