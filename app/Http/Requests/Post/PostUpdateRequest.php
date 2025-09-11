<?php

namespace App\Http\Requests\Post;

use App\Enums\PostStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

/**
 * @OA\Schema(
 *     schema="PostUpdateRequest",
 *     type="object",
 *     @OA\Property(property="body", type="string", example="Обновленное содержимое поста"),
 *     @OA\Property(property="status", type="string", enum={"active", "inactive"}, example="active")
 * )
 */
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
