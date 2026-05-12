<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeamDeviceLimit extends Model
{
    protected $fillable = [
        'team_id',
        'team_no',
        'kode_team',
        'max_devices',
    ];
}
