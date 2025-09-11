<?php

namespace App\Http\Requests\Comment;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="CommentUpdateRequest",
 *     type="object",
 *     @OA\Property(property="body", type="string", example="Обновленный текст комментария")
 * )
 */
class CommentUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'body' => ['sometimes','string'],
        ];
    }
}
