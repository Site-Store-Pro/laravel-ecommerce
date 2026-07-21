<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

try {
    $response = $kernel->handle($request = Illuminate\Http\Request::create('/'));
    if ($response->getStatusCode() === 500) {
        echo "500 ERROR DETECTED\n";
    }
} catch (Throwable $e) {
    echo "EXCEPTION CAUGHT: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}
