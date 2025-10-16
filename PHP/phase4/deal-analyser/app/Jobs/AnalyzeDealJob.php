<?php

namespace App\Jobs;

use App\AiAgents\DealAgent;
use App\Models\Deal;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage; // <-- Make sure Storage is imported

class AnalyzeDealJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(protected Deal $deal)
    {
    }

    public function handle(): void
    {
        // Step 1: Check if there is an image to process
        if (!$this->deal->image_path) {
            Log::info("Job skipped: No image path for Deal ID {$this->deal->id}");
            return; // Exit the job successfully
        }

        // Step 2: Read the raw binary content of the image file from storage
        // We must use the 'public' disk since that's where the file was stored.
        $imageContents = Storage::disk('public')->get($this->deal->image_path);

        // Step 3: Get the correct MIME type (e.g., 'image/png', 'image/jpeg')
        // This is important for the Data URI to be valid.
        $mimeType = Storage::disk('public')->mimeType($this->deal->image_path);

        // Step 4: Encode the image content into a Base64 string
        $base64Image = base64_encode($imageContents);

        // Step 5: Construct the full Data URI string
        $imageDataUri = "data:{$mimeType};base64,{$base64Image}";

        // We wrap it in an array as expected by withImages()
        $imagePayload = [$imageDataUri];

        try {
            $agent = DealAgent::for('deal-analysis-session');

            $response = $agent->withImages($imagePayload)
                              ->respond('Analyze this image in the context of a business deal. Describe what you see, identify any key objects, brands, or concepts shown that might be relevant.');
            
            Log::info("AI Response for Deal ID {$this->deal->id}: " . $response);

        } catch (\Exception $e) {
            Log::error("Failed to analyze deal image for Deal ID: {$this->deal->id}", [
                'error_message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }
}