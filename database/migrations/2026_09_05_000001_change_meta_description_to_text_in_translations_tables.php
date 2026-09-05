<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('cms_page_translations')) {
            Schema::table('cms_page_translations', function (Blueprint $table) {
                if (Schema::hasColumn('cms_page_translations', 'meta_description')) {
                    $table->text('meta_description')->nullable()->change();
                }
                if (Schema::hasColumn('cms_page_translations', 'alternate_page_title')) {
                    $table->text('alternate_page_title')->nullable()->change();
                }
            });
        }

        if (Schema::hasTable('product_translations')) {
            Schema::table('product_translations', function (Blueprint $table) {
                if (Schema::hasColumn('product_translations', 'meta_description')) {
                    $table->text('meta_description')->nullable()->change();
                }
            });
        }

        if (Schema::hasTable('kb_article_translations')) {
            Schema::table('kb_article_translations', function (Blueprint $table) {
                if (Schema::hasColumn('kb_article_translations', 'meta_description')) {
                    $table->text('meta_description')->nullable()->change();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('cms_page_translations')) {
            Schema::table('cms_page_translations', function (Blueprint $table) {
                if (Schema::hasColumn('cms_page_translations', 'meta_description')) {
                    $table->string('meta_description', 255)->nullable()->change();
                }
                if (Schema::hasColumn('cms_page_translations', 'alternate_page_title')) {
                    $table->string('alternate_page_title', 255)->nullable()->change();
                }
            });
        }

        if (Schema::hasTable('product_translations')) {
            Schema::table('product_translations', function (Blueprint $table) {
                if (Schema::hasColumn('product_translations', 'meta_description')) {
                    $table->string('meta_description', 255)->nullable()->change();
                }
            });
        }

        if (Schema::hasTable('kb_article_translations')) {
            Schema::table('kb_article_translations', function (Blueprint $table) {
                if (Schema::hasColumn('kb_article_translations', 'meta_description')) {
                    $table->string('meta_description', 255)->nullable()->change();
                }
            });
        }
    }
};
