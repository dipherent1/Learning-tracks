<?php

namespace App\Http\Controllers;

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
