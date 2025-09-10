<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserStoreRequest;
use App\Http\Requests\User\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\Contracts\UserServiceInterface;
use Illuminate\Http\Resources\Json\ResourceCollection;

class UserController extends Controller
{
    public function __construct(private readonly UserServiceInterface $service) {}

    public function index(): ResourceCollection
    {
        $users = $this->service->paginate(perPage: request('per_page', 15));
        return UserResource::collection($users);
    }

    public function store(UserStoreRequest $request): UserResource
    {
        $user = $this->service->create($request->validated());
        return new UserResource($user);
    }

    public function show(User $user): UserResource
    {
        return new UserResource($user);
    }

    public function update(UserUpdateRequest $request, User $user): UserResource
    {
        $user = $this->service->update($user, $request->validated());
        return new UserResource($user);
    }

    public function destroy(User $user)
    {
        $this->service->delete($user);
        return response()->noContent();
    }
}
