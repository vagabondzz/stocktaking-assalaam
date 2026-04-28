<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StocktakingProgressDraft extends Model
{
    protected $fillable = [
        'draft_date',
        'kode_lokasi',
        'team_id',
        'team_no',
        'kode_team',
        'penghitung_1',
        'penghitung_2',
        'total_items',
        'last_activity_at',
    ];

    protected $casts = [
        'draft_date' => 'date',
        'last_activity_at' => 'datetime',
    ];

    public function items()
    {
        return $this->hasMany(StocktakingProgressDraftItem::class);
    }
}
