<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TicketController extends Controller
{
    public function index()
    {
        $tickets = Ticket::with('user', 'department')
        ->latest()
        ->paginate(10);

        return Inertia::render('Tickets/Index',[
            'tickets' => $tickets
        ]);
    }
    //
}
