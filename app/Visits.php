<?php

namespace App;

use Illuminate\Redis\RedisManager;

class Visits
{
    /**
     * @var Thread
     */
    private $thread;

    /**
     * @var \Illuminate\Foundation\Application|mixed
     */
    private $redis;

    /**
     * Visits constructor.
     * @param Thread $thread
     */
    public function __construct(Thread $thread)
    {
        $this->thread = $thread;
        $this->redis = app(RedisManager::class);
    }

    /**
     *
     */
    public function record()
    {
        $this->redis->incr($this->cacheKey());
    }

    /**
     *
     */
    public function reset()
    {
        $this->redis->del($this->cacheKey());
    }

    /**
     * @return int
     */
    public function count()
    {
        return $this->redis->get($this->cacheKey()) ?? 0;
    }

    /**
     * @return string
     */
    protected function cacheKey()
    {
        return "thread.{$this->thread->id}.visits";
    }
}