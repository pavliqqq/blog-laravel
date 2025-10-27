<?php

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

require_once __DIR__ . '/../Helpers/StoreHelpers.php';

uses(RefreshDatabase::class);

it('returns unprocessable when user tries to create comment with empty content', function () {
    $user = User::factory()->create();
    $category = Category::factory()->create();

    $post = postData([
        'content' => '',
        'category_id' => $category->id,
    ]);

    $this->actingAs($user)
        ->postJson("api/posts/", $post)
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('content');
});
