<?php

namespace App\AiAgents;

use App\Models\Ticket;
use LarAgent\Agent;

class TriageAgent extends Agent
{
    protected $model = 'gemini-2.5-pro';

    protected $history = 'in_memory';

    protected $provider = 'gemini';

    protected $tools = [];

    public function __construct(public Ticket $ticket)
    {
        parent::__construct("triage_ticket_{$this->ticket->id}");
    }
    public function instructions()
    {
        return "You are an expert support ticket analyst. Analyze the following ticket and return a JSON object with two keys: 'priority' and 'category'.
        The 'priority' must be one of the following values: 'low', 'medium', 'high'.
        The 'category' must be one of the following values: 'billing', 'technical', 'sales', 'general'.

        Here is the ticket content:";
    }

    public function prompt($message)
    {
        return $message;
    }
}
