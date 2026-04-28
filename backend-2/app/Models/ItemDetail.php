<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemDetail extends Model {

    protected $table = 'ISITEAMITEMDETAIL';

    protected $primaryKey = 'ISITEAMITEMDETAIL_ID';

    public $timestamps = false;

    public $incrementing = false;

    protected $keyType = 'string';
    protected $connection = 'sqlsrv';

    protected $fillable = [

        'ISITEAMITEMDETAIL_ID',
        'ISITEAMITEMDETAIL_ISITEAMITEM_ID',
        'ISITEAMITEMDETAIL_BRG_ID',
        'ISITEAMITEMDETAIL_SEQ',
        'ISITEAMITEMDETAIL_BRG_CODE',
        'ISITEAMITEMDETAIL_BRG_NAME',
        'ISITEAMITEMDETAIL_BRG_BARCODE',
        'ISITEAMITEMDETAIL_BRG_UOM_ID',
        'DATE_CREATE',
        'DATE_MODIFY',
        'ISITEAMITEMDETAIL_QTY',
        'ISITEAMITEMDETAIL_FLAG',
        'USER_CREATE',
        'USER_MODIFY',
        'ISITEAMITEMDETAIL_PRICE',
        'ISITEAMITEMDETAILID_IS_NOEDIT',
        'YEAR',
        'ISITEAMITEMDETAIL_DESC'
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'ISITEAMITEMDETAIL_BRG_ID', 'BARANG_ID');
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'ISITEAMITEMDETAIL_ISITEAMITEM_ID', 'ISITEAMITEM_ID');
    }

    public function logs()
    {
        return $this->hasMany(DetailLog::class, 'LOG_ISITEAMITEMDETAIL_ID', 'ISITEAMITEMDETAIL_ID');
    }

}