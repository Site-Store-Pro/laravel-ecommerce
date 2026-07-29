<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\CmsBuilderBlock;
use App\Models\CmsSetting;
use App\Models\User;
use App\Services\HeaderFooterCssManager;
use App\Services\HeaderFooterParserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class HeaderFooterBuilderTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(\Database\Seeders\CmsBuilderBlockSeeder::class);
    }

    public function test_admin_can_access_header_footer_builder_page(): void
    {
        $admin = User::factory()->create([
            'role_id' => UserRole::Admin,
        ]);

        $this->actingAs($admin)
            ->get(route('admin.cms-header-footer.index'))
            ->assertStatus(200);
    }

    public function test_non_admin_cannot_access_header_footer_builder_page(): void
    {
        $user = User::factory()->create([
            'role_id' => UserRole::User,
        ]);

        $this->actingAs($user)
            ->get(route('admin.cms-header-footer.index'))
            ->assertStatus(403);
    }

    public function test_admin_can_edit_block_content_and_device_toggles(): void
    {
        $admin = User::factory()->create([
            'role_id' => UserRole::Admin,
        ]);

        $block = CmsBuilderBlock::first();

        Livewire::actingAs($admin)
            ->test('admin-header-footer-builder')
            ->call('editBlock', $block->id)
            ->set('editTitle', 'Updated Header Block Title')
            ->set('editContentDesktop', '<div>Desktop Banner</div>')
            ->set('editContentTablet', '<div>Tablet Banner</div>')
            ->set('editContentMobile', '<div>Mobile Banner</div>')
            ->call('saveBlock');

        $this->assertDatabaseHas('cms_builder_blocks', [
            'id'              => $block->id,
            'title'           => 'Updated Header Block Title',
            'content_desktop' => '<div>Desktop Banner</div>',
            'content_tablet'  => '<div>Tablet Banner</div>',
            'content_mobile'  => '<div>Mobile Banner</div>',
        ]);
    }

    public function test_admin_can_reorder_blocks(): void
    {
        $admin = User::factory()->create([
            'role_id' => UserRole::Admin,
        ]);

        $firstBlock = CmsBuilderBlock::header()->orderBy('sort_desktop', 'asc')->first();
        $secondBlock = CmsBuilderBlock::header()->orderBy('sort_desktop', 'asc')->skip(1)->first();

        $initialSortFirst  = $firstBlock->sort_desktop;
        $initialSortSecond = $secondBlock->sort_desktop;

        Livewire::actingAs($admin)
            ->test('admin-header-footer-builder')
            ->set('deviceView', 'desktop')
            ->call('moveBlockDown', $firstBlock->id);

        $firstBlock->refresh();
        $secondBlock->refresh();

        $this->assertEquals($initialSortSecond, $firstBlock->sort_desktop);
        $this->assertEquals($initialSortFirst, $secondBlock->sort_desktop);
    }

    public function test_css_manager_saves_and_compiles_root_variables(): void
    {
        $admin = User::factory()->create([
            'role_id' => UserRole::Admin,
        ]);

        Livewire::actingAs($admin)
            ->test('admin-header-footer-builder')
            ->set('cssVars.primary_accent_color', '#FF0055')
            ->set('cssVars.header_background_color', '#112233')
            ->call('saveCssVars');

        $this->assertEquals('#FF0055', CmsSetting::get('css_var_primary_accent_color'));
        $this->assertEquals('#112233', CmsSetting::get('css_var_header_background_color'));

        $compiledCss = HeaderFooterCssManager::compileCss();

        $this->assertStringContainsString('--primary-accent-color:#F05;', $compiledCss);
        $this->assertStringContainsString('--header-background-color:#123;', $compiledCss);
    }

    public function test_shortcode_parser_expands_mustache_tags_and_year(): void
    {
        CmsSetting::set('site_name', 'Acme Store');

        $logoHtml = HeaderFooterParserService::renderDynamicLogo();
        $this->assertStringContainsString('Acme Store', $logoHtml);

        $raw = '<div>Copyright {{year}}</div>';
        $parsed = HeaderFooterParserService::parse($raw);

        $this->assertStringContainsString(date('Y'), $parsed);
    }

    public function test_storefront_renders_public_header_and_footer_components(): void
    {
        $response = $this->get('/shop');

        $response->assertStatus(200);
        $response->assertSee('header_container');
        $response->assertSee('footer_container');
    }

    public function test_admin_can_create_new_block(): void
    {
        $admin = User::factory()->create([
            'role_id' => UserRole::Admin,
        ]);

        Livewire::actingAs($admin)
            ->test('admin-header-footer-builder')
            ->call('openCreateModal', 'footer')
            ->set('newTitle', 'Custom Footer Column #6')
            ->set('newTargetElement', 'footer_col6')
            ->set('newContentDesktop', '<h3>Custom Column</h3>')
            ->call('saveNewBlock');

        $this->assertDatabaseHas('cms_builder_blocks', [
            'title'           => 'Custom Footer Column #6',
            'target_element'  => 'footer_col6',
            'section_type'    => 'footer',
            'content_desktop' => '<h3>Custom Column</h3>',
        ]);
    }

    public function test_admin_can_delete_block(): void
    {
        $admin = User::factory()->create([
            'role_id' => UserRole::Admin,
        ]);

        $block = CmsBuilderBlock::create([
            'title'        => 'Block To Delete',
            'section_type' => 'footer',
        ]);

        Livewire::actingAs($admin)
            ->test('admin-header-footer-builder')
            ->call('deleteBlock', $block->id);

        $this->assertDatabaseMissing('cms_builder_blocks', [
            'id' => $block->id,
        ]);
    }

    public function test_admin_can_seed_default_blocks(): void
    {
        $admin = User::factory()->create([
            'role_id' => UserRole::Admin,
        ]);

        CmsBuilderBlock::query()->delete();
        $this->assertEquals(0, CmsBuilderBlock::count());

        Livewire::actingAs($admin)
            ->test('admin-header-footer-builder')
            ->call('seedDefaultBlocks');

        $this->assertGreaterThan(0, CmsBuilderBlock::count());
    }

    public function test_admin_can_access_iframe_preview_endpoint(): void
    {
        $admin = User::factory()->create([
            'role_id' => UserRole::Admin,
        ]);

        $response = $this->actingAs($admin)
            ->get(route('admin.cms-header-footer.preview', ['device' => 'mobile', 'tab' => 'header']));

        $response->assertStatus(200);
        $response->assertSee('Header and Footer Live Preview Frame');
    }

    public function test_public_header_renders_sticky_navigation_and_categories_brands(): void
    {
        $cat = \App\Models\Category::create([
            'name' => 'Test Tech Category',
            'slug' => 'test-tech-category',
            'sort_order' => 1,
            'is_visible_in_menu' => true,
        ]);

        $product = \App\Models\Product::create([
            'title' => 'Test Product',
            'sku' => 'TEST-SKU-1',
            'slug' => 'test-product',
            'price' => 10.00,
            'is_active' => true,
        ]);

        $cat->products()->attach($product->id);

        $brand = \App\Models\Brand::create([
            'name' => 'Test Apex Brand',
            'slug' => 'test-apex-brand',
            'sort_order' => 1,
        ]);

        $menu = \App\Models\NavMenu::create([
            'name' => 'Primary Test Nav',
            'slug' => 'primary-test-nav',
            'is_primary' => true,
            'is_active' => true,
            'sticky' => true,
        ]);

        \App\Models\NavItem::create([
            'menu_id'   => $menu->id,
            'label'     => 'Categories Menu',
            'item_type' => 'categories',
            'is_active' => true,
        ]);

        \App\Models\NavItem::create([
            'menu_id'   => $menu->id,
            'label'     => 'Brands Menu',
            'item_type' => 'brands',
            'is_active' => true,
        ]);

        CmsSetting::set('top_nav_sticky', true);

        Livewire::test('public-header')
            ->assertSee('sticky top-0')
            ->assertSee('Test Tech Category')
            ->assertSee('Test Apex Brand');
    }

    public function test_removable_features_bar_and_embedded_navigation(): void
    {
        $admin = User::factory()->create([
            'role_id' => UserRole::Admin,
        ]);

        CmsSetting::set('nav_inside_main_header', '1');

        $this->actingAs($admin);

        Livewire::test(\App\Livewire\AdminHeaderFooterBuilder::class)
            ->assertSee('top_nav_container');

        $this->get(route('admin.cms-header-footer.preview'))
            ->assertStatus(200);
    }

    public function test_css_manager_clear_cache_and_toggle(): void
    {
        $admin = User::factory()->create([
            'role_id' => UserRole::Admin,
        ]);

        $this->actingAs($admin);

        Livewire::test(\App\Livewire\AdminHeaderFooterBuilder::class)
            ->call('toggleEmbedNavigation');

        $this->assertEquals('1', \App\Models\CmsSetting::get('nav_inside_main_header'));
        $this->assertEquals('1', \App\Models\CmsSetting::get('css_var_nav_inside_main_header'));
    }

    public function test_wireframe_canvas_renders_active_nav_menu_items(): void
    {
        $admin = User::factory()->create([
            'role_id' => UserRole::Admin,
        ]);

        \App\Models\NavMenu::query()->update(['is_primary' => false]);

        $menu = \App\Models\NavMenu::create([
            'name' => 'Custom Wireframe Menu',
            'slug' => 'custom-wireframe-menu',
            'is_primary' => true,
            'is_active' => true,
        ]);

        \App\Models\NavItem::create([
            'menu_id'   => $menu->id,
            'label'     => 'Special Active Product Item',
            'item_type' => 'custom',
            'url'       => '/special-item',
            'is_active' => true,
        ]);

        $this->actingAs($admin);

        Livewire::test(\App\Livewire\AdminHeaderFooterBuilder::class)
            ->assertSee('Custom Wireframe Menu')
            ->assertSee('Special Active Product Item');
    }

    public function test_dynamic_logo_fallback_and_custom_settings(): void
    {
        // 1. Fallback mode (no custom settings in DB)
        CmsSetting::whereIn('key', ['site_name', 'logo_type', 'logo_path', 'logo_svg_html'])->delete();
        \Illuminate\Support\Facades\Cache::forget('cms_settings_all');

        $defaultLogoHtml = HeaderFooterParserService::renderDynamicLogo();
        $this->assertStringContainsString('Support Tickets', $defaultLogoHtml);
        $this->assertStringContainsString('<svg class="w-5 h-5"', $defaultLogoHtml);

        // 2. Custom site name only
        CmsSetting::set('site_name', 'Vanguard Cyber Store');
        $customNameHtml = HeaderFooterParserService::renderDynamicLogo();
        $this->assertStringContainsString('Vanguard Cyber Store', $customNameHtml);
        $this->assertStringContainsString('<svg class="w-5 h-5"', $customNameHtml);

        // 3. Custom SVG logo
        CmsSetting::set('logo_type', 'svg');
        CmsSetting::set('logo_svg_html', '<svg id="custom-logo-svg"><circle cx="10" cy="10" r="5"/></svg>');
        $customSvgHtml = HeaderFooterParserService::renderDynamicLogo();
        $this->assertStringContainsString('Vanguard Cyber Store', $customSvgHtml);
        $this->assertStringContainsString('<svg id="custom-logo-svg"', $customSvgHtml);
    }

    public function test_admin_can_reorder_header_rows(): void
    {
        $admin = User::factory()->create([
            'role_id' => UserRole::Admin,
        ]);

        $navBlock = CmsBuilderBlock::where('target_element', 'top_nav_container')->first();
        $sharingBlock = CmsBuilderBlock::where('target_element', 'top_sharing_container')->first();
        $headerBlock = CmsBuilderBlock::where('target_element', 'site_header_container')->first();

        Livewire::actingAs($admin)
            ->test('admin-header-footer-builder')
            ->set('deviceView', 'desktop')
            ->call('reorderHeaderRows', 2, 0);

        $navBlock->refresh();
        $sharingBlock->refresh();
        $headerBlock->refresh();

        $this->assertTrue($navBlock->sort_desktop < $sharingBlock->sort_desktop);
        $this->assertTrue($sharingBlock->sort_desktop < $headerBlock->sort_desktop);
    }

    public function test_dynamic_menu_desktop_visibility_and_nav_item_url_resolution(): void
    {
        $menu = \App\Models\NavMenu::create([
            'name' => 'Test Desktop Menu',
            'slug' => 'test-desktop-menu',
            'is_primary' => true,
            'is_active' => true,
        ]);

        $item = \App\Models\NavItem::create([
            'menu_id' => $menu->id,
            'label' => 'Explore Shop',
            'item_type' => 'shop',
            'is_active' => true,
            'position' => 1.0,
        ]);

        $this->assertEquals(route('shop.index'), $item->computed_url);
        $this->assertEquals('_self', $item->target);

        Livewire::test(\App\Livewire\PublicHeader::class)
            ->assertSeeHtml('top_nav_container')
            ->assertSeeHtml('hidden lg:flex')
            ->assertSee('Explore Shop');
    }

    public function test_shop_catalog_header_title_has_no_gradient_shading(): void
    {
        Livewire::test(\App\Livewire\ShopCatalog::class)
            ->assertSeeHtml('<h1 class="text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white">E-Commerce Products</h1>')
            ->assertDontSeeHtml('bg-gradient-to-r');
    }

    public function test_editing_top_nav_container_displays_navigation_builder_alert(): void
    {
        $admin = User::factory()->create(['role_id' => UserRole::Admin]);
        $navBlock = CmsBuilderBlock::where('target_element', 'top_nav_container')->firstOrFail();

        Livewire::actingAs($admin)
            ->test('admin-header-footer-builder')
            ->call('editBlock', $navBlock->id)
            ->assertSee('Navigation Bar Managed With Navigation Builder')
            ->assertSee('MANAGED VIA NAVIGATION BUILDER')
            ->assertSee('Open Navigation Builder in Admin');
    }

    public function test_nav_and_features_placement_in_header_columns(): void
    {
        $admin = User::factory()->create(['role_id' => UserRole::Admin]);

        Livewire::actingAs($admin)
            ->test('admin-header-footer-builder')
            ->call('setNavPlacement', 'header_col1')
            ->call('setFeaturesPlacement', 'header_col2');

        $this->assertEquals('header_col1', \App\Models\CmsSetting::get('nav_placement'));
        $this->assertEquals('header_col2', \App\Models\CmsSetting::get('features_placement'));

        Livewire::test(\App\Livewire\PublicHeader::class)
            ->assertSeeHtml('top_nav_area_col1')
            ->assertSeeHtml('header_features_icons_col2');
    }

    public function test_header_padding_defaults_and_cart_icon_dispatches_open_cart(): void
    {
        $vars = \App\Services\HeaderFooterCssManager::getActiveVariables();
        $this->assertEquals('5px', $vars['header_padding_top']);
        $this->assertEquals('5px', $vars['header_padding_bottom']);

        $css = \App\Services\HeaderFooterCssManager::compileCss($vars);
        // dump($css);
        $this->assertStringContainsString('5px', $css);
        $this->assertStringContainsString('--header-padding-top:', $css);

        Livewire::test(\App\Livewire\PublicHeader::class)
            ->assertSeeHtml('open-cart');
    }
}
