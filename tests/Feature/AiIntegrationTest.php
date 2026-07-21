<?php

namespace Tests\Feature;

use App\Models\Ticket;
use App\Models\User;
use App\Enums\UserRole;
use App\Models\KbArticle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class AiIntegrationTest extends TestCase
{
    use RefreshDatabase;

    private bool $tempFileCreated = false;

    protected function setUp(): void
    {
        parent::setUp();
        // Seed default roles to database since they are required for foreign key constraints
        $this->artisan('db:seed', ['--class' => 'Database\\Seeders\\DatabaseSeeder']);

        // Write temporary ticket response helper if missing so AppServiceProvider loads it
        $ticketPath = app_path('Includes/ai_ticket_response.php');
        if (!file_exists($ticketPath)) {
            if (!is_dir(dirname($ticketPath))) {
                mkdir(dirname($ticketPath), 0755, true);
            }
            file_put_contents($ticketPath, '<?php function ai_ticket_response($text) { return "This is a dummy AI ticket response. Received input:\n\n" . $text; }');
            $this->tempFileCreated = true;
            
            // Re-boot AppServiceProvider manually to load the newly created file
            (new \App\Providers\AppServiceProvider($this->app))->boot();
        }
    }

    protected function tearDown(): void
    {
        if ($this->tempFileCreated) {
            $ticketPath = app_path('Includes/ai_ticket_response.php');
            if (file_exists($ticketPath)) {
                unlink($ticketPath);
            }
        }
        parent::tearDown();
    }

    public function test_ai_features_not_visible_when_required_config_is_null(): void
    {
        $admin = User::factory()->create(['role_id' => UserRole::Admin->value]);
        $ticket = Ticket::factory()->create();

        // 1. When AI_PROVIDER is null, Ticket Reply button should be false
        config([
            'ai.ai_provider' => null,
            'ai.openai_api_key' => 'test_key',
        ]);
        Livewire::actingAs($admin)
            ->test(\App\Livewire\AdminTicketShow::class, ['ticket' => $ticket])
            ->assertViewHas('showAiButton', false);

        // 2. When OPENAI_API_KEY is null, KB Article button should be false
        config([
            'ai.ai_provider' => 'test_provider',
            'ai.openai_api_key' => null,
        ]);
        Livewire::actingAs($admin)
            ->test(\App\Livewire\AdminKbCreate::class)
            ->assertViewHas('showAiButton', false);
    }

    public function test_ai_features_visible_and_functional_when_required_config_is_set_and_helpers_exist(): void
    {
        // Set configuration variables
        config([
            'ai.ai_provider' => 'gemini',
            'ai.openai_api_key' => 'test_openai_key',
        ]);
        
        $admin = User::factory()->create(['role_id' => UserRole::Admin->value]);
        $ticket = Ticket::factory()->create(['description' => 'Help me please']);

        Livewire::actingAs($admin)
            ->test(\App\Livewire\AdminTicketShow::class, ['ticket' => $ticket])
            ->assertViewHas('showAiButton', true)
            ->call('generateAiResponse')
            ->assertSet('aiResponse', "This is a dummy AI ticket response. Received input:\n\nHelp me please")
            ->call('copyAiResponse')
            ->assertSet('body', "This is a dummy AI ticket response. Received input:\n\nHelp me please");

        Livewire::actingAs($admin)
            ->test(\App\Livewire\AdminKbCreate::class)
            ->assertViewHas('showAiButton', true)
            ->assertSet('aiPrompt', 'Please rewrite this kb article to be SEO optimized and concise')
            ->set('form.article_content', '')
            ->call('generateAiContent')
            ->assertHasErrors(['ai_content_error' => 'Please write some content in the KB article editor first.'])
            ->set('form.article_content', 'Creating new KB content')
            ->call('generateAiContent')
            ->assertHasNoErrors('ai_content_error')
            ->assertSet('aiResponse', "This is placeholder AI content for the KB article using prompt: 'Please rewrite this kb article to be SEO optimized and concise' based on:\n\nCreating new KB content")
            ->set('aiPrompt', 'Write a joke about programming')
            ->call('generateAiContent')
            ->assertSet('aiResponse', "This is placeholder AI content for the KB article using prompt: 'Write a joke about programming' based on:\n\nCreating new KB content");
    }
}
