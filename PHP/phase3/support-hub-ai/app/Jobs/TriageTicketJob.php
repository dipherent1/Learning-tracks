<?php

namespace App\Jobs;

use App\AiAgents\TriageAgent;
use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class TriageTicketJob implements ShouldQueue // <-- This interface is crucial
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public Ticket $ticket)
    {
    }

    public function handle(): void
    {
        
        echo "TriageTicketJob is processing ticket ID: {$this->ticket->id}\n";
        // // This is the code the worker will execute in the background.
        // $agent = resolve(TriageAgent::class, ['ticket' => $this->ticket]);
        // $response = $agent->run();

        // // The response should be a JSON object, so we decode it.
        // $triageData = json_decode($response, true);

        // Update the ticket with the AI's analysis.
        $this->ticket->update([
            'priority' => 'low',
            'category' => 'general',
        ]);
    }
}