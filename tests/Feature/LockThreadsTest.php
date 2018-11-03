<?php

namespace Tests\Feature;

use App\Thread;
use App\User;
use Illuminate\Http\Response;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LockThreadsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function non_administrators_may_not_lock_threads()
    {
        $this->signIn();

        $thread = create(Thread::class, ['user_id' => auth()->id()]);

        $this->post(route('threads.locked.store', $thread))->assertStatus(403);

        $this->assertFalse($thread->fresh()->locked);
    }

    /** @test */
    function administrators_can_lock_threads()
    {
        $this->withoutExceptionHandling();

        $this->signIn(factory(User::class)->state('administrator')->create());

        $thread = create(Thread::class, ['user_id' => auth()->id()]);

        $this->post(route('threads.locked.store', $thread));

        $this->assertTrue($thread->fresh()->locked);
    }

    /** @test */
    function administrators_can_unlock_threads()
    {
        $this->withoutExceptionHandling();

        $this->signIn(factory(User::class)->state('administrator')->create());

        $thread = create(Thread::class, ['user_id' => auth()->id(), 'locked' => true]);

        $this->delete(route('threads.locked.destroy', $thread));

        $this->assertFalse($thread->fresh()->locked);
    }

    /** @test */
    function once_locked_a_thread_may_not_receive_new_replies()
    {
        $this->signIn();

        $thread = create(Thread::class, ['locked' => true]);

        $this->post($thread->path() . '/replies', [
            'body' => 'Foobar',
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
