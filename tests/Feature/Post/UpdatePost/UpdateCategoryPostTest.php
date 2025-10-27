<?php

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

require_once __DIR__ . '/../Helpers/StoreHelpers.php';

uses(RefreshDatabase::class);

it('returns unprocessable when category is empty', function () {
    $user = User::factory()->create();

    $category = Category::factory()->create();

    $post = Post::factory()->create([
        'category_id' => $category->id,
        'user_id' => $user->id,
    ]);

    $updateData = [
        'category_id' => '',
    ];

    $this->actingAs($user)
        ->putJson("api/posts/{$post->id}", $updateData)
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('category_id');
});

it('returns unprocessable when user tries to update post with non-existent category', function () {
    $user = User::factory()->create();

    $category = Category::factory()->create();

    $post = Post::factory()->create([
        'category_id' => $category->id,
        'user_id' => $user->id,
    ]);

    $updateData = [
        'category_id' => 10000,
    ];

    $this->actingAs($user)
        ->putJson("api/posts/{$post->id}", $updateData)
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('category_id');
});
