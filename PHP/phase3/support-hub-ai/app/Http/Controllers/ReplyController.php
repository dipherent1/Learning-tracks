<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReplyRequest;
use App\Models\Reply;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ReplyController extends Controller
{
    use AuthorizesRequests;
    public function store(StoreReplyRequest $request, Ticket $ticket)
    {

        $this->authorize('create', [Reply::class, $ticket]);
        $ticket->replies()->create([
            'user_id' => $request->user()->id,
            'content' => $request->validated('content')

        ]);

        return to_route('tickets.show',$ticket);

    }
    //
}
