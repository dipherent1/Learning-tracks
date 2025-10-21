<?php

namespace App\AiAgents;

use LarAgent\Agent;

class RiskMitigationAgent extends Agent
{
    protected $model = 'gemini-2.5-flash';

    protected $history = 'in_memory';

    protected $provider = 'gemini';

    protected $tools = [];

    public function instructions()
    {
        return "Define your agent's instructions here.";
    }

    public function prompt($message)
    {
        return $message;
    }
}
