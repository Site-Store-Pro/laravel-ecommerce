<?php

namespace Tests\Feature;

use App\Models\EmailTemplate;
use App\Models\EmailTemplateType;
use App\Models\User;
use App\Services\EmailTemplateService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class EmailTemplateTest extends TestCase
{
    use RefreshDatabase;

    public function test_email_template_types_and_defaults_are_seeded(): void
    {
        $this->seed(); // Runs standard migrations & seeds

        // Verify we have all 13 types (including 2FA)
        $this->assertEquals(13, EmailTemplateType::count());

        // Verify we have seeded default profiles
        $this->assertEquals(13, EmailTemplate::count());

        // Verify active templates exist for each type
        foreach (EmailTemplateType::all() as $type) {
            $this->assertNotNull($type->activeTemplate());
        }
    }

    public function test_variable_replacement(): void
    {
        $text = "Hello {{customer_name}}, your order id is {{order_id}}.";
        $vars = [
            'customer_name' => 'Alice',
            'order_id' => '999888',
        ];

        $replaced = EmailTemplateService::replaceVariables($text, $vars);
        $this->assertEquals("Hello Alice, your order id is 999888.", $replaced);
    }

    public function test_activating_template_deactivates_siblings(): void
    {
        $this->seed();

        $type = EmailTemplateType::first();
        
        // Create another profile for same type
        $newTemplate = EmailTemplate::create([
            'email_type_id' => $type->id,
            'profile_name' => 'Alternate Template',
            'subject' => 'Alternate Subject',
            'is_active' => false,
        ]);

        $this->assertCount(2, $type->templates);

        // Make the new template active via the Livewire component method logic
        EmailTemplate::where('email_type_id', $type->id)->update(['is_active' => false]);
        $newTemplate->update(['is_active' => true]);

        // Refresh and check active template
        $active = $type->fresh()->activeTemplate();
        $this->assertEquals($newTemplate->id, $active->id);
    }

    public function test_password_reset_uses_dynamic_email_if_active(): void
    {
        $this->seed();

        // Create a user
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'name' => 'Test User',
        ]);

        // Bind flag to enable override in tests
        app()->bind('dynamic_email_test_active', fn() => true);

        Mail::fake();

        // Send reset notification
        $user->sendPasswordResetNotification('fake-token-123');

        // Assert mail was sent through dynamic service
        Mail::assertSent(\App\Mail\DynamicTemplateMail::class, function ($mail) use ($user) {
            return $mail->hasTo($user->email) && str_contains($mail->renderedSubject, 'Reset Password');
        });
    }

    public function test_shipping_confirmation_email_dispatch(): void
    {
        $this->seed();

        $user = User::factory()->create([
            'email' => 'buyer@example.com',
            'name' => 'John Doe',
        ]);

        $order = \App\Models\Order::create([
            'order_external_id' => 'ext-shipped-123',
            'order_user_id' => $user->id,
            'order_status' => 1,
            'order_date' => now(),
            'order_invoice_no' => 'INV-1001',
            'order_subtotal' => 50.00,
            'order_taxes' => 4.13,
            'order_shipping' => 5.00,
            'order_total' => 59.13,
            'order_discounts' => 0.00,
            'order_shipping_method' => 1,
        ]);

        \App\Models\OrderDetail::create([
            'order_id' => $order->id,
            'item_name' => 'Shippable Physical Product',
            'item_qty' => 1,
            'final_price' => 50.00,
            'base_price' => 50.00,
            'discount_price' => 0.00,
            'options_fee' => 0.00,
            'download_item' => false,
        ]);

        $adminUser = User::factory()->create([
            'role_id' => \App\Enums\UserRole::Admin,
        ]);

        $this->actingAs($adminUser);
        Mail::fake();

        // Instantiate Livewire component and trigger shipping
        \Livewire\Livewire::test(\App\Livewire\AdminOrderDetails::class, ['id' => $order->id])
            ->assertStatus(200)
            ->call('markShipped');

        // Assert shipment email was sent
        Mail::assertSent(\App\Mail\DynamicTemplateMail::class, function ($mail) use ($user) {
            return $mail->hasTo($user->email) && 
                   str_contains($mail->renderedSubject, 'Shipment Confirmation') &&
                   str_contains($mail->renderedBody, 'Shippable Physical Product') &&
                   str_contains($mail->renderedBody, 'INV-1001') &&
                   str_contains($mail->renderedBody, 'Total Charged');
        });
    }

    public function test_download_reminder_email_dispatch(): void
    {
        $this->seed();

        $user = User::factory()->create([
            'email' => 'buyer-downloads@example.com',
            'name' => 'Jane Download',
        ]);

        $adminUser = User::factory()->create([
            'role_id' => \App\Enums\UserRole::Admin,
        ]);

        $order = \App\Models\Order::create([
            'order_external_id' => 'ext-downloads-123',
            'order_user_id' => $user->id,
            'order_status' => 1,
            'order_date' => now(),
            'order_invoice_no' => 'INV-1002',
            'order_subtotal' => 30.00,
            'order_taxes' => 2.48,
            'order_shipping' => 0.00,
            'order_total' => 32.48,
            'order_discounts' => 0.00,
            'order_shipping_method' => 2, // Digital download
            'order_download' => 1,
        ]);

        \App\Models\OrderDetail::create([
            'order_id' => $order->id,
            'item_name' => 'Awesome Digital Software E-Book',
            'item_qty' => 1,
            'final_price' => 30.00,
            'base_price' => 30.00,
            'discount_price' => 0.00,
            'options_fee' => 0.00,
            'download_item' => true,
        ]);

        $this->actingAs($adminUser);
        Mail::fake();

        // Instantiate Livewire component and trigger download reminder
        \Livewire\Livewire::test(\App\Livewire\AdminOrderDetails::class, ['id' => $order->id])
            ->assertStatus(200)
            ->call('sendDownloadReminder');

        // Assert download reminder email was sent
        Mail::assertSent(\App\Mail\DynamicTemplateMail::class, function ($mail) use ($user) {
            return $mail->hasTo($user->email) && 
                   str_contains($mail->renderedSubject, 'Download Reminder') &&
                   str_contains($mail->renderedBody, 'Jane Download') &&
                   str_contains($mail->renderedBody, 'Awesome Digital Software E-Book') &&
                   str_contains($mail->renderedBody, 'Download File') &&
                   str_contains($mail->renderedBody, 'Total Charged');
        });
    }

    public function test_abandoned_cart_reminder_checkout_url_uses_app_url(): void
    {
        $this->seed();

        \App\Models\CmsSetting::set('enable_abandoned_cart_reminder_1', true);

        $cart = \App\Models\ShoppingCartLog::create([
            'cart_log_session' => 'session_test_123',
            'user_id' => 0,
            'guest_email' => 'abandoned_test@example.com',
            'item_id' => 1,
            'item_name' => 'Sample Abandoned Item',
            'item_qty' => 1,
            'item_price' => 29.99,
            'item_discount_price' => 0.00,
            'item_shippable' => 0,
            'item_taxable' => 0,
            'item_weight' => 0.0,
            'item_downloadable' => 0,
            'order_id' => 0,
        ]);

        \Illuminate\Support\Facades\DB::table('shopping_cart_log')
            ->where('id', $cart->id)
            ->update(['created_at' => now()->subHours(30)]);

        Mail::fake();

        // Simulate incoming HTTP request on an IP address
        $this->withServerVariables(['HTTP_HOST' => '192.168.1.100']);

        \App\Services\AbandonedCartService::processReminders();

        Mail::assertSent(\App\Mail\DynamicTemplateMail::class, function ($mail) {
            $appUrl = config('app.url');
            return $mail->hasTo('abandoned_test@example.com') &&
                   str_contains($mail->renderedBody, $appUrl . '/cart') &&
                   !str_contains($mail->renderedBody, '192.168.1.100');
        });
    }

    public function test_email_template_image_upload_and_rendering(): void
    {
        $this->seed();

        \Illuminate\Support\Facades\Storage::fake('public');

        $admin = User::factory()->create([
            'role_id' => \App\Enums\UserRole::Admin,
        ]);

        $template = EmailTemplate::first();

        $bannerFile = \Illuminate\Http\UploadedFile::fake()->image('banner.png', 800, 200);
        $footerFile = \Illuminate\Http\UploadedFile::fake()->image('footer.png', 300, 100);

        \Livewire\Livewire::actingAs($admin)
            ->test(\App\Livewire\AdminEmailTemplateEdit::class, ['id' => $template->id])
            ->set('show_banner', true)
            ->set('banner_upload_mode', 'local')
            ->set('banner_file', $bannerFile)
            ->call('uploadBanner')
            ->assertSet('banner_file', null)
            ->assertDispatched('toast')
            ->set('banner_image_link', 'https://example.com/banner-promo')
            ->set('show_footer_image', true)
            ->set('footer_upload_mode', 'local')
            ->set('footer_file', $footerFile)
            ->call('uploadFooterImage')
            ->assertSet('footer_file', null)
            ->assertDispatched('toast')
            ->set('footer_image_link', 'https://example.com/footer-link')
            ->call('save')
            ->assertRedirect(route('admin.email-templates.index'));

        $template->refresh();
        $this->assertTrue($template->show_banner);
        $this->assertNotEmpty($template->banner_image_url);
        $this->assertEquals('https://example.com/banner-promo', $template->banner_image_link);
        $this->assertTrue($template->show_footer_image);
        $this->assertNotEmpty($template->footer_image_url);
        $this->assertEquals('https://example.com/footer-link', $template->footer_image_link);

        // Verify HTML rendering contains images and clickable links
        $renderedHtml = EmailTemplateService::renderBody($template, ['customer_name' => 'John Doe']);
        $this->assertStringContainsString($template->banner_image_url, $renderedHtml);
        $this->assertStringContainsString('https://example.com/banner-promo', $renderedHtml);
        $this->assertStringContainsString($template->footer_image_url, $renderedHtml);
        $this->assertStringContainsString('https://example.com/footer-link', $renderedHtml);
    }

    public function test_email_template_clear_image(): void
    {
        $this->seed();

        $admin = User::factory()->create([
            'role_id' => \App\Enums\UserRole::Admin,
        ]);

        $template = EmailTemplate::first();
        $template->update([
            'banner_image_url' => 'https://example.com/banner.jpg',
            'footer_image_url' => 'https://example.com/footer.png',
        ]);

        \Livewire\Livewire::actingAs($admin)
            ->test(\App\Livewire\AdminEmailTemplateEdit::class, ['id' => $template->id])
            ->assertSet('banner_image_url', 'https://example.com/banner.jpg')
            ->call('clearBannerImage')
            ->assertSet('banner_image_url', null)
            ->assertSet('banner_file', null)
            ->assertDispatched('toast')
            ->call('clearFooterImage')
            ->assertSet('footer_image_url', null)
            ->assertSet('footer_file', null)
            ->assertDispatched('toast');
    }

    public function test_email_template_cloudfront_cdn_image_resolution(): void
    {
        $this->seed();

        // Set CloudFront / CDN URL in config/env
        $_ENV['CDN_URL'] = 'https://cdn.example.com';
        $_SERVER['CDN_URL'] = 'https://cdn.example.com';
        putenv('CDN_URL=https://cdn.example.com');
        config(['filesystems.disks.s3.url' => 'https://cdn.example.com']);

        $s3Url1 = 'https://my-bucket.s3.us-east-2.amazonaws.com/email_templates/banners/hero.jpg';
        $s3Url2 = 'https://s3.us-east-2.amazonaws.com/my-bucket/email_templates/footers/logo.png';

        $this->assertEquals(
            'https://cdn.example.com/email_templates/banners/hero.jpg',
            EmailTemplateService::resolveImageUrl($s3Url1)
        );

        $this->assertEquals(
            'https://cdn.example.com/email_templates/footers/logo.png',
            EmailTemplateService::resolveImageUrl($s3Url2)
        );

        $template = EmailTemplate::first();
        $template->update([
            'banner_image_url' => $s3Url1,
            'footer_image_url' => $s3Url2,
            'show_banner' => 1,
            'show_footer_image' => 1,
        ]);

        $rendered = EmailTemplateService::renderBody($template, ['customer_name' => 'Jane']);
        $this->assertStringContainsString('https://cdn.example.com/email_templates/banners/hero.jpg', $rendered);
        $this->assertStringContainsString('https://cdn.example.com/email_templates/footers/logo.png', $rendered);
        $this->assertStringNotContainsString('my-bucket.s3.us-east-2.amazonaws.com', $rendered);
    }
}
