<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CmsImageUploadController extends Controller
{
    public function upload(Request $request)
    {
        abort_unless(auth()->check(), 403);

        $request->validate([
            'file' => 'required|image|max:5120',
        ]);

        $uploaded = $request->file('file');

        if (! $uploaded) {
            return response()->json(['error' => 'No file received by server.'], 400);
        }

        try {
            $extension = $uploaded->getClientOriginalExtension() ?: 'jpg';
            $filename  = uniqid('img_', true) . '.' . $extension;

            // Ensure the target directory exists on the real filesystem
            // (Storage::disk does not auto-create parent dirs on all environments)
            $realDir = storage_path('app/public/cms_inline');
            if (! is_dir($realDir)) {
                @mkdir($realDir, 0775, true);
            }

            // Store via Laravel's Storage facade (works in tests with Storage::fake too)
            $path = $uploaded->storeAs('cms_inline', $filename, ['disk' => 'public']);

            if ($path === false || $path === '') {
                Log::error('TinyMCE upload: storeAs returned false/empty', [
                    'dir'        => $realDir,
                    'dir_exists' => is_dir($realDir),
                    'writable'   => is_writable($realDir),
                ]);
                return response()->json([
                    'error' => 'File could not be saved. Directory writable: ' . (is_writable($realDir) ? 'yes' : 'no'),
                ], 500);
            }

            Log::info('TinyMCE image upload success', ['path' => $path]);

            return response()->json([
                'location' => '/storage/' . $path,
            ]);

        } catch (\Throwable $e) {
            Log::error('TinyMCE upload exception', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
            ]);
            return response()->json([
                'error' => 'Upload exception: ' . $e->getMessage(),
            ], 500);
        }
    }
}
