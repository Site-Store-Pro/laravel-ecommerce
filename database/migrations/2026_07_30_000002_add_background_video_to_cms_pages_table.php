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
        Schema::table('cms_pages', function (Blueprint $table) {
            if (!Schema::hasColumn('cms_pages', 'background_video')) {
                $table->string('background_video')->nullable()->after('background_image');
            }
            if (!Schema::hasColumn('cms_pages', 'background_video_url')) {
                $table->string('background_video_url')->nullable()->after('background_video');
            }
            if (!Schema::hasColumn('cms_pages', 'background_video_type')) {
                $table->string('background_video_type')->default('local')->after('background_video_url');
            }
            if (!Schema::hasColumn('cms_pages', 'background_video_s3')) {
                $table->integer('background_video_s3')->default(0)->after('background_video_type');
            }
            if (!Schema::hasColumn('cms_pages', 'background_video_region')) {
                $table->string('background_video_region')->nullable()->after('background_video_s3');
            }
            if (!Schema::hasColumn('cms_pages', 'background_video_bucket_name')) {
                $table->string('background_video_bucket_name')->nullable()->after('background_video_region');
            }
            if (!Schema::hasColumn('cms_pages', 'background_video_access_key_id')) {
                $table->string('background_video_access_key_id')->nullable()->after('background_video_bucket_name');
            }
            if (!Schema::hasColumn('cms_pages', 'background_video_secret_access_key')) {
                $table->string('background_video_secret_access_key')->nullable()->after('background_video_access_key_id');
            }
            if (!Schema::hasColumn('cms_pages', 'background_video_cdn_url')) {
                $table->string('background_video_cdn_url')->nullable()->after('background_video_secret_access_key');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cms_pages', function (Blueprint $table) {
            $table->dropColumn([
                'background_video',
                'background_video_url',
                'background_video_type',
                'background_video_s3',
                'background_video_region',
                'background_video_bucket_name',
                'background_video_access_key_id',
                'background_video_secret_access_key',
                'background_video_cdn_url',
            ]);
        });
    }
};
