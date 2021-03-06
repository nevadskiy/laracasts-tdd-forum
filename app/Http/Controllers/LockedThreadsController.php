<?php

namespace App\Http\Controllers;

use App\Thread;
use Illuminate\Http\Request;

class LockedThreadsController extends Controller
{
    public function store(Request $request, Thread $thread)
    {
        $thread->update(['locked' => true]);
    }

    public function destroy(Request $request, Thread $thread)
    {
        $thread->update(['locked' => false]);
    }
}
