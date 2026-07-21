<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('plugins', function (Blueprint $table) {
            $table->id();
            $table->string('api_id', 255)->nullable()->unique();
            $table->text('name')->nullable();
            $table->text('version')->nullable();
            $table->string('type', 100)->nullable(); // display, shipping, email, images, etc.
            $table->text('author')->nullable();
            $table->string('filename', 150)->nullable()->unique(); // slug used in class resolution
            $table->tinyInteger('install_type')->default(1); // 0=drop-in file, 1=built-in
            $table->text('description')->nullable();
            $table->string('shortcode', 150)->nullable()->unique(); // used in [plugin:shortcode]
            $table->string('activation_required', 100)->default('no');
            $table->text('activation_instructions')->nullable();
            $table->text('activation_failed_msg')->nullable();
            $table->text('activation_success_msg')->nullable();
            $table->text('usage_instructions')->nullable();
            $table->text('help_info')->nullable();
            $table->text('help_url')->nullable();
            $table->dateTime('activation_date')->nullable();
            $table->tinyInteger('activation_status')->default(0);
            $table->text('activation_key')->nullable();
            $table->text('serial_number')->nullable();
            $table->timestamps();
        });

        Schema::create('plugin_options', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('plugin_id')->default(0);
            $table->text('field_name')->nullable();
            $table->text('field_label')->nullable();
            $table->text('field_type')->nullable(); // input, textarea, checkbox, select, text-only, color-picker, upload
            $table->text('field_data_format')->nullable(); // string, float, integer, date
            $table->longText('field_default_value')->nullable();
            $table->text('field_selections')->nullable(); // comma-separated for select/radio
            $table->text('field_min_value')->nullable();
            $table->text('field_max_value')->nullable();
            $table->text('field_editor')->nullable(); // null=plain, 'css'=prism CSS editor, 'yes'=TinyMCE WYSIWYG
            $table->text('field_help')->nullable();
            $table->string('field_required', 10)->default('no');
            $table->text('field_error_msg')->nullable();
            $table->text('field_html')->nullable();
            $table->integer('sort_order')->default(0);
            // No timestamps for perf reasons
        });

        Schema::create('plugin_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('plugin_id')->default(0);
            $table->text('field_name')->nullable();
            $table->longText('field_value')->nullable();
            $table->index('plugin_id');
        });
    }

    public function down(): void {
        Schema::dropIfExists('plugin_settings');
        Schema::dropIfExists('plugin_options');
        Schema::dropIfExists('plugins');
    }
};
