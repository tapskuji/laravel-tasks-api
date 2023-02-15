<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\GetTaskRequest;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\TaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskCollection;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Services\TaskQueryFilter;
use Illuminate\Http\Response;

class TaskController extends Controller
{
    public function index(GetTaskRequest $request, TaskQueryFilter $filter)
    {
        $validated = $request->validated();
        $filters = $filter->transform($validated);
        $tasks = Task::where('user_id', $request->user()->id)
            ->filter($filters)
            ->get();
        return new TaskCollection($tasks);
    }

    public function show(Task $task, TaskRequest $request)
    {
        return new TaskResource($task);
    }

    public function store(StoreTaskRequest $request)
    {
        $validated = $request->validated();

        $user = $request->user();
        $task = Task::create([
            'user_id' => $user->id,
            'title' => $validated['title'],
            'description' => $validated['description'],
            'completed' => $validated['completed'],
            'due_date' => $validated['dueDate'],
        ]);

        return (new TaskResource($task))->response()->setStatusCode(Response::HTTP_CREATED);
    }

    public function update(Task $task, UpdateTaskRequest $request)
    {
        $task->update($request->only(['title', 'description', 'completed', 'due_date']));
        return new TaskResource($task);
    }

    public function delete(Task $task, TaskRequest $request)
    {
        $task->delete();
        return response()->json();
    }
}
