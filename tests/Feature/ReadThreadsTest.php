<?php

namespace Tests\Feature;

use App\Channel;
use App\Reply;
use App\Thread;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReadThreadsTest extends TestCase
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
    function a_user_can_read_all_threads()
    {
        $response = $this->get('/threads');

        $response->assertStatus(200);
        $response->assertSee($this->thread->title);
    }

    /** @test */
    function a_user_can_read_specific_thread()
    {
        $response = $this->get($this->thread->path());

        $response->assertStatus(200);
        $response->assertSee($this->thread->title);
    }

    /** @test */
    function a_user_can_filter_threads_according_to_a_channel()
    {
        $channel = create(Channel::class);

        $threadInChannel = create(Thread::class, ['channel_id' => $channel->id]);
        $threadNotInChannel= create(Thread::class);

        $this->get('/threads/' . $channel->slug)
            ->assertSee($threadInChannel->title)
            ->assertDontSee($threadNotInChannel->title);
    }

    /** @test */
    function it_can_be_filtered_by_username()
    {
        $this->signIn(create(User::class, ['name' => 'JohnDoe']));

        $threadByJohn = create(Thread::class, ['user_id' => auth()->id()]);
        $threadNotByJohn = create(Thread::class);

        $this->get('threads?by=JohnDoe')
            ->assertSee($threadByJohn->title)
            ->assertDontSee($threadNotByJohn->title);
    }

    /** @test */
    function a_user_can_filter_it_by_popularity()
    {
        // Given three thread with different replies count
        $threadWithTwoReplies = create(Thread::class);
        create(Reply::class, ['thread_id' => $threadWithTwoReplies->id], 2);

        $threadWithThreeReplies = create(Thread::class);
        create(Reply::class, ['thread_id' => $threadWithThreeReplies->id], 3);

        $threadWithNoReplies = $this->thread;

        // When going threads page with filter by popularity
        $response = $this->getJson('threads?popular=1')->json();

        // Assert the most replied in the first order
        $this->assertEquals([3, 2, 0], array_column($response['data'], 'replies_count'));
    }

    /** @test */
    function a_user_can_filter_threads_by_those_that_are_unanswered()
    {
        $thread = create(Thread::class);
        create(Reply::class, ['thread_id' => $thread->id]);

        $response = $this->getJson('threads?unanswered=1')->json();

        $this->assertNotContains($thread->id, array_column($response['data'], 'id'));
        $this->assertContains($this->thread->id, array_column($response['data'], 'id'));
    }

    /** @test */
    function a_user_can_request_all_replies_for_a_given_thread()
    {
        $thread = create(Thread::class);
        create(Reply::class, ['thread_id' => $thread->id], 21);

        $response = $this->getJson($thread->path() . '/replies')->json();

        $this->assertCount(20, $response['data']);
        $this->assertEquals(21, $response['total']);
    }

    /** @test */
    function it_record_visits_for_each_time_the_thread_is_read()
    {
        $thread = create(Thread::class);

        $this->assertSame(0, $thread->fresh()->visits);

        $this->call('GET', $thread->path());

        $this->assertEquals(1, $thread->fresh()->visits);
    }
}
