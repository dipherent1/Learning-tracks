<?php

namespace App\AiAgents;

use LarAgent\Agent;

class TestAgent extends Agent
{
    protected $model = 'gemini-2.5-pro';

    protected $history = 'in_memory';

    protected $provider = 'gemini';

    protected $tools = [];

    public function instructions()
    {
        return "Define your agent's instructions here.";
    }

    public function prompt($message = "Tell me a short, programmer-themed joke.")
    {
        return $message;
    }
}
