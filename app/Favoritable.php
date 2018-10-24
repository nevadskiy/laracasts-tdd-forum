<?php

namespace App;

trait Favoritable
{
    public static function bootFavoritable()
    {
        static::deleting(function ($model) {
            $model->favorites->each->delete();
        });
    }

    public function favorites()
    {
        return $this->morphMany(Favorite::class, 'favorited');
    }

    public function addFavorite()
    {
        if ($this->isFavorited()) {
            return;
        }

        $this->favorites()->create(['user_id' => auth()->id()]);
    }

    public function removeFavorite()
    {
        $this->favorites()->where('user_id', auth()->id())->get()->each->delete();
    }

    public function isFavorited()
    {
        return !! $this->favorites->where('user_id', auth()->id())->count();
    }

    public function getIsFavoritedAttribute()
    {
        return $this->isFavorited();
    }

    public function getFavoritesCountAttribute()
    {
        return $this->favorites->count();
    }
}
