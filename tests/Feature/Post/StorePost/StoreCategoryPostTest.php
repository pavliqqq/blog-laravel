<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

require_once __DIR__ . '/../Helpers/StoreHelpers.php';

uses(RefreshDatabase::class);

it('returns unprocessable when user tries to create comment with empty category', function () {
    $user = User::factory()->create();

    $post = postData([
        'category_id' => '',
    ]);

    $this->actingAs($user)
        ->postJson("api/posts/", $post)
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('category_id');
});

it('returns unprocessable when user tries to create post with non-existent category', function () {
    $user = User::factory()->create();

    $post = postData([
        'category_id' => 10000,
    ]);

    $this->actingAs($user)
        ->postJson("api/posts/", $post)
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('category_id');
});
