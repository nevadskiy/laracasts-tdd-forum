<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    use Favoritable, RecordsActivity;

    /**
     * @var array
     */
    protected $fillable = [
        'body', 'user_id'
    ];

    /**
     * @var array
     */
    protected $with = [
        'owner', 'favorites'
    ];

    /**
     * @var array
     */
    protected $appends = [
        'favoritesCount', 'isFavorited'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function thread()
    {
        return $this->belongsTo(Thread::class);
    }

    public function path()
    {
        return $this->thread->path() . '#reply-' . $this->id;
    }
}
