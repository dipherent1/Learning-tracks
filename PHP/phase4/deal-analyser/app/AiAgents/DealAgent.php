<?php

namespace App\AiAgents;

use App\Models\CompanyProfile;
use Illuminate\Support\Facades\Log;
use LarAgent\Agent;
use LarAgent\Attributes\Tool;


class DealAgent extends Agent
{
    protected $model = 'gemini-2.5-pro';

    protected $history = 'in_memory';

    protected $provider = 'gemini';

    protected $tools = [];

    public function instructions()
    {
        return "You are a world-class business analyst specializing in risk assessment and deal structure. Your task is to analyze information provided about a business deal and provide structured output using the tools available to you.
        u have
        -companyInfoTool with parameter company id";
    }

    public function prompt($message)
    {
        return $message;
    }

    #[Tool('get company info',[
        'company'=> 'the company id '
    ])]
    public function companyInfoTool( $company_id)
    {
        $company = CompanyProfile::find($company_id);
        // Implement logic to fetch and return company information based on the provided ID
        dump('i am called');
        dump($company->name);
        Log::info('Company Info Tool called with ID: ' . $company->id);
        $industry = $company->industry ?? 'N/A';
        $size = $company->size ?? 'N/A';
        $revenue = $company->revenue ?? 'N/A';
        $location = $company->location ?? 'N/A';
        $description = $company->description ?? 'N/A';
        return "Company info for ID: " . $company->id . "\n" .
               "Industry: " . $industry . "\n" .
               "Size: " . $size . "\n" .
               "Revenue: " . $revenue . "\n" .
               "Location: " . $location . "\n" .
               "Description: " . $description . "\n";
    }

}
