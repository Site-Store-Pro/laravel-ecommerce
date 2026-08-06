<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Add fields to existing users table
        Schema::table('users', function (Blueprint $table) {
            $table->text('company')->nullable()->after('role_id');
            $table->text('shipping_address1')->nullable()->after('company');
            $table->text('shipping_address2')->nullable()->after('shipping_address1');
            $table->text('shipping_city')->nullable()->after('shipping_address2');
            $table->text('shopping_postalcode')->nullable()->after('shipping_city');
            $table->text('shipping_country')->nullable()->after('shopping_postalcode');
            $table->text('shipping_countrycode')->nullable()->after('shipping_country');
            $table->integer('rewards_status')->default(0)->after('shipping_countrycode');
            $table->dateTime('new_user_discount')->nullable()->after('rewards_status');
            $table->integer('active')->default(1)->after('new_user_discount');
            $table->string('user_token_1', 255)->nullable()->after('active');
            $table->string('user_token_2', 255)->nullable()->after('user_token_1');
            $table->string('shipping_state', 255)->nullable()->after('user_token_2');
        });

        // 2. Add performance indexes to existing tickets table
        Schema::table('tickets', function (Blueprint $table) {
            $table->index('status');
            $table->index('assigned_to');
        });

        // 3. Create product_brands table
        Schema::create('product_brands', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->integer('sort_order')->default(0);
            $table->string('brand_icon')->nullable();
            $table->integer('brand_logo_s3')->default(0);
            $table->string('brand_url')->nullable();
            $table->timestamps();
        });

        // 4. Create products table
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('brand_id')->nullable()->constrained('product_brands')->nullOnDelete();
            $table->text('title');
            $table->text('short_description')->nullable();
            $table->longText('long_description')->nullable();
            $table->text('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('seo_slug', 255)->nullable()->index();
            $table->integer('download_item')->default(0);
            $table->integer('shipping')->default(1);
            $table->tinyInteger('max_qty')->default(0);
            $table->tinyInteger('checkout_redirect')->default(0);
            $table->tinyInteger('standalone_purchase')->default(0);
            $table->text('advanced_options')->nullable();
            $table->integer('dependent_variants')->default(0);
            $table->integer('hide_inventory_levels')->default(0);
            $table->timestamps();
        });

        // 5. Create product_variants table
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('sku')->nullable()->unique();
            $table->decimal('public_price', 10, 2);
            $table->decimal('wholesale_price', 10, 2);
            $table->integer('on_sale')->default(0);
            $table->decimal('sale_price', 10, 2)->nullable();
            $table->decimal('variant_fee', 10, 2)->default(0.00);
            $table->decimal('wholesale_variant_fee', 10, 2)->default(0.00);
            $table->integer('personalization_active')->default(0);
            $table->decimal('personalization_fee', 10, 2)->default(0.00);
            $table->integer('shipping')->default(0);
            $table->double('weight')->nullable();
            $table->text('weight_type')->nullable();
            $table->text('attributes')->nullable();
            $table->integer('download_item')->default(0);
            $table->text('download_location')->nullable();
            $table->dateTime('download_expiration')->nullable();
            $table->integer('downloads_max_allowed')->default(100)->nullable();
            $table->integer('download_s3')->default(0);
            $table->text('download_s3_region')->nullable();
            $table->text('download_s3_bucket_name')->nullable();
            $table->text('download_s3_access_key_id')->nullable();
            $table->text('download_s3_secret_access_key')->nullable();
            $table->integer('subscription')->default(0);
            $table->integer('video_item')->default(0);
            $table->text('video_preview')->nullable();
            $table->text('video_purchase')->nullable();
            $table->text('download_cdn_url')->nullable();
            $table->timestamps();
        });

        // 6. Create products_inventory table
        Schema::create('products_inventory', function (Blueprint $table) {
            $table->id();
            $table->foreignId('variant_id')->references('id')->on('product_variants')->cascadeOnDelete();
            $table->integer('quantity_available')->default(0);
            $table->integer('warehouse_stock_level')->default(0);
            $table->boolean('use_warehouse_stock')->default(false);
            $table->integer('reserved_stock')->default(0);
            $table->integer('location_id')->default(0);
            $table->timestamps();
        });

        // 7. Create product_images table
        Schema::create('product_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('variant_id')->constrained('product_variants')->cascadeOnDelete();
            $table->text('image_url');
            $table->tinyInteger('image_s3')->default(0);
            $table->text('cdn_url')->nullable();
            $table->integer('sort_order')->default(0);
            $table->tinyInteger('image_url_source')->default(0);
            $table->string('alt_label', 255)->nullable();
            $table->string('zoom_label', 255)->nullable();
            $table->timestamps();
        });

        // 8. Create product_fields table
        Schema::create('product_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('label');
            $table->string('field_type'); // text, textarea, select, radio, checkbox, multiselect_checkbox
            $table->boolean('is_required')->default(false);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // 9. Create product_field_options table
        Schema::create('product_field_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_field_id')->references('id')->on('product_fields')->cascadeOnDelete();
            $table->string('option_value');
            $table->decimal('option_price_modifier', 10, 2)->default(0.00);
            $table->decimal('option_wholesale_price_modifier', 10, 2)->default(0.00);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // 10. Create orders table
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_invoice_no', 255)->unique();
            $table->text('order_external_id')->nullable();
            $table->integer('order_user_id')->default(0);
            $table->integer('order_status')->default(0);
            $table->dateTime('order_date');
            $table->decimal('order_total', 10, 2);
            $table->decimal('order_subtotal', 10, 2);
            $table->decimal('order_taxes', 10, 2);
            $table->decimal('order_discounts', 10, 2);
            $table->integer('order_shipping')->default(0);
            $table->dateTime('order_shipping_date')->nullable();
            $table->integer('order_shipping_method')->default(0);
            $table->text('order_shipping_tracking')->nullable();
            $table->integer('order_download')->default(0);
            $table->decimal('order_handling', 10, 2)->default(0.00);
            $table->text('order_comments')->nullable();
            $table->string('order_shipping_method_name', 255)->nullable();
            $table->timestamps();
        });

        // 11. Create order_details table
        Schema::create('order_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->text('item_name');
            $table->decimal('item_qty', 10, 3);
            $table->decimal('final_price', 10, 2);
            $table->decimal('base_price', 10, 2);
            $table->decimal('discount_price', 10, 2);
            $table->decimal('options_fee', 10, 2);
            $table->longText('options_list')->nullable();
            $table->integer('inventory_id')->default(0);
            $table->integer('download_item')->default(0);
            $table->dateTime('download_expiration')->nullable();
            $table->integer('downloads_counter')->nullable();
            $table->integer('downloads_max_allowed')->nullable();
            $table->timestamps();
        });

        // 12. Create order_payments table
        Schema::create('order_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->dateTime('payment_date');
            $table->decimal('payment_amount', 10, 2);
            $table->text('payment_method');
            $table->integer('payment_status')->default(0);
            $table->text('authorization_code')->nullable();
            $table->text('processor_response')->nullable();
            $table->timestamps();
        });

        // 13. Create order_refunds table
        Schema::create('order_refunds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->dateTime('refund_date');
            $table->decimal('amount', 10, 2)->default(0.00);
            $table->timestamps();
        });

        // 14. Create order_downloads table
        Schema::create('order_downloads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_details_id')->constrained()->cascadeOnDelete();
            $table->integer('user_id')->default(0);
            $table->dateTime('download_date');
            $table->timestamps();
        });

        // 15. Create shopping_cart_log table
        Schema::create('shopping_cart_log', function (Blueprint $table) {
            $table->id();
            $table->text('cart_log_session');
            $table->text('item_name');
            $table->decimal('item_qty', 10, 3);
            $table->decimal('item_price', 10, 2);
            $table->decimal('item_discount_price', 10, 2);
            $table->longText('item_attributes')->nullable();
            $table->integer('item_shippable')->default(0);
            $table->decimal('item_weight', 10, 3);
            $table->integer('item_taxable')->default(0);
            $table->integer('item_downloadable')->default(0);
            $table->integer('order_id')->default(0);
            $table->integer('user_id')->default(0);
            $table->timestamps();
        });

        Schema::table('shopping_cart_log', function (Blueprint $table) {
            $table->index('cart_log_session');
        });

        // 16. Create order_checkout_options table
        Schema::create('order_checkout_options', function (Blueprint $table) {
            $table->id();
            $table->integer('primary_processor')->default(0);
            $table->integer('secondary_processor')->default(0);
            $table->integer('tertiary_processor')->default(0);
            $table->tinyInteger('randomize_processor')->default(0);
            $table->timestamps();
        });

        // 17. Create order_processors table
        Schema::create('order_processors', function (Blueprint $table) {
            $table->id();
            $table->integer('processor_id');
            $table->text('processor_name');
            $table->tinyInteger('production')->default(0); // 0 = sandbox, 1 = live
            $table->timestamps();
        });

        // 18. Create order_status_list table
        Schema::create('order_status_list', function (Blueprint $table) {
            $table->id();
            $table->integer('orderstatuscode')->nullable();
            $table->string('orderstatus', 200)->nullable();
            $table->float('sortorder')->nullable();
            $table->longText('customerdisplay')->nullable();
            $table->integer('Active')->default(0);
            $table->string('AdminDisplay', 255)->nullable();
            $table->timestamps();
        });

        // Seed order status list values
        DB::table('order_status_list')->insert([
            ['id' => 1, 'orderstatuscode' => 1, 'orderstatus' => 'Open/Pending Order', 'sortorder' => 0.05, 'customerdisplay' => 'Payment Received - Order Being Processed.', 'Active' => 1, 'AdminDisplay' => 'Open (PENDING)', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'orderstatuscode' => 2, 'orderstatus' => 'Shipped - Email Automatically Sent', 'sortorder' => 2.0, 'customerdisplay' => 'Your Order Has Shipped.', 'Active' => 1, 'AdminDisplay' => 'Shipped', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'orderstatuscode' => 3, 'orderstatus' => 'Refunded - No Email Sent', 'sortorder' => 7.0, 'customerdisplay' => 'Refunded', 'Active' => 1, 'AdminDisplay' => 'Refunded (Closed)', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'orderstatuscode' => 4, 'orderstatus' => 'Canceled - No Email Sent', 'sortorder' => 8.0, 'customerdisplay' => 'Canceled', 'Active' => 1, 'AdminDisplay' => 'Canceled (Closed)', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 5, 'orderstatuscode' => 5, 'orderstatus' => 'Partially Shipped -  Email Automatically Sent', 'sortorder' => 3.0, 'customerdisplay' => 'Partially Shipped (See Notes Below).', 'Active' => 1, 'AdminDisplay' => 'Partially Shipped (PENDING)', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 6, 'orderstatuscode' => 6, 'orderstatus' => 'Back Ordered - No Email Sent', 'sortorder' => 5.0, 'customerdisplay' => 'Your Order Is Back-Ordered (See Notes Below.)', 'Active' => 1, 'AdminDisplay' => 'Back-Ordered (PENDING)', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 7, 'orderstatuscode' => 7, 'orderstatus' => 'Completed (CLOSED) - No Email Sent', 'sortorder' => 1.0, 'customerdisplay' => 'Completed', 'Active' => 1, 'AdminDisplay' => 'Completed | Closed', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 8, 'orderstatuscode' => 8, 'orderstatus' => 'Partially Refunded- No Email Sent', 'sortorder' => 6.0, 'customerdisplay' => 'Your order was partially refunded.', 'Active' => 1, 'AdminDisplay' => 'Partially Refunded (Closed)', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 9, 'orderstatuscode' => 0, 'orderstatus' => 'Order Failed Or Waiting For Payment', 'sortorder' => 9.0, 'customerdisplay' => 'Awaiting Payment Authorization', 'Active' => 1, 'AdminDisplay' => 'Awaiting Payment Authorization or Payment Failure', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 10, 'orderstatuscode' => 10, 'orderstatus' => 'Awaiting Payment By Check or PO', 'sortorder' => 10.0, 'customerdisplay' => 'Awaiting Payment By Check or PO', 'Active' => 1, 'AdminDisplay' => 'Awaiting Payment By Check or PO', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // 19. Create product_categories table
        Schema::create('product_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->foreignId('parent_id')->nullable()->constrained('product_categories')->cascadeOnDelete();
            $table->integer('sort_order')->default(0)->index();
            $table->boolean('is_visible_in_menu')->default(true)->index();
            $table->timestamps();
        });

        // 20. Create product_categories_assignments table
        Schema::create('product_categories_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('category_id')->constrained('product_categories')->cascadeOnDelete();
            $table->unique(['product_id', 'category_id']);
            $table->index(['category_id', 'product_id'], 'cat_prod_composite_idx');
        });

        // 21. Create cms_page_types table
        Schema::create('cms_page_types', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary();
            $table->string('title');
            $table->timestamps();
        });

        // Seed page types
        DB::table('cms_page_types')->insert([
            ['id' => 1, 'title' => 'Page', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'title' => 'Post', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // 22. Create cms_layouts table
        Schema::create('cms_layouts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->timestamps();
        });

        // Seed layouts
        DB::table('cms_layouts')->insert([
            ['id' => 1, 'name' => 'Single Column (Full Width)', 'code' => 'full-width', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'name' => 'Left Sidebar + Main Column', 'code' => 'left-sidebar', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'name' => 'Main Column + Right Sidebar', 'code' => 'right-sidebar', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'name' => 'Left Sidebar + Main Column + Right Sidebar', 'code' => 'both-sidebars', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // 23. Create cms_pages table
        Schema::create('cms_pages', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->longText('content');
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->unsignedBigInteger('author_id')->nullable();
            $table->dateTime('expires_at')->nullable();
            $table->boolean('requires_code')->default(false);
            $table->string('access_code')->nullable();
            $table->unsignedBigInteger('required_product_id')->nullable();
            $table->text('custom_css')->nullable();
            $table->text('custom_js')->nullable();
            $table->string('header_image')->nullable();
            $table->string('background_image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('page_type')->default(1);
            $table->integer('page_ranking')->default(0);
            $table->integer('hide_page_ranking')->default(1);
            $table->float('custom_sorting')->default(0);
            $table->unsignedBigInteger('layout_type')->default(1);
            $table->mediumText('left_col')->nullable();
            $table->mediumText('right_col')->nullable();
            $table->string('custom_author')->nullable();
            $table->tinyInteger('show_author')->default(1);
            $table->tinyInteger('show_title')->default(1);
            $table->tinyInteger('show_date')->default(1);
            $table->string('featured_image')->nullable();
            $table->tinyInteger('featured_image_s3')->default(0);
            $table->string('featured_image_region')->nullable();
            $table->string('featured_image_bucket_name')->nullable();
            $table->string('featured_image_access_key_id')->nullable();
            $table->string('featured_image_secret_access_key')->nullable();
            $table->tinyInteger('media_image_s3')->default(0);
            $table->string('media_image_region')->nullable();
            $table->string('media_image_bucket_name')->nullable();
            $table->string('media_image_access_key_id')->nullable();
            $table->string('media_image_secret_access_key')->nullable();
            $table->string('featured_image_cdn_url')->nullable();
            $table->string('media_image_cdn_url')->nullable();
            $table->timestamps();

            $table->foreign('author_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('required_product_id')->references('id')->on('products')->onDelete('set null');
            $table->foreign('page_type')->references('id')->on('cms_page_types');
            $table->foreign('layout_type')->references('id')->on('cms_layouts');
        });

        // 24. Create cms_page_revisions table
        Schema::create('cms_page_revisions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cms_page_id')->constrained('cms_pages')->cascadeOnDelete();
            $table->string('title');
            $table->longText('content');
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('custom_css')->nullable();
            $table->text('custom_js')->nullable();
            $table->string('header_image')->nullable();
            $table->string('background_image')->nullable();
            $table->string('revision_type')->default('manual');
            $table->unsignedBigInteger('author_id')->nullable();
            $table->integer('layout_type')->default(1);
            $table->mediumText('left_col')->nullable();
            $table->mediumText('right_col')->nullable();
            $table->string('custom_author')->nullable();
            $table->tinyInteger('show_author')->default(1);
            $table->tinyInteger('show_title')->default(1);
            $table->tinyInteger('show_date')->default(1);
            $table->timestamps();

            $table->foreign('author_id')->references('id')->on('users')->onDelete('set null');
        });

        // 25. Create cms_pages_categories table
        Schema::create('cms_pages_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->timestamps();
        });

        // 26. Create cms_pages_tags table
        Schema::create('cms_pages_tags', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->timestamps();
        });

        // 27. Create cms_page_category pivot
        Schema::create('cms_page_category', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cms_page_id');
            $table->unsignedBigInteger('category_id');
            $table->timestamps();

            $table->foreign('cms_page_id')->references('id')->on('cms_pages')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('cms_pages_categories')->onDelete('cascade');
            $table->unique(['cms_page_id', 'category_id']);
        });

        // 28. Create cms_page_tag pivot
        Schema::create('cms_page_tag', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cms_page_id');
            $table->unsignedBigInteger('tag_id');
            $table->timestamps();

            $table->foreign('cms_page_id')->references('id')->on('cms_pages')->onDelete('cascade');
            $table->foreign('tag_id')->references('id')->on('cms_pages_tags')->onDelete('cascade');
            $table->unique(['cms_page_id', 'tag_id']);
        });

        // 29. Create cms_settings table
        Schema::create('cms_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('label');
            $table->string('type')->default('text'); // boolean | text | textarea
            $table->string('group')->default('general');
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });

        // 30. Create discount_types table
        Schema::create('discount_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->timestamps();
        });

        // Seed discount types
        DB::table('discount_types')->insert([
            ['id' => 1, 'name' => 'Coupon Code | Gift Certificate', 'description' => 'Discounts triggered by a promo code or gift certificate.'],
            ['id' => 2, 'name' => 'Preferred Customer', 'description' => 'Applied to preferred customer profiles.'],
            ['id' => 3, 'name' => 'General Order', 'description' => 'Applied to the entire order subtotal.'],
            ['id' => 4, 'name' => 'New Customer', 'description' => 'Applied to the first order of new customers.'],
            ['id' => 5, 'name' => 'Brand or Category', 'description' => 'Applied to items in a specific category or brand.'],
            ['id' => 6, 'name' => 'Item-Specific', 'description' => 'Applied to specific products.'],
            ['id' => 7, 'name' => 'BOGO', 'description' => 'Buy X and get Y for a discounted price.'],
        ]);

        // 31. Create discount_configuration table
        Schema::create('discount_configuration', function (Blueprint $table) {
            $table->id();
            $table->integer('store_id')->default(1);
            $table->tinyInteger('coupon_codes')->default(1);
            $table->tinyInteger('preferred_customers')->default(1);
            $table->tinyInteger('category_discounts')->default(1);
            $table->tinyInteger('quantity_based')->default(1);
            $table->tinyInteger('value_based')->default(1);
            $table->tinyInteger('new_customer_discount')->default(1);
            $table->tinyInteger('item_specific')->default(1);
            $table->tinyInteger('allow_multiple_order_discounts')->default(1);
            $table->timestamps();
        });

        // Seed default configuration
        DB::table('discount_configuration')->insert([
            'id' => 1,
            'store_id' => 1,
            'coupon_codes' => 1,
            'preferred_customers' => 1,
            'category_discounts' => 1,
            'quantity_based' => 1,
            'value_based' => 1,
            'new_customer_discount' => 1,
            'item_specific' => 1,
            'allow_multiple_order_discounts' => 1,
        ]);

        // 32. Create discounts table
        Schema::create('discounts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('discount_type_id');
            $table->integer('value_type')->default(1); // 1 = Specific Value Off ($), 2 = Percent Off (%)
            $table->double('order_minimum')->default(0);
            $table->double('order_maximum')->default(100000);
            $table->integer('order_qty_min')->default(1);
            $table->integer('order_qty_max')->default(1000000);
            $table->integer('product_id')->default(0);
            $table->string('name')->nullable();
            $table->double('value')->default(0);
            $table->string('code')->nullable();
            $table->integer('code_type')->default(0); // 0 = Coupon Code, 1 = Gift Certificate
            $table->integer('times_redeemed')->default(0);
            $table->integer('get_x_free')->default(0);
            $table->integer('free_range1')->default(0);
            $table->integer('free_range2')->default(0);
            $table->double('free_percent')->default(100);
            $table->integer('show_get_x_free')->default(0);
            $table->longText('show_get_x_text')->nullable();
            $table->integer('buy_x_get_y')->default(0);
            $table->integer('product_id_y')->default(0);
            $table->double('product_y_percent')->default(100);
            $table->dateTime('start_date')->nullable();
            $table->dateTime('expiration_date')->nullable();
            $table->tinyInteger('is_active')->default(1);
            $table->integer('store_id')->default(1);
            $table->integer('brand_id')->default(0);
            $table->integer('brand_qty_min')->default(1);
            $table->integer('brand_qty_max')->default(1000000);
            $table->double('brand_subtotal_min')->default(0);
            $table->double('brand_subtotal_max')->default(1000000);
            $table->integer('category_id')->default(0);
            $table->integer('cat_qty_min')->default(1);
            $table->integer('cat_qty_max')->default(1000000);
            $table->double('cat_subtotal_min')->default(0);
            $table->double('cat_subtotal_max')->default(1000000);
            $table->integer('subcat_id')->default(0);
            $table->integer('subcat_qty_min')->default(1);
            $table->integer('subcat_qty_max')->default(1000000);
            $table->double('subcat_subtotal_min')->default(0);
            $table->double('subcat_subtotal_max')->default(1000000);
            $table->integer('style_id')->default(0);
            $table->integer('style_qty_min')->default(1);
            $table->integer('style_qty_max')->default(1000000);
            $table->double('style_subtotal_min')->default(0);
            $table->double('style_subtotal_max')->default(1000000);
            $table->integer('item_qty_min')->default(1);
            $table->integer('item_qty_max')->default(1000000);
            $table->double('item_subtotal_min')->default(0);
            $table->double('item_subtotal_max')->default(1000000);
            $table->longText('bogo_cart_text')->nullable();
            $table->tinyInteger('free_shipping')->default(0);
            $table->tinyInteger('wholesale_only')->default(0);
            $table->double('order_weight_min')->default(0);
            $table->double('order_weight_max')->default(1000000);
            $table->timestamps();

            $table->foreign('discount_type_id')->references('id')->on('discount_types')->onDelete('cascade');
        });

        // 33. Create product_quantity_discounts table
        Schema::create('product_quantity_discounts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_variant_id');
            $table->integer('qty_min')->default(1);
            $table->integer('qty_max')->default(1000000);
            $table->decimal('discount_value', 10, 2)->default(0.00);
            $table->integer('value_type')->default(1); // 1 = Specific Value Off ($), 2 = Percent Off (%)
            $table->timestamps();

            $table->foreign('product_variant_id')->references('id')->on('product_variants')->onDelete('cascade');
        });

        // 34. Add preferred_discount_id to users table (after discounts table is created)
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('preferred_discount_id')->nullable()->after('new_user_discount');
            if (DB::getDriverName() !== 'sqlite') {
                $table->foreign('preferred_discount_id')->references('id')->on('discounts')->onDelete('set null');
            }
        });

        // 35. Create email_template_types table
        Schema::create('email_template_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->double('ordering')->default(0);
            $table->tinyInteger('is_active')->default(1);
            $table->timestamps();
        });

        // Seed email template types
        $types = [
            ['id' => 1, 'name' => 'Order Confirmation', 'slug' => 'order_confirmation', 'ordering' => 2.0, 'is_active' => 1],
            ['id' => 2, 'name' => 'Order Shipment Confirmation', 'slug' => 'order_shipment', 'ordering' => 3.0, 'is_active' => 1],
            ['id' => 3, 'name' => 'Download Order Reminder', 'slug' => 'download_reminder', 'ordering' => 4.0, 'is_active' => 1],
            ['id' => 4, 'name' => 'Customer Registration (Retail)', 'slug' => 'registration_retail', 'ordering' => 5.0, 'is_active' => 1],
            ['id' => 5, 'name' => 'Customer Registration (Wholesale)', 'slug' => 'registration_wholesale', 'ordering' => 6.0, 'is_active' => 1],
            ['id' => 6, 'name' => 'Account Activation / Email Verification', 'slug' => 'account_activation', 'ordering' => 6.5, 'is_active' => 1],
            ['id' => 7, 'name' => 'Reset Password', 'slug' => 'password_reset', 'ordering' => 9.5, 'is_active' => 1],
            ['id' => 8, 'name' => 'Support Ticket Submitted', 'slug' => 'ticket_submitted', 'ordering' => 10.0, 'is_active' => 1],
            ['id' => 9, 'name' => 'Support Ticket Reply Received', 'slug' => 'ticket_reply', 'ordering' => 11.0, 'is_active' => 1],
            ['id' => 10, 'name' => 'Support Ticket Status Updated', 'slug' => 'ticket_status', 'ordering' => 12.0, 'is_active' => 1],
        ];

        foreach ($types as $type) {
            $type['created_at'] = now();
            $type['updated_at'] = now();
            DB::table('email_template_types')->insert($type);
        }

        // 36. Create email_templates table
        Schema::create('email_templates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('email_type_id');
            $table->string('profile_name');
            $table->string('from_address')->nullable();
            $table->string('from_name')->nullable();
            $table->string('bcc_address')->nullable();
            $table->longText('subject');
            $table->longText('header_html')->nullable();
            $table->string('banner_image_url')->nullable();
            $table->string('banner_image_link')->nullable();
            $table->tinyInteger('show_banner')->default(1);
            $table->longText('salutation')->nullable();
            $table->tinyInteger('include_salutation')->default(0);
            $table->longText('greeting')->nullable();
            $table->longText('body')->nullable();
            $table->longText('sign_off')->nullable();
            $table->longText('signature')->nullable();
            $table->longText('disclaimer')->nullable();
            $table->longText('copyright')->nullable();
            $table->string('footer_image_url')->nullable();
            $table->string('footer_image_link')->nullable();
            $table->tinyInteger('show_footer_image')->default(0);
            $table->longText('footer_html')->nullable();
            $table->tinyInteger('is_active')->default(0);
            $table->timestamps();

            $table->foreign('email_type_id')->references('id')->on('email_template_types')->onDelete('cascade');
        });

        // Seed Default Email Profiles
        $now = now();
        $defaultProfiles = [
            [
                'email_type_id' => 1,
                'profile_name' => 'Default Order Confirmation',
                'from_address' => 'support@example.com',
                'from_name' => 'Online Store',
                'bcc_address' => '',
                'subject' => 'Order Confirmation # {{order_id}}',
                'header_html' => '',
                'banner_image_url' => '',
                'banner_image_link' => '',
                'show_banner' => 0,
                'salutation' => 'Dear {{customer_name}},',
                'include_salutation' => 1,
                'greeting' => '<p><strong>We appreciate your order!</strong></p><p>If you need assistance, please submit a support ticket or send us an email.</p>',
                'body' => '<p>Below are the details of your order.</p>',
                'sign_off' => 'Sincerely,',
                'signature' => 'Sales Department',
                'disclaimer' => 'If you have any questions about this order, please contact us anytime!',
                'copyright' => 'Copyright 2026',
                'footer_image_url' => '',
                'footer_image_link' => '',
                'show_footer_image' => 0,
                'footer_html' => '',
                'is_active' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'email_type_id' => 2,
                'profile_name' => 'Default Shipment Confirmation',
                'from_address' => 'support@example.com',
                'from_name' => 'Online Store',
                'bcc_address' => '',
                'subject' => 'Online Order Shipment Confirmation # {{order_id}}',
                'header_html' => '',
                'banner_image_url' => '',
                'banner_image_link' => '',
                'show_banner' => 0,
                'salutation' => 'Dear {{customer_name}},',
                'include_salutation' => 1,
                'greeting' => '<p>This is a shipment notification message. Your order has shipped!</p>',
                'body' => '<p>Tracking Number: {{tracking_number}}</p><p>{{order_items_table}}</p>',
                'sign_off' => 'Sincerely,',
                'signature' => 'Sales Department',
                'disclaimer' => 'If you have any questions about your order, please do not hesitate to contact us.',
                'copyright' => 'Copyright 2026',
                'footer_image_url' => '',
                'footer_image_link' => '',
                'show_footer_image' => 0,
                'footer_html' => '',
                'is_active' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'email_type_id' => 3,
                'profile_name' => 'Default Download Reminder',
                'from_address' => 'support@example.com',
                'from_name' => 'Online Store',
                'bcc_address' => '',
                'subject' => 'Download Reminder -- Order ID # {{order_id}}',
                'header_html' => '',
                'banner_image_url' => '',
                'banner_image_link' => '',
                'show_banner' => 0,
                'salutation' => 'Dear {{customer_name}},',
                'include_salutation' => 1,
                'greeting' => '<p><strong>We just wanted to remind you to download your product(s) that you recently ordered.</strong></p>',
                'body' => '<p>You can access your download link(s) below:</p><p>{{download_links}}</p><p>{{order_items_table}}</p>',
                'sign_off' => 'Sincerely,',
                'signature' => 'Customer Support Team',
                'disclaimer' => 'Downloads expire in 7 days.',
                'copyright' => 'Copyright 2026',
                'footer_image_url' => '',
                'footer_image_link' => '',
                'show_footer_image' => 0,
                'footer_html' => '',
                'is_active' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'email_type_id' => 4,
                'profile_name' => 'Default Retail Registration',
                'from_address' => 'support@example.com',
                'from_name' => 'Online Store',
                'bcc_address' => '',
                'subject' => 'Account Registration Confirmation',
                'header_html' => '',
                'banner_image_url' => '',
                'banner_image_link' => '',
                'show_banner' => 0,
                'salutation' => 'Dear {{customer_name}},',
                'include_salutation' => 1,
                'greeting' => '<p>Thank you for registering for an online account!</p>',
                'body' => '<p>We look forward to providing you with excellent service.</p>',
                'sign_off' => 'Sincerely,',
                'signature' => 'Sales Department',
                'disclaimer' => 'If you have any questions about your account, please do not hesitate to contact us.',
                'copyright' => 'Copyright 2026',
                'footer_image_url' => '',
                'footer_image_link' => '',
                'show_footer_image' => 0,
                'footer_html' => '',
                'is_active' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'email_type_id' => 5,
                'profile_name' => 'Default Wholesale Registration',
                'from_address' => 'support@example.com',
                'from_name' => 'Online Store',
                'bcc_address' => '',
                'subject' => 'Wholesale Account Registration',
                'header_html' => '',
                'banner_image_url' => '',
                'banner_image_link' => '',
                'show_banner' => 0,
                'salutation' => 'Dear {{customer_name}},',
                'include_salutation' => 1,
                'greeting' => '<p>Thank you for registering for an online wholesale account!</p>',
                'body' => '<p>We look forward to providing you with excellent service. Our administrators will review your wholesale credentials shortly.</p>',
                'sign_off' => 'Sincerely,',
                'signature' => 'Sales Department',
                'disclaimer' => 'If you have any questions about your account, please do not hesitate to contact us.',
                'copyright' => 'Copyright 2026',
                'footer_image_url' => '',
                'footer_image_link' => '',
                'show_footer_image' => 0,
                'footer_html' => '',
                'is_active' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'email_type_id' => 6,
                'profile_name' => 'Default Account Activation',
                'from_address' => 'support@example.com',
                'from_name' => 'Online Store',
                'bcc_address' => '',
                'subject' => 'Account Activation Required',
                'header_html' => '',
                'banner_image_url' => '',
                'banner_image_link' => '',
                'show_banner' => 0,
                'salutation' => 'Dear {{customer_name}},',
                'include_salutation' => 1,
                'greeting' => '<p>Thank you for registering for an account on our website.</p>',
                'body' => '<p>To complete the registration process you must click the link below to activate your account:</p><p><a href="{{activation_url}}" style="background-color: #4f46e5; color: #ffffff; padding: 10px 20px; text-decoration: none; border-radius: 6px; display: inline-block;">Activate Account</a></p>',
                'sign_off' => 'Sincerely,',
                'signature' => 'Customer Support Dept.',
                'disclaimer' => 'If you have any questions about your account, please do not hesitate to contact us.',
                'copyright' => 'Copyright 2026',
                'footer_image_url' => '',
                'footer_image_link' => '',
                'show_footer_image' => 0,
                'footer_html' => '',
                'is_active' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'email_type_id' => 7,
                'profile_name' => 'Default Reset Password Request',
                'from_address' => 'support@example.com',
                'from_name' => 'Online Store',
                'bcc_address' => '',
                'subject' => 'Reset Password Request',
                'header_html' => '',
                'banner_image_url' => '',
                'banner_image_link' => '',
                'show_banner' => 0,
                'salutation' => 'Dear {{customer_name}},',
                'include_salutation' => 1,
                'greeting' => '<p>You are receiving this email because we received a password reset request for your account.</p>',
                'body' => '<p>Please click the button below to reset your password:</p><p><a href="{{reset_url}}" style="background-color: #4f46e5; color: #ffffff; padding: 10px 20px; text-decoration: none; border-radius: 6px; display: inline-block;">Reset Password</a></p><p>This password reset link will expire in 60 minutes.</p><p>If you did not request a password reset, no further action is required.</p>',
                'sign_off' => 'Sincerely,',
                'signature' => 'Customer Support Dept.',
                'disclaimer' => 'If you are having trouble clicking the reset button, copy and paste the URL into your web browser.',
                'copyright' => 'Copyright 2026',
                'footer_image_url' => '',
                'footer_image_link' => '',
                'show_footer_image' => 0,
                'footer_html' => '',
                'is_active' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'email_type_id' => 8,
                'profile_name' => 'Default Support Ticket Submitted',
                'from_address' => 'support@example.com',
                'from_name' => 'Support Team',
                'bcc_address' => '',
                'subject' => 'Support Ticket Submitted: {{ticket_title}}',
                'header_html' => '',
                'banner_image_url' => '',
                'banner_image_link' => '',
                'show_banner' => 0,
                'salutation' => 'Hi {{customer_name}},',
                'include_salutation' => 1,
                'greeting' => '<p>Thank you for contacting support. We received your ticket <strong>"{{ticket_title}}"</strong> and our team will review it shortly.</p>',
                'body' => '<p><strong>Current status:</strong> {{ticket_status}}</p><p><a href="{{ticket_url}}" style="background-color: #4f46e5; color: #ffffff; padding: 10px 20px; text-decoration: none; border-radius: 6px; display: inline-block;">View Your Ticket</a></p>',
                'sign_off' => 'Thanks,',
                'signature' => 'Customer Support Team',
                'disclaimer' => 'You can reply directly to this email to add updates to your ticket.',
                'copyright' => 'Copyright 2026. All rights reserved.',
                'footer_image_url' => '',
                'footer_image_link' => '',
                'show_footer_image' => 0,
                'footer_html' => '',
                'is_active' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'email_type_id' => 9,
                'profile_name' => 'Default Support Ticket Reply',
                'from_address' => 'support@example.com',
                'from_name' => 'Support Team',
                'bcc_address' => '',
                'subject' => 'New Reply on Ticket: {{ticket_title}}',
                'header_html' => '',
                'banner_image_url' => '',
                'banner_image_link' => '',
                'show_banner' => 0,
                'salutation' => 'Hi {{customer_name}},',
                'include_salutation' => 1,
                'greeting' => '<p>There is a new reply on your ticket <strong>"{{ticket_title}}"</strong> from {{reply_author}}.</p>',
                'body' => '<div style="border-left: 4px solid #e2e8f0; padding-left: 16px; margin: 16px 0; color: #475569;">{{reply_body}}</div><p><a href="{{ticket_url}}" style="background-color: #4f46e5; color: #ffffff; padding: 10px 20px; text-decoration: none; border-radius: 6px; display: inline-block;">View Conversation</a></p>',
                'sign_off' => 'Thanks,',
                'signature' => 'Customer Support Team',
                'disclaimer' => 'You can reply directly to this email to continue the conversation.',
                'copyright' => 'Copyright 2026. All rights reserved.',
                'footer_image_url' => '',
                'footer_image_link' => '',
                'show_footer_image' => 0,
                'footer_html' => '',
                'is_active' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'email_type_id' => 10,
                'profile_name' => 'Default Support Ticket Status Updated',
                'from_address' => 'support@example.com',
                'from_name' => 'Support Team',
                'bcc_address' => '',
                'subject' => 'Ticket Update: {{ticket_title}} is now {{ticket_status}}',
                'header_html' => '',
                'banner_image_url' => '',
                'banner_image_link' => '',
                'show_banner' => 0,
                'salutation' => 'Hi {{customer_name}},',
                'include_salutation' => 1,
                'greeting' => '<p>Your support ticket <strong>"{{ticket_title}}"</strong> has been updated.</p>',
                'body' => '<p><strong>Previous status:</strong> {{previous_status}}</p><p><strong>New status:</strong> {{ticket_status}}</p><p><a href="{{ticket_url}}" style="background-color: #4f46e5; color: #ffffff; padding: 10px 20px; text-decoration: none; border-radius: 6px; display: inline-block;">View Your Ticket</a></p>',
                'sign_off' => 'Thanks,',
                'signature' => 'Customer Support Team',
                'disclaimer' => 'You can reply directly to this email to add updates to your ticket.',
                'copyright' => 'Copyright 2026. All rights reserved.',
                'footer_image_url' => '',
                'footer_image_link' => '',
                'show_footer_image' => 0,
                'footer_html' => '',
                'is_active' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        foreach ($defaultProfiles as $profile) {
            DB::table('email_templates')->insert($profile);
        }

        // 37. Create shipping_countries table
        Schema::create('shipping_countries', function (Blueprint $table) {
            $table->id();
            $table->double('sort_order')->default(0);
            $table->string('name');
            $table->string('code', 50)->nullable();
            $table->boolean('charge_vat')->default(true);
            $table->double('custom_vat_rate')->default(0);
            $table->boolean('exclude_free_shipping')->default(false);
            $table->boolean('is_active')->default(true);
            $table->integer('flat_rate_value_type')->default(1);
            $table->text('flat_rate_range')->nullable();
            $table->timestamps();
        });

        // 38. Create shipping_states table
        Schema::create('shipping_states', function (Blueprint $table) {
            $table->id();
            $table->string('country_code', 50)->default('US');
            $table->string('name');
            $table->string('code', 50)->nullable();
            $table->boolean('is_active')->default(true);
            $table->double('sales_tax_rate')->default(0);
            $table->double('vat_rate')->default(0);
            $table->boolean('exclude_free_shipping')->default(false);
            $table->integer('flat_rate_value_type')->default(1);
            $table->text('flat_rate_range')->nullable();
            $table->timestamps();
        });

        // 39. Create shipping_configurations table
        Schema::create('shipping_configurations', function (Blueprint $table) {
            $table->id();
            $table->boolean('custom_ship_options_us')->default(false);
            $table->boolean('custom_ship_options_int')->default(false);
            $table->boolean('allow_comments')->default(false);
            $table->string('origin_zipcode')->nullable();
            $table->string('origin_country_code', 50)->nullable();
            $table->boolean('realtime_fedex')->default(false);
            $table->boolean('realtime_ups')->default(false);
            $table->boolean('realtime_usps')->default(false);
            $table->boolean('realtime_pickup')->default(false);
            $table->timestamps();
        });

        // 40. Create shipping_flat_rates table
        Schema::create('shipping_flat_rates', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_international')->default(false);
            $table->string('name');
            $table->decimal('amount', 10, 2)->default(0.00);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // 41. Create handling_charges table
        Schema::create('handling_charges', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('fee', 10, 2)->default(0.00);
            $table->boolean('is_active')->default(false);
            $table->decimal('min_subtotal', 10, 2)->nullable();
            $table->decimal('max_subtotal', 10, 2)->nullable();
            $table->decimal('min_weight', 10, 2)->nullable();
            $table->integer('min_items')->nullable();
            $table->timestamps();
        });

        // 42. Create warehouse_locations table
        Schema::create('warehouse_locations', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('code', 50)->unique();
            $table->text('address')->nullable();
            $table->string('city', 100)->nullable();
            $table->string('state_code', 10)->nullable();
            $table->string('country_code', 10)->default('US');
            $table->string('zipcode', 20)->nullable();
            $table->string('shipstation_carrier_id', 100)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Seed default warehouse locations
        DB::table('warehouse_locations')->insert([
            [
                'name' => 'Primary US Warehouse',
                'code' => 'US-WH-1',
                'address' => '100 Congress Ave',
                'city' => 'Austin',
                'state_code' => 'TX',
                'country_code' => 'US',
                'zipcode' => '78701',
                'is_active' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Canada Fulfillment Hub',
                'code' => 'CA-WH-1',
                'address' => '50 Bay St',
                'city' => 'Toronto',
                'state_code' => 'ON',
                'country_code' => 'CA',
                'zipcode' => 'M5J 2L2',
                'is_active' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'UK Distribution Centre',
                'code' => 'UK-WH-1',
                'address' => '100 Wood St',
                'city' => 'London',
                'state_code' => null,
                'country_code' => 'GB',
                'zipcode' => 'EC2V 7AN',
                'is_active' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        // 43. Seed user roles
        $roles = [
            ['id' => 1, 'name' => 'User', 'description' => 'Regular customer. Can submit and view their own tickets.'],
            ['id' => 2, 'name' => 'Wholesale', 'description' => 'Wholesale customer. Receives wholesale tier pricing.'],
            ['id' => 3, 'name' => 'Admin', 'description' => 'Full admin access. Can manage all tickets, users, and settings.'],
            ['id' => 4, 'name' => 'Order Processor', 'description' => 'Can view and edit orders in the admin area.'],
            ['id' => 5, 'name' => 'Ticket Manager', 'description' => 'Can view and reply to tickets in the admin area.'],
        ];

        foreach ($roles as $role) {
            DB::table('user_roles')->updateOrInsert(
                ['id' => $role['id']],
                [
                    'name' => $role['name'],
                    'description' => $role['description'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        // 44. Convert existing user role_id = 2 to role_id = 5 (Staff Migration)
        DB::table('users')
            ->where('role_id', 2)
            ->update(['role_id' => 5]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop all tables created
        Schema::dropIfExists('warehouse_locations');
        Schema::dropIfExists('handling_charges');
        Schema::dropIfExists('shipping_flat_rates');
        Schema::dropIfExists('shipping_configurations');
        Schema::dropIfExists('shipping_states');
        Schema::dropIfExists('shipping_countries');
        Schema::dropIfExists('email_templates');
        Schema::dropIfExists('email_template_types');
        
        Schema::table('users', function (Blueprint $table) {
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign(['preferred_discount_id']);
            }
            $table->dropColumn([
                'company',
                'shipping_address1',
                'shipping_address2',
                'shipping_city',
                'shopping_postalcode',
                'shipping_country',
                'shipping_countrycode',
                'rewards_status',
                'new_user_discount',
                'active',
                'user_token_1',
                'user_token_2',
                'shipping_state',
                'preferred_discount_id'
            ]);
        });

        Schema::dropIfExists('product_quantity_discounts');
        Schema::dropIfExists('discounts');
        Schema::dropIfExists('discount_configuration');
        Schema::dropIfExists('discount_types');
        Schema::dropIfExists('cms_settings');
        Schema::dropIfExists('cms_page_tag');
        Schema::dropIfExists('cms_page_category');
        Schema::dropIfExists('cms_pages_tags');
        Schema::dropIfExists('cms_pages_categories');
        Schema::dropIfExists('cms_page_revisions');
        Schema::dropIfExists('cms_pages');
        Schema::dropIfExists('cms_layouts');
        Schema::dropIfExists('cms_page_types');
        Schema::dropIfExists('product_categories_assignments');
        Schema::dropIfExists('product_categories');
        Schema::dropIfExists('order_status_list');
        Schema::dropIfExists('order_processors');
        Schema::dropIfExists('order_checkout_options');
        Schema::dropIfExists('shopping_cart_log');
        Schema::dropIfExists('order_downloads');
        Schema::dropIfExists('order_refunds');
        Schema::dropIfExists('order_payments');
        Schema::dropIfExists('order_details');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('product_field_options');
        Schema::dropIfExists('product_fields');
        Schema::dropIfExists('product_images');
        Schema::dropIfExists('products_inventory');
        Schema::dropIfExists('product_variants');
        Schema::dropIfExists('products');
        Schema::dropIfExists('product_brands');
    }
};
