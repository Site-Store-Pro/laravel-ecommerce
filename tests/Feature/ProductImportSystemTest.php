<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductImage;
use App\Models\User;
use App\Services\ProductImportService;
use App\Livewire\AdminProductImport;
use Livewire\Livewire;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductImportSystemTest extends TestCase
{
    use RefreshDatabase;

    private function createAdminUser(): User
    {
        return User::factory()->create([
            'role_id' => \App\Enums\UserRole::Admin,
        ]);
    }

    public function test_admin_can_access_product_import_page(): void
    {
        $admin = $this->createAdminUser();

        $this->actingAs($admin)
            ->get(route('admin.ecommerce.import'))
            ->assertStatus(200)
            ->assertSee('Product Bulk Import System');
    }

    public function test_import_service_parses_single_and_multivariant_products(): void
    {
        $service = new ProductImportService();

        $rows = [
            // Row 1: Multi-variant product option 1
            [
                'title'                 => 'Vintage Denim Jacket',
                'short_description'     => 'Classic denim jacket',
                'long_description'      => 'High quality denim',
                'categories'            => 'Apparel > Outerwear > Jackets',
                'brand'                 => 'DenimCo',
                'public_price'          => '89.99',
                'wholesale_price'       => '45.00',
                'thumbnail_url'         => 'https://example.com/thumb.jpg',
                'main_image_url'        => 'https://example.com/main.jpg',
                'zoom_images_url'       => 'https://example.com/zoom1.jpg',
                'image_url_source'      => '1',
                'variant_sku'           => 'DENIM-BLK-M',
                'variant_name'          => 'Medium',
                'variant_attributes'    => 'Size:M, Color:Black',
                'variant_price'         => '89.99',
                'variant_wholesale_price'=> '45.00',
                'inventory'             => '25',
            ],
            // Row 2: Multi-variant product option 2
            [
                'title'                 => 'Vintage Denim Jacket',
                'short_description'     => 'Classic denim jacket',
                'long_description'      => 'High quality denim',
                'categories'            => 'Apparel > Outerwear > Jackets',
                'brand'                 => 'DenimCo',
                'public_price'          => '89.99',
                'wholesale_price'       => '45.00',
                'thumbnail_url'         => 'https://example.com/thumb.jpg',
                'main_image_url'        => 'https://example.com/main.jpg',
                'zoom_images_url'       => 'https://example.com/zoom2.jpg',
                'image_url_source'      => '1',
                'variant_sku'           => 'DENIM-BLK-L',
                'variant_name'          => 'Large',
                'variant_attributes'    => 'Size:L, Color:Black',
                'variant_price'         => '94.99',
                'variant_wholesale_price'=> '48.00',
                'inventory'             => '30',
            ],
            // Row 3: Single variant product
            [
                'title'                 => 'Leather Wallet',
                'short_description'     => 'Genuine leather wallet',
                'long_description'      => 'Slim design',
                'categories'            => '["Accessories", "Wallets"]',
                'brand'                 => 'LeatherWorks',
                'public_price'          => '39.99',
                'wholesale_price'       => '20.00',
                'thumbnail_url'         => 'https://example.com/wallet.jpg',
                'main_image_url'        => 'https://example.com/wallet.jpg',
                'zoom_images_url'       => '',
                'image_url_source'      => '1',
                'variant_sku'           => 'WALLET-GENUINE-01',
                'variant_name'          => '',
                'variant_attributes'    => '',
                'variant_price'         => '',
                'variant_wholesale_price'=> '',
                'inventory'             => '100',
            ]
        ];

        $stats = $service->executeImport($rows);

        $this->assertEquals(2, $stats['products_created']);
        $this->assertEquals(3, $stats['variants_created']);
        $this->assertGreaterThanOrEqual(2, $stats['brands_created']);

        // Check Parent Product 1
        $jacket = Product::where('title', 'Vintage Denim Jacket')->first();
        $this->assertNotNull($jacket);
        $this->assertCount(2, $jacket->variants);

        // Check Subcategory Hierarchy creation
        $jacketsCategory = Category::where('name', 'Jackets')->first();
        $this->assertNotNull($jacketsCategory);
        $this->assertNotNull($jacketsCategory->parent);
        $this->assertEquals('Outerwear', $jacketsCategory->parent->name);

        // Check Direct Image URL handling
        $var1 = ProductVariant::where('sku', 'DENIM-BLK-M')->first();
        $this->assertNotNull($var1);
        $img = ProductImage::where('variant_id', $var1->id)->first();
        $this->assertNotNull($img);
        $this->assertEquals(1, $img->image_url_source);
        $this->assertEquals('https://example.com/main.jpg', $img->main_path);
    }

    public function test_import_updates_existing_product_on_sku_match(): void
    {
        $service = new ProductImportService();

        $product = Product::create(['title' => 'Original Smart Lamp', 'seo_slug' => 'original-smart-lamp']);
        $variant = ProductVariant::create([
            'product_id' => $product->id,
            'sku' => 'LAMP-SMART-99',
            'public_price' => 25.00,
            'wholesale_price' => 12.00,
        ]);

        $rows = [
            [
                'title'                 => 'Updated Smart RGB Lamp',
                'short_description'     => 'Updated short desc',
                'long_description'      => 'Updated details',
                'categories'            => 'Home > Lighting',
                'brand'                 => 'Lumina',
                'public_price'          => '35.00',
                'wholesale_price'       => '18.00',
                'thumbnail_url'         => '',
                'main_image_url'        => '',
                'zoom_images_url'       => '',
                'image_url_source'      => '1',
                'variant_sku'           => 'LAMP-SMART-99',
                'variant_name'          => 'Default',
                'variant_attributes'    => 'Color:RGB',
                'variant_price'         => '35.00',
                'variant_wholesale_price'=> '18.00',
                'inventory'             => '50',
            ]
        ];

        $stats = $service->executeImport($rows);

        $this->assertEquals(0, $stats['products_created']);
        $this->assertEquals(1, $stats['products_updated']);
        $this->assertEquals(1, $stats['variants_updated']);

        $updatedProduct = Product::find($product->id);
        $this->assertEquals('Updated Smart RGB Lamp', $updatedProduct->title);

        $updatedVariant = ProductVariant::find($variant->id);
        $this->assertEquals(35.00, (float)$updatedVariant->public_price);
    }
}
