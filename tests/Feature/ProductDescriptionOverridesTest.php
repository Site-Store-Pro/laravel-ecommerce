<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Livewire\AdminProductEdit;
use App\Livewire\AdminProducts;
use App\Livewire\CrossSellListWidget;
use App\Livewire\FeaturedItemsWidget;
use App\Livewire\QuickShopModal;
use App\Livewire\ShopCatalog;
use App\Models\Language;
use App\Models\Product;
use App\Models\ProductCrossSell;
use App\Models\ProductTranslation;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Livewire\Livewire;
use Tests\TestCase;

class ProductDescriptionOverridesTest extends TestCase
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

    public function test_description_overrides_fallback_to_default_short_description(): void
    {
        $product = Product::create([
            'title'                      => 'Fallback Test Product ' . rand(1000, 9999),
            'meta_title'                 => 'Fallback Test Product',
            'short_description'          => 'Default Short Description Text',
            'search_results_description' => null,
            'quick_shop_description'     => null,
            'seo_slug'                   => 'fallback-test-prod-' . rand(1000, 9999),
        ]);

        $this->assertEquals('Default Short Description Text', $product->catalog_short_description);
        $this->assertEquals('Default Short Description Text', $product->quick_shop_short_description);
        $this->assertStringContainsString('Default Short Description Text', $product->parsed_catalog_description);
        $this->assertStringContainsString('Default Short Description Text', $product->parsed_quick_shop_description);
    }

    public function test_search_results_description_overrides_in_catalog_and_widgets(): void
    {
        $product = Product::create([
            'active'                     => true,
            'show_in_results'            => true,
            'title'                      => 'Search Override Product ' . rand(1000, 9999),
            'meta_title'                 => 'Search Override Product',
            'short_description'          => 'Default Short Description',
            'search_results_description' => '<p>Special <strong>Catalog Search</strong> Description</p>',
            'seo_slug'                   => 'search-override-prod-' . rand(1000, 9999),
            'featured_item'              => 1,
        ]);

        ProductVariant::create([
            'product_id'      => $product->id,
            'sku'             => 'SR-SKU-' . rand(100, 999),
            'public_price'    => 50.00,
            'wholesale_price' => 30.00,
        ]);

        // Accessor check
        $this->assertEquals('<p>Special <strong>Catalog Search</strong> Description</p>', $product->catalog_short_description);

        // 1. Catalog Results
        Livewire::test(ShopCatalog::class)
            ->assertSee('Special Catalog Search Description')
            ->assertDontSee('Default Short Description');

        // 2. Featured Items Widget
        Livewire::test(FeaturedItemsWidget::class, ['display' => 'grid', 'max' => 10])
            ->assertSee('Special Catalog Search Description')
            ->assertDontSee('Default Short Description');

        // 3. Cross-Selling Widget
        $mainProduct = Product::create([
            'active'            => true,
            'show_in_results'   => true,
            'title'             => 'Main Item ' . rand(1000, 9999),
            'meta_title'        => 'Main Item',
            'short_description' => 'Main short description',
            'seo_slug'          => 'main-item-' . rand(1000, 9999),
        ]);

        ProductCrossSell::create([
            'product_id'            => $mainProduct->id,
            'cross_sell_product_id' => $product->id,
            'display_on_item_view'  => 1,
            'sort_order'            => 1,
        ]);

        Livewire::test(CrossSellListWidget::class, [
            'productId' => $mainProduct->id,
            'display'   => 'grid',
            'max'       => 10,
        ])
            ->assertSee('Special Catalog Search Description')
            ->assertDontSee('Default Short Description');
    }

    public function test_quick_shop_description_overrides_only_in_quick_shop_modal(): void
    {
        $product = Product::create([
            'active'                     => true,
            'show_in_results'            => true,
            'title'                      => 'Quick Shop Override Product ' . rand(1000, 9999),
            'meta_title'                 => 'Quick Shop Override Product',
            'short_description'          => 'Default Full Page Description',
            'quick_shop_description'     => '<p>Exclusive <em>Quick Shop Modal</em> Teaser</p>',
            'seo_slug'                   => 'qs-override-prod-' . rand(1000, 9999),
            'quick_shop_active'          => true,
            'layout_type'                => 1,
        ]);

        $variant = ProductVariant::create([
            'product_id'      => $product->id,
            'sku'             => 'QS-MOD-SKU-' . rand(100, 999),
            'public_price'    => 75.00,
            'wholesale_price' => 45.00,
        ]);

        // Accessor checks
        $this->assertEquals('<p>Exclusive <em>Quick Shop Modal</em> Teaser</p>', $product->quick_shop_short_description);
        $this->assertEquals('Default Full Page Description', $product->short_description);

        // Modal View renders the Quick Shop Description
        Livewire::test(QuickShopModal::class)
            ->dispatch('open-quick-shop', ['productId' => $product->id])
            ->assertSeeHtml('Exclusive <em>Quick Shop Modal</em> Teaser')
            ->assertDontSee('Default Full Page Description');
    }

    public function test_admin_can_save_and_duplicate_description_overrides(): void
    {
        $admin = User::create([
            'name'              => 'Admin User',
            'email'             => 'admin_desc_' . rand(1000, 9999) . '@support.local',
            'password'          => bcrypt('password'),
            'role_id'           => UserRole::Admin,
            'email_verified_at' => now(),
        ]);

        $product = Product::create([
            'active'                     => true,
            'show_in_results'            => true,
            'title'                      => 'Edit Desc Product ' . rand(1000, 9999),
            'meta_title'                 => 'Edit Desc Product',
            'short_description'          => 'Initial short description',
            'search_results_description' => null,
            'quick_shop_description'     => null,
            'seo_slug'                   => 'edit-desc-prod-' . rand(1000, 9999),
        ]);

        // Save via AdminProductEdit
        Livewire::actingAs($admin)
            ->test(AdminProductEdit::class, ['id' => $product->id])
            ->set('search_results_description', '<p>Updated Search Desc</p>')
            ->set('quick_shop_description', '<p>Updated QS Desc</p>')
            ->call('updateProduct')
            ->assertHasNoErrors();

        $fresh = $product->fresh();
        $this->assertEquals('<p>Updated Search Desc</p>', $fresh->search_results_description);
        $this->assertEquals('<p>Updated QS Desc</p>', $fresh->quick_shop_description);

        // Duplicate product via AdminProducts
        $copySlug = 'copy-desc-prod-' . rand(1000, 9999);
        Livewire::actingAs($admin)
            ->test(AdminProducts::class)
            ->call('openCopyModal', $fresh->id)
            ->set('copyProductTitle', 'Duplicated Desc Product')
            ->set('copyProductSlug', $copySlug)
            ->call('duplicateProduct')
            ->assertHasNoErrors();

        $copy = Product::where('seo_slug', $copySlug)->first();
        $this->assertNotNull($copy);
        $this->assertEquals('<p>Updated Search Desc</p>', $copy->search_results_description);
        $this->assertEquals('<p>Updated QS Desc</p>', $copy->quick_shop_description);
    }

    public function test_translations_support_description_overrides(): void
    {
        Language::clearCache();

        $defaultLang = Language::firstOrCreate(
            ['code' => 'en'],
            ['name' => 'English', 'native_name' => 'English', 'is_default' => 1, 'is_active' => 1]
        );
        $defaultLang->update(['is_default' => 1]);

        $spanishLang = Language::firstOrCreate(
            ['code' => 'es'],
            ['name' => 'Spanish', 'native_name' => 'Español', 'is_default' => 0, 'is_active' => 1]
        );
        $spanishLang->update(['is_default' => 0]);

        Language::clearCache();

        $product = Product::create([
            'active'                     => true,
            'show_in_results'            => true,
            'title'                      => 'Translatable Item ' . rand(1000, 9999),
            'meta_title'                 => 'Translatable Item',
            'short_description'          => 'English short description',
            'search_results_description' => 'English search description',
            'quick_shop_description'     => 'English quick shop description',
            'seo_slug'                   => 'trans-desc-item-' . rand(1000, 9999),
        ]);

        ProductTranslation::create([
            'product_id'                 => $product->id,
            'language_id'                => $spanishLang->id,
            'title'                      => 'Artículo traducido',
            'short_description'          => 'Descripción corta ES',
            'search_results_description' => 'Descripción de búsqueda ES',
            'quick_shop_description'     => 'Descripción de compra rápida ES',
        ]);

        $fresh = $product->fresh();
        $this->assertEquals('Descripción de búsqueda ES', $fresh->getTranslated('search_results_description', $spanishLang->id));
        $this->assertEquals('Descripción de compra rápida ES', $fresh->getTranslated('quick_shop_description', $spanishLang->id));
    }
}
