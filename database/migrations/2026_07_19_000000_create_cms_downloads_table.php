<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cms_downloads', function (Blueprint $table) {
            $table->id();

            // Admin-facing meta
            $table->string('internal_name', 255);
            $table->string('link_label', 255)->nullable();

            // Status & display
            $table->boolean('is_active')->default(true);
            $table->dateTime('expires_at')->nullable();
            $table->boolean('force_download')->default(false);
            $table->boolean('open_in_new_tab')->default(true);
            $table->boolean('show_icon')->default(false);
            $table->text('custom_css')->nullable();

            // Source type: 0=local, 1=direct URL, 2=env S3, 3=custom S3
            $table->tinyInteger('source_type')->default(0)->unsigned();

            // Local storage
            $table->string('file_path', 500)->nullable();

            // Direct URL / CDN
            $table->string('cdn_url', 500)->nullable();

            // Env S3 (uses .env AWS_* credentials)
            $table->string('s3_file_key', 500)->nullable();
            $table->unsignedInteger('s3_expiration_seconds')->default(600);

            // Custom S3 (per-file credentials)
            $table->string('s3_custom_key', 255)->nullable();
            $table->string('s3_custom_secret', 255)->nullable();
            $table->string('s3_custom_region', 100)->nullable();
            $table->string('s3_custom_bucket', 255)->nullable();
            $table->string('s3_custom_file_key', 500)->nullable();
            $table->unsignedInteger('s3_custom_expiration_seconds')->default(600);

            // Video poster image (for future video player support)
            $table->string('poster_image_path', 500)->nullable();
            $table->string('poster_image_cdn_url', 500)->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cms_downloads');
    }
};
