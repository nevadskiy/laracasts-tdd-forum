<?php

namespace App\Http\Controllers;

use App\Activity;
use App\User;

class ProfilesController extends Controller
{
    public function show(User $user)
    {
        $activities = Activity::feed($user, 50);

        return view('profiles.show', compact('user', 'activities'));
    }
}
