<?php

namespace App\DTO\Comment;

use App\Http\Requests\Comment\CommentUpdateRequest;

final readonly class CommentUpdateDto
{
    public function __construct(
        public string $body,
    ) {}

    public static function fromRequest(CommentUpdateRequest $request): self
    {
        $v = $request->validated();
        return new self(
            body: (string)$v['body'],
        );
    }

    public function toEloquentUpdate(): array
    {
        return [
            'body' => $this->body,
        ];
    }
}
