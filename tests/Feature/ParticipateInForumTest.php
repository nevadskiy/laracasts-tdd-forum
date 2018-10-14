<?php

namespace Tests\Feature;

use App\Reply;
use App\Thread;
use App\User;
use Illuminate\Auth\AuthenticationException;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ParticipateInForumTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function unauthenticated_users_may_not_add_replies()
    {
        $this->expectException(AuthenticationException::class);

        $thread = create(Thread::class);

        $reply = make(Reply::class);
        $this->post('/threads/'.$thread->id.'/replies', $reply->toArray());
    }

    /** @test */
    function an_authenticated_user_can_participate_in_forum_threads()
    {
        $this->be($user = create(User::class));

        $thread = create(Thread::class);

        $reply = make(Reply::class);
        $this->post('/threads/'.$thread->id.'/replies', $reply->toArray());

        $response = $this->get('/threads/' . $thread->id);

        $response->assertSee($reply->body);
    }
}
