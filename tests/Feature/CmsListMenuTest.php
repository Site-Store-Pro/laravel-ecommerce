<?php

namespace Tests\Feature;

use App\Models\CmsListMenu;
use App\Models\CmsListMenuItem;
use App\Models\CmsPage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CmsListMenuTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $customer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role_id' => 3, // Admin
            'email_verified_at' => now(),
        ]);

        $this->customer = User::create([
            'name' => 'Customer User',
            'email' => 'customer@example.com',
            'password' => bcrypt('password'),
            'role_id' => 1, // Customer
            'email_verified_at' => now(),
        ]);
    }

    public function test_non_admin_cannot_access_list_menus(): void
    {
        // Guest redirect
        $this->get(route('admin.cms-list-menus.index'))->assertRedirect(route('login'));

        // Customer forbidden
        $this->actingAs($this->customer);
        $this->get(route('admin.cms-list-menus.index'))->assertStatus(403);
    }

    public function test_admin_can_access_list_menus(): void
    {
        $this->actingAs($this->admin);
        $this->get(route('admin.cms-list-menus.index'))->assertStatus(200);
    }

    public function test_list_menus_livewire_crud_listing(): void
    {
        $this->actingAs($this->admin);

        // Test Livewire listing
        Livewire::test('admin-cms-list-menus')
            ->set('newMenuName', 'Header Menu List')
            ->call('createMenu')
            ->assertHasNoErrors()
            ->assertRedirect(); // should redirect to edit page

        $this->assertDatabaseHas('cms_list_menus', [
            'name' => 'Header Menu List'
        ]);
    }

    public function test_list_menu_edit_and_sorting(): void
    {
        $this->actingAs($this->admin);

        $menu = CmsListMenu::create([
            'name' => 'Sidebar Quick Links',
            'custom_css' => '.my-menu {}'
        ]);

        $item1 = CmsListMenuItem::create([
            'cms_list_menu_id' => $menu->id,
            'list_item' => 'Item A',
            'sort_val' => 10.0
        ]);

        $item2 = CmsListMenuItem::create([
            'cms_list_menu_id' => $menu->id,
            'list_item' => 'Item B',
            'sort_val' => 20.0
        ]);

        // Test reordering
        Livewire::test('admin-cms-list-menu-edit', ['id' => $menu->id])
            ->call('updateItemOrder', [$item2->id, $item1->id])
            ->assertHasNoErrors();

        // Verify reordered values in database
        $this->assertEquals(1.0, $item2->fresh()->sort_val);
        $this->assertEquals(2.0, $item1->fresh()->sort_val);
    }

    public function test_shortcode_parsing_in_page_response(): void
    {
        // 1. Create a page to link to
        $targetPage = CmsPage::create([
            'title' => 'FAQ Page',
            'slug' => 'faq-slug',
            'content' => 'FAQ details',
            'is_active' => true,
        ]);

        // 2. Create the menu and items with new [page:id] shortcode syntax
        $menu = CmsListMenu::create([
            'name' => 'Global Links',
            'custom_css' => '#cms-menu-' . 99 . ' { color: red; }'
        ]);

        CmsListMenuItem::create([
            'cms_list_menu_id' => $menu->id,
            'list_item' => '[page:' . $targetPage->id . ' label="Go to FAQ"]',
            'sort_val' => 1
        ]);

        // 3. Create a public page displaying the menu list via [list:id] shortcode
        $publicPage = CmsPage::create([
            'title' => 'Home Page',
            'slug' => 'home-page',
            'content' => '<div class="links-box">[list:' . $menu->id . ']</div>',
            'is_active' => true,
        ]);

        // 4. Access the public page and assert the final rendered HTML structure
        $response = $this->get(route('page.show', $publicPage->slug));
        $response->assertStatus(200);

        // Verify the parsed output includes list wrapper, custom CSS, and translated links
        $html = $response->getContent();
        $this->assertStringContainsString('<ul id="cms-menu-' . $menu->id . '" class="cms-list-menu">', $html);
        $this->assertStringContainsString('<a href="' . route('page.show', $targetPage->slug) . '">Go to FAQ</a>', $html);
        $this->assertStringContainsString('#cms-menu-99 { color: red; }', $html);
    }
}
