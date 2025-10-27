<?php

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('shows post', function () {
    $post = Post::factory()->create([
        'title' => 'Nature',
        'content' => 'There, are a lot of blue rivers and lakes on the Earth',
    ]);

    $this->getJson("api/posts/{$post->id}")
        ->assertOk()
        ->assertJsonFragment([
            'title' => 'Nature',
            'content' => 'There, are a lot of blue rivers and lakes on the Earth',
        ]);
});

it('returns 404 if post not found', function () {
    $this->getJson("api/posts/10000")
        ->assertNotFound();
});
