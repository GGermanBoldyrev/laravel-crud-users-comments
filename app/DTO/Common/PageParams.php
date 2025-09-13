<?php

namespace App\DTO\Common;

use Illuminate\Http\Request;

class PageParams
{
    public function __construct(
        public int $perPage,
        public int $page,
    ) {}

    public static function fromRequest(Request $request, int $defaultPerPage = 15, int $maxPerPage = 100): self
    {
        $perPage = (int) $request->integer('per_page', $defaultPerPage);
        $perPage = max(1, min($perPage, $maxPerPage));

        $page = (int) $request->integer('page', 1);
        $page = max(1, $page);

        return new self($perPage, $page);
    }
}
