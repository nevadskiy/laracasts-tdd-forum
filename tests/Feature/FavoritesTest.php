<?php

namespace Tests\Feature;

use App\Reply;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FavoritesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function guest_can_not_favorite_anything()
    {
        $reply = create(Reply::class);

        $this->post('replies/' . $reply->id .'/favorites')
            ->assertRedirect('login');
    }

    /** @test */
    function an_authenticated_user_can_favorite_any_reply()
    {
        // Given
        $this->signIn();
        $reply = create(Reply::class);

        // When
        $this->post('replies/' . $reply->id . '/favorites');

        // Then
        $this->assertCount(1, $reply->favorites);
    }

    /** @test */
    function an_authenticated_user_may_favorite_a_reply_one()
    {
        // Given
        $this->signIn();
        $reply = create(Reply::class);

        // When
        $this->post('replies/' . $reply->id . '/favorites');
        $this->post('replies/' . $reply->id . '/favorites');

        // Then
        $this->assertCount(1, $reply->favorites);
    }

    /** @test */
    function an_authenticated_user_can_unfavorite_any_reply()
    {
        $this->signIn();
        $reply = create(Reply::class);

        $reply->addFavorite();
        $this->assertCount(1, $reply->fresh()->favorites);

        $this->delete('replies/' . $reply->id . '/favorites');
        $this->assertCount(0, $reply->fresh()->favorites);
    }
}
