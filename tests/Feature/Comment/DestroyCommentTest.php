<?php

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('deletes comment if user is author', function () {
    $user = User::factory()->create();
    $post = Post::factory()->create();

    $comment = Comment::factory()->create([
        'user_id' => $user->id,
        'post_id' => $post->id,
    ]);

    $this->actingAs($user)
        ->deleteJson("api/comments/{$comment->id}")
        ->assertNoContent();

    $this->assertDatabaseMissing('comments', [
        'id' => $comment->id,
    ]);
});

it('returns 403 when user tries to delete comment if he is not author', function () {
    $userCreator = User::factory()->create();
    $post = Post::factory()->create();
    $user = User::factory()->create();

    $comment = Comment::factory()->create([
        'user_id' => $userCreator->id,
        'post_id' => $post->id,
    ]);

    $this->actingAs($user)
        ->deleteJson("api/comments/{$comment->id}")
        ->assertForbidden();
});

it('returns 404 if comment not found for delete', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->deleteJson("api/comments/10000")
        ->assertNotFound();
});
