<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\OrderDownload;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\TestCase;

class ProductDownloadUuidTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Order $order;
    private ProductVariant $variant;
    private OrderDetail $orderDetail;

    protected function setUp(): void
    {
        parent::setUp();

        \Illuminate\Support\Facades\DB::table('user_roles')->insertOrIgnore(['id' => 1, 'name' => 'Customer']);
        \Illuminate\Support\Facades\DB::table('user_roles')->insertOrIgnore(['id' => 3, 'name' => 'Admin']);

        Storage::fake('public');
        Storage::disk('public')->put('downloads/ebook.pdf', 'dummy pdf content');

        $this->user = User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => bcrypt('password'),
            'role_id' => 1,
        ]);

        $this->order = Order::create([
            'order_invoice_no' => 'INV-9001',
            'order_external_id' => (string) Str::uuid(),
            'order_user_id' => $this->user->id,
            'order_status' => 7, // Completed / Paid
            'order_total' => 25.00,
            'order_subtotal' => 25.00,
            'order_taxes' => 0.00,
            'order_discounts' => 0.00,
            'order_shipping' => 0.00,
            'order_download' => 1,
            'order_date' => now(),
        ]);

        $product = Product::create([
            'title' => 'Digital eBook',
            'short_description' => 'A great eBook',
            'long_description' => 'Long description',
            'seo_slug' => 'digital-ebook',
        ]);

        $this->variant = ProductVariant::create([
            'product_id' => $product->id,
            'sku' => 'EBOOK-01',
            'public_price' => 25.00,
            'wholesale_price' => 10.00,
            'download_item' => 1,
            'download_location' => 'downloads/ebook.pdf',
            'download_expiration' => now()->addDays(30),
            'downloads_max_allowed' => 5,
        ]);

        $this->orderDetail = OrderDetail::create([
            'order_id' => $this->order->id,
            'item_name' => 'Digital eBook',
            'item_qty' => 1,
            'final_price' => 25.00,
            'base_price' => 25.00,
            'discount_price' => 0.00,
            'options_fee' => 0.00,
            'inventory_id' => $this->variant->id,
            'download_item' => 1,
            'download_location' => 'downloads/ebook.pdf',
            'download_expiration' => now()->addDays(30),
            'downloads_counter' => 0,
            'downloads_max_allowed' => 5,
        ]);
    }

    public function test_order_detail_auto_generates_uuid(): void
    {
        $this->assertNotEmpty($this->orderDetail->order_detail_external_id);
        $this->assertTrue(Str::isUuid($this->orderDetail->order_detail_external_id));
    }

    public function test_download_works_using_new_uuid_format(): void
    {
        $url = route('products.download', [
            $this->orderDetail->order_detail_external_id,
            $this->order->order_external_id,
        ]);

        $response = $this->get($url);

        $response->assertOk();
        $this->orderDetail->refresh();
        $this->assertEquals(1, $this->orderDetail->downloads_counter);
        $this->assertDatabaseHas('order_downloads', [
            'order_details_id' => $this->orderDetail->id,
        ]);
    }

    public function test_download_works_using_legacy_numeric_id_format(): void
    {
        $legacyUrl = route('products.download', [
            $this->orderDetail->id,
            $this->order->order_external_id,
        ]);

        $response = $this->get($legacyUrl);

        $response->assertOk();
        $this->orderDetail->refresh();
        $this->assertEquals(1, $this->orderDetail->downloads_counter);
        $this->assertDatabaseHas('order_downloads', [
            'order_details_id' => $this->orderDetail->id,
        ]);
    }

    public function test_download_fails_with_invalid_token(): void
    {
        $url = route('products.download', [
            $this->orderDetail->order_detail_external_id,
            'invalid-order-token',
        ]);

        $response = $this->get($url);

        $response->assertStatus(403);
    }

    public function test_download_fails_when_expired(): void
    {
        $this->orderDetail->update([
            'download_expiration' => now()->subDay(),
        ]);

        $url = route('products.download', [
            $this->orderDetail->order_detail_external_id,
            $this->order->order_external_id,
        ]);

        $response = $this->get($url);

        $response->assertStatus(403);
    }

    public function test_download_fails_when_max_downloads_reached(): void
    {
        $this->orderDetail->update([
            'downloads_counter' => 5,
            'downloads_max_allowed' => 5,
        ]);

        $url = route('products.download', [
            $this->orderDetail->order_detail_external_id,
            $this->order->order_external_id,
        ]);

        $response = $this->get($url);

        $response->assertStatus(403);
    }
}
