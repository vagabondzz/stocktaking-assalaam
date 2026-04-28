<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipeBarang extends Model {

    protected $table = 'REF$TIPE_BARANG';

    protected $primaryKey = 'REF$TIPE_BARANG_ID';

    public $timestamps = false;

    public $incrementing = false;

    protected $keyType = 'string';
    protected $connection = 'sqlsrv';

    protected $fillable = [

       'REF$TIPE_BARANG_ID',
       'TPBRG_CODE',
       'TPBRG_NAME',
       'OP_CREATE',
       'DATE_CREATE',
       'OP_MODIFY',
       'DATE_MODIFY',
       'OPC_UNIT',
       'OPM_UNIT',
       'ISPATRACONTRABON',
       'TPBRG_REKENING_HUTANG_ID'
    ];
}