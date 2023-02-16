<?php

namespace App\Actions;

use App\Models\Task;
use App\Services\CacheKeyService;
use App\Services\TaskQueryFilter;
use Illuminate\Support\Facades\Cache;

class GetTasksAction
{

    public function __construct(private TaskQueryFilter $filter)
    {
    }

    public function get(int $userId, array $validated)
    {
        $filters = $this->filter->transform($validated);

        if (!empty($filters)) {
            return Task::where('user_id', $userId)
                ->filter($filters)
                ->get();
        }

        return Cache::rememberForever(CacheKeyService::generateKey($userId), function () use ($userId) {
            return Task::where('user_id', $userId)->get();
        });
    }
}
