<?php

namespace Tests\Feature;

use App\Thread;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SubscribeToThreadTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function a_user_can_subscribe_to_threads()
    {
        $this->withoutExceptionHandling();

        $this->signIn();

        $thread = create(Thread::class);

        $this->post($thread->path() . '/subscriptions');

        $this->assertCount(1, $thread->fresh()->subscriptions);
    }

    /** @test */
    function a_user_can_unsubscribe_from_threads()
    {
        $this->withoutExceptionHandling();

        $this->signIn();

        $thread = create(Thread::class);

        $thread->subscribe();

        $this->delete($thread->path() . '/subscriptions');

        $this->assertCount(0, $thread->subscriptions);
    }
}
