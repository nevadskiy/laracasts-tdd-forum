<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    protected $fillable = [
        'body', 'user_id'
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function favorites()
    {
        return $this->morphMany(Favorite::class, 'favorited');
    }

    public function addFavorite(User $user = null)
    {
        /** @var User $user */
        $user = $user ?: auth()->user();

        if ($this->isFavorited($user)) {
            return;
        }

        $this->favorites()->create(['user_id' => $user->id]);
    }

    public function isFavorited(User $user = null)
    {
        /** @var User $user */
        $user = $user ?: auth()->user();

        return $this->favorites()->where(['user_id' => $user->id])->exists();
    }
}
