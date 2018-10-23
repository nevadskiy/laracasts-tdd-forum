<?php

namespace Tests\Feature;

use App\Reply;
use App\Thread;
use App\User;
use Illuminate\Auth\AuthenticationException;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ParticipateInThreadsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function unauthenticated_users_may_not_add_replies()
    {
        $thread = create(Thread::class);

        $this->post($thread->path() . '/replies', make(Reply::class)->toArray())
            ->assertRedirect(route('login'));
    }

    /** @test */
    function an_authenticated_user_can_participate_in_forum_threads()
    {
        $this->be($user = create(User::class));

        $thread = create(Thread::class);

        $reply = make(Reply::class);
        $this->post($thread->path() . '/replies', $reply->toArray());

        $response = $this->get($thread->path());

        $response->assertSee($reply->body);
    }

    /** @test */
    function a_reply_requires_a_body()
    {
        $this->signIn();

        $thread = create(Thread::class);

        $reply = make(Reply::class, ['body' => null]);

        $this->post($thread->path() . '/replies', $reply->toArray())
            ->assertSessionHasErrors('body');
    }

    /** @test */
    function unauthorized_users_cannot_delete_replies()
    {
        $reply = create(Reply::class);

        $this->delete("/replies/{$reply->id}")
            ->assertRedirect('/login');

        $this->signIn()
            ->delete("/replies/{$reply->id}")
            ->assertStatus(403);
    }

    /** @test */
    function authorized_users_can_delete_replies()
    {
        $this->signIn();

        $reply = create(Reply::class, ['user_id' => auth()->id()]);

        $response = $this->delete("/replies/{$reply->id}");

        $response->assertStatus(302);
        $this->assertDatabaseMissing('replies', ['id' => $reply->id]);
    }

    /** @test */
    function authorized_users_can_update_replies()
    {
        $this->signIn();

        $reply = create(Reply::class, ['user_id' => auth()->id()]);

        $this->put("/replies/{$reply->id}", ['body' => $body = 'You been changed, fool.']);

        $this->assertDatabaseHas('replies', [
            'id' => $reply->id,
            'body' => $body
        ]);
    }

    /** @test */
    function unauthorized_users_cannot_update_replies()
    {
        $reply = create(Reply::class);

        $this->put("/replies/{$reply->id}")
            ->assertRedirect('/login');

        $this->signIn()
            ->put("/replies/{$reply->id}")
            ->assertStatus(403);
    }
}
