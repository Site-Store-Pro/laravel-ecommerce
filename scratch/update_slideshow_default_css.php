<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use App\Models\Plugin;

$plugin = Plugin::where('shortcode', 'slideshow-2026')->first()
       ?? Plugin::where('filename', 'slideshow_2026')->first();

if ($plugin) {
    echo "Updating Plugin ID {$plugin->id}...\n";

    // 1. Update plugin_options
    $opt = DB::table('plugin_options')
        ->where('plugin_id', $plugin->id)
        ->where('field_name', 'default_css')
        ->first();

    if ($opt) {
        $newCss = str_replace([
            'text-shadow: 0 2px 4px rgba(0,0,0,0.5);',
            'text-shadow: 0 1px 3px rgba(0,0,0,0.4);',
            'text-shadow: 0 2px 4px rgba(0,0,0,0.5)',
            'text-shadow: 0 1px 3px rgba(0,0,0,0.4)',
        ], '', $opt->field_default_value);

        DB::table('plugin_options')
            ->where('id', $opt->id)
            ->update(['field_default_value' => $newCss]);
        echo "Updated plugin_options default_css!\n";
    }

    // 2. Update plugin_settings
    $settings = DB::table('plugin_settings')
        ->where('plugin_id', $plugin->id)
        ->get();

    foreach ($settings as $s) {
        if (str_contains($s->field_value, 'text-shadow')) {
            $newVal = str_replace([
                'text-shadow: 0 2px 4px rgba(0,0,0,0.5);',
                'text-shadow: 0 1px 3px rgba(0,0,0,0.4);',
                'text-shadow: 0 2px 4px rgba(0,0,0,0.5)',
                'text-shadow: 0 1px 3px rgba(0,0,0,0.4)',
            ], '', $s->field_value);

            DB::table('plugin_settings')
                ->where('id', $s->id)
                ->update(['field_value' => $newVal]);
            echo "Updated plugin_settings ID {$s->id} ({$s->field_name})!\n";
        }
    }
}
