<?php

namespace App\Services;

use App\DTO\Common\PageParams;
use App\DTO\User\UserCreateDto;
use App\DTO\User\UserUpdateDto;
use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Services\Contracts\UserServiceInterface;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class UserService implements UserServiceInterface
{
    public function __construct(
        private readonly UserRepositoryInterface $repository,
        private readonly Hasher $hasher
    ) {}

    public function paginate(PageParams $params): LengthAwarePaginator
    {
        return $this->repository->paginate($params);
    }

    public function create(UserCreateDto $dto): User
    {
        $hash = $this->hasher->make($dto->plainPassword);
        return $this->repository->create($dto, $hash);
    }

    public function update(User $user, UserUpdateDto $dto): User
    {
        $hash = $dto->plainPassword !== null
            ? $this->hasher->make($dto->plainPassword)
            : null;

        return $this->repository->update($user, $dto, $hash);
    }

    public function delete(User $user): void
    {
        $this->repository->delete($user);
    }
}
