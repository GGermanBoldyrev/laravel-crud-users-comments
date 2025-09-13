<?php

namespace App\Repositories\Contracts;

use App\DTO\Comment\CommentCreateDto;
use App\DTO\Comment\CommentFilterDto;
use App\DTO\Comment\CommentUpdateDto;
use App\DTO\Common\PageParams;
use App\Models\Comment;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface CommentRepositoryInterface
{
    public function paginate(CommentFilterDto $dto, PageParams $params): LengthAwarePaginator;

    public function create(CommentCreateDto $dto): Comment;

    public function update(Comment $comment, CommentUpdateDto $dto): Comment;

    public function delete(Comment $comment): void;

    public function getUserCommentsToActivePosts(int $userId, PageParams $params): LengthAwarePaginator;

    public function getByUser(int $userId, PageParams $params): LengthAwarePaginator;

    public function getByPost(int $postId, PageParams $params): LengthAwarePaginator;

    public function getReplies(int $commentId, PageParams $params): LengthAwarePaginator;
}
