<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Seeder;

class TicketSeeder extends Seeder
{
    public function run(): void
    {
        $departments = Department::all();
        $users = User::all(); // Get all users created in DatabaseSeeder

        // Create 20 tickets, each assigned to a random user and their team.
        for ($i = 0; $i < 20; $i++) {
            $user = $users->random();
            
            Ticket::factory()->create([
                'user_id' => $user->id,
                'team_id' => $user->current_team_id, // Assign to user's current team
                'department_id' => $departments->random()->id,
            ]);
        }
    }
}