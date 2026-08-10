<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

if (!Schema::hasTable('cms_faq_translations')) {
    Schema::create('cms_faq_translations', function (Blueprint $table) {
        $table->id();
        $table->foreignId('cms_faq_id')->constrained('cms_faqs')->onDelete('cascade');
        $table->foreignId('language_id')->constrained('languages')->onDelete('cascade');
        $table->text('question')->nullable();
        $table->longText('answer')->nullable();
        $table->string('translation_status')->default('pending');
        $table->timestamp('translated_at')->nullable();
        $table->timestamps();

        $table->unique(['cms_faq_id', 'language_id']);
    });
    echo "Table cms_faq_translations created successfully!\n";
} else {
    echo "Table cms_faq_translations already exists.\n";
}
