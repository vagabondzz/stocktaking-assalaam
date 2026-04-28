<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SatuanBarang extends Model {

    protected $table = 'REF$SATUAN';

    protected $primaryKey = 'REF$SATUAN_ID';

    public $timestamps = false;

    public $incrementing = false;

    protected $keyType = 'string';
    protected $connection = 'sqlsrv';

    protected $fillable = [

        'REF$SATUAN_ID',
        'SAT_CODE',
        'SAT_NAME',
        'SAT_GROUP',
        'OP_CREATE',
        'SAT_URUTAN',
        'DATE_CREATE',
        'OP_MODIFY',
        'DATE_MODIFY',
        'OPC_UNIT',
        'OPM_UNIT',
        'SAT_HO_AUTHORIZE',
    ];
}