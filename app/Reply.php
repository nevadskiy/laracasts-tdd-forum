<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    use Favoritable;

    /**
     * @var array
     */
    protected $fillable = [
        'body', 'user_id'
    ];

    /**
     * @var array
     */
    protected $with = ['owner', 'favorites'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
