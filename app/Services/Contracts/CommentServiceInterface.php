<?php

namespace App\Services\Contracts;

use App\Models\Comment;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Resources\Json\ResourceCollection;

interface CommentServiceInterface
{
    // CRUD
    public function paginate(array $filters = [], int $perPage = 15): LengthAwarePaginator;
    public function create(array $data): Comment;
    public function update(Comment $comment, array $data): Comment;
    public function delete(Comment $comment): void;

    // Бизнес запросы
    public function getUserCommentsToActivePosts(int $userId, int $perPage = 15): LengthAwarePaginator;
    public function getCreatedByCurrentUser(int $perPage = 15): LengthAwarePaginator;
    public function getByPost(int $postId, int $perPage = 15): LengthAwarePaginator;
    public function getReplies(int $commentId, int $perPage = 15): LengthAwarePaginator;
}
