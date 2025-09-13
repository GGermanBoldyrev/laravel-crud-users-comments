<?php

namespace App\Mapper;

final readonly class CommentableTypeMapper
{
    public static function toModel(string $alias): string
    {
        return match ($alias) {
            'post' => \App\Models\Post::class,
            'comment' => \App\Models\Comment::class,
            default => $alias,
        };
    }
}
