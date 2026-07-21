<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StripeSubscriptionVariantTest extends TestCase
{
    use RefreshDatabase;

    public function test_variant_is_marked_as_subscription_if_stripe_sandbox_price_id_is_set(): void
    {
        $product = Product::create([
            'title' => 'Test Product',
            'short_description' => 'desc',
            'long_description' => 'long desc',
            'seo_slug' => 'test-product',
        ]);

        $variant = ProductVariant::create([
            'product_id' => $product->id,
            'sku' => 'SUB-TEST-01',
            'public_price' => 19.99,
            'wholesale_price' => 19.99,
            'stripe_sandbox_price_id' => 'price_test_12345',
        ]);

        $this->assertEquals(1, $variant->subscription);
        $this->assertTrue($variant->isSubscriptionVariant());
    }

    public function test_variant_is_marked_as_subscription_if_stripe_live_price_id_is_set(): void
    {
        $product = Product::create([
            'title' => 'Test Product',
            'short_description' => 'desc',
            'long_description' => 'long desc',
            'seo_slug' => 'test-product',
        ]);

        $variant = ProductVariant::create([
            'product_id' => $product->id,
            'sku' => 'SUB-TEST-02',
            'public_price' => 19.99,
            'wholesale_price' => 19.99,
            'stripe_live_price_id' => 'price_live_12345',
        ]);

        $this->assertEquals(1, $variant->subscription);
        $this->assertTrue($variant->isSubscriptionVariant());
    }

    public function test_variant_is_marked_as_subscription_if_create_new_stripe_product_is_on(): void
    {
        $product = Product::create([
            'title' => 'Test Product',
            'short_description' => 'desc',
            'long_description' => 'long desc',
            'seo_slug' => 'test-product',
        ]);

        $variant = ProductVariant::create([
            'product_id' => $product->id,
            'sku' => 'SUB-TEST-03',
            'public_price' => 19.99,
            'wholesale_price' => 19.99,
            'create_new_stripe_product' => 1,
        ]);

        $this->assertEquals(1, $variant->subscription);
        $this->assertTrue($variant->isSubscriptionVariant());
    }

    public function test_variant_is_not_marked_as_subscription_if_no_keys_are_set(): void
    {
        $product = Product::create([
            'title' => 'Test Product',
            'short_description' => 'desc',
            'long_description' => 'long desc',
            'seo_slug' => 'test-product',
        ]);

        $variant = ProductVariant::create([
            'product_id' => $product->id,
            'sku' => 'SUB-TEST-04',
            'public_price' => 19.99,
            'wholesale_price' => 19.99,
        ]);

        $this->assertEquals(0, $variant->subscription);
        $this->assertFalse($variant->isSubscriptionVariant());
    }

    public function test_updating_variant_recalculates_subscription_correctly(): void
    {
        $product = Product::create([
            'title' => 'Test Product',
            'short_description' => 'desc',
            'long_description' => 'long desc',
            'seo_slug' => 'test-product',
        ]);

        $variant = ProductVariant::create([
            'product_id' => $product->id,
            'sku' => 'SUB-TEST-05',
            'public_price' => 19.99,
            'wholesale_price' => 19.99,
        ]);

        $this->assertEquals(0, $variant->subscription);

        $variant->update([
            'stripe_sandbox_price_id' => 'price_test_123',
        ]);

        $this->assertEquals(1, $variant->subscription);

        $variant->update([
            'stripe_sandbox_price_id' => null,
        ]);

        $this->assertEquals(0, $variant->subscription);
    }
}
