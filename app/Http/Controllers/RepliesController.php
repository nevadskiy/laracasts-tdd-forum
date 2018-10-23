<?php

namespace App\Http\Controllers;

use App\Channel;
use App\Reply;
use App\Thread;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class RepliesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request, Channel $channel, Thread $thread)
    {
        $this->validate($request, [
            'body' => 'required'
        ]);

        $thread->addReply([
            'body' => $request['body'],
            'user_id' => auth()->id()
        ]);

        return back()->with('flash', 'Your reply has been left.');
    }

    public function destroy(Request $request, Reply $reply)
    {
        $this->authorize('delete', $reply);

        $reply->delete();

        if ($request->expectsJson()) {
            return response(['status' => 'Reply deleted'], Response::HTTP_NO_CONTENT);
        }

        return back();
    }

    public function update(Reply $reply, Request $request)
    {
        $this->authorize('update', $reply);

        $reply->update(['body' => $request['body']]);

        return back();
    }
}
