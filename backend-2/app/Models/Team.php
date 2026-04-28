<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Team extends Authenticatable implements JWTSubject {

    protected $table = 'STOCK_OPNAME_TEAM';

    protected $primaryKey = 'STOCK_OPNAME_TEAM_ID';

    public $timestamps = false;

    public $incrementing = false;

    protected $keyType = 'string';
    protected $connection = 'sqlsrv';

    protected $fillable = [
        'STOCK_OPNAME_TEAM_ID',
        'SOPT_NO',
        'SOPT_PENGHITUNG',
        'SOPT_HELPER',
        'SOPT_SAKSI',
        'SOPT_CHECKER',
        'DATE_CREATE',
        'DATE_MODIFY',
        'KODE_TEAM',
        'SOPT_KOODINATOR_ID',
        'SO_NAMA_KOOR',
        'YEAR'
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function items() {
       return $this->hasMany(Item::class, 'ISITEAMITEM_TEAM_ID', 'STOCK_OPNAME_TEAM_ID');
    }
    
}
