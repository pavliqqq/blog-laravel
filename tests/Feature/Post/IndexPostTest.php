<?php

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('gets all posts', function () {
    Post::factory()->count(10)->create();

    $response = $this->getJson('api/posts/');

    $response->assertOk()
        ->assertJsonCount(10, 'data');
});
