<?php

namespace App\AiAgents;

use LarAgent\Agent;

class ChatAgent extends Agent
{
    protected $model = 'gemini-2.5-flash';

    protected $history = 'in_memory';

    protected $provider = 'gemini';

    protected $tools = [];

    public function instructions()
    {
        return "You are an experienced deal advisor. Hold an interactive conversation with the user about a business deal and its associated risks. Keep replies concise, practical, and grounded in the supplied context. When offering guidance, reference the deal facts or risks that apply. If information is missing, ask clarifying questions instead of guessing.";
    }

    public function prompt($message)
    {
        return $message;
    }
}
