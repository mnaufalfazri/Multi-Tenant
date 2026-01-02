<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Sanctum\HasApiTokens;

class Workspaces extends Model
{
    use HasApiTokens, HasFactory, Notifiable, Notifiable, TwoFactorAuthenticatable;

    protected $table = 'workspaces';

    protected $fillable = [
        'name',
        'slug',
        'owner_id'
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function tickets(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\Ticket::class);
    }
}
