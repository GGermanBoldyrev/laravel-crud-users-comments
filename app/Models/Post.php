<?php

namespace App\Models;

use App\Enums\PostStatus;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'status', 'body'];

    protected function status(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => PostStatus::from($value),
            set: fn (PostStatus|string $value) => $value instanceof PostStatus ? $value->value : $value
        );
    }

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

    public function isActive(): bool
    {
        return $this->status->isActive();
    }
}
