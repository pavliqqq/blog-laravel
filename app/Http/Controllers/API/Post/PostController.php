<?php

namespace App\Http\Controllers\API\Post;

use App\Http\Requests\Post\StorePostRequest;
use App\Http\Requests\Post\UpdatePostRequest;
use App\Http\Resources\PostResource;
use App\Http\Services\PostService;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;

class PostController
{
    public function index(): ResourceCollection
    {
        $posts = Post::with(['user:id,name', 'category'])
            ->paginate(10);

        return PostResource::collection($posts);
    }

    public function show(string $id): JsonResource
    {
        $post = Post::with(['user:id,name,email', 'category'])
            ->findOrFail($id);

        return new PostResource($post);
    }

    public function store(StorePostRequest $request, PostService $service): JsonResource
    {
        $validated = $request->validated();

        $post = $service->create($validated);

        $post->load(['user:id,name', 'category']);

        return new PostResource($post);
    }

    public function update(string $id, UpdatePostRequest $request): JsonResource
    {
        $post = Post::findOrFail($id);

        if ($request->user()->cannot('update', $post)) {
            abort(403);
        }

        $validated = $request->validated();

        $post->update($validated);

        $post->load(['user:id,name', 'category']);

        return new PostResource($post);
    }

    public function destroy(string $id, Request $request): Response
    {
        $post = Post::findOrFail($id);

        if ($request->user()->cannot('delete', $post)) {
            abort(403);
        }

        $post->delete();

        return response()->noContent();
    }
}
