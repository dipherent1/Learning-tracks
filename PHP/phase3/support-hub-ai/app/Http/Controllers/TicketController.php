<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTicketRequest;
use App\Models\Department;
use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class TicketController extends Controller
{
    use AuthorizesRequests;
    public function index()
    {

        $user = Auth::user();


        $ticketsQuery = Ticket::with('user', 'department')
        ->latest();
        
        if (! $user->isAdmin()){
            $ticketsQuery->where('user_id',$user->id);
        }


        $tickets = $ticketsQuery->paginate(10);

        return Inertia::render('Tickets/Index',[
            'tickets' => $tickets
        ]);
    }

    public function create()
    {
        $depts = Department::all(['id','name']);

        return Inertia::render('Tickets/Create', props:[
            'departments' => $depts,
        ]);
    }

    public function store(StoreTicketRequest $request)
    {
        $validatedData = $request->validated();
        Auth::user()->tickets()->create($validatedData);

        return to_route('tickets.index');
    }

    public function show(Ticket $ticket)
    {
        $this->authorize('view',$ticket);

        $ticket->load(['user','department','replies.user']);

        return Inertia::render('Tickets/Show', [
            'ticket' => $ticket
        ]);
    }
    //
}
