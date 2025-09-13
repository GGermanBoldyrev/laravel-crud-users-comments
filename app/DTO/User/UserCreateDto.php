<?php

namespace App\DTO\User;

use App\Http\Requests\User\UserStoreRequest;

final readonly class UserCreateDto
{
    public function __construct(
        public string $name,
        public string $email,
        public string $plainPassword,
    ) {}

    public static function fromRequest(UserStoreRequest $request): self
    {
        $v = $request->validated();

        return new self(
            name: (string)$v['name'],
            email: (string)$v['email'],
            plainPassword: (string)$v['password'],
        );
    }

    public function toEloquentCreate(string $passwordHash): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'password' => $passwordHash,
        ];
    }
}
