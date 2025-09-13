<?php

namespace App\Repositories\Contracts;

use App\DTO\Common\PageParams;
use App\DTO\User\UserCreateDto;
use App\DTO\User\UserUpdateDto;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface UserRepositoryInterface
{
    public function paginate(PageParams $params): LengthAwarePaginator;

    public function create(UserCreateDto $dto, string $passwordHash): User;

    public function update(User $user, UserUpdateDto $dto, ?string $passwordHash = null): User;

    public function delete(User $user): void;

    public function findByEmail(string $email): ?User;
}
