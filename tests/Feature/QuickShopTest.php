<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Livewire\AdminProductEdit;
use App\Livewire\AdminProducts;
use App\Livewire\FeaturedItemsWidget;
use App\Livewire\QuickShopModal;
use App\Livewire\ShopCatalog;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Livewire\Livewire;
use Tests\TestCase;

class QuickShopTest extends TestCase
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

    public function test_product_default_quick_shop_active_is_false(): void
    {
        $product = Product::create([
            'title'             => 'Test Catalog Item ' . rand(1000, 9999),
            'meta_title'        => 'Test Catalog Item',
            'short_description' => 'A test item',
            'seo_slug'          => 'test-catalog-item-' . rand(1000, 9999),
        ]);

        $this->assertFalse((bool) $product->fresh()->quick_shop_active);
        $this->assertNull($product->fresh()->quick_shop_label);
    }

    public function test_quick_shop_button_label_fallback_and_custom_override(): void
    {
        $product = Product::create([
            'title'             => 'Test Fallback Item ' . rand(1000, 9999),
            'meta_title'        => 'Test Fallback Item',
            'short_description' => 'A test item',
            'seo_slug'          => 'test-fallback-item-' . rand(1000, 9999),
            'quick_shop_active' => true,
            'quick_shop_label'  => null,
        ]);

        // Default label fallback
        $this->assertEquals('Quick Shop', $product->quick_shop_button_label);

        // Custom label override
        $product->update(['quick_shop_label' => 'Buy in 1-Click']);
        $this->assertEquals('Buy in 1-Click', $product->fresh()->quick_shop_button_label);
    }

    public function test_admin_can_toggle_and_save_quick_shop_settings(): void
    {
        $admin = User::create([
            'name'              => 'Admin User',
            'email'             => 'admin_qs_' . rand(1000, 9999) . '@support.local',
            'password'          => bcrypt('password'),
            'role_id'           => UserRole::Admin,
            'email_verified_at' => now(),
        ]);

        $product = Product::create([
            'active'            => true,
            'show_in_results'   => true,
            'title'             => 'Quick Shop Test Product ' . rand(1000, 9999),
            'meta_title'        => 'Quick Shop Test Product',
            'short_description' => 'Product to test quick shop toggle',
            'seo_slug'          => 'quick-shop-test-product-' . rand(1000, 9999),
            'quick_shop_active' => false,
        ]);

        Livewire::actingAs($admin)
            ->test(AdminProductEdit::class, ['id' => $product->id])
            ->set('quick_shop_active', true)
            ->set('quick_shop_label', 'Instant View')
            ->call('updateAdvancedSettings')
            ->assertHasNoErrors();

        $fresh = $product->fresh();
        $this->assertTrue((bool) $fresh->quick_shop_active);
        $this->assertEquals('Instant View', $fresh->quick_shop_label);
    }

    public function test_duplicate_product_copies_quick_shop_settings(): void
    {
        $admin = User::create([
            'name'              => 'Admin User',
            'email'             => 'admin_dup_' . rand(1000, 9999) . '@support.local',
            'password'          => bcrypt('password'),
            'role_id'           => UserRole::Admin,
            'email_verified_at' => now(),
        ]);

        $product = Product::create([
            'active'            => true,
            'show_in_results'   => true,
            'title'             => 'Original QS Product ' . rand(1000, 9999),
            'meta_title'        => 'Original QS Product',
            'short_description' => 'Original description',
            'seo_slug'          => 'orig-qs-prod-' . rand(1000, 9999),
            'quick_shop_active' => true,
            'quick_shop_label'  => 'Fast Order',
        ]);

        $newSlug = 'orig-qs-prod-copy-' . rand(1000, 9999);

        Livewire::actingAs($admin)
            ->test(AdminProducts::class)
            ->call('openCopyModal', $product->id)
            ->set('copyProductTitle', 'Duplicated QS Product')
            ->set('copyProductSlug', $newSlug)
            ->call('duplicateProduct')
            ->assertHasNoErrors();

        $duplicated = Product::where('seo_slug', $newSlug)->first();
        $this->assertNotNull($duplicated);
        $this->assertTrue((bool) $duplicated->quick_shop_active);
        $this->assertEquals('Fast Order', $duplicated->quick_shop_label);
    }

    public function test_quick_shop_modal_opens_and_adds_to_cart(): void
    {
        $product = Product::create([
            'active'            => true,
            'show_in_results'   => true,
            'title'             => 'Modal Test Product ' . rand(1000, 9999),
            'meta_title'        => 'Modal Test Product',
            'short_description' => 'Modal short description',
            'seo_slug'          => 'modal-test-product-' . rand(1000, 9999),
            'quick_shop_active' => true,
            'layout_type'       => 1,
        ]);

        $variant = ProductVariant::create([
            'product_id'      => $product->id,
            'sku'             => 'MODAL-SKU-1',
            'public_price'    => 29.99,
            'wholesale_price' => 19.99,
            'shipping'        => 1,
            'download_item'   => 0,
        ]);

        Livewire::test(QuickShopModal::class)
            ->assertSet('showModal', false)
            ->dispatch('open-quick-shop', ['productId' => $product->id])
            ->assertSet('showModal', true)
            ->assertSet('selectedVariantId', $variant->id)
            ->set('quantity', 2)
            ->call('addToCart')
            ->assertDispatched('cart-updated')
            ->assertSet('showModal', false);

        // Test direct integer dispatch
        Livewire::test(QuickShopModal::class)
            ->assertSet('showModal', false)
            ->dispatch('open-quick-shop', $product->id)
            ->assertSet('showModal', true)
            ->assertSet('selectedVariantId', $variant->id);

        // Test openQuickShop alias dispatch
        Livewire::test(QuickShopModal::class)
            ->assertSet('showModal', false)
            ->dispatch('openQuickShop', ['id' => $product->id])
            ->assertSet('showModal', true)
            ->assertSet('selectedVariantId', $variant->id);

        $cartItem = \App\Models\ShoppingCartLog::where('variant_id', $variant->id)->first();

        $this->assertNotNull($cartItem);
        $this->assertEquals(2, $cartItem->item_qty);
        $this->assertEquals(29.99, (float) $cartItem->item_price);
    }

    public function test_shop_catalog_renders_quick_shop_button_only_when_active(): void
    {
        $activeProd = Product::create([
            'active'            => true,
            'show_in_results'   => true,
            'title'             => 'Active QS Item ' . rand(1000, 9999),
            'meta_title'        => 'Active QS Item',
            'short_description' => 'Active item desc',
            'seo_slug'          => 'active-qs-item-' . rand(1000, 9999),
            'quick_shop_active' => true,
            'quick_shop_label'  => 'Quick Peek',
        ]);
        ProductVariant::create([
            'product_id'      => $activeProd->id,
            'sku'             => 'ACT-QS-1',
            'public_price'    => 15.00,
            'wholesale_price' => 10.00,
        ]);

        $inactiveProd = Product::create([
            'active'            => true,
            'show_in_results'   => true,
            'title'             => 'Inactive QS Item ' . rand(1000, 9999),
            'meta_title'        => 'Inactive QS Item',
            'short_description' => 'Inactive item desc',
            'seo_slug'          => 'inactive-qs-item-' . rand(1000, 9999),
            'quick_shop_active' => false,
        ]);
        ProductVariant::create([
            'product_id'      => $inactiveProd->id,
            'sku'             => 'INACT-QS-1',
            'public_price'    => 25.00,
            'wholesale_price' => 18.00,
        ]);

        Livewire::test(ShopCatalog::class)
            ->assertSee('Quick Peek')
            ->assertSeeHtml("open-quick-shop', { productId: {$activeProd->id} }")
            ->assertDontSeeHtml("open-quick-shop', { productId: {$inactiveProd->id} }");
    }

    public function test_featured_items_widget_renders_quick_shop_button_when_active(): void
    {
        $featuredQS = Product::create([
            'active'            => true,
            'show_in_results'   => true,
            'featured_item'     => 1,
            'title'             => 'Featured QS Product ' . rand(1000, 9999),
            'meta_title'        => 'Featured QS Product',
            'short_description' => 'Featured item desc',
            'seo_slug'          => 'feat-qs-item-' . rand(1000, 9999),
            'quick_shop_active' => true,
            'quick_shop_label'  => 'Express Buy',
        ]);
        ProductVariant::create([
            'product_id'      => $featuredQS->id,
            'sku'             => 'FEAT-QS-1',
            'public_price'    => 45.00,
            'wholesale_price' => 30.00,
        ]);

        Livewire::test(FeaturedItemsWidget::class, [
            'display' => 'grid',
            'max'     => 10,
        ])
            ->assertSee('Express Buy')
            ->assertSeeHtml("open-quick-shop', { productId: {$featuredQS->id} }");
    }

    public function test_cross_sell_widget_renders_quick_shop_button_when_active(): void
    {
        $mainProd = Product::create([
            'active'            => true,
            'show_in_results'   => true,
            'title'             => 'Main Product ' . rand(1000, 9999),
            'meta_title'        => 'Main Product',
            'short_description' => 'Main prod desc',
            'seo_slug'          => 'main-prod-' . rand(1000, 9999),
        ]);

        $crossProd = Product::create([
            'active'            => true,
            'show_in_results'   => true,
            'title'             => 'Cross Sell QS Item ' . rand(1000, 9999),
            'meta_title'        => 'Cross Sell QS Item',
            'short_description' => 'Cross sell desc',
            'seo_slug'          => 'cross-sell-qs-item-' . rand(1000, 9999),
            'quick_shop_active' => true,
            'quick_shop_label'  => 'Quick Add',
        ]);
        ProductVariant::create([
            'product_id'      => $crossProd->id,
            'sku'             => 'CROSS-QS-1',
            'public_price'    => 35.00,
            'wholesale_price' => 20.00,
        ]);

        \App\Models\ProductCrossSell::create([
            'product_id'            => $mainProd->id,
            'cross_sell_product_id' => $crossProd->id,
            'display_on_item_view'  => 1,
            'display_on_cart_add'   => 1,
            'display_on_post_cart'  => 1,
            'sort_order'            => 1,
        ]);

        Livewire::test(\App\Livewire\CrossSellListWidget::class, [
            'productId' => $mainProd->id,
            'display'   => 'grid',
            'max'       => 10,
        ])
            ->assertSee('Quick Add')
            ->assertSeeHtml("open-quick-shop', { productId: {$crossProd->id} }");
    }

    public function test_product_translation_supports_translated_quick_shop_label(): void
    {
        \App\Models\Language::clearCache();

        $defaultLang = \App\Models\Language::firstOrCreate(
            ['code' => 'en'],
            ['name' => 'English', 'native_name' => 'English', 'is_default' => 1, 'is_active' => 1]
        );
        $defaultLang->update(['is_default' => 1]);

        $lang = \App\Models\Language::firstOrCreate(
            ['code' => 'es'],
            ['name' => 'Spanish', 'native_name' => 'Español', 'is_default' => 0, 'is_active' => 1]
        );
        $lang->update(['is_default' => 0]);

        \App\Models\Language::clearCache();

        $product = Product::create([
            'active'            => true,
            'show_in_results'   => true,
            'title'             => 'Translatable Item ' . rand(1000, 9999),
            'meta_title'        => 'Translatable Item',
            'short_description' => 'English short description',
            'seo_slug'          => 'trans-item-' . rand(1000, 9999),
            'quick_shop_active' => true,
            'quick_shop_label'  => 'Quick Shop EN',
        ]);

        \App\Models\ProductTranslation::create([
            'product_id'        => $product->id,
            'language_id'       => $lang->id,
            'title'             => 'Article Traduit',
            'quick_shop_label'  => 'Achat Rapide',
        ]);

        // When language is requested via getTranslated on fresh product
        $this->assertEquals('Achat Rapide', $product->fresh()->getTranslated('quick_shop_label', $lang->id));
    }
}
