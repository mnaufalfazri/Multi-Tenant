<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Ticket\TicketAssignRequest;
use App\Http\Requests\Ticket\TicketIndexRequest;
use App\Http\Requests\Ticket\TicketStoreRequest;
use App\Http\Requests\Ticket\TicketUpdateRequest;
use App\Models\Ticket;
use App\Models\WorkspaceMember;
use App\Models\Workspaces;
use App\Support\ApiResponse;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class TicketController extends Controller
{
    public function index(TicketIndexRequest $request, Workspaces $workspace)
    {
        $this->authorize('viewAny', [Ticket::class, $workspace]);

        $perPage = max(1, min((int) $request->query('per_page', 15), 100));
        $filters = $request->validated();
        $userId = $request->user()->id;

        $query = Ticket::query()->where('workspace_id', $workspace->id);

        $this->applyFilters($query, $filters, $userId);
        $this->applySort($query, (string) ($filters['sort'] ?? '-created_at'));

        $paginator = $query->with('assignee:id,name,email')
            ->paginate($perPage)
            ->withQueryString();

        return ApiResponse::paginated($paginator, fn (Ticket $t) => $this->resource($t));
    }

    public function store(TicketStoreRequest $request, Workspaces $workspace)
    {
        $this->authorize('create', [Ticket::class, $workspace]);

        $ticket = DB::transaction(function () use ($workspace, $request) {
            $nextNumber = Ticket::query()
                ->where('workspace_id', $workspace->id)
                ->lockForUpdate()
                ->max('number');

            $nextNumber = ((int) $nextNumber) + 1;

            return Ticket::create([
                'workspace_id' => $workspace->id,
                'number' => $nextNumber,
                'subject' => $request->validated('subject'),
                'description' => $request->validated('description'),
                'priority' => $request->validated('priority') ?? 'medium',
                'status' => 'OPEN',
                'requester_name' => $request->validated('requester_name'),
                'requester_email' => $request->validated('requester_email'),
            ]);
        });

        $ticket->load('assignee:id,name,email');

        return ApiResponse::data($this->resource($ticket), 201);
    }

    public function show(TicketIndexRequest $request, Workspaces $workspace, Ticket $ticket)
    {
        // scopeBindings sudah memastikan ticket milik workspace => cross-tenant 404
        $this->authorize('view', $ticket);

        $ticket->load('assignee:id,name,email');

        return ApiResponse::data($this->resource($ticket));
    }

    public function update(TicketUpdateRequest $request, Workspaces $workspace, Ticket $ticket)
    {
        $this->authorize('update', $ticket);

        $ticket->fill($request->validated());

        if ($request->has('status') && in_array($ticket->status, ['RESOLVED', 'CLOSED'], true)) {
            $ticket->resolved_at = $ticket->resolved_at ?? now();
        }

        $ticket->save();
        $ticket->load('assignee:id,name,email');

        return ApiResponse::data($this->resource($ticket));
    }

    public function assign(TicketAssignRequest $request, Workspaces $workspace, Ticket $ticket)
    {
        $this->authorize('assign', $ticket);

        $assignedTo = $request->validated('assigned_to');

        if ($assignedTo !== null) {
            $assigneeMembership = WorkspaceMember::query()
                ->where('workspace_id', $workspace->id)
                ->where('user_id', $assignedTo)
                ->first();

            if (! $assigneeMembership) {
                abort(404); // no leak
            }

            if ($assigneeMembership->role === 'viewer') {
                return ApiResponse::data(['message' => 'Cannot assign ticket to viewer.'], 422);
            }
        }

        $ticket->assigned_to = $assignedTo;
        $ticket->save();
        $ticket->load('assignee:id,name,email');

        return ApiResponse::data($this->resource($ticket));
    }

    public function close(TicketIndexRequest $request, Workspaces $workspace, Ticket $ticket)
    {
        $this->authorize('close', $ticket);

        $ticket->status = 'CLOSED';
        $ticket->resolved_at = $ticket->resolved_at ?? now();
        $ticket->save();
        $ticket->load('assignee:id,name,email');

        return ApiResponse::data($this->resource($ticket));
    }

    // -------------------- helpers --------------------

    private function applyFilters(Builder $query, array $filters, int $userId): void
    {
        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (! empty($filters['priority'])) {
            $query->where('priority', $filters['priority']);
        }

        if (! empty($filters['assigned_to'])) {
            $assigned = (string) $filters['assigned_to'];

            if ($assigned === 'me') {
                $query->where('assigned_to', $userId);
            } elseif ($assigned === 'unassigned') {
                $query->whereNull('assigned_to');
            } elseif (ctype_digit($assigned)) {
                $query->where('assigned_to', (int) $assigned);
            }
        }

        if (! empty($filters['search'])) {
            $term = trim((string) $filters['search']);
            $query->where(function (Builder $q) use ($term) {
                $q->where('subject', 'like', "%{$term}%")
                    ->orWhere('description', 'like', "%{$term}%")
                    ->orWhere('requester_email', 'like', "%{$term}%");
            });
        }
    }

    private function applySort(Builder $query, string $sort): void
    {
        $dir = 'asc';
        $field = $sort;

        if (str_starts_with($sort, '-')) {
            $dir = 'desc';
            $field = substr($sort, 1);
        }

        $allowed = ['created_at', 'updated_at', 'number', 'priority', 'status'];
        if (! in_array($field, $allowed, true)) {
            $field = 'created_at';
            $dir = 'desc';
        }

        $query->orderBy($field, $dir);
    }

    private function nextTicketNumberLocked(int $workspaceId): int
    {
        $max = Ticket::query()
            ->where('workspace_id', $workspaceId)
            ->lockForUpdate()
            ->max('number');

        return ((int) $max) + 1;
    }

    private function resource(Ticket $t): array
    {
        return [
            'id' => $t->id,
            'workspace_id' => $t->workspace_id,
            'number' => $t->number,
            'subject' => $t->subject,
            'description' => $t->description,
            'status' => $t->status,
            'priority' => $t->priority,
            'requester_name' => $t->requester_name,
            'requester_email' => $t->requester_email,
            'assigned_to' => $t->assigned_to,
            'assignee' => $t->relationLoaded('assignee') && $t->assignee ? [
                'id' => $t->assignee->id,
                'name' => $t->assignee->name,
                'email' => $t->assignee->email,
            ] : null,
            'resolved_at' => $t->resolved_at,
            'created_at' => $t->created_at,
            'updated_at' => $t->updated_at,
        ];
    }
}
