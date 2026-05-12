<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class TeamDeviceSession extends Model
{
    protected $fillable = [
        'team_id',
        'team_no',
        'kode_team',
        'device_id',
        'device_name',
        'platform',
        'user_agent',
        'ip_address',
        'token_identifier',
        'last_login_at',
        'last_seen_at',
        'expires_at',
        'is_active',
    ];

    protected $casts = [
        'last_login_at' => 'datetime',
        'last_seen_at' => 'datetime',
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function scopeActive(Builder $query): Builder
    {
        return $query
            ->where('is_active', true)
            ->where(function (Builder $builder) {
                $builder
                    ->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            });
    }
}
