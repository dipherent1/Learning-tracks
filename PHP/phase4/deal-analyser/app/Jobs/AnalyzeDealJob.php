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
use Illuminate\Support\Facades\Storage; 

class AnalyzeDealJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(protected Deal $deal)
    {
    }

    public function handle(): void
    {
        Log::info('AnalyzeDealJob started', [
            'deal_id' => $this->deal->id,
        ]);

        // Step 1: Check if there is an image to process
        $company = $this->deal->company;

         $prompt = <<<PROMPT
        I need you to perform a comprehensive analysis of the following business deal.

        **CONTEXT 1: DEAL INFORMATION FROM FORM**
        - Title: {$this->deal->title}
        - Description: {$this->deal->description}
        - Estimated Value: \${$this->deal->value_estimate}
        - Estimated Duration: {$this->deal->duration_months} months
        
        **CONTEXT 2: COMPANY PROFILE**
        - Company ID: {$company->id}

        **CONTEXT 3: ATTACHED IMAGE**
        Analyze the attached image for any additional context. It might show a product, a location, a document, or people involved. Extract any relevant information.

        **YOUR TASK:**
        Based on ALL the context provided (the deal data, the company's profile, and the image), your task is to:
        1. Identify potential financial, operational, or reputational risks.
        2. Propose a clear mitigation strategy for each risk.
        3. Identify any other companies or entities (third parties) mentioned or implied in the deal.

        PROMPT;

        $imagePayload = null;
        if ($this->deal->image_path) {
            $imageContents = Storage::disk('public')->get($this->deal->image_path);
            $mimeType = Storage::disk('public')->mimeType($this->deal->image_path);
            $base64Image = base64_encode($imageContents);
            $imagePayload = ["data:{$mimeType};base64,{$base64Image}"];
        }


        try {
            $agent = DealAgent::for($this->deal->id);

            if ($imagePayload) {
                $agent->withImages($imagePayload);
            }

            $response = $agent->withImages($imagePayload)
                              ->respond($prompt);

            Log::info("AI Response for Deal ID {$this->deal->id}: " . $response);

            Log::info('AnalyzeDealJob completed', [
                'deal_id' => $this->deal->id,
            ]);

        } catch (\Exception $e) {
            Log::error("Failed to analyze deal image for Deal ID: {$this->deal->id}", [
                'error_message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }
}