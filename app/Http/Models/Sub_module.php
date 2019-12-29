<?php

namespace Travelestt\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sub_module extends Model
{
    use SoftDeletes;
    /**
     * Modelo sub_module, donde se almacenan los sub_modulos, asociados a los módulos, accesibles por los usuarios
     *
     * @return \Illuminate\Http\Response
     */
    protected $table = 'submodule';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $fillable = [
        'name',
        'description',
        'id'
    ];

    //Relación módulo
    public function module()
    {
        return $this->belongsTo('Travelestt\Models\Module', 'id', 'id');
    }

    //Relacion permisos de usuario
    public function permission()
    {
        return $this->belongsToMany('App\Models\User','permissionuser')
                    ->withPivot('id','id');
    }

    //Formateo de las consultas de los sub-módulos
    public function scopeFormatSubModule($query)
    {
        return $query->select('id as id', 'name as name', 'description as description', 'id')
                        ->with(['module' => function($query) {
                            $query->select('id', 'name as name', 'description as description');
                        }])
                        ->select('id as id', 'name as name', 'description as description', 'id')->with(['module' => function($query) {
                            $query->select('id', 'name as name', 'description as description');
                        }]);
    }
}
