<?php

namespace App\Listeners;

use App\Events\DealStored;
use App\Jobs\AnalyzeDealJob;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ProcessDealWithAI
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(DealStored $event): void
    {
        AnalyzeDealJob::dispatch($event->deal);

        //
    }
}
