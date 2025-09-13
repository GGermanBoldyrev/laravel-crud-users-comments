<?php

namespace App\Services;

use App\DTO\Common\PageParams;
use App\DTO\Post\PostCreateDto;
use App\DTO\Post\PostUpdateDto;
use App\Models\Post;
use App\Repositories\Contracts\PostRepositoryInterface;
use App\Services\Contracts\PostServiceInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class PostService implements PostServiceInterface
{
    public function __construct(private readonly PostRepositoryInterface $repository) {}
    public function paginate(array $filters, PageParams $params): LengthAwarePaginator
    {
        return $this->repository->paginate($filters, $params);
    }

    public function create(PostCreateDto $dto): Post
    {
        return $this->repository->create($dto);
    }

    public function update(Post $post, PostUpdateDto $dto): Post
    {
        return $this->repository->update($post, $dto);
    }

    public function delete(Post $post): void
    {
        $this->repository->delete($post);
    }

    public function getActiveByUser(int $userId, PageParams $params): LengthAwarePaginator
    {
        return $this->repository->getActiveByUser($userId, $params);
    }

    public function getCreatedByCurrentUser(PageParams $params): LengthAwarePaginator
    {
        return $this->repository->getByUser((int)auth()->id(), $params);
    }
}
