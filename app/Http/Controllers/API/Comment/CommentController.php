<?php

namespace App\Http\Controllers\API\Comment;

use App\Http\Requests\Comment\StoreCommentRequest;
use App\Http\Resources\CommentResource;
use App\Http\Services\CommentService;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;

class CommentController
{
    public function index(string $id): ResourceCollection
    {
        $post = Post::findOrFail($id);

        $comments = $post->comments()
            ->with('user:id,name')
            ->paginate(10);

        return CommentResource::collection($comments);
    }

    public function store(string $id, StoreCommentRequest $request, CommentService $service): JsonResource
    {
        $post = Post::findOrFail($id);

        $validated = $request->validated();

        $comment = $service->create($validated, $post);

        $comment->load('user:id,email');

        return new CommentResource($comment);
    }

    public function destroy(string $id, Request $request): Response
    {
        $comment = Comment::findOrFail($id);

        if ($request->user()->cannot('delete', $comment)) {
            abort(403);
        };

        $comment->delete();

        return response()->noContent();
    }
}
