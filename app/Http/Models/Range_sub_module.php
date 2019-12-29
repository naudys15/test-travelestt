<?php

namespace Travelestt\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Range_sub_module extends Model
{
    use SoftDeletes;
    /**
     * Modelo Range_sub_module, donde se almacenan los rangos de acceso a los sub-modulos accesibles por los usuarios
     *
     * @return \Illuminate\Http\Response
     */
    protected $table = 'rangesubmodule';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $fillable = [
        'name',
        'description'
    ];

    //Relacion permisos de usuario
    public function permission()
    {
        return $this->belongsToMany('App\Models\User','permissionuser')
                    ->withPivot('id','id');
    }

    //Formateo de las consultas de los rangos de los sub-módulos
    public function scopeFormatRangeSubModule($query)
    {
        return $query->select('id as id', 'name as name', 'description as description');
    }
}
