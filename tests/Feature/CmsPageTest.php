<?php

namespace Tests\Feature;

use App\Models\CmsPage;
use App\Models\CmsPageRevision;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use App\Enums\UserRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CmsPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_dynamic_page_catch_all_routing_loads_successful_response(): void
    {
        $author = User::factory()->create(['role_id' => UserRole::Admin->value]);
        
        $page = CmsPage::create([
            'title' => 'Terms of Service',
            'slug' => 'terms-of-service',
            'content' => '<p>These are our terms.</p>',
            'is_active' => true,
            'author_id' => $author->id,
        ]);

        $response = $this->get('/terms-of-service');
        $response->assertStatus(200);
        $response->assertSee('Terms of Service');
        $response->assertSee('These are our terms.');
    }

    public function test_inactive_page_returns_404(): void
    {
        $author = User::factory()->create(['role_id' => UserRole::Admin->value]);

        $page = CmsPage::create([
            'title' => 'Draft Page',
            'slug' => 'draft-page',
            'content' => '<p>Under construction.</p>',
            'is_active' => false,
            'author_id' => $author->id,
        ]);

        $response = $this->get('/draft-page');
        $response->assertStatus(404);
    }

    public function test_expired_page_returns_404(): void
    {
        $author = User::factory()->create(['role_id' => UserRole::Admin->value]);

        $page = CmsPage::create([
            'title' => 'Expired Deal',
            'slug' => 'expired-deal',
            'content' => '<p>Offered ended.</p>',
            'is_active' => true,
            'expires_at' => now()->subDay(),
            'author_id' => $author->id,
        ]);

        $response = $this->get('/expired-deal');
        $response->assertStatus(404);
    }

    public function test_passcode_gated_page_flow(): void
    {
        $author = User::factory()->create(['role_id' => UserRole::Admin->value]);

        $page = CmsPage::create([
            'title' => 'Locked Guide',
            'slug' => 'locked-guide',
            'content' => '<p>Highly secret guide content.</p>',
            'is_active' => true,
            'requires_code' => true,
            'access_code' => 'SECRETPASS',
            'author_id' => $author->id,
        ]);

        // 1. Visit page first, expect passcode prompt screen
        $response = $this->get('/locked-guide');
        $response->assertStatus(200);
        $response->assertSee('Access Code Required');
        $response->assertDontSee('Highly secret guide content.');

        // 2. Post incorrect code, expect validation back
        $response = $this->post(route('page.unlock', $page->id), ['code' => 'WRONGCODE']);
        $response->assertSessionHasErrors(['code']);

        // 3. Post correct code, expect redirect and unlock
        $response = $this->post(route('page.unlock', $page->id), ['code' => 'SECRETPASS']);
        $response->assertRedirect('/locked-guide');
        $this->assertContains('SECRETPASS', session('unlocked_access_codes'));

        // 4. Visit page again, expect success
        $response = $this->get('/locked-guide');
        $response->assertStatus(200);
        $response->assertSee('Locked Guide');
        $response->assertSee('Highly secret guide content.');
    }

    public function test_product_purchase_gate_flow(): void
    {
        $author = User::factory()->create(['role_id' => UserRole::Admin->value]);
        
        $product = Product::create([
            'title' => 'Exclusive Ebook Product',
            'seo_slug' => 'exclusive-ebook-product',
        ]);
        
        $page = CmsPage::create([
            'title' => 'Exclusive Ebook Page',
            'slug' => 'ebook-page',
            'content' => '<p>Download link: ebook.pdf</p>',
            'is_active' => true,
            'required_product_id' => $product->id,
            'author_id' => $author->id,
        ]);

        // 1. Guest access is redirected to login
        $response = $this->get('/ebook-page');
        $response->assertRedirect('/login');

        // 2. User with no purchase gets redirected to login (or back to login with error)
        $user = User::factory()->create(['role_id' => UserRole::User->value]);
        $response = $this->actingAs($user)->get('/ebook-page');
        $response->assertRedirect('/login');

        // 3. User with completed order status 7 containing product can view the page
        $variant = ProductVariant::create([
            'product_id' => $product->id,
            'sku' => 'EBOOK-VAR-1',
            'public_price' => 10,
            'wholesale_price' => 5,
        ]);
        
        $order = Order::create([
            'order_user_id' => $user->id,
            'order_status' => 7, // Completed
            'order_invoice_no' => 'INV-TEST-CMS',
            'order_subtotal' => 100,
            'order_taxes' => 0,
            'order_discounts' => 0,
            'order_shipping' => 0,
            'order_total' => 100,
            'order_date' => now()->format('Y-m-d H:i:s'),
        ]);

        OrderDetail::create([
            'order_id' => $order->id,
            'inventory_id' => $variant->id,
            'item_name' => 'Exclusive Ebook Product',
            'item_qty' => 1,
            'final_price' => 100,
            'base_price' => 100,
            'discount_price' => 0,
            'options_fee' => 0,
        ]);

        $response = $this->actingAs($user)->get('/ebook-page');
        $response->assertStatus(200);
        $response->assertSee('Exclusive Ebook Page');
        $response->assertSee('Download link: ebook.pdf');
        $this->assertContains($product->id, session('verified_purchased_products'));
    }

    public function test_admin_custom_page_saving_and_autosave_revisions(): void
    {
        $admin = User::factory()->create(['role_id' => UserRole::Admin->value]);

        // Test creating and autosaving with livewire
        Livewire::actingAs($admin)
            ->test(\App\Livewire\AdminCmsPageEdit::class)
            ->set('title', 'About Us')
            ->set('slug', 'about-us')
            ->set('content', '<p>Version 1 content</p>')
            ->call('save');

        $page = CmsPage::where('slug', 'about-us')->first();
        $this->assertNotNull($page);
        $this->assertEquals('About Us', $page->title);

        // Check manual revision is created
        $this->assertDatabaseHas('cms_page_revisions', [
            'cms_page_id' => $page->id,
            'revision_type' => 'manual',
            'content' => '<p>Version 1 content</p>'
        ]);

        // Test autosave livewire action
        Livewire::actingAs($admin)
            ->test(\App\Livewire\AdminCmsPageEdit::class, ['id' => $page->id])
            ->set('content', '<p>Version 2 typed content</p>')
            ->call('saveAutoSaveRevision');

        $this->assertDatabaseHas('cms_page_revisions', [
            'cms_page_id' => $page->id,
            'revision_type' => 'autosave',
            'content' => '<p>Version 2 typed content</p>'
        ]);
        
        // Assert active page content is still Version 1 because autosave doesn't replace active content
        $page->refresh();
        $this->assertEquals('<p>Version 1 content</p>', $page->content);

        // Restore revision
        $revision = CmsPageRevision::where('cms_page_id', $page->id)
            ->where('revision_type', 'autosave')
            ->first();

        Livewire::actingAs($admin)
            ->test(\App\Livewire\AdminCmsPageEdit::class, ['id' => $page->id])
            ->call('restoreRevision', $revision->id)
            ->assertSet('content', '<p>Version 2 typed content</p>');
    }

    public function test_page_layouts_support(): void
    {
        $admin = User::factory()->create(['role_id' => UserRole::Admin->value]);

        // 1. Create a page with left + main + right sidebar layout
        Livewire::actingAs($admin)
            ->test(\App\Livewire\AdminCmsPageEdit::class)
            ->set('title', 'Three Column Page')
            ->set('slug', 'three-column')
            ->set('content', '<p>Main content here</p>')
            ->set('layout_type', 4) // Left + Main + Right sidebars
            ->set('left_col', '<p>Left sidebar content</p>')
            ->set('right_col', '<p>Right sidebar content</p>')
            ->call('save');

        $page = CmsPage::where('slug', 'three-column')->first();
        $this->assertNotNull($page);
        $this->assertEquals(4, $page->layout_type);
        $this->assertEquals('<p>Left sidebar content</p>', $page->left_col);
        $this->assertEquals('<p>Right sidebar content</p>', $page->right_col);

        // Check revision saved these fields
        $this->assertDatabaseHas('cms_page_revisions', [
            'cms_page_id' => $page->id,
            'layout_type' => 4,
            'left_col' => '<p>Left sidebar content</p>',
            'right_col' => '<p>Right sidebar content</p>',
        ]);

        // 2. View storefront, expect all three columns to be rendered
        $response = $this->get('/three-column');
        $response->assertStatus(200);
        $response->assertSee('Left sidebar content');
        $response->assertSee('Main content here');
        $response->assertSee('Right sidebar content');

        // 3. Test autosave revision for multi-column layout
        Livewire::actingAs($admin)
            ->test(\App\Livewire\AdminCmsPageEdit::class, ['id' => $page->id])
            ->set('left_col', '<p>Modified left content</p>')
            ->call('saveAutoSaveRevision');

        $this->assertDatabaseHas('cms_page_revisions', [
            'cms_page_id' => $page->id,
            'revision_type' => 'autosave',
            'left_col' => '<p>Modified left content</p>'
        ]);

        // 4. Restore revision and check if component fields sync
        $revision = CmsPageRevision::where('cms_page_id', $page->id)
            ->where('revision_type', 'autosave')
            ->first();

        Livewire::actingAs($admin)
            ->test(\App\Livewire\AdminCmsPageEdit::class, ['id' => $page->id])
            ->call('restoreRevision', $revision->id)
            ->assertSet('left_col', '<p>Modified left content</p>');
    }

    public function test_publishing_options_and_failsafe_revisions(): void
    {
        $admin = User::factory()->create(['role_id' => UserRole::Admin->value]);
        $guest = User::factory()->create(['role_id' => UserRole::User->value]);

        // 1. Create page with custom publishing options (custom author, show toggles) and is_active = false (draft)
        Livewire::actingAs($admin)
            ->test(\App\Livewire\AdminCmsPageEdit::class)
            ->set('title', 'My Draft Guide')
            ->set('slug', 'draft-guide')
            ->set('content', '<p>Some draft content</p>')
            ->set('is_active', false) // Draft Mode
            ->set('custom_author', 'Anonymous Contributor')
            ->set('show_author', true)
            ->set('show_title', false) // Hide Title
            ->set('show_date', false) // Hide Date
            ->call('save');

        $page = CmsPage::where('slug', 'draft-guide')->first();
        $this->assertNotNull($page);
        $this->assertFalse($page->is_active);
        $this->assertEquals('Anonymous Contributor', $page->custom_author);

        // 2. Draft Mode Gating: Guest gets 404
        $response = $this->actingAs($guest)->get('/draft-guide');
        $response->assertStatus(404);

        // 3. Draft Mode Gating: Admin gets 200 (viewable by logged in admin only)
        $response = $this->actingAs($admin)->get('/draft-guide');
        $response->assertStatus(200);
        $response->assertSee('Anonymous Contributor');
        $response->assertDontSee('<h1'); // show_title was false!

        // 4. Test failsafe backup revision creation on restore
        // Let's create a manual revision first
        Livewire::actingAs($admin)
            ->test(\App\Livewire\AdminCmsPageEdit::class, ['id' => $page->id])
            ->set('content', '<p>Some secondary content</p>')
            ->call('save');

        // Verify we have a manual revision
        $secondRevision = CmsPageRevision::where('cms_page_id', $page->id)
            ->where('revision_type', 'manual')
            ->orderBy('id', 'desc')
            ->first();

        // Update the current page's active state in component (e.g. typing something new)
        // Then perform restore of the old manual revision.
        // It must save a backup of the current page content ("Some secondary content")
        Livewire::actingAs($admin)
            ->test(\App\Livewire\AdminCmsPageEdit::class, ['id' => $page->id])
            ->set('content', '<p>Accidentally typing this before restoring</p>')
            ->call('restoreRevision', $secondRevision->id);

        // Assert database has backup revision containing the state before restore
        $this->assertDatabaseHas('cms_page_revisions', [
            'cms_page_id' => $page->id,
            'revision_type' => 'backup',
            'title' => '[Backup before restore] ' . $page->title,
            'content' => '<p>Some secondary content</p>',
        ]);
    }

    public function test_home_page_integration(): void
    {
        $this->seed();

        // 1. Visit root URL, verify it loads correctly and pulls dynamic meta from page ID = 1
        $response = $this->get('/');
        $response->assertStatus(200);
        
        // Assert meta description from seed is present
        $response->assertSee('Introducing Our E-Commerce & Support Platform', false);
        $response->assertSee('Browse Store');

        // 2. Test Livewire components are rendered
        $response->assertSeeLivewire(\App\Livewire\CmsHomeImage::class);
        $response->assertSeeLivewire(\App\Livewire\CmsHomeContent::class);
    }

    public function test_deleting_homepage_is_prevented(): void
    {
        $this->seed();

        $admin = User::factory()->create(['role_id' => UserRole::Admin->value]);

        // Verify ID = 1 exists in DB
        $this->assertDatabaseHas('cms_pages', ['id' => 1]);

        // Attempt deletion of ID = 1 via AdminCmsPages Livewire component
        Livewire::actingAs($admin)
            ->test(\App\Livewire\AdminCmsPages::class)
            ->call('deletePage', 1)
            ->assertHasNoErrors();

        // Verify page ID = 1 is still in DB (not deleted)
        $this->assertDatabaseHas('cms_pages', ['id' => 1]);
    }

    public function test_custom_login_security_hashing_and_auth(): void
    {
        // 1. Activate custom hashing system by re-binding the hash manager
        putenv('custom_login_security=1');
        $this->app->singleton('hash', function ($app) {
            return new \App\Services\CustomHashManager($app);
        });

        // 2. Create user with raw password and verify tokens auto-population
        $user = User::create([
            'name' => 'Custom Secure User',
            'email' => 'customsecure@example.com',
            'password' => 'securePassword123!',
            'role_id' => UserRole::User->value,
        ]);

        $this->assertNotEmpty($user->user_token_1);
        $this->assertNotEmpty($user->user_token_2);

        // Verify stored password hash matches ripemd256 HMAC
        $expectedHash = hash_hmac('ripemd256', 'securePassword123!', $user->user_token_1);
        $this->assertEquals($expectedHash, $user->password);

        // 3. Test successful authentication
        \App\Services\CustomHashContext::$currentEmail = $user->email;
        $attempt = \Illuminate\Support\Facades\Auth::attempt([
            'email' => $user->email,
            'password' => 'securePassword123!'
        ]);
        \App\Services\CustomHashContext::$currentEmail = null;

        $this->assertTrue($attempt);

        // 4. Test failed authentication
        \App\Services\CustomHashContext::$currentEmail = $user->email;
        $attemptFail = \Illuminate\Support\Facades\Auth::attempt([
            'email' => $user->email,
            'password' => 'wrongPassword!'
        ]);
        \App\Services\CustomHashContext::$currentEmail = null;

        $this->assertFalse($attemptFail);

        // Cleanup env
        putenv('custom_login_security');
    }

    public function test_cms_page_rating_component(): void
    {
        $admin = User::factory()->create(['role_id' => UserRole::Admin->value]);
        
        $page = CmsPage::create([
            'title' => 'Page with Rating',
            'slug' => 'page-with-rating',
            'content' => '<p>Rate this page!</p>',
            'is_active' => true,
            'author_id' => $admin->id,
            'hide_page_ranking' => 0, // Visible
            'page_ranking' => 15,
        ]);

        // 1. Visit page and verify ratings are rendered on the frontend
        $response = $this->get('/page-with-rating');
        $response->assertStatus(200);
        $response->assertSeeLivewire(\App\Livewire\CmsPageRating::class);
        $response->assertSee('Was this page helpful?');

        // 2. Perform vote increment
        Livewire::test(\App\Livewire\CmsPageRating::class, ['pageId' => $page->id])
            ->call('ratePage', 1)
            ->assertSet('hasVoted', true);

        // Verify ranking incremented in DB
        $page->refresh();
        $this->assertEquals(16, $page->page_ranking);

        // 3. Verify duplicate voting is blocked
        Livewire::test(\App\Livewire\CmsPageRating::class, ['pageId' => $page->id])
            ->call('ratePage', 1);

        $page->refresh();
        $this->assertEquals(16, $page->page_ranking); // Remains 16
    }

    public function test_admin_cms_categories_and_tags_crud(): void
    {
        $this->seed();
        $admin = User::factory()->create(['role_id' => UserRole::Admin->value]);

        // 1. Visit Categories Index
        $response = $this->actingAs($admin)->get('/admin/cms-categories');
        $response->assertStatus(200);
        $response->assertSeeLivewire(\App\Livewire\AdminCmsCategories::class);

        // 2. Create Category via Livewire
        Livewire::actingAs($admin)
            ->test(\App\Livewire\AdminCmsCategories::class)
            ->set('name', 'New Category')
            ->set('slug', 'new-category')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('cms_pages_categories', ['slug' => 'new-category']);

        // 3. Test unique slug cross-table constraint (using 'home' which is page ID 1 slug)
        Livewire::actingAs($admin)
            ->test(\App\Livewire\AdminCmsCategories::class)
            ->set('name', 'Home Category')
            ->set('slug', 'home') // Duplicate slug!
            ->call('save')
            ->assertHasErrors(['slug']);

        // 4. Visit Tags Index
        $responseTags = $this->actingAs($admin)->get('/admin/cms-tags');
        $responseTags->assertStatus(200);
        $responseTags->assertSeeLivewire(\App\Livewire\AdminCmsTags::class);

        // 5. Create Tag via Livewire
        Livewire::actingAs($admin)
            ->test(\App\Livewire\AdminCmsTags::class)
            ->set('name', 'New Tag')
            ->set('slug', 'new-tag')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('cms_pages_tags', ['slug' => 'new-tag']);
    }

    public function test_category_landing_page_rendering(): void
    {
        $this->seed();

        // Visit Category landing page /category/blog
        $response = $this->get('/category/blog');
        $response->assertStatus(200);

        // Assert seeded post titles and authors are visible
        $response->assertSee('Announcing Our New Support &amp; Shop Platform', false);
        $response->assertSee('Getting Started with Livewire Components');
        $response->assertSee('Site Manager');
        $response->assertSee('#News');
    }

    public function test_tag_landing_page_filtering_and_rendering(): void
    {
        $this->seed();

        $admin = User::factory()->create(['role_id' => UserRole::Admin->value]);

        // Create a gated page and tag it
        $gatedPage = CmsPage::create([
            'title' => 'Gated Page with Tag',
            'slug' => 'gated-page-with-tag',
            'content' => 'Gated content!',
            'author_id' => $admin->id,
            'is_active' => true,
            'requires_code' => true,
            'access_code' => 'SECRET',
            'hide_page_ranking' => 1
        ]);

        $laravelTag = \App\Models\CmsPagesTag::where('slug', 'laravel')->first();
        $gatedPage->tags()->sync([$laravelTag->id]);

        // 1. Visit Tag page /tag/laravel
        $response = $this->get('/tag/laravel');
        $response->assertStatus(200);

        // Should see the normal seeded post that has laravel tag
        $response->assertSee('Announcing Our New Support &amp; Shop Platform', false);

        // Should NOT see the gated page in the tags index listing
        $response->assertDontSee('Gated Page with Tag');
    }

    public function test_cms_page_duplication(): void
    {
        $this->seed();
        $admin = User::factory()->create(['role_id' => UserRole::Admin->value]);

        // Duplicate page ID 2 (About Us)
        Livewire::actingAs($admin)
            ->test(\App\Livewire\AdminCmsPages::class)
            ->call('duplicatePage', 2)
            ->assertHasNoErrors();

        // Ensure a new page is created with a unique slug ending in 5 random chars
        $this->assertDatabaseHas('cms_pages', [
            'title' => 'About Us'
        ]);
        
        $pages = CmsPage::where('title', 'like', 'About Us %')->get();
        $this->assertGreaterThan(0, $pages->count());
        $this->assertNotEquals('about-us', $pages->first()->slug);
    }

    public function test_cms_customizations(): void
    {
        $admin = User::factory()->create(['role_id' => UserRole::Admin->value]);
        
        // 1. Create a page with customizations
        $page = CmsPage::create([
            'title' => 'Main Title',
            'slug' => 'customized-page',
            'content' => '<p>Welcome</p>',
            'is_active' => true,
            'author_id' => $admin->id,
            'alternate_page_title' => 'Custom Alternate Title',
            'page_title_alignment' => 'middle-left',
            'page_title_css' => '.page-title { color: rgb(255, 0, 0); }',
            'include_slideshow' => '<div class="custom-slideshow">Slideshow Content</div>',
            'min_header_height' => '400px',
            'header_image' => 'cms_headers/test-header.jpg',
            'show_title' => true,
        ]);

        // 2. View page as guest
        $response = $this->get('/customized-page');
        $response->assertStatus(200);
        
        // Assert title override is displayed
        $response->assertSee('Custom Alternate Title');
        $response->assertDontSee('Main Title');

        // Assert custom CSS is output
        $response->assertSee('.page-title { color: rgb(255, 0, 0); }');

        // Assert slideshow is output
        $response->assertSee('Slideshow Content');

        // Assert alignment classes are applied (middle-left maps to horizontal text-left, vertical items-center justify-start)
        $response->assertSee('text-left');

        // Assert dynamic min-height is output
        $response->assertSee('min-height: 400px');

        // 3. Test Livewire Admin Edit saves customizations
        Livewire::actingAs($admin)
            ->test(\App\Livewire\AdminCmsPageEdit::class, ['id' => $page->id])
            ->set('alternate_page_title', 'Updated Title')
            ->set('page_title_alignment', 'top-right')
            ->set('page_title_css', '.title { font-weight: bold; }')
            ->set('include_slideshow', 'New Slideshow')
            ->set('min_header_height', '450px')
            ->call('save')
            ->assertHasNoErrors();

        $page->refresh();
        $this->assertEquals('Updated Title', $page->alternate_page_title);
        $this->assertEquals('top-right', $page->page_title_alignment);
        $this->assertEquals('.title { font-weight: bold; }', $page->page_title_css);
        $this->assertEquals('New Slideshow', $page->include_slideshow);
        $this->assertEquals('450px', $page->min_header_height);
    }

    public function test_display_plugins_panel_rendered_in_cms_edit_page(): void
    {
        $admin = User::factory()->create(['role_id' => UserRole::Admin->value]);
        $page = CmsPage::create([
            'title' => 'Sample Page',
            'slug' => 'sample-page',
            'content' => '<p>Hello</p>',
            'is_active' => true,
            'author_id' => $admin->id,
        ]);

        // Create display plugin manually
        \App\Models\Plugin::create([
            'name' => 'Slideshow - Swiper Display (2026)',
            'shortcode' => 'slideshow-2026',
            'type' => 'display',
            'activation_status' => 1,
            'version' => '1.0',
            'author' => 'Built-in',
            'filename' => 'slideshow_2026',
            'install_type' => 1,
            'description' => 'Slideshow plugin',
        ]);

        // Ensure active display plugins are returned in the view
        Livewire::actingAs($admin)
            ->test(\App\Livewire\AdminCmsPageEdit::class, ['id' => $page->id])
            ->assertViewHas('displayPlugins', function ($displayPlugins) {
                // Ensure slideshow-2026 is present in the list
                $hasSlideshow = false;
                foreach ($displayPlugins as $plugin) {
                    if ($plugin->shortcode === 'slideshow-2026') {
                        $hasSlideshow = true;
                    }
                }
                return $hasSlideshow;
            })
            ->assertSee('Display Plugins')
            ->assertSee('[plugin:slideshow-2026]');
    }

    public function test_link_generator_drawer_functionality(): void
    {
        $admin = User::factory()->create(['role_id' => UserRole::Admin->value]);
        $page = CmsPage::create([
            'title' => 'Sample Page',
            'slug' => 'sample-page',
            'content' => '<p>Hello</p>',
            'is_active' => true,
            'author_id' => $admin->id,
        ]);

        // Create sample product, brand, category, page
        $product = Product::create([
            'title' => 'Anti Gravity Hoodie',
            'short_description' => 'desc',
            'long_description' => 'long desc',
            'seo_slug' => 'antigravity-hoodie',
        ]);
        
        $brand = \App\Models\Brand::create([
            'name' => 'Acme Clothing',
            'slug' => 'acme-clothing',
        ]);
        
        $category = \App\Models\Category::create([
            'name' => 'Men Outerwear',
            'slug' => 'men-outerwear',
        ]);

        $otherPage = CmsPage::create([
            'title' => 'Terms of Service',
            'slug' => 'terms-of-service',
            'content' => '<p>Terms</p>',
            'is_active' => true,
            'author_id' => $admin->id,
        ]);

        // Live search products
        Livewire::actingAs($admin)
            ->test(\App\Livewire\AdminCmsPageEdit::class, ['id' => $page->id])
            ->set('searchProduct', 'hoodie')
            ->assertViewHas('searchedProducts', function ($searchedProducts) use ($product) {
                return $searchedProducts->contains($product);
            })
            ->assertSee('Anti Gravity Hoodie')
            ->assertSee('/items/antigravity-hoodie');

        // Live search brands
        Livewire::actingAs($admin)
            ->test(\App\Livewire\AdminCmsPageEdit::class, ['id' => $page->id])
            ->set('searchBrand', 'Acme')
            ->assertViewHas('searchedBrands', function ($searchedBrands) use ($brand) {
                return $searchedBrands->contains($brand);
            })
            ->assertSee('Acme Clothing')
            ->assertSee('/brands/acme-clothing');

        // Live search categories
        Livewire::actingAs($admin)
            ->test(\App\Livewire\AdminCmsPageEdit::class, ['id' => $page->id])
            ->set('searchCategory', 'Outerwear')
            ->assertViewHas('searchedCategories', function ($searchedCategories) use ($category) {
                return $searchedCategories->contains($category);
            })
            ->assertSee('Men Outerwear')
            ->assertSee('/section/men-outerwear');

        // Live search pages
        Livewire::actingAs($admin)
            ->test(\App\Livewire\AdminCmsPageEdit::class, ['id' => $page->id])
            ->set('searchPage', 'Terms')
            ->assertViewHas('searchedPages', function ($searchedPages) use ($otherPage) {
                return $searchedPages->contains($otherPage);
            })
            ->assertSee('Terms of Service')
            ->assertSee('/terms-of-service');
    }
}

