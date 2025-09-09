<?php

namespace App\Services\Contracts;

use App\Models\Comment;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface CommentServiceInterface
{
    public function paginate(array $filters = [], int $perPage = 15): LengthAwarePaginator;
    public function create(array $data): Comment;
    public function update(Comment $comment, array $data): Comment;
    public function delete(Comment $comment): void;
}
