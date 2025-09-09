<?php

namespace App\Services\Contracts;

use App\Models\Post;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface PostServiceInterface
{
    public function paginate(array $filters = [], int $perPage = 15): LengthAwarePaginator;
    public function create(array $data): Post;
    public function update(Post $post, array $data): Post;
    public function delete(Post $post): void;
    public function getActiveByUser(int $userId, int $perPage = 15): LengthAwarePaginator;
    public function getCreatedByCurrentUser(int $perPage = 15): LengthAwarePaginator;
}
