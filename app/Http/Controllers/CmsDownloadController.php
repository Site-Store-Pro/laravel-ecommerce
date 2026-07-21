<?php

namespace App\Http\Controllers;

use App\Models\CmsDownload;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class CmsDownloadController extends Controller
{
    public function serve(int $id): mixed
    {
        $download = CmsDownload::find($id);

        // 404 — not found or inactive
        if (!$download || !$download->is_active) {
            abort(404);
        }

        // 410 — link has expired
        if ($download->isExpired()) {
            abort(410, 'This download link has expired.');
        }

        return match ((int) $download->source_type) {
            CmsDownload::SOURCE_LOCAL      => $this->serveLocal($download),
            CmsDownload::SOURCE_DIRECT_URL => $this->serveDirectUrl($download),
            CmsDownload::SOURCE_ENV_S3     => $this->serveEnvS3($download),
            CmsDownload::SOURCE_CUSTOM_S3  => $this->serveCustomS3($download),
            default                        => abort(404),
        };
    }

    // -------------------------------------------------------------------------
    // Mode 0 — Local storage (public disk, storage/app/public/cms_downloads/)
    // -------------------------------------------------------------------------
    private function serveLocal(CmsDownload $download): mixed
    {
        if (empty($download->file_path)) {
            abort(404);
        }

        $absolutePath = storage_path('app/public/' . ltrim($download->file_path, '/'));

        if (!file_exists($absolutePath)) {
            abort(404);
        }

        if ($download->force_download) {
            $filename = basename($download->file_path);
            return response()->download($absolutePath, $filename);
        }

        return redirect(Storage::disk('public')->url($download->file_path));
    }

    // -------------------------------------------------------------------------
    // Mode 1 — Direct URL (CDN or any public URL — simple redirect, no proxying)
    // -------------------------------------------------------------------------
    private function serveDirectUrl(CmsDownload $download): mixed
    {
        if (empty($download->cdn_url)) {
            abort(404);
        }

        return redirect()->away($download->cdn_url);
    }

    // -------------------------------------------------------------------------
    // Mode 2 — Env S3 (uses .env AWS_* credentials to generate pre-signed URL)
    // -------------------------------------------------------------------------
    private function serveEnvS3(CmsDownload $download): mixed
    {
        if (empty($download->s3_file_key)) {
            abort(404);
        }

        try {
            $url = Storage::disk('s3')->temporaryUrl(
                $download->s3_file_key,
                now()->addSeconds($download->s3_expiration_seconds ?: 600)
            );
            return redirect()->away($url);
        } catch (\Throwable $e) {
            \Log::error('[CmsDownloadController] Env S3 pre-sign failed for download #' . $download->id . ': ' . $e->getMessage());
            abort(500, 'Could not generate secure download link.');
        }
    }

    // -------------------------------------------------------------------------
    // Mode 3 — Custom S3 (per-file credentials, dynamically registered disk)
    // -------------------------------------------------------------------------
    private function serveCustomS3(CmsDownload $download): mixed
    {
        if (empty($download->s3_custom_file_key)) {
            abort(404);
        }

        $diskName = 'cms_dl_custom_' . $download->id;

        config([
            "filesystems.disks.{$diskName}" => [
                'driver'                  => 's3',
                'key'                     => $download->s3_custom_key,
                'secret'                  => $download->s3_custom_secret,
                'region'                  => $download->s3_custom_region,
                'bucket'                  => $download->s3_custom_bucket,
                'use_path_style_endpoint' => false,
            ],
        ]);

        try {
            $url = Storage::disk($diskName)->temporaryUrl(
                $download->s3_custom_file_key,
                now()->addSeconds($download->s3_custom_expiration_seconds ?: 600)
            );
            return redirect()->away($url);
        } catch (\Throwable $e) {
            \Log::error('[CmsDownloadController] Custom S3 pre-sign failed for download #' . $download->id . ': ' . $e->getMessage());
            abort(500, 'Could not generate secure download link.');
        }
    }
}
