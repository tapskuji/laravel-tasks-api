<?php

namespace App\Services;

use Illuminate\Http\Request;

class TaskQueryFilter
{

    protected array $columnMap = [
        'dueDate' => 'due_date',
        'createdAt' => 'created_at',
        'updatedAt' => 'updated_at',
    ];

    public function transform(array $args): array
    {
        $sortValue = $args['sort'] ?? false;
        if ($sortValue) {
            $column = $this->columnMap[$sortValue] ?? $sortValue;
            $args['sort'] = $column;
        }
        return $args;
    }

}
