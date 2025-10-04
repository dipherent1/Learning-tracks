<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReplyRequest;
use App\Models\Ticket;
use Illuminate\Http\Request;

class ReplyController extends Controller
{
    public function store(StoreReplyRequest $request, Ticket $ticket)
    {
        $ticket->replies()->create([
            'user_id' => $request->user()->id,
            'content' => $request->validated('content')

        ]);

        return to_route('tickets.show',$ticket);

    }
    //
}
