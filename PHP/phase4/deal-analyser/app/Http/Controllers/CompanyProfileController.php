<?php

namespace App\Http\Controllers;

use App\Models\CompanyProfile;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CompanyProfileController extends Controller
{
    public function create(Request $request)
    {
        // If the user already has a company profile, pass it so the form can be used for editing
        $company = $request->user()->companyProfile;

        return Inertia::render('Company/Create', [
            'company' => $company,
        ]);
    }

    public function update(Request $request, CompanyProfile $companyProfile)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'industry' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'size' => 'nullable|integer',
            'revenue' => 'nullable|numeric',
        ]);

        $companyProfile->update($validated);

        return redirect()->route('company.show', $companyProfile->id);
    }

    public function show(CompanyProfile $companyProfile)
    {
        return Inertia::render('Company/Show', [
            'company' => $companyProfile,
        ]);
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
           'name' => 'required|string|max:255',
            'industry' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'size' => 'nullable|integer',
            'revenue' => 'nullable|numeric',
        ]);

        $company = CompanyProfile::create([
            'user_id' => auth()->id(),
            ...$validated
        ]);

        return redirect()->route('company.show', $company->id);

    }
}
