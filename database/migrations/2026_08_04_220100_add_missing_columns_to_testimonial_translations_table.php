<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('testimonial_translations', function (Blueprint $table) {
            // author_name and company_name were missing from the original migration
            // but are required by CmsTestimonialTranslation / AdminTestimonialsManager
            $table->string('author_name')->nullable()->after('author_title');
            $table->string('company_name')->nullable()->after('author_name');
        });
    }

    public function down(): void
    {
        Schema::table('testimonial_translations', function (Blueprint $table) {
            $table->dropColumn(['author_name', 'company_name']);
        });
    }
};
