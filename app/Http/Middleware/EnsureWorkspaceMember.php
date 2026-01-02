<?php

namespace App\Http\Middleware;

use App\Models\WorkspaceMember;
use App\Models\Workspaces;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureWorkspaceMember
{
    public function handle(Request $request, Closure $next): Response
    {
        $workspace = $request->route('workspace');

        if (! $workspace instanceof Workspaces) {
            return $next($request);
        }

        $user = $request->user();
        if (! $user) {
            abort(401);
        }

        $membership = WorkspaceMember::query()
            ->where('workspace_id', $workspace->id)
            ->where('user_id', $user->id)
            ->first();

        if (! $membership) {
            abort(404);
        }

        $request->attributes->set('workspaceMembership', $membership);

        return $next($request);
    }
}
