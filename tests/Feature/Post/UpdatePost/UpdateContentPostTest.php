<?php

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

require_once __DIR__ . '/../Helpers/StoreHelpers.php';

uses(RefreshDatabase::class);

it('returns unprocessable when content is empty', function () {
    $user = User::factory()->create();
    $category = Category::factory()->create();

    $post = Post::factory()->create([
        'category_id' => $category->id,
        'user_id' => $user->id,
    ]);

    $updateData = [
        'content' => '',
    ];

    $this->actingAs($user)
        ->putJson("api/posts/{$post->id}", $updateData)
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('content');
});
