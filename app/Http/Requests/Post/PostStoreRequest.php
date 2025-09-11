<?php

namespace App\Http\Requests\Post;

use App\Enums\PostStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

/**
 * @OA\Schema(
 *     schema="PostStoreRequest",
 *     type="object",
 *     required={"body"},
 *     @OA\Property(property="body", type="string", example="Содержимое нового поста"),
 *     @OA\Property(property="status", type="string", enum={"active", "inactive"}, example="active")
 * )
 */
class PostStoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'status'  => ['sometimes', new Enum(PostStatus::class)],
            'body'    => ['required','string'],
        ];
    }
}
