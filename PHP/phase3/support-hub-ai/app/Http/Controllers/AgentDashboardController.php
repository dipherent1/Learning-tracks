<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AgentDashboardController extends Controller
{
    public function index()
    {
        // The middleware has already confirmed the user is an agent or admin.
        // So, we can safely fetch all tickets.
        $tickets = Ticket::with(['user', 'department', 'team']) // Eager load team for context
            ->latest()
            ->paginate(15);

        return Inertia::render('Agent/Dashboard', [
            'tickets' => $tickets,
        ]);
    }
}