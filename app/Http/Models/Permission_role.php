<?php

namespace Travelestt\Models;

use Illuminate\Database\Eloquent\Model;

class Permission_role extends Model
{
    /**
     * Modelo permission_role, donde se almacenan los modulos accesibles por los roles
     *
     * @return \Illuminate\Http\Response
     */
    protected $table = 'permissionrole';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'id',
        'id',
        'id'
    ];
}
