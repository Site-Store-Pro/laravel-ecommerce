<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Discount;
use App\Models\DiscountConfiguration;
use App\Models\Product;
use App\Models\ProductQuantityDiscount;
use App\Models\ProductVariant;
use App\Models\ShoppingCartLog;
use App\Models\User;
use App\Services\DiscountService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DiscountSystemTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Seed default discount types and default configuration
        $this->artisan('db:seed', ['--class' => 'Database\\Seeders\\DatabaseSeeder']);
    }

    private function createCartItem(array $attributes): ShoppingCartLog
    {
        return ShoppingCartLog::create(array_merge([
            'cart_log_session' => 'test-session',
            'user_id' => 0,
            'item_id' => 1,
            'item_name' => 'Test Item',
            'item_qty' => 1,
            'item_price' => 10.00,
            'item_discount_price' => 0.00,
            'item_shippable' => 0,
            'item_taxable' => 0,
            'item_weight' => 0.0,
            'item_downloadable' => 0,
            'order_id' => 0
        ], $attributes));
    }

    public function test_item_level_category_and_brand_discounts(): void
    {
        $category = Category::create(['name' => 'Rings', 'slug' => 'rings']);
        $brand = Brand::create(['name' => 'Tiffany', 'slug' => 'tiffany']);

        $product = Product::create([
            'title' => 'Gold Diamond Ring',
            'short_description' => 'Test',
            'long_description' => 'Test',
            'brand_id' => $brand->id
        ]);
        $product->categories()->attach($category->id);

        $variant = ProductVariant::create([
            'product_id' => $product->id,
            'sku' => 'GOLD-RING-1',
            'public_price' => 100.00,
            'wholesale_price' => 80.00,
            'on_sale' => 0
        ]);

        // Create Category Discount: 20% OFF Category "Rings"
        Discount::create([
            'discount_type_id' => 5, // Category & Brand
            'value_type' => 2, // %
            'value' => 20, // 20%
            'category_id' => $category->id,
            'name' => '20% off Rings',
            'is_active' => 1
        ]);

        $item = $this->createCartItem([
            'item_name' => 'Gold Diamond Ring (GOLD-RING-1)',
            'item_qty' => 1,
            'item_price' => 100.00,
            'item_shippable' => 1
        ]);

        $result = DiscountService::applyDiscountsToCart(collect([$item]), null);

        $this->assertEquals(80.00, $item->item_price);
        $this->assertEquals(20.00, $item->item_discount_price);
    }

    public function test_item_level_quantity_discount_breaks(): void
    {
        $product = Product::create([
            'title' => 'Silver Necklace',
            'short_description' => 'Test',
            'long_description' => 'Test'
        ]);

        $variant = ProductVariant::create([
            'product_id' => $product->id,
            'sku' => 'SILVER-NECK-1',
            'public_price' => 50.00,
            'wholesale_price' => 40.00,
            'on_sale' => 0
        ]);

        // Create quantity discount breaks
        ProductQuantityDiscount::create([
            'product_variant_id' => $variant->id,
            'qty_min' => 5,
            'qty_max' => 9,
            'discount_value' => 5.00, // $5 off
            'value_type' => 1 // specific value
        ]);

        ProductQuantityDiscount::create([
            'product_variant_id' => $variant->id,
            'qty_min' => 10,
            'qty_max' => 100,
            'discount_value' => 10.00, // $10 off
            'value_type' => 1 // specific value
        ]);

        $item = $this->createCartItem([
            'item_name' => 'Silver Necklace (SILVER-NECK-1)',
            'item_qty' => 10, // triggers the 2nd break
            'item_price' => 50.00
        ]);

        $result = DiscountService::applyDiscountsToCart(collect([$item]), null);

        $this->assertEquals(40.00, $item->item_price); // 50 - 10 = 40
        $this->assertEquals(10.00, $item->item_discount_price);
    }

    public function test_bogo_buy_1_get_1_free(): void
    {
        $productX = Product::create(['title' => 'Shirt X', 'short_description' => 'x', 'long_description' => 'x']);
        $productY = Product::create(['title' => 'Shirt Y', 'short_description' => 'y', 'long_description' => 'y']);

        $variantX = ProductVariant::create([
            'product_id' => $productX->id,
            'sku' => 'SHIRT-X',
            'public_price' => 30.00,
            'wholesale_price' => 20.00
        ]);

        $variantY = ProductVariant::create([
            'product_id' => $productY->id,
            'sku' => 'SHIRT-Y',
            'public_price' => 20.00,
            'wholesale_price' => 15.00
        ]);

        // Create BOGO: Buy 1 Shirt X (free_range1 = 1) Get 1 Shirt Y (free_range2 = 1) 100% OFF (free_percent/product_y_percent = 100)
        Discount::create([
            'discount_type_id' => 7, // BOGO
            'buy_x_get_y' => $productX->id,
            'free_range1' => 1,
            'product_id_y' => $productY->id,
            'free_range2' => 1,
            'product_y_percent' => 100, // 100% Off
            'name' => 'Buy X Get Y Free',
            'is_active' => 1
        ]);

        $itemX = $this->createCartItem([
            'item_name' => 'Shirt X (SHIRT-X)',
            'item_qty' => 1,
            'item_price' => 30.00
        ]);

        $itemY = $this->createCartItem([
            'item_name' => 'Shirt Y (SHIRT-Y)',
            'item_qty' => 1,
            'item_price' => 20.00
        ]);

        $result = DiscountService::applyDiscountsToCart(collect([$itemX, $itemY]), null);

        $this->assertEquals(30.00, $itemX->item_price);
        $this->assertEquals(0.00, $itemY->item_price); // Item Y is 100% OFF
        
        $attrsY = json_decode($itemY->item_attributes, true);
        $this->assertTrue($attrsY['is_bogo_target'] ?? false);
    }

    public function test_order_level_coupon_codes(): void
    {
        $product = Product::create(['title' => 'Hat', 'short_description' => 'h', 'long_description' => 'h']);
        $variant = ProductVariant::create([
            'product_id' => $product->id,
            'sku' => 'HAT-1',
            'public_price' => 100.00,
            'wholesale_price' => 80.00
        ]);

        Discount::create([
            'discount_type_id' => 1, // Coupon Code
            'code' => 'TESTCOUPON',
            'value_type' => 1, // $
            'value' => 15.00,
            'name' => 'Test Coupon',
            'is_active' => 1
        ]);

        session()->put('coupon_code', 'TESTCOUPON');

        $item = $this->createCartItem([
            'item_name' => 'Hat (HAT-1)',
            'item_qty' => 1,
            'item_price' => 100.00
        ]);

        $result = DiscountService::applyDiscountsToCart(collect([$item]), null);

        $this->assertEquals(15.00, $result['total_discount']);
        $this->assertEquals(85.00, $result['adjusted_subtotal']);
    }

    public function test_order_level_coupon_filters_rejection(): void
    {
        $product = Product::create(['title' => 'Hat', 'short_description' => 'h', 'long_description' => 'h']);
        $variant = ProductVariant::create([
            'product_id' => $product->id,
            'sku' => 'HAT-2',
            'public_price' => 10.00,
            'wholesale_price' => 8.00
        ]);

        Discount::create([
            'discount_type_id' => 1, // Coupon Code
            'code' => 'MIN25COUPON',
            'value_type' => 1, // $
            'value' => 5.00,
            'name' => 'Min 25 Coupon',
            'order_minimum' => 25.00,
            'is_active' => 1
        ]);

        session()->put('coupon_code', 'MIN25COUPON');

        $item = $this->createCartItem([
            'item_name' => 'Hat (HAT-2)',
            'item_qty' => 1,
            'item_price' => 10.00
        ]);

        $result = DiscountService::applyDiscountsToCart(collect([$item]), null);

        $this->assertEquals(0.00, $result['total_discount']);
        $this->assertEquals(10.00, $result['adjusted_subtotal']);
        $this->assertNull(session()->get('coupon_code'));
    }

    public function test_get_promotional_texts_for_product(): void
    {
        $category = Category::create(['name' => 'Watches', 'slug' => 'watches']);
        $brand = Brand::create(['name' => 'Seiko', 'slug' => 'seiko']);

        $product = Product::create([
            'title' => 'Seiko 5 Watch',
            'short_description' => 'Test Seiko',
            'long_description' => 'Test Seiko Long',
            'brand_id' => $brand->id
        ]);
        $product->categories()->attach($category->id);

        // Create category and brand discount with promo text
        Discount::create([
            'discount_type_id' => 5, // Brand or Category
            'value_type' => 2,
            'value' => 15,
            'brand_id' => $brand->id,
            'name' => '15% Off Seiko',
            'is_active' => 1,
            'show_get_x_free' => 1,
            'show_get_x_text' => '<p>Get 15% Off all Seiko watches!</p>'
        ]);

        $texts = DiscountService::getPromotionalTextsForProduct($product);
        $this->assertCount(1, $texts);
        $this->assertStringContainsString('Get 15% Off all Seiko watches', $texts[0]);
    }

    public function test_storefront_display_discounts(): void
    {
        $category = Category::create(['name' => 'Gems', 'slug' => 'gems']);
        $brand = Brand::create(['name' => 'Cartier', 'slug' => 'cartier']);

        $product = Product::create([
            'title' => 'Ruby Cartier Necklace',
            'short_description' => 'Beautiful ruby necklace',
            'long_description' => 'Cartier ruby necklace',
            'brand_id' => $brand->id
        ]);
        $product->categories()->attach($category->id);

        $variant = ProductVariant::create([
            'product_id' => $product->id,
            'sku' => 'CARTIER-RUBY',
            'public_price' => 200.00,
            'wholesale_price' => 160.00,
            'on_sale' => 0
        ]);

        // Create Brand Discount: $30 OFF Cartier, brand_qty_min = 0 (showing up immediately)
        Discount::create([
            'discount_type_id' => 5, // Brand or Category
            'value_type' => 1, // $
            'value' => 30.00,
            'brand_id' => $brand->id,
            'brand_qty_min' => 0,
            'name' => 'Cartier Sale',
            'is_active' => 1
        ]);

        $calculatedPrice = DiscountService::getDiscountedPriceForVariant($variant, null, 1);
        $this->assertEquals(170.00, $calculatedPrice); // 200 - 30 = 170

        // If qty is 0 but min_qty is 1, it should also show for qty=1 (default view)
        $calculatedPrice = DiscountService::getDiscountedPriceForVariant($variant, null, 0);
        $this->assertEquals(170.00, $calculatedPrice);
    }

    public function test_general_order_discount_with_free_shipping_and_zero_discount_value(): void
    {
        $product = Product::create(['title' => 'Bracelet', 'short_description' => 'b', 'long_description' => 'b']);
        $variant = ProductVariant::create([
            'product_id' => $product->id,
            'sku' => 'BRAC-1',
            'public_price' => 50.00,
            'wholesale_price' => 40.00
        ]);

        // Create General Order Discount (Type 3) with Free Shipping checked, Value = $0, Min Subtotal = $20, Max Subtotal = $0 (unlimited)
        Discount::create([
            'discount_type_id' => 3, // General Order Discount
            'value_type' => 1, // $
            'value' => 0.00, // $0 off order subtotal
            'free_shipping' => 1, // Free shipping checked
            'order_minimum' => 20.00,
            'order_maximum' => 0.00, // Unlimited
            'name' => 'Free Shipping Over $20',
            'is_active' => 1
        ]);

        $item = $this->createCartItem([
            'item_name' => 'Bracelet (BRAC-1)',
            'item_qty' => 1,
            'item_price' => 50.00
        ]);

        $result = DiscountService::applyDiscountsToCart(collect([$item]), null);

        $this->assertEquals(0.00, $result['total_discount']);
        $this->assertEquals(50.00, $result['adjusted_subtotal']);
        $this->assertCount(1, $result['discounts']);
        $this->assertEquals(1, $result['discounts'][0]['free_shipping']);

        // Verify that selector list shipping values automatically change to $0.00 when free shipping is active
        $shippingOptions = \App\Services\ShippingCalculationService::getAvailableOptions(50.00, 1.0, 1, 'US', 'CA', true);
        foreach ($shippingOptions as $opt) {
            $this->assertEquals(0.00, $opt['amount']);
        }
    }
}
