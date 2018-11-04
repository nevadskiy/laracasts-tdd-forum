<?php

namespace App\Http\Controllers;

use App\Thread;
use App\Trending;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request, Trending $trending)
    {
        $search = $request['q'];

        $threads = Thread::search($search)->paginate(25);

        if ($request->expectsJson()) {
            return $threads;
        }

        $trending = $trending->get();

        return view('threads.index', compact('threads', 'trending'));
    }
}
