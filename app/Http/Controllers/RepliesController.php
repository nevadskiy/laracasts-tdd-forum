<?php

namespace App\Http\Controllers;

use App\Channel;
use App\Reply;
use App\Rules\SpamFree;
use App\Thread;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class RepliesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except('index');
    }

    public function index(Channel $channel, Thread $thread)
    {
        return $thread->replies()->paginate(20);
    }

    public function store(Request $request, Channel $channel, Thread $thread)
    {
        $this->validate($request, ['body' => ['required', new SpamFree()]]);

        $reply = $thread->addReply([
            'body' => $request['body'],
            'user_id' => auth()->id()
        ]);

        return $reply->load('owner');
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

        $this->validate($request, ['body' => ['required', new SpamFree()]]);

        $reply->update(['body' => $request['body']]);

        return response()->json(['status' => 'success'], Response::HTTP_OK);
    }
}
