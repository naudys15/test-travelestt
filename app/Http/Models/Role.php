<?php

namespace Travelestt\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use SoftDeletes;
    /**
     * Modelo role, donde se almacenan los roles de los usuarios en el sistema
     *
     * @return \Illuminate\Http\Response
     */
    protected $table = 'role';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $fillable = [
        'name',
        'description'
    ];

    //Relación usuario
    public function user()
    {
        return $this->hasMany('Travelestt\Models\User');
    }

    //Relacion permisos de role
    public function permission()
    {
        return $this->belongsToMany('Travelestt\Models\Role','permissionrole', 'id', 'id')
                    ->select('id', 'id');
    }

    //Formateo de las consultas de los roles
    public function scopeFormatRole($query)
    {
        return $query->select('id', 'name', 'description')
            ->with(['permission' => function($query) {
                $query
                    ->join('submodule', 'permissionrole.id', '=', 'submodule.id')
                    ->join('rangesubmodule', 'permissionrole.id', '=', 'rangesubmodule.id')
                    ->select('submodule.id as id_submodule', 'submodule.name as name_submodule', 'rangesubmodule.id as id_range', 'rangesubmodule.name as name_range')
                    ->orderBy('id_submodule', 'asc');
            }]);    
    }

}
