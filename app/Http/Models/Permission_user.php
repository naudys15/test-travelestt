<?php

namespace Travelestt\Models;

use Illuminate\Database\Eloquent\Model;

class Permission_user extends Model
{
    /**
     * Modelo permission_user, donde se almacenan los modulos accesibles por los usuarios
     *
     * @return \Illuminate\Http\Response
     */
    protected $table = 'permissionuser';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'id',
        'id',
        'id'
    ];
}
