<?php

namespace App\Http\Controllers;

use App\Http\Resources\WorkspaceResource;
use App\Models\Workspace;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class WorkspaceController extends Controller
{
    
    public function index()
    {
        $user = Auth::user();
        $workspaces = $user->workspaces();
        return response()
            ->json(WorkspaceResource::collection($workspaces), 201);
    }

    public function store(Request $request)
    {
        $this->authorize('create', Workspace::class);

        $workspace = Workspace::create([
            'name' => $request->name,
            'owner_id' => Auth::id(),
        ]);

        $workspace->users()->attach(Auth::id());

        return response()->json(WorkspaceResource::make($workspace), 201);
    }

    public function show(Workspace $workspace)
    {
        $this->authorize('view', $workspace);

        return WorkspaceResource::make($workspace);
    }

    public function update(Request $request, Workspace $workspace)
    {
        $this->authorize('update', $workspace);

        $workspace->update([
            'name' => $request->name,
        ]);

        return response()->json([
            WorkspaceResource::make($workspace)
        ]);
    }

    public function destroy(Workspace $workspace)
    {
        $this->authorize('delete', $workspace);

        $workspace->delete();

        return response()->json([
            'status' => 'success',
            'massage' => 'Workspace deleted',
        ]);
    }
}
