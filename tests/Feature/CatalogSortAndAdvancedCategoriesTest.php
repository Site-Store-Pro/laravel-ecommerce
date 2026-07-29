<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductReview;
use App\Livewire\ShopCatalog;
use Livewire\Livewire;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CatalogSortAndAdvancedCategoriesTest extends TestCase
{
    use RefreshDatabase;

    public function test_catalog_defaults_to_price_asc_sorting(): void
    {
        $cheapProduct = Product::create(['title' => 'Budget Widget', 'seo_slug' => 'budget-widget']);
        ProductVariant::create([
            'product_id' => $cheapProduct->id,
            'sku' => 'CHEAP1',
            'public_price' => 10.00,
            'wholesale_price' => 5.00,
        ]);

        $expensiveProduct = Product::create(['title' => 'Luxury Gadget', 'seo_slug' => 'luxury-gadget']);
        ProductVariant::create([
            'product_id' => $expensiveProduct->id,
            'sku' => 'LUX1',
            'public_price' => 100.00,
            'wholesale_price' => 50.00,
        ]);

        Livewire::test(ShopCatalog::class)
            ->assertSet('sort', 'price_asc')
            ->assertSeeInOrder(['Budget Widget', 'Luxury Gadget']);
    }

    public function test_catalog_sorts_price_desc_and_title(): void
    {
        $cheap = Product::create(['title' => 'Alpha Cheap Item', 'seo_slug' => 'alpha-cheap']);
        ProductVariant::create([
            'product_id' => $cheap->id,
            'sku' => 'CHEAP2',
            'public_price' => 15.00,
            'wholesale_price' => 10.00,
        ]);

        $expensive = Product::create(['title' => 'Zeta Expensive Item', 'seo_slug' => 'zeta-expensive']);
        ProductVariant::create([
            'product_id' => $expensive->id,
            'sku' => 'EXP2',
            'public_price' => 250.00,
            'wholesale_price' => 200.00,
        ]);

        // Price High-Low
        Livewire::test(ShopCatalog::class, ['sort' => 'price_desc'])
            ->assertSeeInOrder(['Zeta Expensive Item', 'Alpha Cheap Item']);

        // Title A-Z
        Livewire::test(ShopCatalog::class, ['sort' => 'title_asc'])
            ->assertSeeInOrder(['Alpha Cheap Item', 'Zeta Expensive Item']);

        // Title Z-A
        Livewire::test(ShopCatalog::class, ['sort' => 'title_desc'])
            ->assertSeeInOrder(['Zeta Expensive Item', 'Alpha Cheap Item']);
    }

    public function test_catalog_sorts_by_product_ratings(): void
    {
        $lowRated = Product::create(['title' => 'Low Rated Product', 'seo_slug' => 'low-rated', 'reviews_rating' => 1.5]);
        ProductVariant::create(['product_id' => $lowRated->id, 'sku' => 'LR1', 'public_price' => 20.00, 'wholesale_price' => 15.00]);

        $highRated = Product::create(['title' => 'High Rated Product', 'seo_slug' => 'high-rated', 'reviews_rating' => 4.9]);
        ProductVariant::create(['product_id' => $highRated->id, 'sku' => 'HR1', 'public_price' => 20.00, 'wholesale_price' => 15.00]);

        // Rating High to Low
        Livewire::test(ShopCatalog::class, ['sort' => 'rating_desc'])
            ->assertSeeInOrder(['High Rated Product', 'Low Rated Product']);

        // Rating Low to High
        Livewire::test(ShopCatalog::class, ['sort' => 'rating_asc'])
            ->assertSeeInOrder(['Low Rated Product', 'High Rated Product']);
    }

    public function test_active_category_and_brand_preselection_in_advanced_filters(): void
    {
        $cat = Category::create(['name' => 'Electronics', 'slug' => 'electronics', 'is_visible_in_menu' => true]);
        $brand = Brand::create(['name' => 'Acme Corp', 'slug' => 'acme-corp', 'is_visible_in_menu' => true]);

        $product = Product::create(['title' => 'Acme Camera', 'seo_slug' => 'acme-camera', 'brand_id' => $brand->id]);
        $product->categories()->attach($cat->id);
        ProductVariant::create(['product_id' => $product->id, 'sku' => 'CAM1', 'public_price' => 50.00, 'wholesale_price' => 40.00]);

        Livewire::test(ShopCatalog::class, ['category_slug' => 'electronics', 'brand_slug' => 'acme-corp'])
            ->assertSet('category', 'electronics')
            ->assertSet('brand', 'acme-corp')
            ->assertSet('selectedCategories', [(string)$cat->id])
            ->assertSet('selectedBrands', [(string)$brand->id]);
    }

    public function test_attribute_selection_maintains_clean_array_state(): void
    {
        \App\Models\CmsSetting::set('enable_advanced_shop_search', 1);

        $prodRed = Product::create(['title' => 'Red Shirt', 'seo_slug' => 'red-shirt']);
        ProductVariant::create([
            'product_id' => $prodRed->id,
            'sku' => 'RED-SHIRT-M',
            'public_price' => 20.00,
            'wholesale_price' => 10.00,
            'attributes' => json_encode(['Color' => 'Red', 'Size' => 'M']),
        ]);

        $prodBlue = Product::create(['title' => 'Blue Shirt', 'seo_slug' => 'blue-shirt']);
        ProductVariant::create([
            'product_id' => $prodBlue->id,
            'sku' => 'BLUE-SHIRT-L',
            'public_price' => 25.00,
            'wholesale_price' => 12.00,
            'attributes' => json_encode(['Color' => 'Blue', 'Size' => 'L']),
        ]);

        Livewire::test(ShopCatalog::class)
            ->set('selectedAttributes.Color', ['Red'])
            ->assertSet('selectedAttributes.Color', ['Red'])
            ->assertSee('Red Shirt')
            ->assertDontSee('Blue Shirt');
    }
}
