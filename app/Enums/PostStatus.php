<?php

namespace App\Enums;

enum PostStatus: string
{
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';

    public function isActive(): bool
    {
        return $this === self::ACTIVE;
    }
}
