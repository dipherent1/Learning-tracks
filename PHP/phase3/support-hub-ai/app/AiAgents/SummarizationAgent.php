<?php

namespace App\AiAgents;

use LarAgent\Agent;

class SummarizationAgent extends Agent
{
    protected $model = 'gemini-2.5-pro';

    protected $history = 'in_memory';

    protected $provider = 'gemini';

    protected $tools = [];

    public function instructions()
    {
        return "You are an expert support ticket analyst. Please summarize the following support ticket conversation concisely.
        Focus on the customer's main problem, the steps taken to resolve it, and the final outcome.
        Provide the summary in a few clear bullet points.
        
        Here is the conversation:";
    }

    public function prompt($message)
    {
        return $message;
    }
}
