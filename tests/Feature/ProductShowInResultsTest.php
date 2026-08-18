<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\Product;
use App\Models\ProductCrossSell;
use App\Models\ProductVariant;
use App\Models\User;
use App\Services\LiveSearchService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Livewire\Livewire;
use Tests\TestCase;

class ProductShowInResultsTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        DB::statement("INSERT IGNORE INTO `user_roles` (`id`, `name`, `description`) VALUES 
            (1, 'User', 'Customer'),
            (2, 'Wholesale', 'Wholesale'),
            (3, 'Admin', 'Admin')");
    }

    public function test_product_default_show_in_results_is_true(): void
    {
        $product = Product::create([
            'title'             => 'Standard Catalog Product ' . rand(1000, 9999),
            'meta_title'        => 'Standard Catalog Product',
            'short_description' => 'A public product',
            'seo_slug'          => 'standard-catalog-product-' . rand(1000, 9999),
        ]);

        $this->assertTrue((bool) $product->fresh()->show_in_results);
    }

    public function test_admin_can_toggle_and_save_show_in_results(): void
    {
        $admin = User::create([
            'name'              => 'Admin User',
            'email'             => 'admin_test_' . rand(1000, 9999) . '@support.local',
            'password'          => bcrypt('password'),
            'role_id'           => UserRole::Admin,
            'email_verified_at' => now(),
        ]);

        $product = Product::create([
            'active'            => true,
            'show_in_results'   => true,
            'title'             => 'Invoice Service Product ' . rand(1000, 9999),
            'meta_title'        => 'Invoice Service Product',
            'short_description' => 'Hidden service item',
            'seo_slug'          => 'invoice-service-product-' . rand(1000, 9999),
        ]);

        // Toggle show_in_results to false via AdminProductEdit and save
        Livewire::actingAs($admin)
            ->test(\App\Livewire\AdminProductEdit::class, ['id' => $product->id])
            ->assertSet('show_in_results', true)
            ->set('show_in_results', false)
            ->call('saveAllSections');

        $this->assertFalse((bool) $product->fresh()->show_in_results);

        // Toggle back to true and save
        Livewire::actingAs($admin)
            ->test(\App\Livewire\AdminProductEdit::class, ['id' => $product->id])
            ->assertSet('show_in_results', false)
            ->set('show_in_results', true)
            ->call('updateAdvancedSettings');

        $this->assertTrue((bool) $product->fresh()->show_in_results);
    }

    public function test_hidden_from_results_product_is_hidden_from_catalog_search_featured_and_cross_sells_but_accessible_via_direct_url(): void
    {
        // 1. Create a service/invoice product with show_in_results = false
        $hiddenProduct = Product::create([
            'active'            => true,
            'show_in_results'   => false,
            'featured_item'     => 1,
            'title'             => 'Custom Service Invoice ' . rand(1000, 9999),
            'meta_title'        => 'Custom Service Invoice',
            'short_description' => 'Direct link invoice item',
            'long_description'  => 'Full description for direct invoice bill pay',
            'seo_slug'          => 'custom-service-invoice-' . rand(1000, 9999),
        ]);

        $hiddenVariant = ProductVariant::create([
            'product_id'      => $hiddenProduct->id,
            'sku'             => 'SRV-INV-' . rand(1000, 9999),
            'public_price'    => 150.00,
            'wholesale_price' => 150.00,
        ]);

        // 2. Create a standard public product that cross-sells the hidden product
        $publicProduct = Product::create([
            'active'            => true,
            'show_in_results'   => true,
            'featured_item'     => 1,
            'title'             => 'Public Featured Widget ' . rand(1000, 9999),
            'meta_title'        => 'Public Featured Widget',
            'short_description' => 'Public widget',
            'seo_slug'          => 'public-featured-widget-' . rand(1000, 9999),
        ]);

        $publicVariant = ProductVariant::create([
            'product_id'      => $publicProduct->id,
            'sku'             => 'PUB-WDG-' . rand(1000, 9999),
            'public_price'    => 49.99,
            'wholesale_price' => 35.00,
        ]);

        ProductCrossSell::create([
            'product_id'            => $publicProduct->id,
            'cross_sell_product_id' => $hiddenProduct->id,
            'display_on_item_view'  => true,
            'sort_order'            => 1,
        ]);

        // --- A. DIRECT URL ACCESS ---
        // Direct URL MUST return 200 OK because the item is active and accessible via direct link
        $response = $this->get(route('shop.product', $hiddenProduct->seo_slug));
        $response->assertStatus(200);
        $response->assertSee($hiddenProduct->title);

        // --- B. SHOP CATALOG LISTING ---
        // Catalog should show the public product, but NOT the hidden product
        Livewire::test(\App\Livewire\ShopCatalog::class)
            ->assertSee($publicProduct->title)
            ->assertDontSee($hiddenProduct->title);

        // Catalog search should return no matched products
        Livewire::test(\App\Livewire\ShopCatalog::class, ['search' => $hiddenProduct->title])
            ->assertSee('No products match your filter criteria');

        // --- C. LIVE SEARCH SERVICE ---
        $searchService = app(LiveSearchService::class);
        $searchResults = $searchService->search($hiddenProduct->title, 1, 1);
        $this->assertFalse(
            collect($searchResults)->contains(fn($r) => $r['id'] === $hiddenProduct->id),
            'Hidden product must not appear in live search results'
        );

        // --- D. FEATURED ITEMS WIDGET ---
        // Even though featured_item = 1, show_in_results = 0 must hide it from the featured widget
        Livewire::test(\App\Livewire\FeaturedItemsWidget::class)
            ->assertSee($publicProduct->title)
            ->assertDontSee($hiddenProduct->title);

        // --- E. CROSS SELLING WIDGET ---
        // Even though assigned as a cross-sell, show_in_results = 0 must hide it from cross-sell displays
        Livewire::test(\App\Livewire\CrossSellListWidget::class, ['productId' => $publicProduct->id])
            ->assertDontSee($hiddenProduct->title);
    }
}
