<?php

namespace Tests\Feature;

use App\Thread;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateThreadsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function only_thread_author_can_update_their_thread()
    {
        $this->withoutExceptionHandling();

        $this->signIn();

        $thread = create(Thread::class, ['user_id' => auth()->id()]);

        $this->put($thread->path(), [
            'title' => $title = 'Changed',
            'body' => $body = 'Changed body',
        ]);

        $this->assertEquals($title, $thread->fresh()->title);
        $this->assertEquals($body, $thread->fresh()->body);
    }

    /** @test */
    function a_thread_requires_a_title_and_body_to_be_updated()
    {
        $this->signIn();

        $thread = create(Thread::class, ['user_id' => auth()->id()]);

        $this->put($thread->path(), ['title' => 'Changed'])
            ->assertSessionHasErrors('body');

        $this->put($thread->path(), ['body' => 'Changed body'])
            ->assertSessionHasErrors('title');
    }

    /** @test */
    function unauthorized_users_may_not_update_threads()
    {
        $this->signIn();

        $thread = create(Thread::class);

        $this->put($thread->path(), ['title' => 'Changed'])
            ->assertStatus(403);
    }
}
