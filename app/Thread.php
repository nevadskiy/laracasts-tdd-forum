<?php

namespace App;

use App\Events\ThreadHasNewReply;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Thread extends Model
{
    use RecordsActivity, Searchable;

    /**
     * @var array
     */
    protected $fillable = [
        'user_id',
        'channel_id',
        'visits',
        'title',
        'body',
        'slug',
        'best_reply_id',
        'locked'
    ];

    /**
     * @var array
     */
    protected $casts = [
        'locked' => 'boolean'
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

        static::created(function (Thread $thread) {
            // Thread->update() will hit mutator
            $thread->update(['slug' => $thread->title]);
        });
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * @return string
     */
    public function path()
    {
        return "/threads/{$this->channel->slug}/{$this->slug}";
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
     * @return Reply $reply
     */
    public function addReply($reply)
    {
        /** @var Reply $reply */
        $reply = $this->replies()->create($reply);

        event(new ThreadHasNewReply($reply));

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

    /**
     * @param User|null $user
     * @return $this
     */
    public function subscribe(User $user = null)
    {
        $this->subscriptions()->create([
            'user_id' => $user ? $user->id : auth()->id()
        ]);

        return $this;
    }

    /**
     * @param User|null $user
     */
    public function unsubscribe(User $user = null)
    {
        $this->subscriptions()
            ->where('user_id', $user ? $user->id : auth()->id())
            ->delete();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function subscriptions()
    {
        return $this->hasMany(ThreadSubscription::class);
    }

    /**
     * @return bool
     */
    public function getIsSubscribedAttribute()
    {
        return $this->subscriptions()->where('user_id', auth()->id())->exists();
    }

    /**
     * @param $user
     * @return bool
     * @throws \Exception
     */
    public function hasUpdatesFor($user)
    {
        $key = $user->visitedThreadCacheKey($this);

        return $this->updated_at > cache($key);
    }

    public function setSlugAttribute($value)
    {
        $slug = str_slug($value);

        if (static::whereSlug($slug)->exists()) {
            $slug = "{$slug}-" . $this->id;
        }

        $this->attributes['slug'] = $slug;
    }

    public function incrementSlug($slug)
    {
        // Get latest by ID value with given title and get its slug
        $max = static::whereTitle($this->title)->latest('id')->value('slug');

        // Get last character and check if it is numeric
        if (is_numeric($max[-1])) {
            // Replace it with incremented number
            return preg_replace_callback('/(\d+)$/', function ($matches) {
                return $matches[1] + 1;
            }, $max);
        }

        return "{$slug}-2";
    }

    public function markAsBestReply(Reply $reply)
    {
        $this->update(['best_reply_id' => $reply->id]);
    }
}
