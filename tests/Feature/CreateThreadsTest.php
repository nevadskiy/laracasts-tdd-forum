<?php

namespace Tests\Feature;

use App\Activity;
use App\Channel;
use App\Reply;
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
    function authenticated_users_must_first_confirm_their_email_address_before_creating_threads()
    {
        $this->withoutExceptionHandling();

        $this->publishThread()->assertRedirect()->assertSessionHas('flash');
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

    /** @test */
    function authorized_users_can_delete_threads()
    {
        $this->signIn();

        $thread = create(Thread::class, ['user_id' => auth()->id()]);
        $reply = create(Reply::class, ['thread_id' => $thread->id]);

        $response = $this->json('DELETE', $thread->path());

        $response->assertStatus(204);
        $this->assertDatabaseMissing('threads', ['id' => $thread->id]);
        $this->assertDatabaseMissing('replies', ['id' => $reply->id]);

        $this->assertEquals(0, Activity::count());
    }

    /** @test */
    function unauthorized_users_cannot_delete_threads()
    {
        // Given thread
        $thread = create(Thread::class);

        // Guest attempt
        $this->delete($thread->path())
            ->assertRedirect('/login');

        $this->signIn();

        // Authenticated attempt
        $this->delete($thread->path())
            ->assertStatus(403);

        $this->assertDatabaseHas('threads', ['id' => $thread->id]);
    }

    protected function publishThread(array $overrides = [])
    {
        $this->signIn();

        return $this->post('/threads', make(Thread::class, $overrides)->toArray());
    }
}
