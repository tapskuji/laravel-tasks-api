<?php

namespace App\Http\Controllers\Api;

use App\Actions\GetTasksAction;
use App\Actions\StoreTaskAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\GetTaskRequest;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\TaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskCollection;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Services\CacheKeyService;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class TaskController extends Controller
{

    public function index(GetTaskRequest $request, GetTasksAction $action)
    {
        $validated = $request->validated();
        $userId = $request->user()->id;
        $tasks = $action->get($userId, $validated);
        return new TaskCollection($tasks);
    }

    public function show(Task $task, TaskRequest $request)
    {
        return new TaskResource($task);
    }

    public function store(StoreTaskRequest $request, StoreTaskAction $action)
    {
        $validated = $request->validated();
        $task = $action->createTask($request->user()->id, $validated);
        return (new TaskResource($task))->response()->setStatusCode(Response::HTTP_CREATED);
    }

    public function update(Task $task, UpdateTaskRequest $request)
    {
        $task->update($request->only(['title', 'description', 'completed', 'due_date']));
        Cache::forget(CacheKeyService::generateKey($request->user()->id));
        return new TaskResource($task);
    }

    public function delete(Task $task, TaskRequest $request)
    {
        $task->delete();
        Cache::forget(CacheKeyService::generateKey($request->user()->id));
        return response()->json();
    }
}
