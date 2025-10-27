<?php

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

require_once __DIR__ . '/../Helpers/StoreHelpers.php';

uses(RefreshDatabase::class);

it('returns unprocessable when trying to update with empty title', function () {
    $user = User::factory()->create();
    $category = Category::factory()->create();

    $post = Post::factory()->create([
        'title' => 'Nature',
        'user_id' => $user->id,
        'category_id' => $category->id,
    ]);

    $updateData = [
        'title' => '',
    ];

    $this->actingAs($user)
        ->putJson("api/posts/{$post->id}", $updateData)
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('title');
});

it('returns unprocessable when updating a post with a title that already exists', function () {
    $user = User::factory()->create();

    Post::factory()->create([
        'title' => 'Nature',
    ]);

    $postToUpdate = Post::factory()->create([
        'title' => 'Cats',
        'user_id' => $user->id,
    ]);

    $updateData = [
        'title' => 'Nature',
        'content' => 'New content',
        'user_id' => $user->id,
        'category_id' => $postToUpdate->category_id,
    ];

    $this->actingAs($user)
        ->putJson("api/posts/{$postToUpdate->id}", $updateData)
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('title');
});

