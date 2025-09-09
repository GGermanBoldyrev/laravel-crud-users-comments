<?php

namespace App\Services;

use App\Enums\PostStatus;
use App\Models\Post;
use App\Services\Contracts\PostServiceInterface;

class PostService implements PostServiceInterface
{
    public function paginate(array $filters = [], int $perPage = 15): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        $q = Post::query()->with('user')->withCount('comments')->latest();

        if (isset($filters['status'])) {
            $status = is_string($filters['status']) ? $filters['status'] : PostStatus::from($filters['status'])->value;
            $q->where('status', $status);
        }

        if (isset($filters['user_id'])) {
            $q->where('user_id', (int)$filters['user_id']);
        }

        return $q->paginate($perPage);
    }

    public function create(array $data): Post
    {
        return Post::create($data);
    }

    public function update(Post $post, array $data): Post
    {
        $post->fill($data)->save();
        return $post->refresh();
    }

    public function delete(Post $post): void
    {
        $post->delete();
    }
}
