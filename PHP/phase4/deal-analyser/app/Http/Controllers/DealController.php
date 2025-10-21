<?php

namespace App\Http\Controllers;

use App\Events\DealStored;
use App\Models\Deal;
use Illuminate\Http\Request;
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
            'input' => $request->except(['_token']),
            'has_image' => $request->hasFile('image'),
        ]);

        $validated = $request->validate([
            'value_estimate' => 'nullable|numeric',
            'duration_months' => 'nullable|integer',
            'description' => 'nullable|required|string|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        Log::info('Deal data validated', [
            'user_id' => $request->user()?->id,
            'validated' => $validated,
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('deals', 'public');
            Log::info('Deal image stored', [
                'user_id' => $request->user()?->id,
                'image_path' => $imagePath,
            ]);
        }
        $user = $request->user();
        $company = $user->companyProfile;
        $deal = Deal::create([
            'user_id' => $user->id,
            'company_id' => $company->id,
            'value_estimate' => $validated['value_estimate'] ?? null,
            'duration_months' => $validated['duration_months'] ?? null,
            'title' => $request->title ?? null,
            'description' => $validated['description'] ?? null,
            'image_path' => $imagePath,
        ]);
        
        Log::info('Deal created', [
            'user_id' => $user->id,
            'company_id' => $company->id,
            'deal_id' => $deal->id,
        ]);

        DealStored::dispatch($deal);

        Log::info('DealStored event dispatched', [
            'deal_id' => $deal->id,
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
