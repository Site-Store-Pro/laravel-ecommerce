<?php
// Search compiled CSS for FAQ accordion rules
$cssFiles = glob('C:/Sites/laravel-gemini/public/build/assets/app-*.css');
foreach ($cssFiles as $f) {
    $content = file_get_contents($f);
    if (strpos($content, 'faq-accordion') !== false) {
        echo "FOUND in: $f\n";
        // Extract snippet
        $pos = strpos($content, 'faq-accordion');
        echo "Context: " . substr($content, max(0, $pos - 20), 300) . "\n";
    } else {
        echo "NOT FOUND in: $f (size: " . strlen($content) . ")\n";
    }
}
