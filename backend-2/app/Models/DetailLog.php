<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailLog extends Model {

    protected $table = 'ISITEAMITEMDETAILLOG';

    protected $primaryKey = 'ISITEAMITEMDETAILLOG_ID';

    public $timestamps = false;

    public $incrementing = false;

    protected $keyType = 'string';
    protected $connection = 'sqlsrv';

    protected $fillable = [
        'ISITEAMITEMDETAILLOG_ID',
        'LOG_BRG_ID',
        'LOG_FLAG',
        'LOG_ISITEAMITEMDETAIL_ID',
        'LOG_NEWQTY',
        'DATE_CREATE',
        'DATE_MODIFY',
        'USER_CREATE',
        'USER_MODIFY'
    ];

    public function itemDetail()
    {
        return $this->belongsTo(ItemDetail::class, 'LOG_ISITEAMITEMDETAIL_ID', 'ISITEAMITEMDETAIL_ID');
    }

    public function barang()
{
    return $this->belongsTo(Barang::class, 'LOG_BRG_ID', 'BARANG_ID');
}


}
