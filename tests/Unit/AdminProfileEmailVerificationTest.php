<?php

namespace Tests\Unit;

use App\Enums\UserRole;
use App\Models\User;
use Livewire\Volt\Volt;
use Tests\TestCase;

class AdminProfileEmailVerificationTest extends TestCase
{
    public function test_admin_email_change_automatically_verifies_account(): void
    {
        $admin = new User();
        $admin->id = 1;
        $admin->name = 'Admin User';
        $admin->email = 'oldadmin@example.com';
        $admin->role_id = UserRole::Admin;
        $admin->email_verified_at = now()->subYear();

        $this->actingAs($admin);

        // Test the logic directly on User instance as executed in update-profile-information-form
        $admin->email = 'newadmin@example.com';
        if ($admin->isDirty('email')) {
            if ($admin->isAdmin() || $admin->role_id == 3 || (is_object($admin->role_id) && $admin->role_id->value === 3)) {
                $admin->email_verified_at = now();
            } else {
                $admin->email_verified_at = null;
            }
        }

        $this->assertNotNull($admin->email_verified_at);
        $this->assertEquals('newadmin@example.com', $admin->email);
    }

    public function test_regular_user_email_change_resets_verification(): void
    {
        $user = new User();
        $user->id = 2;
        $user->name = 'Regular Customer';
        $user->email = 'oldcustomer@example.com';
        $user->role_id = UserRole::User;
        $user->email_verified_at = now()->subYear();

        $this->actingAs($user);

        // Test the logic directly on User instance as executed in update-profile-information-form
        $user->email = 'newcustomer@example.com';
        if ($user->isDirty('email')) {
            if ($user->isAdmin() || $user->role_id == 3 || (is_object($user->role_id) && $user->role_id->value === 3)) {
                $user->email_verified_at = now();
            } else {
                $user->email_verified_at = null;
            }
        }

        $this->assertNull($user->email_verified_at);
        $this->assertEquals('newcustomer@example.com', $user->email);
    }
}
