<?php

namespace Database\Factories;

use App\Models\Department;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TicketFactory extends Factory
{
    public function definition(): array
    {
        // We will remove user_id and team_id from here,
        // as they will be handled by our smart configuration.

        $user = User::factory()->withPersonalTeam()->create();
        return [
            'user_id' => $user->id,
            'team_id' => $user->currentTeam,
            'department_id' => Department::factory(),
            'title' => fake()->sentence(),
            'content' => fake()->paragraph(3, true),
            'status' => fake()->randomElement(['open', 'in_progress', 'closed']),
            'priority' => fake()->randomElement(['low', 'medium', 'high']),
            'category' => fake()->randomElement(['billing', 'technical']),
        ];
    }

    /**
     * Configure the model factory.
     * This is the key to linking our models correctly.
     */
    public function configure(): static
    {
        return $this->afterCreating(function (\App\Models\Ticket $ticket) {
            // This closure runs AFTER a ticket has been created and saved to the DB.

            // If the ticket doesn't have a user, we create one.
            if (! $ticket->user_id) {
                // We create a user with a personal team, which is the Jetstream standard.
                $user = User::factory()->withPersonalTeam()->create();
                $ticket->user_id = $user->id;
            }

            // Now, we ensure the ticket's team_id matches its user's team.
            // $ticket->user is available because we just set the user_id.
            if (! $ticket->team_id) {
                // Get the user who owns this ticket and assign the ticket
                // to that user's *current* team.
                $ticket->team_id = $ticket->user->current_team_id;
            }

            // Save the changes to the ticket model.
            $ticket->save();
        });
    }
}