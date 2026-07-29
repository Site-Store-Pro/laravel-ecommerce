<?php

namespace App\Http\Controllers;

use App\Models\OrderDetail;
use App\Models\OrderDownload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductDownloadController extends Controller
{
    public function download(OrderDetail $orderDetail, string $token)
    {
        // 1. Authorize token matching order external UUID
        abort_unless(
            $orderDetail->order && $orderDetail->order->order_external_id === $token,
            403,
            'Invalid download link or token.'
        );

        // 2. Ensure order status is paid / active (1=Pending, 2=Shipped, 5=Partially Shipped, 7=Completed, 8=Partially Refunded)
        abort_unless(
            in_array($orderDetail->order->order_status, [1, 2, 5, 7, 8]),
            403,
            'Order has not been paid or is not active.'
        );

        // 3. Ensure this item is actually a download item
        abort_unless(
            $orderDetail->download_item,
            403,
            'This product is not a downloadable item.'
        );

        // 4. Check if download link has expired
        if ($orderDetail->download_expiration && now()->greaterThan($orderDetail->download_expiration)) {
            abort(403, 'Your download link has expired.');
        }

        // 5. Check if maximum downloads limit is reached
        if ($orderDetail->downloads_counter >= $orderDetail->downloads_max_allowed) {
            abort(403, 'Maximum download attempts reached for this item.');
        }

        // 6. Record download attempt
        $orderDetail->increment('downloads_counter');
        OrderDownload::create([
            'order_details_id' => $orderDetail->id,
            'user_id' => auth()->id() ?? 0,
            'download_date' => now(),
        ]);

        // Load the variant to pull all file storage info dynamically
        $variant = $orderDetail->variant;
        abort_unless(
            $variant,
            404,
            'Associated product variant could not be found.'
        );

        // 6.5 Direct URL Download Mode (Overrides any uploaded download files or S3 options)
        if (!empty($variant->direct_download_url)) {
            $directUrl = trim($variant->direct_download_url);
            $filename = basename(parse_url($directUrl, PHP_URL_PATH)) ?: 'download-file';

            if (!str_contains($filename, '.')) {
                $filename .= '.bin';
            }

            try {
                $httpResponse = \Illuminate\Support\Facades\Http::timeout(30)->get($directUrl);
                if ($httpResponse->successful()) {
                    $contentType = $httpResponse->header('Content-Type') ?: 'application/octet-stream';
                    return response()->streamDownload(function () use ($httpResponse) {
                        echo $httpResponse->body();
                    }, $filename, [
                        'Content-Type' => $contentType,
                        'Content-Disposition' => 'attachment; filename="' . $filename . '"',
                    ]);
                }
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::error("Direct download URL stream failed for variant {$variant->id}: " . $e->getMessage());
            }

            return redirect()->away($directUrl);
        }

        // 7. Resolve storage disk
        $diskName = 'public';
        if ($variant->download_s3 == 1) {
            $diskName = 's3';
        } elseif ($variant->download_s3 == 2) {
            $diskName = 'custom_s3_var_' . $variant->id;
            config([
                "filesystems.disks.{$diskName}" => [
                    'driver' => 's3',
                    'key' => $variant->download_s3_access_key_id,
                    'secret' => $variant->download_s3_secret_access_key,
                    'region' => $variant->download_s3_region,
                    'bucket' => $variant->download_s3_bucket_name,
                    'use_path_style_endpoint' => false,
                ]
            ]);
        }

        // Ensure download location is set and file exists on storage
        abort_unless(
            !empty($variant->download_location) && Storage::disk($diskName)->exists($variant->download_location),
            404,
            'The requested file could not be found on storage.'
        );

        // 8. Redirect or download
        if ($variant->download_s3 == 1 || $variant->download_s3 == 2) {
            $cdn = $variant->download_cdn_url ?: config('app.cdn_url');
            if ($cdn) {
                $url = rtrim($cdn, '/') . '/' . ltrim($variant->download_location, '/');
                return redirect()->away($url);
            }
            return redirect()->away(Storage::disk($diskName)->url($variant->download_location));
        }

        return Storage::disk($diskName)->download($variant->download_location);
    }
}
