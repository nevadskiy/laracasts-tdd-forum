<?php

namespace App;

use Illuminate\Redis\RedisManager;

class Trending
{
    /**
     * @var RedisManager
     */
    private $redis;

    /**
     * Trending constructor.
     * @param RedisManager $redis
     */
    public function __construct(RedisManager $redis)
    {
        $this->redis = $redis;
    }


    /**
     * @return array
     */
    public function get()
    {
        return array_map('json_decode', $this->redis->zrevrange($this->cacheKey(), 0, 4));
    }

    /**
     * Push threads to trending threads
     *
     * @param Thread $thread
     */
    public function push(Thread $thread)
    {
        $this->redis->zincrby($this->cacheKey(), 1, json_encode([
            'title' => $thread->title,
            'path' => $thread->path(),
        ]));
    }

    /**
     * Get trending threads cache key
     *
     * @return string
     */
    protected function cacheKey()
    {
        return app()->environment('testing') ? 'testing_trending_threads' : 'trending_threads';
    }

    /**
     * Reset trending threads
     */
    public function reset()
    {
        $this->redis->del([$this->cacheKey()]);
    }
}