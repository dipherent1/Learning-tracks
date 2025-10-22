<?php

namespace App\Jobs;

use App\AiAgents\RiskAgent;
use App\Models\Deal;
use App\Models\RiskMitigation;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class AnalyzeRiskJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(protected User $user, protected Deal $deal)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info('AnalyzeRiskJob started', [
            'deal_id' => $this->deal->id,
            'user_id' => $this->user->id,
        ]);

        try {
            $prompt = $this->buildPrompt();
            Log::debug('Risk analysis prompt prepared', [
                'deal_id' => $this->deal->id,
                'prompt_length' => strlen($prompt),
            ]);

            $agent = RiskAgent::for($this->user->id . '-' . $this->deal->id);
            $response = $agent->respond($prompt);

            Log::info('Risk agent response received', [
                'deal_id' => $this->deal->id,
                'response_preview' => $this->previewResponse($response),
            ]);

            $payload = $this->decodeResponse($response);

            if (! $payload) {
                Log::warning('Risk agent response could not be decoded', [
                    'deal_id' => $this->deal->id,
                ]);

                return;
            }

            $risks = $this->extractRisks($payload);

            if (empty($risks)) {
                Log::warning('Risk agent response did not contain risks', [
                    'deal_id' => $this->deal->id,
                ]);

                return;
            }

            $persisted = $this->persistRisks($risks);

            Log::info('Risk analysis persisted', [
                'deal_id' => $this->deal->id,
                'persisted_count' => $persisted,
            ]);

            Log::info('AnalyzeRiskJob completed', [
                'deal_id' => $this->deal->id,
            ]);
        } catch (\Throwable $exception) {
            Log::error('AnalyzeRiskJob failed', [
                'deal_id' => $this->deal->id,
                'user_id' => $this->user->id,
                'error_message' => $exception->getMessage(),
            ]);

            throw $exception;
        }
    }

    private function buildPrompt(): string
    {
        $existingRisks = $this->deal->risks->map(function (RiskMitigation $risk) {
            return [
                'category' => $risk->category,
                'risk' => $risk->risk,
                'likelihood' => $risk->likelihood,
                'impact' => $risk->impact,
                'mitigations' => $risk->mitigations,
            ];
        });

        $prompt = <<<PROMPT
        Read the entire conversation history for this session. From the conversation:
        - Identify all risks discussed.
        - Add a new risk if it is different from those provided or if its empty.
        - Avoid duplicates.
        - For each risk include category, risk description, likelihood (0-1 or %), impact (0-1 or %), and mitigation details.
        Only use information grounded in the conversation; do not invent facts.

        Current risks (JSON):
        {$existingRisks->toJson(JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)}

        Deal context (JSON):
        {$this->deal->toJson(JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)}
        PROMPT;

        return $prompt;
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

    private function extractRisks(array $payload): array
    {
        if (! isset($payload['risks']) || ! is_array($payload['risks'])) {
            return [];
        }

        $normalized = [];

        foreach ($payload['risks'] as $risk) {
            if (! is_array($risk)) {
                continue;
            }

            $mapped = $this->normalizeRisk($risk);

            if ($mapped !== null) {
                $normalized[] = $mapped;
            }
        }

        return $normalized;
    }

    private function normalizeRisk(array $risk): ?array
    {
        $description = is_string($risk['risk'] ?? null) ? trim($risk['risk']) : null;

        if ($description === null || $description === '') {
            Log::debug('Skipping risk without description');

            return null;
        }

        $mitigations = $this->normalizeMitigations($risk['mitigations'] ?? []);

        return [
            'category' => isset($risk['category']) && $risk['category'] !== ''
                ? (string) $risk['category']
                : null,
            'risk' => $description,
            'likelihood' => $this->parseScore($risk['likelihood'] ?? null),
            'impact' => $this->parseScore($risk['impact'] ?? null),
            'mitigations' => $mitigations,
        ];
    }

    private function normalizeMitigations(mixed $value): array
    {
        if (is_string($value)) {
            $decoded = json_decode($value, true);

            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                $value = $decoded;
            } else {
                $lines = array_filter(array_map('trim', preg_split('/[\r\n]+/', $value)));

                return array_map(static fn ($line) => ['details' => $line], $lines);
            }
        }

        if (! is_array($value)) {
            return [];
        }

        $normalized = [];

        foreach ($value as $item) {
            if (is_array($item)) {
                $normalized[] = $item;

                continue;
            }

            if (is_string($item) && trim($item) !== '') {
                $normalized[] = ['details' => trim($item)];
            }
        }

        return $normalized;
    }

    private function parseScore(mixed $value): ?float
    {
        if ($value === null || $value === '') {
            return null;
        }

        $numeric = null;

        if (is_numeric($value)) {
            $numeric = (float) $value;
        } elseif (is_string($value)) {
            $lower = strtolower(trim($value));

            $wordScale = [
                'very low' => 0.1,
                'low' => 0.25,
                'medium' => 0.5,
                'moderate' => 0.5,
                'medium-high' => 0.65,
                'high' => 0.75,
                'very high' => 0.9,
                'critical' => 1.0,
            ];

            if (isset($wordScale[$lower])) {
                return $wordScale[$lower];
            }

            if (preg_match('/-?\d+(?:\.\d+)?/', $lower, $matches)) {
                $numeric = (float) $matches[0];
            }
        }

        if ($numeric === null) {
            return null;
        }

        if ($numeric > 1 && $numeric <= 5) {
            return round($numeric / 5, 2);
        }

        if ($numeric > 1 && $numeric <= 100) {
            return round($numeric / 100, 2);
        }

        if ($numeric < 0) {
            $numeric = 0;
        }

        if ($numeric > 1) {
            $numeric = 1;
        }

        return round($numeric, 2);
    }

    private function persistRisks(array $risks): int
    {
        $count = 0;

        foreach ($risks as $risk) {
            $record = RiskMitigation::updateOrCreate(
                [
                    'deal_id' => $this->deal->id,
                    'risk' => $risk['risk'],
                ],
                [
                    'category' => $risk['category'],
                    'likelihood' => $risk['likelihood'],
                    'impact' => $risk['impact'],
                    'mitigations' => $risk['mitigations'],
                ]
            );

            Log::info('Risk mitigation saved', [
                'deal_id' => $this->deal->id,
                'risk_id' => $record->id,
                'risk' => $risk['risk'],
            ]);

            $count++;
        }

        $this->deal->unsetRelation('risks');

        return $count;
    }

    private function previewResponse(mixed $response): string
    {
        $stringResponse = is_string($response)
            ? $response
            : json_encode($response, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        if (! $stringResponse) {
            return '[unavailable]';
        }

        $trimmed = trim($stringResponse);

        return substr($trimmed, 0, 500) . (strlen($trimmed) > 500 ? '...' : '');
    }
}
