<?php

namespace App\Http\Controllers;

use App\Channel;
use App\Thread;
use Illuminate\Http\Request;

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
     * @param Channel|null $channel
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Channel $channel = null)
    {
        $threads = $this->getThreads($request, $channel);

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

        return redirect($thread->path());
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
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function destroy(Thread $thread)
    {
        //
    }

    /**
     * @param Request $request
     * @param Channel $channel
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Relations\HasMany
     */
    protected function getThreads(Request $request, ?Channel $channel)
    {
        if ($channel) {
            $threads = $channel->threads()->latest();
        } else {
            $threads = Thread::latest();
        }

        if ($request->has('by')) {
            $threads->whereHas('creator', function ($query) use ($request) {
                $query->where('name', $request['by']);
            });
        }

        $threads = $threads->get();

        return $threads;
    }
}
