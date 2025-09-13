<?php

namespace App\DTO\Post;

use App\Enums\PostStatus;
use App\Http\Requests\Post\PostUpdateRequest;

final readonly class PostUpdateDto
{
    public function __construct(
        public ?string $body,
        public ?PostStatus $status,
    ) {}

    public static function fromRequest(PostUpdateRequest $request): self
    {
        $v = $request->validated();

        return new self(
            body: $v['body'] ?? null,
            status: isset($v['status']) ? PostStatus::from($v['status']) : null,
        );
    }

    public function toEloquentUpdate(): array
    {
        $out = [];

        if ($this->body !== null) {
            $out['body'] = $this->body;
        }
        if ($this->status !== null) {
            $out['status'] = $this->status;
        }

        return $out;
    }
}
