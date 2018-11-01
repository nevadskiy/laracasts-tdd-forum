<?php

namespace Tests\Unit;

use App\Channel;
use App\Notifications\ThreadWasUpdated;
use App\Thread;
use App\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Notification;
use Redis;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ThreadTest extends TestCase
{
    use RefreshDatabase;

    /** @var Thread */
    private $thread;

    public function setUp()
    {
        parent::setUp();

        $this->thread = create(Thread::class);
    }

    /** @test */
    function it_has_replies()
    {
        $this->assertInstanceOf(Collection::class, $this->thread->replies);
    }

    /** @test */
    function it_can_make_a_string_path()
    {
        $this->assertEquals('/threads/' . $this->thread->channel->slug . '/' . $this->thread->id, $this->thread->path());
    }

    /** @test */
    function it_has_a_creator()
    {
        $this->assertInstanceOf(User::class, $this->thread->creator);
    }

    /** @test */
    function it_can_add_a_reply()
    {
        $this->thread->addReply([
            'body' => 'Foobar',
            'user_id' => 1
        ]);

        $this->assertCount(1, $this->thread->replies);
    }

    /** @test */
    function it_thread_notifies_all_registered_subscribers_when_a_reply_is_added()
    {
        Notification::fake();

        $this->signIn()
            ->thread
            ->subscribe()
            ->addReply([
                'body' => 'Foobar',
                'user_id' => 999
            ]);

        Notification::assertSentTo(auth()->user(), ThreadWasUpdated::class);
    }

    /** @test */
    function it_belongs_to_a_channel()
    {
        $this->assertInstanceOf(Channel::class, $this->thread->channel);
    }

    /** @test */
    function it_can_be_used_for_subscriptions()
    {
        // Given
        $this->signIn();

        // When
        $this->thread->subscribe();

        // Then
        $this->assertEquals(1, $this->thread->subscriptions()->where('user_id', auth()->id())->count());
    }

    /** @test */
    function it_can_be_unsubscribed_from()
    {
        // Given
        $this->signIn();

        // When
        $this->thread->subscribe();
        $this->thread->unsubscribe();

        // Then
        $this->assertCount(0, $this->thread->subscriptions);
    }

    /** @test */
    function it_knows_if_the_authenticated_user_is_subscribe_to_it()
    {
        $this->signIn();

        $this->assertFalse($this->thread->isSubscribed);

        $this->thread->subscribe();

        $this->assertTrue($this->thread->isSubscribed);
    }

    /** @test */
    function it_can_check_if_the_authenticated_user_has_read_all_replies()
    {
        $this->signIn();

        $user = auth()->user();

        $this->assertTrue($this->thread->hasUpdatesFor($user));

        $user->readThread($this->thread);

        $this->assertFalse($this->thread->hasUpdatesFor($user));
    }
}
