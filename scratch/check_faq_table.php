<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Schema;

echo "cms_faqs table exists: " . (Schema::hasTable('cms_faqs') ? 'YES' : 'NO') . "\n";
echo "cms_faq_translations table exists: " . (Schema::hasTable('cms_faq_translations') ? 'YES' : 'NO') . "\n";
if (Schema::hasTable('cms_faq_translations')) {
    print_r(Schema::getColumnListing('cms_faq_translations'));
}
