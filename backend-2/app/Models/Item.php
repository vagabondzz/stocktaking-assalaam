<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model {

    protected $table = 'ISITEAMITEM';

    protected $primaryKey = 'ISITEAMITEM_ID';

    public $timestamps = false;

    public $incrementing = false;

    protected $keyType = 'string';
    protected $connection = 'sqlsrv';

    protected $fillable = [

    'ISITEAMITEM_ID',
    'ISITEAMITEM_ISITEAM_ID',
    'ISITEAMITEM_LOKASI_ID',
    'ISITEAMITEM_TEAM_ID',
    'ISITEAMITEM_POSISI',
    'ISITEAMITEM_RAK',
    'ISITEAMITEM_SHELV',
    'ISITEAMITEM_NOTEAM',
    'ISITEAMITEM_NOFORM',
    'ISITEAMITEM_KODELOKASI',
    'DATE_CREATE',
    'DATE_MODIFY',
    'ISITEAMITEM_STATUS',
    'ISITEAMITEM_NOFORM_BACKUP',
    'ISITEAMITEM_IS_SORTING',
    'ISITEAMITEM_DATE_SORTING',
    'DATE_OPEN',
    'ISITEAMITEM_USER_OPEN',
    'ISITEAMITEM_IS_OPEN',
    'ISITEAMITEM_IS_NOEDIT',
    'DATE_PRINT',
    'ISITEAMITEM_PRINT_KE',
    'ISITEAMITEM_USER_PRINT',
    'YEAR'

    ];

    public function team() {
        return $this->belongsTo(Team::class, 'ISITEAMITEM_TEAM_ID', 'STOCK_OPNAME_TEAM_ID');
    }

    public function details()
    {
        return $this->hasMany(ItemDetail::class, 'ISITEAMITEMDETAIL_ISITEAMITEM_ID', 'ISITEAMITEM_ID');
    }
    
}
