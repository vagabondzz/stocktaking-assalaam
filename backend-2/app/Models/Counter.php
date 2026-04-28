<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemDetail extends Model {

    protected $table = 'ISITEAM';

    protected $primaryKey = 'ISITEAM_ID';

    public $timestamps = false;

    public $incrementing = false;

    protected $keyType = 'string';
    protected $connection = 'sqlsrv';

    protected $fillable = [

        'ISITEAM_ID',
        'ISITEAM_TEAM_ID',
        'ISITEAM_LOKASI_ID',
        'ISITEAM_KODEPOS',
        'ISITEAM_RAK',
        'ISITEAM_SHELVAWAL',
        'ISITEAM_SHELVAKHIR',
        'DATE_CREATE',
        'DATE_MODIFY',
        'YEAR',

    ];
}