<?php

namespace Tests\Feature;

use App\Models\User;
use App\Enums\UserRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CmsImageUploadTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_upload_image_successfully(): void
    {
        Storage::fake('public');

        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@support.local',
            'password' => bcrypt('password'),
            'role_id' => UserRole::Admin,
            'email_verified_at' => now(),
        ]);

        $file = UploadedFile::fake()->image('photo.jpg', 800, 600);

        $response = $this->actingAs($admin)
            ->postJson('/admin/cms-pages/upload-image', [
                'file' => $file,
            ]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['location']);
        
        $location = $response->json('location');
        $this->assertStringStartsWith('/storage/cms_inline/', $location);
        
        // Assert file exists on the fake public storage
        $storedPath = str_replace('/storage/', '', $location);
        Storage::disk('public')->assertExists($storedPath);
    }

    public function test_non_admin_cannot_upload_image(): void
    {
        $user = User::create([
            'name' => 'Regular User',
            'email' => 'user@example.com',
            'password' => bcrypt('password'),
            'role_id' => UserRole::User,
            'email_verified_at' => now(),
        ]);

        $file = UploadedFile::fake()->image('photo.jpg');

        $response = $this->actingAs($user)
            ->postJson('/admin/cms-pages/upload-image', [
                'file' => $file,
            ]);

        $response->assertStatus(403);
    }

    public function test_guest_cannot_upload_image(): void
    {
        $file = UploadedFile::fake()->image('photo.jpg');

        $response = $this->post('/admin/cms-pages/upload-image', [
            'file' => $file,
        ]);

        $response->assertRedirect('/login');
    }

    public function test_order_processor_can_upload_image_successfully(): void
    {
        Storage::fake('public');

        $processor = User::create([
            'name' => 'Order Processor User',
            'email' => 'processor@example.com',
            'password' => bcrypt('password'),
            'role_id' => UserRole::OrderProcessor,
            'email_verified_at' => now(),
        ]);

        $file = UploadedFile::fake()->image('photo.jpg', 800, 600);

        $response = $this->actingAs($processor)
            ->postJson('/admin/cms-pages/upload-image', [
                'file' => $file,
            ]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['location']);
        
        $location = $response->json('location');
        $this->assertStringStartsWith('/storage/cms_inline/', $location);
        
        $storedPath = str_replace('/storage/', '', $location);
        Storage::disk('public')->assertExists($storedPath);
    }
}
