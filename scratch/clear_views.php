<?php

foreach (glob(__DIR__ . '/../storage/framework/views/*.php') as $f) {
    @unlink($f);
}
echo "Compiled views cleared successfully.\n";
