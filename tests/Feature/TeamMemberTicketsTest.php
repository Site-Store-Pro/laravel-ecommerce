<?php

namespace Tests\Feature;

use App\Models\Ticket;
use App\Models\User;
use App\Enums\UserRole;
use App\Enums\TicketStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class TeamMemberTicketsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Seed default roles to database since they are required for foreign key constraints
        $this->artisan('db:seed', ['--class' => 'Database\\Seeders\\DatabaseSeeder']);
    }

    public function test_admin_can_access_admin_dashboard_and_delete_tickets(): void
    {
        $admin = User::factory()->create(['role_id' => UserRole::Admin->value]);
        $ticket = Ticket::factory()->create();

        $response = $this->actingAs($admin)->get('/admin/tickets');
        $response->assertOk();

        // Admin can delete ticket via Livewire AdminDashboard
        Livewire::actingAs($admin)
            ->test(\App\Livewire\AdminDashboard::class)
            ->call('deleteTicket', $ticket->id);

        $this->assertDatabaseMissing('tickets', ['id' => $ticket->id]);
    }

    public function test_non_ticket_manager_cannot_access_admin_tickets(): void
    {
        $wholesale = User::factory()->create(['role_id' => UserRole::Wholesale->value]);

        $response = $this->actingAs($wholesale)->get('/admin/tickets');
        $response->assertStatus(403);
    }

    public function test_ticket_manager_can_access_admin_dashboard(): void
    {
        $manager = User::factory()->create(['role_id' => UserRole::TicketManager->value]);

        $response = $this->actingAs($manager)->get('/admin/tickets');
        $response->assertOk();
    }

    public function test_ticket_manager_can_access_assigned_tickets_dashboard(): void
    {
        $manager = User::factory()->create(['role_id' => UserRole::TicketManager->value]);
        $ticket = Ticket::factory()->create(['assigned_to' => $manager->id]);

        $response = $this->actingAs($manager)->get('/admin/assigned-tickets');
        $response->assertOk()
            ->assertSee($ticket->title);
    }

    public function test_ticket_manager_can_view_and_reply_to_ticket(): void
    {
        $manager = User::factory()->create(['role_id' => UserRole::TicketManager->value]);
        $ticket = Ticket::factory()->create();

        $response = $this->actingAs($manager)->get("/admin/tickets/{$ticket->id}");
        $response->assertOk();

        // Ticket manager can post reply
        Livewire::actingAs($manager)
            ->test(\App\Livewire\AdminTicketShow::class, ['ticket' => $ticket])
            ->set('body', 'This is a ticket manager reply')
            ->call('reply');

        $this->assertDatabaseHas('ticket_replies', [
            'ticket_id' => $ticket->id,
            'user_id' => $manager->id,
            'body' => 'This is a ticket manager reply',
        ]);
    }

    public function test_ticket_manager_cannot_delete_tickets(): void
    {
        $manager = User::factory()->create(['role_id' => UserRole::TicketManager->value]);
        $ticket = Ticket::factory()->create(['assigned_to' => $manager->id]);

        // Attempting to delete via AdminTicketShow component should abort
        Livewire::actingAs($manager)
            ->test(\App\Livewire\AdminTicketShow::class, ['ticket' => $ticket])
            ->call('deleteTicket')
            ->assertStatus(403);

        $this->assertDatabaseHas('tickets', ['id' => $ticket->id]);
    }

    public function test_reassignment_redirects_manager_to_dashboard(): void
    {
        $manager = User::factory()->create(['role_id' => UserRole::TicketManager->value]);
        $otherManager = User::factory()->create(['role_id' => UserRole::TicketManager->value]);
        $ticket = Ticket::factory()->create(['assigned_to' => $manager->id]);

        Livewire::actingAs($manager)
            ->test(\App\Livewire\AdminTicketShow::class, ['ticket' => $ticket])
            ->set('assigned_to', $otherManager->id)
            ->call('updateStatus')
            ->assertRedirect(route('admin.assigned-tickets'));
    }
}
