<?php

namespace App\Models\Traits;

use App\Models\Cupboard\Post;

trait PaginationTrait
{
    private function loadRequestResource($query, $perPage)
    {
        return [
            'per_page' => $perPage ?? 30,
            'current_page' => $query->currentPage(),
            'last_page' => $query->lastPage(),
            'first_item' => $query->firstItem(),
            'last_item' => $query->lastItem(),
            'total' => $query->total()
        ];
    }
}