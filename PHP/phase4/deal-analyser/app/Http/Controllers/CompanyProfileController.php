<?php

namespace App\Http\Controllers;

use App\Models\CompanyProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class CompanyProfileController extends Controller
{
    public function create()
    {
        Log::info('Opening company profile create form', [
            'user_id' => Auth::id(),
        ]);

        return Inertia::render('Company/Create');

    }

    public function edit(CompanyProfile $company)
    {
        Log::info('Opening company profile edit form', [
            'user_id' => Auth::id(),
            'company_id' => $company->id,
        ]);

        return Inertia::render('Company/Create', [
            'company' => $company,
        ]);
    }

    public function update(Request $request, CompanyProfile $company)
    {
        Log::info('Updating company profile', [
            'user_id' => $request->user()?->id,
            'company_id' => $company->id,
            'input' => $request->except(['_token', '_method']),
        ]);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'industry' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'size' => 'nullable|integer',
            'revenue' => 'nullable|numeric',
        ]);

        $company->update($validated);

        Log::info('Company profile updated', [
            'user_id' => $request->user()?->id,
            'company_id' => $company->id,
        ]);

        return redirect()->route('company.show', $company);

    }

    public function show(CompanyProfile $company)
    {
        Log::info('Viewing company profile', [
            'user_id' => Auth::id(),
            'company_id' => $company->id,
        ]);

        return Inertia::render('Company/Show', [
            'company' => $company,
        ]);
    }
    
    public function store(Request $request)
    {
        Log::info('Storing company profile', [
            'user_id' => $request->user()?->id,
            'input' => $request->except(['_token']),
        ]);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'industry' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'size' => 'nullable|integer',
            'revenue' => 'nullable|numeric',
        ]);

        $company = CompanyProfile::create([
            'user_id' => Auth::id(),
            ...$validated
        ]);

        Log::info('Company profile created', [
            'user_id' => $request->user()?->id,
            'company_id' => $company->id,
        ]);

        return redirect()->route('company.show', $company);

    }
}
