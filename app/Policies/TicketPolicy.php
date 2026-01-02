<?php

namespace App\Policies;

use App\Models\Ticket;
use App\Models\User;
use App\Models\WorkspaceMember;
use App\Models\Workspaces;

class TicketPolicy
{
    public function viewAny(User $user, Workspaces $workspace): bool
    {
        return true; // membership sudah dijamin middleware
    }

    public function view(User $user, Ticket $ticket): bool
    {
        return true;
    }

    public function create(User $user, Workspaces $workspace): bool
    {

        $role = WorkspaceMember::query()
            ->where('workspace_id', $workspace->id)
            ->where('user_id', $user->id)
            ->value('role');

        \Log::info('TicketPolicy create check', [
            'workspace_id' => $workspace->id,
            'user_id' => $user->id,
            'role' => $role,
        ]);

        return in_array($role, ['owner', 'agent'], true);
    }

    public function update(User $user, Ticket $ticket): bool
    {
        $role = WorkspaceMember::query()
            ->where('workspace_id', $ticket->workspace_id)
            ->where('user_id', $user->id)
            ->value('role');

        return in_array($role, ['owner', 'agent'], true);
    }

    public function assign(User $user, Ticket $ticket): bool
    {
        return $this->update($user, $ticket);
    }

    public function close(User $user, Ticket $ticket): bool
    {
        return $this->update($user, $ticket);
    }
}
