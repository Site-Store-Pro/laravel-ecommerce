<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cms_modals', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->longText('body')->nullable();
            $table->enum('position', ['center', 'left', 'right', 'bottom'])->default('center');
            $table->string('max_width', 50)->default('640px');
            $table->text('custom_css')->nullable();
            $table->string('cookie_name', 100)->nullable()->comment('Cookie key; auto-generated as cms_modal_{id} if blank');
            $table->integer('cookie_lifetime')->default(30)->comment('Days; 0 = session only');
            $table->boolean('auto_open')->default(false);
            $table->integer('open_delay')->default(0)->comment('Milliseconds before auto-open');
            $table->boolean('overlay_dismissible')->default(true);
            $table->boolean('show_close_button')->default(true)->comment('Show X + Dismiss label');
            $table->string('trigger_selector', 255)->nullable()->comment('CSS selector to trigger modal on click');
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('cms_modal_translations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cms_modal_id')->index();
            $table->unsignedBigInteger('language_id')->index();
            $table->text('title')->nullable();
            $table->longText('body')->nullable();
            $table->string('translation_status', 30)->default('ai_translated');
            $table->timestamp('translated_at')->nullable();
            $table->timestamps();

            $table->foreign('cms_modal_id')->references('id')->on('cms_modals')->onDelete('cascade');
            $table->foreign('language_id')->references('id')->on('languages')->onDelete('cascade');
            $table->unique(['cms_modal_id', 'language_id']);
        });

        DB::table('plugins')->updateOrInsert(
            ['shortcode' => 'modal'],
            [
                'api_id'              => 'cms-modal-display',
                'name'                => 'CMS Modal Display',
                'version'             => '1.0.0',
                'type'                => 'display',
                'author'              => 'System',
                'filename'            => 'ModalDisplayPlugin',
                'install_type'        => 1,
                'description'         => 'Embeds a managed modal window into any page body via shortcode.',
                'shortcode'           => 'modal',
                'usage_instructions'  => 'Use [plugin:modal id=N] in any CMS page or product body.',
                'activation_status'   => 1,
                'activation_required' => 0,
                'created_at'          => now(),
                'updated_at'          => now(),
            ]
        );
    }

    public function down(): void
    {
        Schema::dropIfExists('cms_modal_translations');
        Schema::dropIfExists('cms_modals');
        DB::table('plugins')->where('shortcode', 'modal')->delete();
    }
};
