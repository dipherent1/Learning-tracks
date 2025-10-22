<?php

namespace App\Jobs;

use App\AiAgents\DealAgent;
use App\Models\Deal;
use App\Models\User;
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

    public function __construct(protected User $user, protected Deal $deal)
    {
    }

    public function handle(): void
    {
        Log::info('AnalyzeDealJob started', [
            'deal_id' => $this->deal->id,
        ]);

        $prompt = "Analyze the following business deal image.";
    
        $imagePayload = null;
        if ($this->deal->image_path) {
            $imageContents = Storage::disk('public')->get($this->deal->image_path);
            $mimeType = Storage::disk('public')->mimeType($this->deal->image_path);
            $base64Image = base64_encode($imageContents);
            $imagePayload = ["data:{$mimeType};base64,{$base64Image}"];
        }


        try {
            Log::info("Preparing to analyze deal image for Deal ID: {$this->deal->id} for user ID: {$this->user->id}");
            $agent = DealAgent::for($this->user->id . '-' . $this->deal->id);
            
            $response = $agent->withImages($imagePayload)
                              ->respond($prompt);

            Log::info('AI response received', [
                'deal_id' => $this->deal->id,
                'response' => $response,
            ]);

            $payload = $this->decodeResponse($response);

            if ($payload) {
                $updates = $this->mapAgentResponse($payload);

                if (! empty($updates)) {
                    $this->deal->fill($updates);
                    $this->deal->save();

                    Log::info('Deal updated from AI analysis', [
                        'deal_id' => $this->deal->id,
                        'updated_fields' => array_keys($updates),
                    ]);
                } else {
                    Log::warning('AI response contained no mappable fields', [
                        'deal_id' => $this->deal->id,
                    ]);
                }
            } else {
                Log::warning('Unable to decode AI response', [
                    'deal_id' => $this->deal->id,
                ]);
            }

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

    private function decodeResponse(mixed $response): ?array
    {
        if (is_array($response)) {
            return $response;
        }

        if (is_string($response)) {
            $decoded = json_decode($response, true);

            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                return $decoded;
            }
        }

        return null;
    }

    private function mapAgentResponse(array $payload): array
    {
        $updates = [];

        if (! empty($payload['title'])) {
            $updates['title'] = $payload['title'];
        }

        if (! empty($payload['description'])) {
            $updates['description'] = $payload['description'];
        }

        if (! empty($payload['valueEstimate'])) {
            $value = $this->parseDecimal($payload['valueEstimate']);

            if ($value !== null) {
                $updates['value_estimate'] = $value;
            }
        }

        if (! empty($payload['duration'])) {
            $duration = $this->parseInteger($payload['duration']);

            if ($duration !== null) {
                $updates['duration_months'] = $duration;
            }
        }

        return $updates;
    }

    private function parseDecimal(mixed $value): ?float
    {
        if (is_null($value)) {
            return null;
        }

        if (is_numeric($value)) {
            return (float) $value;
        }

        if (is_string($value)) {
            $filtered = preg_replace('/[^0-9.\-]/', '', $value);

            if ($filtered !== '' && is_numeric($filtered)) {
                return (float) $filtered;
            }
        }

        return null;
    }

    private function parseInteger(mixed $value): ?int
    {
        if (is_null($value)) {
            return null;
        }

        if (is_int($value)) {
            return $value;
        }

        if (is_numeric($value)) {
            return (int) $value;
        }

        if (is_string($value)) {
            $filtered = preg_replace('/[^0-9\-]/', '', $value);

            if ($filtered !== '' && is_numeric($filtered)) {
                return (int) $filtered;
            }
        }

        return null;
    }
}