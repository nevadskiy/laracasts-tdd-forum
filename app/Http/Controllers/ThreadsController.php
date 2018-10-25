<?php

namespace App\Http\Controllers;

use App\Channel;
use App\Filters\ThreadFilters;
use App\Thread;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ThreadsController extends Controller
{
    /**
     * ThreadsController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param ThreadFilters $filters
     * @param Channel|null $channel
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, ThreadFilters $filters, Channel $channel = null)
    {
        $threads = $this->getThreads($filters, $channel);

        if ($request->expectsJson()) {
            return $threads;
        }

        return view('threads.index', compact('threads'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('threads.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'body' => 'required',
            'channel_id' => 'required|numeric|exists:channels,id',
        ]);

        $thread = Thread::create([
            'user_id' => auth()->id(),
            'title' => $request['title'],
            'channel_id' => $request['channel_id'],
            'body' => $request['body'],
        ]);

        return redirect($thread->path())->with('flash', 'Your thread has been published!');
    }

    /**
     * Display the specified resource.
     *
     * @param Channel $channel
     * @param  \App\Thread $thread
     * @return \Illuminate\Http\Response
     */
    public function show(Channel $channel, Thread $thread)
    {
        return view('threads.show', compact('thread'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function edit(Thread $thread)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Thread $thread)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param Channel $channel
     * @param  \App\Thread $thread
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(Request $request, Channel $channel, Thread $thread)
    {
        $this->authorize('delete', $thread);

        $thread->delete();

        if ($request->expectsJson()) {
            return response([], Response::HTTP_NO_CONTENT);
        }

        return redirect()->route('threads.index');
    }

    /**
     * @param ThreadFilters $filters
     * @param Channel $channel
     * @return mixed
     */
    protected function getThreads(ThreadFilters $filters, ?Channel $channel)
    {
        $threads = Thread::latest()->filter($filters);

        if ($channel) {
            $threads->where('channel_id', $channel->id);
        }

        return $threads->paginate(20);
    }
}
