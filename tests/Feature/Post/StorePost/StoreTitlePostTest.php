<?php

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

require_once __DIR__ . '/../Helpers/StoreHelpers.php';

uses(RefreshDatabase::class);

it('returns unprocessable when user tries to create comment with empty title', function () {
    $user = User::factory()->create();
    $category = Category::factory()->create();

    $post = postData([
        'title' => '',
        'category' => $category->id,
    ]);

    $this->actingAs($user)
        ->postJson("api/posts/", $post)
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('title');
});

it('returns unprocessable when same user trying to create a duplicate title', function () {
    $user = User::factory()->create();
    $category = Category::factory()->create();

    $post = postData([
        'category_id' => $category->id,
    ]);

    $this->actingAs($user)
        ->postJson("api/posts/", $post)
        ->assertCreated();

    $duplicatePost = postData([
        'category_id' => $category->id,
    ]);

    $this->actingAs($user)
        ->postJson("api/posts/", $duplicatePost)
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('title');
});

it('returns unprocessable when another user tries to create a duplicate title', function () {
    $user = User::factory()->create();
    $anotherUser = User::factory()->create();
    $category = Category::factory()->create();

    $post = postData([
        'category_id' => $category->id,
    ]);

    $this->actingAs($user)
        ->postJson("api/posts/", $post)
        ->assertCreated();

    $duplicatePost = postData([
        'category_id' => $category->id,
    ]);

    $this->actingAs($anotherUser)
        ->postJson("api/posts/", $duplicatePost)
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('title');
});
