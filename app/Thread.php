<?php

namespace App;

use App\Notifications\ThreadWasUpdated;
use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
    use RecordsActivity;

    /**
     * @var array
     */
    public $fillable = [
        'user_id',
        'channel_id',
        'title',
        'body',
    ];

    /**
     * Boot model
     */
    protected static function boot()
    {
        parent::boot();

        // Allow to use like this: $thread->withoutGlobalScope('creator')
        static::addGlobalScope('creator', function ($builder) {
            $builder->with('creator');
        });

        static::addGlobalScope('channel', function ($builder) {
            $builder->with('channel');
        });

        static::deleting(function (Thread $thread) {
            // Trick with collection EACH method
            $thread->replies->each->delete();
        });
    }

    /**
     * @return string
     */
    public function path()
    {
        return "/threads/{$this->channel->slug}/{$this->id}";
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }

    /**
     * @param $reply
     * @return Model
     */
    public function addReply($reply)
    {
        $reply = $this->replies()->create($reply);

        $this->subscriptions
            ->filter(function ($sub) use ($reply) {
                return $sub->user_id !== $reply->user_id;
            })
            ->each->notify($reply);

        return $reply;
    }

    /**
     * @param $query
     * @param $filters
     * @return mixed
     */
    public function scopeFilter($query, $filters)
    {
        return $filters->apply($query);
    }

    public function subscribe(User $user = null)
    {
        $this->subscriptions()->create([
            'user_id' => $user ? $user->id : auth()->id()
        ]);

        return $this;
    }

    public function unsubscribe(User $user = null)
    {
        $this->subscriptions()
            ->where('user_id', $user ? $user->id : auth()->id())
            ->delete();
    }

    public function subscriptions()
    {
        return $this->hasMany(ThreadSubscription::class);
    }

    public function getIsSubscribedAttribute()
    {
        return $this->subscriptions()->where('user_id', auth()->id())->exists();
    }
}
