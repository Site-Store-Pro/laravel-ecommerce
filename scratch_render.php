<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(Illuminate\Http\Request::create('/'));
echo "HOMEPAGE HTML START:\n";
echo substr($response->getContent(), 0, 500);
echo "\n";
