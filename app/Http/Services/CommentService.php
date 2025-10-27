<?php

namespace App\Http\Services;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class CommentService
{
    public function create(array $data, Post $post)
    {
        return $post->comments()->create([
            'content' => $data['content'],
            'user_id' => Auth::id(),
        ]);
    }
}
