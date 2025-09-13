<?php

namespace App\DTO\User;

use App\Http\Requests\Auth\RegisterRequest;

final readonly class RegisterUserDto
{
    public function __construct(
        public string $name,
        public string $email,
        public string $plainPassword
    ) {}

    public static function fromRequest(RegisterRequest $request): self
    {
        $data = $request->validated();

        return new self(
            $data['name'],
            $data['email'],
            $data['password']
        );
    }
}
