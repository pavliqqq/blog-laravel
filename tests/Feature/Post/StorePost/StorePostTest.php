<?php

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

require_once __DIR__ . '/../Helpers/StoreHelpers.php';

uses(RefreshDatabase::class);

it('creates post when user authorized', function () {
    $user = User::factory()->create();

    $this->assertDatabaseCount('posts', 0);

    $category = Category::factory()->create();
    $post = postData([
        'category_id' => $category->id,
    ]);

    $this->actingAs($user)
        ->postJson("api/posts/", $post)
        ->assertCreated();

    $this->assertDatabaseCount('posts', 1);
    $this->assertDatabaseHas('posts', [
        'title' => 'Nature',
        'user_id' => $user->id,
    ]);
});

it('returns unauthorized when user tries to create a post without authentication', function () {
    $category = Category::factory()->create();
    $post = postData([
        'category_id' => $category->id,
    ]);

    $this->postJson("api/posts/", $post)
        ->assertUnauthorized();
});
