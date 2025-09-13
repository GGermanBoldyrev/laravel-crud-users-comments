<?php

namespace App\Repositories;

use App\DTO\Comment\CommentCreateDto;
use App\DTO\Comment\CommentFilterDto;
use App\DTO\Comment\CommentUpdateDto;
use App\DTO\Common\PageParams;
use App\Models\Comment;
use App\Repositories\Contracts\CommentRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CommentRepository implements CommentRepositoryInterface
{
    public function paginate(CommentFilterDto $dto, PageParams $params): LengthAwarePaginator
    {
        $q = Comment::query()
            ->with(['user'])
            ->withCount('replies')
            ->latest();

        if ($dto->hasScope()) {
            $q->where('commentable_type', $dto->eloquentType())
              ->where('commentable_id', $dto->commentableId);
        }

        return $q->paginate(...$params->toArgs());
    }

    public function create(CommentCreateDto $dto): Comment
    {
        return Comment::create([
            'user_id' => $dto->userId,
            'body' => $dto->body,
            'commentable_type' => $dto->eloquentType(),
            'commentable_id' => $dto->commentableId,
        ]);
    }

    public function update(Comment $comment, CommentUpdateDto $dto): Comment
    {
        $comment->fill($dto->toEloquentUpdate())->save();
        return $comment->refresh();
    }

    public function delete(Comment $comment): void
    {
        $comment->delete();
    }

    public function getUserCommentsToActivePosts(int $userId, PageParams $params): LengthAwarePaginator
    {
        return Comment::query()
            ->byUser($userId)
            ->toActivePosts()
            ->with('user')
            ->withCount('replies')
            ->latest()
            ->paginate(...$params->toArgs());
    }

    public function getByUser(int $userId, PageParams $params): LengthAwarePaginator
    {
        return Comment::query()
            ->byUser($userId)
            ->with('user')
            ->withCount('replies')
            ->latest()
            ->paginate(...$params->toArgs());
    }

    public function getByPost(int $postId, PageParams $params): LengthAwarePaginator
    {
        return Comment::query()
            ->toPost($postId)
            ->with('user')
            ->withCount('replies')
            ->latest()
            ->paginate(...$params->toArgs());
    }

    public function getReplies(int $commentId, PageParams $params): LengthAwarePaginator
    {
        return Comment::query()
            ->repliesTo($commentId)
            ->with('user')
            ->withCount('replies')
            ->latest()
            ->paginate(...$params->toArgs());
    }
}
