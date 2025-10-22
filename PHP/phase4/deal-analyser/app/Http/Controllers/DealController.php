<?php

namespace App\Http\Controllers;

use App\AiAgents\ChatAgent;
use App\Jobs\AnalyzeDealJob;
use App\Jobs\AnalyzeRiskJob;
use App\Models\Deal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class DealController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        Log::info('Viewing deals index', [
            'user_id' => $user?->id,
            'company_id' => $user?->companyProfile?->id,
            'filters' => $request->query(),
        ]);

        $deals = $user?->companyProfile?->deals()->paginate(10);

        Log::info('Deals index response ready', [
            'user_id' => $user?->id,
            'company_id' => $user?->companyProfile?->id,
            'returned_count' => $deals?->count(),
            'current_page' => $deals?->currentPage(),
        ]);

        return Inertia::render('Deals/Index', [
            'deals' => $deals
        ]);
    }

    public function create(Request $request)
    {
        Log::info('Viewing deal create form', [
            'user_id' => $request->user()?->id,
        ]);

        return Inertia::render('Deals/Create');
    }

    public function store(Request $request)
    {
        Log::info('Creating deal', [
            'user_id' => $request->user()?->id,
            'has_image' => $request->hasFile('image'),
        ]);

        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $imagePath = $request->file('image')->store('deals', 'public');

        Log::info('Deal image stored', [
            'user_id' => $request->user()?->id,
            'image_path' => $imagePath,
        ]);


        $user = $request->user();
        $deal = $user->companyProfile->deals()->create([
            'user_id' => $user->id,
            'company_id' => $user->companyProfile->id,
            'image_path' => $imagePath,
        ]);

        Bus::chain([
            new AnalyzeDealJob($user, $deal),
            new AnalyzeRiskJob($user, $deal),
        ])->dispatch();

        Log::info('AnalyzeDealJob dispatched', [
            'user_id' => $user->id,
            'deal_id' => $deal->id,
            'image_path'=> $imagePath,
        ]);

        return redirect()->route('deals.show', $deal)->with('success', 'Deal created successfully!');
    }


    public function show(Request $request, Deal $deal)
    {
        Log::info('Viewing deal', [
            'user_id' => $request->user()?->id,
            'deal_id' => $deal->id,
        ]);

        return Inertia::render('Deals/Show', [
            'deal' => $deal->load(['company', 'user', 'parties', 'risks']),
        ]);

    }

    public function chat(Request $request, Deal $deal)
    {
        $user = $request->user();

        if (! $user) {
            abort(401);
        }

        if ($deal->user_id !== $user->id && $deal->company_id !== optional($user->companyProfile)->id) {
            abort(403);
        }

        $validated = $request->validate([
            'message' => ['required', 'string', 'max:2000'],
        ]);

        $deal->loadMissing(['company', 'risks']);

        Log::info('Deal chat message received', [
            'deal_id' => $deal->id,
            'user_id' => $user->id,
        ]);

        $agent = ChatAgent::for($user->id . '-' . $deal->id);

        $prompt = $this->buildChatPrompt($deal, $validated['message']);
        try {
        $response = $agent->respond($prompt);

        $reply = is_string($response)
            ? $response
            : json_encode($response, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        Log::info('Deal chat agent responded', [
            'deal_id' => $deal->id,
            'user_id' => $user->id,
        ]);
        
        } catch (\Exception $e) {
            Log::error('Deal chat agent error', [
                'deal_id' => $deal->id,
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);

            $reply = "I'm sorry, but I'm currently unable to respond to your message. Please try again later.";
        }

        $deal->unsetRelation('risks');

        return response()->json([
            'reply' => $reply,
            'risks' => $deal->risks()->get(['id', 'category', 'risk', 'likelihood', 'impact', 'mitigations']),
        ]);
    }

    public function refreshRisks(Request $request, Deal $deal)
    {
        $user = $request->user();

        if (! $user) {
            abort(401);
        }

        if ($deal->user_id !== $user->id && $deal->company_id !== optional($user->companyProfile)->id) {
            abort(403);
        }

    AnalyzeRiskJob::dispatch($user, $deal);

        Log::info('AnalyzeRiskJob dispatched from chat update button', [
            'deal_id' => $deal->id,
            'user_id' => $user->id,
        ]);

        return response()->json([
            'status' => 'queued',
            'risks' => $deal->risks()->get(['id', 'category', 'risk', 'likelihood', 'impact', 'mitigations']),
        ]);
    }

    private function buildChatPrompt(Deal $deal, string $message): string
    {
        $dealContext = [
            'title' => $deal->title,
            'description' => $deal->description,
            'value_estimate' => $deal->value_estimate,
            'duration_months' => $deal->duration_months,
            'status' => $deal->status,
            'company' => [
                'name' => $deal->company?->name,
            ],
        ];

        $risks = $deal->risks->map(function ($risk) {
            return [
                'category' => $risk->category,
                'risk' => $risk->risk,
                'likelihood' => $risk->likelihood,
                'impact' => $risk->impact,
                'mitigations' => $risk->mitigations,
            ];
        })->values();

        $context = json_encode([
            'deal' => $dealContext,
            'risks' => $risks,
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        return <<<PROMPT
        You are supporting a deal review conversation. Use the provided JSON context to inform the discussion.
        Context:
        {$context}

        The user says: {$message}

        Reply conversationally with guidance or clarifying questions that stay grounded in the context.
        PROMPT;
    }

    public function edit(Request $request, Deal $deal)
    {
        Log::info('Viewing deal edit form', [
            'user_id' => $request->user()?->id,
            'deal_id' => $deal->id,
        ]);

    }

    public function update(Request $request, Deal $deal)
    {
        Log::info('Updating deal', [
            'user_id' => $request->user()?->id,
            'deal_id' => $deal->id,
            'input' => $request->except(['_token', '_method']),
        ]);

    }

    public function aiAnalysis(Request $request)
    {
        Log::info('AI analysis requested', [
            'user_id' => $request->user()?->id,
            'input' => $request->except(['_token']),
        ]);

    }
}
