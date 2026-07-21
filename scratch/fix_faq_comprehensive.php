<?php
/**
 * Comprehensive FAQ accordion fix:
 *
 * 1. Fixes TinyMCE extended_valid_elements in both blade files so that
 *    SVG attributes (viewBox, fill, stroke, d, etc.) and the details
 *    name/open attributes are no longer stripped on save.
 *
 * 2. Rewrites the icon span in both FAQ widget HTML strings to use
 *    inline styles instead of Tailwind utility classes, making the
 *    icons self-contained and framework-independent.
 */

// ─────────────────────────────────────────────────────────────────
// 1. FIX extended_valid_elements IN BOTH BLADE FILES
// ─────────────────────────────────────────────────────────────────
$old_eve = "extended_valid_elements: '*[class|style|id]',";
$new_eve = "extended_valid_elements: '*[class|style|id|name|open],svg[*],path[*],circle[*],rect[*],g[*],line[*],polyline[*],polygon[*]',";

$blades = [
    'C:/Sites/laravel-gemini/resources/views/livewire/admin-cms-page-edit.blade.php',
    'C:/Sites/laravel-gemini/resources/views/livewire/admin-product-edit.blade.php',
];

foreach ($blades as $path) {
    $content = file_get_contents($path);
    $before = substr_count($content, $old_eve);
    $content = str_replace($old_eve, $new_eve, $content);
    $after  = substr_count($content, $new_eve);
    file_put_contents($path, $content);
    echo basename($path) . ": replaced {$before} extended_valid_elements → {$after} occurrences updated\n";
}

// ─────────────────────────────────────────────────────────────────
// 2. REWRITE WIDGET ICON SPANS IN html-widgets-drawer.blade.php
//
// The blade file stores HTML-encoded widget strings inside JS
// attributes.  In the raw file, < = &lt;, " = &quot;.
//
// We replace the Tailwind-class-dependent span+svgs with a version
// that uses only inline styles (no CSS framework needed).
// ─────────────────────────────────────────────────────────────────
$widgetFile = 'C:/Sites/laravel-gemini/resources/views/partials/html-widgets-drawer.blade.php';
$widget = file_get_contents($widgetFile);

// ---- OLD icon span (HTML-encoded, as it appears in the raw blade file) ----
$old_span =
    '&lt;span class=&quot;relative w-5 h-5 shrink-0 block&quot;&gt;' .
    '&lt;svg class=&quot;absolute inset-0 w-5 h-5 opacity-100 group-open:opacity-0 transition-opacity text-slate-500&quot; fill=&quot;none&quot; stroke=&quot;currentColor&quot; viewBox=&quot;0 0 24 24&quot;&gt;' .
    '&lt;path stroke-linecap=&quot;round&quot; stroke-linejoin=&quot;round&quot; stroke-width=&quot;2&quot; d=&quot;M12 4v16m8-8H4&quot;&gt;&lt;/path&gt;' .
    '&lt;/svg&gt;' .
    '&lt;svg class=&quot;absolute inset-0 w-5 h-5 opacity-0 group-open:opacity-100 transition-opacity text-slate-500&quot; fill=&quot;none&quot; stroke=&quot;currentColor&quot; viewBox=&quot;0 0 24 24&quot;&gt;' .
    '&lt;path stroke-linecap=&quot;round&quot; stroke-linejoin=&quot;round&quot; stroke-width=&quot;2&quot; d=&quot;M20 12H4&quot;&gt;&lt;/path&gt;' .
    '&lt;/svg&gt;' .
    '&lt;/span&gt;';

// ---- NEW icon span: inline styles, simple class names, no Tailwind dependency ----
$new_span =
    '&lt;span style=&quot;position:relative;display:block;flex-shrink:0;width:1.25rem;height:1.25rem;&quot;&gt;' .
    '&lt;svg class=&quot;faq-icon-plus&quot; style=&quot;position:absolute;top:0;right:0;bottom:0;left:0;width:1.25rem;height:1.25rem;opacity:1;transition:opacity 0.15s ease;&quot; fill=&quot;none&quot; stroke=&quot;currentColor&quot; viewBox=&quot;0 0 24 24&quot;&gt;' .
    '&lt;path stroke-linecap=&quot;round&quot; stroke-linejoin=&quot;round&quot; stroke-width=&quot;2&quot; d=&quot;M12 4v16m8-8H4&quot;&gt;&lt;/path&gt;' .
    '&lt;/svg&gt;' .
    '&lt;svg class=&quot;faq-icon-minus&quot; style=&quot;position:absolute;top:0;right:0;bottom:0;left:0;width:1.25rem;height:1.25rem;opacity:0;transition:opacity 0.15s ease;&quot; fill=&quot;none&quot; stroke=&quot;currentColor&quot; viewBox=&quot;0 0 24 24&quot;&gt;' .
    '&lt;path stroke-linecap=&quot;round&quot; stroke-linejoin=&quot;round&quot; stroke-width=&quot;2&quot; d=&quot;M20 12H4&quot;&gt;&lt;/path&gt;' .
    '&lt;/svg&gt;' .
    '&lt;/span&gt;';

$beforeCount = substr_count($widget, $old_span);
if ($beforeCount === 0) {
    // Try to find partial match to diagnose encoding differences
    echo "\nWARNING: Old icon span not found! Checking partial match...\n";
    $partial = '&lt;span class=&quot;relative w-5 h-5 shrink-0 block&quot;&gt;';
    echo "Partial match '...shrink-0 block...': " . substr_count($widget, $partial) . " occurrences\n";
    $partial2 = 'group-open:opacity-0';
    echo "Partial match 'group-open:opacity-0': " . substr_count($widget, $partial2) . " occurrences\n";
} else {
    $widget = str_replace($old_span, $new_span, $widget);
    $afterCount = substr_count($widget, $new_span);
    file_put_contents($widgetFile, $widget);
    echo "\nWidget drawer: replaced {$beforeCount} icon spans → {$afterCount} new inline-style spans\n";
}

echo "\nDone.\n";
