<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductField;
use App\Models\ProductFieldOption;
use Livewire\Livewire;
use App\Livewire\ShopCatalog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CatalogPricingTest extends TestCase
{
    use RefreshDatabase;

    public function test_catalog_displays_from_for_multiple_variants_with_different_prices(): void
    {
        $product = Product::create([
            'title' => 'Multi Price Product',
            'seo_slug' => 'multi-price-product',
            'short_description' => 'A test product',
            'long_description' => 'A test product description',
        ]);

        ProductVariant::create([
            'product_id' => $product->id,
            'sku' => 'VAR-1',
            'public_price' => 10.00,
            'wholesale_price' => 8.00,
            'on_sale' => 0,
        ]);

        ProductVariant::create([
            'product_id' => $product->id,
            'sku' => 'VAR-2',
            'public_price' => 20.00,
            'wholesale_price' => 16.00,
            'on_sale' => 0,
        ]);

        Livewire::test(ShopCatalog::class)
            ->assertSee('From $10.00')
            ->assertDontSee('From $20.00');
    }

    public function test_catalog_displays_from_for_products_with_optional_fees(): void
    {
        $product = Product::create([
            'title' => 'Optional Fee Product',
            'seo_slug' => 'optional-fee-product',
            'short_description' => 'A test product',
            'long_description' => 'A test product description',
        ]);

        ProductVariant::create([
            'product_id' => $product->id,
            'sku' => 'VAR-SINGLE',
            'public_price' => 15.00,
            'wholesale_price' => 12.00,
            'on_sale' => 0,
        ]);

        $field = ProductField::create([
            'product_id' => $product->id,
            'label' => 'Options',
            'field_type' => 'select',
            'is_required' => false,
            'sort_order' => 1,
        ]);

        ProductFieldOption::create([
            'product_field_id' => $field->id,
            'option_value' => 'Option With Fee',
            'option_price_modifier' => 5.00,
            'option_wholesale_price_modifier' => 4.00,
            'sort_order' => 1,
        ]);

        Livewire::test(ShopCatalog::class)
            ->assertSee('From $15.00');
    }

    public function test_catalog_does_not_display_from_for_single_variant_no_optional_fees(): void
    {
        $product = Product::create([
            'title' => 'Single Variant Product',
            'seo_slug' => 'single-variant-product',
            'short_description' => 'A test product',
            'long_description' => 'A test product description',
        ]);

        ProductVariant::create([
            'product_id' => $product->id,
            'sku' => 'VAR-SINGLE-ONLY',
            'public_price' => 15.00,
            'wholesale_price' => 12.00,
            'on_sale' => 0,
        ]);

        Livewire::test(ShopCatalog::class)
            ->assertSee('$15.00')
            ->assertDontSee('From $15.00');
    }
}
