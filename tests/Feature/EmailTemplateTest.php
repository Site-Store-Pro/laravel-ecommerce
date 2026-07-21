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

        // Verify we have all 10 types
        $this->assertEquals(10, EmailTemplateType::count());

        // Verify we have seeded default profiles
        $this->assertEquals(10, EmailTemplate::count());

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
}
