<?php

namespace App\Repositories;

use App\DTO\Common\PageParams;
use App\DTO\User\UserCreateDto;
use App\DTO\User\UserUpdateDto;
use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class UserRepository implements UserRepositoryInterface
{
    public function paginate(PageParams $params): LengthAwarePaginator
    {
        return User::query()->latest()->paginate(...$params->toArgs());
    }

    public function create(UserCreateDto $dto, string $passwordHash): User
    {
        return User::create($dto->toEloquentCreate($passwordHash));
    }

    public function update(User $user, UserUpdateDto $dto, ?string $passwordHash = null): User
    {
        $user->fill($dto->toEloquentUpdate($passwordHash))->save();
        return $user;
    }

    public function delete(User $user): void
    {
        $user->delete();
    }

    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }
}
