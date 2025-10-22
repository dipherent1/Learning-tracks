<?php

namespace App\AiAgents;

use App\Models\CompanyProfile;
use Illuminate\Support\Facades\Log;
use LarAgent\Agent;
use LarAgent\Attributes\Tool;


class DealAgent extends Agent
{
    protected $model = 'gemini-2.5-flash';

    protected $history = 'in_memory';

    protected $provider = 'gemini';

    protected $tools = [];

    protected $responseSchema = [
        'name' => 'Deal_Agent_Response',
        'schema' => [
            'type' => 'object',
            'properties' => [
                'title' => [
                    'type' => 'string',
                    'description' => 'the title of the deal',
                ],
                'description' => [
                    'type' => 'string',
                    'description' => 'a brief description of the deal',
                ],
                'valueEstimate' => [
                    'type' => 'string',
                    'description' => 'an estimate of the deal\'s value',
                ],
                'duration' => [
                    'type' => 'string',
                    'description' => 'the duration of the deal in months',
                ],
            ],
            'required' => ['title', 'description', 'valueEstimate', 'duration'],
            'additionalProperties' => false,
        ],
        'strict' => true,
    ];

    public function instructions()
    {
        return "You are a Deal Analysis Agent. Your task is to analyze business deals based on provided information and generate structured responses. Use the provided data to extract key details about the deal, including title, description, value estimate, and duration. Ensure that your responses adhere strictly to the defined response schema. Avoid adding any extra information or deviating from the specified format.";
    }

    public function prompt($message)
    {
        return $message;
    }



    // #[Tool('get company info',[
    //     'company'=> 'the company id '
    // ])]
    // public function companyInfoTool( $company_id)
    // {
    //     $company = CompanyProfile::find($company_id);
    //     // Implement logic to fetch and return company information based on the provided ID
    //     dump('i am called');
    //     dump($company->name);
    //     Log::info('Company Info Tool called with ID: ' . $company->id);
    //     $industry = $company->industry ?? 'N/A';
    //     $size = $company->size ?? 'N/A';
    //     $revenue = $company->revenue ?? 'N/A';
    //     $location = $company->location ?? 'N/A';
    //     $description = $company->description ?? 'N/A';
    //     return "Company info for ID: " . $company->id . "\n" .
    //            "Industry: " . $industry . "\n" .
    //            "Size: " . $size . "\n" .
    //            "Revenue: " . $revenue . "\n" .
    //            "Location: " . $location . "\n" .
    //            "Description: " . $description . "\n";
    // }

}
