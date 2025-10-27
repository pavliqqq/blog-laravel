<?php

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('gets all comments for post', function () {
    $post = Post::factory()->create();
    Comment::factory()->count(10)->create([
        'post_id' => $post->id,
    ]);

    $this->getJson("api/posts/{$post->id}/comments")
        ->assertOk()
        ->assertJsonCount(10, 'data');
});
