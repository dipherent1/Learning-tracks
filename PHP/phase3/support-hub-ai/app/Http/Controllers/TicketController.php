<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTicketRequest;
use App\Http\Requests\UpdateTicketRequest;
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
        switch ($user->name) {
            case "admin":
                $ticketsQuery = Ticket::query()->with('user', 'department')->latest();
                break;
            default:
                $team = $user->currentTeam;
                $ticketsQuery = $team->tickets()->with('user', 'department')->latest();
                break;
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
        
        $user = $request->user();


        $user->currentTeam->tickets()->create([
            'user_id' => $user->id,
        'department_id' => $validatedData['department_id'],
        'title' => $validatedData['title'],
        'content' => $validatedData['content'],
        ]);

        return to_route('tickets.index');
    }

    public function show(Ticket $ticket)
    {
        $this->authorize('view',$ticket);

        $ticket->load(['user','department','replies.user']);

        $user = auth()->user();

        return Inertia::render('Tickets/Show', [
            'ticket' => $ticket,
            'permissions' => [
                'can_update_ticket' => $user->can('update', $ticket)
            ]
        ]);
    }

    public function update(UpdateTicketRequest $request,Ticket $ticket)
    {
        $this->authorize('update', $ticket);
        $ticket->update($request->validated());

        return to_route('tickets.show', $ticket);
    }
    //
}
