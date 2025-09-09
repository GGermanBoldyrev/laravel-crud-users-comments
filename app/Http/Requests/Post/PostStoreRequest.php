<?php

namespace App\Http\Requests\Post;

use App\Enums\PostStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class PostStoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'user_id' => ['required','integer','exists:users,id'],
            'status'  => ['sometimes', new Enum(PostStatus::class)],
            'body'    => ['required','string'],
        ];
    }
}
