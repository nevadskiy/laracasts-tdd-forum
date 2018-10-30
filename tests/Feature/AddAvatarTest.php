<?php

namespace Tests\Feature;

use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AddAvatarTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function only_members_can_add_avatar()
    {
        $this->json('POST', 'api/users/1/avatar')
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /** @test */
    function a_valid_avatar_must_be_provided()
    {
        $this->signIn();

        $response = $this->json('POST', 'api/users/' . auth()->id() .'/avatar', [
            'avatar' => 'not-an-image'
        ]);

        $response->assertStatus(422);
    }

    /** @test */
    function a_user_may_add_an_avatar_to_their_profile()
    {
        $this->signIn();

        Storage::fake('public');

        $this->json('POST', 'api/users/' . auth()->id() .'/avatar', [
            'avatar' => $file = UploadedFile::fake()->image('avatar.jpg')
        ]);

        $this->assertEquals(asset('storage/avatars/' . $file->hashName()), auth()->user()->avatar_path);

        Storage::disk('public')->assertExists('avatars/' . $file->hashName());
    }
}
