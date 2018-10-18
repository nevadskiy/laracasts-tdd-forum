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

        $response = $this->post('/threads', $thread->toArray());

        // Request to redirected url
        $response = $this->get($response->headers->get('Location'));

        $response->assertSee($thread->title);
        $response->assertSee($thread->body);
    }

    /** @test */
    function guest_cannot_see_the_create_thread_page()
    {
        $this->expectException(AuthenticationException::class);

        $this->get('/threads/create');
    }

    /** @test */
    function guest_can_not_create_thread()
    {
        $this->expectException(AuthenticationException::class);

        $thread = make(Thread::class);
        $this->post('/threads', $thread->toArray());
    }
}
