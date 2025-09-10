<?php

namespace App\Services;

use App\Models\User;
use App\Services\Contracts\UserServiceInterface;

class UserService implements UserServiceInterface
{
    public function paginate(int $perPage = 15): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return User::query()->latest()->paginate($perPage);
    }

    public function create(array $data): User
    {
        return User::create($data);
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
