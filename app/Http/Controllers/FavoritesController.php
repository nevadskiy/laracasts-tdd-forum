<?php

namespace App\Http\Controllers;

use App\Reply;

class FavoritesController extends Controller
{
    /**
     * FavoritesController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * @param Reply $reply
     */
    public function store(Reply $reply)
    {
        $reply->addFavorite();
    }

    /**
     * @param Reply $reply
     */
    public function destroy(Reply $reply)
    {
        $reply->removeFavorite();
    }
}
