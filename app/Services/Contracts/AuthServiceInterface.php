<?php

namespace App\Services\Contracts;

use App\DTO\User\LoginDto;
use App\DTO\User\RegisterUserDto;
use App\Models\User;

interface AuthServiceInterface
{
    public function register(RegisterUserDto $dto): array;

    public function login(LoginDto $dto): ?array;

    public function logout(User $user): void;
}
