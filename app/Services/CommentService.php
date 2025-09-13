<?php

namespace App\Services;

use App\DTO\Comment\CommentCreateDto;
use App\DTO\Comment\CommentFilterDto;
use App\DTO\Comment\CommentUpdateDto;
use App\DTO\Common\PageParams;
use App\Models\Comment;
use App\Repositories\Contracts\CommentRepositoryInterface;
use App\Services\Contracts\CommentServiceInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CommentService implements CommentServiceInterface
{
    public function __construct(private readonly CommentRepositoryInterface $repository) {}
    public function paginate(CommentFilterDto $dto, PageParams $params): LengthAwarePaginator
    {
        return $this->repository->paginate($dto, $params);
    }

    public function create(CommentCreateDto $dto): Comment
    {
        return $this->repository->create($dto);
    }

    public function update(Comment $comment, CommentUpdateDto $dto): Comment
    {
        return $this->repository->update($comment, $dto);
    }

    public function delete(Comment $comment): void
    {
        $this->repository->delete($comment);
    }

    public function getUserCommentsToActivePosts(int $userId, PageParams $params): LengthAwarePaginator
    {
        return $this->repository->getUserCommentsToActivePosts($userId, $params);
    }

    public function getCreatedByCurrentUser(PageParams $params): LengthAwarePaginator
    {
        return $this->repository->getByUser((int)auth()->id(), $params);
    }

    public function getByPost(int $postId, PageParams $params): LengthAwarePaginator
    {
        return $this->repository->getByPost($postId, $params);
    }

    public function getReplies(int $commentId, PageParams $params): LengthAwarePaginator
    {
        return $this->repository->getReplies($commentId, $params);
    }
}
