<?php

namespace App\Services\Traits;

use App\Models\User;
use Carbon\Carbon;

trait TokenManager
{
    public function generateToken(User $user, string $tokenName = 'api', ?Carbon $expiresAt = null): string
    {
        $token = $user->createToken($tokenName, ['*'], $expiresAt);
        return $token->plainTextToken;
    }

    public function revokeCurrentToken(User $user): void
    {
        $user->currentAccessToken()?->delete();
    }
}
