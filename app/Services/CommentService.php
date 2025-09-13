<?php

namespace App\Services;

use App\DTO\Comment\CommentFilterDto;
use App\DTO\Common\PageParams;
use App\Models\Comment;
use App\Models\Post;
use App\Services\Contracts\CommentServiceInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CommentService implements CommentServiceInterface
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

        return $q->paginate($params->perPage, '[*]', 'page', $params->page);
    }

    public function create(array $data): Comment
    {
        return Comment::create([
                'user_id' => $data['user_id'] ?? null,
                'body' => $data['body'],
                'commentable_type' => $this->mapType($data['commentable_type']),
                'commentable_id' => (int)$data['commentable_id'],
            ]);
    }

    public function update(Comment $comment, array $data): Comment
    {
        $comment->fill($data)->save();
        return $comment->refresh();
    }

    public function delete(Comment $comment): void
    {
        $comment->delete();
    }

    public function getUserCommentsToActivePosts(int $userId, int $perPage = 15): LengthAwarePaginator
    {
        return Comment::query()
            ->byUser($userId)
            ->toActivePosts()
            ->with('user')
            ->withCount('replies')
            ->latest()
            ->paginate($perPage);
    }

    public function getCreatedByCurrentUser(int $perPage = 15): LengthAwarePaginator
    {
        return Comment::query()
            ->byUser((int)auth()->id())
            ->with('user')
            ->withCount('replies')
            ->latest()
            ->paginate($perPage);
    }

    public function getByPost(int $postId, int $perPage = 15): LengthAwarePaginator
    {
        return Comment::query()
            ->toPost($postId)
            ->with('user')
            ->withCount('replies')
            ->latest()
            ->paginate($perPage);
    }

    public function getReplies(int $commentId, int $perPage = 15): LengthAwarePaginator
    {
        return Comment::query()
            ->repliesTo($commentId)
            ->with('user')
            ->withCount('replies')
            ->latest()
            ->paginate($perPage);
    }

    private function mapType(string $input): string
    {
        $t = strtolower($input);
        return match ($t) {
            'post' => Post::class,
            'comment' => Comment::class,
            default => $input,
        };
    }
}
