<?php

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('deletes post if user is creator', function () {
    $user = User::factory()->create();

    $post = Post::factory()->create([
        'user_id' => $user->id,
    ]);

    $this->actingAs($user)
        ->deleteJson("api/posts/{$post->id}")
        ->assertNoContent();

    $this->assertDatabaseMissing('posts', [
        'id' => $post->id,
    ]);
});

it('returns 403 if user is not creator', function () {
    $userCreator = User::factory()->create();

    $user = User::factory()->create();

    $post = Post::factory()->create([
        'user_id' => $userCreator->id,
    ]);

    $this->actingAs($user)
        ->deleteJson("api/posts/{$post->id}")
        ->assertForbidden();
});

it('returns 404 if post not found for delete', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->deleteJson("api/posts/10000")
        ->assertNotFound();
});
