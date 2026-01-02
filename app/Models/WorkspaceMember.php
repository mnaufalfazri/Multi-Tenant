<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkspaceMember extends Model
{
    use HasFactory;

    protected $table = 'workspace_members';

    protected $fillable = [
        'workspace_id',
        'user_id',
        'role',
        'joined_at',
    ];

    protected $casts = [
        'joined_at' => 'datetime',
    ];

    public function workspace(): BelongsTo
    {
        return $this->belongsTo(Workspaces::class, 'workspace_id');
        // Kalau nanti Anda rename model ke Workspace (singular), ganti ke Workspace::class
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Helper kecil (opsional, enak dipakai di policy)
    public function isOwner(): bool
    {
        return $this->role === 'owner';
    }

    public function isAgent(): bool
    {
        return $this->role === 'agent';
    }

    public function isViewer(): bool
    {
        return $this->role === 'viewer';
    }
}
