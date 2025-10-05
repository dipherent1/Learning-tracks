<?php

namespace Database\Seeders;

use App\Models\Reply;
use App\Models\Ticket;
use Illuminate\Database\Seeder;

class ReplySeeder extends Seeder
{
    public function run(): void
    {
        // Get all the tickets that were created in the TicketSeeder.
        $tickets = Ticket::all();

        // Let's add 15 replies in total, each to a random ticket.
        for ($i = 0; $i < 15; $i++) {
            // 1. Get a random ticket from the collection.
            $ticket = $tickets->random();

            // 2. Get a random user who is a member of that ticket's team.
            $user = $ticket->user_id;

            // 3. Use the ReplyFactory to create ONE reply with the correct data.
            Reply::factory()->create([
                'ticket_id' => $ticket->id,
                'user_id' => $user,
            ]);
        }
    }
}