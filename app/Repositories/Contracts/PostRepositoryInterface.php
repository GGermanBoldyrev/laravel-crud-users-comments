<?php

namespace App\Repositories\Contracts;

use App\DTO\Common\PageParams;
use App\DTO\Post\PostCreateDto;
use App\DTO\Post\PostUpdateDto;
use App\Models\Post;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface PostRepositoryInterface
{
    public function paginate(array $filters, PageParams $params): LengthAwarePaginator;

    public function create(PostCreateDto $dto): Post;

    public function update(Post $post, PostUpdateDto $dto): Post;

    public function delete(Post $post): void;

    public function getActiveByUser(int $userId, PageParams $params): LengthAwarePaginator;

    public function getByUser(int $userId, PageParams $params): LengthAwarePaginator;
}
