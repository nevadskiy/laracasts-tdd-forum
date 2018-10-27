<?php

namespace App\Http\Controllers;

use App\Channel;
use App\Reply;
use App\Inspections\Spam;
use App\Thread;
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

    public function store(Request $request, Channel $channel, Thread $thread, Spam $spam)
    {
        $this->validate($request, [
            'body' => 'required'
        ]);

        $spam->detect($request['body']);

        $reply = $thread->addReply([
            'body' => $request['body'],
            'user_id' => auth()->id()
        ]);

        if ($request->expectsJson()) {
            return $reply->load('owner');
        }

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

        if ($request->expectsJson()) {
            return response()->json(['status' => 'success'], Response::HTTP_OK);
        }

        return back();
    }
}
