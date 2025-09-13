<?php

namespace App\Services\Contracts;

use App\DTO\Comment\CommentCreateDto;
use App\DTO\Comment\CommentFilterDto;
use App\DTO\Comment\CommentUpdateDto;
use App\DTO\Common\PageParams;
use App\Models\Comment;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Resources\Json\ResourceCollection;

interface CommentServiceInterface
{
    // CRUD
    public function paginate(CommentFilterDto $dto, PageParams $params): LengthAwarePaginator;

    public function create(CommentCreateDto $dto): Comment;

    public function update(Comment $comment, CommentUpdateDto $dto): Comment;

    public function delete(Comment $comment): void;

    // Бизнес запросы
    public function getUserCommentsToActivePosts(int $userId, PageParams $params): LengthAwarePaginator;

    public function getCreatedByCurrentUser(PageParams $params): LengthAwarePaginator;

    public function getByPost(int $postId, PageParams $params): LengthAwarePaginator;

    public function getReplies(int $commentId, PageParams $params): LengthAwarePaginator;
}
