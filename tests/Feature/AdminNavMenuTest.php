<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\CmsPage;
use App\Models\NavMenu;
use App\Models\NavItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class AdminNavMenuTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_nav_menu_edit_component_and_search_cms_pages(): void
    {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role_id' => 3, // Admin
            'email_verified_at' => now(),
        ]);

        // Create a Nav Menu
        $menu = NavMenu::create([
            'name' => 'Header Menu',
            'slug' => 'header',
        ]);
        $menu->refresh();

        // Create CMS page
        $page = CmsPage::create([
            'title' => 'About Us',
            'slug' => 'about-us',
            'content' => 'About us page content',
            'is_active' => true,
        ]);

        $this->actingAs($admin);

        // Test AdminNavMenuEdit component
        $component = Livewire::test('admin-nav-menu-edit', ['menu' => $menu])
            ->assertStatus(200);

        // Get cms pages property and assert it contains our page
        $cmsPages = $component->get('cmsPages');
        $this->assertCount(1, $cmsPages);
        $this->assertEquals('About Us', $cmsPages->first()->title);
        $this->assertEquals($page->id, $cmsPages->first()->id);
    }

    public function test_public_navigation_renders_with_active_menu(): void
    {
        // Create a primary Nav Menu
        $menu = NavMenu::create([
            'name' => 'Main Navigation',
            'slug' => 'main-navigation',
            'is_primary' => true,
            'is_active' => true,
        ]);
        $menu->refresh();

        // Renders public-navigation component
        $component = Livewire::test('public-navigation');
        
        // Assert that the dynamic navigation container ID is present in HTML
        $component->assertSeeHtml('id="top-nav-main-navigation"');
    }

    public function test_admin_can_save_menu_alignment(): void
    {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role_id' => 3, // Admin
            'email_verified_at' => now(),
        ]);

        $menu = NavMenu::create([
            'name' => 'Header Menu',
            'slug' => 'header',
        ]);
        $menu->refresh();

        $this->actingAs($admin);

        Livewire::test('admin-nav-menu-edit', ['menu' => $menu])
            ->set('menuAlignment', 'center')
            ->call('saveAppearance')
            ->assertHasNoErrors();

        $menu->refresh();
        $this->assertEquals('center', $menu->alignment);
    }

    public function test_public_navigation_renders_categories_and_brands_items(): void
    {
        // Create a primary Nav Menu
        $menu = NavMenu::create([
            'name' => 'Main Navigation',
            'slug' => 'main-navigation',
            'is_primary' => true,
            'is_active' => true,
            'alignment' => 'right',
        ]);
        $menu->refresh();

        // Create categories and brands navigation items
        NavItem::create([
            'menu_id' => $menu->id,
            'label' => 'Categories Item',
            'item_type' => 'categories',
            'is_active' => true,
            'position' => 1,
        ]);

        NavItem::create([
            'menu_id' => $menu->id,
            'label' => 'Brands Item',
            'item_type' => 'brands',
            'is_active' => true,
            'position' => 2,
        ]);

        // Renders public-navigation component
        $component = Livewire::test('public-navigation');

        // Check alignment is applied to dynamic nav container in HTML
        $component->assertSeeHtml('justify-end');

        // Check categories and brands Livewire widgets are rendered in desktop
        $component->assertSee('Categories');
        $component->assertSee('Brands');
    }

    public function test_navigation_respects_hide_on_mobile_and_hide_on_desktop(): void
    {
        // Create a primary Nav Menu
        $menu = NavMenu::create([
            'name' => 'Visibility Navigation',
            'slug' => 'visibility-navigation',
            'is_primary' => true,
            'is_active' => true,
        ]);
        $menu->refresh();

        // 1. Top-level mobile hidden
        $topMobileHidden = NavItem::create([
            'menu_id' => $menu->id,
            'label' => 'Top Mobile Hidden',
            'item_type' => 'link',
            'url' => '/mobile-hidden',
            'hide_on_mobile' => true,
            'hide_on_desktop' => false,
            'is_active' => true,
            'position' => 1,
        ]);

        // 2. Top-level desktop hidden
        $topDesktopHidden = NavItem::create([
            'menu_id' => $menu->id,
            'label' => 'Top Desktop Hidden',
            'item_type' => 'link',
            'url' => '/desktop-hidden',
            'hide_on_mobile' => false,
            'hide_on_desktop' => true,
            'is_active' => true,
            'position' => 2,
        ]);

        // 3. Top-level normal
        $topNormal = NavItem::create([
            'menu_id' => $menu->id,
            'label' => 'Top Normal',
            'item_type' => 'link',
            'url' => '/normal',
            'hide_on_mobile' => false,
            'hide_on_desktop' => false,
            'is_active' => true,
            'position' => 3,
        ]);

        // 4. Child mobile hidden
        $childMobileHidden = NavItem::create([
            'menu_id' => $menu->id,
            'parent_id' => $topNormal->id,
            'label' => 'Child Mobile Hidden',
            'item_type' => 'link',
            'url' => '/child-mobile-hidden',
            'hide_on_mobile' => true,
            'hide_on_desktop' => false,
            'is_active' => true,
            'position' => 1,
        ]);

        // 5. Child desktop hidden
        $childDesktopHidden = NavItem::create([
            'menu_id' => $menu->id,
            'parent_id' => $topNormal->id,
            'label' => 'Child Desktop Hidden',
            'item_type' => 'link',
            'url' => '/child-desktop-hidden',
            'hide_on_mobile' => false,
            'hide_on_desktop' => true,
            'is_active' => true,
            'position' => 2,
        ]);

        // 6. Child normal
        $childNormal = NavItem::create([
            'menu_id' => $menu->id,
            'parent_id' => $topNormal->id,
            'label' => 'Child Normal',
            'item_type' => 'link',
            'url' => '/child-normal',
            'hide_on_mobile' => false,
            'hide_on_desktop' => false,
            'is_active' => true,
            'position' => 3,
        ]);

        $component = Livewire::test('public-navigation');
        $html = $component->html();

        // Separate the desktop portion (<nav class="hidden md:flex...>) and mobile portion (<div x-show="mobileOpen" class="md:hidden...>)
        $desktopStart = strpos($html, 'aria-label="Desktop navigation"');
        $mobileStart = strpos($html, 'class="md:hidden border-t px-4 py-4 space-y-1"');

        $this->assertNotFalse($desktopStart);
        $this->assertNotFalse($mobileStart);

        $desktopHtml = substr($html, $desktopStart, $mobileStart - $desktopStart);
        $mobileHtml = substr($html, $mobileStart);

        // Desktop assertions
        $this->assertStringContainsString('Top Mobile Hidden', $desktopHtml);
        $this->assertStringContainsString('Top Normal', $desktopHtml);
        $this->assertStringNotContainsString('Top Desktop Hidden', $desktopHtml);

        $this->assertStringContainsString('Child Mobile Hidden', $desktopHtml);
        $this->assertStringContainsString('Child Normal', $desktopHtml);
        $this->assertStringNotContainsString('Child Desktop Hidden', $desktopHtml);

        // Mobile assertions
        $this->assertStringContainsString('Top Desktop Hidden', $mobileHtml);
        $this->assertStringContainsString('Top Normal', $mobileHtml);
        $this->assertStringNotContainsString('Top Mobile Hidden', $mobileHtml);

        $this->assertStringContainsString('Child Desktop Hidden', $mobileHtml);
        $this->assertStringContainsString('Child Normal', $mobileHtml);
        $this->assertStringNotContainsString('Child Mobile Hidden', $mobileHtml);
    }

    public function test_navigation_login_logout_item(): void
    {
        // Create a primary Nav Menu
        $menu = NavMenu::create([
            'name' => 'Auth Navigation',
            'slug' => 'auth-navigation',
            'is_primary' => true,
            'is_active' => true,
        ]);
        $menu->refresh();

        // Create a login_logout nav item
        $loginLogoutItem = NavItem::create([
            'menu_id' => $menu->id,
            'label' => 'Sign In Here',
            'item_type' => 'login_logout',
            'is_active' => true,
            'position' => 1,
        ]);

        // 1. Guest view
        $component = Livewire::test('public-navigation');
        $html = $component->html();
        
        // As guest, it should show 'Sign In Here' and href containing '/login'
        $this->assertStringContainsString('Sign In Here', $html);
        $this->assertStringContainsString(route('login'), $html);
        $this->assertStringNotContainsString('Logout', $html);

        // 2. Authenticated view
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'role_id' => 1, // regular customer
            'email_verified_at' => now(),
        ]);

        $componentAuth = Livewire::actingAs($user)->test('public-navigation');
        $htmlAuth = $componentAuth->html();

        // As authenticated user, it should show 'Test User', profile link, and 'Logout' with wire:click.prevent="logout"
        $this->assertStringContainsString('Test User', $htmlAuth);
        $this->assertStringContainsString(route('profile'), $htmlAuth);
        $this->assertStringContainsString('Logout', $htmlAuth);
        $this->assertStringContainsString('wire:click.prevent="logout"', $htmlAuth);
        $this->assertStringNotContainsString('Sign In Here', $htmlAuth);
    }
}
