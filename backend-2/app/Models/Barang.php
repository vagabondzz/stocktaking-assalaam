<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model {

    protected $table = 'BARANG';

    protected $primaryKey = 'BARANG_ID';

    public $timestamps = false;

    public $incrementing = false;

    protected $keyType = 'string';
    protected $connection = 'sqlsrv';

    protected $fillable = [

        'BARANG_ID',
        'BRG_CODE',
        'BRG_NAME',
        'BRG_MERK',
        'BRG_ALIAS',
        'BRG_CATALOG',
        'BRG_IS_ACTIVE',
        'BRG_IS_STOCK',
        'BRG_IS_CS',
        'BRG_IS_GALON',
        'BRG_IS_DEPOSIT',
        'BRG_TPBRG_ID',
        'BRG_KAT_ID',
        'BRG_PJK_ID',
        'BRG_UOM_WEIGHT',
        'BRG_UOM_VOLUME',
        'BRG_UOM_DIAMETER',
        'BRG_WIDTH',
        'BRG_LENGTH',
        'BRG_HEIGHT',
        'BRG_EXPIRE_TIME',
        'BRG_IS_DECIMAL',
        'BRG_IS_BUILD',
        'BRG_IS_VALIDATE',
        'BRG_IS_DISC_GMC',
        'BRG_DEFAULT_MARK_UP',
        'BRG_VALIDATE_USR_ID',
        'BRG_VALIDATE_USR_UNT_ID',
        'BRG_MERCHAN_ID',
        'BRG_CODE_PURCHASE',
        'BRG_IS_PJK_INCLUDE',
        'OP_CREATE',
        'DATE_CREATE',
        'OP_MODIFY',
        'DATE_MODIFY',
        'BRG_MERCHANGRUP_ID',
        'OPC_UNIT',
        'OPM_UNIT',
        'BRG_HARGA_AVERAGE',
        'SAFETY_STOCK',
        'BRG_PKM_AVERAGE',
        'BRG_GALON_CODE',
        'AUTHOR_ID',
        'PUBLISHER_ID',
        'BRG_HO_AUTHORIZE',
        'BRG_IS_BASIC',
        'BRG_LASTCOST',
        'BRG_NILAI_RAFAKSI',
        'BRG_QTY_RAFAKSI',
        'REF$KATEGORI_ID',
        'REF$MERCHANDISE_ID',
        'REF$MERCHANDISE_GRUP_ID',
        'REF$TIPE_BARANG_ID',
        'MERK_ID',
        'REF$PAJAK_ID',
        'REF$OUTLET_ID',
        'REF$SATUAN_STOCK',
        'REF$SATUAN_PURCHASE',
        'REF$LOKASI_ID',
        'BRG_NAME_PURCHASE',
        'TEMP_STOCK',
        'TEMP_AVGSALES',
        'BRG_POS_LOOKUP',
        'BRG_MAX_STOCK',
        'BRG_POS_INACTIVE',
        'BRG_IS_BONUS',
        'USER_CREATE',
        'USER_MODIFY',
        'BRG_IS_NON_KPK',

    ];

    public function itemDetails()
    {
        return $this->hasMany(ItemDetail::class, 'ISITEAMITEMDETAIL_BRG_ID', 'BARANG_ID');
    }
    
    public function isDecimal()
{
    return (int) $this->BRG_IS_DECIMAL === 1;
} 
}