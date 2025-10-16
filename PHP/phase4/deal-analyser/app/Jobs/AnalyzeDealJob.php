<?php

namespace App\Jobs;
use Illuminate\Support\Facades\Log;

use App\AiAgents\DealAgent;
use App\Models\Deal;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Storage; 
class AnalyzeDealJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(protected Deal $deal)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //
        $images = [
            Storage::url($this->deal->image_path),
        ];


        try {
            $agent = DealAgent::for('test');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }

        try {
            $response = $agent->withImages($images)->respond('tell me what you see');
            // $response = $agent->respond('tell me a joke about deals');
            dump($response);
            Log::info($response);

        } catch (\Exception $e) {
            dump('error in response');
            Log::error($e);
        }
        //log the response
    }
}
