<?php

namespace Tests\Feature;

use App\Thread;
use App\User;
use Illuminate\Auth\AuthenticationException;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateThreadsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function an_authenticated_user_can_create_new_forum_threads()
    {
        $this->signIn();

        $thread = make(Thread::class);
        $this->post('/threads', $thread->toArray());

        $response = $this->get('/threads/' . $thread->id);

        $response->assertSee($thread->title);
        $response->assertSee($thread->body);
    }

    /** @test */
    function guest_can_not_create_thread()
    {
        $this->expectException(AuthenticationException::class);

        $thread = make(Thread::class);
        $this->post('/threads', $thread->toArray());
    }
}
