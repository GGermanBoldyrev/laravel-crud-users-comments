<?php

namespace App\DTO\Comment;

use App\Http\Requests\Comment\CommentStoreRequest;
use App\Mapper\CommentableTypeMapper;

final readonly class CommentCreateDto
{
    public function __construct(
        public int $userId,
        public string $body,
        public string $commentableTypeAlias,
        public int $commentableId,
    ) {}

    public static function fromRequest(CommentStoreRequest $request): self
    {
        $v = $request->validated();

        return new self(
            userId: (int)$request->user()->id,
            body: (string)$v['body'],
            commentableTypeAlias: \strtolower(\trim($v['commentable_type'])),
            commentableId: (int)$v['commentable_id'],
        );
    }

    public function eloquentType(): string
    {
        return CommentableTypeMapper::toModel($this->commentableTypeAlias);
    }
}
