<?php

namespace Tests\Unit;

use App\Reply;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function it_can_fetch_its_most_recent_reply()
    {
        $user = create(User::class);
        $reply = create(Reply::class, ['user_id' => $user->id]);

        $this->assertEquals($user->lastReply->id, $reply->id);
    }

    /** @test */
    function it_can_determine_their_avatar_path()
    {
        $user = create(User::class, ['avatar_path' => null]);

        $this->assertEquals(asset('images/user-default.jpg'), $user->avatar());

        $user->avatar_path = 'avatars/me.jpg';

        $this->assertEquals(asset('storage/avatars/me.jpg'), $user->avatar());
    }
}
