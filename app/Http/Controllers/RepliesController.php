<?php

namespace App\Http\Controllers;

use App\Channel;
use App\Http\Forms\CreatePostForm;
use App\Reply;
use App\Rules\SpamFree;
use App\Thread;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class RepliesController extends Controller
{
    /**
     * RepliesController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth')->except('index');
    }

    /**
     * @param Channel $channel
     * @param Thread $thread
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function index(Channel $channel, Thread $thread)
    {
        return $thread->replies()->paginate(20);
    }

    /**
     * @param CreatePostForm $form
     * @param Channel $channel
     * @param Thread $thread
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function store(CreatePostForm $form, Channel $channel, Thread $thread)
    {
        if ($thread->locked) {
            return response('Thread is locked', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return $form->persist($thread)->load('owner');
    }

    /**
     * @param Reply $reply
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Reply $reply, Request $request)
    {
        $this->authorize('update', $reply);

        $this->validate($request, ['body' => ['required', new SpamFree()]]);

        $reply->update(['body' => $request['body']]);

        return response()->json(['status' => 'success'], Response::HTTP_OK);
    }


    /**
     * @param Request $request
     * @param Reply $reply
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\RedirectResponse|Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Request $request, Reply $reply)
    {
        $this->authorize('delete', $reply);

        $reply->delete();

        if ($request->expectsJson()) {
            return response(['status' => 'Reply deleted'], Response::HTTP_NO_CONTENT);
        }

        return back();
    }
}
