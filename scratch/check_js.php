<?php
$jsFiles = glob('C:/Sites/laravel-gemini/public/build/assets/app-*.js');
foreach ($jsFiles as $f) {
    $c = file_get_contents($f);
    // Look for closeOtherDetails or the string literal faq-group
    $found1 = strpos($c, 'removeAttribute("open")') !== false;
    $found2 = strpos($c, 'faq-group') !== false || strpos($c, 'closest("summary")') !== false;
    echo basename($f) . "\n";
    echo "  removeAttribute('open'):  " . ($found1 ? "FOUND ✓" : "NOT FOUND ✗") . "\n";
    echo "  closest(summary) logic:   " . ($found2 ? "FOUND ✓" : "NOT FOUND ✗") . "\n";

    if ($found1) {
        $pos = strpos($c, 'removeAttribute("open")');
        echo "  Context: ..." . substr($c, max(0, $pos-150), 350) . "...\n";
    }
}
