<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Category;
use App\Models\CmsPage;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SearchIndexAndCategoryBrandLiveSearchTest extends TestCase
{
    use RefreshDatabase;

    public function test_cms_page_auto_collates_search_index_on_save(): void
    {
        $page = CmsPage::create([
            'title' => 'Warranty Policy',
            'slug' => 'warranty-policy',
            'content' => '<p>Coverage applies to manufacturing defects within 24 months.</p>',
            'meta_description' => 'Official lifetime warranty terms and registration guidelines.',
            'is_active' => true,
            'layout_type' => 1,
        ]);

        $this->assertNotNull($page->cms_search_index);
        $this->assertStringContainsString('Warranty Policy', $page->cms_search_index);
        $this->assertStringContainsString('manufacturing defects', $page->cms_search_index);
        $this->assertStringContainsString('lifetime warranty terms', $page->cms_search_index);
    }

    public function test_search_index_strips_plugin_shortcodes_and_embeds(): void
    {
        $page = CmsPage::create([
            'title' => 'Home Page Showcase',
            'slug' => 'home-showcase',
            'content' => '<h1>Welcome to Our Store</h1> [plugin:brands-2026 mode="slider"] <p>Check out our products.</p> [code-embed:42] [plugin:live-search-2026]',
            'is_active' => true,
            'layout_type' => 1,
        ]);

        $this->assertStringNotContainsString('code-embed', $page->cms_search_index);
        $this->assertStringNotContainsString('plugin:brands-2026', $page->cms_search_index);
        $this->assertStringNotContainsString('plugin:live-search-2026', $page->cms_search_index);
        $this->assertStringContainsString('Welcome to Our Store Check out our products.', $page->cms_search_index);
    }

    public function test_cms_page_locked_search_index_prevents_auto_update_on_save(): void
    {
        $page = CmsPage::create([
            'title' => 'Original Title',
            'slug' => 'original-title',
            'content' => 'Original Content',
            'cms_search_index' => 'CUSTOM_LOCKED_KEYWORD_PROMO_2026',
            'cms_search_index_locked' => true,
            'is_active' => true,
            'layout_type' => 1,
        ]);

        $this->assertEquals('CUSTOM_LOCKED_KEYWORD_PROMO_2026', $page->cms_search_index);

        $page->update([
            'title' => 'Completely New Title',
            'content' => 'Completely New Content Body',
        ]);

        $this->assertEquals('CUSTOM_LOCKED_KEYWORD_PROMO_2026', $page->fresh()->cms_search_index);
    }

    public function test_product_auto_collates_search_index_and_download_event_keywords(): void
    {
        $product = Product::create([
            'title' => 'Super Digital Masterclass Book',
            'seo_slug' => 'super-digital-masterclass-book',
            'short_description' => 'Downloadable PDF guide with bonus video.',
            'long_description' => 'Complete digital download course.',
            'download_item' => 1,
            'meta_title' => 'Super Digital Masterclass Book',
        ]);

        $this->assertNotNull($product->product_search_index);
        $this->assertStringContainsString('Super Digital Masterclass Book', $product->product_search_index);
        $this->assertStringContainsString('download downloads digital', $product->product_search_index);
    }

    public function test_product_locked_search_index_prevents_overwrite(): void
    {
        $product = Product::create([
            'title' => 'Old Product Title',
            'short_description' => 'Old short desc',
            'product_search_index' => 'SPECIAL_DISCOUNT_SERIAL_KEY_8899',
            'product_search_index_locked' => true,
            'meta_title' => 'Old Product Title',
        ]);

        $this->assertEquals('SPECIAL_DISCOUNT_SERIAL_KEY_8899', $product->product_search_index);

        $product->update([
            'title' => 'Updated Product Name',
        ]);

        $this->assertEquals('SPECIAL_DISCOUNT_SERIAL_KEY_8899', $product->fresh()->product_search_index);
    }

    public function test_live_search_api_returns_categories_and_brands_with_pill_labels_and_seo_slugs(): void
    {
        $category = Category::create([
            'name' => 'Audio Engineering Equipment',
            'slug' => 'audio-engineering-equipment',
            'description' => 'Studio monitors, headphones, and microphonic instruments.',
            'is_visible_in_menu' => true,
        ]);

        $brand = Brand::create([
            'name' => 'Acoustic Audio Dynamics',
            'slug' => 'acoustic-audio-dynamics',
            'description' => 'Premium high-fidelity audio monitors and amplifiers.',
            'is_visible_in_menu' => true,
        ]);

        $response = $this->getJson('/api/live-search-api?q=Audio');

        $response->assertStatus(200);
        $json = $response->json();

        $catResult = collect($json)->firstWhere('type', 'category');
        $this->assertNotNull($catResult, 'Category result should be returned in live search.');
        $this->assertEquals('Category', $catResult['type_label']);
        $this->assertEquals('Audio Engineering Equipment', $catResult['title']);
        $this->assertEquals(route('shop.category', 'audio-engineering-equipment'), $catResult['url']);

        $brandResult = collect($json)->firstWhere('type', 'brand');
        $this->assertNotNull($brandResult, 'Brand result should be returned in live search.');
        $this->assertEquals('Brand', $brandResult['type_label']);
        $this->assertEquals('Acoustic Audio Dynamics', $brandResult['title']);
        $this->assertEquals(route('shop.brand', 'acoustic-audio-dynamics'), $brandResult['url']);
    }

    public function test_live_search_api_finds_download_products_by_keyword(): void
    {
        $product = Product::create([
            'title' => 'E-Book Architectural Drafting Standard',
            'seo_slug' => 'ebook-architectural-drafting-standard',
            'short_description' => 'Official guide for blueprints.',
            'long_description' => 'Digital file for CAD professionals.',
            'download_item' => 1,
            'meta_title' => 'E-Book Architectural Drafting Standard',
        ]);

        $response = $this->getJson('/api/live-search-api?q=download');

        $response->assertStatus(200);
        $json = $response->json();

        $prodResult = collect($json)->firstWhere('id', $product->id);
        $this->assertNotNull($prodResult, 'Download product should be matched when searching for "download".');
        $this->assertEquals('Product', $prodResult['type_label']);
    }

    public function test_shipping_settings_lists_installed_shipping_plugins_and_filters_plugin_manager_by_type(): void
    {
        $admin = User::factory()->create(['role_id' => 3]);

        $plugin = \App\Models\Plugin::create([
            'name' => 'Express Courier Shipping API 2026',
            'type' => 'shipping',
            'filename' => 'express_courier_2026',
            'author' => 'Built-in',
            'version' => '1.0',
            'install_type' => 1,
            'activation_status' => 1,
        ]);

        $response = $this->actingAs($admin)->get('/admin/ecommerce/shipping');
        $response->assertStatus(200);
        $response->assertSee('Express Courier Shipping API 2026');
        $response->assertSee('/admin/plugins?type=shipping');

        $pluginResponse = $this->actingAs($admin)->get('/admin/plugins?type=shipping');
        $pluginResponse->assertStatus(200);
        $pluginResponse->assertSee('Express Courier Shipping API 2026');
    }

    public function test_direct_download_url_overrides_uploaded_file_and_forces_download(): void
    {
        \Illuminate\Support\Facades\Http::fake([
            'https://example.com/files/cad-schema-2026.pdf' => \Illuminate\Support\Facades\Http::response('PDF-CONTENT-BINARY-DATA', 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="cad-schema-2026.pdf"'
            ]),
        ]);

        $product = Product::create([
            'title' => 'CAD Blueprint Package',
            'seo_slug' => 'cad-blueprint-package',
        ]);

        $variant = \App\Models\ProductVariant::create([
            'product_id' => $product->id,
            'sku' => 'CAD-BLU-001',
            'public_price' => 49.99,
            'wholesale_price' => 29.99,
            'download_item' => 1,
            'download_location' => 'local_fallback.pdf',
            'direct_download_url' => 'https://example.com/files/cad-schema-2026.pdf',
        ]);

        $order = \App\Models\Order::create([
            'order_user_id' => 1,
            'order_invoice_no' => 'INV-998877',
            'order_external_id' => 'EXT-ORD-998877',
            'order_subtotal' => 49.99,
            'order_taxes' => 0,
            'order_discounts' => 0,
            'order_shipping' => 0,
            'order_total' => 49.99,
            'order_date' => now()->format('Y-m-d H:i:s'),
            'order_status' => 7, // Completed
        ]);

        $orderDetail = \App\Models\OrderDetail::create([
            'order_id' => $order->id,
            'inventory_id' => $variant->id,
            'item_name' => 'CAD Blueprint Package',
            'item_qty' => 1,
            'base_price' => 49.99,
            'discount_price' => 0,
            'options_fee' => 0,
            'final_price' => 49.99,
            'download_item' => 1,
            'downloads_counter' => 0,
            'downloads_max_allowed' => 10,
        ]);

        $response = $this->get('/downloads/' . $orderDetail->id . '/EXT-ORD-998877');

        $response->assertStatus(200);
        $this->assertEquals(1, $orderDetail->fresh()->downloads_counter);
        $this->assertStringContainsString('PDF-CONTENT-BINARY-DATA', $response->streamedContent());
    }

    public function test_product_editor_preserves_prose_div_wrapper(): void
    {
        $admin = User::factory()->create(['role_id' => 3]);

        $proseHtml = '<div class="prose prose-slate max-w-none" style="max-width: none !important; width: 100%;"><p>Detailed architectural specification list.</p></div>';

        $product = Product::create([
            'title' => 'Structural Engineering Specs',
            'seo_slug' => 'structural-engineering-specs',
            'long_description' => $proseHtml,
        ]);

        $response = $this->actingAs($admin)->get('/admin/ecommerce/products/' . $product->id . '/edit');

        $response->assertStatus(200);
        $response->assertSee('prose prose-slate max-w-none', false);
    }

    public function test_add_to_cart_error_message_is_rendered_inline_next_to_buy_box_button(): void
    {
        $product = Product::create([
            'title' => 'Limited Edition Watch',
            'seo_slug' => 'limited-edition-watch',
        ]);

        $variant = \App\Models\ProductVariant::create([
            'product_id' => $product->id,
            'sku' => 'WATCH-LIM-01',
            'public_price' => 199.99,
            'wholesale_price' => 149.99,
        ]);

        \App\Models\ProductInventory::create([
            'variant_id' => $variant->id,
            'quantity_available' => 2,
            'reserved_stock' => 0,
        ]);

        \Livewire\Livewire::test(\App\Livewire\ProductDetails::class, ['seo_link' => 'limited-edition-watch'])
            ->set('selectedVariantId', $variant->id)
            ->set('quantity', 10)
            ->call('addToCart')
            ->assertSee('Could not add item to cart:')
            ->assertSee('Only 2 units available in stock.');
    }

    public function test_active_filters_box_hidden_when_no_filters_applied_and_reset_all_filters_clears_everything(): void
    {
        $cat = Category::create(['name' => 'Audio Gear', 'slug' => 'audio-gear']);
        $brand = Brand::create(['name' => 'Sony', 'slug' => 'sony']);

        // 1. Unfiltered catalog should NOT show "Active Filters:"
        \Livewire\Livewire::test(\App\Livewire\ShopCatalog::class)
            ->assertDontSee('Active Filters:')
            ->assertSet('hasActiveFilters', false);

        // 2. Active filters applied -> "Active Filters:" IS shown
        \Livewire\Livewire::test(\App\Livewire\ShopCatalog::class, ['category_slug' => 'audio-gear', 'brand_slug' => 'sony'])
            ->assertSee('Active Filters:')
            ->assertSet('hasActiveFilters', true)
            ->call('resetAllAdvancedFilters')
            ->assertSet('hasActiveFilters', false)
            ->assertSet('category', null)
            ->assertSet('brand', null)
            ->assertSet('search', '')
            ->assertSet('selectedBrands', [])
            ->assertSet('selectedCategories', [])
            ->assertSet('selectedAttributes', []);
    }

    public function test_admin_settings_custom_go_to_top_button_colors(): void
    {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin_settings@example.com',
            'password' => bcrypt('password'),
            'role_id' => 3,
        ]);

        \Livewire\Livewire::actingAs($admin)
            ->test(\App\Livewire\AdminSettings::class)
            ->set('backtop_bg_color', '#ff5500')
            ->set('backtop_hover_bg_color', '#cc2200')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertEquals('#ff5500', \App\Models\CmsSetting::get('backtop_bg_color'));
        $this->assertEquals('#cc2200', \App\Models\CmsSetting::get('backtop_hover_bg_color'));

        $compiledCss = \App\Services\HeaderFooterCssManager::compileCss();
        $this->assertStringContainsString('#f50', $compiledCss);
        $this->assertStringContainsString('#c20', $compiledCss);
    }

    public function test_shop_catalog_error_renders_in_floating_modal(): void
    {
        $product = Product::create([
            'title' => 'Out of Stock Gadget',
            'seo_slug' => 'out-of-stock-gadget',
            'standalone_purchase' => 0,
        ]);
        $variant = \App\Models\ProductVariant::create([
            'product_id' => $product->id,
            'sku' => 'OOS-GADGET-123',
            'download_item' => 0,
            'public_price' => 19.99,
            'wholesale_price' => 15.00,
        ]);
        \App\Models\ProductInventory::create([
            'variant_id' => $variant->id,
            'quantity_available' => 0,
            'location_id' => 1,
        ]);

        \Livewire\Livewire::test(\App\Livewire\ShopCatalog::class)
            ->call('buyNow', $variant->id)
            ->assertSet('catalogError', 'Item is out of stock.')
            ->assertDispatched('show-catalog-error')
            ->assertSee('showErrorModal')
            ->assertSee('Item is out of stock.');
    }

    public function test_product_edit_personalization_options_saving_and_rendering(): void
    {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin_pers@example.com',
            'password' => bcrypt('password'),
            'role_id' => 3,
        ]);
        $product = Product::create([
            'title' => 'Custom Engraved Watch',
            'seo_slug' => 'custom-engraved-watch',
        ]);
        $variant = \App\Models\ProductVariant::create([
            'product_id' => $product->id,
            'sku' => 'ENGRAVE-WATCH-1',
            'personalization_active' => 1,
            'personalization_fee' => 7.50,
            'personalization_label' => 'Custom Engraving Text',
            'personalization_details_label' => 'Engraving Placement & Font',
            'personalization_placeholder' => 'Enter text to be engraved on the back',
            'public_price' => 120.00,
            'wholesale_price' => 90.00,
        ]);

        \Livewire\Livewire::actingAs($admin)
            ->test(\App\Livewire\AdminProductEdit::class, ['id' => $product->id])
            ->assertSee('Customization &amp; Personalization', false)
            ->assertSee('Custom Engraving Text')
            ->assertSee('+$7.50')
            ->call('startEditVariant', $variant->id)
            ->assertSet('personalization_active', 1)
            ->assertSet('personalization_fee', 7.50)
            ->assertSet('personalization_label', 'Custom Engraving Text')
            ->set('personalization_fee', 10.00)
            ->set('personalization_label', 'Updated Engraving Prompt')
            ->call('updateVariant')
            ->assertHasNoErrors();

        $variant->refresh();
        $this->assertEquals(10.00, $variant->personalization_fee);
        $this->assertEquals('Updated Engraving Prompt', $variant->personalization_label);
    }

    public function test_admin_products_copy_feature_duplicates_product_variants_and_inventory(): void
    {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin_copy@example.com',
            'password' => bcrypt('password'),
            'role_id' => 3,
        ]);
        $product = Product::create([
            'title' => 'Original Smart Lamp',
            'seo_slug' => 'original-smart-lamp',
        ]);
        $variant = \App\Models\ProductVariant::create([
            'product_id' => $product->id,
            'sku' => 'LAMP-ORIG-001',
            'public_price' => 49.99,
            'wholesale_price' => 35.00,
        ]);
        \App\Models\ProductInventory::create([
            'variant_id' => $variant->id,
            'quantity_available' => 25,
            'location_id' => 1,
        ]);

        $test = \Livewire\Livewire::actingAs($admin)
            ->test(\App\Livewire\AdminProducts::class)
            ->call('openCopyModal', $product->id)
            ->assertSet('showCopyModal', true)
            ->assertSet('copyOriginalTitle', 'Original Smart Lamp')
            ->set('copyProductTitle', 'Original Smart Lamp - Custom Copy')
            ->set('copyProductSlug', 'original-smart-lamp-custom-copy')
            ->set('copyVariantsAndImages', true)
            ->call('duplicateProduct')
            ->assertHasNoErrors()
            ->assertSet('showCopyModal', false);

        $duplicatedProduct = Product::where('seo_slug', 'original-smart-lamp-custom-copy')->first();
        $this->assertNotNull($duplicatedProduct);
        $this->assertEquals('Original Smart Lamp - Custom Copy', $duplicatedProduct->title);

        $duplicatedVariants = \App\Models\ProductVariant::where('product_id', $duplicatedProduct->id)->get();
        $this->assertCount(1, $duplicatedVariants);

        $dupVar = $duplicatedVariants->first();
        $this->assertNotEquals('LAMP-ORIG-001', $dupVar->sku);
        $this->assertStringContainsString('LAMP-ORIG-001-COPY-', $dupVar->sku);
        $this->assertEquals(49.99, $dupVar->public_price);

        $dupInventory = \App\Models\ProductInventory::where('variant_id', $dupVar->id)->first();
        $this->assertNotNull($dupInventory);
        $this->assertEquals(25, $dupInventory->quantity_available);
    }

    public function test_subcategory_active_filter_pill_renders_in_shop_catalog(): void
    {
        $parentCat = Category::create([
            'name' => 'Apparel Root',
            'slug' => 'apparel-root',
            'is_visible_in_menu' => true,
        ]);
        $subCat = Category::create([
            'name' => 'Winter Jackets Subcategory',
            'slug' => 'winter-jackets-subcategory',
            'parent_id' => $parentCat->id,
            'is_visible_in_menu' => true,
        ]);

        \Livewire\Livewire::test(\App\Livewire\ShopCatalog::class)
            ->set('selectedCategories', [(string)$subCat->id])
            ->assertSee('Active Filters:')
            ->assertSee('Category: Winter Jackets Subcategory')
            ->call('removeSelectedCategory', $subCat->id)
            ->assertSet('selectedCategories', []);
    }

    public function test_dynamic_personalization_label_renders_on_product_details_page(): void
    {
        $product = Product::create([
            'title' => 'Laser Engraved Mug',
            'seo_slug' => 'laser-engraved-mug',
        ]);
        $variant = \App\Models\ProductVariant::create([
            'product_id' => $product->id,
            'sku' => 'LASER-MUG-1',
            'personalization_active' => 1,
            'personalization_fee' => 5.00,
            'personalization_label' => 'Custom Monogram & Logo Engraving',
            'personalization_details_label' => 'Monogram Initials',
            'personalization_placeholder' => 'Enter up to 3 initials',
            'public_price' => 25.00,
            'wholesale_price' => 18.00,
        ]);
        \App\Models\ProductInventory::create([
            'variant_id' => $variant->id,
            'quantity_available' => 10,
            'location_id' => 1,
        ]);

        \Livewire\Livewire::test(\App\Livewire\ProductDetails::class, ['seo_link' => $product->seo_slug])
            ->assertSee('Personalization Options')
            ->assertSee('Custom Monogram &amp; Logo Engraving', false)
            ->set('personalization_selected', true)
            ->set('personalization_text', 'ABC')
            ->assertSee('Monogram Initials')
            ->assertSee('Enter up to 3 initials')
            ->call('addToCart');

        $cartItem = \App\Models\ShoppingCartLog::where('variant_id', $variant->id)->first();
        $this->assertNotNull($cartItem);
        $attrs = json_decode($cartItem->item_attributes, true);
        $this->assertArrayHasKey('customizations', $attrs);
        $this->assertEquals('Custom Monogram & Logo Engraving', $attrs['customizations'][0]['label']);
        $this->assertEquals('ABC', $attrs['customizations'][0]['value']);
    }

    public function test_dynamic_download_label_renders_on_product_details_page(): void
    {
        $product = Product::create([
            'title' => 'Software License Key',
            'seo_slug' => 'software-license-key',
        ]);
        $variant = \App\Models\ProductVariant::create([
            'product_id' => $product->id,
            'sku' => 'SOFT-KEY-01',
            'download_item' => 1,
            'download_label' => 'Software Key & Download Installer',
            'direct_download_url' => 'https://example.com/software-installer.exe',
            'public_price' => 99.00,
            'wholesale_price' => 75.00,
        ]);

        \Livewire\Livewire::test(\App\Livewire\ProductDetails::class, ['seo_link' => $product->seo_slug])
            ->assertSee('Software Key & Download Installer');
    }

    public function test_events_calendar_plugin_shortcode_renders_event_products(): void
    {
        \App\Models\Plugin::updateOrCreate(
            ['filename' => 'events_calendar_2026'],
            [
                'name'                => 'Events Calendar Display (2026)',
                'shortcode'           => 'events-calendar-2026',
                'type'                => 'display',
                'author'              => 'Built-in',
                'version'             => '1.0',
                'install_type'        => 1,
                'activation_required' => 'no',
                'activation_status'   => 1,
            ]
        );

        $product = Product::create([
            'title' => '2026 Tech Summit Workshop Ticket',
            'seo_slug' => '2026-tech-summit-workshop-ticket',
            'short_description' => 'Join us for an exclusive 2026 tech summit workshop.',
        ]);
        $variant = \App\Models\ProductVariant::create([
            'product_id' => $product->id,
            'sku' => 'EVENT-TECH-01',
            'public_price' => 149.00,
            'wholesale_price' => 129.00,
        ]);
        \App\Models\ProductVariantEvent::create([
            'variant_id' => $variant->id,
            'event_start_date' => now()->addDays(5),
            'event_end_date' => now()->addDays(5)->addHours(4),
            'event_label' => 'VIP Workshop Ticket',
            'label_background' => '#6366f1',
            'event_location' => 'Metropolitan Convention Center',
            'event_description' => 'Exclusive hands-on workshop covering advanced web architecture.',
        ]);

        $html = \App\Services\ContentParserService::parse('[plugin:events-calendar-2026 header="Spring Tech Workshops"]');

        $this->assertStringContainsString('Spring Tech Workshops', $html);
        $this->assertStringContainsString('2026 Tech Summit Workshop Ticket', $html);
        $this->assertStringContainsString('VIP Workshop Ticket', $html);
        $this->assertStringContainsString('Metropolitan Convention Center', $html);
        $this->assertStringContainsString('$149.00', $html);
    }
}
