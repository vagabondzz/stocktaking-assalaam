<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StocktakingReport extends Model
{
    protected $fillable = [
        'report_date',
        'report_datetime',
        'kode_lokasi',
        'item_id',
        'report_year',
        'team_id',
        'team_no',
        'kode_team',
        'penghitung_1',
        'penghitung_2',
        'total_items',
        'total_logs',
        'source_updated_at',
        'report_payload',
    ];

    protected $casts = [
        'report_date' => 'date',
        'report_datetime' => 'datetime',
        'source_updated_at' => 'datetime',
        'report_payload' => 'array',
    ];
}
