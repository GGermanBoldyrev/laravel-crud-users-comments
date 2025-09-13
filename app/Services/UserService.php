<?php

namespace App\Services;

use App\DTO\Common\PageParams;
use App\DTO\User\UserCreateDto;
use App\Models\User;
use App\Services\Contracts\UserServiceInterface;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class UserService implements UserServiceInterface
{
    public function __construct(private readonly Hasher $hasher) {}

    public function paginate(PageParams $params): LengthAwarePaginator
    {
        return User::query()->latest()->paginate(...$params->toArgs());
    }

    public function create(UserCreateDto $dto): User
    {
        $hash = $this->hasher->make($dto->plainPassword);

        return User::create($dto->toEloquentCreate($hash));
    }

    public function update(User $user, array $data): User
    {
        $user->fill($data)->save();
        return $user;
    }

    public function delete(User $user): void
    {
        $user->delete();
    }
}
