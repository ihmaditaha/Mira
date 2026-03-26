<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProjectRequest;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use App\Models\Workspace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{

    public function index(Workspace $workspace)
    {
        $this->authorize('view', $workspace);
        return $workspace->projects()->get();
    }

    public function store(StoreProjectRequest $request, Workspace $workspace)
    {
        $this->authorize('create', $workspace);

        $project = $workspace->projects()->create([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'created successfully',
            'project' => ProjectResource::make($project),
        ], 201);
    }

    public function show(Project $project)
    {
        $this->authorize('view', $project);
        return ProjectResource::make($project);
    }

    public function update(UpdateProjectRequest $request, Project $project)
    {
        $this->authorize('update', $project);

        $project->update($request->only(['name', 'description']));

        return response()->json([
            'status' => 'success',
            'message' => 'project Updated Successfully',
            'project' => ProjectResource::make($project)
        ]);
    }

    public function destroy(Project $project)
    {
        $this->authorize('delete', $project);

        $project->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'project deleted successfully'
        ]);
    }
}
