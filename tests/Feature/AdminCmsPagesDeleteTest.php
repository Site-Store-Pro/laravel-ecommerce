<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\CmsPage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Livewire\Livewire;
use Tests\TestCase;

class AdminCmsPagesDeleteTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        DB::statement("INSERT IGNORE INTO `user_roles` (`id`, `name`, `description`) VALUES 
            (1, 'User', 'Customer'),
            (2, 'Wholesale', 'Wholesale'),
            (3, 'Admin', 'Admin')");

        DB::statement("INSERT IGNORE INTO `cms_page_types` (`id`, `title`) VALUES (1, 'Standard Page')");
        DB::statement("INSERT IGNORE INTO `cms_layouts` (`id`, `name`, `code`) VALUES (1, 'Default', 'default')");

        $this->admin = User::create([
            'name'              => 'Admin User',
            'email'             => 'admin_cms_del_test@support.local',
            'password'          => bcrypt('password'),
            'role_id'           => UserRole::Admin,
            'email_verified_at' => now(),
        ]);
    }

    public function test_admin_can_open_and_cancel_page_delete_modal(): void
    {
        $page = CmsPage::create([
            'title'     => 'Sample Page To Keep',
            'slug'      => 'sample-page-to-keep',
            'content'   => '<p>Content</p>',
            'is_active' => true,
            'author_id' => $this->admin->id,
        ]);

        Livewire::actingAs($this->admin)
            ->test(\App\Livewire\AdminCmsPages::class)
            ->assertSet('showDeleteModal', false)
            ->call('confirmDeletePage', $page->id)
            ->assertSet('showDeleteModal', true)
            ->assertSet('deletePageId', $page->id)
            ->assertSet('deletePageTitle', 'Sample Page To Keep')
            ->call('cancelDeletePage')
            ->assertSet('showDeleteModal', false)
            ->assertSet('deletePageId', null);

        $this->assertDatabaseHas('cms_pages', ['id' => $page->id]);
    }

    public function test_deleting_page_only_deletes_the_targeted_page(): void
    {
        $pageA = CmsPage::create([
            'title'     => 'Page Alpha',
            'slug'      => 'page-alpha',
            'content'   => '<p>Alpha</p>',
            'is_active' => true,
            'author_id' => $this->admin->id,
        ]);

        $pageB = CmsPage::create([
            'title'     => 'Page Beta',
            'slug'      => 'page-beta',
            'content'   => '<p>Beta</p>',
            'is_active' => true,
            'author_id' => $this->admin->id,
        ]);

        $pageC = CmsPage::create([
            'title'     => 'Page Gamma',
            'slug'      => 'page-gamma',
            'content'   => '<p>Gamma</p>',
            'is_active' => true,
            'author_id' => $this->admin->id,
        ]);

        // Delete specifically Page B
        Livewire::actingAs($this->admin)
            ->test(\App\Livewire\AdminCmsPages::class)
            ->call('confirmDeletePage', $pageB->id)
            ->assertSet('deletePageId', $pageB->id)
            ->call('deletePage')
            ->assertSet('showDeleteModal', false)
            ->assertSet('deletePageId', null);

        // Verify Page B is deleted, Page A and C remain intact
        $this->assertDatabaseMissing('cms_pages', ['id' => $pageB->id]);
        $this->assertDatabaseHas('cms_pages', ['id' => $pageA->id]);
        $this->assertDatabaseHas('cms_pages', ['id' => $pageC->id]);
    }

    public function test_deleting_homepage_id_1_is_blocked(): void
    {
        $homePage = CmsPage::forceCreate([
            'id'        => 1,
            'title'     => 'Home Page',
            'slug'      => 'home',
            'content'   => '<p>Home</p>',
            'is_active' => true,
            'author_id' => $this->admin->id,
        ]);

        Livewire::actingAs($this->admin)
            ->test(\App\Livewire\AdminCmsPages::class)
            ->call('confirmDeletePage', 1)
            ->assertSet('showDeleteModal', false)
            ->call('deletePage', 1);

        $this->assertDatabaseHas('cms_pages', ['id' => 1]);
    }
}
