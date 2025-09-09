<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'body' => $this->body,
            'author' => new UserResource($this->whenLoaded('user')),
            'commentable' => [
                'type' => class_basename($this->commentable_type),
                'id' => $this->commentable_id,
            ],
            'replies_count' => $this->whenCounted('replies'),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
