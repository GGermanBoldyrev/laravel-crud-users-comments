<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Post\PostStoreRequest;
use App\Http\Requests\Post\PostUpdateRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Services\Contracts\PostServiceInterface;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PostController extends Controller
{
    public function __construct(private readonly PostServiceInterface $service) {}

    public function index(): ResourceCollection
    {
        $filters = request()->only(['status','user_id']);
        $posts = $this->service->paginate($filters, request('per_page', 15));
        return PostResource::collection($posts);
    }

    public function store(PostStoreRequest $request): PostResource
    {
        $post = $this->service->create($request->validated());
        $post->loadCount('comments')->load('user');
        return new PostResource($post);
    }

    public function show(Post $post): PostResource
    {
        $post->loadCount('comments')->load('user');
        return new PostResource($post);
    }

    public function update(PostUpdateRequest $request, Post $post): PostResource
    {
        $post = $this->service->update($post, $request->validated());
        $post->loadCount('comments')->load('user');
        return new PostResource($post);
    }

    public function destroy(Post $post)
    {
        $this->service->delete($post);
        return response()->noContent();
    }
}
