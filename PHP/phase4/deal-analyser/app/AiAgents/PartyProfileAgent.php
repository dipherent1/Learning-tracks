<?php

namespace App\AiAgents;

use LarAgent\Agent;

class PartyProfileAgent extends Agent
{
    protected $model = 'gemini-2.5-flash';

    protected $history = 'in_memory';

    protected $provider = 'gemini';

    protected $tools = [];

    protected $responseSchema = [
    'name' => 'Party_Profile_Agent_Response',
    'schema' => [
        'type' => 'object',
        'properties' => [
            'temperature' => [
                'type' => 'number',
                'description' => 'Temperature in degrees'
            ],
        ],
        'required' => ['temperature'],
        'additionalProperties' => false,
    ],
    'strict' => true,
];


    public function instructions()
    {
        return "Define your agent's instructions here.";
    }

    public function prompt($message)
    {
        return $message;
    }
}
