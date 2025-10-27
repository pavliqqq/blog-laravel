<?php

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

require_once __DIR__ . '/../Helpers/StoreHelpers.php';

uses(RefreshDatabase::class);

it('updates post when user is author', function () {
    $user = User::factory()->create();

    $category = Category::factory()->create();
    $newCategory = Category::factory()->create();

    $post = Post::factory()->create([
        'title' => 'Nature',
        'user_id' => $user->id,
        'category_id' => $category->id,
    ]);

    $updatedData = [
        'title' => 'New title',
        'content' => 'New content',
        'category_id' => $newCategory->id,
    ];

    $this->actingAs($user)
        ->putJson("api/posts/{$post->id}", $updatedData)
        ->assertOk();

    $this->assertDatabaseHas('posts', $updatedData);
    $this->assertDatabaseMissing('posts', [
        'id' => $post->id,
        'title' => $post->title,
    ]);
});

it('returns forbidden when user tries to update post when he is not author', function () {
    $user = User::factory()->create();
    $newUser = User::factory()->create();

    $category = Category::factory()->create();

    $post = Post::factory()->create([
        'title' => 'Nature',
        'user_id' => $user->id,
        'category_id' => $category->id,
    ]);

    $updatedData = [
        'title' => 'New title',
        'content' => 'New content',
        'category_id' => $category->id,
    ];

    $this->actingAs($newUser)
        ->putJson("api/posts/{$post->id}", $updatedData)
        ->assertForbidden();
});


