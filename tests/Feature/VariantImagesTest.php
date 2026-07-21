<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductImage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class VariantImagesTest extends TestCase
{
    use RefreshDatabase;


    public function test_custom_s3_credentials_are_isolated_for_images_and_downloads()
    {
        $product = Product::create([
            'title' => 'Test Product S3',
            'slug' => 'test-product-s3',
            'description' => 'Test Description',
            'sku_prefix' => 'TPS',
        ]);

        // Variant S3 download details
        $variant = ProductVariant::create([
            'product_id' => $product->id,
            'sku' => 'TPS-VAR-1',
            'public_price' => 15.00,
            'wholesale_price' => 12.00,
            'quantity_available' => 10,
            'download_s3' => 2,
            'download_s3_region' => 'download-region',
            'download_s3_bucket_name' => 'download-bucket',
            'download_s3_access_key_id' => 'download-key',
            'download_s3_secret_access_key' => 'download-secret',
        ]);

        // Image set with unique S3 details
        $image = ProductImage::create([
            'variant_id' => $variant->id,
            'thumbnail_path' => 'images/thumbnails/thumb.jpg',
            'main_path' => 'images/mains/main.jpg',
            'image_s3' => 2,
            'image_s3_region' => 'image-region',
            'image_s3_bucket_name' => 'image-bucket',
            'image_s3_access_key_id' => 'image-key',
            'image_s3_secret_access_key' => 'image-secret',
        ]);

        // Get storage disk and check configuration
        $disk = $image->getStorageDisk();
        $diskName = 'custom_image_s3_' . $image->id;

        // Verify config was loaded with image S3 values, NOT download S3 values
        $config = config("filesystems.disks.{$diskName}");
        $this->assertNotNull($config);
        $this->assertEquals('image-key', $config['key']);
        $this->assertEquals('image-secret', $config['secret']);
        $this->assertEquals('image-region', $config['region']);
        $this->assertEquals('image-bucket', $config['bucket']);

        // Verify variant's download fields are untouched
        $this->assertEquals('download-key', $variant->download_s3_access_key_id);
    }

    public function test_product_primary_thumbnail_url_returns_search_image()
    {
        $product = Product::create([
            'title' => 'Test Product',
            'slug' => 'test-product',
            'description' => 'Test Description',
            'sku_prefix' => 'TP',
        ]);

        $variant1 = ProductVariant::create([
            'product_id' => $product->id,
            'sku' => 'TP-VAR-1',
            'public_price' => 10.00,
            'wholesale_price' => 8.00,
        ]);

        $variant2 = ProductVariant::create([
            'product_id' => $product->id,
            'sku' => 'TP-VAR-2',
            'public_price' => 20.00,
            'wholesale_price' => 16.00,
        ]);

        // Add standard image to variant 1
        $image1 = ProductImage::create([
            'variant_id' => $variant1->id,
            'thumbnail_path' => 'images/thumbnails/thumb1.jpg',
            'main_path' => 'images/mains/main1.jpg',
            'active' => 1,
            'search_image' => 0,
        ]);

        // Add search image to variant 2
        $image2 = ProductImage::create([
            'variant_id' => $variant2->id,
            'thumbnail_path' => 'images/thumbnails/thumb2.jpg',
            'main_path' => 'images/mains/main2.jpg',
            'active' => 1,
            'search_image' => 1,
        ]);

        // Clear relations cache and reload
        $product->load('variants.images');

        // It should return the search image from variant 2 (thumb2.jpg)
        $thumbnailUrl = $product->primaryThumbnailUrl();
        $this->assertStringContainsString('thumb2.jpg', $thumbnailUrl);
    }

    public function test_product_primary_thumbnail_url_returns_first_search_image_if_multiple()
    {
        $product = Product::create([
            'title' => 'Test Product 2',
            'slug' => 'test-product-2',
            'description' => 'Test Description',
            'sku_prefix' => 'TP2',
        ]);

        $variant1 = ProductVariant::create([
            'product_id' => $product->id,
            'sku' => 'TP2-VAR-1',
            'public_price' => 10.00,
            'wholesale_price' => 8.00,
        ]);

        $variant2 = ProductVariant::create([
            'product_id' => $product->id,
            'sku' => 'TP2-VAR-2',
            'public_price' => 20.00,
            'wholesale_price' => 16.00,
        ]);

        // Add search image 1 to variant 1
        $image1 = ProductImage::create([
            'variant_id' => $variant1->id,
            'thumbnail_path' => 'images/thumbnails/thumb1.jpg',
            'main_path' => 'images/mains/main1.jpg',
            'active' => 1,
            'search_image' => 1,
        ]);

        // Add search image 2 to variant 2
        $image2 = ProductImage::create([
            'variant_id' => $variant2->id,
            'thumbnail_path' => 'images/thumbnails/thumb2.jpg',
            'main_path' => 'images/mains/main2.jpg',
            'active' => 1,
            'search_image' => 1,
        ]);

        // Clear relations cache and reload
        $product->load('variants.images');

        // It should return the first search image found (thumb1.jpg)
        $thumbnailUrl = $product->primaryThumbnailUrl();
        $this->assertStringContainsString('thumb1.jpg', $thumbnailUrl);
    }
}
