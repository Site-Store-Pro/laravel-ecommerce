<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\LanguageTranslation;

$translations = LanguageTranslation::where('translatable_type', \App\Models\CmsFaq::class)->get();
echo "Found " . $translations->count() . " CmsFaq translations in DB:\n";
foreach ($translations as $t) {
    echo "  - Lang: {$t->language_code} | Field: {$t->field_name} | Record ID: {$t->translatable_id} | Val: {$t->translation_value}\n";
}
