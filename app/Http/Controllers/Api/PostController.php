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
        $data = $request->validated();
        $data['user_id'] = $request->user()->id;

        $post = $this->service->create($data);
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
        $this->authorize('update', $post);

        $post = $this->service->update($post, $request->validated());
        $post->loadCount('comments')->load('user');

        return new PostResource($post);
    }

    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);

        $this->service->delete($post);
        return response()->noContent();
    }

    public function userActive(int $userId): ResourceCollection
    {
        $posts = $this->service->getActiveByUser($userId, request('per_page', 15));
        return PostResource::collection($posts);
    }

    public function mine(): ResourceCollection | int
    {
        $posts = $this->service->getCreatedByCurrentUser(request('per_page', 15));
        return PostResource::collection($posts);
    }
}
