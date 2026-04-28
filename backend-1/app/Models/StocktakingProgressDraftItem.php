<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StocktakingProgressDraftItem extends Model
{
    protected $fillable = [
        'stocktaking_progress_draft_id',
        'item_identity_key',
        'item_detail_id',
        'barang_id',
        'kode_plu',
        'kode_barcode',
        'nama_barang',
        'uom',
        'draft_action',
        'is_counted',
        'draft_qty',
        'draft_note',
        'source_updated_at',
    ];

    protected $casts = [
        'is_counted' => 'boolean',
        'draft_qty' => 'decimal:3',
        'source_updated_at' => 'datetime',
    ];

    public function draft()
    {
        return $this->belongsTo(StocktakingProgressDraft::class, 'stocktaking_progress_draft_id');
    }
}
