<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Workspace\StoreWorkspaceRequest;
use App\Models\WorkspaceMember;
use App\Models\Workspaces;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class WorkspacesController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $workspaces = $user->workspaces()
            ->orderBy('workspaces.created_at', 'desc')
            ->get();

        $data = $workspaces->map(function ($ws) {
            return [
                'name' => $ws->name,
                'slug' => $ws->slug,
                'owner-id' => $ws->owner_id,
            ];
        })->values();

        return response()->json([
            'data' => $data,
        ]);
    }

    public function show(Request $request, Workspaces $workspace)
    {
        $user = $request->user();

        $isMember = $user->workspaceMemberships()
            ->where('workspace_id', $workspace->id)
            ->exists();

        if (! $isMember) {
            abort(404);
        }

        return response()->json([
            'data' => [
                'name' => $workspace->name,
                'slug' => $workspace->slug,
                'owner-id' => $workspace->owner_id,
            ],
        ]);
    }

    public function createWorkspace(StoreWorkspaceRequest $request)
    {
        $user = $request->user();

        $name = $request->string('name')->toString();
        $slug = $request->input('slug')
            ? Str::slug($request->input('slug'))
            : Str::slug($name);

        $slug = $this->makeUniqueSlug($slug);

        $workspace = DB::transaction(function () use ($user, $name, $slug) {
            $ws = Workspaces::create([
                'name' => $name,
                'slug' => $slug,
                'owner_id' => $user->id,
            ]);

            WorkspaceMember::create([
                'workspace_id' => $ws->id,
                'user_id' => $user->id,
                'role' => 'owner',
                'joined_at' => now(),
            ]);

            return $ws;
        });

        return response()->json([
            'data' => [
                'id' => $workspace->id,
                'name' => $workspace->name,
                'slug' => $workspace->slug,
                'owner_id' => $workspace->owner_id,
                'role' => 'owner',
                'created_at' => $workspace->created_at,
                'updated_at' => $workspace->updated_at,
            ],
        ], 201);
    }

    private function makeUniqueSlug(string $slug): string
    {
        $base = $slug;
        $i = 1;

        while (Workspaces::query()->where('slug', $slug)->exists()) {
            $i++;
            $slug = "{$base}-{$i}";
        }

        return $slug;
    }
}
