<?php

namespace App\DTO\Post;

use App\Enums\PostStatus;
use App\Http\Requests\Post\PostStoreRequest;

final readonly class PostCreateDto
{
    public function __construct(
        public string $body,
        public PostStatus $status,
        public int $userId,
    ) {}

    public static function fromRequest(PostStoreRequest $request): self
    {
        $v = $request->validated();

        return new self(
            body: (string)$v['body'],
            status: isset($v['status']) ? PostStatus::from($v['status']) : PostStatus::ACTIVE,
            userId: (int)$request->user()->id,
        );
    }

    public function toEloquentCreate(): array
    {
        return [
            'body' => $this->body,
            'status' => $this->status,
            'user_id' => $this->userId,
        ];
    }
}
