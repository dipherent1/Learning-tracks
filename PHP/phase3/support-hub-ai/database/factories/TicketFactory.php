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
            $user = $ticket->user; // Get the user associated with the ticket

            // If the ticket was created without a user, create one.
            if (! $user) {
                // Create a user with their personal team.
                $user = User::factory()->withPersonalTeam()->create();
                $ticket->user_id = $user->id;
            }
            
            $team = $user->currentTeam;

            // ** THE CRITICAL FIX IS HERE **
            // Ensure the user is actually a member of the team in the pivot table.
            if (! $user->belongsToTeam($team)) {
                $user->teams()->attach($team, ['role' => 'editor']); // Use Jetstream's role system
                $user->switchTeam($team);
            }
            
            // Assign the ticket to the user's team.
            $ticket->team_id = $team->id;
            
            // Save all the changes.
            $ticket->save();
        });
    }
}