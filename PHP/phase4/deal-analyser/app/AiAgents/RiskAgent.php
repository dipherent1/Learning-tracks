<?php

namespace App\AiAgents;

use LarAgent\Agent;

class RiskAgent extends Agent
{
    protected $model = 'gemini-2.5-flash';

    protected $history = 'in_memory';

    protected $provider = 'gemini';

    protected $tools = [];

    protected $responseSchema = [
        'name' => 'Risk_Agent_Response',
        'schema' => [
            'type' => 'object',
            'properties' => [
                'risks' => [
                    'type' => 'array',
                    'description' => 'list of identified risks for the deal',
                    'items' => [
                        'type' => 'object',
                        'properties' => [
                            'category' => [
                                'type' => 'string',
                                'description' => 'the category of the deal/risk',
                            ],
                            'risk' => [
                                'type' => 'string',
                                'description' => 'a brief description of the risk of the deal',
                            ],
                            'likelihood' => [
                                'type' => 'string',
                                'description' => 'an estimate of the likelihood of the risk occurring',
                            ],
                            'impact' => [
                                'type' => 'string',
                                'description' => 'the potential impact of the risk on the deal',
                            ],
                            'mitigations' => [
                                'type' => 'array',
                                'description' => 'recommended mitigations for this risk as JSON objects',
                                'items' => [
                                    'type' => 'object',
                                    'description' => 'a single mitigation item as a JSON object',
                                    'additionalProperties' => true,
                                ],
                                'minItems' => 1,
                            ],
                        ],
                        'required' => ['category', 'risk', 'likelihood', 'impact', 'mitigations'],
                        'additionalProperties' => false,
                    ],
                ],
            ],
            'required' => ['risks'],
            'additionalProperties' => false,
        ],
        'strict' => true,
    ];


    public function instructions()
    {
        return "You are a Risk Analysis Agent. Your task is to analyze business deals based on provided information and generate structured responses. Use the provided data to extract key details about the deal risks, including category, risk description, likelihood, impact, and mitigations. Ensure that your responses adhere strictly to the defined response schema. Avoid adding any extra information or deviating from the specified format. If you need additional information about a company involved in the deal, use the available tools to fetch the necessary data. Always aim to provide clear, concise, and accurate information that can assist in understanding the deal's risks.";
    }

    public function prompt($message)
    {
        return $message;
    }
}
