<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ShopMetaTitleTest extends TestCase
{
    use RefreshDatabase;

    public function test_shop_catalog_meta_title_reflects_category_name(): void
    {
        $parentCat = Category::create([
            'name' => 'Electronics',
            'slug' => 'electronics',
        ]);
        $childCat = Category::create([
            'name' => 'Laptops',
            'slug' => 'laptops',
            'parent_id' => $parentCat->id,
        ]);

        $comp = Livewire::test('shop-catalog', ['category_slug' => 'laptops']);

        $comp->assertViewHas('pageTitle', 'Electronics › Laptops')
             ->assertSee('Electronics › Laptops');
    }

    public function test_shop_catalog_meta_title_reflects_brand_name(): void
    {
        Brand::create([
            'name' => 'Apple',
            'slug' => 'apple',
        ]);

        $comp = Livewire::test('shop-catalog', ['brand_slug' => 'apple']);

        $comp->assertViewHas('pageTitle', 'Apple')
             ->assertSee('Apple');
    }

    public function test_shop_catalog_meta_title_reflects_both_category_and_brand(): void
    {
        $cat = Category::create([
            'name' => 'Computers',
            'slug' => 'computers',
        ]);
        Brand::create([
            'name' => 'Dell',
            'slug' => 'dell',
        ]);

        $comp = Livewire::withQueryParams(['brand' => 'dell'])
            ->test('shop-catalog', ['category_slug' => 'computers']);

        $comp->assertViewHas('pageTitle', 'Computers › Dell')
             ->assertSee('Computers › Dell');
    }
}
