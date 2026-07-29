<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Category;
use App\Models\CmsSetting;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use App\Enums\UserRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Livewire\Livewire;
use Tests\TestCase;

class AdvancedShopSearchFilterTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Cache::flush();
        \Illuminate\Support\Facades\DB::table('cms_settings')->where('key', 'enable_advanced_shop_search')->delete();
        Cache::flush();
    }

    public function test_advanced_search_setting_defaults_to_false(): void
    {
        $this->assertFalse(CmsSetting::isAdvancedSearchEnabled());
    }

    public function test_admin_can_toggle_advanced_search_setting(): void
    {
        $admin = User::factory()->create(['role_id' => UserRole::Admin]);

        Livewire::actingAs($admin)
            ->test(\App\Livewire\AdminSettings::class)
            ->set('enable_advanced_shop_search', true)
            ->call('save');

        Cache::flush();
        $this->assertTrue(CmsSetting::isAdvancedSearchEnabled());
    }

    public function test_shop_catalog_hides_advanced_filters_when_disabled(): void
    {
        Cache::flush();
        \Illuminate\Support\Facades\DB::table('cms_settings')->where('key', 'enable_advanced_shop_search')->delete();
        Cache::flush();

        $this->assertFalse(CmsSetting::isAdvancedSearchEnabled());

        Livewire::test(\App\Livewire\ShopCatalog::class)
            ->assertViewHas('advancedSearchEnabled', false)
            ->assertDontSee('Advanced Filters');
    }

    public function test_shop_catalog_shows_advanced_filters_when_enabled(): void
    {
        CmsSetting::set('enable_advanced_shop_search', '1');
        Cache::forget('cms_settings_all');

        $this->assertTrue(CmsSetting::isAdvancedSearchEnabled());

        Livewire::test(\App\Livewire\ShopCatalog::class)
            ->assertViewHas('advancedSearchEnabled', true)
            ->assertSee('Advanced Filters');
    }

    public function test_multi_brand_checkbox_filtering(): void
    {
        CmsSetting::set('enable_advanced_shop_search', '1');
        Cache::forget('cms_settings_all');

        $brandA = Brand::create(['name' => 'Brand Alpha', 'slug' => 'brand-alpha', 'is_visible_in_menu' => true]);
        $brandB = Brand::create(['name' => 'Brand Beta', 'slug' => 'brand-beta', 'is_visible_in_menu' => true]);

        $prodA = Product::create(['title' => 'Alpha Gadget', 'seo_slug' => 'alpha-gadget', 'brand_id' => $brandA->id]);
        ProductVariant::create(['product_id' => $prodA->id, 'sku' => 'ALPHA-1', 'public_price' => 50.00, 'wholesale_price' => 40.00]);

        $prodB = Product::create(['title' => 'Beta Widget', 'seo_slug' => 'beta-widget', 'brand_id' => $brandB->id]);
        ProductVariant::create(['product_id' => $prodB->id, 'sku' => 'BETA-1', 'public_price' => 75.00, 'wholesale_price' => 60.00]);

        Livewire::test(\App\Livewire\ShopCatalog::class)
            ->set('selectedBrands', [$brandA->id])
            ->assertSee('Alpha Gadget')
            ->assertDontSee('Beta Widget');
    }

    public function test_price_range_slider_filtering(): void
    {
        CmsSetting::set('enable_advanced_shop_search', '1');
        Cache::forget('cms_settings_all');

        $prodCheap = Product::create(['title' => 'Budget Item', 'seo_slug' => 'budget-item']);
        ProductVariant::create(['product_id' => $prodCheap->id, 'sku' => 'CHEAP-1', 'public_price' => 20.00, 'wholesale_price' => 15.00]);

        $prodExpensive = Product::create(['title' => 'Luxury Item', 'seo_slug' => 'luxury-item']);
        ProductVariant::create(['product_id' => $prodExpensive->id, 'sku' => 'LUX-1', 'public_price' => 500.00, 'wholesale_price' => 450.00]);

        Livewire::test(\App\Livewire\ShopCatalog::class)
            ->set('minPriceFilter', 10)
            ->set('maxPriceFilter', 100)
            ->assertSee('Budget Item')
            ->assertDontSee('Luxury Item');
    }

    public function test_dynamic_json_variant_attribute_filtering(): void
    {
        CmsSetting::set('enable_advanced_shop_search', '1');
        Cache::forget('cms_settings_all');

        $prodShirt = Product::create(['title' => 'Cotton T-Shirt', 'seo_slug' => 'cotton-tshirt']);
        ProductVariant::create([
            'product_id' => $prodShirt->id,
            'sku' => 'TSHIRT-XL-RED',
            'public_price' => 25.00,
            'wholesale_price' => 20.00,
            'attributes' => json_encode(['Size' => 'XL', 'Color' => 'Red']),
        ]);

        $prodPants = Product::create(['title' => 'Denim Jeans', 'seo_slug' => 'denim-jeans']);
        ProductVariant::create([
            'product_id' => $prodPants->id,
            'sku' => 'JEANS-32-BLUE',
            'public_price' => 60.00,
            'wholesale_price' => 50.00,
            'attributes' => json_encode(['Size' => '32', 'Color' => 'Blue']),
        ]);

        Livewire::test(\App\Livewire\ShopCatalog::class)
            ->set('selectedAttributes', ['Size' => ['XL']])
            ->assertSee('Cotton T-Shirt')
            ->assertDontSee('Denim Jeans');
    }

    public function test_reset_all_advanced_filters(): void
    {
        CmsSetting::set('enable_advanced_shop_search', '1');
        Cache::forget('cms_settings_all');

        Livewire::test(\App\Livewire\ShopCatalog::class)
            ->set('selectedBrands', [1, 2])
            ->set('minPriceFilter', 50)
            ->set('selectedAttributes', ['Size' => ['L']])
            ->call('resetAllAdvancedFilters')
            ->assertSet('selectedBrands', [])
            ->assertSet('minPriceFilter', null)
            ->assertSet('selectedAttributes', []);
    }

    public function test_boolean_or_malformed_attribute_input_does_not_throw_exception(): void
    {
        CmsSetting::set('enable_advanced_shop_search', '1');
        Cache::forget('cms_settings_all');

        $prodShirt = Product::create(['title' => 'Silk Shirt', 'seo_slug' => 'silk-shirt']);
        ProductVariant::create([
            'product_id' => $prodShirt->id,
            'sku' => 'SILK-S',
            'public_price' => 40.00,
            'wholesale_price' => 30.00,
            'attributes' => json_encode(['Size' => 'S']),
        ]);

        Livewire::test(\App\Livewire\ShopCatalog::class)
            ->set('selectedAttributes', ['Size' => true])
            ->assertOk()
            ->assertSee('Silk Shirt');
    }

    public function test_realtime_filtered_product_counter_on_advanced_panel(): void
    {
        CmsSetting::set('enable_advanced_shop_search', '1');
        Cache::forget('cms_settings_all');

        $p1 = Product::create(['title' => 'Item One', 'seo_slug' => 'item-one']);
        ProductVariant::create(['product_id' => $p1->id, 'sku' => 'SKU-1', 'public_price' => 10.00, 'wholesale_price' => 8.00]);

        $p2 = Product::create(['title' => 'Item Two', 'seo_slug' => 'item-two']);
        ProductVariant::create(['product_id' => $p2->id, 'sku' => 'SKU-2', 'public_price' => 20.00, 'wholesale_price' => 15.00]);

        Livewire::test(\App\Livewire\ShopCatalog::class)
            ->assertSeeHtml('2 Products')
            ->set('minPriceFilter', 15)
            ->assertSeeHtml('1 Product');
    }
}
