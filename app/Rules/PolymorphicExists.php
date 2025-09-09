<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Проверяет, что commentable_type есть в списке и запись с commentable_id существует.
 */
class PolymorphicExists implements ValidationRule
{
    private array $map;

    public function __construct(array $allowedMap)
    {
        $this->map = $allowedMap;
    }

    public function validate(string $attribute, mixed $value, \Closure $fail): void
    {
        $type = strtolower((string)$value);
        if (!array_key_exists($type, $this->map)) {
            $fail("Unsupported commentable type: {$type}.");
        }
    }

    public function checkId(string $type, int|string $id, \Closure $fail): void
    {
        $class = $this->map[strtolower($type)] ?? null;
        if (!$class || !$class::query()->whereKey($id)->exists()) {
            $fail("commentable_id not found for type {$type}.");
        }
    }
}
