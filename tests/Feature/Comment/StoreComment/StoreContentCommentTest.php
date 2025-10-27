<?php

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('returns unprocessable when user tries to create comment with empty content', function () {
    $user = User::factory()->create();
    $post = Post::factory()->create();

    $comment = [
        'content' => '',
        'post_id' => $post->id,
        'user_id' => $user->id,
    ];

    $this->actingAs($user)
        ->postJson("api/posts/{$post->id}/comments", $comment)
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('content');
});
