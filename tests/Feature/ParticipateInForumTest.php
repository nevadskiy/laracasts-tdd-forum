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

        $thread = factory(Thread::class)->create();

        $reply = factory(Reply::class)->make();
        $this->post('/threads/'.$thread->id.'/replies', $reply->toArray());
    }

    /** @test */
    function an_authenticated_user_can_participate_in_forum_threads()
    {
        $this->be($user = factory(User::class)->create());

        $thread = factory(Thread::class)->create();

        $reply = factory(Reply::class)->make();
        $this->post('/threads/'.$thread->id.'/replies', $reply->toArray());

        $response = $this->get('/threads/' . $thread->id);

        $response->assertSee($reply->body);
    }
}
