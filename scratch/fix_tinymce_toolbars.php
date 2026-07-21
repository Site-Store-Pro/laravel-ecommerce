<?php
/**
 * Standardises TinyMCE editor heights and toolbars.
 * Uses str_replace / preg_replace without line-anchor issues on CRLF files.
 */

$row1 = "'supercode fullscreen | undo redo | styles blocks | bold italic underline strikethrough | forecolor backcolor'";
$row2 = "'fontfamily fontsize lineheight | alignleft aligncenter alignright alignjustify | outdent indent | removeformat | numlist bullist | pagebreak | charmap emoticons | link image media anchor | ltr rtl | preview'";

// ──────────────────────────────────────────────────────────────────────────────
// CMS PAGE EDIT
// ──────────────────────────────────────────────────────────────────────────────
$cmsFile = 'C:/Sites/laravel-gemini/resources/views/livewire/admin-cms-page-edit.blade.php';
$cms = file_get_contents($cmsFile);

// The exact toolbar string present in all 3 CMS editors
$oldToolbar = "toolbar: 'supercode fullscreen undo redo | styles | bold italic underline strikethrough | forecolor backcolor sizeselect blocks fontfamily fontsize lineheight | alignleft aligncenter alignright alignjustify | outdent indent | removeformat numlist bullist | pagebreak | charmap emoticons | fullscreen preview  | image media link anchor | ltr rtl',";

// Replace each occurrence, preserving leading whitespace
$cms = preg_replace_callback(
    '/([ \t]*)' . preg_quote($oldToolbar, '/') . '/',
    function ($m) use ($row1, $row2) {
        $pad   = $m[1];       // leading whitespace of the original line
        $inner = $pad . '    '; // 4 extra spaces for array contents
        return
            $pad . "toolbar: [\n" .
            $inner . $row1 . ",\n" .
            $inner . $row2 . "\n" .
            $pad . "],\n" .
            $pad . "toolbar_mode: 'wrap',";
    },
    $cms
);

// Fix heights (simple str_replace – these values are unique in context)
$cms = str_replace('height: 400,', 'height: 850,', $cms);
$cms = str_replace('height: 350,', 'height: 850,', $cms);

file_put_contents($cmsFile, $cms);
echo "CMS page edit: OK\n";

// ──────────────────────────────────────────────────────────────────────────────
// PRODUCT EDIT
// ──────────────────────────────────────────────────────────────────────────────
$prodFile = 'C:/Sites/laravel-gemini/resources/views/livewire/admin-product-edit.blade.php';
$prod = file_get_contents($prodFile);

// Match the entire "toolbar: [ ... ]," block (any rows, multiline)
$prod = preg_replace_callback(
    '/([ \t]*)toolbar:\s*\[[\s\S]*?\],/',
    function ($m) use ($row1, $row2) {
        $pad   = $m[1];
        $inner = $pad . '    ';
        return
            $pad . "toolbar: [\n" .
            $inner . $row1 . ",\n" .
            $inner . $row2 . "\n" .
            $pad . "],";
    },
    $prod
);

// Add toolbar_mode after the toolbar block if not already present
if (strpos($prod, "toolbar_mode: 'wrap'") === false) {
    $prod = preg_replace_callback(
        '/([ \t]*)toolbar:\s*\[[\s\S]*?\],/',
        function ($m) {
            return $m[0] . "\n" . $m[1] . "toolbar_mode: 'wrap',";
        },
        $prod
    );
}

file_put_contents($prodFile, $prod);
echo "Product edit: OK\n";

// ──────────────────────────────────────────────────────────────────────────────
// VERIFICATION
// ──────────────────────────────────────────────────────────────────────────────
echo "\n=== CMS PAGE EDIT: toolbar_mode occurrences ===\n";
preg_match_all("/toolbar_mode/", file_get_contents($cmsFile), $m);
echo count($m[0]) . " (expected 3 – one per editor)\n";

preg_match_all("/height: 850/", file_get_contents($cmsFile), $m);
echo "height:850 occurrences: " . count($m[0]) . " (expected 3)\n";

echo "\n=== PRODUCT EDIT: toolbar_mode occurrences ===\n";
preg_match_all("/toolbar_mode/", file_get_contents($prodFile), $m);
echo count($m[0]) . " (expected 1)\n";

preg_match_all("/height: 850/", file_get_contents($prodFile), $m);
echo "height:850 occurrences: " . count($m[0]) . " (expected 1)\n";
