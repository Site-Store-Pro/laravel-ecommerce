<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\CmsFaq;
use App\Plugins\Display\FaqsPlugin;
use Illuminate\Support\Facades\App;

// Test FAQs query with language set to 'es' or another active locale if exists
$locales = ['es', 'fr', 'de'];
foreach ($locales as $loc) {
    App::setLocale($loc);
    $faqs = CmsFaq::withCurrentTranslations()->active()->ordered()->get();
    echo "Locale: {$loc} | FAQ count: " . $faqs->count() . "\n";
    foreach ($faqs as $f) {
        echo "  - [{$f->id}] Q: {$f->question} | A: {$f->answer}\n";
    }
}
