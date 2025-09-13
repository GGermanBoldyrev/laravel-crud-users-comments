<?php

namespace App\DTO\Comment;

use Illuminate\Http\Request;

final readonly class CommentFilterDto
{
    public function __construct(
        public ?string $commentableType,
        public ?int $commentableId,
    ) {}

    public static function fromRequest(Request $request): self
    {
        $type = $request->string('commentable_type')->trim()->lower()->toString();
        $id = $request->integer('commentable_id') ?: null;

        return new self(
            commentableType: $type !== '' ? $type : null,
            commentableId: $id,
        );
    }

    public function eloquentType(): ?string
    {
        if ($this->commentableType === null) return null;

        return match ($this->commentableType) {
            'post' => \App\Models\Post::class,
            'comment' => \App\Models\Comment::class,
            default => $this->commentableType,
        };
    }

    public function hasScope(): bool
    {
        return $this->commentableType !== null && $this->commentableId !== null;
    }
}
