<?php

namespace App\Http\Requests\Post;

use App\Enums\PostStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class PostUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'status' => ['sometimes', new Enum(PostStatus::class)],
            'body'   => ['sometimes','string'],
        ];
    }
}
