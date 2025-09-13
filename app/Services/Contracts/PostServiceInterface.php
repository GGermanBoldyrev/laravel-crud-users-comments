<?php

namespace App\Services\Contracts;

use App\DTO\Post\PostCreateDto;
use App\DTO\Post\PostUpdateDto;
use App\Models\Post;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface PostServiceInterface
{
    public function paginate(array $filters = [], int $perPage = 15): LengthAwarePaginator;

    public function create(PostCreateDto $dto): Post;

    public function update(Post $post, PostUpdateDto $dto): Post;

    public function delete(Post $post): void;

    public function getActiveByUser(int $userId, int $perPage = 15): LengthAwarePaginator;

    public function getCreatedByCurrentUser(int $perPage = 15): LengthAwarePaginator;
}
