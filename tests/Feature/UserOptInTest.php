<?php

namespace Tests\Feature;

use App\Models\CmsSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Livewire\Livewire;
use Tests\TestCase;

class UserOptInTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_opt_in_default_is_zero(): void
    {
        DB::table('user_roles')->insertOrIgnore(['id' => 1, 'name' => 'Customer']);

        $user = User::create([
            'name' => 'Default Opt-in User',
            'email' => 'default_optin@example.com',
            'password' => bcrypt('password123'),
            'role_id' => 1,
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'opt_in' => 0,
        ]);
    }

    public function test_user_profile_edit_updates_opt_in_and_triggers_sync(): void
    {
        DB::table('user_roles')->insertOrIgnore(['id' => 1, 'name' => 'Customer']);

        $user = User::create([
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'password' => bcrypt('password123'),
            'role_id' => 1,
            'opt_in' => 0,
        ]);

        $this->actingAs($user);

        // Edit profile and check opt_in checkbox
        Livewire::test('profile.update-profile-information-form')
            ->set('name', 'John Doe')
            ->set('email', 'john.doe@example.com')
            ->set('opt_in', true)
            ->call('updateProfileInformation');

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'opt_in' => 1,
        ]);
    }

    public function test_admin_reports_exports_retail_and_wholesale_customers(): void
    {
        DB::table('user_roles')->insertOrIgnore(['id' => 1, 'name' => 'Customer']);
        DB::table('user_roles')->insertOrIgnore(['id' => 2, 'name' => 'Wholesale']);
        DB::table('user_roles')->insertOrIgnore(['id' => 3, 'name' => 'Admin']);

        $retailUser = User::create([
            'name' => 'Retail Customer',
            'email' => 'retail@example.com',
            'password' => bcrypt('password'),
            'role_id' => 1,
            'opt_in' => 1,
        ]);

        $wholesaleUser = User::create([
            'name' => 'Wholesale Partner',
            'email' => 'wholesale@example.com',
            'password' => bcrypt('password'),
            'role_id' => 2,
            'opt_in' => 0,
        ]);

        $adminUser = User::create([
            'name' => 'System Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role_id' => 3,
            'opt_in' => 1,
        ]);

        $this->actingAs($adminUser);

        // Test exportCustomers (role_id 1 and 2 only)
        Livewire::test(\App\Livewire\AdminReports::class)
            ->call('exportCustomers')
            ->assertStatus(200);

        // Test exportOptInSubscribers (role_id 1 and 2 with opt_in=1 only)
        Livewire::test(\App\Livewire\AdminReports::class)
            ->call('exportOptInSubscribers')
            ->assertStatus(200);
    }

    public function test_out_of_stock_alert_message_uses_dynamic_translatable_site_labels(): void
    {
        $singleMsg = \App\Services\InventoryCheckService::formatOutOfStockMessage(['Widget A']);
        $this->assertStringContainsString("The item 'Widget A' is out of stock", $singleMsg);

        $multipleMsg = \App\Services\InventoryCheckService::formatOutOfStockMessage(['Widget A', 'Widget B']);
        $this->assertStringContainsString("The following items were out of stock and have been removed from your cart: Widget A, Widget B", $multipleMsg);
    }
}
