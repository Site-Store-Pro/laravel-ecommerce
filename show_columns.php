<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
$cols = Illuminate\Support\Facades\Schema::getColumns('products');
foreach ($cols as $c) {
    echo $c['name'] . "\n";
}

