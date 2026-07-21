<?php
/**
 * Removes "(Closed)?" and "(Open)?" status labels from the
 * FAQ accordion H2 titles inserted into TinyMCE.
 * Preserves the drawer card labels "FAQ Accordion (Closed)" / "(Open)".
 */
$file = 'C:/Sites/laravel-gemini/resources/views/partials/html-widgets-drawer.blade.php';
$content = file_get_contents($file);

// Only change the h2 question text, not the widget-card h5 labels
$content = str_replace(
    'Frequently Asked Question (Closed)?',
    'Frequently Asked Question?',
    $content
);
$content = str_replace(
    'Frequently Asked Question (Open)?',
    'Frequently Asked Question?',
    $content
);

file_put_contents($file, $content);

// Verify
$changed = file_get_contents($file);
$closedCount = substr_count($changed, 'Frequently Asked Question (Closed)?');
$openCount   = substr_count($changed, 'Frequently Asked Question (Open)?');
$cleanCount  = substr_count($changed, 'Frequently Asked Question?');

echo "Remaining '(Closed)?' occurrences: $closedCount (expected 0)\n";
echo "Remaining '(Open)?' occurrences:   $openCount (expected 0)\n";
echo "Clean 'Frequently Asked Question?' occurrences: $cleanCount (expected 4)\n";
echo "Card labels still intact (Closed): " . substr_count($changed, 'FAQ Accordion (Closed)') . " (expected 1)\n";
echo "Card labels still intact (Open):   " . substr_count($changed, 'FAQ Accordion (Open)') . " (expected 1)\n";
