<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->bootstrap();

// Try to actually store a test file to see what happens
try {
    $result = \Illuminate\Support\Facades\Storage::disk('public')->put('cms_inline/test.txt', 'test content');
    echo "Storage put result: " . ($result ? 'SUCCESS' : 'FAILED') . "\n";
    echo "File exists after put: " . (\Illuminate\Support\Facades\Storage::disk('public')->exists('cms_inline/test.txt') ? 'YES' : 'NO') . "\n";
    
    // Clean up
    \Illuminate\Support\Facades\Storage::disk('public')->delete('cms_inline/test.txt');
} catch (\Exception $e) {
    echo "Exception: " . $e->getMessage() . "\n";
}

// Check the actual storage path
$disk = \Illuminate\Support\Facades\Storage::disk('public');
$adapter = $disk->getAdapter();
echo "\nDisk root: " . $adapter->getPathPrefix() . "\n";
echo "Directory writable: " . (is_writable($adapter->getPathPrefix()) ? 'YES' : 'NO') . "\n";

// Also check if cms_inline dir can be created
$cmsInlinePath = $adapter->getPathPrefix() . 'cms_inline';
if (is_dir($cmsInlinePath)) {
    echo "cms_inline dir exists: YES\n";
    echo "cms_inline writable: " . (is_writable($cmsInlinePath) ? 'YES' : 'NO') . "\n";
} else {
    echo "cms_inline dir exists: NO (will be auto-created)\n";
}
