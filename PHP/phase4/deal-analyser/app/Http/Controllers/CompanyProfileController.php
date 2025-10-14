<?php

namespace App\Http\Controllers;

use App\Models\CompanyProfile;
use Illuminate\Http\Request;

class CompanyProfileController extends Controller
{
    public function create()
    {
        return view('company.create');
    }
    
    // public function index()
    // {

    // }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'industry' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'size' => 'nullable|integer',
            'revenue' => 'nullable|numeric',
        ]);

        $companyProfile = CompanyProfile::findOrFail($request->input('id'));
        $companyProfile->update($validated);

        return redirect()->route('dashboard');
    }

    public function show(CompanyProfile $companyProfile)
    {
        return view('company.show', compact('companyProfile'));
    }
    
    public function store(Request $request){

        $validated = $request->validate([
           'name' => 'required|string|max:255',
            'industry' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'size' => 'nullable|integer',
            'revenue' => 'nullable|numeric',
        ]);

         CompanyProfile::create([
            'user_id' => auth()->id(),
            ...$validated
        ]);

        return redirect()->route('dashboard');

    }
}
