<?php

namespace Tests\Feature;

use App\Thread;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReadThreadsTest extends TestCase
{
    use RefreshDatabase;

    public $thread;

    public function setUp()
    {
        parent::setUp();

        $this->thread = factory(Thread::class)->create();
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
        $response = $this->get('/threads/' . $this->thread->id);

        $response->assertStatus(200);
        $response->assertSee($this->thread->title);
    }
}
