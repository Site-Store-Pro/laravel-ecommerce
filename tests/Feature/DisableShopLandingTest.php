<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Category;
use App\Models\CmsSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class DisableShopLandingTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_settings_can_toggle_disable_shop_landing(): void
    {
        $admin = User::create([
            'name'     => 'Admin User',
            'email'    => 'admin@example.com',
            'password' => bcrypt('password'),
            'role_id'  => 3,
        ]);

        $this->actingAs($admin);

        Livewire::test('admin-settings')
            ->set('disable_shop_landing', true)
            ->call('save');

        $this->assertTrue(CmsSetting::isEnabled('disable_shop_landing'));
    }

    public function test_shop_catalog_renders_normally_when_disable_shop_landing_is_off(): void
    {
        CmsSetting::set('disable_shop_landing', '0');

        $response = $this->get('/shop');
        $response->assertStatus(200);
    }

    public function test_shop_catalog_redirects_to_home_when_disable_shop_landing_is_on_and_no_filter_active(): void
    {
        CmsSetting::set('disable_shop_landing', '1');

        $response = $this->get('/shop');
        $response->assertRedirect('/');
    }

    public function test_shop_catalog_allows_access_when_disable_shop_landing_is_on_if_category_filter_is_active(): void
    {
        CmsSetting::set('disable_shop_landing', '1');

        Category::create([
            'name' => 'Hardware',
            'slug' => 'hardware',
        ]);

        $response = $this->get('/section/hardware');
        $response->assertStatus(200);
    }

    public function test_shop_catalog_allows_access_when_disable_shop_landing_is_on_if_brand_filter_is_active(): void
    {
        CmsSetting::set('disable_shop_landing', '1');

        Brand::create([
            'name' => 'Sony',
            'slug' => 'sony',
        ]);

        $response = $this->get('/brands/sony');
        $response->assertStatus(200);
    }

    public function test_shop_catalog_allows_access_when_disable_shop_landing_is_on_if_search_filter_is_active(): void
    {
        CmsSetting::set('disable_shop_landing', '1');

        $response = $this->get('/shop?search=wireless');
        $response->assertStatus(200);
    }
}
