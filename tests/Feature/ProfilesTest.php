<?php

namespace Tests\Feature;

use App\Thread;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProfilesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function a_user_has_a_profile()
    {
        $this->withoutExceptionHandling();

        $user = create(User::class);

        $this->get("/profiles/{$user->name}")
            ->assertSee($user->name);
    }

    /** @test */
    function it_displays_all_threads_created_by_associated_user()
    {
        $user = create(User::class);

        $thread = create(Thread::class, ['user_id' => $user->id]);

        $this->get("/profiles/{$user->name}")
            ->assertSee($thread->title)
            ->assertSee($thread->body);
    }
}
