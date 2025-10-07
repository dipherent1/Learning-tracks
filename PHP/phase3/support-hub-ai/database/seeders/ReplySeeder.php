<?php

namespace Database\Seeders;

use App\Models\Reply;
use App\Models\Ticket;
use Illuminate\Database\Seeder;

class ReplySeeder extends Seeder
{
    public function run(): void
    {
        $tickets = Ticket::all();

        if ($tickets->isEmpty()) {
            return;
        }

        // Add a total of 50 replies across all tickets
        for ($i = 0; $i < 50; $i++) {
            $ticket = $tickets->random();

            // This now works, because the UserFactory guarantees the team has members.
            $user = $ticket->team->users->random();

            Reply::factory()->create([
                'ticket_id' => $ticket->id,
                'user_id' => $user->id,
            ]);
        }
    }
}