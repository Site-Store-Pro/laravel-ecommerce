<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class AdminModalEditTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_modal_edit_renders_with_single_root_element(): void
    {
        DB::table('user_roles')->insertOrIgnore(['id' => 3, 'name' => 'Admin']);

        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin_modal@example.com',
            'password' => bcrypt('password'),
            'role_id' => 3,
        ]);

        $this->actingAs($admin);

        Livewire::test(\App\Livewire\AdminModalEdit::class)
            ->assertStatus(200);
    }
}
