<?php

namespace App\Models;

use App\Enums\PostStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Post",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="status", type="string", enum={"active", "inactive"}, example="active"),
 *     @OA\Property(property="is_active", type="boolean", example=true),
 *     @OA\Property(property="body", type="string", example="Содержимое поста"),
 *     @OA\Property(property="author", ref="#/components/schemas/User"),
 *     @OA\Property(property="comments_count", type="integer", example=5),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */

class Post extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'status', 'body'];

    protected $casts = [
        'status' => PostStatus::class,
    ];
    protected $attributes = [
        'status' => PostStatus::ACTIVE->value,
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function scopeActive($query)
    {
        return $query->where('status', PostStatus::ACTIVE->value);
    }

    public function scopeByUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function isActive(): bool
    {
        return $this->status->isActive();
    }
}
