<?php

namespace App\Http\Controllers;

use App\Channel;
use App\Reply;
use App\Inspections\Spam;
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
        try {
            $this->validateReply($request);

            $reply = $thread->addReply([
                'body' => $request['body'],
                'user_id' => auth()->id()
            ]);
        } catch (Exception $e) {
            return response('Sorry, your reply could not be saved at this time.', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

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

        try {
            $this->validateReply($request);
            $reply->update(['body' => $request['body']]);
        } catch (Exception $e) {
            return response('Sorry, your reply could not be saved at this time.', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return response()->json(['status' => 'success'], Response::HTTP_OK);
    }

    protected function validateReply(Request $request): void
    {
        $this->validate($request, ['body' => 'required']);

        resolve(Spam::class)->detect($request['body']);
    }
}
