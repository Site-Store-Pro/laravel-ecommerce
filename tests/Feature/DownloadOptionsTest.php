<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use App\Models\OrderDetail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class DownloadOptionsTest extends TestCase
{
    use RefreshDatabase;

    public function test_checkout_saves_variant_download_expiration_and_max_downloads(): void
    {
        $user = User::create([
            'name' => 'Customer User',
            'email' => 'customer@example.com',
            'password' => bcrypt('password'),
            'role_id' => 1, // Customer
            'email_verified_at' => now(),
        ]);

        $this->actingAs($user);

        // 1. Create a downloadable product with a variant having specific expiration and limit
        $product = Product::create([
            'title' => 'Downloadable Product',
            'short_description' => 'desc',
            'long_description' => 'long desc',
            'seo_slug' => 'downloadable-product',
        ]);

        $expiry = now()->addMonths(6)->startOfMinute();
        $variant = ProductVariant::create([
            'product_id' => $product->id,
            'sku' => 'DL-TEST-01',
            'public_price' => 10.00,
            'wholesale_price' => 9.00,
            'download_item' => 1,
            'download_expiration' => $expiry,
            'downloads_max_allowed' => 75,
        ]);

        // Create initial inventory (even though downloads are usually infinite)
        \App\Models\ProductInventory::create([
            'variant_id' => $variant->id,
            'quantity_available' => 100,
            'reserved_stock' => 0,
            'location_id' => 1,
        ]);

        // 2. Add to cart
        Livewire::test(\App\Livewire\ProductDetails::class, ['seo_link' => 'downloadable-product'])
            ->call('addToCart');

        // Verify cart item was created
        $cartItem = \App\Models\ShoppingCartLog::where('order_id', 0)->first();
        $this->assertNotNull($cartItem);
        $this->assertEquals(1, $cartItem->item_downloadable);

        // 3. Checkout and place order via OrderReview
        Livewire::test(\App\Livewire\OrderReview::class)
            ->set('gatewayToken', '')
            ->call('placeOrder');

        // 4. Verify order details saved the variant's download settings
        $detail = OrderDetail::where('item_name', 'Downloadable Product (DL-TEST-01)')->first();
        $this->assertNotNull($detail);
        $this->assertEquals(1, $detail->download_item);
        $this->assertEquals($expiry->toDateTimeString(), $detail->download_expiration->toDateTimeString());
        $this->assertEquals(75, $detail->downloads_max_allowed);
    }

    public function test_admin_can_configure_variant_download_options(): void
    {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@support.local',
            'password' => bcrypt('password'),
            'role_id' => 3, // Admin
            'email_verified_at' => now(),
        ]);

        $product = Product::create([
            'title' => 'Download Admin Product',
            'short_description' => 'desc',
            'long_description' => 'long desc',
            'seo_slug' => 'download-admin-product',
        ]);

        $variant = ProductVariant::create([
            'product_id' => $product->id,
            'sku' => 'DL-CONF-01',
            'public_price' => 12.00,
            'wholesale_price' => 10.00,
            'download_item' => 1,
            'download_location' => 'downloads/testfile.zip',
            'download_expiration' => now()->addYear()->startOfMinute(),
            'downloads_max_allowed' => 100,
        ]);

        $this->actingAs($admin);

        $newExpiry = now()->addMonths(3)->startOfMinute();
        
        Livewire::test(\App\Livewire\AdminProductEdit::class, ['id' => $product->id])
            ->call('startEditVariant', $variant->id)
            ->set('download_expiration', $newExpiry->format('Y-m-d\TH:i'))
            ->set('downloads_max_allowed', 50)
            ->call('updateVariant');

        $variant->refresh();
        $this->assertEquals($newExpiry->toDateTimeString(), $variant->download_expiration->toDateTimeString());
        $this->assertEquals(50, $variant->downloads_max_allowed);
        $this->assertEquals(50, $variant->downloads_max_allowed);
    }
}
