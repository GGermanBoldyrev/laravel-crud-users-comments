<?php

namespace App\DTO\User;

use App\Http\Requests\Auth\LoginRequest;

final readonly class LoginDto
{
    public function __construct(
        public string $email,
        public string $plainPassword
    ) {}

    public static function fromRequest(LoginRequest $request): self
    {
        $data = $request->validated();

        return new self(
            $data['email'],
            $data['password']
        );
    }
}
