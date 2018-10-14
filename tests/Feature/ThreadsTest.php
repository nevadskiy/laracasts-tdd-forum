<?php

namespace Tests\Feature;

use App\Thread;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ThreadsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_browse_threads()
    {
        $thread = factory(Thread::class)->create();

        $response = $this->get('/threads');

        $response->assertStatus(200);
        $response->assertSee($thread->title);

        $response = $this->get('/threads/' . $thread->id);
        $response->assertSee($thread->title);
    }
}
