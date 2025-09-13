<?php

namespace App\Repositories;

use App\DTO\Common\PageParams;
use App\DTO\Post\PostCreateDto;
use App\DTO\Post\PostUpdateDto;
use App\Enums\PostStatus;
use App\Models\Post;
use App\Repositories\Contracts\PostRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class PostRepository implements PostRepositoryInterface
{
    public function paginate(array $filters, PageParams $params): LengthAwarePaginator
    {
        $q = Post::query()->with('user')->withCount('comments')->latest();

        if (isset($filters['status'])) {
            $status = is_string($filters['status']) ? $filters['status'] : PostStatus::from($filters['status'])->value;
            $q->where('status', $status);
        }

        if (isset($filters['user_id'])) {
            $q->where('user_id', (int)$filters['user_id']);
        }

        return $q->paginate(...$params->toArgs());
    }

    public function create(PostCreateDto $dto): Post
    {
        return Post::create($dto->toEloquentCreate());
    }

    public function update(Post $post, PostUpdateDto $dto): Post
    {
        $post->fill($dto->toEloquentUpdate())->save();
        return $post->refresh();
    }

    public function delete(Post $post): void
    {
        $post->delete();
    }

    public function getActiveByUser(int $userId, PageParams $params): LengthAwarePaginator
    {
        return Post::query()
            ->active()
            ->byUser($userId)
            ->with('user')
            ->withCount('comments')
            ->latest()
            ->paginate(...$params->toArgs());
    }

    public function getByUser(int $userId, PageParams $params): LengthAwarePaginator
    {
        return Post::query()
            ->byUser($userId)
            ->with('user')
            ->withCount('comments')
            ->latest()
            ->paginate(...$params->toArgs());
    }
}
