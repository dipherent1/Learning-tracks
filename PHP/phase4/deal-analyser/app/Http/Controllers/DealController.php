<?php

namespace App\Http\Controllers;

use App\Models\Deal;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DealController extends Controller
{
    //
    public function index()
    {
        $deals = auth()->user()->companyProfile->deals()->paginate(10);
        return Inertia::render('Deals/Index', [
            'deals' => $deals
        ]);
    }

    public function create(Request $request)
    {
        return Inertia::render('Deals/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'value_estimate' => 'nullable|numeric',
            'duration_months' => 'nullable|integer',
            'description' => 'nullable|required|string|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('deals', 'public');
        }
        $user = $request->user();
        $company = $user->companyProfile;
        Deal::create([
            'user_id' => $user->id,
            'company_id' => $company->id,
            'value_estimate' => $validated['value_estimate'] ?? null,
            'duration_months' => $validated['duration_months'] ?? null,
            'title' => $request->title ?? null,
            'description' => $validated['description'] ?? null,
            'image_path' => $imagePath,
        ]);

        return redirect()->route('deals.index')->with('success', 'Deal created successfully!');
    }


    public function show(Request $request, Deal $deal)
    {
        return Inertia::render('Deals/Show', [
            'deal' => $deal->load(['company', 'user', 'parties', 'risks']),
        ]);

    }

    public function edit(Request $request)
    {

    }

    public function update(Request $request, Deal $deal)
    {

    }

    public function aiAnalysis(Request $request)
    {

    }
}
