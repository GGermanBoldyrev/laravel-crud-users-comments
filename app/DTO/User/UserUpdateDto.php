<?php

namespace App\DTO\User;

use App\Http\Requests\User\UserUpdateRequest;

final readonly class UserUpdateDto
{
    public function __construct(
        public ?string $name,
        public ?string $email,
        public ?string $plainPassword,
    ) {}

    public static function fromRequest(UserUpdateRequest $request): self
    {
        $v = $request->validated();

        return new self(
            name: $v['name']   ?? null,
            email: $v['email'] ?? null,
            plainPassword: $v['password'] ?? null,
        );
    }

    public function toEloquentUpdate(?string $passwordHash = null): array
    {
        $out = [];

        if ($this->name !== null) {
            $out['name'] = $this->name;
        }
        if ($this->email !== null) {
            $out['email'] = $this->email;
        }
        if ($passwordHash !== null) {
            $out['password'] = $passwordHash;
        }

        return $out;
    }
}
