<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class AdminNavBarTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_nav_bar_links_sort_order(): void
    {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@support.local',
            'password' => bcrypt('password'),
            'role_id' => 3, // Admin
            'email_verified_at' => now(),
        ]);

        $this->actingAs($admin);

        // Test layout.navigation rendering
        $component = Livewire::test('layout.navigation');

        // Capture HTML output
        $html = $component->html();

        // Verify that the main admin desktop links appear in the requested sort order
        $dashboardPos = strpos($html, 'Dashboard');
        $customersPos = strpos($html, 'Customers');
        $ordersPos = strpos($html, 'Orders');
        $productsPos = strpos($html, 'Products');
        $discountsPos = strpos($html, 'Discounts');
        $shippingPos = strpos($html, 'Shipping');
        $emailsPos = strpos($html, 'Emails');
        $cmsPos = strpos($html, 'CMS');
        $kbDocsPos = strpos($html, 'KB Docs');
        $settingsPos = strpos($html, 'Settings');

        // Assert all exist
        $this->assertNotFalse($dashboardPos, 'Dashboard not found');
        $this->assertNotFalse($customersPos, 'Customers not found');
        $this->assertNotFalse($ordersPos, 'Orders not found');
        $this->assertNotFalse($productsPos, 'Products not found');
        $this->assertNotFalse($discountsPos, 'Discounts not found');
        $this->assertNotFalse($shippingPos, 'Shipping not found');
        $this->assertNotFalse($emailsPos, 'Emails not found');
        $this->assertNotFalse($cmsPos, 'CMS not found');
        $this->assertNotFalse($kbDocsPos, 'KB Docs not found');
        $this->assertNotFalse($settingsPos, 'Settings not found');

        // Assert sorting order (each must be positioned after the previous)
        $this->assertTrue($dashboardPos < $customersPos, 'Dashboard should be before Customers');
        $this->assertTrue($customersPos < $ordersPos, 'Customers should be before Orders');
        $this->assertTrue($ordersPos < $productsPos, 'Orders should be before Products');
        $this->assertTrue($productsPos < $discountsPos, 'Products should be before Discounts');
        $this->assertTrue($discountsPos < $shippingPos, 'Discounts should be before Shipping');
        $this->assertTrue($shippingPos < $emailsPos, 'Shipping should be before Emails');
        $this->assertTrue($emailsPos < $cmsPos, 'Emails should be before CMS');
        $this->assertTrue($cmsPos < $kbDocsPos, 'CMS should be before KB Docs');
        $this->assertTrue($kbDocsPos < $settingsPos, 'KB Docs should be before Settings');
    }
}
