<?php

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('creates comment when user authorized', function () {
    $user = User::factory()->create();
    $post = Post::factory()->create();

    $this->assertDatabaseCount('comments', 0);

    $comment = [
        'content' => 'Awesome',
        'user_id' => $user->id,
        'post_id' => $post->id,
    ];

    $this->actingAs($user)
        ->postJson("api/posts/{$post->id}/comments", $comment)
        ->assertCreated();

    $this->assertDatabaseCount('comments', 1);
    $this->assertDatabaseHas('comments', [
        'content' => 'Awesome',
        'user_id' => $user->id,
    ]);
});

it('returns unauthorized when user tries to create a comment without authentication', function () {
    $post = Post::factory()->create();

    $comment = [
        'content' => 'awesome',
        'post_id' => $post->id,
    ];

    $this->postJson("api/posts/{$post->id}/comments", $comment)
        ->assertUnauthorized();
});
