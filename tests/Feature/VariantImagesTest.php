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
}
