<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskResources;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function index(Project $project)
    {
        $this->authorize('view', $project);
        return $project->tasks()->get();
    }

    public function store(TaskRequest $request, Project $project)
    {
        $this->authorize('update', $project);

        $validate = $request->validated();

        $validate['assigned_to'] = $validate['assigned_to'] ?? Auth::id();

        $task = $project->tasks()->create($validate);

        return response()->json([
            'status' => 'success',
            'message' => 'task created successfully',
            'task' => TaskResources::make($task),
        ]);
    }

    public function show(Task $task)
    {
        $this->authorize('view', $task);

        return TaskResources::make($task);
    }

    public function update(UpdateTaskRequest $request, Task $task)
    {
        $this->authorize('update', $task);

        $validate = $request->validated();

        $task->update($validate);

        return response()->json([
            'validate' => $validate,
            'status' => 'success',
            'message' => 'task updated successfully',
            'task' => TaskResources::make($task),
        ]);
    }

    public function destroy(Task $task)
    {
        $this->authorize('delete', $task);

        $task->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'task deleted successfully',
        ]);
    }
}
