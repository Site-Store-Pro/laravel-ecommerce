<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\CmsFaq;
use App\Models\CmsFaqTranslation;
use App\Models\Language;
use App\Plugins\Display\FaqsPlugin;

$esLang = Language::where('code', 'es-MX')->first();
$faq = CmsFaq::first();

if ($esLang && $faq) {
    CmsFaqTranslation::updateOrCreate(
        ['cms_faq_id' => $faq->id, 'language_id' => $esLang->id],
        [
            'question' => '¿Pregunta frecuente de prueba?',
            'answer'   => 'Respuesta frecuente de prueba.',
        ]
    );
    echo "Added Spanish (es-MX) translation for FAQ #{$faq->id}\n";
}

// Test rendering in English
session(['language_code' => 'en']);
$faqsPlugin = new FaqsPlugin();
$pluginModel = \App\Models\Plugin::where('shortcode', 'faqs-2026')->first();

$htmlEn = $faqsPlugin->render([], $pluginModel);

// Test rendering in Spanish (es-MX)
session(['language_code' => 'es-MX']);
$langService = app(\App\Services\LanguageService::class);
$refClass = new \ReflectionClass($langService);
$prop = $refClass->getProperty('currentLanguage');
$prop->setAccessible(true);
$prop->setValue($langService, null);

$htmlEs = $faqsPlugin->render([], $pluginModel);

echo "\n=== ENGLISH OUTPUT ===\n";
echo (str_contains($htmlEn, 'Sample FAQ Question') ? 'ENGLISH PASS' : 'ENGLISH FAIL') . "\n";

echo "\n=== SPANISH (es-MX) OUTPUT ===\n";
echo (str_contains($htmlEs, '¿Pregunta frecuente de prueba?') ? 'SPANISH PASS' : 'SPANISH FAIL') . "\n";
