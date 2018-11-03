<?php

namespace App\Http\Controllers;

use App\Reply;
use Illuminate\Http\Request;

class BestReplyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request, Reply $reply)
    {
        $this->authorize('update', $reply->thread);

        $reply->thread->markAsBestReply($reply);
    }

    public function destroy(Request $request, Reply $reply)
    {
        
    }
}
