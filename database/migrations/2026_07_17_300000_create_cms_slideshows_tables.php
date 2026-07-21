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
        Schema::create('cms_slideshows', function (Blueprint $table) {
            $table->id('slideshow_id');
            $table->string('slideshow_name', 255)->nullable();
            $table->integer('slideshow_active')->default(1);
            $table->integer('sort_order')->default(0);
            $table->string('slide_show_alignment', 255)->nullable();
            $table->timestamps();
        });

        Schema::create('cms_slides', function (Blueprint $table) {
            $table->id();
            $table->longText('Title')->nullable();
            $table->longText('Description')->nullable();
            $table->longText('SlideURL')->nullable();
            $table->longText('LargeImage')->nullable();
            $table->longText('Thumbnail')->nullable();
            $table->integer('Active')->nullable();
            $table->double('ImageSort')->nullable();
            $table->longText('slide_heading')->nullable();
            $table->longText('slide_sub_heading')->nullable();
            $table->longText('slide_content_css')->nullable();
            $table->longText('slide_heading_css')->nullable();
            $table->longText('slide_callout_button_label')->nullable();
            $table->integer('slideshow_id')->nullable()->default(1);
            
            // Image and CDN related fields
            $table->string('mobile_image', 255)->nullable();
            $table->string('cdn_image', 255)->nullable();
            $table->string('cdn_mobile_image', 255)->nullable();
            $table->string('cdn_thumbnail', 255)->nullable();
            $table->integer('cdn_image_width')->nullable()->default(1920);
            $table->integer('cdn_image_height')->nullable()->default(725);
            $table->integer('cdn_mobile_image_height')->nullable()->default(500);
            $table->integer('cdn_mobile_image_width')->nullable()->default(600);
            
            // Custom S3 and CDN url fields
            $table->integer('image_s3')->default(0); // 0=public local, 1=env S3, 2=custom S3
            $table->string('image_s3_region', 255)->nullable();
            $table->string('image_s3_bucket', 255)->nullable();
            $table->string('image_s3_key', 255)->nullable();
            $table->string('image_s3_secret', 255)->nullable();
            $table->string('cdn_url', 255)->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cms_slides');
        Schema::dropIfExists('cms_slideshows');
    }
};
