<?php

namespace App\DTO\Comment;

use App\Mapper\CommentableTypeMapper;
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

    public function eloquentType(): string
    {
        return CommentableTypeMapper::toModel($this->commentableType);
    }

    public function hasScope(): bool
    {
        return $this->commentableType !== null && $this->commentableId !== null;
    }
}
