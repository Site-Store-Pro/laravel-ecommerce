<?php

use Illuminate\Support\Facades\DB;

$cols = DB::select('DESCRIBE product_images');
foreach ($cols as $c) {
    echo $c->Field . ': ' . $c->Type . PHP_EOL;
}
