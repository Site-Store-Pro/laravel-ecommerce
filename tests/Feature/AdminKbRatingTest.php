<?php

namespace Tests\Feature;

use App\Models\KbArticle;
use App\Models\User;
use App\Enums\UserRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class AdminKbRatingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed', ['--class' => 'Database\\Seeders\\DatabaseSeeder']);
    }

    public function test_admin_can_view_and_edit_kb_rating_on_edit_page(): void
    {
        $admin = User::where('role_id', UserRole::Admin->value)->first();
        $article = KbArticle::first();

        // Admin can access the edit page and see it successfully
        $response = $this->actingAs($admin)->get("/admin/kb/{$article->id}/edit");
        $response->assertOk();

        // Verify we can modify kb_rating via Livewire AdminKbEdit component
        Livewire::actingAs($admin)
            ->test(\App\Livewire\AdminKbEdit::class, ['article' => $article])
            ->assertSet('form.kb_rating', $article->kb_rating)
            ->set('form.kb_rating', 42)
            ->call('save');

        $this->assertDatabaseHas('kb_articles', [
            'id' => $article->id,
            'kb_rating' => 42,
        ]);
    }

    public function test_admin_can_set_kb_rating_on_create_page(): void
    {
        $admin = User::where('role_id', UserRole::Admin->value)->first();

        // Verify we can create an article and set the initial kb_rating
        Livewire::actingAs($admin)
            ->test(\App\Livewire\AdminKbCreate::class)
            ->set('form.title', 'New Guide')
            ->set('form.seo_link', 'new-guide')
            ->set('form.article_content', 'Some helpful content')
            ->set('form.kb_rating', 99)
            ->call('save');

        $this->assertDatabaseHas('kb_articles', [
            'title' => 'New Guide',
            'kb_rating' => 99,
        ]);
    }

    public function test_non_admin_cannot_access_or_edit_kb_rating(): void
    {
        $nonAdmin = User::factory()->create(['role_id' => UserRole::Wholesale->value]);
        $article = KbArticle::first();

        // Non-admin users cannot access the edit page
        $response = $this->actingAs($nonAdmin)->get("/admin/kb/{$article->id}/edit");
        $response->assertStatus(403);
    }
}
