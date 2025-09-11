<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="UserStoreRequest",
 *     type="object",
 *     required={"name", "email", "password"},
 *     @OA\Property(property="name", type="string", example="John Doe"),
 *     @OA\Property(property="email", type="string", example="john@example.com"),
 *     @OA\Property(property="password", type="string", format="password", example="password123")
 * )
 */
class UserStoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required','string','max:255'],
        ];
    }
}
