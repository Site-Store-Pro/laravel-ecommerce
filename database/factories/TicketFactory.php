<?php

namespace Database\Factories;

use App\Enums\TicketStatus;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Ticket>
 */
class TicketFactory extends Factory
{
    protected $model = Ticket::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'assigned_to' => null,
            'title' => fake()->sentence(6),
            'description' => fake()->paragraphs(2, true),
            'status' => fake()->randomElement(TicketStatus::cases()),
            'token' => (string) Str::uuid(),
            'reply_token' => Str::random(32),
        ];
    }
}
