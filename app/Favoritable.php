<?php

namespace App;

trait Favoritable
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function favorites()
    {
        return $this->morphMany(Favorite::class, 'favorited');
    }

    /**
     * @param User|null $user
     */
    public function addFavorite(User $user = null)
    {
        if ($this->isFavorited($user)) {
            return;
        }

        $this->favorites()->create(['user_id' => $this->getUserId($user)]);
    }

    /**
     * @param User|null $user
     * @return bool
     */
    public function isFavorited(User $user = null)
    {
        return !! $this->favorites->where(['user_id' => $this->getUserId($user)])->count();
    }

    /**
     * @return mixed
     */
    public function getFavoritesCountAttribute()
    {
        return $this->favorites->count();
    }

    /**
     * @param User $user
     * @return int|mixed|null
     */
    protected function getUserId(?User $user)
    {
        return $user ? $user->id : auth()->id();
    }
}