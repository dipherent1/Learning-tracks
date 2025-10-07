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

            return [
            'user_id' => User::factory()->withPersonalTeam(),
            'team_id' => null,
            'department_id' => Department::factory(),
            'title' => fake()->sentence(),
            'content' => fake()->paragraph(3, true),
            'status' => fake()->randomElement(['open', 'in_progress', 'closed']),
            'priority' => fake()->randomElement(['low', 'medium', 'high']),
            'category' => fake()->randomElement(['billing', 'technical']),
        ];
    }

    
   
}