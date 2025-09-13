<?php

namespace App\Services\Contracts;

use App\DTO\Common\PageParams;
use App\DTO\User\UserCreateDto;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface UserServiceInterface
{
    public function paginate(PageParams $params): LengthAwarePaginator;

    public function create(UserCreateDto $dto): User;

    public function update(User $user, array $data): User;

    public function delete(User $user): void;
}
