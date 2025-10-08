<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReplyRequest;
use App\Models\Reply;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Events\ReplyAdded;

class ReplyController extends Controller
{
    use AuthorizesRequests;
    public function store(StoreReplyRequest $request, Ticket $ticket)
    {

        $this->authorize('create', [Reply::class, $ticket]);
        $reply = $ticket->replies()->create([
            'user_id' => $request->user()->id,
            'content' => $request->validated('content')

        ]);

        $reply->load('user');

        broadcast(new ReplyAdded($reply));

        return to_route('tickets.show',$ticket)->with('flash.banner', 'Reply added successfully!');

    }
    //
}
