<?php

namespace Tests\Feature;

use App\Reply;
use App\Thread;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MentionUsersTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function mentioned_users_in_a_reply_are_notified()
    {
        $this->withoutExceptionHandling();

        // Given I have a user, JohnDoe, who is signed in.
        $john = create(User::class, ['name' => 'JohnDoe']);
        $this->signIn($john);

        // And another user, JaneDoe
        $jane = create(User::class, ['name' => 'JaneDoe']);

        // If we have a thread
        $thread = create(Thread::class);

        // And JohnDoe replies and mentions @JaneDoe
        $reply = make(Reply::class, [
            'body' => '@JaneDoe look at this. And also @Jack'
        ]);

        $this->postJson($thread->path() . '/replies', $reply->toArray());

        // Then JaneDoe should be notified.
        $this->assertCount(1, $jane->notifications);
    }
}
