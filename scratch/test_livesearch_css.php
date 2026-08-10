<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Plugin;
use App\Plugins\Display\LiveSearchPlugin;

$plugin = Plugin::where('shortcode', 'live-search-2026')->first() 
       ?? Plugin::where('filename', 'live_search_2026')->first();

$liveSearch = new LiveSearchPlugin();
echo "Plugin custom_css setting: " . var_export($plugin->getSetting('custom_css'), true) . "\n";
echo "Plugin settings array custom_css: " . var_export($plugin->getSettings()['custom_css'] ?? 'NOT SET', true) . "\n\n";

$htmlInput = $liveSearch->render([], $plugin);
$htmlResults = $liveSearch->render(['mode' => 'results'], $plugin);

echo "=== INPUT MODE CSS CHECK ===\n";
echo "Has <style> block: " . (str_contains($htmlInput, '<style>') ? 'YES' : 'NO') . "\n";
echo "Contains custom_css content (max-width:275px): " . (str_contains($htmlInput, 'max-width:275px') ? 'YES' : 'NO') . "\n";

echo "\n=== RESULTS MODE CSS CHECK ===\n";
echo "Has <style> block: " . (str_contains($htmlResults, '<style>') ? 'YES' : 'NO') . "\n";
echo "Contains custom_css content (max-width:275px): " . (str_contains($htmlResults, 'max-width:275px') ? 'YES' : 'NO') . "\n";
