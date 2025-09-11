<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * @OA\Schema(
 *     schema="Comment",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="body", type="string", example="Текст комментария"),
 *     @OA\Property(property="author", ref="#/components/schemas/User"),
 *     @OA\Property(
 *         property="commentable",
 *         type="object",
 *         @OA\Property(property="type", type="string", example="Post"),
 *         @OA\Property(property="id", type="integer", example=1)
 *     ),
 *     @OA\Property(property="replies_count", type="integer", example=3),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */

class Comment extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'body', 'commentable_type', 'commentable_id'];

    public function commentable()
    {
        return $this->morphTo();
    }

    public function replies(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeByUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeToActivePosts(Builder $q): Builder
    {
        return $q->whereHasMorph(
            'commentable',
            [Post::class],
            fn (Builder $b) => $b->active()
        );
    }

    public function scopeToPost(Builder $q, int $postId): Builder
    {
        return $q->where('commentable_type', Post::class)
            ->where('commentable_id', $postId);
    }

    public function scopeRepliesTo(Builder $q, int $commentId): Builder
    {
        return $q->where('commentable_type', Comment::class)
            ->where('commentable_id', $commentId);
    }
}
