<?php

namespace App\Listeners;

use App\Events\TicketCreated;
use App\Jobs\TriageTicketJob;

class QueueTicketTriage
{
    public function handle(TicketCreated $event): void
    {
        // Take the ticket from the event and dispatch it to the queue as a new job.
        TriageTicketJob::dispatch($event->ticket);
    }
}