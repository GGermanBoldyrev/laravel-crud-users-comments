<?php

namespace App\Services;

use App\Models\Comment;
use App\Models\Post;
use App\Services\Contracts\CommentServiceInterface;

class CommentService implements CommentServiceInterface
{
    public function paginate(array $filters = [], int $perPage = 15): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        $q = Comment::query()->with(['user'])->withCount('replies')->latest();

        if (isset($filters['commentable_type'], $filters['commentable_id'])) {
            $q->where('commentable_type', $this->mapType($filters['commentable_type']))
                ->where('commentable_id', (int)$filters['commentable_id']);
        }

        return $q->paginate($perPage);
    }

    public function create(array $data): Comment
    {
        return Comment::create([
                'user_id' => $data['user_id'] ?? null,
                'body' => $data['body'],
            ] + [
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
