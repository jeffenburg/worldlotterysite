<?php

namespace App\Services;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use RuntimeException;

class SupabaseStorage
{
    private string $url;

    private string $key;

    private string $bucket;

    public function __construct()
    {
        $this->url = rtrim((string) config('services.supabase.url'), '/');
        $this->key = (string) config('services.supabase.service_key');
        $this->bucket = (string) config('services.supabase.bucket');
    }

    public function isConfigured(): bool
    {
        return $this->url !== '' && $this->key !== '' && $this->bucket !== '';
    }

    /**
     * Upload a file into the bucket and return its public URL.
     */
    public function upload(UploadedFile $file, string $folder): string
    {
        $this->ensureConfigured();

        $extension = strtolower($file->getClientOriginalExtension() ?: $file->extension());
        $path = trim($folder, '/').'/'.now()->format('Y/m').'/'.Str::uuid().'.'.$extension;

        try {
            $this->client()
                ->withHeaders(['x-upsert' => 'false'])
                ->withBody(file_get_contents($file->getRealPath()), $file->getMimeType() ?: 'application/octet-stream')
                ->post("{$this->url}/storage/v1/object/{$this->bucket}/{$path}")
                ->throw();
        } catch (RequestException $e) {
            throw new RuntimeException('Image upload failed: '.($e->response->json('message') ?? $e->getMessage()));
        }

        return $this->publicUrl($path);
    }

    /**
     * Delete a previously uploaded object. Accepts a public URL or a bucket path; ignores foreign URLs.
     */
    public function delete(?string $urlOrPath): void
    {
        if (! $urlOrPath || ! $this->isConfigured()) {
            return;
        }

        $path = $this->pathFromUrl($urlOrPath);

        if ($path === null) {
            return;
        }

        $this->client()->delete("{$this->url}/storage/v1/object/{$this->bucket}/{$path}");
    }

    // New sb_secret_/sb_publishable_ keys are not JWTs and must be sent on `apikey`; legacy service_role keys are JWTs sent as Bearer.
    private function client(): PendingRequest
    {
        $request = Http::withHeaders(['apikey' => $this->key]);

        return str_starts_with($this->key, 'sb_') ? $request : $request->withToken($this->key);
    }

    public function publicUrl(string $path): string
    {
        return "{$this->url}/storage/v1/object/public/{$this->bucket}/{$path}";
    }

    private function pathFromUrl(string $urlOrPath): ?string
    {
        $prefix = "{$this->url}/storage/v1/object/public/{$this->bucket}/";

        if (str_starts_with($urlOrPath, $prefix)) {
            return substr($urlOrPath, strlen($prefix));
        }

        if (! str_contains($urlOrPath, '://')) {
            return ltrim($urlOrPath, '/');
        }

        return null;
    }

    private function ensureConfigured(): void
    {
        if (! $this->isConfigured()) {
            throw new RuntimeException('Supabase Storage is not configured. Set SUPABASE_URL, SUPABASE_SERVICE_KEY and SUPABASE_STORAGE_BUCKET.');
        }

        if (str_starts_with($this->key, 'sb_publishable_')) {
            throw new RuntimeException('SUPABASE_SERVICE_KEY is a publishable key. Use the sb_secret_ key (Settings → API Keys) so uploads bypass RLS.');
        }
    }
}
