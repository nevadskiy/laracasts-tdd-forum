<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class UserAvatarController extends Controller
{

    /**
     * UserAvatarController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'avatar' => ['required', 'image']
        ]);

        auth()->user()->update([
            'avatar_path' => $request->file('avatar')->store('avatars', 'public')
        ]);

        return response([], Response::HTTP_NO_CONTENT);
    }
}
