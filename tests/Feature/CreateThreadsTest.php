<?php

namespace Tests\Feature;

use App\Channel;
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
        $this->get('/threads/create')
            ->assertRedirect(route('login'));
    }

    /** @test */
    function guest_can_not_create_thread()
    {
        $thread = make(Thread::class);

        $this->post('/threads', $thread->toArray())
            ->assertRedirect(route('login'));
    }

    /** @test */
    function a_thread_requires_a_title()
    {
        $this->publishThread(['title' => null])
            ->assertSessionHasErrors('title');
    }

    /** @test */
    function a_thread_requires_a_body()
    {
        $this->publishThread(['body' => null])
            ->assertSessionHasErrors('body');
    }

    /** @test */
    function a_thread_requires_a_valid_channel()
    {
        factory(Channel::class, 2)->create();

        $this->publishThread(['channel_id' => null])
            ->assertSessionHasErrors('channel_id');

        $this->publishThread(['channel_id' => 999])
            ->assertSessionHasErrors('channel_id');
    }

    public function publishThread(array $overrides = [])
    {
        $this->signIn();

        return $this->post('/threads', make(Thread::class, $overrides)->toArray());
    }
}
