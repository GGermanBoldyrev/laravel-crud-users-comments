<?php

namespace App\Services;

use App\DTO\User\LoginDto;
use App\DTO\User\RegisterUserDto;
use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Services\Contracts\AuthServiceInterface;
use App\Services\Traits\TokenManager;
use Illuminate\Support\Facades\Hash;

class AuthService implements AuthServiceInterface
{
    use TokenManager;

    public function __construct(private readonly UserRepositoryInterface $userRepository) {}

    public function register(RegisterUserDto $dto): array
    {
        $user = User::create([
            'name' => $dto->name,
            'email' => $dto->email,
            'password' => Hash::make($dto->plainPassword),
        ]);

        $token = $this->generateToken($user);

        return [
            'user' => $user,
            'token' => $token,
        ];
    }

    public function login(LoginDto $dto): ?array
    {
        $user = $this->userRepository->findByEmail($dto->email);

        if (!$user || !Hash::check($dto->plainPassword, $user->password)) {
            return null;
        }

        $token = $this->generateToken($user);

        return [
            'user' => $user,
            'token' => $token,
        ];
    }

    public function logout(User $user): void
    {
        $this->revokeCurrentToken($user);
    }
}
